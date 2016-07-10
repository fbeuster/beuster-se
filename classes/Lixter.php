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
  private $page; /**< loaded page */
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
    $this->loadLocales();
    $this->loadClasses();
  }

  /**
   * loading content
   */
  public function run() {
    $this->loadPage();
    $this->loadTheme();
    $this->buildContent();
  }

  public function getPage() {
    return $this->page;
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

    // non-catched exceptions should be logged
    set_exception_handler(function($exception) {
      echo 'Oups, something went wrong :(';

      foreach (split("\n", $exception) as $line) {
        error_log($line);
      }
    });

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
   * loading the specified language
   */
  private function loadLocales() {
    $lang = Config::getConfig()->get('language');
    $lang = $lang === null ? 'en' : $lang;

    $locales = new Locale($lang);
  }

  /**
   * generating and loading current page content
   */
  private function loadPage() {
    global $file;

    $db = Database::getDB()->getCon();
    if ($db->connect_errno) {
      $message    = I18n::t( 'lixter.load.mysql_connection_error', array(mysqli_connect_error()) );
      $this->page = new ErrorPage($message);

    } else {
      if (isset($_GET['p'])) {
        # page argument found

        if (StaticPage::exists($_GET['p'])) {
          # page argument is static page
          $this->page = new StaticPage($_GET['p']);

        } else if (isset($file[$_GET['p']][0])) {
          # page argument has specific file

          if (ContentPage::exists($_GET['p'])) {
            # specific file is found
            $this->page = new ContentPage($_GET['p']);

          } else {
            # specif file is not found
            $message    = I18n::t( 'lixter.load.include_not_found', array('includes/' . $file[$_GET['p']][0]) );
            $this->page = new ErrorPage($message);
          }

        } else {
          # page argument has no declaration and is not a static page
          $this->page = new ContentPage('blog');
        }

      } else {
        # no page argument found
        $this->page = new ContentPage('blog');
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

    $pageType = $this->page->getPageClass();
    $currPage = getPage();

    include($this->theme->getFile('functions.php'));
    include($this->theme->getFile('htmlheader.php'));
    if (Utilities::isDevServer()) include('settings/analyse.php');
    include($this->theme->getFile('htmlwarning.php'));

    if ($this->isValidTemplate()) {
      # valid include file
      $file = $this->theme->getFile($this->page->getFilename());

      if ($file !== false) {
        $data = $this->page->getContent();
        include $file;

      } else {
        $this->page = new ErrorPage( I18n::t('lixter.build.template_not_found', array($file)) );
        include $this->theme->getFile('static.php');
      }

    } else if ($this->page->getType() === Page::STATIC_PAGE) {
      # error message
      include $this->theme->getFile('static.php');

    } else if ($this->page === 1) {
      # no return statement given
      $this->page = new ErrorPage( I18n::t('lixter.build.no_return_statement') );
      include $this->theme->getFile('static.php');

    } else {
      # invalid return value
      $this->page = new ErrorPage( I18n::t('lixter.build.invalid_return_value') );
      include $this->theme->getFile('static.php');
    }

    include($this->theme->getFile('htmlaside.php'));
    include($this->theme->getFile('htmlfooter.php'));
  }

  /**
   * check if we have a valid template file and data
   */
  private function isValidTemplate() {
    return isset($this->page) && is_string($this->page->getFilename());
  }
}

?>