<?php

class Config {

  private $base_path;
  private $config;
  private static $instance;
  private static $key = '314159265';

  public static function getConfig($base_path = '') {
    if(!self::$instance) {
      self::$instance = new Config(self::$key, $base_path);
    }
    return self::$instance;
  }

  public function __construct($key, $base_path = '') {
    if($key !== self::$key)
      throw new InvalidArgumentException( 'Private constructor!' );

    $this->base_path      = $base_path;
    $this->system_config  = FileLoader::loadIni($this->base_path . 'system/config.ini');
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
    if(file_exists($this->base_path . 'user/config.ini')) {
      return FileLoader::loadIni($this->base_path . 'user/config.ini');
    }

    throw new Exception( 'No configuration file found.' );
    return array();
  }
}

?>