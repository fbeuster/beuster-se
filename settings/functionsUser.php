<?php

    function adminMail() {
        $db = Database::getDB()->getCon();
        $sql = 'SELECT
                    Email
                FROM
                    users
                WHERE
                    Rights = ?';
        $stmt = $db->prepare($sql);
        if(!$stmt) {return $db->error;}
        $r = 'admin';
        $stmt->bind_param('s', $r);
        if(!$stmt->execute()){return $stmt->error;}
        $stmt->bind_result($mailTo);
        if(!$stmt->fetch()){return 'nö';}
        $stmt->close();
        return $mailTo;
    }
?>