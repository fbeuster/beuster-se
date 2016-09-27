<?php
  $a = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    refreshCookies();
    $a['filename'] = 'stats.php';
    $a['data'] = array();
    $db = Database::getDB()->getCon();

    # get top 10 article statistics
    $top = array();
    $sql = "SELECT
              ID,
              Titel,
              Hits,
              Datum,
              TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp
            FROM
              news
            WHERE
              enable = 1 AND
              Datum < NOW()
            GROUP BY
              ID
            ORDER BY
              Hits DESC,
              Datum DESC
            LIMIT
              0, 10";

    if(!$stmt = $db->prepare($sql)) {
      return $db->error;
    }

    if(!$stmt->execute()) {
      return $result->error;
    }
    $stmt->bind_result($id, $title, $hits, $date, $uptime);

    while($stmt->fetch()) {
      $top[] = array(
                  'title'   => Parser::parse($title, Parser::TYPE_PREVIEW),
                  'link'    => '',
                  'id'      => $id,
                  'date'    => date("d.m.Y H:i", strtotime($date)),
                  'hits'    => $hits,
                  'per_day' => number_format($hits / ($uptime < 1 ? 1 : $uptime), 2, '.', ','));
    }
    $stmt->close();

    foreach($top as $k => $v) {
      $top[$k]['link'] = getLink(getCatName(getNewsCat($v['id'])), $v['id'], $v['title']);
    }
    $a['data']['top'] = $top;

    # get last 10 article statistics
    $last = array();
    $sql = "SELECT
              ID,
              Titel,
              Hits,
              Datum,
              TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp
            FROM
              news
            WHERE
              enable = 1 AND
              Datum < NOW()
            GROUP BY
              ID
            ORDER BY
              Datum DESC,
              Hits DESC
            LIMIT
              0, 10";

    if(!$stmt = $db->prepare($sql)) {
      return $db->error;
    }

    if(!$stmt->execute()) {
      return $result->error;
    }
    $stmt->bind_result($id, $title, $hits, $date, $uptime);

    while($stmt->fetch()) {
      $last[] = array(
                  'title'   => Parser::parse($title, Parser::TYPE_PREVIEW),
                  'link'    => '',
                  'id'      => $id,
                  'date'    => date("d.m.Y H:i", strtotime($date)),
                  'hits'    => $hits,
                  'per_day' => number_format($hits / ($uptime < 1 ? 1 : $uptime), 2, '.', ','));
    }
    $stmt->close();

    foreach($last as $k => $v) {
      $last[$k]['link'] = getLink(getCatName(getNewsCat($v['id'])), $v['id'], $v['title']);
    }
    $a['data']['last'] = $last;

    # get download statistics
    $sql = "SELECT
              Name,
              downloads
            FROM
              files";

    if(!$stmt = $db->prepare($sql)) {
      return $db->error;
    }

    if(!$stmt->execute()) {
      return $stmt->error;
    }
    $stmt->bind_result($name, $downloads);

    $down = array();
    while($stmt->fetch()) {
          $down[] = array(
                      'name' => $name,
                      'down' => $downloads);
    }
    $a['data']['down'] = $down;

    $stmt->close();
    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>