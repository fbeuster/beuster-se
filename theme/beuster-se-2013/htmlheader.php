<!DOCTYPE html>
<html dir="ltr" lang="de-DE" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo getPageDescription(); ?>">
  <meta name="loaded_lang" content="<?php echo Config::getConfig()->get('site', 'language'); ?>">
  <?php
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
  <meta property='og:image' content='<?php echo getPageOGImage('images/prev.png'); ?>'/>
  <meta property='og:description' content='<?php echo getPageDescription(); ?>'/>
  <!--<link href="/images/favicon.ico" type="image/x-icon" rel="shortcut icon"> -->

  <!-- so many sweet favicons, inspired by Edward Black (http://quda.tv) -->
  <link rel="shortcut icon" href="/images/favs/favicon.ico">
  <link rel="apple-touch-icon" sizes="57x57" href="/images/favs/fav96.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/images/favs/fav144.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/images/favs/fav96.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/images/favs/fav144.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/images/favs/fav96.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/images/favs/fav144.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/images/favs/fav96.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/images/favs/fav144.png">
  <link rel="icon" type="image/png" href="/images/favs/fav310.png" sizes="196x196">
  <link rel="icon" type="image/png" href="/images/favs/fav144.png" sizes="160x160">
  <link rel="icon" type="image/png" href="/images/favs/fav96.png" sizes="96x96">
  <link rel="icon" type="image/png" href="/images/favs/fav96.png" sizes="32x32">
  <link rel="icon" type="image/png" href="/images/favs/fav96.png" sizes="16x16">
  <meta name="msapplication-TileColor" content="#1686c6">
  <meta name="msapplication-TileImage" content="/images/favs/fav144.png">
  <meta name="msapplication-square70x70logo" content="/images/favs/fav96.png">
  <meta name="msapplication-square144x144logo" content="/images/favs/fav144.png">
  <meta name="msapplication-square150x150logo" content="/images/favs/fav144.png">
  <meta name="msapplication-square310x310logo" content="/images/favs/fav310.png">
  <meta name="msapplication-wide310x150logo" content="/images/favs/fav310w.png">
  <meta name="apple-mobile-web-app-title" content="beuster{se}">

  <link rel="alternate" type="application/rss+xml" title="RSS" href="/rss.xml" />
  <title><?php echo $page->getTitle().' - '.$config->get('meta', 'name'); ?></title>
  <?php if(!Utilities::isOldIE()) { ?>
  <!-- style it -->
  <link href="/<?php echo Lixter::getLix()->getTheme()->getFile('styles/application.css'); ?>" rel="stylesheet" type="text/css" media="screen">
  <?php } else { ?>
  <!-- not another internet explorer -.- -->
  <link href="/<?php echo Lixter::getLix()->getTheme()->getFile('styles/ie.css'); ?>" rel="stylesheet" type="text/css" media="screen">
  <style type="text/css">
  .alert {
    font-family: sans-Serif;
    font-size: 18px;
    color: red;
  }
  </style>
  <?php } ?>
  <!-- wanna play javascript? -->
  <script type="text/javascript" src="/<?php echo Lixter::getLix()->getTheme()->getFile('scripts/beusterse.js'); ?>"></script>
  <?php if (isset($_GET['p']) && AdminPage::exists($_GET['p'])) { ?>
  <script type="text/javascript" src="/<?php echo Lixter::getLix()->getTheme()->getFile('scripts/scriptAdm.js'); ?>"></script>
  <?php } ?>
  <?php if($page->getType() == Page::CONTENT_PAGE && $page->getRefreshName() !== '') {
    echo '<meta http-equiv="refresh" content="3; url=/'.$page->getRefreshName().'">';
  } ?>
 </head>
 <body>
  <header id="beMainHeader">
    <?php if($currPage == 'index') { ?>
    <h1 class="pageTitle">
      beuster{se}
      <span>
        Blog, Videos und News
      </span>
    </h1>
    <?php } else { ?>
    <span class="pageTitle">
      beuster{se}
      <span>
        Blog, Videos und News
      </span>
    </span>
    <?php } ?>
    <nav>
      <menu id="beMainNav"><?php genMenu();
      ?></menu>
    </nav>
  </header>
  <?php if($pageType == 'multipleArticles') { ?>
  <div id="beMainContainer" class="<?php echo $pageType; ?>">
  <?php } else { ?>
  <div id="beMainContainer" class="<?php echo $pageType; ?>">
    <div id="beMainContent">
  <?php } ?>

  <?php if(Utilities::isOldIE()) { ?>
    <p class="alert notice">
      <br>
      Sie verwenden einen veralteten oder nicht unterstützten Browser.
      <br>
      Um Darstellungsfehlern konsquent vozubeugen, wird der Blog ohne Stile angezeigt. Hey, so war das halt früher.
      <br>
      Ich empfehle Mozilla Firefox oder Google Chrome.
    </p>
  <?php } ?>
