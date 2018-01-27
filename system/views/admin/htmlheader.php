<!DOCTYPE html>
<html dir="ltr" lang="de-DE" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo getPageDescription(); ?>">
  <meta name="loaded_lang" content="<?php echo Config::getConfig()->get('site', 'language'); ?>">
  <?php
    $page = Lixter::getLix()->getPage();
  ?>
<meta name="keywords" content="beuster{se}, Felix Beuster, Blog, News, Tutorials">
<meta name="author" content="Felix Beuster">
  <meta property='og:locale' content='de_de'/>
  <meta property='fb:admins' content='100002550334323'/>
  <meta property='og:title' content='<?php echo $page->getTitle().' - '.$config->get('meta', 'name'); ?>'/>
  <meta property='og:url' content='<?php echo getPageUrl(); ?>'/>
  <meta property='og:site_name' content='beusterse.de'/>
  <meta property='og:type' content='website'/>
  <meta property='og:image' content='<?php echo getPageOGImage(); ?>'/>
  <meta property='og:description' content='<?php echo getPageDescription(); ?>'/>

  <link rel="alternate" type="application/rss+xml" title="RSS" href="/rss.xml" />
  <title><?php echo $page->getTitle().' - '.$config->get('meta', 'name'); ?></title>

  <link href="/<?php echo Lixter::getLix()->getSystemFile('assets/css/admin/styles.css'); ?>" rel="stylesheet" type="text/css" media="screen">

  <!-- wanna play javascript? -->
  <script type="text/javascript" src="/<?php echo Lixter::getLix()->getSystemFile('assets/js/admin/admin.js'); ?>"></script>

  <?php if($page->getRefreshName() !== '') {
    echo '<meta http-equiv="refresh" content="3; url=/'.$page->getRefreshName().'">';
  } ?>
 </head>
 <body class="article">
  <?php include_once(Lixter::getLix()->getSystemFile('assets/img/admin/icons.svg')); ?>
  <header>
    <div class="wrapper">
      <span><a href="/">beuster{se}</a></span>
    </div>
  </header>
  <div class="wrapper">
