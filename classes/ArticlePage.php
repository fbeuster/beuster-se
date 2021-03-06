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
    $db           = Database::getDB();
    $content      = Parser::parse(trim($_POST['usrcnt']),
                                  Parser::TYPE_NEW);
    $cookie_user  = User::newFromCookie();

    if ($cookie_user) {
      $cookie_user->refreshCookies();

      $enable   = 2;
      $usermail = $cookie_user->getMail();
      $username = $cookie_user->getClearName();
      $website  = $cookie_user->getWebsite();

    } else {
      $enable   = 0;
      $usermail = strtolower(   trim( $_POST['usrml'] ) );
      $username = htmlentities( trim( $_POST['usr']   ) );
      $website  = htmlentities( trim( $_POST['usrpg'] ) );
    }

    $this->error = checkStandForm($username, $content, $usermail, $website,
                                  trim($_POST['date']), $_POST['email'],
                                  $_POST['homepage'], 'commentForm');

    $content      = remDoubles($content, array('[b]','[i]','[u]'));
    $reply_to     = checkReplyId(trim($_POST['reply']));

    $error_return = $this->article->getLink();

    if (isset($_POST['notifications_enabled'])) {
      $notifications_enabled = true;

    } else {
      $notifications_enabled = false;
    }

    if ($this->error == 0) {
      $new_user = true;
      $user_id  = 0;

      # exists user in db?
      $fields = array('id');
      $conds = array('LOWER(mail) = ?', 's', array($usermail));
      $res  = $db->select('users', $fields, $conds);

      if (count($res)) {
        $new_user = false;
        $user_id  = $res[0]['id'];

      } else {
        # add new user
        do {
          $token  = hash('sha256', microtime() + random_int(0, 1000));
          $fields = array('id');
          $conds  = array('token = ?', 's', array($token));
          $unique = $db->select('users', $fields, $conds);
        } while (count($unique) > 0);

        $fields   = array('username', 'rights', 'mail', 'registered', 'screen_name', 'website', 'token');
        $values   = array('sss&sss', array(
                      preg_replace('/[^A-Za-z0-9-_]/', '', $username),
                      'user', $usermail, 'NOW()', $username, $website, $token));
        $user_id  = $db->insert('users', $fields, $values);
      }

      # insert comment
      $fields = array('content', 'date', 'article_id', 'enabled', 'parent_comment_id', 'user_id', 'notifications');
      $values = array('s&iiiii', array(
                 $content, 'NOW()', $this->article_id, $enable, $reply_to, $user_id, $notifications_enabled ));
      $id     = $db->insert('comments', $fields, $values);

      if ($id) {
        # reload comments
        # TODO shold actually be article->reloadComments()
        $this->article = new Article($this->article_id);

        # notification mails
        $this->sendNotifications(new Comment($id));

        # add info
        $this->info = array('success',
                            I18n::t('comment.info.title'),
                            I18n::t('comment.info.message'));
      }

    } else {
      $this->values = array('user' => $username,
                            'cnt' => $content,
                            'mail' => $usermail,
                            'page' => $website,
                            'notifications_enabled' => $notifications_enabled);
    }
  }

  private function increaseHitCount() {
    if (!Utilities::isDevServer()) {
      $user = User::newFromCookie();

      if (!$user || ($user && !$user->isAdmin())) {
        $db   = Database::getDB()->getCon();
        $sql  = 'UPDATE
                  articles
                SET
                  hits = hits + 1
                WHERE
                  id = ?';

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
    $cookie_user    = User::newFromCookie();

    if ($this->article->getEnable()
        || (!$this->article->getEnable()
            && $cookie_user
            && $cookie_user->isAdmin())
        ) {
      $this->title    = $this->article->getTitle();
      $this->valid    = true;
      $this->increaseHitCount();

      if (count($this->article->getAttachments())) {
        $this->addScript('/system/assets/js/download.js');
        $token = null;

        if (!isset($_COOKIE['api_token'])) {
          $token = new ApiToken(true);

        } else {
          if (!ApiToken::isValid($_COOKIE['api_token'])) {
            ApiToken::delete($_COOKIE['api_token']);
            $token = new ApiToken(true);
          }
        }

        # adding article information to the token
        if ($token) {
          $extra = array('article_id' => $this->article_id);
          $extra = json_encode($extra);

          $token_string = $token->getString();

          $db  = Database::getDB()->getCon();
          $sql = "UPDATE  api_tokens
                  SET     extra = ?
                  WHERE   token = ?";

          if (!$stmt = $db->prepare($sql)) {
            return $db->error;
          }

          $stmt->bind_param('ss', $extra, $token_string);

          if (!$stmt->execute()) {
            return $stmt->error;
          }

          $stmt->close();
        }
      }
    }
  }

  private function sendNotifications($comment) {
    $notified   = array();

    # don't notify author of comment
    $notified[] = $comment->getAuthor()->getId();

    # notify admin about comment
    $admin_mail = MailService::getAdminNotificationMail();
    $admin      = User::newFromMail( $admin_mail );

    if (!in_array($admin->getId(), $notified)) {
      MailService::commentNotification($this->article, $comment);
      $notified[] = $admin->getId();
    }

    # notify users about answer
    if ($comment->getParentId() > 0) {

      $parent = new Comment($comment->getParentId());

      if (!in_array($parent->getAuthor()->getId(), $notified) &&
          $parent->notificationsEnabled()) {
        MailService::commentNotification($this->article, $comment, $parent->getAuthor());
        $notified[] = $comment->getAuthor()->getId();
      }

      $parent->loadReplies();

      # notify whole thread
      if ($parent->hasReplies()) {
        foreach ($parent->getReplies() as $reply) {
          if (!in_array($reply->getAuthor()->getId(), $notified) &&
              $reply->notificationsEnabled()) {
            MailService::commentNotification($this->article, $comment, $reply->getAuthor());
            $notified[] = $reply->getAuthor()->getId();
          }
        }
      }
    }
  }
}

?>
