<?php
    $a = array();
    if (getUserID() && hasUserRights('admin')) {
        refreshCookies();
        $a['filename'] = 'newsneu.php';
        $a['data'] = array();
        $err = 0;
        $neu = 0;
        $neuPl = 0;
        $db = Database::getDB()->getCon();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $title      = changetext($_POST['newstitel'], 'neu');
            $inhalt     = changeText($_POST['newsinhalt'], 'neu');
            $release    = trim($_POST['release']);
            $tagStr     = trim($_POST['tags']);

            $cat        = $_POST['cat'];
            $catNeu     = trim($_POST['catneu']);
            $catPar     = $_POST['catPar'];

            $play       = $_POST['pl'];
            $playNeu    = trim($_POST['plneu']);
            $playNeuID  = trim($_POST['plneuid']);

            if(isset($_POST['enable']))
                $ena = 0;
            else
                $ena = 1;
            $authID = getUserID();
            if(isset($_POST['projStat']))
                $projStat = trim($_POST['projStat']);
            $eRet = array(  'titel'  => $title,
                            'rel'    => $release,
                            'inhalt' => $inhalt);
            if('' == $title || '' == $inhalt) {
                // Leerer Titel oder leerer Inhalt
                $err = 1;
            } else {
                // Datum festlegen
                if($release == '') {
                    $release = date("Y-m-d H:i:s", time());
                } else {
                    $release .= ' 13:37:42';
                }
                // Playlist oder Sub-Kat?
                if($cat == 'error' && $catNeu == '' && $play == 'error' && $playNeu == '') {
                    // weder Kategorie noch Playlist
                    $err = 2;
                } else if($play != 'error' && $playNeu != '') {
                    // alte und neue Playlist geht nicht
                    $err = 3;
                } else if($playNeuID == '' && $playNeu != '') {
                    // alte und neue Playlist geht nicht
                    $err = 4;
                } else if($cat != 'error' && $catNeu != '') {
                    // alte und neue Ketgorie geht nicht
                    $err = 5;
                } else if($catPar == 'error' && $catNeu != '') {
                    // neue Ketgorie, aber kein Parent
                    $err = 6;
                } else if($playNeu != '' && $catNeu != '') {
                    // neue Ketgorie und neue Playlist geht nicht
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
                if(!$neu){
                    $catID = getCatID($cat);
                    $catNewsID = getMaxCatID($catID) + 1;
                } else {
                    $catNewsID = 1;
                }
                if($catID != getCatID('Projekte')) {
                    $projStat = 0;
                } else {
                    if($projStat == 0) {
                        $err = 8;
                        // Projekte als Cat gewält aber keinen Status angegeben
                    }
                }
                if($catID == getCatID('Portfolio')) {
                    $ena = 0;
                }

                $tags = array();
                $tmp = explode(',', $tagStr);
                foreach($tmp as $tag) {
                    if(trim($tag) !== '' &&
                        !in_array($tag, $tags)) {
                        $tags[] = $db->real_escape_string($tag);
                    }
                }

                if($err == 0) {
                    $sql = "INSERT INTO
                                news(Autor, Titel, Inhalt, Datum, enable, Status)
                            VALUES
                                (?, ?, ?, ?, ?, ?);";
                    if(!$stmt = $db->prepare($sql)) {
                        return $db->error;
                    }
                    $stmt->bind_param('isssii', $authID, $title, $inhalt, $release, $ena, $projStat);
                    if(!$stmt->execute()) {
                        return $stmt->error;
                    }
                    $id = $stmt->insert_id;
                    $stmt->close();

                    // Bilder hochladen
                    $e = array();
                    $imgReplace = array();
                    foreach($_FILES['file']['name'] as $key => $value) {
                        if($_FILES['file']['size'][$key] > 0 && $_FILES['file']['size'][$key] < 5242880 && isImage($_FILES['file']['type'][$key])) {
                            if($catID == getCatID('Portfolio')) {
                                $pfad = 'images/port/'.pathinfo($_FILES['file']['name'][$key], PATHINFO_BASENAME);
                            } else {
                                $pfad = 'images/blog/id'.$id.'date'.date('Ymd').'n'.$key.'.'.pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
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
                                if(!$stmt = $db->prepare($sql)) {
                                    return $db->error;
                                }
                                $stmt->bind_param('issi', $id, $name, $pfad, $thumb);
                                if(!$stmt->execute()) {
                                    return $stmt->error;
                                }
                                $stmt->close();

                                // Bild-ID abfragen
                                $sql = "SELECT
                                            MAX(ID) AS maxid
                                        FROM
                                            pics";
                                $stmt = $db->prepare($sql);
                                if(!$stmt) {
                                    return $db->error;
                                }
                                if(!$stmt->execute()) {
                                    return $stmt->error;
                                }
                                $stmt->bind_result($maxid);
                                $stmt->close();
                                $imgReplace[] = array('n' => $key + 1, 'id' => $maxid);
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
                                    break;
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
                    if(empty($e)) {
                        if($neu) {
                            if($neuPl) {
                                $typ = 1;
                                $catPar = 7;
                            } else {
                                $typ = 2;
                                $catPar = getCatID($catPar);
                            }
                            if(!$neuPl) {
                                // Kategorie anlegen
                                $sql = "INSERT INTO
                                            newscat(Cat, ParentID, Typ)
                                        VALUES
                                            (?, ?, ?)";
                                if(!$stmt = $db->prepare($sql)) {
                                    return $db->error;
                                }
                                $stmt->bind_param('sii',$cat, $catPar, $typ);
                                if(!$stmt->execute()) {
                                    return $stmt->error;
                                }
                                $stmt->close();
                            } else {
                                // Playlist anlegen
                                $sql = "INSERT INTO
                                            playlist(ytID, catID)
                                        VALUES
                                            (?, ?, ?)";
                                if(!$stmt = $db->prepare($sql)) {
                                    return $db->error;
                                }
                                $stmt->bind_param('si', $playNeuID, $catID);
                                if(!$stmt->execute()) {
                                    return $stmt->error;
                                }
                                $stmt->close();
                            }
                        }
                        $catID = getCatID($cat);

                        // Bilder einfügen
                        $newContent = $inhalt;
                        foreach($imgReplace as $image) {
                            $search = '[img'.$image['n'].']';
                            $replace = '[img'.$image['id'].']';
                            $newContent = str_replace($search, $replace, $newContent);
                        }
                        $sql = "UPDATE
                                    news
                                SET
                                    Inhalt = ?
                                WHERE
                                    ID = ?";
                        if(!$stmt = $db->prepare($sql)) {
                            return $db->error;
                        }
                        $stmt->bind_param('si', $newContent, $id);
                        if(!$stmt->execute()) {#
                            return $stmt->error;
                        }
                        $stmt->close();

                        // Tags in DB einfügen
                        if(!empty($tags)) {
                            $tagSql = "(".$id.", '".implode("'), (".$id.", '", $tags)."')";
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

                        // Eintrag und Kategorie verknüpfen
                        $sql = "INSERT INTO
                                    newscatcross(NewsID, Cat, CatID)
                                VALUES
                                    (?,?,?)";
                        if(!$stmt = $db->prepare($sql)) {
                            return $db->error;
                        }
                        $stmt->bind_param('iii',$id,$catID,$catNewsID);
                        if(!$stmt->execute()) {
                            return $stmt->error;
                        }
                        $stmt->close();

                        // RSS-Eintrag
                        if($ena && $catID !== getCatID('Portfolio')) {
                            $lnk = 'http://beusterse.de'.getLink($cat, $id, $title);
                            addRssItem( $rssFeedPath,
                                        $title,
                                        str_replace('###link###', $lnk, changetext($inhalt, 'vorschau')),
                                        date("D, j M Y H:i:s ", time()).'GMT',
                                        $id,
                                        $lnk);
                        }
                    } else {
                        $sql = "DELETE FROM
                                    news
                                WHERE
                                    ID = ?;";
                        if(!$stmt = $db->prepare($sql)) {
                            return $db->error;
                        }
                        $stmt->bind_param('i', $id);
                        if(!$stmt->execute()) {
                            return $stmt->error;
                        }
                        $stmt->close();
                        $sql = "DELETE FROM
                                    pics
                                WHERE
                                    NewsID = ?;";
                        if(!$stmt = $db->prepare($sql)) {
                            return $db->error;
                        }
                        $stmt->bind_param('i', $id);
                        if(!$stmt->execute()) {
                            return $stmt->error;
                        }
                        $stmt->close();
                        $err = 1;
                    }
                }
            }
            if($err != 0) {
                $eRet['t'] = $err;
                $a['data']['fe'] = $eRet;
            } else {
                return showInfo('Die News wurde hinzugefügt. <br /><a href="/admin" class="back">Zurück zur Administration</a>', 'admin');
            }
        }

        $a['data']['pars'] = getTopCats();
        $a['data']['cats'] = getSubCats();
        $a['data']['cats'][] = 'Blog';
        $a['data']['pls'] = getPlaylists();
        return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
    } else if(getUserID()){
        return 'Sie haben hier keine Zugriffsrechte.';
    } else {
        return 'Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>';
    }
?>