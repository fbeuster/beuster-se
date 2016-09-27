<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    $db = Database::getDB()->getCon();
    refreshCookies();

    $a['filename']  = 'newsoverview.php';
    $a['data']      = array();

    # get articles
    $news = array();
    $sql = "SELECT
              ID,
              Titel,
              Hits,
              Datum,
              TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp,
              enable
            FROM
              news
            ORDER BY
              Datum DESC";

    if (!$stmt = $db->prepare($sql)) {
      return $db->error;
    }

    if (!$stmt->execute()) {
        return $stmt->error;
    }
    $stmt->bind_result($id, $title, $hits, $date, $uptime, $enabled);

    while($stmt->fetch()) {
      $news[] = array(
                  'title'     => Parser::parse($title, Parser::TYPE_PREVIEW),
                  'link'      => '',
                  'id'        => $id,
                  'date'      => date("d.m.Y H:i", strtotime($date)),
                  'hits'      => $hits,
                  'per_day'   => number_format($hits / ($uptime < 1 ? 1 : $uptime), 2, '.', ','),
                  'enabled'   => $enabled);
    }
    $stmt->close();

    foreach ($news as $k => $v) {
      $news[$k]['link'] = getLink(getCatName(getNewsCat($v['id'])), $v['id'], $v['title']);
    }

    # get comment amount
    $sql = "SELECT
              COUNT(ID) AS cmtAmount
            FROM
              kommentare";

    if (!$stmt = $db->prepare($sql)) {
      return $db->error;
    }

    if (!$stmt->execute()) {
      return $stmt->error;
    }
    $stmt->bind_result($cmtAmount);

    if (!$stmt->fetch()) {
      return $stmt->error;
    }
    $stmt->close();

    # get comment amount
    $sql = "SELECT
              COUNT(news.ID) AS enaAmount
            FROM
              news
            LEFT JOIN
              newscatcross
              ON news.ID = newscatcross.NewsID
            WHERE
              enable = 0 AND
              newscatcross.Cat != 12";

    if (!$stmt = $db->prepare($sql)) {
        return $db->error;
    }

    if (!$stmt->execute()) {
      return $stmt->error;
    }
    $stmt->bind_result($enaAmount);

    if (!$stmt->fetch()) {
      return $stmt->error;
    }
    $stmt->close();

    $a['data']['news']        = $news;
    $a['data']['cmtAmount']   = $cmtAmount;
    $a['data']['enaAmount']   = $enaAmount;
    $a['data']['admin_news']  = true;

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>