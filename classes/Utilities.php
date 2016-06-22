<?php

class Utilities {

  public static function getRemoteAddress() {
    return Config::getConfig()->get('remote_address');
  }

  public static function getSystemAddress() {
    return preg_replace('#(.+?)\.(.+?)\.(.+)#', '$2.$3', $_SERVER['SERVER_NAME']);
  }

  public static function getUriSnippets() {
    if (Utilities::hasUriSnippets()) {
      return $_GET['snip'];
    }

    return '';
  }

  public static function hasUriSnippets() {
    if (isset($_GET['snip']) && trim($_GET['snip'] !== '')) {
      return true;
    }

    return false;
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