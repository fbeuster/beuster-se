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
        $this->total_articles++;
        $this->unlisted++;
      }

      $this->article_lists['unlisted_articles'] = $unlisted;

      # future
      $conds    = array('Datum > NOW() AND enable = ?', 'i', array(1));
      $planned  = $db->select('news', $fields, $conds, $options);

      foreach ($planned as $k => $article) {
        $planned[$k] = array(
                    'article'   => new Article($article['ID']),
                    'hits'      => $article['Hits'],
                    'per_day'   => number_format($article['Hits'] / ($article['TimeUp'] < 1 ? 1 : $article['TimeUp']), 2, '.', ','));
        $this->total_articles++;
      }

      $this->article_lists['planned_articles'] = $planned;

      # released
      $conds    = array('Datum < NOW() AND enable = ?', 'i', array(1));
      $released = $db->select('news', $fields, $conds, $options);

      foreach ($released as $k => $article) {
        $released[$k] = array(
                    'article'   => new Article($article['ID']),
                    'hits'      => $article['Hits'],
                    'per_day'   => number_format($article['Hits'] / ($article['TimeUp'] < 1 ? 1 : $article['TimeUp']), 2, '.', ','));
        $this->total_articles++;
      }

      $this->article_lists['released_articles'] = $released;

      # get number of comments
      $fields = array('COUNT(ID) AS total_comments');
      $res    = $db->select('kommentare', $fields);

      if (count($res)) {
        $this->total_comments = $res[0]['total_comments'];
      }
    }

    public function show() {
      include 'system/views/admin/article_overview.php';
    }
  }

?>
