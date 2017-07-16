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
  private $protocal;
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
    $this->loadTheme();
    $this->loadPage();

    if ($this->page->getType() == Page::ADMIN_PAGE) {
      $this->buildAdmin();

    } else {
      $this->buildContent();
    }
  }

  public function getPage() {
    return $this->page;
  }

  public function getProtocol() {
    return $this->protocol;
  }

  public function getTheme() {
    return $this->theme;
  }

  public function getSystemFile($filename) {
    $path = 'system/';

    if (file_exists($path.$filename))
      return $path.$filename;

    return false;
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
    // non-catched exceptions should be logged
    set_exception_handler(function($exception) {
      echo 'Oups, something went wrong :(';

      foreach (explode("\n", $exception) as $line) {
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

    // set the protocol
    $this->protocol = Utilities::getProtocol();

    // loading configuration and functions
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
    $db = Database::getDB()->getCon();
    if ($db->connect_errno) {
      $message    = I18n::t( 'lixter.load.mysql_connection_error', array(mysqli_connect_error()) );
      $this->page = new ErrorPage($message);

    } else {
      if (isset($_GET['p'])) {
        # page argument found

        if ($_GET['p'] == 'search') {
          # page argument is search page
          $this->page = new SearchPage();

          if ($this->page->isValid()) {
            $this->page->search();

          } else {
            $this->page = new ErrorPage($this->page->getError());
          }

        } else if (FeedbackPage::exists($_GET['p'])) {
          # page argument is static page
          $this->page = new FeedbackPage($_GET['p']);

        } else if (StaticPage::exists($_GET['p'])) {
          # page argument is static page
          $this->page = new StaticPage($_GET['p']);

        } else if ($_GET['p'] == 'blog' && isset($_GET['n'])) {
          # page argument is article page
          # TODO make this ArticlePage
          $this->page = new ArticlePage();

          if (!$this->page->isValid()) {
            # add note at the top of the page
            $this->page = new CategoryPage();
          }

        } else if (AbstractAdminPage::exists($_GET['p'])) {
          # page argument is admin page
          $user = User::newFromCookie();

          if ($user && $user->isAdmin()) {
            $class_name = AbstractAdminPage::getClass($_GET['p']);
            $this->page = new $class_name();

          } else if ($user) {

          } else {
            if ($_GET['p'] == 'login') {
              $this->page = new LoginPage();

            } else {
              $link       = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
              $message    = I18n::t('admin.not_logged_in').$link;
              $this->page = new ErrorPage($message, 'login');
            }
          }

        } else if ($_GET['p'] == 'blog' && isset($_GET['c'])
          && Category::isCategoryName($_GET['c'])) {
          # page argument is category page
          $this->page = new CategoryPage($_GET['c']);

        } else if (Category::isCategoryName($_GET['p'])) {
          # page argument is category page
          $this->page = new CategoryPage($_GET['p']);

        } else if (User::isAuthor($_GET['p'])) {
          # page argument is category page
          $this->page = new CategoryPage(null, $_GET['p']);

        } else {
          # page argument has no declaration and is not a static page
          $this->page = new CategoryPage('blog');
        }

      } else {
        # no page argument found
        $this->page = new IndexPage();
      }
    }
  }

  private function loadTheme() {
    if (isset($_GET['theme']) && Theme::isValidTheme($_GET['theme'])) {
      $this->theme = new Theme( $_GET['theme'] );

    } else {
      $this->theme = new Theme( Config::getConfig()->get('theme') );
    }

    include($this->theme->getFile('functions.php'));
  }

  private function buildAdmin() {
    setcookie('choco-cookie', 'i-love-it', strtotime("+1 day"));

    $pageType = $this->page->getPageClass();
    $currPage = getPage();
    $config   = Config::getConfig();

    include('system/views/admin/htmlheader.php');

    if (Utilities::isDevServer() || $config->get('debug')) {
      include('system/views/debug.php');
    }

    $this->page->show();

    include('system/views/admin/htmlfooter.php');
  }

  /**
   * building the user interface
   */
  private function buildContent() {
    setcookie('choco-cookie', 'i-love-it', strtotime("+1 day"));

    $pageType = $this->page->getPageClass();
    $currPage = getPage();
    $config   = Config::getConfig();

    include($this->theme->getFile('htmlheader.php'));

    if (Utilities::isDevServer() || $config->get('debug')) {
      include('system/views/debug.php');
    }

    if (!empty($this->page->getInfo())) {
      echo '<div class="info '.$this->page->getInfo()[0].'">
        <div class="title">'.$this->page->getInfo()[1].'</div>
        <ul class="messages">
          <li>'.$this->page->getInfo()[2].'</li>
        </ul>
      </div>';
    }

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

    if ($this->page->hasScripts()) {
      $this->page->includeScripts();
    }

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
