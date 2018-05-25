<?php

class PrivacySettingsPage extends Page {

  private $action     = 'privacy-settings';
  private $by_get     = false;
  private $comment    = null;
  private $error      = '';
  private $file_name  = 'privacy_settings.php';
  private $info       = array();
  private $type       = Page::GDPR_PAGE;
  private $user       = null;

  private static $page_name   = 'privacy-settings';

  public function __construct() {
    $this->addScript('system/assets/js/privacy_settings.js');
    $this->addStyle('system/assets/css/privacy_settings.css');

    if (isset($_POST) && isset($_POST['disable_notifications'])) {
      $this->validateDisableNotificationRequest();
      $this->disableNotifications();

    } else {
      if ($this->isNotificationDisablePage()) {
        $this->validateDisableNotificationRequest();
      }
    }
  }

  private function disableNotifications() {
    $db             = Database::getDB()->getCon();
    $comment_id     = $this->comment->getId();
    $notifications  = false;
    $user_id        = $this->user->getId();


    $disable_thread_notifications
      = isset($_POST['disable_thread_notifications'])
        ? $_POST['disable_thread_notifications']
        : false;

    $disable_all_notifications
      = isset($_POST['disable_all_notifications'])
        ? $_POST['disable_all_notifications']
        : false;

    if ($disable_thread_notifications) {
      $sql = 'UPDATE
                comments
              SET
                notifications = ?
              WHERE
                user_id = ? AND
                ( parent_comment_id = ? OR
                  id = ?)';
      $stmt = $db->prepare($sql);

      if (!$stmt) {
        $this->error = $db->error;
      }

      $stmt->bind_param('iiii',
                        $notifications, $user_id,
                        $comment_id, $comment_id);
      if (!$stmt->execute()) {
        $this->error = $stmt->error;
      }

      $stmt->close();
    }

    if ($disable_all_notifications) {
      $sql = 'UPDATE
                comments
              SET
                notifications = ?
              WHERE
                user_id = ?';
      $stmt = $db->prepare($sql);

      if (!$stmt) {
        $this->error = $db->error;
      }

      $stmt->bind_param('ii', $notifications, $user_id);
      if (!$stmt->execute()) {
        $this->error = $stmt->error;
      }

      $stmt->close();
    }

    if ($this->error == '') {
      $this->info = array('success',
                          I18n::t('privacy_settings.success.title'),
                          I18n::t('privacy_settings.success.message'));
    }
  }

  public function getComment() {
    return $this->comment;
  }

  public function getCommentHash() {
    return $this->comment_hash;
  }

  public function getContent() {
    return '';
  }

  public function getError() {
    return $this->error;
  }

  public function getFileName() {
    return $this->file_name;
  }

  public function getInfo() {
    return $this->info;
  }

  public function getLink() {
    $lb   = Lixter::getLix()->getLinkBuilder();
    $href = $lb->makeOtherPageLink($this->action);

    return $href;
  }

  public function getParsedContent() {
    return $this->getContent();
  }

  public function getTitle() {
    return I18n::t('privacy_settings.title');
  }

  public function getType() {
    return $this->type;
  }

  public function getUser() {
    return $this->user;
  }

  public function getUserToken() {
    return $this->user_token;
  }

  public function isNotificationDisablePage() {
    if (isset($_GET['comment'], $_GET['user'])) {
      $this->by_get = true;
      return true;

    } else if (isset($_POST['comment'], $_POST['user'])) {
      $this->by_get = false;
      return true;
    }

    return false;
  }

  public static function is($url) {
    return $url == self::$page_name;
  }

  public function sentByGet() {
    return $this->by_get;
  }

  private function validateDisableNotificationRequest() {
    if (isset($_GET['comment'], $_GET['user'])) {
      $this->comment_hash = trim($_GET['comment']);
      $this->user_token   = trim($_GET['user']);

    } else {
      $this->comment_hash = trim($_POST['comment']);
      $this->user_token   = trim($_POST['user']);
    }

    if ($this->comment_hash === '') {
      $this->error = I18n::t('privacy_settings.error.no_comment');

    } else if ($this->user_token === '') {
      $this->error = I18n::t('privacy_settings.error.no_user');

    } else {
      $comment_id = Comment::getIdFromHash($this->comment_hash);
      $user       = User::newFromToken($this->user_token);

      if ($user === null) {
        $this->error = I18n::t('privacy_settings.error.user_not_found');

      } else if($comment_id < 1) {
        $this->error = I18n::t('privacy_settings.error.comment_not_found');

      } else {
        $this->comment  = new Comment($comment_id);
        $this->user     = $user;

        $this->comment->loadReplies();

        $author_in_thread = false;

        if ($this->comment->getAuthor()->getId() == $user->getId()) {
          $author_in_thread = true;

        } else {
          foreach ($this->comment->getReplies() as $reply) {
            if ($reply->getAuthor()->getId() == $user->getId()) {
              $author_in_thread = true;
              break;
            }
          }
        }

        if (!$author_in_thread) {
          $this->error = I18n::t('privacy_settings.error.user_not_in_thread');
        }
      }
    }
  }
}

?>
