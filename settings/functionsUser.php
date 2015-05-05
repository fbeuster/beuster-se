<?php

    function getUserID() {
        if (!isset($_COOKIE['UserID'], $_COOKIE['Password'])) {return false;}
        $db = Database::getDB()->getCon();
        $sql = 'SELECT
                    ID
                FROM
                    users
                WHERE
                    ID = ? AND
                    Password = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) {return $db->error;}
        $stmt->bind_param('is', $_COOKIE['UserID'], $_COOKIE['Password']);
        if (!$stmt->execute()) {
            $str = $stmt->error;
            $stmt->close();
            return $str;
        }
        $stmt->bind_result($UserID);
        if (!$stmt->fetch()) {
            $stmt->close();
            return false;
        }
        $stmt->close();
        return $UserID;
    }

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