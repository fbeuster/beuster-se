<!DOCTYPE html>
<html dir="ltr" lang="de-DE" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo getPageDescription(); ?>">
  <meta name="loaded_lang" content="<?php echo Config::getConfig()->get('language'); ?>">
  <?php
    $page = Lixter::getLix()->getPage();
    $keywords = '';

    if ($page->getType() === Page::CATEGORY_PAGE || $page->getType() === Page::CONTENT_PAGE) {
      $keywords = $page->getTags() . ', ';
    }
  ?>
<meta name="keywords" content="<?php echo $keywords; ?> beuster{se}, Felix Beuster, Blog, News, Tutorials">
<meta name="author" content="Felix Beuster">
  <meta property='og:locale' content='de_de'/>
  <meta property='fb:admins' content='100002550334323'/>
  <meta property='og:title' content='<?php echo getPageTitle($file); ?>'/>
  <meta property='og:url' content='<?php echo getPageUrl(); ?>'/>
  <meta property='og:site_name' content='beusterse.de'/>
  <meta property='og:type' content='website'/>
  <meta property='og:image' content='<?php echo getPageOGImage(); ?>'/>
  <meta property='og:description' content='<?php echo getPageDescription(); ?>'/>

  <link rel="alternate" type="application/rss+xml" title="RSS" href="/rss.xml" />
  <title><?php echo getPageTitle($file); ?></title>

  <link href="/<?php echo Lixter::getLix()->getTheme()->getFile('assets/css/styles.css'); ?>" rel="stylesheet" type="text/css" media="screen">

  <!-- wanna play javascript? -->
  <script type="text/javascript" src="/<?php echo Lixter::getLix()->getTheme()->getFile('assets/js/beusterse.js'); ?>"></script>

  <?php if(isset($_GET['p']) && in_array($_GET['p'], $noGA)) { ?>
  <script type="text/javascript" src="/<?php echo Lixter::getLix()->getTheme()->getFile('assets/js/scriptAdm.js'); ?>"></script>
  <?php } ?>

  <?php if($page->getType() == Page::CONTENT_PAGE && $page->getRefreshName() !== '') {
    echo '<meta http-equiv="refresh" content="3; url=/'.$page->getRefreshName().'">';
  } ?>
 </head>
 <body class="<?php echo makeBodyClass($currPage); ?>">
  <header>
    <div class="wrapper">
      <?php if($currPage == 'index') { ?>
      <h1><a href="/">beuster{se}</a></h1>
      <?php } else { ?>
      <span><a href="/">beuster{se}</a></span>
      <?php } ?>
      <nav>
        <menu><?php genMenu(); ?></menu>
        <div class="search">
          <form action="/search" method="post">
            <input type="text" placeholder="Search for..." name="s">
            <input type="submit" value="" alt="search" name="search">
          </form>
        </div>
      </nav>
    </div>
  </header>
  <div class="wrapper">
