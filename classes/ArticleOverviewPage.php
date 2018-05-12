<?php

  class ArticleOverviewPage extends AbstractAdminPage {

    private $article_lists  = array();
    private $unlisted       = 0;
    private $total_articles = 0;
    private $total_comments = 0;

    public function __construct() {
      $this->load();
    }

    private function load() {
      $this->setTitle(I18n::t('admin.article.overview.label'));

      $db = Database::getDB();

      # commen vars
      $fields   = array('id', 'hits', 'TO_DAYS(NOW()) - TO_DAYS(created) AS uptime');
      $options  = 'ORDER BY created DESC';

      # unlisted
      $conds    = array('public = ?', 'i', array(0));
      $unlisted = $db->select('articles', $fields, $conds, $options);

      foreach ($unlisted as $k => $article) {
        $unlisted[$k] = array(
                    'article'   => new Article($article['id']),
                    'hits'      => $article['hits'],
                    'per_day'   => number_format($article['hits'] / ($article['uptime'] < 1 ? 1 : $article['uptime']), 2, '.', ','));
        $this->total_articles++;
        $this->unlisted++;
      }

      $this->article_lists['unlisted_articles'] = $unlisted;

      # future
      $conds    = array('created > NOW() AND public = ?', 'i', array(1));
      $planned  = $db->select('articles', $fields, $conds, $options);

      foreach ($planned as $k => $article) {
        $planned[$k] = array(
                    'article'   => new Article($article['id']),
                    'hits'      => $article['hits'],
                    'per_day'   => number_format($article['hits'] / ($article['uptime'] < 1 ? 1 : $article['uptime']), 2, '.', ','));
        $this->total_articles++;
      }

      $this->article_lists['planned_articles'] = $planned;

      # released
      $conds    = array('created < NOW() AND public = ?', 'i', array(1));
      $released = $db->select('articles', $fields, $conds, $options);

      foreach ($released as $k => $article) {
        $released[$k] = array(
                    'article'   => new Article($article['id']),
                    'hits'      => $article['hits'],
                    'per_day'   => number_format($article['hits'] / ($article['uptime'] < 1 ? 1 : $article['uptime']), 2, '.', ','));
        $this->total_articles++;
      }

      $this->article_lists['released_articles'] = $released;

      # get number of comments
      $fields = array('COUNT(id) AS total_comments');
      $res    = $db->select('comments', $fields);

      if (count($res)) {
        $this->total_comments = $res[0]['total_comments'];
      }
    }

    public function show() {
      include 'system/views/admin/article_overview.php';
    }
  }

?>
