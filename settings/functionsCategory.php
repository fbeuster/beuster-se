<?php

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

    function isTopCat($c) {
        $db = Database::getDB()->getCon();
        $cID = $c;
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

    function getCatID($cat) {
        $db = Database::getDB()->getCon();
        $cat = LinkBuilder::replaceUmlaute(lowerCat($cat));
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
            $cats[$id] = LinkBuilder::replaceUmlaute(lowerCat($c));
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