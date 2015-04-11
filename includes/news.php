<?php

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
        if(is_numeric($_GET['c'])) {
            $cat = new Category($_GET['c']);
        } else {
            $cat = Category::newFromName($_GET['c']);
        }
    } else if(isset($_GET['p'])){
        $cat = Category::newFromName($_GET['p']);
    } else {
        $cat = new Category(1);
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
        $a['data']['articles'] = $articles;
    } else if(!$cat->isLoaded() || $cat->getId() == 1) {

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
            $a['data']['conf']['dest']  = $articles[0]->getCategory()->getNameUrl();
        }
        $a['data']['conf']['seitenzahl'] = $pages;
        $a['data']['conf']['start'] = $start;
        $a['data']['articles'] = $articles;

    } else {

        // Kategorienews

        if($cat->isPortfolio()) {

            $a['filename'] = 'portfolio.php';

            # request portfolio sets
            $fields = array('ID');
            $conds = array('Typ = ? AND ParentId != ?', 'ii', array(3, 1));
            $options = 'ORDER BY Cat ASC';
            $db = Database::getDB();
            $res = $db->select('newscat', $fields, $conds, $options);

            # fill portfolio sets
            $portSets = array();
            foreach ($res as $set) {
                $portSets[] = new PortfolioSet($set['ID']);
            }

            $a['data']['portSets'] = $portSets;
            $a['data']['ret'] = '';
        } else {
            // get article ids
            #echo '<pre>'; print_r($cat); echo '</pre>';
            
            $fields = array('news.ID');
            if($cat->isTopCategory()) {
                if($cat->getId() == getCatID('blog')) {
                    $blogID = getCatID('blog');
                    $anzahl = getAnzCat($blogID);
                } else {
                    $blogID = 0;
                    $anzahl = getAnzTopCat($cat->getId());
                }

                // Top-Cat
                $conds = array(
                    'news.enable = ? AND news.Datum < NOW() AND'.
                    ' (newscat.ParentID = ? OR newscat.ID = ?)', 'iii', array($ena, $cat->getId(), $blogID));
                $joins = 'JOIN newscatcross ON news.ID = newscatcross.NewsID';
                $joins .= ' JOIN newscat ON newscat.ID = newscatcross.Cat';
            } else {
                // Sub-Cat
                $anzahl = getAnzCat($cat->getId());

                $conds = array(
                    'news.enable = ? AND news.Datum < NOW() AND'.
                    ' newscatcross.Cat = ?', 'ii', array($ena, $cat->getId()));
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
                $a['data']['conf']['dest']  = $articles[0]->getCategory()->getNameUrl();
            }
            $a['data']['conf']['seitenzahl'] = $pages;
            $a['data']['conf']['start'] = $start;
            $a['data']['articles'] = $articles;
        }
    }
    return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
?>