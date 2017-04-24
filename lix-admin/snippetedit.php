<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    refreshCookies();

    $a['filename']  = 'snippetedit.php';
    $a['data']      = array();
    $a['title']     = I18n::t('admin.snippet.edit.label');

    $db = Database::getDB();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      if (isset($_POST['formactionchange'])) {
        /*** hier ändern ***/
        $content  = Parser::parse($_POST['content'], Parser::TYPE_NEW);
        $name     = trim($_POST['name']);
        $old_name = trim($_POST['old_name']);
        $errors   = array();
        $values   = array(  'content' => $content,
                            'name'    => $name);

        if ($name == '') {
          $errors['name'] = array(
            'message' => I18n::t('admin.snippet.edit.errors.empty_name'),
            'value'   => $name);
        }

        if (strlen($name) > 20) {
          $errors['name'] = array(
            'message' => I18n::t('admin.snippet.edit.errors.long_name'),
            'value'   => $name);
        }

        if (!preg_match('#^[A-Za-z0-9]*$#', $name)) {
          $errors['name'] = array(
            'message' => I18n::t('admin.snippet.edit.errors.invalid_characters'),
            'value'   => $name);
        }

        if (Snippet::exists($name) && $name !== $old_name) {
          $errors['name'] = array(
            'message' => I18n::t('admin.snippet.edit.errors.exists'),
            'value'   => $name);
        }

        if (!Snippet::exists($old_name)) {
          $errors['name'] = array(
            'message' => I18n::t('admin.snippet.edit.errors.no_old_exists'),
            'value'   => $name);
        }

        if ($content == '') {
          $errors['content'] = array(
            'message' => I18n::t('admin.snippet.edit.errors.empty_content'),
            'value'   => $content);
        }

        if (!empty($errors)) {
          $a['data']['errors'] = $errors;
          $a['data']['values'] = $values;

        } else {
          $db2 = $db->getCon();
          $now = date("Y-m-d H:i:s", time());

          $sql = 'UPDATE
                    snippets
                  SET
                    name = ?,
                    content_de = ?,
                    content_en = ?,
                    edited = ?
                  WHERE
                    name LIKE ?';
          if(!$stmt = $db2->prepare($sql)) {return $db2->error;}
          $stmt->bind_param('sssss', $name, $content, $content, $now, $old_name);
          if(!$stmt->execute()) {return $stmt->error;}
          $stmt->close();

          $link = '<br /><a href="/admin">'.I18n::t('admin.back_link').'</a>';
          return showInfo(I18n::t('admin.snippet.edit.success').$link, 'admin');
        }

      } else if (isset($_POST['formactionchoose'])) {
        $name   = trim($_POST['snippetname']);
        $fields = array('name', 'content_de');
        $conds  = array('name = ?', 's', array($name));
        $res    = $db->select('snippets', $fields, $conds);

        if (count($res) > 0) {
          $a['data']['values'] = array(
                                  'name'    => $name,
                                  'content' => $res[0]['content_de']);
        }
      }
    } else if ('GET' == $_SERVER['REQUEST_METHOD']) {
      if (isset($_GET['snip'])) {
        $name   = trim($_GET['snip']);
        $fields = array('name', 'content_de');
        $conds  = array('name = ?', 's', array($name));
        $res    = $db->select('snippets', $fields, $conds);

        if (count($res) > 0) {
          $a['data']['values'] = array(
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

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }

?>