<?php

class Config {

  private $base_path;
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
      throw new InvalidArgumentException( 'Private constructor!' );

    $db     = Database::getDB();
    $fields = array('option_set', 'option_name', 'option_value');
    $result = $db->select('configuration', $fields);

    $this->config = array();

    if ($result != null  && count($result)) {
      foreach ($result as $option) {
        if (!isset($this->config[$option['option_set']])) {
          $this->config[$option['option_set']] = array();
        }

        $this->config[$option['option_set']][$option['option_name']] =
          $option['option_value'];
      }
    }
  }

  public function get($set, $key) {
    if(isset( $this->config[$set],
              $this->config[$set][$key])) {
      return $this->config[$set][$key];
    }

    return null;
  }

  public function set($set, $key, $value) {
    if ($set === null || $key === null ||
        trim($set) === '' || trim($key) === '') {
      return false;
    }

    $this->config[$value][$key] = $value;

    $mysqli = Database::getDB()->getCon();
    $sql    = " UPDATE  configuration
                SET     option_value = ?
                WHERE   option_set = ? AND
                        option_name = ?";

    if (!$stmt = $mysqli->prepare($sql)) {
      return false;
    }

    $stmt->bind_param('sss', $value, $set, $key);

    if (!$stmt->execute()) {
      return false;
    }

    $stmt->close();

    return true;
  }
}

?>