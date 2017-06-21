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

    private static function getNotificationMailHeader($from, $reply = '') {
      if ($reply == '') {
        $reply = $from;
      }

      $header  = 'MIME-Version: 1.0'."\n";
      $header  .= 'Content-Type: text/html; charset=utf-8'."\n";
      $header  .= 'From: '.$from."\n";
      $header  .= 'Reply-To: '.$reply."\n";
      $header  .= 'X-Mailer: PHP/'.phpversion().'\r\n';

      return $header;
    }

    public static function commentNotification($article, $comment, $user = null) {
      if (!isset($article) ||
          !isset($comment) ||
          Utilities::isDevServer()) {
        return false;
      }

      $site_name    = Config::getConfig()->get('site_name');
      $server_mail  = Config::getConfig()->get('server_mail');

      if (!$server_mail) {
        return false;
      }

      $from   = $site_name.' <'.$server_mail.'>';
      $header = MailService::getNotificationMailHeader($from);

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
      $body = preg_replace('/{{title}}/',       $subject,     $body);
      $body = preg_replace('/{{description}}/', $description, $body);
      $body = preg_replace('/{{forward}}/',     $forward,     $body);
      $body = preg_replace('/{{footer}}/',      $footer,      $body);
      $body = preg_replace('/{{copy}}/',        $copy,        $body);
      $body = preg_replace('/{{message}}/',     $comment->getContentParsed(), $body);

      return mail( $user_mail, $subject, $body, $header );
    }

    public static function feedbackNotification($values) {
      $page_title = '';
      $protocol   = Lixter::getLix()->getProtocol();
      $site_link  = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
      $site_link  = '<a href="'.$protocol.'://'.$site_link.'">'.$site_link.'</a>';
      $site_name  = Config::getConfig()->get('site_name');


      $fields = array('title');
      $conds = array('url = ?', 's', array($_GET['p']));

      $db   = Database::getDB();
      $res  = $db->select('static_pages', $fields, $conds);

      foreach ($res as $page) {
        $page_title = $page['title'] . ' - ';
      }

      # mail info
      $from       = $values[FeedbackPage::NAME_NAME].
                    '<'.$values[FeedbackPage::NAME_MAIL].'>';
      $subject    = I18n::t('general_form.notification.subject',
                            array($values[FeedbackPage::NAME_NAME],
                                  $site_name));
      $to         = MailService::getAdminNotificationMail();

      if ($values[FeedbackPage::NAME_PAGE] == '') {
        $page_link = '';

      } else {
        $page_link  = '<a href="'.$values[FeedbackPage::NAME_PAGE].'">'.
                      $values[FeedbackPage::NAME_PAGE].'</a>';
        $page_link  = '('.$page_link.')';
      }

      # mail header
      $header = MailService::getNotificationMailHeader($from);

      # mail replacements
      $title        = I18n::t('general_form.notification.title',
                              array($values[FeedbackPage::NAME_NAME]));

      $description  = I18n::t('general_form.notification.description',
                              array($page_title . $site_name,
                                    $values[FeedbackPage::NAME_NAME],
                                    $page_link));

      $message      = $values[FeedbackPage::NAME_MESSAGE];

      $footer       = I18n::t('general_form.notification.footer',
                              array($site_name, $site_link));

      $copy         = I18n::t('admin.footer.runs_with');
      $copy         .=  ' <a href="https://fixel.me">'.
                        I18n::t('admin.footer.cms').'</a><br>';
      $copy         .=  I18n::t('admin.footer.copy');

      # get and fill mail content
      $content = file_get_contents('system/views/feedback_mail.php');
      $content = preg_replace('/{{title}}/',        $title,       $content);
      $content = preg_replace('/{{description}}/',  $description, $content);
      $content = preg_replace('/{{footer}}/',       $footer,      $content);
      $content = preg_replace('/{{copy}}/',         $copy,        $content);
      $content = preg_replace('/{{message}}/',      $message,     $content);

      # send mail
      return mail($to, $subject, $content, $header);
    }
  }

?>
