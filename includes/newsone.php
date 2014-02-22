<?php
    if(!$local) {
        increaseHitNumber($db, $id);
    }
    
    $playlistID = getPlaylistID($db, getNewsCat($db, $id));
    $return = '';
 
    if($playlistID == false) {
        $a['filename'] = 'newsone.tpl';
    } else {
        $a['filename'] = 'playlist.tpl';
        include('playlist.php');
    }
 
    if('POST' == $_SERVER['REQUEST_METHOD']) {
        $frei = 0;
        
        $user = $db->real_escape_string(stripslashes(trim($_POST['usr'])));
        $Inhalt = changetext(trim($_POST['usrcnt']), 'neu', $mob);
        $Usermail = $db->real_escape_string(stripslashes(trim($_POST['usrml'])));
        $webpage = $db->real_escape_string(stripslashes(trim($_POST['usrpg'])));
        $err = checkStandForm($user, $Inhalt, $Usermail, $webpage, trim($_POST['date']), $_POST['email'], $_POST['homepage'], 'commentForm');
        $Inhalt = remDoubles($Inhalt, array('[b]','[i]','[u]'));
        $replyTo = checkReplyId($db, trim($_POST['reply']));
        
        if (getUserID($db) && hasUserRights($db, 'admin')) {
            refreshCookies();
            $frei = 2;
            $Usermail = $db->real_escape_string(stripslashes(ADMIN_GRAV_MAIL));
            $webpage = $db->real_escape_string(stripslashes(ADMIN_WEBPAGE));
        }
        $titel = getNewsTitel($db, $id);
        $errRet = substr(getLink($db, getCatName($db, getNewsCat($db, $id)), $id, $titel), 1);
        if($err == 0) {
            // no errors, insert comment
            $sql = 'INSERT INTO
                        kommentare(Mail, Name, Inhalt, Datum, NewsID, website, Frei, ParentID)
                    VALUES
                        (?, ?, ?, NOW(), ?, ?, ?, ?)';
            if(!$stmt = $db->prepare($sql)) {
                $return = showInfo('Fehler #NC1, bitte Admin kontaktieren.', $errRet);
            }
            $stmt->bind_param('sssisii', $Usermail, $user, $Inhalt, $id, $webpage, $frei, $replyTo);
            if(!$stmt->execute()) {
                $return = showInfo('Fehler #NC2, bitte Admin kontaktieren.', $errRet);
            }
            $stmt->close();
            
            // notify admin
            $mailTopic = 'Neuer Kommentar zu "'.$titel.'"';
            $mailContent = '<html>';
            $mailContent .= '<head><title>Neuer Kommentar</title>';
            $mailContent .= '</head>';
            $mailContent .= '<body>';
            $mailRealContent = '<h1>'.$titel.'</h1>';
            $mailRealContent .= '<p>'.$Inhalt.'</p>';
            $mailRealContent .= '<p>von: '.$user.'</p>';
            $mailContent .= $mailRealContent;
            $mailContent .= '</body></html>';
            $mailHeader = 'MIME-Version: 1.0'."\n";
            $mailHeader .= 'Content-Type: text/html; charset=utf-8'."\n";
            $mailHeader .= 'From: beuster{se} Kommentare <info@beusterse.de>'."\n";
            $mailHeader .= 'Reply-To: beuster{se} Kommentare <info@beusterse.de>'."\n";
            $mailHeader .= 'X-Mailer: PHP/'.phpversion().'\r\n';

            // return
            if($local) {
                $return = showInfo('Kommentar wurde hinzugefügt.<br>'.$mailRealContent, $errRet);
            } else {
                $mailSent = mail(adminMail($db), $mailTopic, $mailContent, $mailHeader);
                if($mailsent){}
                $return = showInfo('Kommentar wurde hinzugefügt.', $errRet);
            }
        }
        $a['data']['eType'] = $err; 
        $a['data']['ec'] = array('user' => $user, 'cnt' => $Inhalt, 'mail' => $Usermail, 'page' => $webpage);
    }

    // reply link to a comment
    $a['data']['comment_reply'] = 'null';
    if(isset($_GET['comment-reply'])) {
        $a['data']['comment_reply'] = $_GET['comment-reply'];
    }

    if($local) {
        $sql = "SELECT
                    Titel,
                    Autor,                
                    Inhalt,
                    UNIX_TIMESTAMP(Datum),
                    Status
                FROM            
                    news
                WHERE
                    ID = ?";
        if(!$result = $db->prepare($sql)) {
            $return = $db->error;
        } 
        $result->bind_param('i', $id);
    } else {
        $sql = "SELECT
                    Titel,
                    Autor,                
                    Inhalt,
                    UNIX_TIMESTAMP(Datum),
                    Status
                FROM            
                    news
                WHERE
                    enable = ? AND
                    ID = ?";
        if(!$result = $db->prepare($sql)) {
            $return = $db->error;
        } 
        $result->bind_param('ii', $ena, $id);
    }
    if(!$result->execute()) {
        $return = $result->error;
    }
    $result->bind_result($newstitel, $newsautor, $newsinhalt, $newsdatum, $projState);
    if(!$result->fetch()) {
        $return = 'Es wurde keine News mit dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';
    }
    $result->close();
    $anzCmt = getCmt($db, $id);
    if('[yt]' == substr($newsinhalt,0,4)) {
        $preApp = '<p style="text-indent:0;">';
    } else {
        $preApp = '<p>';
    }
    $backApp = '</p>';
    $news[0] = array(   'ID'            => $id,
                        'Titel'         => changetext($newstitel, 'titel', $mob),
                        'Autor'         => getClearName($db, $newsautor),
                        'Datum'         => date('d.m.Y H:i', $newsdatum),
                        'datAttr'       => date('c', $newsdatum),
                        'Inhalt'        => $preApp.grabImages($db, changetext($newsinhalt, 'inhalt', $mob)).$backApp,
                        'Cmt'           => $anzCmt,
                        'Cat'           => getCatName($db, getNewsCat($db, $id)),
                        'seitenzahl'    => 1,
                        'start'         => 1,
                        'seitenzahlC'   => getPages($anzCmt, 10, $start),
                        'startC'        => $start,
                        'projState'     => getProjState($projState),
                        'tags'          => getNewsTags($db, $id, true));
    $a['data']['eType'] = 0;
    $a['data']['ec'] = '';
    $a['data']['formCnt'] = 20;
 
    $aside = array( 'author'        => getClearName($db, $newsautor),
                    'authorNick'    => getuserName($db, $newsautor),
                    'date'          => $news[0]['Datum'],
                    'datAttr'       => $news[0]['datAttr'],
                    'link'          => getLink($db, replaceUml($news[0]['Cat']), $news[0]['ID'], $news[0]['Titel']));
 
    $sql = "SELECT
                ID,
                Name,
                Inhalt,
                UNIX_TIMESTAMP(Datum),
                Mail,
                Website,
                Frei,
                ParentID
            FROM
                kommentare
            WHERE
                NewsID = ?
            ORDER BY
                Datum DESC
            LIMIT
                ?, 10";
    if(!$result = $db->prepare($sql)) {
        $return = $db->error;
    }
    $result->bind_param('ii', $id, getOffset($anzCmt, 10, $start));
    if(!$result->execute()) {
        $return = $result->error;
    }
    $comments = array();
    $replies = array();
    $result->bind_result($cmtID, $cmtAutor, $cmtInhalt, $cmtDatum, $cmtMail, $cmtWeb, $cmtFrei, $cmtReply);
    while($result->fetch()) {
        if($cmtFrei == 3) $cmtInhalt = '[cmtSpam]'.$cmtInhalt;

        // collecting comments and replies
        if($cmtReply != -1) {
            if(!isset($replies[$cmtReply]) || !is_array($replies[$cmtReply])) {
                $replies[$cmtReply] = array();
            }
            $replies[$cmtReply][] = array(  'id'      => $cmtID,
                                            'autor'   => $cmtAutor,
                                            'web'     => rewriteUrl($cmtWeb),
                                            'inhalt'  => changetext($cmtInhalt, 'cmtInhalt', $mob),
                                            'datum'   => date('d.m.Y H:i', $cmtDatum),
                                            'datAttr' => date('c', $cmtDatum),
                                            'mail'    => $cmtMail,
                                            'frei'    => $cmtFrei,
                                            'replyTo' => $cmtReply);
        } else {
            $comments[] = array('id'      => $cmtID,
                                'autor'   => $cmtAutor,
                                'web'     => rewriteUrl($cmtWeb),
                                'inhalt'  => changetext($cmtInhalt, 'cmtInhalt', $mob),
                                'datum'   => date('d.m.Y H:i', $cmtDatum),
                                'datAttr' => date('c', $cmtDatum),
                                'mail'    => $cmtMail,
                                'frei'    => $cmtFrei,
                                'replyTo' => $cmtReply,
                                'replies' => array());
        }
    }
    $result->close();

    // sorting replies to comments
    foreach($replies as $cid => $rep) {
        foreach($comments as $k => $c) {
            if($c['id'] == $cid) {
                // array reverse so that older replies are closer to orig. comment
                $comments[$k]['replies'] = array_reverse($rep);
            }
        }
    }
    $a['data']['comments'] = $comments;
 
    $pics = array();
    $sql = "SELECT
                ID,
                Name,
                Pfad
            FROM
                pics
            WHERE
                NewsID = ?
            ORDER BY
                ID";
    if(!$result = $db->prepare($sql)) {
        $return = $db->error;
    }
    $result->bind_param('i', $id);
    if(!$result->execute()) {
        $return = $result->error;
    }
    $result->bind_result($picId, $picName, $picPfad);
    while($result->fetch()) {
        $pics[] = array('id' =>$picId,
                        'name' =>$picName,
                        'pfad' =>$picPfad);
    }
    $result->close();
    $t = 1;
    if(!empty($pics)) {
        $sql = 'SELECT
                    Pfad
                FROM
                    pics
                WHERE
                    NewsID = ? AND
                    Thumb = ?';
        if(!$stmt = $db->prepare($sql)) {
            $return = $db->error;
        }
        $stmt->bind_param('ii', $id, $t);
        if(!$stmt->execute()) {
            $return = $stmt->error;
        }
        $stmt->bind_result($a['data']['th_og']);
        if(!$stmt->fetch()) {}
        $stmt->close();
        $a['data']['th'] = str_replace('blog/id', 'blog/thid', $a['data']['th_og']);
        $a['data']['th'] = str_replace('.', '_', $a['data']['th']);
        $a['data']['th'] = 'http://'.$sysAdrr.'/'.$a['data']['th'].'.jpg';
        $a['data']['th_og'] = 'http://'.$sysAdrr.'/'.$a['data']['th_og'];
    }
    $a['data']['pics'] = $pics;
    $a['data']['aside'] = $aside;
    $a['data']['ret'] = $return;
?>