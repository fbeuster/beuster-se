<?php

class Utilities {

  public static function getThemeName() {
    return Config::getConfig()->get('theme');
  }

  public static function isDevServer() {
    return Config::getConfig()->get('devServer') === $_SERVER['SERVER_NAME'];
  }

  public static function isOldIE() {
    $agent = $_SERVER['HTTP_USER_AGENT'];
    return  strpos($agent, 'MSIE 5.5') || strpos($agent, 'MSIE 6.0') ||
            strpos($agent, 'MSIE 7.0') || strpos($agent, 'MSIE 8.0');
  }

}

?>