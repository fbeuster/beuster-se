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
  $sql = "SELECT
            kommentare.NewsID,
            news.Titel,
            newscatcross.Cat,
            kommentare.ID,
            kommentare.Name,
            kommentare.Mail,
            kommentare.Inhalt,
            DATE_FORMAT(kommentare.Datum, '".DATE_STYLE."') AS Changedatum,
            kommentare.Website
          FROM
            kommentare
          JOIN
            news ON
            kommentare.NewsID = news.ID
          JOIN
            newscatcross ON
            news.ID = newscatcross.NewsID
          WHERE
            kommentare.frei = 0
          ORDER BY
            kommentare.NewsID DESC,
            Changedatum DESC";
  if(!$stmt = $db->query($sql)) {
    return $db->error;
  }

  $cmt = array();
  $idss = '';
  if($stmt->num_rows) {
    while($row = $stmt->fetch_assoc()) {
      $cmt[] = array( 'link'      => $row['Cat'],
                      'titel'     => shortenTitle($row['Titel']),
                      'titelFull' => $row['Titel'],
                      'name'      => $row['Name'],
                      'mail'      => $row['Mail'],
                      'inhalt'    => changetext($row['Inhalt'],'cmtInhalt'),
                      'datum'     => $row['Changedatum'],
                      'web'       => $row['Website'],
                      'id'        => $row['ID'],
                      'newsID'    => $row['NewsID']);
      $idss .= $row['ID'].'###';
    }
    $idss = substr($idss, 0, strlen($idss) - 3);
  }
  $a['data']['cmt'] = $cmt;
  $a['data']['idss'] = $idss;
  $stmt->close();
  foreach($a['data']['cmt'] as $k => $val) {
    if(newsExists($val['newsID']) === false) {
      $sql = 'DELETE FROM kommentare WHERE ID = ?';
      if(!$stmt = $db->prepare($sql)) {
        return $db->error;
      }
      $stmt->bind_param('i', $val['id']);
      if(!$stmt->execute()) {
        return $stmt->error;
      }
      $stmt->close();
      unset($a['data']['cmt'][$k]);
    } else {
      $a['data']['cmt'][$k]['link'] = getLink(getCatName($val['link']), $val['newsID'], $val['titelFull']);
    }
  }
  return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
} else if($user){
        return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');
} else {
  return 'Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>';
}
?>