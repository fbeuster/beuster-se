<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    $db = Database::getDB()->getCon();
    $user->refreshCookies();

    $a['filename']  = 'staticpageoverview.php';
    $a['data']      = array();
    $a['title']     = I18n::t('admin.static_page.overview.label');

    # get articles
    $pages = array();
    $sql = "SELECT
              url,
              title
            FROM
              static_pages
            ORDER BY
              url ASC";

    if (!$stmt = $db->prepare($sql)) {
      return $db->error;
    }

    if (!$stmt->execute()) {
        return $stmt->error;
    }
    $stmt->bind_result($url, $title);

    while($stmt->fetch()) {
      $pages[] = array(
                  'url'   => $url,
                  'title' => $title);
    }
    $stmt->close();

    $a['data']['pages'] = $pages;

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>