<?php

/**
 * Lixter main class
 * \file classes/lixter.php
 */

/**
 * Lixter main class
 *
 * \class Lixter
 * \author Felix Beuster
 *
 * This class hold an initialize the CMS.
 */
class Lixter {

  private static $lix; /**< Lixter instance */
  private $content; /**< loaded content */
  private $isPage = false;
  private $theme;

  /**
   * constructor
   */
  public function __construct() {}

  /*** STATIC ***/

  /**
   * get (or creates) current Lixter instance
   */
  public static function getLix() {
    if(!self::$lix)
      self::$lix = new Lixter();
    return self::$lix;
  }

  /*** PUBLIC ***/

  /**
   * initialize Lixter.
   *
   * Loadings classes.
   */
  public function init() {
    $this->loadConfig();
    $this->loadClasses();
  }

  /**
   * loading content
   */
  public function run() {
    $this->loadContent();
    $this->loadTheme();
    $this->buildContent();
  }

  public function getContent() {
    return $this->content->getContent();
  }

  public function getTheme() {
    return $this->theme;
  }

  /*** PRIVATE ***/

  /**
   * loading neccessary classes
   */
  private function loadClasses() {
    $classes = array(
      'Parser',
    );
    foreach ($classes as $classfile) {
      include ('classes/'.$classfile.'.php');
    }
  }

  /**
   * loading Lixter config
   */
  private function loadConfig() {
    global  $file, $noGA;

    // loading core functions
    include('settings/core.php');

    // using specifc user functions and settings
    include('user/local.php');

    if(Utilities::isDevServer()) {
      error_reporting(E_ALL);
      ini_set('display_errors', 1);
    } else {
      error_reporting(NULL);
    }

    // loading configuration and functions
    include('settings/config.php');
    include('settings/functions.php');
    include('settings/generators.php');
    include('settings/modules.php');
  }

  /**
   * generating and loading current page content
   */
  private function loadContent() {
    global $file;

    $db = Database::getDB()->getCon();
    if($db->connect_errno){
      $message        = 'Konnte keine Verbindung zu Datenbank aufbauen, MySQL meldete: '.mysqli_connect_error();
      $this->content  = new ErrorPage($message);
    } else if(is_string($error = getUserID())){
      $this->content  = new ErrorPage($error);
    } else {
      // Laden der Include-Datei
      if(isset($_GET['p'])) {
        if(isset($file[$_GET['p']][0])) {
          if(ContentPage::exists($_GET['p'])) {
            $this->content = new ContentPage($_GET['p']);
          } else {
            $message        = "Include-Datei konnte nicht geladen werden: 'includes/".$file[$_GET['p']][0]."'";
            $this->content  = new ErrorPage($message);
          }
        } else {
          $this->content = new ContentPage('blog');
        }
      } else {
        $this->content = new ContentPage('blog');
      }
    }
  }

  private function loadTheme() {
    $this->theme = new Theme( Config::getConfig()->get('theme') );
  }

  /**
   * building the user interface
   */
  private function buildContent() {
    global $file, $noGA;

    setcookie('choco-cookie', 'i-love-it', strtotime("+1 day"));

    $pageType = $this->content->getPageClass();
    $currPage = getPage();

    // Laden HTML-Kopf
    include($this->theme->getFile('htmlheader.php'));
    if(Utilities::isDevServer()) include('settings/analyse.php');
    include($this->theme->getFile('htmlwarning.php'));

    // Laden der Template-Datei
    if ($this->isValidTemplate()) {
      // Gültige Include-Datei
      $file = $this->theme->getFile($this->content->getFilename());
      if($file !== false) {
        $data = $this->getContent();
        include $file;
      } else {
        $this->content = new ErrorPage('Templatedatei "'.$file.'" ist nicht vorhanden.');
        include $this->theme->getFile('static.php');
      }
    } else if ($this->content->getType() === Page::STATIC_PAGE) {
      // error message
      include $this->theme->getFile('static.php');
    } else if (1 == $this->content) {
      // return wurde vergessen
      $this->content = new ErrorPage('In der Include-Datei wurde die return Anweisung vergessen.');
      include $this->theme->getFile('static.php');
    } else {
      // ein Ungültiger Return wert
      $this->content = new ErrorPage('Die Include-Datei hat einen ungültigen Wert zurückgeliefert.');
      include $this->theme->getFile('static.php');
    }

    include($this->theme->getFile('htmlaside.php'));
    include($this->theme->getFile('htmlfooter.php'));
  }

  /**
   * check if we have a valid template file and data
   */
  private function isValidTemplate() {
    return isset($this->content) && is_string($this->content->getFilename());
  }
}

?>