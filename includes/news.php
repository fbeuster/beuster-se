<?php
    // Umstrukturieren:
    //     wenn kein c da ist, dann alles anzeigen
    //     wenn c == blog dann wirklich nur die EInträge der KAtegorie blog, nicht mehr
    // 0 - Top
    // 1 - Playlist
    // 2 - Sub
    // 3 - Portfolio
    $a = array();
    $ena = 1;
    $news = array();
    $archive = false;

    $a['filename'] = 'news.php';
    $a['data'] = array();
    $db = Database::getDB()->getCon();

    if(isset($_GET['page']))
        $start = (int)$_GET['page'];
    else 
        $start = 1;

    if(isset($_GET['c'])) {
        $cat = $_GET['c'];
        if(!is_numeric($cat)) {
            $cat = getCatID($cat);
        }
        if(!isCat($cat))
            $cat = -1;
    } else if(isset($_GET['p']) && isCat($_GET['p'])) {
        $cat = getCatID($_GET['p']);
    } else {
        $cat = -1;
    }
    if(isset($_GET['n'])) {
        $id = $_GET['n'];
    } else {
        $id = -1;
    }
    
    if($id !== -1) {
        // einzelne News
        include('newsone.php');
        if($a['data']['ret'] != '') return $a['data']['ret'];
    } else if($cat == -1) {
        // alle News
        
        // calc date range
        $dateSQL = "err";
        if(isset($_GET['y'])) {
            $year = (int)$_GET['y'];
            if($year > (int)date("Y")) {
                $dateSQL = "err";
            } else {
                // valid year
                if(isset($_GET['m'])) {
                    $month = (int)$_GET['m'];
                    if($month > 12 || $month < 1) {
                        $dateSQL = "err";
                    } else {
                        // valid month
                        $dateSQL = "YEAR(news.Datum) = ".$year." AND
                                    MONTH(news.Datum) = ".$month;
                        $destDate = $year.'/'.$month;
                    }
                } else {
                    $dateSQL = "YEAR(news.Datum) = ".$year;
                    $destDate = $year;
                }
            }
        }
        if($dateSQL == "err") {
            $dateSQL = "news.Datum < NOW()";
        } else {
            $archive = true;
            $dateSQL .= " AND news.Datum < NOW()";
        }
        
        // calc number of articles and pages
        if($local) {
            $anzahl = getAnzDev($dateSQL);
        } else {
            $anzahl = getAnz($dateSQL);
        }
        $pages = getPages($anzahl, 8, $start);
        if($start>$pages)$start=$pages;
        
        if($local) {
            $sql = "SELECT
                        news.ID,
                        news.Titel,
                        news.Autor,                
                        news.Inhalt,
                        UNIX_TIMESTAMP(news.Datum)
                    FROM            
                        news
                    LEFT JOIN
                        newscatcross
                        ON news.ID = newscatcross.NewsID
                    WHERE
                        ".$dateSQL." AND
                        newscatcross.Cat != 12
                    GROUP BY
                        news.ID
                    ORDER BY                
                        news.Datum DESC
                    LIMIT
                        ?, 8";
            if(!$stmt = $db->prepare($sql)) {return $db->error;}
            $stmt->bind_param('i', getOffset($anzahl, 8, $start));
        } else {
            $sql = "SELECT
                        ID,
                        Titel,
                        Autor,                
                        Inhalt,
                        UNIX_TIMESTAMP(news.Datum)
                    FROM            
                        news
                    WHERE
                        enable = ? AND
                        ".$dateSQL."
                    GROUP BY
                        ID
                    ORDER BY                
                        Datum DESC
                    LIMIT
                        ?, 8";
            if(!$stmt = $db->prepare($sql)) {return $db->error;}
            $stmt->bind_param('ii', $ena, getOffset($anzahl, 8, $start));
        }
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->bind_result($newsid, $newstitel, $newsautor, $newsinhalt, $newsdatum);
        while($stmt->fetch()) {
            $titel = changetext($newstitel, 'titel', $mob);
            $news[] = array('Titel'         => $titel,
                            'author'        => $newsautor,
                            'Datum'         => date('d.m.Y', $newsdatum),
                            'datAttr'       => date('c', $newsdatum),
                            'Inhalt'        => changetext($newsinhalt, 'vorschau', $mob),
                            'Cmt'           => 0,
                            'ID'            => $newsid,
                            'Cat'           => 0,
                            'CatID'         => 0,
                            'CatDispName'   => '',
                            'CatDescr'      => '',
                            'thumb'         => 0,
                            'seitenzahl'    => $pages,
                            'start'         => $start,
                            'dest'          => '',
                            'playlist'      => 0,
                            'archive'       => 0);
        }
        $stmt->close();
        $t = 1;
        foreach ($news as $key => $entry){
            $sql = "SELECT
                        Pfad
                    FROM
                        pics
                    WHERE
                        NewsID = ? AND
                        Thumb = ?";
            if(!$stmt = $db->prepare($sql)) {return $db->error;}
            $stmt->bind_param('ii', $entry['ID'], $t);
            if(!$stmt->execute()) {return $result->error;}
            $stmt->bind_result($path);
            while($stmt->fetch()) {
                $path = str_replace('blog/id', 'blog/thid', $path);
                $path = str_replace('.', '_', $path);
                $news[$key]['thumb'] = 'http://'.$sysAdrr.'/'.$path.'.jpg';
            }
            $stmt->close();
            $news[$key]['Cmt']   = getCmt($entry['ID']);
            $news[$key]['Cat']   = getCatName(getNewsCat($entry['ID']));
            $news[$key]['CatID'] = getNewsCatID($entry['ID']);
            if($archive) {
                $news[$key]['archive'] = 1;
                $news[$key]['dest']    = $destDate;
            } else {
                $news[$key]['dest']  = lowerCat($news[$key]['Cat']);   
            }
            $news[$key]['author'] = User::newFromId($news[$key]['author']);
            
            $playlistID = getPlaylistID(getNewsCat($entry['ID']));
            if($playlistID !== false) {
                $videoID = getYouTubeIDFromArticle($entry['ID']);
                $path = 'images/tmp/'.$playlistID.'-'.$videoID;
                $news[$key]['thumb']    = 'http://'.$sysAdrr.'/'.$path.'.jpg';
                $news[$key]['playlist'] = 1;
            }
        }
    } else {
        // Kategorienews
        if(getCatID('portfolio') == $cat) {
            include('portfolio.php');
        } else {
            if(isTopCat($cat)) {
                // Top-Cat
                $catID = getCatParent($cat);
                if($cat == getCatID('blog')) {
                    $blogID = getCatID('blog');
                    $anzahl = getAnzCat($blogID);
                } else {
                    $blogID = 0;
                    $anzahl = getAnzTopCat($catID);
                }
                $pages = getPages($anzahl, 8, $start);
                if($start>$pages) $start=$pages;
                $sql = "SELECT
                            news.ID,
                            news.Titel,
                            news.Autor,                
                            news.Inhalt,
                            UNIX_TIMESTAMP(news.Datum)
                        FROM            
                            news
                        JOIN
                            newscatcross ON news.ID = newscatcross.NewsID
                        JOIN
                            newscat ON newscat.ID = newscatcross.Cat
                        WHERE
                            news.enable = ? AND
                            news.Datum < NOW() AND
                            (newscat.ParentID = ? OR
                            newscat.ID = ?)
                        GROUP BY
                            news.ID
                        ORDER BY                
                            news.Datum DESC
                        LIMIT
                            ?, 8";
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('iiii', $ena, $catID, $blogID, getOffset($anzahl, 8, $start));
            } else {
                // Sub-Cat
                $catID = $cat;
                $anzahl = getAnzCat($catID);
                $pages = getPages($anzahl, 8, $start);
                if($start > $pages) $start = $pages;
                $sql = "SELECT
                            news.ID,
                            news.Titel,
                            news.Autor,                
                            news.Inhalt,
                            UNIX_TIMESTAMP(news.Datum)
                        FROM            
                            news
                        JOIN
                            newscatcross ON news.ID = newscatcross.NewsID
                        WHERE
                            news.enable = ? AND
                            news.Datum < NOW() AND
                            newscatcross.Cat = ?
                        GROUP BY
                            news.ID
                        ORDER BY                
                            news.Datum DESC
                        LIMIT
                            ?, 8";
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('iii', $ena, $catID, getOffset($anzahl, 8, $start));
            }
            if(!$stmt->execute()) {return $result->error;}
            $stmt->bind_result($newsid, $newstitel, $newsautor, $newsinhalt, $newsdatum);
            while($stmt->fetch()) {
                $titel = changetext($newstitel, 'titel', $mob);
                $news[] = array('Titel'         => $titel,
                                'author'        => $newsautor,
                                'Datum'         => date('d.m.Y', $newsdatum),
                                'datAttr'       => date('c', $newsdatum),
                                'Inhalt'        => changetext($newsinhalt, 'vorschau', $mob),
                                'Cmt'           => 0,
                                'ID'            => $newsid,
                                'Cat'           => '',
                                'CatID'         => 0,
                                'CatDispName'   => '',
                                'CatDescr'      => '',
                                'thumb'         => 0,
                                'seitenzahl'    => $pages,
                                'start'         => $start,
                                'dest'          => '',
                                'playlist'      => 0,
                                'archive'       => 0);
            }
            $stmt->close();
            $t = 1;
            foreach ($news as $key => $entry){
                $sql = "SELECT
                            Pfad
                        FROM
                            pics
                        WHERE
                            NewsID = ? AND
                            Thumb = ?";
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('ii', $entry['ID'], $t);
                if(!$stmt->execute()) {return $result->error;}
                $stmt->bind_result($path);
                while($stmt->fetch()) {
                    $path = str_replace('blog/id', 'blog/thid', $path);
                    $path = str_replace('.', '_', $path);
                    $news[$key]['thumb'] = 'http://'.$sysAdrr.'/'.$path.'.jpg';
                }
                $stmt->close();
                $news[$key]['Cmt']          = getCmt($entry['ID']);
                $news[$key]['Cat']          = getCatName(getNewsCat($entry['ID']));
                $news[$key]['CatID']        = getNewsCatID($entry['ID']);
                if($archive) {
                    $news[$key]['archive'] = 1;
                    $news[$key]['dest']    = $destDate;
                } else {
                    $news[$key]['dest']     = lowerCat(getCatName($catID)); 
                }
                $news[$key]['dest']         = lowerCat(getCatName($catID));
                $news[$key]['author']       = User::newFromId($news[$key]['author']);
                $news[$key]['CatDispName']  = getCatName($catID);
                $news[$key]['CatDescr']     = getCatDescr($catID);
            
                $playlistID = getPlaylistID(getNewsCat($entry['ID']));
                if($playlistID !== false) {
                    $videoID = getYouTubeIDFromArticle($entry['ID']);
                    $path = 'images/tmp/'.$playlistID.'-'.$videoID;
                    $news[$key]['thumb'] = 'http://'.$sysAdrr.'/'.$path.'.jpg';
                    $news[$key]['playlist'] = 1;
                }
            }
        }
    }
    $a['data']['news'] = $news;
    return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
?>