<?php

class Setup {

  private static $setup;
  private $form_handler;
  private $step_name = 'welcome';

  public function __construct() {}

  public function getFormHandler() {
    return $this->form_handler;
  }

  public static function getSetup() {
    if(!self::$setup)
      self::$setup = new Setup();
    return self::$setup;
  }

  public function getStepFile() {
    if (!file_exists($this->step_name . '.php')) {
      # todo exception
    }

    return $this->step_name . '.php';
  }

  public function getStepName() {
    return $this->step_name;
  }

  public function handleRequest() {
    if (isset($_POST) && !empty($_POST)) {

      $this->setup_handler  = new SetupHandler();
      $this->validator      = new SetupValidator();

      if ($this->validator->isValid()) {
        $this->setup_handler->handleStep();
        if (!$this->setup_handler->hasError()) {
          header('Location:index.php?step=' . $this->validator->getNextStep());
        }
      } else {
        $this->form_handler->addErrors($this->validator->getErrors());
        $this->form_handler->addMessages($this->validator->getMessages());
      }
    }
  }

  public function init() {
    include('../settings/functions.php');

    if (file_exists('../user/local.php')) {
      include('../user/local.php');
    }

    session_start();

    $_SESSION['setup_values'] = array();

    $locales = new Locale('en', '../');

    if (isset($_GET['step'])) {
      $this->step_name = $_GET['step'];
    }

    $this->form_handler = new FormHandler();
  }
}

?>