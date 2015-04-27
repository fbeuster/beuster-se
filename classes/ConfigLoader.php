<?php

class ConfigLoader {

  public static function loadIni() {
    return parse_ini_file('user/config.ini');
  }

}

?>