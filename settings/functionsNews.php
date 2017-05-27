<?php

    function getNewsCat($id) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    Cat
                FROM
                    newscatcross
                WHERE
                    NewsID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($a);
        if(!$result->fetch()) {return 'Es wurde keine News mit dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $result->close();
        return $a;
    }

    function getNewsCatID($id) {
        $db = Database::getDB()->getCon();
        $sql = "SELECT
                    CatID
                FROM
                    newscatcross
                WHERE
                    NewsID = ?";
        if(!$result = $db->prepare($sql)) {return $db->error;}
        $result->bind_param('i', $id);
        if(!$result->execute()) {return $result->error;}
        $result->bind_result($a);
        if(!$result->fetch()) {return 'Es wurde keine News zu dieser ID gefunden. <br /><a href="/blog">Zurück zum Blog</a>';}
        $result->close();
        return $a;
    }

    function getRandomArticle() {
        $db  = Database::getDB()->getCon();
        $ena = 1;
        $sql = "SELECT  ID
                FROM    news
                WHERE Datum < NOW() AND enable = ? AND ID >= (
                    SELECT FLOOR( MAX(ID) * RAND())
                    FROM news )
                ORDER BY ID
                LIMIT 1";

        if(!$stmt = $db->prepare($sql)) return null;

        $stmt->bind_param('i', $ena);

        if(!$stmt->execute()) return null;

        $stmt->bind_result($id);

        if(!$stmt->fetch()) return null;

        $stmt->close();

        return new Article($id);
    }

?>