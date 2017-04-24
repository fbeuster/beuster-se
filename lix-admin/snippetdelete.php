<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    refreshCookies();

    $a['filename']  = 'snippetdelete.php';
    $a['data']      = array();
    $a['title']     = I18n::t('admin.snippet.delete.label');

    $db = Database::getDB();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      if (isset($_POST['formactiondel'])) {

        $db2 = $db->getCon();

        # remove snippet
        $cond = array('name LIKE ?', 's', array($_POST['name']));
        $res  = $db->delete('snippets', $cond);

        $link = '<br /><a href="/admin">'.I18n::t('admin.back_link').'</a>';
        return showInfo(I18n::t('admin.snippet.delete.success').$link, 'admin');

      } else if (isset($_POST['formactionchoose'])) {
        $name   = trim($_POST['snippetname']);
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

    $fields   = array('name');
    $res      = $db->select('snippets', $fields);
    $snippets = array();

    foreach ($res as $result) {
      $snippets[] = $result['name'];
    }

    $a['data']['snippets'] = $snippets;

    if (!isset($a['data']['snippetedit'])) {
      $a['data']['snippetedit'] = array('name' => '', 'content' => '');
    }

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>