<?php
$a = array();
$user = User::newFromCookie();
if ($user && $user->isAdmin()) {
  refreshCookies();
  $a['filename'] = 'admincmtenable.php';
  $a['data'] = array();

  $db = Database::getDB()->getCon();

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(preg_match('/###/', $ids = $_POST['ids'])) {
      $ids = preg_split('/###/', $ids);
    }
    if(is_array($ids)) {
      foreach($ids as $id) {
        if(isset($_POST[$id])) {
          if($_POST[$id] == 'del') {
            $sql = 'DELETE FROM kommentare WHERE ID = ?';
          } else {
            $sql = 'UPDATE kommentare SET Frei = 1 WHERE ID = ?';
          }
          if(!$stmt = $db->prepare($sql)) {
            return $db->error;
          }
          $stmt->bind_param('i', $id);
          if(!$stmt->execute()) {
            return $stmt->error;
          }
          $stmt->close();
        }
      }
    } else {
      if(isset($_POST[$ids])) {
        if($_POST[$ids] == 'del') {
          $sql = 'DELETE FROM kommentare WHERE ID = ?';
        } else {
          $sql = 'UPDATE kommentare SET Frei = 1 WHERE ID = ?';
        }
        if(!$stmt = $db->prepare($sql)) {
          return $db->error;
        }
        $stmt->bind_param('i', $ids);
        if(!$stmt->execute()) {
          return $stmt->error;
        }
        $stmt->close();
      }
    }
    return showInfo('Erfolgreich abgeschlossen.<br><a href="/admin" class="back">Zurück zur Administration</a>', 'admin');
  }

  $db2 = Database::getDB();
  $fields = array('`ID`', '`UID`', '`NewsID`', '`Inhalt`', 'UNIX_TIMESTAMP(Datum) AS `comment_date`');
  $conds = array('Frei = ?', 'i', array(0));
  $options = 'ORDER BY comment_date DESC, NewsID DESC';

  $results = $db2->select('kommentare', $fields, $conds, $options);

  $cmt = array();
  $idss = '';

  foreach($results as $result) {
    $cmt[] = array( 'content' => changetext($result['Inhalt'], 'cmtInhalt'),
                    'date'    => $result['comment_date'],
                    'id'      => $result['ID'],
                    'user'    => $result['UID'],
                    'news'    => $result['NewsID']);
  }

  foreach ($cmt as $key => $cmt_single) {
    $cmt_single['user'] = User::newFromId($cmt_single['user']);
    $cmt_single['news'] = new Article($cmt_single['news']);
    $cmt[$key] = $cmt_single;
  }

  $a['data']['cmt'] = $cmt;
  $a['data']['idss'] = $idss;
  return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)

} else if($user){
  return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');
} else {
  return showInfo('Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>', 'login');
}
?>