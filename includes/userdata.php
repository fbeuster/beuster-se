<?php
  $a = array();
  if ($UserID = getUserID()) {
    refreshCookies();
    $db = Database::getDB()->getCon();
    $a['filename'] = 'userdata.php';
    $a['data'] = array();
    $err = '';

    $sql = 'SELECT
              Email
            FROM
              users
            WHERE
              ID = ?';
    if(!$stmt = $db->prepare($sql)) {
      return $db->error;
    }
    $stmt->bind_param('i', getUserID());
    if (!$stmt->execute()) {
        return $stmt->error;
    }
    $stmt->bind_result($mailold);
    $stmt->fetch();
    $a['data']['mailold'] = $mailold;
    $stmt->close();
    
    
    if('POST' == $_SERVER['REQUEST_METHOD']) {
      if(isset($_POST['changeMail']) || isset($_POST['changeBoth'])) {
        if ('' == $Mail = trim($_POST['mail'])) {
          $err = 'Kein Feld wurde ausgefüllt, daher keine Änderung.';
        }
        if($Mail != '') {
          if(!preg_match('/^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+\.[a-zA-Z]{2,4}$/', $Mail)) {
            $err = 'Die angegebene E-Mail-Adresse ist ungültig.';
          }
        }
        if($err == '') {
          $sql = 'UPDATE
                    users
                  SET
                    Email = ?
                  WHERE
                    ID = ?';
          $stmt = $db->prepare($sql);
          if (!$stmt) {
            return $db->error;
          }
          $stmt->bind_param('si', $Mail, getUserID());
          if (!$stmt->execute()) {
            return $stmt->error;
          }
          $stmt->close();
        }
      }
      if(isset($_POST['changePass']) || (isset($_POST['changeBoth']) && $err == '')) {
        $PassOld = trim($_POST['passOld']);
        $Pass = trim($_POST['pass']);
        $Pass2 = trim($_POST['pass2']);
        if( '' == $PassOld ||
            '' == $Pass ||
            '' == $Pass2) {
          $err = 'Passwortfelder nicht ausgefüllt.';
        }
        if($err == '') {
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
          $Hash = hash('sha512', $PassOld);
          $stmt->bind_param('is', $UserID, $Hash);
          if (!$stmt->execute()) {
            return $stmt->error;
          }
          $stmt->bind_result($Hash);
          if (!$stmt->fetch()) {
            $err = 'Altes Passwort falsch';
          }
          $stmt->close();
        }
        if( $err == '' &&
            $Pass != $Pass2) {
          $err == 'Passwörter stimmen nicht überein.';
        }
        if($err == '') {
          $Hash = hash('sha512', $Pass);
          $sql = 'UPDATE
                    users
                  SET
                    Password = ?
                  WHERE
                    ID = ?';
          $stmt = $db->prepare($sql);
          if (!$stmt) {
            return $db->error;
          }
          $stmt->bind_param('si', $Hash, getUserID());
          if (!$stmt->execute()) {
            return $stmt->error;
          }
          $stmt->close();
          refreshCookies($Hash);
        }
      }
      if($err == '') {
        if(isset($_POST['changeBoth'])) {
          return showInfo('E-Mail und Passwort wurden erfolgreich geändert, du wirst weitergeleitet.', 'admin');
        }
        if(isset($_POST['changePass'])) {
          return showInfo('Passwort wurde erfolgreich geändert, du wirst weitergeleitet.', 'admin');
        }
        if(isset($_POST['changeMail'])) {
          return showInfo('E-Mail wurde erfolgreich geändert, du wirst weitergeleitet.', 'admin');
        }
      }
    }
    $a['data']['err'] = $err;
    return $a; // nicht Vergessen, sonst enthält $a nur den Wert int(1)
  } else {
    return 'Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>';
  }
?>