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

  public function getContent()
  {
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
    global $analyse, $beTheme, $file, $ieOld, $local, $mob, $noGA, $sysAdrr;

    // loading core functions
    include('settings/core.php');

    // using specifc user functions and settings
    include('user/local.php');

    // setting up some variables
    $sev      = $_SERVER['SERVER_NAME'];
    $sysAdrr  = preg_replace('#(.+?)\.(.+?)\.(.+)#', '$2.$3', $sev);
    $agent    = $_SERVER['HTTP_USER_AGENT'];
    $ieOld    = strpos($agent, 'MSIE 5.5') ||
                strpos($agent, 'MSIE 6.0') ||
                strpos($agent, 'MSIE 7.0') ||
                strpos($agent, 'MSIE 8.0');
    $ie9      = strpos($agent, 'MSIE 9.0');
    $mob      = false;

    if($sev == $devServer) {
      $local = true;
    } else {
      $local = false;
    }
    if($local) {
      error_reporting(E_ALL);
      ini_set('display_errors', 1);
    } else {
      error_reporting(NULL);
    }

    // loading configuration and functions
    include('settings/config.php');
    include('settings/functions.php');
    include('settings/externals.php');
    include('settings/generators.php');
    include('settings/modules.php');
  }

  /**
   * generating and loading current page content
   */
  private function loadContent() {
    global $file, $local, $sysAdrr, $mob;

    $db = Database::getDB()->getCon();
    if($db->connect_errno){
      $this->content = 'Konnte keine Verbindung zu Datenbank aufbauen, MySQL meldete: '.mysqli_connect_error();
    } else if(is_string($error = getUserID())){
      $this->content = $error;
    } else {
      // Laden der Include-Datei
      if(isset($_GET['p'])) {
        if(isset($file[$_GET['p']][0])) {
          if(file_exists('includes/'.$file[$_GET['p']][0])) {
            $this->content = include 'includes/'.$file[$_GET['p']][0];
          } else {
            $this->content = "Include-Datei konnte nicht geladen werden: 'includes/".$file[$_GET['p']][0]."'";
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
    global $file, $local, $analyse, $beTheme, $ieOld, $mob, $noGA, $bbCmt;

    setcookie('choco-cookie', 'i-love-it', strtotime("+1 day"));

    $pageType = getPageType($this->content['data']);
    $currPage = getPage();

    if(!isset($beTheme) || $beTheme == '') {
      $beTheme = 'default';
    }
    $beThemeI = 'theme/'.$beTheme.'/';

    // Laden HTML-Kopf
    include($beThemeI.'htmlheader.php');
    if($analyse && $local) include('settings/analyse.php');
    include($beThemeI.'htmlwarning.php');

    // Laden der Template-Datei
    if (is_array($this->content) && isset($this->content['filename'], $this->content['data']) &&
      is_string($this->content['filename']) && is_array($this->content['data'])) {
      // Gültige Include-Datei
      if(file_exists($file = 'user/theme/'.$this->content['filename'])) {
        $data = $this->content['data'];
        include $file;
      } else if (file_exists($file = $beThemeI.$this->content['filename'])) {
        $data = $this->content['data'];
        include $file;
      } else {
        $data['msg'] = 'Templatedatei "'.$file.'" ist nicht vorhanden.';
        include $beThemeI.'error.php';
      }
    } else if (is_string($this->content)) {
      // Fehlermeldung
      $data['msg'] = $this->content;
      include $beThemeI.'error.php';
    } else if (1 == $this->content) {
      // return wurde vergessen
      $data['msg'] = 'In der Include-Datei wurde die return Anweisung vergessen.';
      include $beThemeI.'error.php';
    } else {
      // ein Ungültiger Return wert
      $data['msg'] = 'Die Include-Datei hat einen ungültigen Wert zurückgeliefert.';
      include $beThemeI.'error.php';
    }
    include($beThemeI.'htmlaside.php');
    // Laden HTML-Fuss
    include($beThemeI.'htmlfooter.php');
  }
}

?>