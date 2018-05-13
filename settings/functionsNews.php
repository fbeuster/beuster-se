<?php

    function getRandomArticle() {
        $db  = Database::getDB()->getCon();
        $ena = 1;
        $sql = "SELECT  id
                FROM    articles
                WHERE created < NOW() AND public = ? AND id >= (
                    SELECT FLOOR( MAX(id) * RAND())
                    FROM articles )
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