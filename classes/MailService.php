<?php

  class MailService {

    public static function getAdminNotificationMail() {
      $db     = Database::getDB();
      $fields = array('Email');
      $conds  = array('Rights = ?', 's', array('admin'));
      $res    = $db->select('users', $fields, $conds);

      if (count($res) == 0) {
        return '';
      }

      return $res[0]['Email'];
    }

    private static function getNotificationMailHeader() {
      $site_name    = Config::getConfig()->get('site_name');
      $server_mail  = Config::getConfig()->get('server_mail');

      if (!$server_mail) {
        return null;
      }

      $header  = 'MIME-Version: 1.0'."\n";
      $header  .= 'Content-Type: text/html; charset=utf-8'."\n";
      $header  .= 'From: '.$site_name.' <'.$server_mail.'>'."\n";
      $header  .= 'Reply-To: '.$site_name.' <'.$server_mail.'>'."\n";
      $header  .= 'X-Mailer: PHP/'.phpversion().'\r\n';

      return $header;
    }

    public static function commentNotification($article, $comment, $user = null) {
      if (!isset($article) ||
          !isset($comment) ||
          !Utilities::isDevServer()) {
        return false;
      }

      $header = MailService::getNotificationMailHeader();

      if (!$header) {
        return false;
      }

      if ($comment->getAuthor()->getWebsite() == '') {
        $user_page = '';

      } else {
        $page_link  = '<a href="'.$comment->getAuthor()->getWebsite().'">'.
                      $comment->getAuthor()->getWebsite().'</a>';
        $user_page  = '('.$page_link.')';
      }

      $site_name  = Config::getConfig()->get('site_name');

      if ($user == null) {
        $type = 'admin';
        $user_mail = MailService::getAdminNotificationMail();

      } else {
        $type = 'answer';
        $user_mail = $user->getMail();
      }


      if (Utilities::getRemoteAddress() === null) {
        $system = Utilities::getSystemAddress();

      } else {
        $system = Utilities::getRemoteAddress();
      }

      $comment_link = Utilities::getProtocol().'://'.$system.$comment->getLink();
      $comment_link = '<a href="'.$comment_link.'">'.
                      I18n::t('utilities.here').'</a>';

      $copy         = I18n::t('admin.footer.runs_with');
      $copy         .= ' <a href="https://fixel.me">'.I18n::t('admin.footer.cms').'</a><br>';
      $copy         .= I18n::t('admin.footer.copy');

      $description  = I18n::t('comment.notification.'.$type.'.description',
                              array($site_name, $comment->getAuthor()->getName(),
                                    $user_page));

      $footer       = I18n::t('comment.notification.'.$type.'.footer',
                              array($site_name));

      $forward      = I18n::t('comment.notification.forward',
                              array($comment_link));

      $subject      = I18n::t('comment.notification.'.$type.'.subject',
                              array($article->getTitle(), $site_name) );

      $body = file_get_contents('system/views/comment_mail.php');
      $body = preg_replace('/{{title}}/',       $subject,               $body);
      $body = preg_replace('/{{description}}/', $description,           $body);
      $body = preg_replace('/{{forward}}/',     $forward,               $body);
      $body = preg_replace('/{{footer}}/',      $footer,                $body);
      $body = preg_replace('/{{copy}}/',        $copy,                  $body);
      $body = preg_replace('/{{message}}/',     $comment->getContent(), $body);

      return mail( $user_mail, $subject, $body, $header );
    }
  }

?>
