<?php

class ConfigLoader {

  public static function loadIni($path) {
    return parse_ini_file($path);
  }

}

?>