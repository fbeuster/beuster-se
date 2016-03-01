<?php
    $user = User::newFromCookie();
    if ($user) {
        return showInfo('Sie sind bereits eingeloggt.','admin');
    }
    $ret = array();
    $ret['filename'] = 'login.php';
    $ret['data'] = array();
    $db = Database::getDB()->getCon();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
        if (!isset($_POST['Username'], $_POST['Password'], $_POST['formaction'])) {
            return  showInfo(INVALID_FORM, 'login');
        }
        if (('' == $Username = trim($_POST['Username'])) OR
            ('' == $Password = trim($_POST['Password']))) {
            return  showInfo(EMPTY_FORM, 'login');
        }

        $sql = 'SELECT
                    ID
                FROM
                    users
                WHERE
                    Name = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            return $db->error;
        }
        $stmt->bind_param('s', $Username);
        if (!$stmt->execute()) {
            return $stmt->error;
        }
        $stmt->bind_result($UserID);
        if (!$stmt->fetch()) {
            return  showInfo('Es wurde kein Benutzer mit den angegebenen Namen gefunden.<br /><a href="/login" class="back">Erneut versuchen</a>', 'login');
        }
        $stmt->close();

        $sql = 'SELECT
                    Password
                FROM
                    users
                WHERE
                    ID = ? AND
                    Password = ?';
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            return $db->error;
        }
        /* TODO: add some salt */
        $Hash = hash('sha512', $Password);
        $stmt->bind_param('is', $UserID, $Hash);
        if (!$stmt->execute()) {
            return $stmt->error;
        }
        $stmt->bind_result($Hash);
        if (!$stmt->fetch()) {
            return showInfo('Das eingegebene Password ist ungültig.<br /><a href="/login" class="back">Erneut versuchen</a>', 'login');
        }
        $stmt->close();

        setcookie('UserID', $UserID, strtotime("+1 day"), '/');
        setcookie('Password', $Hash, strtotime("+1 day"), '/');
        $_COOKIE['UserID'] = $UserID; // fake-cookie setzen
        $_COOKIE['Password'] = $Hash; // fake-cookie setzen

        return showInfo('Sie sind nun eingeloggt.', 'admin');
    } else {
        return $ret;
    }
?>