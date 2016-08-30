<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    $db = Database::getDB()->getCon();
    refreshCookies();

    $a['filename']  = 'snippetoverview.php';
    $a['data']      = array();

    # get articles
    $snippets = array();
    $sql = "SELECT
              name,
              created,
              edited
            FROM
              snippets
            ORDER BY
              name ASC";

    if (!$stmt = $db->prepare($sql)) {
      return $db->error;
    }

    if (!$stmt->execute()) {
        return $stmt->error;
    }
    $stmt->bind_result($name, $created, $edited);

    while($stmt->fetch()) {
      $snippets[] = array(
                  'name'    => $name,
                  'created' => date("d.m.Y H:i", strtotime($created)),
                  'edited'  => date("d.m.Y H:i", strtotime($edited)));
    }
    $stmt->close();

    $a['data']['snippets'] = $snippets;

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(i18N::T('admin.not_logged_in').$link, 'login');
  }
?>