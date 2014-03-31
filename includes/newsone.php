<?php
    $db = Database::getDB()->getCon();
    if(!$local) {
        increaseHitNumber($id);
    }
    
    $playlistID = getPlaylistID(getNewsCat($id));
    $return = '';
 
    if($playlistID == false) {
        $a['filename'] = 'newsone.php';
    } else {
        $a['filename'] = 'playlist.php';
        include('playlist.php');
    }
 
    if('POST' == $_SERVER['REQUEST_METHOD']) {
        $frei = 0;
        
        $user = $db->real_escape_string(stripslashes(trim($_POST['usr'])));
        $Inhalt = changetext(trim($_POST['usrcnt']), 'neu', $mob);
        $Usermail = $db->real_escape_string(stripslashes(strtolower(trim($_POST['usrml']))));
        $webpage = $db->real_escape_string(stripslashes(trim($_POST['usrpg'])));
        $err = checkStandForm($user, $Inhalt, $Usermail, $webpage, trim($_POST['date']), $_POST['email'], $_POST['homepage'], 'commentForm');
        $Inhalt = remDoubles($Inhalt, array('[b]','[i]','[u]'));
        $replyTo = checkReplyId(trim($_POST['reply']));
        
        if (getUserID() && hasUserRights('admin')) {
            refreshCookies();
            $frei = 2;
            $Usermail = $db->real_escape_string(stripslashes(strtolower(ADMIN_GRAV_MAIL)));
            $webpage = $db->real_escape_string(stripslashes(ADMIN_WEBPAGE));
        }
        $titel = getNewsTitel($id);
        $errRet = substr(getLink(getCatName(getNewsCat($id)), $id, $titel), 1);
        if($err == 0) {

            // exists user in db?
            $newUser = true;
            $sql = 'SELECT ID FROM users WHERE LOWER(Email) = ?';
            $stmt = $db->prepare($sql);
            if($stmt === false) {
                $return = showInfo('Fehler #NC3, bitte Admin kontaktieren.', $errRet);
            } else {
                $stmt->bind_param('s', $Usermail);
                if(!$stmt->execute()) {
                    $return = showInfo('Fehler #NC4, bitte Admin kontaktieren.', $errRet);
                } else {
                    $stmt->bind_result($uid);
                    if($stmt->fetch()) {
                        $newUser = false;
                    }
                }
            }
            $stmt->close();
            
            // add user to db
            if($newUser) {
                $sql = 'INSERT INTO
                            users(Name, Rights, Email, regDate, Clearname, Website)
                        VALUES
                            (?, ?, ?, NOW(), ?, ?)';
                if(!$stmt = $db->prepare($sql)) {
                    $return = showInfo('Fehler #NC1, bitte Admin kontaktieren.', $errRet);
                } else {
                    $rights = 'user';
                    $stmt->bind_param('sssss', preg_replace('/[^A-Za-z0-9-_]/', '', $user), $rights, $Usermail, $user, $webpage);
                    if(!$stmt->execute()) {
                        $return = showInfo('Fehler #NC2, bitte Admin kontaktieren.', $errRet);
                    } else {
                        $uid = $stmt->insert_id;
                    }
                }
                $stmt->close();
            }
            

            // insert comment
            $sql = 'INSERT INTO
                        kommentare(Inhalt, Datum, NewsID, Frei, ParentID, UID)
                    VALUES
                        (?, NOW(), ?, ?, ?, ?)';
            if(!$stmt = $db->prepare($sql)) {
                $return = showInfo('Fehler #NC1, bitte Admin kontaktieren.', $errRet);
            } else {
                $stmt->bind_param('siiii', $Inhalt, $id, $frei, $replyTo, $uid);
                if(!$stmt->execute()) {
                    $return = showInfo('Fehler #NC2, bitte Admin kontaktieren.', $errRet);
                }
            }
            $stmt->close();
            
            // notify mails
            notifyAdmin($titel, $Inhalt, $user);

            // return
            if($local) {
                $return = showInfo('Kommentar wurde hinzugefügt.<br>'.$Inhalt, $errRet);
            } else {
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
    $anzCmt = getCmt($id);
    if('[yt]' == substr($newsinhalt,0,4)) {
        $preApp = '<p style="text-indent:0;">';
    } else {
        $preApp = '<p>';
    }
    $backApp = '</p>';
    $news[0] = array(   'ID'            => $id,
                        'Titel'         => changetext($newstitel, 'titel', $mob),
                        'Datum'         => date('d.m.Y H:i', $newsdatum),
                        'datAttr'       => date('c', $newsdatum),
                        'Inhalt'        => $preApp.grabImages(changetext($newsinhalt, 'inhalt', $mob)).$backApp,
                        'Cmt'           => $anzCmt,
                        'Cat'           => getCatName(getNewsCat($id)),
                        'seitenzahl'    => 1,
                        'start'         => 1,
                        'seitenzahlC'   => getPages($anzCmt, 10, $start),
                        'startC'        => $start,
                        'projState'     => getProjState($projState),
                        'tags'          => getNewsTags($id, true));
    $a['data']['eType'] = 0;
    $a['data']['ec'] = '';
    $a['data']['formCnt'] = 20;
 
    $aside = array( 'author'        => User::newFromId($newsautor),
                    'date'          => $news[0]['Datum'],
                    'datAttr'       => $news[0]['datAttr'],
                    'link'          => getLink(replaceUml($news[0]['Cat']), $news[0]['ID'], $news[0]['Titel']));

    // get comments
    $comments = Database::getDB()->select(
        'kommentare',
        array('ID'),
        array('NewsID = ? AND ParentID = -1', 'i', array($id)),
        'ORDER BY Datum DESC',
        array('LIMIT ?, 10', 'i', array(getOffset($anzCmt, 10, $start))));
    foreach ($comments as $k => $comment) {
        $comment = new Comment($comment['ID']);
        $comment->loadReplies();
        $comments[$k] = $comment;
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