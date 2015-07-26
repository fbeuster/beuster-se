<?php

class Config {

  private $config;
  private static $instance;
  private static $key = '314159265';

  public static function getConfig() {
    if(!self::$instance) {
      self::$instance = new Config(self::$key);
    }
    return self::$instance;
  }

  public function __construct($key) {
    if($key !== self::$key)
      throw new InvalidArgumentException( I18n::t('config.private_constructor') );

    $this->system_config  = FileLoader::loadIni('system/config.ini');
    $this->user_config    = $this->loadUserConfig();
    $this->config         = array_merge($this->system_config, $this->user_config);
  }

  public function get($key) {
    if(isset($this->config[$key])) {
      return $this->config[$key];
    }

    return null;
  }

  public function set($key, $value) {
    $this->config[$key] = $value;
  }

  private function loadUserConfig() {
    if(file_exists('user/config.ini')) {
      return FileLoader::loadIni('user/config.ini');
    }

    throw new Exception( I18n::t('config.no_config_file') );
    return array();
  }
}

?>