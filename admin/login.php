<?php
    $user = User::newFromCookie();
    if ($user) {
        return showInfo(I18n::t('login.already_signed_in'), 'admin');
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
            $back = '<a href="/login" class="back">'.I18n::t('login.try_again').'</a>';
            return  showInfo(I18n::t('login.invalid_user').'<br />'.$back, 'login');
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
            $back = '<a href="/login" class="back">'.I18n::t('login.try_again').'</a>';
            return  showInfo(I18n::t('login.invalid_password').'<br />'.$back, 'login');
        }
        $stmt->close();

        setcookie('UserID', $UserID, strtotime("+1 day"), '/');
        setcookie('Password', $Hash, strtotime("+1 day"), '/');
        $_COOKIE['UserID'] = $UserID;
        $_COOKIE['Password'] = $Hash;

        return showInfo(I18n::t('login.success'), 'admin');
    } else {
        return $ret;
    }
?>