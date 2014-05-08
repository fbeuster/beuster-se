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

        // single article

        include('newsone.php');
        if($a['data']['ret'] != '') return $a['data']['ret'];
    } else if($cat == -1) {

        // all articles
        
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


        // get article ids

        $fields = array('news.ID');
        if($local) {
            $conds = array($dateSQL.' AND newscatcross.Cat != ?', 'i', array(12));
            $joins = 'LEFT JOIN newscatcross ON news.ID = newscatcross.NewsID';
            $opt = 'GROUP BY news.ID ORDER BY news.Datum DESC';
        } else {
            $conds = array($dateSQL.' AND enable = ?', 'i', array($ena));
            $joins = '';
            $opt = 'GROUP BY ID ORDER BY Datum DESC';
        }
        $limit = array('LIMIT ?, 8', 'i', array(getOffset($anzahl, 8, $start)));
        $dbs = Database::getDB();
        $res = $dbs->select('news', $fields, $conds, $opt, $limit, $joins);
        
        $articles = array();
        foreach ($res as $aId) {
            $articles[] = new Article($aId['ID'], $local);
        }

        if($archive) {
            $a['data']['conf']['archive'] = 1;
            $a['data']['conf']['dest']    = $destDate;
        } else {
            $a['data']['conf']['archive'] = 0;
            $a['data']['conf']['dest']  = lowerCat($articles[0]->getCategory());   
        }
        $a['data']['conf']['seitenzahl'] = $pages;
        $a['data']['conf']['start'] = $start;

    } else {

        // Kategorienews

        if(getCatID('portfolio') == $cat) {
            include('portfolio.php');
        } else {
            // get article ids
            
            $fields = array('news.ID');
            if(isTopCat($cat)) {
                $catID = getCatParent($cat);
                if($cat == getCatID('blog')) {
                    $blogID = getCatID('blog');
                    $anzahl = getAnzCat($blogID);
                } else {
                    $blogID = 0;
                    $anzahl = getAnzTopCat($catID);
                }

                // Top-Cat
                $conds = array(
                    'news.enable = ? AND news.Datum < NOW() AND'.
                    ' (newscat.ParentID = ? OR newscat.ID = ?)', 'iii', array($ena, $catID, $blogID));
                $joins = 'JOIN newscatcross ON news.ID = newscatcross.NewsID';
                $joins .= ' JOIN newscat ON newscat.ID = newscatcross.Cat';
            } else {
                // Sub-Cat
                $catID = $cat;
                $anzahl = getAnzCat($catID);

                $conds = array(
                    'news.enable = ? AND news.Datum < NOW() AND'.
                    ' newscatcross.Cat = ?', 'ii', array($ena, $catID));
                $joins = 'JOIN newscatcross ON news.ID = newscatcross.NewsID';
            }

            $pages = getPages($anzahl, 8, $start);
            if($start > $pages) $start = $pages;

            $opt = 'GROUP BY news.ID ORDER BY news.Datum DESC';
            $limit = array('LIMIT ?, 8', 'i', array(getOffset($anzahl, 8, $start)));

            $dbs = Database::getDB();
            $res = $dbs->select('news', $fields, $conds, $opt, $limit, $joins);
            
            $articles = array();
            foreach ($res as $aId) {
                $articles[] = new Article($aId['ID'], $local);
            }

            if($archive) {
                $a['data']['conf']['archive'] = 1;
                $a['data']['conf']['dest']    = $destDate;
            } else {
                $a['data']['conf']['archive'] = 0;
                $a['data']['conf']['dest']  = lowerCat($articles[0]->getCategory());   
            }
            $a['data']['conf']['seitenzahl'] = $pages;
            $a['data']['conf']['start'] = $start;
        }
    }
    $a['data']['articles'] = $articles;
    return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
?>