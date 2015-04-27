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
    $this->buildContent();
  }

  public function getContent() {
    return $this->content;
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
    global  $analyse, $file, $noGA;

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
      $this->isPage   = true;
      $this->content  = new ErrorPage($message);
    } else if(is_string($error = getUserID())){
      $this->isPage   = true;
      $this->content  = new ErrorPage($error);
    } else {
      // Laden der Include-Datei
      if(isset($_GET['p'])) {
        if(isset($file[$_GET['p']][0])) {
          if(file_exists('includes/'.$file[$_GET['p']][0])) {
            $this->content = include 'includes/'.$file[$_GET['p']][0];
          } else {
            $message        = "Include-Datei konnte nicht geladen werden: 'includes/".$file[$_GET['p']][0]."'";
            $this->isPage   = true;
            $this->content  = new ErrorPage($message);
          }
        } else {
          $this->content = include 'includes/'.$file['blog'][0];
        }
      } else {
        $this->content = include 'includes/'.$file['blog'][0];
      }
    }
  }

  /**
   * building the user interface
   */
  private function buildContent() {
    global $file, $analyse, $noGA, $bbCmt;

    setcookie('choco-cookie', 'i-love-it', strtotime("+1 day"));

    $pageType = getPageType($this->content);
    $currPage = getPage();

    // Laden HTML-Kopf
    include($this->getFilePath('htmlheader.php'));
    if($analyse && Utilities::isDevServer()) include('settings/analyse.php');
    include($this->getFilePath('htmlwarning.php'));

    // Laden der Template-Datei
    if ($this->isValidTemplate()) {
      // Gültige Include-Datei
      $file = $this->getFilePath();
      if($file !== false) {
        $data = $this->content['data'];
        include $file;
      } else {
        $this->content = new ErrorPage('Templatedatei "'.$file.'" ist nicht vorhanden.');
        include $this->getFilePath('static.php');
      }
    } else if ($this->isPage) {
      // error message
      include $this->getFilePath('static.php');
    } else if (1 == $this->content) {
      // return wurde vergessen
      $this->content = new ErrorPage('In der Include-Datei wurde die return Anweisung vergessen.');
      include $this->getFilePath('static.php');
    } else {
      // ein Ungültiger Return wert
      $this->content = new ErrorPage('Die Include-Datei hat einen ungültigen Wert zurückgeliefert.');
      include $this->getFilePath('static.php');
    }

    include($this->getFilePath('htmlaside.php'));
    include($this->getFilePath('htmlfooter.php'));
  }

  /**
   * check wether the specified file has a local override or not
   */
  private function getFilePath($filename = null) {
    if($filename === null) {
      $filename = $this->content['filename'];
    }

    if (file_exists('user/theme/'.$filename)) {
      return 'user/theme/'.$filename;
    }

    $beTheme = Utilities::getThemeName();
    if(!isset($beTheme) || $beTheme == '') {
      $beTheme = 'default';
    }
    $beThemeP = 'theme/'.$beTheme.'/';

    if (file_exists($beThemeP.$filename)) {
      return $beThemeP.$filename;
    }

    return false;
  }

  /**
   * check if we have a valid template file and data
   */
  private function isValidTemplate() {
    return is_array($this->content)
            && isset($this->content['filename'], $this->content['data'])
            && is_string($this->content['filename'])
            && is_array($this->content['data']);
  }
}

?>