<?php

  class EUCookieNotifier {

    public static function areCookiesAccepted() {
      if (isset($_COOKIE['eu_cookies_accepted'])) {
        return true;
      }

      return false;
    }

    public static function embed($info_page = '#') {
      if (!self::areCookiesAccepted()) {
        self::loadAssets();
        self::prompt($info_page);
      }
    }

    private static function loadAssets() {
      echo '<link href="/system/assets/css/eu_cookies.css" rel="stylesheet">'."\n";
      echo '<script src="/system/assets/js/eu_cookies.js"></script>'."\n";
    }

    private static function prompt($info_page) {
      $link = '<a href="'.$info_page.'">'.
              I18n::t('eu_cookie_notifier.here').'</a>';
      $ok   = I18n::t('eu_cookie_notifier.confirm');
      $text = I18n::t('eu_cookie_notifier.text', array($link));

      $html = '';
      $html .= '<div class="eu_cookie_notifier">'."\n";
      $html .= ' <span class="text">'."\n";
      $html .= '  '.$text."\n";
      $html .= ' </span>'."\n";
      $html .= ' <span class="close" title="';
      $html .= I18n::t('eu_cookie_notifier.title');
      $html .= '">'."\n";
      $html .= '  '.$ok."\n";
      $html .= ' </span>'."\n";
      $html .= '</div>'."\n";
      echo $html;
    }
  }

?>
