<?php

class Utilities {

  public static function getProtocol() {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
      return 'https';

    } else {
      return 'http';
    }
  }

  public static function getRemoteAddress() {
    return Config::getConfig()->get('dev', 'remote_server_address');
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
    return Config::getConfig()->get('dev', 'dev_server_address') === $_SERVER['SERVER_NAME'];
  }

  public static function isOldIE() {
    $agent = $_SERVER['HTTP_USER_AGENT'];
    return  strpos($agent, 'MSIE 5.5') || strpos($agent, 'MSIE 6.0') ||
            strpos($agent, 'MSIE 7.0') || strpos($agent, 'MSIE 8.0');
  }

  public static function levenshtein($a, $b) {
    # based on santhoshtr's method https://gist.github.com/santhoshtr/1710925
    $length1 = mb_strlen($a, 'UTF-8');
    $length2 = mb_strlen($b, 'UTF-8');

    if ($length1 < $length2) {
      return Utilities::levenshtein($b, $a);
    }

    if ($length1 == 0) {
      return $length2;
    }

    if ($a === $b) {
      return 0;
    }

    $prevRow    = range( 0, $length2);
    $currentRow = array();

    for ($i = 0; $i < $length1; $i++) {
      $currentRow     = array();
      $currentRow[0]  = $i + 1;

      $c1 = mb_substr($a, $i, 1, 'UTF-8');

      for ($j = 0; $j < $length2; $j++) {
        $c2 = mb_substr($b, $j, 1, 'UTF-8');

        $insertions     = $prevRow[$j+1] + 1;
        $deletions      = $currentRow[$j] + 1;
        $substitutions  = $prevRow[$j] + (($c1 != $c2) ? 1 : 0);
        $currentRow[]   = min($insertions, $deletions, $substitutions);
      }

      $prevRow = $currentRow;
    }

    return $prevRow[$length2];
  }

  public static function loadSystemView($view, $args = array()) {
    include (__DIR__ . '/../system/views/' . $view);
  }

}

?>
