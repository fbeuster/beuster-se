<?php

  function moduleDonate() {
    $content  = "<script id='fbvkn0d'>\n";
    $content  .= "  (function(i){\n";
    $content  .= "    var f,s=document.getElementById(i);\n";
    $content  .= "    f=document.createElement('iframe');\n";
    $content  .= "    f.src='//api.flattr.com/button/view/?uid=beuster-se&button=compact&url='+encodeURIComponent(document.URL);\n";
    $content  .= "    f.title='Flattr';\n";
    $content  .= "    f.height=20;\n";
    $content  .= "    f.width=110;\n";
    $content  .= "    f.style.borderWidth=0;\n";
    $content  .= "    s.parentNode.insertBefore(f,s);\n";
    $content  .= "  })('fbvkn0d');\n";
    $content  .= "</script>\n";
    $donate   = new SidebarModule(null, $content, "donate");
    return $donate->getModuleHTML();
  }

  function moduleTopArticles () {
    $title  = "viel gelesene Artikel";
    $top    = new SidebarModule($title, genTopArticles(), "top list");
    return $top->getModuleHTML();
  }

  function moduleLastArticles () {
    $n      = 5;
    $title  = "Letzten '.$n.' Artikel";
    $last   = new SidebarModule($title, genLastArticles($n), "list");
    return $last->getModuleHTML();
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

    $adsense = new SidebarModule(null, $content, "adSense", $id);
    return $adsense->getModuleHTML();
  }

  function moduleSocialShare() {
    $content  = '<ul class="socialInteraction">'."\n";
    $content  .= ' <li><a href="http://www.youtube.com/user/waterwebdesign" class="socialLinkYoutube" title="YouTube"></a></li>'."\n";
    $content  .= ' <li><a href="https://twitter.com/#!/FBeuster" class="socialLinkTwitter" title="Twitter"></a></li>'."\n";
    $content  .= ' <li><a href="https://www.facebook.com/beusterse" class="socialLinkFacebook" title="Facebook"></a></li>'."\n";
    $content  .= ' <li><a href="https://plus.google.com/102857640059997003370" class="socialLinkGoogle" title="Google+" rel="publisher"></a></li>'."\n";
    $content  .= '</ul>'."\n";
    $share    = new SidebarModule(null, $content, "socialInteraction");
    return $share->getModuleHTML();
  }

  function moduleArticleInfo($info) {
    $author   = $info['author'];
    $link     = 'http://'.$_SERVER['HTTP_HOST'].$info['link'];
    $title    = "informationen zum artikel";

    $break    = ' <br class="clear">';
    $content  = '<dl>';
    $content  .= ' <dt>Veröffentlicht</dt>';
    $content  .= ' <dd><time datetime="'.$info['datAttr'].'" class="long">'.$info['date'].'</time></dd>';
    $content  .= $break;
    $content  .= ' <dt>Autor</dt>';
    $content  .= ' <dd><a href="/aboutAuthor/'.$author->getName().'">'.$author->getClearname().'</a></dd>';
    $content  .= $break;
    $content  .= ' <dt>Link</dt>';
    $content  .= ' <dd class="articleLink"><a href="'.$link.'" title="'.$link.'">'.$link.'</a></dd>';
    $content  .= $break;
    $content  .= '</dl>';
    $content  .= '<p class="info">Mit einem * gekennzeichnete Links sind Amazon Affiliate Links.</p>';

    $info     = new SidebarModule($title, $content, "articleInfo");
    return $info->getModuleHTML();
  }

  function moduleSearch() {
    $content = '<form action="/search" method="post">'."\n";
    $content .= ' <input type="text" name="s" id="field" placeholder="Wonach möchtest du suchen?">'."\n";
    $content .= ' <input type="submit" value="" name="search" title="Suchen">'."\n";
    $content .= ' <br class="clear">'."\n";
    $content .= '</form>'."\n";
    $search = new SidebarModule(null, $content, "searchBox");
    return $search->getModuleHTML();
  }

  function moduleRandomArticle() {
    $db = Database::getDB()->getCon();
    $ena = 1;

    $done = false;
    while(!$done) {
      $id = mt_rand(0,getAnzNews());
      if(newsExists($id) && isNewsVisible($id)) {
        $done = true;
      }
    }
    $sql = "SELECT
              ID,
              Titel,
              Inhalt
            FROM
              news
            WHERE
              enable = ? AND
              ID = ? AND
              Datum < NOW()";
    if(!$result = $db->prepare($sql))
      return $db->error;

    $result->bind_param('ii', $ena, $id);

    if(!$result->execute())
      return $result->error;

    $result->bind_result($newsid, $newstitel, $newsinhalt);

    if(!$result->fetch())
      return 'Es wurde keine News mit dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';

    $result->close();

    if('[yt]' == substr($newsinhalt,0,4)) {
      $preApp = '<p class="randomText" style="text-indent:0;">';
    } else {
      $preApp = '<p class="randomText">';
    }

    $backApp = '</p>';
    $catName = getCatName(getNewsCat($id));
    $art_title  = '<h5 class="randomTitle">'.Parser::parse($newstitel, Parser::TYPE_PREVIEW).'</h5>'."\n";
    $art_text   = str_replace('###link###', getLink($catName, $newsid, $newstitel), Parser::parse($newsinhalt, Parser::TYPE_PREVIEW, 200));
    $content    = $art_title . $preApp . $art_text . $backApp;
    $title      =  "Kennst du schon...";
    $random     = new SidebarModule($title, $content, "randomArticle");
    return $random->getModuleHTML();
  }

  function moduleArchive() {
    $title    = "Schau mal ins Archiv";
    $content  = '<ul class="articleArchiveMain">'."\n";
    for($year = (int)date("Y"); $year >= 2010; $year--) {

      if(articlesInDate($year) === 0) continue;

      $months = '';
      for($month = 12; $month >= 1; $month--) {

        $numberMonth = articlesInDate($year, $month);
        if($numberMonth === 0)
          continue;

        $month_str  = '<li><a href="/'.$year.'/'.$month.'">';
        $month_str  .= makeMonthName($month);
        $month_str  .= ' <span class="number" style="color: #999999;">('.$numberMonth.')</span>';
        $month_str  .= '</a></li>'."\n";
        $months     .= $month_str;
      }

      $year_title = '  <span class="articleArchiveYear" style="cursor: pointer;">'.$year."</span>\n";
      $year_list  = '  <ul class="articleArchiveSub">'."\n" . $months . '  </ul>'."\n";
      $year_str   = $year_title . $year_list;
      $content    .= ' <li>'."\n".$year_str.' </li>'."\n";
    }
    $content .= '</ul>'."\n";
    $archive  = new SidebarModule($title, $content, "archive list");

    return $archive->getModuleHTML();
  }

  function moduleRecommendedArticle($article_id) {
    if($article_id === null) return;

    $article    = new Article($article_id);
    $title      = "Empfohlener Artikel:";
    $content    = " <a href='" . $article->getLink() . "'>\n  " . $article->getTitle() . "\n </a>\n";
    $recommend  = new SidebarModule($title, $content, "recommend");

    return $recommend->getModuleHTML();
  }
?>