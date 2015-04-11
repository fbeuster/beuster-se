<?php

    function moduleDonate() {
        $ret = "";
        $ret .= '<div class="beMainAsideEntry donate">'."\n";
        $ret .= "<script id='fbvkn0d'>\n";
        $ret .= "    (function(i){\n";
        $ret .= "        var f,s=document.getElementById(i);\n";
        $ret .= "        f=document.createElement('iframe');\n";
        $ret .= "        f.src='//api.flattr.com/button/view/?uid=beuster-se&button=compact&url='+encodeURIComponent(document.URL);\n";
        $ret .= "        f.title='Flattr';\n";
        $ret .= "        f.height=20;\n";
        $ret .= "        f.width=110;\n";
        $ret .= "        f.style.borderWidth=0;\n";
        $ret .= "        s.parentNode.insertBefore(f,s);\n";
        $ret .= "    })('fbvkn0d');\n";
        $ret .= "</script>\n";
        $ret .= "</div>\n";
        return $ret;
    }
    function moduleTopArticles ($mob) {
        if(!$mob) {
            $appPre = '<div class="beMainAsideEntry top">';
            $appPost = '</div>';
            return $appPre.genTopArticles($mob).$appPost;
        }
    }
 
    function moduleLastArticles ($mob) {
        if(!$mob) {
            $appPre = '<div class="moduleAside">';
            $appPost = '</div>';
            return $appPre.genLastArticles($mob).$appPost;
        }
    }
 
    function moduleAdSenseAside($mob, $local, $noGA) {
        $ret = '';
        if(!$mob) {
            $ret .= '<div class="beMainAsideEntry adSense" id="google-ads-1">'."\n";
            if($local || (isset($_GET['p']) && in_array($_GET['p'], $noGA))) {
                $ret .= 'Google AdSense'."\n";
            } else {
                $ret .= ' <script type="text/javascript"><!--'."\n";
                $ret .= '   adUnit = document.getElementById("google-ads-1");'."\r";
                $ret .= '   adWidth = adUnit.offsetWidth;'."\r";
                $ret .= '   google_ad_client = "ca-pub-4132935023049723";'."\n";
                $ret .= '   if ( adWidth >= 428 ) {                 '."\r";
                $ret .= '       /* responsiveGe428 */               '."\r";
                $ret .= '       google_ad_slot = "3206886433";      '."\r";
                $ret .= '       google_ad_width = 320;              '."\r";
                $ret .= '       google_ad_height = 50;              '."\r";
                $ret .= '   } else if ( adWidth >= 308 ) {          '."\r";
                $ret .= '       /* responsiveGe308 */               '."\r";
                $ret .= '       google_ad_slot = "2111929639";      '."\r";
                $ret .= '       google_ad_width = 300;              '."\r";
                $ret .= '       google_ad_height = 250;             '."\r";
                $ret .= '   } else if ( adWidth >= 288 ) {          '."\r";
                $ret .= '       /* responsiveGe288 */               '."\r";
                $ret .= '       google_ad_slot = "5065396031";      '."\r";
                $ret .= '       google_ad_width = 234;              '."\r";
                $ret .= '       google_ad_height = 60;              '."\r";
                $ret .= '   } else if ( adWidth >= 218 ) {          '."\r";
                $ret .= '       /* responsiveGe218 */               '."\r";
                $ret .= '       google_ad_slot = "6542129239";      '."\r";
                $ret .= '       google_ad_width = 200;              '."\r";
                $ret .= '       google_ad_height = 200;             '."\r";
                $ret .= '   } else if ( adWidth >= 198 ) {          '."\r";
                $ret .= '       /* responsiveGe198 */               '."\r";
                $ret .= '       google_ad_slot = "1972328831";      '."\r";
                $ret .= '       google_ad_width = 180;              '."\r";
                $ret .= '       google_ad_height = 150;             '."\r";
                $ret .= '   } else if ( adWidth >= 178 ) {          '."\r";
                $ret .= '       /* responsiveGe178 */               '."\r";
                $ret .= '       google_ad_slot = "3449062032";      '."\r";
                $ret .= '       google_ad_width = 120;              '."\r";
                $ret .= '       google_ad_height = 240;             '."\r";
                $ret .= '   } else {                                '."\r";
                $ret .= '       /* Do not display the Google Ad */  '."\r";
                $ret .= '       google_ad_slot = "0";               '."\r";
                $ret .= '       adUnit.style.display = "none";      '."\r";
                $ret .= '   }                                       '."\r";
                $ret .= '  //-->'."\n";
                $ret .= ' </script>'."\n";
                $ret .= ' <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">'."\n";
                $ret .= ''."\n";
                $ret .= ' </script>'."\n";
            }
            $ret .= '</div>'."\n";
        }
        return $ret;
    }
 
    function moduleSocialShare($mob) {
        $ret = '';
        $ret .= '<div class="beMainAsideEntry socialInteraction">'."\n";
        if(false && !$mob) {
            $ret .= ' <h2>Teile diesen Artikel</h2>'."\n";
            $ret .= ' <div id="socialshareprivacy"></div>'."\n";
        }
        $ret .= ' <ul class="socialInteraction">'."\n";
        $ret .= '    <li><a href="http://www.youtube.com/user/waterwebdesign" class="socialLinkYoutube" title="YouTube"></a></li>'."\n";
        $ret .= '    <li><a href="https://twitter.com/#!/FBeuster" class="socialLinkTwitter" title="Twitter"></a></li>'."\n";
        $ret .= '    <li><a href="https://www.facebook.com/beusterse" class="socialLinkFacebook" title="Facebook"></a></li>'."\n";
        $ret .= '    <li><a href="https://plus.google.com/102857640059997003370" class="socialLinkGoogle" title="Google+" rel="publisher"></a></li>'."\n";
        $ret .= ' </ul>'."\n";
        $ret .= '</div>'."\n";
        return $ret;
    }
 
    function moduleArticleInfo($mob, $info) {
        $author = $info['author'];
        $lnk = 'http://'.$_SERVER['HTTP_HOST'].$info['link'];
        $ret = '';
        $ret .= '<div class="beMainAsideEntry articleInfo">'."\n";
        $ret .= ' <span class="entryInfo">informationen zum artikel</span>';
        $ret .= ' <dl>';
        $ret .= '  <dt>Veröffentlicht</dt>';
        $ret .= '  <dd><time datetime="'.$info['datAttr'].'" class="long">'.$info['date'].'</time></dd>';
        $ret .= '  <br class="clear">';
        $ret .= '  <dt>Autor</dt>';
        $ret .= '  <dd><a href="/aboutAuthor/'.$author->getName().'">'.$author->getClearname().'</a></dd>';
        $ret .= '  <br class="clear">';
        $ret .= '  <dt>Link</dt>';
        $ret .= '  <dd class="articleLink"><a href="'.$lnk.'" title="'.$lnk.'">'.$lnk.'</a></dd>';
        $ret .= '  <br class="clear">';
        $ret .= ' </dl>';
        $ret .= ' <p class="info">Mit einem * gekennzeichnete Links sind Amazon Affiliate Links.</p>';
        $ret .= '</div>'."\n";
        return $ret;
    }
 
    function moduleSearch() {
        $ret = '';
        $ret .= '<div class="beMainAsideEntry searchBox">'."\n";
        $ret .= ' <form action="/search" method="post">'."\n";
        $ret .= '  <input type="text" name="s" id="field" placeholder="Wonach möchtest du suchen?">'."\n";
        $ret .= '  <input type="submit" value="" name="search" title="Suchen">'."\n";
        $ret .= '  <br class="clear">'."\n";
        $ret .= ' </form>'."\n";
        $ret .= '</div>'."\n";
        return $ret;
    }
 
    function moduleRandomArticle($mob) {
        $db = Database::getDB()->getCon();
        $ret = '';
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
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('ii', $ena, $id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($newsid, $newstitel, $newsinhalt);
        if(!$result->fetch()) {return 'Es wurde keine News mit dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $result->close();
        if('[yt]' == substr($newsinhalt,0,4)) {$preApp = '<p class="randomText" style="text-indent:0;">';} else {$preApp = '<p class="randomText">';}
        $backApp = '</p>';                  
        $catName = getCatName(getNewsCat($id));
        $ret .= '<div class="beMainAsideEntry randomArticle">'."\n";
        $ret .= '  <span class="entryInfo">Kennst du schon...</span>';
        $ret .= '<h5 class="randomTitle">'.changetext($newstitel, 'titel', $mob).'</h5>'."\n";
        $ret .= $preApp.str_replace('###link###', getLink($catName, $newsid, $newstitel), changetext($newsinhalt, 'vorschau', $mob, 200)).$backApp;
        $ret .= '</div>'."\n";
        return $ret;
    }
    
    function moduleArchive($mob) {
        $ret = '';
        if(!$mob) {
            $ret .= '<div class="beMainAsideEntry archive" style="max-height: inherit !important;">'."\n";
            $ret .= ' <span class="entryInfo">schau mal ins Archiv</span>'."\n";
            $ret .= ' <ul class="articleArchiveMain">'."\n";
            for($year = (int)date("Y"); $year >= 2010; $year--) {
                $numberYear= articlesInDate($year);
                if($numberYear > 0) {
                    $ret .= '  <li>'."\n";
                    $ret .= '   <span class="articleArchiveYear" style="cursor: pointer;">'.$year."</span>\n";
                    $ret .= '   <ul class="articleArchiveSub">'."\n";
                    if($year == (int)date("Y") && (int)date("m") < 12) {
                        $m = (int)date("m");
                    } else {
                        $m = 12;
                    }
                    for($month = $m; $month >= 1; $month--) {
                        $numberMonth = articlesInDate($year, $month);
                        if($numberMonth > 0) {
                            $ret .= '    <li>'."\n";
                            $ret .= '     <a href="/'.$year.'/'.$month.'">';
                            $ret .= '     '.makeMonthName($month).' <span class="number" style="color: #999999;">('.$numberMonth.')</span>';
                            $ret .= '     </a>'."\n";
                            $ret .= '    </li>'."\n";
                        }
                    }
                    $ret .= '   </ul>'."\n";
                    $ret .= '  </li>'."\n";
                }
            }
            $ret .= ' </ul>'."\n";
            $ret .= '</div>'."\n";
        }
        return $ret;
    }
?>