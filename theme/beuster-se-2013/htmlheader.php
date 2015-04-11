<!DOCTYPE html>
<?php
 echo '<!--'.date('d.m.Y H:i',time()).'-->
<!--'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.http_build_query ($_GET).'-->
';?>
<html dir="ltr" lang="de-DE" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
 <head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?php echo getPageDescription(); ?>">
  <?php if(isset($ret['data']['article']) && count($ret['data']['article']) == 1 && $ret['data']['article'][0]->hasTags()) { ?>
<meta name="keywords" content="<?php echo $ret['data']['articles'][0]->getTagsString(); ?>, beuster{se}, Felix Beuster, Blog, News, Tutorials">
  <?php } else { ?>
<meta name="keywords" content="beuster{se}, Felix Beuster, Blog, News, Tutorials">
  <?php } ?>
<meta name="author" content="Felix Beuster">
  <meta property='og:locale' content='de_de'/>
  <meta property='fb:admins' content='100002550334323'/>
  <meta property='og:title' content='<?php echo getPageTitle($file); ?>'/>
  <meta property='og:url' content='<?php echo getPageUrl(); ?>'/>
  <meta property='og:site_name' content='beusterse.de'/>
  <meta property='og:type' content='website'/>
  <meta property='og:image' content='<?php echo getPageOGImage($ret['data']); ?>'/>
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
  <title><?php echo getPageTitle($file); ?></title>
  <?php if(!$ieOld) { ?>
  <!-- style it -->
  <link href="<?php echo getThemeStyle('styles/main.css'); ?>" rel="stylesheet" type="text/css" media="screen">
  <?php } else { ?>
  <!-- not another internet explorer -.- -->
  <link href="/settings/ie.css" rel="stylesheet" type="text/css" media="screen">
  <style type="text/css">
  .alert {
    font-family: sans-Serif;
    font-size: 18px;
    color: red;
  }
  </style>
  <?php } ?>
  <!-- wanna play javascript? -->
  <script type="text/javascript" src="<?php echo getThemeStyle('scripts/beusterse.js'); ?>"></script>
  <?php if(isset($_GET['p']) && in_array($_GET['p'], $noGA)) { ?>
  <script type="text/javascript" src="/settings/scriptAdm.js"></script>
  <?php } ?>
  <?php if(isset($ret['data']['refresh'])) {
    echo '<meta http-equiv="refresh" content="3; url=/'.$ret['data']['refresh'].'">';
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