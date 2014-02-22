<?php

    function getUserID($db) {
        if (!is_object($db)) {return false;}
        if (!($db instanceof MySQLi)) {return false;}
        if (!isset($_COOKIE['UserID'], $_COOKIE['Password'])) {return false;}
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

    function getUserIDbyName($db, $uName) {
        $sql = 'SELECT
                    ID
                FROM
                    users
                WHERE
                    Name = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) return $db->error;
        $stmt->bind_param('s', $uName);
        if (!$stmt->execute()) return $stmt->error;
        $stmt->bind_result($uID);
        if(!$stmt->fetch()) return $stmt->error;
        $stmt->close();
        return $uID;
    }
 
    function hasUserRights($db, $rights) {
        if (!is_object($db)) {return false;}
        if (!($db instanceof MySQLi)) {return false;}
        if (!isset($_COOKIE['UserID'], $_COOKIE['Password'])) {return false;}
        $sql = 'SELECT
                    rights
                FROM
                    users
                WHERE
                    ID = ? AND
                    Password = ? AND
                    Rights = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) {return $db->error;}
        $stmt->bind_param('iss', $_COOKIE['UserID'], $_COOKIE['Password'], $rights);
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
        return true;
    }
 
    function getUserName($db, $uID) {
        $uName = 'err';
        $sql = 'SELECT
                    Name
                FROM
                    users
                WHERE
                    ID = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) return $db->error;
        $stmt->bind_param('i', $uID);
        if (!$stmt->execute()) return $stmt->error;
        $stmt->bind_result($uName);
        $stmt->fetch();
        $stmt->close();  
        return $uName;
    }
 
    function getClearName($db, $uID) {
        $uName = 'err';
        $sql = 'SELECT
                    Clearname
                FROM
                    users
                WHERE
                    ID = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) return $db->error;
        $stmt->bind_param('i', $uID);
        if (!$stmt->execute()) return $stmt->error;
        $stmt->bind_result($uName);
        $stmt->fetch();
        $stmt->close();  
        return $uName;
    }
 
    function getContactMail($db, $uID) {
        $umail = '';
        $sql = 'SELECT
                    Contactmail
                FROM
                    users
                WHERE
                    ID = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) return $db->error;
        $stmt->bind_param('i', $uID);
        if (!$stmt->execute()) return $stmt->error;
        $stmt->bind_result($uMail);
        $stmt->fetch();
        $stmt->close(); 
        return $uMail;
    }
 
    function isUsernameNotAvalible($db, $name) {
        $sql = 'SELECT
                    Name
                FROM
                    users
                WHERE
                    Name = ?';
        $stmt = $db->prepare($sql);
        $stmt->bind_param('s', $name);
        if (!$stmt->execute()) {
            $str = $stmt->error;
            $stmt->close();
            return $str;
        }
        if (!$stmt->fetch()) {
            $stmt->close();
            return false;
        }
        $stmt->close();
        return true;
    }
  
    function getUser($db, $i) {
        $sql = "SELECT
                    ID,
                    Name,
                    Email,
                    Rights
                FROM
                    users
                WHERE
                ID = ?";
        if(!$stmt = $db->prepare($sql)) {return $db->error;}
        $stmt->bind_param('i', $i);
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->bind_result($userid, $username, $usermail, $userrights);
        if(!$stmt->fetch()) {return 'Es wurde kein User mit dieser ID gefunden. <br /><a href="/adminuser" class="back">Zurück</a>';}
        $a = array(
                'userid'    => $userid,
                'username'  => $username,
                'usermail'  => $usermail,
                'userrights' =>$userrights);
        $stmt->close();
        return $a;
    }
 
    function adminMail($db) {
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