<?php
$a = array();
$user = User::newFromId(getUserID());
if ($user && $user->isAdmin()) {
  refreshCookies();
  $a['filename'] = 'admindownbea.php';
  $a['data'] = array();

  if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['formactionchange'])) {
    if (!isset($_POST['downname'], $_POST['downdescr'], $_POST['downver'], $_FILES['file'], $_POST['formaction'])) {
      return INVALID_FORM;
    }
    if (('' == $downname = changetext($_POST['downname'], 'neu')) OR
        ('' == $downdescr = changetext($_POST['downdescr'], 'neu')) OR
        ('' == $downver = $_POST['downver'])) {
      return EMPTY_FORM;
    }
    $db = Database::getDB()->getCon();
    if(0 == $downlic = $_POST['downlic']) {
      $downlic = 'by-sa';
    }
    $logid = 0;
    if(!empty($_FILES['file'])) {
      $e = array();
      if($_FILES['file']['size'] > 0 && $_FILES['file']['size'] < 5242880) {
        if(!file_exists($pfad)) {
          $name = $_FILES['file']['name'];
          move_uploaded_file($_FILES['file']['tmp_name'], $pfad);
          $sql = "INSERT INTO
                   files(Name, Path)
                  VALUES
                    (?, ?);";
          if(!$stmt = $db->prepare($sql)){
            return $db->error;
          }
          $stmt->bind_param('ss', $name, $pfad);
          if(!$stmt->execute()){
            return $stmt->error;
          }
          $stmt->close();

          $sql = "SELECT
                    ID
                  FROM
                    files
                  WHERE
                    Path = ?";
          if(!$stmt = $db->prepare($sql)) {
            return $db->error;
          }
          $stmt->bind_param('s', $pfad);
          if(!$stmt->execute()){
            return $stmt->error;
          }
          $stmt->bind_result($fileid);
          $stmt->close();
        } else {
          $e[] = $_FILES['file']['name'];
        }
      } else if($_FILES['file']['size'] != 0){
        $e[] = $_FILES['file']['name'];
      }
      if(empty($e)) {
        if(!empty($_FILES['log'])) {
          if($_FILES['log']['size'] > 0 && $_FILES['log']['size'] < 5242880) {
            $logpfad = 'files/'.pathinfo($FILES['log']['name'], PATHINFO_EXTENSION);
            if(!file_exists($pfad)) {
              $logname = $_FILES['log']['name'];
              move_uploaded_file($_FILES['log']['tmp_name'], $logpfad);
              $sql = "INSERT INTO
                        files(Name, Path)
                      VALUES
                        (?, ?);";
              if(!$stmt = $db->prepare($sql)) {
                return $db->error;
              }
              $stmt->bind_param('ssi', $logname, $logpfad, 0);
              if(!$stmt->execute()) {
                return $stmt->error;
              }
              $stmt->close();

              $sql = "SELECT
                        ID
                      FROM
                        files
                      WHERE
                        Path = ?";
              if(!$stmt = $db->prepare($sql)) {
                return $db->error;
              }
              $stmt->bind_param('s', $logpfad);
              if(!$stmt->execute()) {
                return $stmt->error;
              }
              $stmt->bind_result($logid);
              $stmt->close();
            }
          } else if($_FILES['log']['size'] != 0) {
            $e[] = $_FILES['log']['name'];
            $sql = "DELETE FROM
                      downloads
                    WHERE
                      ID = ?;";
            if(!$stmt = $db->prepare($sql)) {
              return $db->error;
            }
            $stmt->bind_param('i', $fileid);
            if(!$stmt->execute()) {
              return $stmt->error;
            }
            $stmt->close();
            unlink($pfad);
          }
        }
        if(empty($e)) {
          $sql = "INSERT INTO
                    downloads(Name, Description, Version, License, File, Log)
                  VALUES
                    (?, ?, ?, ?, ?, ?);";
          if(!$stmt = $db->prepare($sql)) {
            return $db->error;
          }
          $stmt->bind_param('ssssii', $downname, $downdescr, $downver, $downlic, $fileid, $logid);
          if(!$stmt->execute()) {
            return $stmt->error;
          }
          $stmt->close();
          return showInfo('Der Download wurde hinzugefügt. <br /><a href="/admin" class="back">Zurück zur Administration</a>', 'admin');
        } else {
          $a['data']['fe'] = array('name' => $downname, 'descr' => $downdescr);
          $a['data']['fm'] = $e;
        }
      } else {
        $a['data']['fe'] = array('name' => $downname, 'descr' => $downdescr);
        $a['data']['fm'] = $e;
      }
    } else {
      $a['data']['fe'] = array('name' => $downname, 'descr' => $downdescr);
    }
  } else if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['formactionchoose'])) {
    if(0 != $id = trim($_POST['down'])) {
      $sql = "SELECT
                Name,
                Description,
                Version,
                License,
                CatID
              FROM
                downloads
              WHERE
                ID = ?";
      if(!$stmt = $db->prepare($sql)) {
        return $db->error;
      }
      $stmt->bind_param('i', $id);
      if(!$stmt->execute()) {
        return $stmt->error;
      }
      $down = array();
      $stmt->bind_result($name, $descr, $ver, $lic, $cat);
      while($stmt->fetch()) {
        $down[] = array('id'    => $id,
                        'name'  => $name,
                        'descr' => $descr,
                        'ver'   => $ver,
                        'lic'   => $lic,
                        'cat'   => $cat);
      }
      $a['data']['downs'] = $down;
      $stmt->close();
    }
  }

  $sql = "SELECT
            ID,
            Name
          FROM
            downloads";
  if(!$stmt = $db->prepare($sql)) {
    return $db->error;
  }
  if(!$stmt->execute()) {
    return $stmt->error;
  }
  $down = array();
  $stmt->bind_result($id, $name);
  while($stmt->fetch()) {
    $down[] = array('id' => $id, 'name' => $name);
  }
  $a['data']['downs'] = $down;
  $stmt->close();
  return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
} else if($user){
        return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');
} else {
  return 'Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>';
}
?>