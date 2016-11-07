<?php
    $a = array();
    $user = User::newFromCookie();
    if ($user && $user->isAdmin()) {
        refreshCookies();
        $a['filename'] = 'newsnew.php';
        $a['data'] = array();
        $err = 0;
        $neu = 0;
        $neuPl = 0;
        $db = Database::getDB()->getCon();
        $dbo = Database::getDB();

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $title      = Parser::parse($_POST['newstitel'], Parser::TYPE_NEW);
            $inhalt     = Parser::parse($_POST['newsinhalt'], Parser::TYPE_NEW);
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

                if($neu || $catID != getCatID('Projekte')) {
                    $projStat = 0;
                } else {
                    if($projStat == 0) {
                        $err = 8;
                        // Projekte als Cat gewält aber keinen Status angegeben
                    }
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
                    $fields = array('Autor', 'Titel', 'Inhalt', 'Datum', 'enable', 'Status');
                    $values = array('isssii', array($user->getId(), $title, $inhalt, $release, $ena, $projStat));
                    $id    = $dbo->insert('news', $fields, $values);

                    // Bilder hochladen
                    $e = array();
                    $imgReplace = array();
                    foreach($_FILES['file']['name'] as $key => $value) {
                        if($_FILES['file']['size'][$key] > 0 && $_FILES['file']['size'][$key] < 5242880 && isImage($_FILES['file']['type'][$key])) {

                            $pre_path   = 'images/blog/';
                            $file_name  = 'id'.$id.'date'.date('Ymd').'n'.$key;
                            $file_ext   = pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
                            $pfad       = $pre_path.$file_name.'.'.$file_ext;

                            if (!is_writable($pre_path)) {
                                $e[] = $_FILES['file']['name'][$key];

                            } else if(!file_exists($pfad)) {
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

                                $fields = array('NewsID', 'Name', 'Pfad', 'Thumb');
                                $values = array('issi', array($id, $name, $pfad, $thumb));
                                $maxid  = $dbo->insert('pics', $fields, $values);

                                $imgReplace[] = array('n' => $key + 1, 'id' => $maxid);

                                # create thumbnail
                                Image::createThumbnail($pfad, 295, 190);
                            }

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

                            // Kategorie anlegen
                            $fields = array('Cat', 'ParentID', 'Typ');
                            $values = array('sii', array($cat, $catPar, $typ));
                            $catID  = $dbo->insert('newscat', $fields, $values);

                            if ($neuPl) {
                                // Playlist anlegen
                                $fields = array('ytID', 'catID');
                                $values = array('si', array($playNeuID, $catID));
                                $res    = $dbo->insert('playlist', $fields, $values);
                            }
                        }
                        $catID = getCatID($cat);

                        // grab video thumbnail
                        if (isCatPlaylist($catID)) {
                            $video_id       = getYouTubeIDFromArticle($id);
                            $playlist_id    = getPlaylistID($catID);
                            $thumbnail      = 'https://img.youtube.com/vi/'.$video_id.'/maxresdefault.jpg';
                            $store_path     = 'images/tmp/'.$playlist_id.'-'.$video_id.'.jpg';

                            if(!file_exists($store_path)) {
                                $source_image   = imagecreatefromjpeg($thumbnail);
                                $thumb_width     = imagesx($source_image);
                                $thumb_height    = imagesy($source_image);

                                $scaled_image   = imagecreatetruecolor($thumb_width, $thumb_height);

                                imagecopy($scaled_image, $source_image, 0, 0, 0, 0, $thumb_width, $thumb_height);
                                imagedestroy($source_image);
                                $scaled_image = imagescale($scaled_image, 480, 270);
                                imagejpeg($scaled_image, $store_path);
                                imagedestroy($scaled_image);
                            }
                        }

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
                        $fields = array('NewsID', 'Cat', 'CatID');
                        $values = array('iii', array($id, $catID, $catNewsID));
                        $res    = $dbo->insert('newscatcross', $fields, $values);

                        // RSS-Eintrag
                        if($ena && isset($rssFeedPath)) {
                            $lnk = 'http://'.Utilities::getSystemAddress().getLink($cat, $id, $title);
                            addRssItem( $rssFeedPath,
                                        $title,
                                        str_replace('###link###', $lnk, Parser::parse($inhalt, Parser::TYPE_PREVIEW)),
                                        date("D, j M Y H:i:s ", time()).'GMT',
                                        $id,
                                        $lnk);
                        }
                    } else {
                        $cond   = array('ID = ?', 'i', array($id));
                        $res    = $dbo->delete('news', $cond);

                        $cond   = array('NewsID = ?', 'i', array($id));
                        $res    = $dbo->delete('pics', $cond);

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
    } else if($user){
        return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');
    } else {
        return showInfo('Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>', 'login');
    }
?>