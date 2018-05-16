<?php

  function moduleDonate() {
    $content  = "<script id='fbvkn0d'>\n";
    $content  .= "  (function(i){\n";
    $content  .= "    var f,s=document.getElementById(i);\n";
    $content  .= "    f=document.createElement('iframe');\n";
    $content  .= "    f.src='//api.flattr.com/button/view/?uid=beuster-se&button=compact&url='+encodeURIComponent(document.URL);\n";
    $content  .= "    f.title='Flattr';\n f.height=20;\n f.width=110;\n f.style.borderWidth=0;\n";
    $content  .= "    s.parentNode.insertBefore(f,s);\n";
    $content  .= "  })('fbvkn0d');\n";
    $content  .= "</script>\n";

    $config = array("title" => "",
                    "classes" => "donate",
                    "content" => $content);
    $donate   = new SidebarModuleContent($config);
    return $donate->getHTML();
  }

  function moduleAdSenseAside() {
    $id = "google-ads-1";

    if (Utilities::isDevServer() ||
        (isset($_GET['p']) && AdminPage::exists($_GET['p']))) {
      $content = 'Google AdSense'."\n";

    } else {
      $content = '<script type="text/javascript"><!--'."\n";
      $content .= '  adUnit = document.getElementById("google-ads-1");'."\r";
      $content .= '  adWidth = adUnit.offsetWidth;'."\r";
      $content .= '  google_ad_client = "ca-pub-4132935023049723";'."\n";
      $content .= '  if ( adWidth >= 428 ) {                 '."\r";
      $content .= '    /* responsiveGe428 */               '."\r";
      $content .= '    google_ad_slot = "3206886433";      '."\r";
      $content .= '    google_ad_width = 320;              '."\r";
      $content .= '    google_ad_height = 50;              '."\r";
      $content .= '  } else if ( adWidth >= 308 ) {          '."\r";
      $content .= '    /* responsiveGe308 */               '."\r";
      $content .= '    google_ad_slot = "2111929639";      '."\r";
      $content .= '    google_ad_width = 300;              '."\r";
      $content .= '    google_ad_height = 250;             '."\r";
      $content .= '  } else if ( adWidth >= 288 ) {          '."\r";
      $content .= '    /* responsiveGe288 */               '."\r";
      $content .= '    google_ad_slot = "5065396031";      '."\r";
      $content .= '    google_ad_width = 234;              '."\r";
      $content .= '    google_ad_height = 60;              '."\r";
      $content .= '  } else if ( adWidth >= 218 ) {          '."\r";
      $content .= '    /* responsiveGe218 */               '."\r";
      $content .= '    google_ad_slot = "6542129239";      '."\r";
      $content .= '    google_ad_width = 200;              '."\r";
      $content .= '    google_ad_height = 200;             '."\r";
      $content .= '  } else if ( adWidth >= 198 ) {          '."\r";
      $content .= '    /* responsiveGe198 */               '."\r";
      $content .= '    google_ad_slot = "1972328831";      '."\r";
      $content .= '    google_ad_width = 180;              '."\r";
      $content .= '    google_ad_height = 150;             '."\r";
      $content .= '  } else if ( adWidth >= 178 ) {          '."\r";
      $content .= '    /* responsiveGe178 */               '."\r";
      $content .= '    google_ad_slot = "3449062032";      '."\r";
      $content .= '    google_ad_width = 120;              '."\r";
      $content .= '    google_ad_height = 240;             '."\r";
      $content .= '  } else {                                '."\r";
      $content .= '    /* Do not display the Google Ad */  '."\r";
      $content .= '    google_ad_slot = "0";               '."\r";
      $content .= '    adUnit.style.display = "none";      '."\r";
      $content .= '  }                                       '."\r";
      $content .= ' //-->'."\n";
      $content .= '</script>'."\n";
      $content .= '<script type="text/javascript" src="https://pagead2.googlesyndication.com/pagead/show_ads.js">'."\n";
      $content .= '</script>'."\n";
    }

    $config = array("title" => "",
                    "classes" => "adSense",
                    "id" => $id,
                    "content" => $content);
    $adsense   = new SidebarModuleContent($config);
    return $adsense->getHTML();
  }

  function moduleGoogleAdSense() {
    $classes = "google_adsense";

    if (Utilities::isDevServer() || User::newFromCookie()) {
      $content = 'Google AdSense';

    } else {
      $content = Config::getConfig()->get('ext', 'google_adsense_ad');
    }

    $config = array("title"   => "",
                    "classes" => $classes,
                    "id"      => "google-adsense-1",
                    "content" => $content);
    $adsense = new SidebarModuleContent($config);
    return $adsense->getHTML();
  }

  /**
   * self optimizing link widget
   *
   * Colors are default for now, size can be adjusted.
   */
  function moduleAmazon($width, $height) {
    $amazon_tag = Config::getConfig()->get('ext', 'amazon_tag');
    $classes    = "amazon w".$width;

    if (Utilities::isDevServer() || User::newFromCookie()) {
      $content = 'Amazon Widget'."\n";

    } else {
      # TODO make configuration for these settings
      $background_color = '191f1f';
      $category         = 'Electronics';
      $region           = 'DE';
      $search           = '';
      $theme            = 'dark';

      $content = '<script charset="utf-8" type="text/javascript">'.
                  'amzn_assoc_ad_type = "responsive_search_widget";'.
                  'amzn_assoc_tracking_id = "'.$amazon_tag.'";'.
                  'amzn_assoc_marketplace = "amazon";'.
                  'amzn_assoc_region = "'.$region.'";'.
                  'amzn_assoc_placement = "";'.
                  'amzn_assoc_search_type = "search_widget";'.
                  'amzn_assoc_width = '.$width.';'.
                  'amzn_assoc_height = 250;'.
                  'amzn_assoc_default_search_category = "'.$category.'";'.
                  'amzn_assoc_default_search_key = "'.$search.'";'.
                  'amzn_assoc_theme = "'.$theme.'";'.
                  'amzn_assoc_bg_color = "'.$background_color.'";'.
                  '</script>'.
                  '<script src="//z-eu.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&Operation=GetScript&ID=OneJS&WS=1&MarketPlace='.$region.'"></script>';
    }

    $config = array("title"   => "",
                    "classes" => $classes,
                    "id"      => "amazon-ads-1",
                    "content" => $content);
    $amazon = new SidebarModuleContent($config);
    return $amazon->getHTML();
  }

  function moduleArticleInfo($page) {
    $author   = $page->getArticle()->getAuthor();
    $title    = "informationen zum artikel";

    $break    = ' <br class="clear">';
    $content  = '<dl>';
    $content  .= ' <dt>Veröffentlicht</dt>';
    $content  .= ' <dd><time datetime="'.$page->getArticle()->getDateFormatted('c').'" class="long">'.$page->getArticle()->getDateFormatted('d.m.Y H:i').'</time></dd>';
    $content  .= $break;
    $content  .= ' <dt>Autor</dt>';
    $content  .= ' <dd><a href="/'.$author->getName().'">'.$author->getClearname().'</a></dd>';
    $content  .= $break;
    $content  .= ' <dt>Link</dt>';
    $content  .= ' <dd class="articleLink"><a href="'.$page->getLink().'" title="'.$page->getLink().'">'.$page->getLink().'</a></dd>';
    $content  .= $break;
    $content  .= '</dl>';
    $content  .= '<p class="info">Mit einem * gekennzeichnete Links sind Amazon Affiliate Links.</p>';


    $config = array("title" => $title,
                    "classes" => "articleInfo",
                    "content" => $content);
    $info   = new SidebarModuleContent($config);
    return $info->getHTML();
  }

  function moduleRandomArticle() {
    $article  = getRandomArticle();
    $style    = '';


    if('[yt]' == substr($article->getContent(),0,4)) {
      $style = ' style="text-indent:0;"';
    }

    $preApp     = "<p class='randomText' $style >";
    $backApp    = "</p>\n";
    $art_title  = '<h5 class="randomTitle">'.Parser::parse($article->getTitle(), Parser::TYPE_PREVIEW, 250).'</h5>'."\n";
    $art_text   = $article->getContentPreview();
    $art_text .= '<a href="'.$article->getLink().'"> weiter</a>';
    $article_html    = $art_title . $preApp . $art_text . $backApp;

    $config = array("title" => "Kennst du schon...",
                    "classes" => "randomArticle",
                    "content" => $article_html);
    $random = new SidebarModuleContent($config);
    return $random->getHTML();
  }

  function moduleRecommendedArticle($article_id) {
    if($article_id === null) return;

    $article    = new Article($article_id);
    $config = array("title" => "Empfohlener Artikel:",
                    "classes" => "recommend",
                    "content" => "<a href='" . $article->getLink() . "'>\n  " . $article->getTitle() . "\n </a>\n");
    $recommend  = new SidebarModuleContent($config);

    return $recommend->getHTML();
  }
?>