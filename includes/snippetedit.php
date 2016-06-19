<?php
  $a = array();
  $user = User::newFromCookie();
  if ($user && $user->isAdmin()) {
    refreshCookies();
    $a['filename'] = 'snippetedit.php';
    $a['data'] = array();
    $err = 0;
    $db = Database::getDB();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      if (isset($_POST['formactionchange'])) {
        /*** hier ändern ***/
        $content  = Parser::parse($_POST['content'], Parser::TYPE_NEW);
        $name     = trim($_POST['name']);
        $old_name = trim($_POST['old_name']);

        $eRet = array(  'content' => $content,
                        'name'    => $name);

        if('' == $name || '' == $content) {
          # empty name or content
          $err = 1;

        } else if(strlen($name) > 20) {
          $err = 2;

        } else if(false) {
          # already exists
          $err = 3;

        } else if(false) {
          # old_name not exists
          $err = 4;

        } else {
          $db2 = $db->getCon();

          $sql = 'UPDATE
                    snippets
                  SET
                    name = ?,
                    content_de = ?,
                    content_en = ?
                  WHERE
                    name = ?';
          if(!$stmt = $db2->prepare($sql)) {return $db2->error;}
          $stmt->bind_param('ssss', $name, $content, $content, $old_name);
          if(!$stmt->execute()) {return $stmt->error;}
          $stmt->close();
        }

        if($err != 0) {
          $a['data']['err'] = $eRet;
          $a['data']['err']['type'] = analyseErrNewsBea($err);

        } else {
          return showInfo('Das Snippet wurde geändert. <br /><a href="/snippetedit" class="back">Zurück zum Bearbeiten</a>', 'newsbea');
        }

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
    return showInfo('Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>', 'blog');
  }
?>