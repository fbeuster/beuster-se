<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    $db = Database::getDB();

    refreshCookies();

    $a['filename']  = 'newsoverview.php';
    $a['data']      = array();

    # get articles
    $fields   = array('ID', 'Hits', 'TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp', 'enable');
    $options  = 'ORDER BY Datum DESC';
    $articles = $db->select('news', $fields, null, $options);

    foreach ($articles as $k => $article) {
      $ar = new Article($article['ID']);
      $articles[$k] = array(
                  'title'     => Parser::parse($ar->getTitle(), Parser::TYPE_PREVIEW),
                  'link'      => $ar->getLink(),
                  'id'        => $article['ID'],
                  'date'      => $ar->getDateFormatted("d.m.Y H:i"),
                  'hits'      => $article['Hits'],
                  'per_day'   => number_format($article['Hits'] / ($article['TimeUp'] < 1 ? 1 : $article['TimeUp']), 2, '.', ','),
                  'enabled'   => $article['enable']);
    }

    # get number of comments
    $total_comments = 0;
    $fields         = array('COUNT(ID) AS total_comments');
    $res            = $db->select('kommentare', $fields);

    if (count($res)) {
      $total_comments = $res[0]['total_comments'];
    }

    # get number of unlisted articles
    $unlisted = 0;
    $fields   = array('COUNT(news.ID) AS unlisted');
    $conds    = array('enable = ? AND newscatcross.Cat != ?', 'ii', array(0, 12));
    $joins    = 'LEFT JOIN newscatcross ON news.ID = newscatcross.NewsID';
    $res      = $db->select('news', $fields, $conds, null, null, $joins);

    if (count($res)) {
      $unlisted = $res[0]['unlisted'];
    }

    $a['data']['articles']        = $articles;
    $a['data']['total_comments']  = $total_comments;
    $a['data']['unlisted']        = $unlisted;
    $a['data']['admin_news']      = true;

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>