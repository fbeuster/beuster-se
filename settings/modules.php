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
    $donate   = new SidebarContentModule($config);
    return $donate->getHTML();
  }

  function moduleTopArticles() {
    $config = array("title" => "viel gelesene Artikel",
                    "classes" => "top list",
                    "list" => getTopArticles());
    $top    = new SidebarListModule($config);
    return $top->getHTML();
  }

  function moduleLastArticles() {
    $n      = 5;
    $config = array("title" => "Letzten $n Artikel",
                    "classes" => "list",
                    "list" => getLastArticles($n));
    $last   = new SidebarListModule($config);
    return $last->getHTML();
  }

  function moduleAdSenseAside($noGA) {
    $id = "google-ads-1";

    if(Utilities::isDevServer() || (isset($_GET['p']) && in_array($_GET['p'], $noGA))) {
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
      $content .= '<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">'."\n";
      $content .= '</script>'."\n";
    }

    $config = array("title" => "",
                    "classes" => "adSense",
                    "id" => $id,
                    "content" => $content);
    $adsense   = new SidebarContentModule($config);
    return $adsense->getHTML();
  }

  function moduleSocialShare() {
    $config = array("title" => '',
                    "classes" => "socialInteraction",
                    "list" => array(
                      '<a href="http://www.youtube.com/user/waterwebdesign" class="socialLinkYoutube" title="YouTube"></a>',
                      '<a href="https://twitter.com/#!/FBeuster" class="socialLinkTwitter" title="Twitter"></a>',
                      '<a href="https://www.facebook.com/beusterse" class="socialLinkFacebook" title="Facebook"></a>',
                      '<a href="https://plus.google.com/102857640059997003370" class="socialLinkGoogle" title="Google+" rel="publisher"></a>'));
    $test = new SidebarListModule($config);
    return $test->getHTML();
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
    $content  .= ' <dd><a href="/aboutAuthor/'.$author->getName().'">'.$author->getClearname().'</a></dd>';
    $content  .= $break;
    $content  .= ' <dt>Link</dt>';
    $content  .= ' <dd class="articleLink"><a href="'.$page->getLink().'" title="'.$page->getLink().'">'.$page->getLink().'</a></dd>';
    $content  .= $break;
    $content  .= '</dl>';
    $content  .= '<p class="info">Mit einem * gekennzeichnete Links sind Amazon Affiliate Links.</p>';


    $config = array("title" => $title,
                    "classes" => "articleInfo",
                    "content" => $content);
    $info   = new SidebarContentModule($config);
    return $info->getHTML();
  }

  function moduleSearch() {
    $config = array("classes" => "searchBox");
    $search = new SidebarSearchModule($config);
    return $search->getHTML();
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
    $art_text   = str_replace('###link###', $article->getLink(), $article->getContentPreview());
    $article_html    = $art_title . $preApp . $art_text . $backApp;

    $config = array("title" => "Kennst du schon...",
                    "classes" => "randomArticle",
                    "content" => $article_html);
    $random = new SidebarContentModule($config);
    return $random->getHTML();
  }

  function moduleArchive() {
    $list = array();

    for($year = (int)date("Y"); $year >= 2010; $year--) {

      if(articlesInDate($year) === 0) continue;

      $month_list = array();
      for($month = 12; $month >= 1; $month--) {

        $numberMonth = articlesInDate($year, $month);
        if($numberMonth === 0)
          continue;

        $month_str  = '<a href="/'.$year.'/'.$month.'">';
        $month_str  .= makeMonthName($month);
        $month_str  .= ' <span class="number" style="color: #999999;">('.$numberMonth.')</span>';
        $month_str  .= '</a>';
        $month_list[] = $month_str;
      }

      $year_title = '<span class="articleArchiveYear" style="cursor: pointer;">'.$year."</span>";

      $list[$year_title] = $month_list;
    }

    $config = array("title" => "Schau mal ins Archiv",
                    "classes" => "archive list",
                    "list" => $list);

    $archive  = new SidebarListModule($config);
    return $archive->getHTML();
  }

  function moduleRecommendedArticle($article_id) {
    if($article_id === null) return;

    $article    = new Article($article_id);
    $config = array("title" => "Empfohlener Artikel:",
                    "classes" => "recommend",
                    "content" => "<a href='" . $article->getLink() . "'>\n  " . $article->getTitle() . "\n </a>\n");
    $recommend  = new SidebarContentModule($config);

    return $recommend->getHTML();
  }
?>