<?php
  $adsense    = ' https://*.doubleclick.net';
  $analytics  = ' https://*.google-analytics.com https://*.googletagmanager.com http://*.googlesyndication.com';
  $google     = ' https://*.google.de https://*.google.com';
  $youtube    = ' https://*.youtube.com';
  $default    = " default-src 'self' https://*.beusterse.de".
                $adsense.$analytics.$google.$youtube;
  $script     = "; script-src 'self' 'unsafe-inline' https://*.beusterse.de".
                $adsense.$analytics.$google.$youtube;
  $style      = "; style-src 'self' 'unsafe-inline' https://*.beusterse.de".
                $adsense.$analytics.$google.$youtube;

  header('Content-Security-Policy:'.$default.$script.$style);
  header('X-Frame-Options: DENY');
?>

<!DOCTYPE html>
<html dir="ltr" lang="de-DE" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo getPageDescription(); ?>">
  <meta name="loaded_lang" content="<?php echo Config::getConfig()->get('site', 'language'); ?>">
  <?php
    $lb   = Lixter::getLix()->getLinkBuilder();
    $page = Lixter::getLix()->getPage();
    $keywords = '';

    if ($page->getType() === Page::CATEGORY_PAGE || $page->getType() === Page::CONTENT_PAGE || $page->getType() === Page::ARTICLE_PAGE) {
      $keywords = $page->getTags() . ', ';
    }
  ?>
<meta name="keywords" content="<?php echo $keywords; ?> beuster{se}, Felix Beuster, Blog, News, Tutorials">
<meta name="author" content="Felix Beuster">
  <meta property='og:locale' content='de_de'/>
  <meta property='fb:admins' content='100002550334323'/>
  <meta property='og:title' content='<?php echo $page->getTitle().' - '.$config->get('meta', 'name'); ?>'/>
  <meta property='og:url' content='<?php echo getPageUrl(); ?>'/>
  <meta property='og:site_name' content='beusterse.de'/>
  <meta property='og:type' content='website'/>
  <meta property='og:image' content='<?php echo getPageOGImage('assets/img/prev.png'); ?>'/>
  <meta property='og:description' content='<?php echo getPageDescription(); ?>'/>

  <link rel="alternate" type="application/rss+xml" title="RSS" href="/rss.xml" />
  <title><?php echo $page->getTitle().' - '.$config->get('meta', 'name'); ?></title>

  <link href="/<?php echo Lixter::getLix()->getTheme()->getFile('assets/css/styles.css'); ?>" rel="stylesheet" type="text/css" media="screen">

  <!-- wanna play javascript? -->
  <script type="text/javascript" src="/<?php echo Lixter::getLix()->getTheme()->getFile('assets/js/beusterse.js'); ?>"></script>

  <?php if (isset($_GET['p']) && AdminPage::exists($_GET['p'])) { ?>
  <script type="text/javascript" src="/<?php echo Lixter::getLix()->getTheme()->getFile('assets/js/scriptAdm.js'); ?>"></script>
  <?php } ?>

  <?php if( ( $page->getType() == Page::CONTENT_PAGE ||
              $page->getType() == Page::STATIC_PAGE) &&
            $page->getRefreshName() !== '') {
    echo '<meta http-equiv="refresh" content="3; url=/'.$page->getRefreshName().'">';
  } ?>
 </head>
 <body class="<?php echo makeBodyClass($currPage); ?>">
  <header>
    <div class="wrapper">
      <div class="row">
        <?php if($currPage == 'index') { ?>
        <h1><a href="/">beuster{se}</a></h1>
        <?php } else { ?>
        <span><a href="/">beuster{se}</a></span>
        <?php } ?>
        <div class="search inactive">
          <form action="<?php echo $lb->makeOtherPageLink('search'); ?>"
                method="post">
            <input type="text" placeholder="<?php I18n::e('search.form.placeholder'); ?>" name="s">
            <input type="submit" value="" alt="<?php I18n::e('search.form.submit'); ?>" name="search">
          </form>
        </div>
      </div>
      <nav>
        <span class="expander">Menü anzeigen</span>
        <menu class="clearfix">
          <?php genMenu(); ?>
          <li>
            <a href="<?php echo $lb->makeOtherPageLink('kontakt'); ?>">
              Kontakt
            </a>
          </li>
        </menu>
      </nav>
    </div>
  </header>
  <div class="wrapper">
