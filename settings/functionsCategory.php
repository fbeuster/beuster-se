<?php

    function isCatPlaylist($catId) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Typ
                FROM
                    newscat
                WHERE
                    ID = ?";
        $stmt = $db->prepare($sql);
        if($stmt === false) {
            return false;
        }
        $stmt->bind_param('i', $catId);
        if(!$stmt->execute()) {
            return false;
        }
        $tags = array();
        $stmt->bind_result($type);
        if(!$stmt->fetch()) {
            return false;
        }
        $stmt->close();
        if($type == 1) {
            return true;
        }
        return false;
    }
    
    function createCat($cat, $parID, $typ) {
        $db = Database::getDB()->getCon();
        $sql = "INSERT INTO
                    newscat(Cat, ParentID, Typ)
                VALUES
                    (?,?,?)";
        if(!$stmt = $db->prepare($sql)){return $db->error;}
        $stmt->bind_param('sii', $cat, $parID, $typ);
        if(!$stmt->execute()){return $stmt->error;}
        $stmt->close();
        return true;
    }
 
    function transformCat($table, $col, $tar, $old, $col2 = '') {
        $db = Database::getDB()->getCon();
        if($col2 == '') {
            $sql = "UPDATE
                    ".$table."
                    SET
                    ".$col." = ?
                    WHERE
                    ".$col." = ?";
        } else {
            $sql = "UPDATE
                    ".$table."
                    SET
                    ".$col." = ?
                    WHERE
                    ".$col2." = ?";
        }
        if(!$stmt = $db->prepare($sql)) {return $db->error;}
        $stmt->bind_param('ii', $tar, $old);
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->close();
        return true;
    }
 
    function removeCat($cat) {
        $db = Database::getDB()->getCon();
        $sql = "DELETE FROM
                    newscat
                WHERE
                    ID = ?";
        if(!$stmt = $db->prepare($sql)) {return $db->error;}
        $stmt->bind_param('i', $cat);
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->close();
        return true;
    }
 
    function transformNews($cat, $new, $max) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    NewsID
                FROM
                    newscatcross
                WHERE
                    Cat = ?";
        if(!$stmt = $db->prepare($sql)) {return $db->error;}
        $stmt->bind_param('i', $cat);
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->bind_result($c);
        $r = array();
        while($stmt->fetch()) {
            $r[] = $c;
        }
        $stmt->close();
        foreach($r as $news) {
            $max += 1;
            $sql = "UPDATE
                        newscatcross
                    SET
                        Cat = ?
                        CatID = ?
                    WHERE
                        NewsID = ?";
            if(!$stmt = $db->prepare($sql)) {return $db->error;}
            $stmt->bind_param('iii', $new, $max, $news);
            if(!$stmt->execute()) {return $stmt->error;}
            $stmt->close();
        }
        return true;
    }
 
    function getMaxCatID($catID) {
        $db = Database::getDB()->getCon();
        $sql = " SELECT
                    MAX(CatID) AS n
                FROM
                    newscatcross
                WHERE
                    Cat = ?";
        if(!$stmt = $db->prepare($sql)){return $db->error;}
        $stmt->bind_param('i', $catID);
        if(!$stmt->execute()) {return $result->error;}
        $stmt->bind_result($catNewsID);
        if(!$stmt->fetch()) {return 'Es wurde keine solche Kategorie gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $stmt->close();
        return $catNewsID;
    }
 
    function getPlaylists() {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Cat
                FROM
                    newscat
                WHERE
                    Typ = 1
                ORDER BY
                    Cat ASC";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($c);
        $r = array();
        while($result->fetch()) {
            $r[] = $c;
        }
        $result->close();
        return $r;
    }
 
    function isCat($c) {
        if(!is_numeric($c)) {
            $c = getCatID($c);
            if($c == 0) {
                return false;
            }
        }
        $cs = getCats();
        $i = count($cs) - 1;
        for($i = count($cs) - 1; $i >= 0; $i--) {
            if(getCatID($cs[$i]) == $c) return true;
        }
        return false;
    }
 
    function isTopCat($c) {
        $db = Database::getDB()->getCon();
        $cID = $c;
        //$cID = getCatID(replaceUml(lowerCat($c)));
        $sql = "SELECT
                    Typ
                FROM
                    newscat
                WHERE
                    ID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $cID);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($t);
        if(!$result->fetch()) {return false;}
        $result->close();
        if($t == 0)
            return true;
        else
            return false;
    }
 
    function getPlaylistID($c) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    ytID
                FROM
                    playlist
                WHERE
                    catID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $c);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($id);
        if(!$result->fetch()) {return false;}
        $result->close();
        return $id;
    }
 
    function getChildrenNames($c) {
        $db = Database::getDB()->getCon();
        $ret = array();
        $sql = "SELECT
                    Cat
                FROM
                    newscat
                WHERE
                    ParentID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $c);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($c);
        $r = array();
        while($result->fetch()) {
            $ret[] = $c;
        }
        $result->close();
            return $ret;
    }
 
    function lowerCat($a) {
        $a = str_replace(' ', '-', $a);
        $a = str_replace('+', '-', $a);
        $a = str_replace('--', '-', $a);
        $a = mb_strtolower($a, 'UTF-8');
        return $a;
    }
 
    function getCats() {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Cat
                FROM
                    newscat";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($c);
        $r = array();
        while($result->fetch()) {
            $r[] = $c;
        }
        $result->close();
        return $r;
    }
 
    function getTopCatIDsNames() {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    ID,
                    Cat
                FROM
                    newscat
                WHERE
                    ParentID = 0";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($id, $c);
        $r = array();
        while($result->fetch()) {
            $r[] = array($id, $c);
        }
        $result->close();
        return $r;
    }
 
    function getTopCats() {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    ID
                FROM
                    newscat
                WHERE
                    ParentID = 0";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($c);
        $r = array();
        while($result->fetch()) {
            $r[] = $c;
        }
        $result->close();
        return $r;
    }
 
    function getSubCats() {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Cat
                FROM
                    newscat
                WHERE
                    Typ = 2 OR
                    Typ = 3
                ORDER BY
                    ParentID ASC,
                    Cat ASC";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($c);
        $r = array();
        while($result->fetch()) {
            $r[] = $c;
        }
        $result->close();
        return $r;
    }
 
    function getSubCatIDsNamesParents() {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    ID,
                    Cat,
                    ParentID
                FROM
                    newscat
                WHERE
                    Typ = 2 OR
                    Typ = 3
                ORDER BY
                    ParentID ASC,
                    Cat ASC";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($id, $c, $p);
        $r = array();
        while($result->fetch()) {
            $r[] = array($id, $c, $p);
        }
        $result->close();
        return $r;
    }
 
    function getAnzCat($type) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    COUNT(news.ID) as Anzahl
                FROM
                    news
                JOIN
                    newscatcross ON news.ID = newscatcross.NewsID
                WHERE
                    newscatcross.Cat = ? AND
                    news.enable = 1 AND
                    news.Datum < NOW()";
        if(!$anz = $db->prepare($sql)) {return $db->error;}
        $anz->bind_param('i', $type);
        if(!$anz->execute()) {return $result->error;}
        $anz->bind_result($a);
        if(!$anz->fetch()) {return 'Es wurden keine News gefunden. <br /><a href="/blog">Zurück zum Block</a>';}
        $anz->close();
        return $a;
    }
 
    function getAnzTopCat($type) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    COUNT(news.ID) as Anzahl
                FROM
                    news
                JOIN
                    newscatcross ON news.ID = newscatcross.NewsID
                JOIN
                    newscat ON newscatcross.Cat = newscat.ID
                WHERE
                    newscat.ParentID = ? AND
                    news.enable = 1 AND
                    news.Datum < NOW()";
        if(!$anz = $db->prepare($sql)) {return $db->error;}
        $anz->bind_param('i', $type);
        if(!$anz->execute()) {return $result->error;}
        $anz->bind_result($a);
        if(!$anz->fetch()) {return 'Es wurden keine News gefunden. <br /><a href="/blog">Zurück zum Block</a>';}
        $anz->close();
        return $a;
    }
 
    function getCatID($cat) {
        $db = Database::getDB()->getCon();
        $cat = replaceUml(lowerCat($cat));
        $cats = array();
        $sql = "SELECT
                    ID,
                    Cat
                FROM
                    newscat";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($id, $c);
        while($result->fetch()) {
            $cats[$id] = replaceUml(lowerCat($c));
        }
        $result->close();
        $ret = array_search($cat, $cats);
        if($ret == false) {
            return 0;
        } else {
            return $ret;
        }
    }
 
    function getCatName($id) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Cat
                FROM
                    newscat
                WHERE
                    ID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i',$id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($a);
        if(!$result->fetch()){return $result->error;}
        $result->close();
        return $a;
    }
 
    function getCatDescr($id) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Beschreibung
                FROM
                    newscat
                WHERE
                    ID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i',$id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($a);
        if(!$result->fetch()){return $result->error;}
        $result->close();
        return $a;
    }
 
    function getCatParent($catID) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    ParentID
                FROM
                    newscat
                WHERE
                    ID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $catID);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($a);
        if(!$result->fetch()) {return 'Es wurde keine Kategorie mit dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $result->close();
        if($a == 0) return $catID; else return $a;
    }
?>