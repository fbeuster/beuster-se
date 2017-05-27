<?php

class ArticlePage extends RequestPage {

  private $article;
  private $article_id;
  private $content;
  private $error;
  private $filename;
  private $info;
  private $title;
  private $type;
  private $valid;
  private $values;

  public function __construct() {
    $this->error    = 0;
    $this->filename = 'article.php';
    $this->info     = '';
    $this->type     = Page::ARTICLE_PAGE;
    $this->valid    = false;
    $this->values   = null;

    if (isset($_GET['n']) && is_numeric($_GET['n'])) {
      $this->article_id = $_GET['n'];
      $this->increaseHitCount();
      $this->loadPage();
      $this->setExpectedRequestMethod(RequestPage::METHOD_POST);
      $this->setRequestMethod($_SERVER['REQUEST_METHOD']);

      if ($this->hasValidRequest()) {
        $this->handleRequest();
      }
    }
  }

  public function getArticle() {
    return $this->article;
  }

  public function getCommentReply() {
    return isset($_GET['comment-reply']) ? $_GET['comment-reply'] : 'null';
  }

  public function getContent() {
    # unused
    return $this->content;
  }

  public function getError() {
    return $this->error;
  }

  public function getFilename() {
    return $this->filename;
  }

  public function getInfo() {
    return $this->info;
  }

  public function getLink() {
    return  Lixter::getLix()->getProtocol().
            '://'.$_SERVER['HTTP_HOST'].$this->article->getLink();
  }

  public function getParsedContent() {
    # unused
    return $this->content;
  }

  public function getTags() {
    return $this->article->getTagsString();
  }

  public function getTitle() {
    return $this->title;
  }

  public function getType() {
    return $this->type;
  }

  public function getValues() {
    return $this->values;
  }

  protected function handleRequest() {
    $db       = Database::getDB();
    $enable   = 0;
    $username = trim($_POST['usr']);
    $content  = Parser::parse(trim($_POST['usrcnt']), Parser::TYPE_NEW);
    $usermail = strtolower(trim($_POST['usrml']));
    $website  = trim($_POST['usrpg']);

    $this->error = checkStandForm($username, $content, $usermail, $website,
                                  trim($_POST['date']), $_POST['email'],
                                  $_POST['homepage'], 'commentForm');

    $content      = remDoubles($content, array('[b]','[i]','[u]'));
    $reply_to     = checkReplyId(trim($_POST['reply']));
    $cookie_user  = User::newFromCookie();

    if ($cookie_user && $cookie_user->isAdmin()) {
      $cookie_user->refreshCookies();
      $enable   = 2;
      $usermail = strtolower(ADMIN_GRAV_MAIL);
      $website  = ADMIN_WEBPAGE;
    }

    $error_return = $this->article->getLink();

    if ($this->error == 0) {
      $new_user = true;
      $user_id  = 0;

      # exists user in db?
      $fields = array('ID');
      $conds = array('LOWER(Email) = ?', 's', array($usermail));
      $res  = $db->select('users', $fields, $conds);

      if (count($res)) {
        $new_user = false;
        $user_id  = $res[0]['ID'];

      } else {
        # add new user
        $fields   = array('Name', 'Rights', 'Email', 'regDate', 'Clearname', 'Website');
        $values   = array('sss&ss', array(
                      preg_replace('/[^A-Za-z0-9-_]/', '', $username),
                      'user', $usermail, 'NOW()', $username, $website));
        $user_id  = $db->insert('users', $fields, $values);
      }

      # insert comment
      $fields = array('Inhalt', 'Datum', 'NewsID', 'Frei', 'ParentID', 'UID');
      $values = array('s&iiii', array(
                 $content, 'NOW()', $this->article_id, $enable, $reply_to, $user_id ));
      $res = $db->insert('kommentare', $fields, $values);

      # notification mails
      $this->sendNotifications($content, $username, $website, $reply_to);

      # add info
      $this->info = array('success',
                          I18n::t('comment.info.title'),
                          I18n::t('comment.info.message'));

      # reload comments
      # TODO shold actually be article->reloadComments()
      $this->article = new Article($this->article_id);

    } else {
      $this->values = array('user' => $username,
                            'cnt' => $content,
                            'mail' => $usermail,
                            'page' => $website);
    }
  }

  private function increaseHitCount() {
    if (!Utilities::isDevServer()) {
      $user = User::newFromCookie();

      if (!$user || ($user && !$user->isAdmin())) {
        $db   = Database::getDB()->getCon();
        $sql  = 'UPDATE
                  news
                SET
                  Hits = Hits + 1
                WHERE
                  ID = ?';

        if (!$stmt = $db->prepare($sql)) {
          return $db->error;
        }

        $stmt->bind_param('i', $this->article_id);

        if (!$stmt->execute()) {
          return $stmt->error;
        }

        $stmt->close();
      }
    }
  }

  public function isValid() {
    return $this->valid;
  }

  private function loadPage() {
    $this->article  = new Article($this->article_id);
    $this->title    = $this->article->getTitle();
    $this->valid    = true;
  }

  private function sendNotifications($user_content, $user_name, $user_page, $user_reply) {
    # notify admin about comment
    MailService::commentNotification(
        $this->title, $user_content, $user_name, $user_page );

    # notify users about answer
    if ($user_reply > 0) {
      $notified = array();
      $comment  = new Comment($user_reply);

      MailService::commentNotification(
          $this->title, $user_content, $user_name, $user_page,
          $comment->getAuthor()->getMail() );

      $notified[] = $comment->getAuthor()->getId();

      $comment->loadReplies();

      # notify whole thread
      if ($comment->hasReplies()) {
        foreach ($comment->getReplies() as $reply) {
          if (!in_array($reply->getAuthor()->getId(), $notified)) {
            MailService::commentNotification(
                $this->title, $user_content, $user_name, $user_page,
                $reply->getAuthor()->getMail() );

            $notified[] = $reply->getAuthor()->getId();
          }
        }
      }
    }
  }
}

?>