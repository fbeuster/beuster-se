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
  <meta name="loaded_lang" content="<?php echo Config::getConfig()->get('language'); ?>">
  <?php if(isset($ret['data']['article']) && count($ret['data']['article']) == 1 && $ret['data']['article'][0]->hasTags()) { ?>
<meta name="keywords" content="<?php echo $ret['data']['articles'][0]['tags']; ?>, beuster{se}, Felix Beuster, Blog, News, Tutorials">
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

  <link rel="alternate" type="application/rss+xml" title="RSS" href="/rss.xml" />
  <title><?php echo getPageTitle($file); ?></title>

  <!-- wanna play javascript? -->
  <?php if(isset($_GET['p']) && in_array($_GET['p'], $noGA)) { ?>
  <script type="text/javascript" src="<?php echo Lixter::getLix()->getTheme()->getFile('scripts/scriptAdm.js'); ?>"></script>
  <?php } ?>
  <?php if(isset($ret['data']['refresh'])) {
    echo '<meta http-equiv="refresh" content="3; url=/'.$ret['data']['refresh'].'">';
  } ?>
 </head>
 <body>
  <header>
    <?php if($currPage == 'index') { ?>
    <h1>
      beuster{se}
      <span>
        Blog, Videos und News
      </span>
    </h1>
    <?php } else { ?>
    <span>
      beuster{se}
      <span>
        Blog, Videos und News
      </span>
    </span>
    <?php } ?>
    <nav>
      <menu><?php genMenu(); ?></menu>
    </nav>
  </header>