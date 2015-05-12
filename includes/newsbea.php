<?php
    $a = array();
    $user = User::newFromCookie();
    if ($user && $user->isAdmin()) {
        refreshCookies();
        $a['filename'] = 'newsbea.php';
        $a['data'] = array();
        $err = 0;
        $neu = 0;
        $neuPl = 0;
        $db = Database::getDB()->getCon();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (isset($_POST['formactionchange'])) {
                /*** hier ändern ***/
                $title     = changetext($_POST['newstitel'], 'neu');
                $inhalt    = changeText($_POST['newsinhalt'], 'neu');
                $tagStr     = trim($_POST['tags']);
                $cat       = $_POST['cat'];
                $catNeu    = trim($_POST['catneu']);
                $catPar    = $_POST['catPar'];
                $play      = $_POST['pl'];
                $playNeu   = trim($_POST['plneu']);
                $playNeuID = trim($_POST['plneuid']);
                $newsID    = $_POST['newsid2'];
                if(isset($_POST['enable']))
                    $ena = 0;
                else
                    $ena = 1;
                $oldEna = getArticleAttribute($newsID, 'enable');
                $eRet = array(  'titel'  => $title,
                                'inhalt' => $inhalt,
                                'id'     => $newsID);
                if('' == $title || '' == $inhalt || '' == $newsID) {
                    $err = 1;
                } else if($cat == 'error' && $catNeu == '' && $play == 'error' && $playNeu == '') {
                    $err = 2;
                } else if($play != 'error' && $playNeu != '') {
                    $err = 3;
                } else if($playNeuID == '' && $playNeu != '') {
                    $err = 4;
                } else if($cat != 'error' && $catNeu != '') {
                    $err = 5;
                } else if($catPar == 'error' && $catNeu != '') {
                    $err = 6;
                } else if($playNeu != '' && $catNeu != '') {
                    $err = 7;
                } else if($catNeu != '') {
                    $cat = $catNeu;
                    $neu = 1;
                } else if($playNeu != '') {
                    $cat = $playNeu;
                    $neu = 1;
                    $neuPl = 1;
                } else if($cat == 'error' && $play != 'error'){
                    $cat = $play;
                } else {
                }

                $tags = array();
                $tmp = explode(',', $tagStr);
                foreach($tmp as $tag) {
                    if(trim($tag) !== '' &&
                        !in_array(strtolower($tag), array_map('strtolower', $tags))) {
                        $tags[] = $db->real_escape_string($tag);
                    }
                }

                $e = array();
                if(isset($_FILES['files'])) {
                    $picsAnzOld = getNewsPicNumber($newsID);
                    foreach($_FILES['file']['name'] as $key => $value) {
                        if( $_FILES['file']['size'][$key] > 0 &&
                            $_FILES['file']['size'][$key] < 5242880 &&
                            isImage($_FILES['file']['type'][$key])) {
                            if($catID == getCatID('Portfolio')) {
                                $pfad = 'images/port/'.pathinfo($_FILES['file']['name'][$key], PATHINFO_BASENAME);
                            } else {
                                $pfad = 'images/blog/id'.$id.'date'.date('Ymd').'n'.($key + $picAnzOld).'.'.pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
                            }
                            if(!file_exists($pfad)) {
                                $thumb = (int)trim($_POST['thumb']);
                                if(is_int($thumb) && '' != $thumb) {
                                    if($thumb == $key + 1) {
                                        $thumb = 1;
                                    } else {
                                        $thumb = 0;
                                    }
                                } else {
                                    $thumb = 0;
                                }
                                $name = $_FILES['file']['name'][$key];
                                move_uploaded_file($_FILES['file']['tmp_name'][$key], $pfad);
                                $sql = "INSERT INTO
                                            pics(NewsID, Name, Pfad, Thumb)
                                        VALUES
                                            (?, ?, ?, ?);";
                                if(!$stmt = $db->prepare($sql)){return $db->error;}
                                $stmt->bind_param('issi', $newsID, $name, $pfad, $thumb);
                                if(!$stmt->execute()){return $stmt->error;}
                                $stmt->close();
                            }
                            // Thumbnail erstellen
                            $pic = array();
                            $pathTemp = pathinfo($pfad);
                            $pic['pathNeu'] = $pathTemp['dirname'].'/th'.$pathTemp['filename'].'_'.$pathTemp['extension'].'.jpg';
                            $pic['dim'] = getimagesize($pfad);
                            $pic['dim'] = array('w' => $pic['dim'][0],
                                                'h' => $pic['dim'][1]);
                            $pic['factor'] = max($pic['dim']['w'] / 285, $pic['dim']['h'] / 190);
                            $pic['dimNeu'] = array( 'w' => round($pic['dim']['w'] / $pic['factor']),
                                                    'h' => round($pic['dim']['h'] / $pic['factor']));
                            $pic['t'] = getimagesize($pfad);
                            $pic['t'] = $pic['t'][2];
                            switch($pic['t']) {
                                case "1":
                                    $picOld = imagecreatefromgif($pfad);
                                    break;
                                case "2":
                                    $picOld = imagecreatefromjpeg($pfad);
                                    break;
                                case "3":
                                    $picOld = imagecreatefrompng($pfad);
                                    break;
                                default:
                                    $picOld = imagecreatefromjpeg($pfad);
                            }
                            $picNeu = imagecreatetruecolor($pic['dimNeu']['w'],$pic['dimNeu']['h']);
                            imagecopyresampled( $picNeu,
                                                $picOld,
                                                0,
                                                0,
                                                0,
                                                0,
                                                $pic['dimNeu']['w'],
                                                $pic['dimNeu']['h'],
                                                $pic['dim']['w'],
                                                $pic['dim']['h']);
                            imagejpeg($picNeu, $pic['pathNeu'], 100);
                            imagedestroy($picNeu);
                            imagedestroy($picOld);
                        } else if($_FILES['file']['size'][$key] != 0){
                            $e[] = $_FILES['file']['name'][$key];
                        }
                    }
                }
                if($err == 0 && empty($e)) {
                    $catidalt = getNewsCatID($newsID);
                    $catalt = getNewsCat($newsID);
                    if ($neu){
                        /* neue Kategorie */
                        $sql = "INSERT INTO
                                    newscat(Cat)
                                VALUES
                                    (?)";
                        if(!$stmt = $db->prepare($sql)){return $db->error;}
                        $stmt-bind_param('s',$catNeu);
                        if(!$stmt->execute()){return $stmt->error;}
                        $stmt->close();

                        $sql = "SELECT
                                    MAX(ID) as idn
                                FROM
                                    newscat;";
                        if(!$stmt = $db->query($sql)){return $db->error;}
                        if($row = $stmt->fetch_assoc()){$catID = $row['idn'];}
                        $stmt->close();
                        $catID = 1;

                        if($neuPl) {
                            $sql = "INSERT INTO
                                        playlist(ytID, CatID)
                                    VALUES
                                        (?, ?)";
                            if(!$stmt = $db->prepare($sql)){return $db->error;}
                            $stmt-bind_param('si',$playNeuID, $catID);
                            if(!$stmt->execute()){return $stmt->error;}
                            $stmt->close();
                        }
                    } else {
                        $cat = getCatID($cat);
                        if($catalt != $cat) {
                            $sql = "SELECT
                                        MAX(CatID) AS neu
                                    FROM
                                        newscatcross
                                    WHERE
                                        Cat = ?";
                            if(!$stmt = $db->prepare($sql)){return $db->error;}
                            $stmt->bind_param('i', $cat);
                            if(!$stmt->execute()) {return $result->error;}
                            $stmt->bind_result($catID);
                            if(!$stmt->fetch()) {return 'Es wurde keine solche Kategorie gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
                            $stmt->close();
                            $catID = $catID + 1;
                        } else {
                            $catID = $catidalt;
                        }
                    }
                    $sql = 'UPDATE
                                news
                            SET
                                Titel = ?,
                                Inhalt = ?,
                                enable = ?
                            WHERE
                                ID = ?';
                    if(!$stmt = $db->prepare($sql)) {return $db->error;}
                    $stmt->bind_param('ssii', $title, $inhalt, $ena, $newsID);
                    if(!$stmt->execute()) {return $stmt->error;}
                    $stmt->close();

                    $sql = 'UPDATE
                                newscatcross
                            SET
                                Cat = ?,
                                CatID = ?
                            WHERE
                                NewsID = ?';
                    if(!$stmt = $db->prepare($sql)) {return $db->error;}
                    $stmt->bind_param('iii', $cat, $catID, $newsID);
                    if(!$stmt->execute()) {return $stmt->error;}
                    $stmt->close();

                    /* tags löschen und setzen */
                    $sql = "DELETE FROM
                                tags
                            WHERE
                                news_id = ?";
                    if(!$stmt = $db->prepare($sql)){return $db->error;}
                    $stmt->bind_param('i', $newsID);
                    if(!$stmt->execute()) {return $result->error;}
                    $stmt->close();

                    if(!empty($tags)) {
                        $tagSql = "(".$newsID.", '".implode("'), (".$newsID.", '", $tags)."')";
                        $sql = "INSERT INTO tags
                                    (`news_id`, `tag`)
                                VALUES ".$tagSql;
                        if(!$stmt = $db->prepare($sql)) {
                            return $db->error;
                        }
                        if(!$stmt->execute()) {
                            return $stmt->error;
                        }
                        $stmt->close();
                    }

                    /* thumbnail updaten */
                    if(isset($_POST['thumbOld'])) {
                        $pidF = trim($_POST['thumbOld']);
                        $sql = "SELECT
                                    ID
                                FROM
                                    pics
                                WHERE
                                    NewsID = ? AND
                                    Thumb = 1";
                        if(!$stmt = $db->prepare($sql)){return $db->error;}
                        $stmt->bind_param('i', $newsID);
                        if(!$stmt->execute()) {return $result->error;}
                        $stmt->bind_result($th);
                        if(!$stmt->fetch()) {}
                        $stmt->close();

                        if($th != $pidF) {
                            $sql = 'UPDATE
                                        pics
                                    SET
                                        Thumb = 1
                                    WHERE
                                        ID = ?';
                            if(!$stmt = $db->prepare($sql)) {return $db->error;}
                            $stmt->bind_param('i', $pidF);
                            if(!$stmt->execute()) {return $stmt->error;}
                            $stmt->close();
                            $sql = 'UPDATE
                                        pics
                                    SET
                                        Thumb = 0
                                    WHERE
                                        ID = ?';
                            if(!$stmt = $db->prepare($sql)) {return $db->error;}
                            $stmt->bind_param('i', $th);
                            if(!$stmt->execute()) {return $stmt->error;}
                            $stmt->close();
                        }
                    }

                    /* Bilder löschen */
                    if(!empty($_POST['del'])) {
                        $del = $_POST['del'];
                        foreach($del as $pf) {
                            $sql = 'SELECT
                                        Pfad
                                    FROM
                                        pics
                                    WHERE
                                        ID = ?';
                            if(!$stmt = $db->prepare($sql)) {return $db->error;}
                            $stmt->bind_param('i', $pf);
                            if(!$stmt->execute()) {return $stmt->error;}
                            $stmt->bind_result($path);
                            $pics = array();
                            if(!$stmt->fetch()) {return 'Es wurde kein Bild mit dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
                            $stmt->close();

                            $path2 = str_replace('.', '_', $path).'.jpg';
                            if(file_exists($path)) {
                                unlink($path);
                            }
                            if(file_exists($path2)) {
                                unlink($path2);
                            }
                            $sql = 'DELETE FROM
                                        pics
                                    WHERE
                                        ID = ?';
                            if(!$stmt = $db->prepare($sql)) {return $db->error;}
                            $stmt->bind_param('i', $pf);
                            if(!$stmt->execute()) {return $stmt->error;}
                            $stmt->close();
                        }
                    }

                    /* add to RSS */
                    if($ena && !$oldEna) {
                        if($neu) {
                            $lnk = 'http://beusterse.de'.getLink($catNeu, $newsID, $title);
                        } else {
                            $lnk = 'http://beusterse.de'.getLink(getCatName($cat), $newsID, $title);
                        }
                        $lnk = 'http://beusterse.de'.getLink($cat, $id, $title);
                        addRssItem( $rssFeedPath,
                                    $title,
                                    str_replace('###link###', $lnk, changetext($inhalt, 'vorschau')),
                                    date("D, j M Y H:i:s ", time()).'GMT',
                                    $id,
                                    $lnk);
                    }

                    return showInfo('Die News wurde geändert. <br /><a href="/newsbea" class="back">Zurück zum Bearbeiten</a>', 'newsbea');
                } else {
                    $a['data']['err'] = $eRet;
                    $a['data']['err']['type'] = analyseErrNewsBea($err);
                }
            } else if(isset($_POST['formactionchoose'])) {
                $id = trim($_POST['newsid']);
                /*** zum Bearbeiten holen ***/
                $sql = 'SELECT
                            news.Titel,
                            news.Inhalt,
                            news.enable,
                            newscat.Cat,
                            newscat.ID
                        FROM
                            news
                        LEFT JOIN
                            newscatcross ON
                            news.ID = newscatcross.NewsID
                        LEFT JOIN
                            newscat ON
                            newscat.ID = newscatcross.Cat
                        WHERE
                            news.ID = ?';
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('i', $id);
                if(!$stmt->execute()) {return $stmt->error;}
                $stmt->bind_result($newstitel, $newsinhalt, $newsena, $newscat, $newscatid);
                if(!$stmt->fetch()) {return 'Es wurde keine News mit dieser ID gefunden. <br /><a href="/newsbea" class="back">Zurück zum Bearbeiten</a>';}
                $stmt->close();
                $a['data']['newsbea'] = array(
                                            'newsidbea'     => $id,
                                            'newsinhalt'    => changetext($newsinhalt, 'bea'),
                                            'newstitel'     => changetext($newstitel, 'bea'),
                                            'newsena'       => $newsena,
                                            'newstags'      => getNewsTags($id, true),
                                            'newscat'       => $newscat,
                                            'isPlaylist'    => isCatPlaylist($newscatid));
                $sql = 'SELECT
                            Pfad,
                            Thumb,
                            ID
                        FROM
                            pics
                        WHERE
                            NewsID = ?
                        ORDER BY
                            ID';
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('i', $newsid);
                if(!$stmt->execute()) {return $stmt->error;}
                $stmt->bind_result($pfad, $thumb, $pid);
                $a['data']['pfad'] = array();
                while($stmt->fetch()) {
                    $a['data']['pfad'][] = array('pfad' => $pfad, 'thumb' => $thumb, 'id' => $pid);
                }
                $stmt->close();
            }
        }
        $sql = "SELECT
                    ID,
                    Titel,
                    DATE_FORMAT(Datum, '".DATE_STYLE."') AS Changedatum
                FROM
                    news
                ORDER BY
                    Datum DESC";
        if(!$stmt = $db->query($sql)) {return $db->error;}
        $news = array();
        while($row = $stmt->fetch_assoc()) {
            $news[$row['ID']] = array(
                                    'newsid'    =>$row['ID'],
                                    'newsdatum' =>$row['Changedatum'],
                                    'newstitel' =>$row['Titel']);
        }
        $stmt->close();
        $a['data']['news'] = $news;
        $a['data']['pars'] = getTopCats();
        $a['data']['cats'] = getSubCats();
        $a['data']['cats'][] = 'Blog';
        $a['data']['pls'] = getPlaylists();
        $a['data']['admin_news'] = true;

        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    } else if($user){
        return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');
    } else {
        return 'Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>';
    }
?>