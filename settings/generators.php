<?php
    function genGal($pics) {
        global $sysAdrr;
        $ret = '';
        $j = count($pics);
        $ret .= ' <div class="beContentEntryGallery">';
        $ret .= '  <ul class="beContentEntryGalleryImageList">';
        $i = 0;
        foreach($pics as $pic) {
            $i++;
            $pfad = str_replace('blog/id', 'blog/thid', $pic['pfad']);
            $pfad = str_replace('.', '_', $pfad);
            $pfad = $pfad.'.jpg';
            $ret .= '   <li><a href="http://'.$sysAdrr.'/'.$pic['pfad'].'">';
            $ret .= '    <img src="http://'.$sysAdrr.'/'.$pfad.'" id="pic'.$i.'" class="pic" alt="" name="'.$pic['name'].'" title="'.$pic['name'].'">';
            $ret .= '   </a></li>';
            $ret .= '   <li class="hasjs"><img src="http://'.$sysAdrr.'/'.$pfad.'" id="pic'.$i.'" class="pic" alt="" name="'.$pic['name'].'" title="'.$pic['name'].'"></li>';
        }
        $ret .= '  </ul>';
        $ret .= '  <br class="clear">'."\r";
        $ret .= '  <span id="galL" title="Zurück">&nbsp;</span>';
        $ret .= '  <div class="beContentEntryGalleryBigImage">';
        $ret .= '   <img src="http://'.$sysAdrr.'/images/spacer.gif" alt="picL.0" id="pic">';
        $ret .= '  </div>';
        $ret .= '  <span id="galR" title="Weiter">&nbsp;</span>';
        $ret .= '  <br class="clear">';
        $ret .= ' </div>';
        return $ret;
    }
 
    function genPager($anzPages, $currPage, $dest, $mob) {
        $pagesToSee = 5;
        $ret = '';
        if($mob) {
            $i = $currPage - 3;
            $j = $currPage + 3;
            if($i < 1) $i = 1;
            if($j > $anzPages) $j = $anzPages;
            $t = $i;
        } else {
            $i = 1;
            $j = $anzPages;
            $t = 1;
        }
        $ret .= '<br class="clear">'."\r";
        $ret .= '<!-- Pager ANFANG -->'."\r";
        $ret .= '<div id="pager"';
        if($mob) {
            $ret .= 'style="width:'.($j*27+50).'px;"';
        }
        $ret .= '>'."\r";
        $ret .= ' <a href="';
        if($currPage > 1) {
            $ret .= $dest.($currPage - 1);
        } else {
            $ret .= '#';
        }
        $ret .= '" class="pArrows" id="pagerleft" title="Zurück">&nbsp;</a>'."\r";
        $ret .= ' <div id="number"';
        if($j < 5) {
            $ret .= 'style="text-align:center;';
            if($mob) {
                $ret .= 'width:'.($j*27).'px;';
            }
            $ret .= '"';
        }
        $ret .= '>'."\r";
        $ret .= '  <ul class="pnr" id="pnr" style="width:'.($j*27-2).'px">'."\r";
        for($i; $i <= $j; $i++) {
            $link = '   <li';
            if($i == $currPage) {
                $link .= ' class="themecolor"';
            }
            if($i == $t) {
                $link .= ' style="display: block;"';
            }
            $link .= '><a href="';
            if($i == $currPage) {
                $link .= '#';
            } else {
                $link .= $dest.$i;
            }
            $link .= '"';
            if($i == $t && $i == $j) {
                $link .= ' style="border: 0;"';
            }
            if($i == $t) {
                $link .= ' style="border-left: 0;"';
            }
            if($i == $j) {
                $link .= ' style="border-right: 0;"';
            }
            $link .= '>'.$i.'</a></li>'."\r";
            $ret .= $link;
        }
        $ret .= '  </ul>'."\r";
        $ret .= ' </div>'."\r";
        $ret .= ' <a href="';
        if($currPage < $anzPages && $anzPages > 5) {
            $ret .= $dest.($currPage + 1);
        } else {
            $ret .= '#';
        }
        $ret .= '" class="pArrows" id="pagerright" title="Weiter">&nbsp;</a>'."\r";
        $ret .= '</div>'."\r";
        return $ret;
    }
 
    function genFormPublic($err, $dest, $time, $mob, $bb, $title, $formType, $reply = 'null') {
        $ret = '';
        switch($err['t']) {
            case 1:
                $ret .= '<p class="alert">Das Formular ist unvollständig. Makierte Felder sind Pflicht.</p>'."\r";
                break;
            case 2:
                $ret .= '<p class="alert">Sie müssen die Zeit abwarten!</p>'."\r";
                break;
            case 3:
                $ret .= '<p class="alert">E-Mail ist ungültig</p>'."\r";
                break;
            case 4:
                $ret .= '<p class="alert">Dein Kommentar ist zu lang (max. 1500 Zeichen).</p>'."\r";
                break;
            default:
                break;
        }
        $ret .= '<script type="text/javascript"></script>'."\r";
        $ret .= '<form action="'.$dest.'" method="post">'."\r";
        $ret .= '  <label for="usr">Name *:</label>'."\r";
        $ret .= '  <input type="text" name="usr"';
        if($err['t'] != 0 && $err['c'] != '') {
            $ret .= ' value="'.$err['c']['user'].'"';
        }
        $ret .= ' required>'."\r";
        $ret .= '  <br>'."\r";
        $ret .= '  <span class="antSp">'."\r";
        $ret .= '   <label for="email">Die Abfrage erfolt in einem anderen Feld. Tragen Sie hier bitte NICHTS ein!</label>'."\r";
        $ret .= '   <input type="email" name="email">'."\r";
        $ret .= '   <br>'."\r";
        $ret .= '  </span>'."\r";
        $ret .= '  <label for="usrml">Mail *:</label>'."\r";
        $ret .= '  <input type="text" name="usrml"';
        if($err['t'] != 0 && $err['c'] != '') {
            $ret .= ' value="'.$err['c']['mail'].'"';
        }
        $ret .= ' required>'."\r";
        $ret .= '  <br>'."\r";
        $ret .= '  <label for="usrpg">Website:</label>'."\r";
        $ret .= '  <input type="text" name="usrpg"';
        if($err['t'] != 0 && $err['c'] != '') {
            $ret .= ' value="'.$err['c']['page'].'"';
        }
        $ret .= '>'."\r";
        $ret .= '  <br>'."\r";
        $ret .= '  <label for="usrcnt">Nachricht *:</label>'."\r";
        $ret .= '  <textarea name="usrcnt" id="usrcnt" required>';
        if($err['t'] != 0 && $err['c'] != '') {
            $ret .= $err['c']['cnt'];
        }
        $ret .= '</textarea>'."\r";
        if($formType == 'commentForm') {
            $ret .= '<p class="beCommentNewInfo messageLength">'."\r";
            $ret .= '  Noch <span>1500</span> Zeichen übrig.'."\r";
            $ret .= '</p>'."\r";
        }
        /*if($bb != '' && !$mob) {
            $ret .= '  <p id="bb">'."\r";
            $ret .= '   <img class="button" src="/images/bbbold.gif" id="btnbold" title="Fett">'."\r";
            $ret .= '   <img class="button" src="/images/bbitalic.gif" id="btnitalic" title="Kursiv">'."\r";
            $ret .= '   <img class="button" src="/images/bbunderline.gif" id="btnunderline" title="Unterstrichen">'."\r";
            if(!$mob) $ret .= '<br>'."\r";
            $ret .= '   <img class="button" src="/images/bblink.gif" id="btnlink" title="Link">'."\r";
            $ret .= '   <img class="button" src="/images/bbquote.gif" id="btnquote" title="Quote">'."\r";
            if(!$mob) $ret .= '<br>'."\r";
            $ret .= '   <img class="button" src="/images/smsmile.gif" id="smsmile" title="smiling">'."\r";
            $ret .= '   <img class="button" src="/images/smlaugh.gif" id="smlaugh" title="laughing">'."\r";
            $ret .= '   <img class="button" src="/images/smsad.gif" id="smsad" title="sad">'."\r";
            if(!$mob) $ret .= '<br>'."\r";
            $ret .= '   <img class="button" src="/images/smone.gif" id="smone" title="You know...">'."\r";
            $ret .= '  </p>'."\r";
        }*/
        $ret .= '  <input type="hidden" name="date" value="'.$time.'">'."\r";
        $ret .= '  <input type="hidden" name="reply" value="'.$reply.'" class="reply">'."\r";
        $ret .= '  <span class="antSp">'."\r";
        $ret .= '   <label for="homepage">Tragen Sie auch hier bitte NICHTS ein!</label>'."\r";
        $ret .= '   <input type="text" name="homepage">'."\r";
        $ret .= '  </span>'."\r";
        $ret .= '  <input type="submit" name="formaction" value="Absenden" class="button" id="formPublicSub">'."\r";
        $ret .= '</form>'."\r";
        $ret .= '<p class="beCommentNewTime">'."\r";
        $ret .= '  Warte noch <strong id="wait">20</strong> Sekunden, bevor du posten kannst.'."\r";
        $ret .= '</p>'."\r";
        $ret .= '<p class="beCommentNewDisclaimer">'."\r";
        $ret .= '  Mit * makierte Felder sind Pflicht, deine Mailadresse wird nicht veröffentlicht. Mehr dazu im <a href="/impressum">Impressum</a>.'."\r"; 
        $ret .= '</p>'."\r";
        return $ret;
    }
 
    function genMenu($db) {
        $pars = getTopCats($db);
        foreach($pars as $par) {
            echo '<li><a href="/'.(($par!=getCatID($db, 'Blog'))?lowerCat(getCatName($db, $par)):'').'">'.getCatName($db, $par).'</a></li>'."\r";
        }
    }
 
    function genSubMenu($db) {
        if(isset($_GET['n'])) {
            $catID = getNewsCat($db, $_GET['n']);
            $cat = getCatName($db, $catID);
            $catParentID = getCatParent($db, $catID);
            $catParentName = getCatname($db, $catParentID);
            echo '<li class="between">&gt;</li><li><a href="/">Blog</a></li>'."\r";
            if(lowerCat($cat) !== 'blog') {
                echo '<li class="between">&gt;</li><li><a href="/'.lowerCat($catParentName).'">'.$catParentName.'</a></li>'."\r";
                echo '<li class="between">&gt;</li><li><a href="/'.lowerCat($cat).'">'.$cat.'</a></li>'."\r";
            }
        } else {
            if(isset($_GET['c']) && isCat($db, $_GET['c'])) {
                $catID = $_GET['c'];
            } else if(isset($_GET['p']) && isCat($db, $_GET['p'])) {
                $catID = $_GET['p'];
            } else {
                $catID = getCatID($db, 'Blog');
            }
            if(!is_numeric($catID)) {
                $catID = getCatID($db, $catID);
            }
            $catParentID = getCatParent($db, $catID);
            $children = getChildrenNames($db, $catParentID);
            $noborder = ' class="noborder"';
            if($catID == getCatID($db, 'blog')) {
                echo ' <li'.$noborder.'><a href="/blog">Blog</a></li>'."\r";
                $noborder = '';
            }
            foreach($children as $child) {
                echo ' <li'.$noborder.'><a href="/'.lowerCat($child).'">'.$child.'</a></li>'."\r";
                $noborder = '';
            }
        }
    }
 
    function genTopArticles($db, $mob, $n = 5) {
        $ret = '';
        $ret .= '<span class="entryInfo">viel gelesene Artikel</span>'."\n";
        $ret .= '<ul class="topList">'."\n";
        $res = array();
        $sql = "SELECT
                    ID,
                    Titel
                FROM            
                    news
                WHERE
                    enable = 1 AND
                    Datum < NOW()
                GROUP BY
                    ID
                ORDER BY                
                    Hits DESC,
                    Datum DESC
                LIMIT
                    0, ?";
        if(!$stmt = $db->prepare($sql)) {return $db->error;}
        $stmt->bind_param('i', $n);
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->bind_result($id, $newstitel);
        while($stmt->fetch()) {
            $res[] = array( 'Titel' => changetext($newstitel, 'titel', $mob),
                            'Link' => '',
                            'id' => $id);
        }
        $stmt->close();
        foreach($res as $k => $v) {
            $ret .= '<li><a href="'.getLink($db, getCatName($db, getNewsCat($db, $v['id'])), $v['id'], $v['Titel']).'" title="'.$v['Titel'].'">'.shortenTitle($v['Titel'], 25).'</a></li>';
        }
        $ret .= '</ul>'."\n";
        return $ret;
    }
 
    function genlastArticles($db, $mob, $n = 5) {
        $ret = '';
        $ret .= '<h2>Letzten '.$n.' Artikel</h2>'."\n";
        $ret .= '<ul>'."\n";
        $res = array();
        $sql = "SELECT
                    ID,
                    Titel
                FROM            
                    news
                WHERE
                    enable = 1 AND
                    Datum < NOW()
                GROUP BY
                    ID
                ORDER BY
                    Datum DESC
                LIMIT
                    0, ?";
        if(!$stmt = $db->prepare($sql)) {return $db->error;}
        $stmt->bind_param('i', $n);
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->bind_result($id, $newstitel);
        while($stmt->fetch()) {
            $res[] = array( 'Titel' => changetext($newstitel, 'titel', $mob),
                            'Link' => '',
                            'id' => $id);
        }
        $stmt->close();
        foreach($res as $k => $v) {
            $ret .= '<li><a href="'.getLink($db, getCatName($db, getNewsCat($db, $v['id'])), $v['id'], $v['Titel']).'" title="'.$v['Titel'].'">'.shortenTitle($v['Titel'], 25).'</a></li>';
        }
        $ret .= '</ul>'."\n";
        return $ret;
    }

    function genComment($cmt, $cmtReply, $replyId = '') {
        if($replyId == '') {
            $replyId = $cmt['id'];
        }
        $html = '';
        $html .= '<div class="beCommentEntry" id="comment'.$cmt['id'].'" data-reply="'.$replyId.'">';
        $html .= '  <span class="beCommentEntryAvatar"><img src="'.get_Gravatar($cmt['mail']).'" alt="Avatar"></span>';
        $html .= '  <span class="beCommentEntryHeader">';
        $html .= '    <time datetime="'.$cmt['datAttr'].'" class="long">'.$cmt['datum'].'</time> -';
        $html .= '    <span class="author">';
        if(isValidUserUrl($cmt['web'])) {
            $html .= '<a href="'.$cmt['web'].'">'.$cmt['autor'].'</a>';
        } else {
            $html .= $cmt['autor'];
        }
        $html .= '</span>';
        $html .= '  </span>';
        $html .= '  <div class="beCommentEntryContent">';
        $html .= $cmt['inhalt']."\n";
        $html .= '  </div>';
        $html .= '  <br class="clear">';
        $cmtReplyLink = $cmtReply.'#newComment?comment-reply='.$replyId;
        $html .= '  <a class="reply" href="'.$cmtReplyLink.'">Antworten</a>';
        if(isset($cmt['replies']) && count($cmt['replies']) > 0) {
            $html .= '  <div class="replies">';
            foreach($cmt['replies'] as $reply) {
                $html .= genComment($reply, $cmtReply, $replyId);
            }
            $html .= '  </div>';
        }
        $html .= '</div>';
        return $html;
    }
?>