<?php

class SetupHelper {

  public static function getCharsets() {
    $con = @new MySQLi( DB_HOST, DB_USER, DB_PASS, DB_NAME);

    $sql = 'SELECT CHARACTER_SET_NAME
            FROM information_schema.CHARACTER_SETS';

    $stmt = $con->prepare($sql);
    if (!$stmt) {
      # todo error
    }

    if (!$stmt->execute()) {
      # todo error
    }

    $stmt->bind_result($charset);

    $charsets = array();
    while ($stmt->fetch()) {
      $charsets[$charset] = $charset;
    }

    ksort($charsets);
    return $charsets;
  }

  public static function getFieldOptions($field_name, $placeholder = null) {
    $ret = array();
    $ret['value'] = SetupHelper::getFieldValue($field_name);

    if ($placeholder !== null) {
      $ret['placeholder'] = $placeholder;
    }

    return $ret;
  }

  public static function getFieldValue($field_name) {
    if (!empty($_SESSION['setup_values']) &&
        isset( $_SESSION['setup_values'][$field_name] )) {

      return $_SESSION['setup_values'][$field_name];
    }

    return '';
  }

  public static function getAvailableLanguages() {
    return array( 'en' => 'English (US)',
                  'de' => 'Deutsch (DE)');
  }

  public static function getAvailableThemes() {
    $files = scandir('../theme');

    foreach ($files as $key => $file) {
      if (!is_dir('../theme/'.$file) || $file == '.' || $file == '..') {
        unset($files[$key]);
      }
    }

    return $files;
  }

  public static function getAvailableTimezones() {
    $timezones = array();

    foreach (DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $zone) {
      $timezones[$zone] = $zone;
    }

    return $timezones;
  }

  public static function getTimeZone() {
    return date('e', time());
  }
}

?>