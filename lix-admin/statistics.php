<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    refreshCookies();

    $a['data']      = array();
    $a['filename']  = 'statistics.php';
    $a['title']     = I18n::t('admin.statistics.label');

    $db = Database::getDB();

    # get top 10 article statistics
    $top    = array();
    $fields = array('ID', 'Hits', 'TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp');
    $conds  = 'enable = 1 AND Datum < NOW()';
    $opts   = 'GROUP BY ID ORDER BY Hits DESC, Datum DESC';
    $limit  = array('LIMIT ?, ?', 'ii', array(0, 10));
    $res    = $db->select('news', $fields, $conds, $opts, $limit);

    if ($res) {
      foreach ($res as $row) {
        $article  = new Article($row['ID']);
        $per_day  = $row['Hits'] / ($row['TimeUp'] < 1 ? 1 : $row['TimeUp']);
        $top[]    = array(
                      'title'   => $article->getTitle(),
                      'link'    => $article->getLink(),
                      'id'      => $row['ID'],
                      'date'    => $article->getDateFormatted('d.m.Y'),
                      'hits'    => $row['Hits'],
                      'per_day' => number_format($per_day, 2, '.', ','));
      }
    }

    $a['data']['top'] = $top;

    # get last 10 article statistics
    $last   = array();
    $opts   = 'GROUP BY ID ORDER BY Datum DESC, Hits DESC';
    $res    = $db->select('news', $fields, $conds, $opts, $limit);

    if ($res) {
      foreach ($res as $row) {
        $article  = new Article($row['ID']);
        $per_day  = $row['Hits'] / ($row['TimeUp'] < 1 ? 1 : $row['TimeUp']);
        $last[]   = array(
                      'title'   => $article->getTitle(),
                      'link'    => $article->getLink(),
                      'id'      => $row['ID'],
                      'date'    => $article->getDateFormatted('d.m.Y'),
                      'hits'    => $row['Hits'],
                      'per_day' => number_format($per_day, 2, '.', ','));
      }
    }

    $a['data']['last'] = $last;

    # get download statistics
    $fields = array('file_name', 'downloads');
    $res    = $db->select('attachments', $fields);

    if ($res) {
      foreach ($res as $row) {
        $down[] = array(
                    'name' => $row['file_name'],
                    'down' => $row['downloads']);
      }
    }

    $a['data']['down'] = $down;

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>