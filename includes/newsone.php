<?php
    $db = Database::getDB();
    $dbCon = $db->getCon();

    if(!Utilities::isDevServer()) {
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

        $user = $dbCon->real_escape_string(stripslashes(trim($_POST['usr'])));
        $Inhalt = Parser::parse(trim($_POST['usrcnt']), Parser::TYPE_NEW);
        $Usermail = $dbCon->real_escape_string(stripslashes(strtolower(trim($_POST['usrml']))));
        $webpage = $dbCon->real_escape_string(stripslashes(trim($_POST['usrpg'])));
        $err = checkStandForm($user, $Inhalt, $Usermail, $webpage, trim($_POST['date']), $_POST['email'], $_POST['homepage'], 'commentForm');
        $Inhalt = remDoubles($Inhalt, array('[b]','[i]','[u]'));
        $replyTo = checkReplyId(trim($_POST['reply']));

        $cookie_user = User::newFromCookie();
        if ($cookie_user && $cookie_user->isAdmin()) {
            refreshCookies();
            $frei = 2;
            $Usermail = $dbCon->real_escape_string(stripslashes(strtolower(ADMIN_GRAV_MAIL)));
            $webpage = $dbCon->real_escape_string(stripslashes(ADMIN_WEBPAGE));
        }
        $article = new Article($id);
        $title = $article->getTitle();
        $errRet = substr(getLink(getCatName(getNewsCat($id)), $id, $title), 1);
        if($err == 0) {

            // exists user in db?
            $newUser = true;

            $fields = array('ID');
            $conds = array('LOWER(Email) = ?', 's', array($Usermail));
            $res  = $db2->select('users', $fields, $conds);

            if (count($res)) {
                $newUser = false;
            }

            // add user to db
            if($newUser) {
                $sql = 'INSERT INTO
                            users(Name, Rights, Email, regDate, Clearname, Website)
                        VALUES
                            (?, ?, ?, NOW(), ?, ?)';
                if(!$stmt = $dbCon->prepare($sql)) {
                    $return = showInfo('Fehler #NC1, bitte Admin kontaktieren.', $errRet);
                } else {
                    $rights = 'user';
                    $trimmed_name = preg_replace('/[^A-Za-z0-9-_]/', '', $user);

                    $stmt->bind_param('sssss', $trimmed_name, $rights, $Usermail, $user, $webpage);
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
            if(!$stmt = $dbCon->prepare($sql)) {
                $return = showInfo('Fehler #NC1, bitte Admin kontaktieren.', $errRet);
            } else {
                $stmt->bind_param('siiii', $Inhalt, $id, $frei, $replyTo, $uid);
                if(!$stmt->execute()) {
                    $return = showInfo('Fehler #NC2, bitte Admin kontaktieren.', $errRet);
                }
            }
            $stmt->close();

            // notify mails
            notifyAdmin($title, $Inhalt, $user);

            // return
            if(Utilities::isDevServer()) {
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

    $article = new Article($id);
    $articles[0] = $article;

    $a['data']['eType'] = 0;
    $a['data']['ec'] = '';
    $a['data']['formCnt'] = 20;

    $aside = array( 'author'        => $article->getAuthor(),
                    'date'          => date('d.m.Y H:i', $article->getDate()),
                    'datAttr'       => date('c', $article->getDate()),
                    'link'          => $article->getLink());

    $pics    = array();
    $fields  = array('ID', 'Name', 'Pfad');
    $conds   = array('NewsID = ?', 'i', array($id));
    $options = 'ORDER BY ID';
    $res     = $db->select('pics', $fields, $conds, $options);

    foreach ($res as $key => $pic) {
        $pics[] = array('id'    => $pic['ID'],
                        'name'  => $pic['Name'],
                        'pfad'  => $pic['Pfad']);
    }

    $t = 1;
    if(!empty($pics)) {
        $fields = array('Pfad');
        $conds  = array('NewsID = ? AND Thumb = ?', 'ii', array($id, $t));
        $res    = $db->select('pics', $fields, $conds);

        $a['data']['th_og'] = count($res) ? $res[0]['Pfad'] : '';
        $a['data']['th'] = str_replace('blog/id', 'blog/thid', $a['data']['th_og']);
        $a['data']['th'] = str_replace('.', '_', $a['data']['th']);
        $a['data']['th'] = 'http://'.Utilities::getSystemAddress().'/'.$a['data']['th'].'.jpg';
        $a['data']['th_og'] = 'http://'.Utilities::getSystemAddress().'/'.$a['data']['th_og'];
    }

    $a['data']['pics'] = $pics;
    $a['data']['aside'] = $aside;
    $a['data']['ret'] = $return;
?>