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

    public static function commentNotification($title, $content, $user_name, $user_page, $user_mail = null) {
      if (!$title     || trim($title) == '' ||
          !$content   || trim($content) == '' ||
          !$user_name || trim($user_name) == '' ||
          !Utilities::isDevServer()) {
        return false;
      }

      $header = MailService::getNotificationMailHeader();

      if (!$header) {
        return false;
      }

      if ($user_page != '') {
        $page_link  = '<a href="'.$user_page.'">'.$user_page.'</a>';
        $user_page = '('.$page_link.')';
      }

      $site_name  = Config::getConfig()->get('site_name');

      if ($user_mail == null) {
        $type = 'admin';
        $user_mail = MailService::getAdminNotificationMail();

      } else {
        $type = 'answer';
      }

      $copy         = I18n::t('admin.footer.runs_with');
      $copy         .= ' <a href="https://fixel.me">'.I18n::t('admin.footer.cms').'</a><br>';
      $copy         .= I18n::t('admin.footer.copy');

      $description  = I18n::t('comment.notification.'.$type.'.description',
                              array($site_name, $user_name, $user_page));

      $footer       = I18n::t('comment.notification.'.$type.'.footer',
                              array($site_name));

      $subject      = I18n::t('comment.notification.'.$type.'.subject',
                              array($title, $site_name) );

      $body = file_get_contents('system/views/comment_mail.php');
      $body = preg_replace('/{{title}}/',       $subject,     $body);
      $body = preg_replace('/{{description}}/', $description, $body);
      $body = preg_replace('/{{footer}}/',      $footer,      $body);
      $body = preg_replace('/{{copy}}/',        $copy,        $body);
      $body = preg_replace('/{{message}}/',     $content,     $body);

      return mail( $user_mail, $subject, $body, $header );
    }
  }

?>
