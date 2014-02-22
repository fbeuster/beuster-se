<?php

    function getPageType($db, $data) {
        $page = getPage($db);
        if(isset($data['news']) && count($data['news']) > 0 && !isset($data['admin_news']) &&
            $page != 'single' && $page != 'page' || $page == 'portfolio') {
            return 'multipleArticles';
        } else {
            return 'singleArticle';
        }
    }
    
    function getPage($db) {
        $curPage = getCurrentPage($db);
        if(isset($_GET['p']) && strtolower($_GET['p']) == 'portfolio') {
            return 'portfolio';
        }
        switch($curPage) {
            case 'blog':
                return 'index';
            case 'entry':
                return 'single';
            case 'topCategory':
                return 'category';
            case 'category':
                return 'category';
            default:
                return 'index';
        }
    }
 
    function getCurrentPage($db) {
        if(!isset($_GET['p'])) {
            return 'blog';
        } else {
            if($_GET['p'] == 'blog' && isset($_GET['n'])) {
                return 'entry';
            } else if(getCatID($db, $_GET['p']) && isTopCat($db, $_GET['p'])) {
                return 'topCategory';
            } else if(getCatID($db, $_GET['p'])) {
                return 'category';
            } else {
                return 'page';
            }
        }
    }
 
    function getPageImage($data) {
        global $sysAdrr;
        if(isset($data['th'])) {
            return $data['th'];
        } else {
            return 'http://beusterse.de/images/logo.png';
        }
    }
 
    function getPageOGImage($data) {
        global $sysAdrr;
        if(isset($data['th_og'])) {
            return $data['th_og'];
        } else {
            return 'http://beusterse.de/images/prev.png';
        }
    }
    
    function getPageUrl($db) {
        $curPage = getCurrentPage($db);
        switch($curPage) {
            case 'blog':
                return 'http://'.$_SERVER['HTTP_HOST'];
            case 'entry':
                $id = $_GET['n'];
                $title = getNewsTitle($db, $id);
                $cat = getCatName($db, getNewsCat($db, $id));
                return 'http://'.$_SERVER['HTTP_HOST'].getLink($db, $cat, $id, $title);
            case 'topCategory':
                return 'http://'.$_SERVER['HTTP_HOST'];
            case 'category':
                return 'http://'.$_SERVER['HTTP_HOST'];
            default:
                return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.http_build_query ($_GET);
        }
    }
    
    function getPageTitle($db, $file) {
        $curPage = getCurrentPage($db);
        switch($curPage) {
            case 'blog':
                return 'Blog, Tipps und Videos - beuster{se}';
            case 'entry':
                return getNewsTitle($db).' - beuster{se}';
            case 'topCategory':
                return getCatName($db, getCatID($db, $_GET['p'])).' - beuster{se}';
            case 'category':
                return getCatName($db, getCatID($db, $_GET['p'])).' - beuster{se}';
            default:
                return $file[$_GET['p']][1].' - beuster{se}';
        }
    }
 
    function getPageDescription($db) {
        $curPage = getCurrentPage($db);
        switch($curPage) {
            case 'blog':
                $i = getCatID($db, 'Blog');
                break;
            case 'entry':
                $i = 0;
                break;
            case 'topCategory':
                $i = getCatID($db, $_GET['p']);
                break;
            case 'category':
                $i = getCatID($db, $_GET['p']);
                break;
            default:
                $i = getCatID($db, 'Blog');
                break;
        }
        if($i !== 0) {
            $sql = " SELECT
                        Beschreibung
                    FROM
                        newscat
                    WHERE
                        ID = ?";
            if(!$stmt = $db->prepare($sql)){return $db->error;}
            $stmt->bind_param('i', $i);
            if(!$stmt->execute()) {return $result->error;}
            $stmt->bind_result($catDescr);
            if(!$stmt->fetch()) {return 'Es wurde keine solche Kategorie gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
            $stmt->close();
            return $catDescr;
        } else {
            $id = $_GET['n'];
            $sql = "SELECT
                        Inhalt
                    FROM
                        news
            WHERE
                ID = ?";
            if(!$stmt = $db->prepare($sql)){return $db->error;}
            $stmt->bind_param('i', $id);
            if(!$stmt->execute()) {return $result->error;}
            $stmt->bind_result($cont);
            if(!$stmt->fetch()) {return 'Es wurde keine solche News gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
            $stmt->close();
            return changetext($cont, 'descr', true, 250);
        }
    }
?>