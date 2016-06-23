<?php
  $a = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    refreshCookies();
    $a['filename'] = 'snippetdel.php';
    $a['data'] = array();
    $db = Database::getDB();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      if (isset($_POST['formactiondel'])) {

        $db2 = $db->getCon();

        // remove news
        $sql = 'DELETE FROM
                  snippets
                WHERE
                  name LIKE ?';
        if(!$stmt = $db2->prepare($sql)) {return $db2->error;}
        $stmt->bind_param('s', $_POST['name']);
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->close();

        return showInfo('Das Snippet wurde gelöscht. <br /><a href="/admin" class="back">Zurück zur Administration</a>', 'admin');

      } else if(isset($_POST['formactionchoose'])) {
        $name = trim($_POST['snippetname']);

        $fields = array('name', 'content_de');
        $conds  = array('name = ?', 's', array($name));
        $res    = $db->select('snippets', $fields, $conds);

        if (count($res) > 0) {
          $a['data']['snippetedit'] = array(
                                        'name'    => $name,
                                        'content' => $res[0]['content_de']);
        }
      }
    }


    $fields = array('name');
    $res    = $db->select('snippets', $fields);

    $snippets = array();

    foreach ($res as $result) {
      $snippets[] = $result['name'];
    }

    $a['data']['snippets'] = $snippets;
    return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)

  } else if($user){
    return showInfo('Sie haben hier keine Zugriffsrechte.', 'blog');

  } else {
    return showInfo('Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>', 'login');
  }
?>