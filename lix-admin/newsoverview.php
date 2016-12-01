<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    $db = Database::getDB();

    refreshCookies();

    $a['filename']  = 'newsoverview.php';
    $a['data']      = array();
    $article_lists  = array();

    # commen vars
    $fields   = array('ID', 'Hits', 'TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp');
    $options  = 'ORDER BY Datum DESC';

    # unlisted
    $conds    = array('enable = ?', 'i', array(0));
    $unlisted = $db->select('news', $fields, $conds, $options);

    foreach ($unlisted as $k => $article) {
      $unlisted[$k] = array(
                  'article'   => new Article($article['ID']),
                  'hits'      => $article['Hits'],
                  'per_day'   => number_format($article['Hits'] / ($article['TimeUp'] < 1 ? 1 : $article['TimeUp']), 2, '.', ','));
    }

    $article_lists['unlisted_articles'] = $unlisted;

    # future
    $conds    = array('Datum > NOW() AND enable = ?', 'i', array(1));
    $planned  = $db->select('news', $fields, $conds, $options);

    foreach ($planned as $k => $article) {
      $planned[$k] = array(
                  'article'   => new Article($article['ID']),
                  'hits'      => $article['Hits'],
                  'per_day'   => number_format($article['Hits'] / ($article['TimeUp'] < 1 ? 1 : $article['TimeUp']), 2, '.', ','));
    }

    $article_lists['planned_articles'] = $planned;

    # released
    $conds    = array('Datum < NOW() AND enable = ?', 'i', array(1));
    $released = $db->select('news', $fields, $conds, $options);

    foreach ($released as $k => $article) {
      $released[$k] = array(
                  'article'   => new Article($article['ID']),
                  'hits'      => $article['Hits'],
                  'per_day'   => number_format($article['Hits'] / ($article['TimeUp'] < 1 ? 1 : $article['TimeUp']), 2, '.', ','));
    }

    $article_lists['released_articles'] = $released;

    # get number of comments
    $total_comments = 0;
    $fields         = array('COUNT(ID) AS total_comments');
    $res            = $db->select('kommentare', $fields);

    if (count($res)) {
      $total_comments = $res[0]['total_comments'];
    }

    $a['data']['article_lists']   = $article_lists;
    $a['data']['total_articles']  = count($planned) + count($released) + count($unlisted);
    $a['data']['total_comments']  = $total_comments;
    $a['data']['unlisted']        = count($unlisted);
    $a['data']['admin_news']      = true;

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>