<?php

class FileLoader {

  public static function loadIni($path) {
    return parse_ini_file($path);
  }

  public static function loadJson($path, $as_array) {
    $file_content = file_get_contents($path);
    return json_decode($file_content, $as_array);
  }

}

?>