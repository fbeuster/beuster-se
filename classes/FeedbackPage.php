<?php

class FeedbackPage extends Page {

  const NAME_DATE     = 'date';
  const NAME_MAIL     = 'usrml';
  const NAME_MESSAGE  = 'usrcnt';
  const NAME_NAME     = 'usr';
  const NAME_PAGE     = 'usrpg';
  const NAME_RESET    = 'formreset';
  const NAME_SUBMIT   = 'formaction';

  private $errors;
  private $file_name  = 'static.php';
  private $form;
  private $status     = false;
  private $type       = Page::FEEDBACK_PAGE;
  private $url;
  private $values;

  private $title;
  private $content;

  public function __construct($url) {
    $this->url = $url;

    if ($this->hadData()) {
      $this->errors = array();
      $this->values = array(
        self::NAME_MAIL     => $_POST[self::NAME_MAIL],
        self::NAME_MESSAGE  => $_POST[self::NAME_MESSAGE],
        self::NAME_NAME     => $_POST[self::NAME_NAME],
        self::NAME_PAGE     => $_POST[self::NAME_PAGE]
      );
      $this->validate();

      if (empty($this->errors)) {
        $this->sendFeedback();
      }

    } else {
      $this->values = array(
        self::NAME_MAIL     => '',
        self::NAME_MESSAGE  => '',
        self::NAME_NAME     => '',
        self::NAME_PAGE     => ''
      );
    }

    $this->loadPage();
  }

  public static function exists($url) {
    $db   = Database::getDB();

    if(!$db->tableExists('static_pages'))
      return false;

    $fields = array('title');
    $conds = array('url = ? AND feedback = ?', 'si', array($url, 1));

    $res  = $db->select('static_pages', $fields, $conds);

    return count($res) > 0;
  }

  public function getContent() {
    return $this->content;
  }

  public function getForm() {
    return $this->form;
  }

  public function getParsedContent() {
    return '<p>'.Parser::parse($this->content, Parser::TYPE_CONTENT).'</p>';
  }

  public function getTitle() {
    return $this->title;
  }

  public function getUrl() {
    return $this->url;
  }

  public function getFileName() {
    return $this->file_name;
  }

  public function getType() {
    return $this->type;
  }

  private function hadData() {
    return isset($_POST, $_POST[self::NAME_SUBMIT]);
  }

  private function loadPage() {
    $fields = array('title', 'content');
    $conds = array('url = ?', 's', array($this->url));

    $db   = Database::getDB();
    $res  = $db->select('static_pages', $fields, $conds);

    foreach ($res as $page) {
      $this->title    = $page['title'];
      $this->content  = $page['content'];
    }

    $this->form = new Form($_SERVER['REQUEST_URI'], $this->errors, $this->status);

    $this->form->addInputText('name', self::NAME_NAME, true, $this->values[self::NAME_NAME]);
    $this->form->addInputText('mail', self::NAME_MAIL, true, $this->values[self::NAME_MAIL]);
    $this->form->addInputText('website', self::NAME_PAGE, false, $this->values[self::NAME_PAGE]);

    $this->form->addTextarea('message', self::NAME_MESSAGE, true, $this->values[self::NAME_MESSAGE]);

    $this->form->addHiddenInput(self::NAME_DATE, time());

    $this->form->addControl(self::NAME_SUBMIT, 'submit', 'submit');
    $this->form->addControl(self::NAME_RESET, 'reset', 'clear');
  }

  private function sendFeedback() {
    $this->status = MailService::feedbackNotification($this->values);

    if ($this->status) {
      $this->values = null;

    } else {
      $this->errors['send'] = I18n::t('general_form.errors.send_error');
    }
  }

  private function validate() {
    if (!isset($_POST[self::NAME_NAME]) || trim($_POST[self::NAME_NAME]) == '') {
      $this->errors[self::NAME_NAME] = I18n::t('general_form.errors.invalid_name');
    }

    if (!isset($_POST[self::NAME_MAIL]) || trim($_POST[self::NAME_MAIL]) == '' ||
        !checkMail($_POST[self::NAME_MAIL])) {
      $this->errors[self::NAME_MAIL] = I18n::t('general_form.errors.invalid_mail');
    }

    if (!isset($_POST[self::NAME_MESSAGE]) || trim($_POST[self::NAME_MESSAGE]) == '') {
      $this->errors[self::NAME_MESSAGE] = I18n::t('general_form.errors.invalid_message');
    }

    if (Form::WAIT_TIME > time() - $_POST[self::NAME_DATE]) {
      $this->errors['date'] = I18n::t('general_form.errors.too_quick');
    }
  }
}

?>