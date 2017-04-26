<?php

class Api {

  const METHOD_GET  = 0;
  const METHOD_POST = 1;

  private static $api;

  private $method;

  public function __construct() {}

  public static function getApi() {
    if(!self::$api)
      self::$api = new Api();
    return self::$api;
  }

  public function init() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->method = self::METHOD_POST;

    } else {
      $this->method = self::METHOD_GET;
    }

    $this->loadConfig();
    $this->loadLocales();
    $this->loadClasses();
  }

  private function loadClasses() {
    $classes = array(
      'Parser',
    );
    foreach ($classes as $classfile) {
      include ('classes/'.$classfile.'.php');
    }
  }

  private function loadConfig() {
    global  $file;

    // non-catched exceptions should be logged
    set_exception_handler(function($exception) {
      echo 'Oups, something went wrong :(';

      foreach (split("\n", $exception) as $line) {
        error_log($line);
      }
    });

    // using specifc user functions and settings
    include('user/local.php');

    if (Utilities::isDevServer()) {
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

  private function loadLocales() {
    $lang = Config::getConfig()->get('language');
    $lang = $lang === null ? 'en' : $lang;

    $locales = new Locale($lang);
  }

  public function run() {
    if ($this->method == self::METHOD_POST) {
      $data   = $_POST['data'];
      $method = $_POST['method'];
      $path   = 'classes/api/' . $method . '.php';

      if (!file_exists($path)) {
        # no api module found
        echo 'no_module';

      } else {
        # api module found
        include($path);
        $module = new $method();

        if ($module->requiresAdmin()) {
          # admin api module
          $user = User::newFromCookie();

          if ($user && $user->isAdmin()) {
            # run api module
            $module->init( $data );
            $module->run();

          } else {
            # error
            echo 'no_access';
          }

        } else {
          # general api module
          $module->init( $data );
          $module->run();
        }
      }
    }
  }
}

?>