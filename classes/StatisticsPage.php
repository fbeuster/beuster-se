<?php

  class StatisticsPage extends AbstractAdminPage {

    private $down = array();
    private $last = array();
    private $top  = array();

    public function __construct() {
      $this->load();
    }

    private function load() {
      $this->setTitle(I18n::t('admin.statistics.label'));

      $db = Database::getDB();

      # get top 10 article statistics
      $fields = array('ID', 'Hits', 'TO_DAYS(NOW()) - TO_DAYS(Datum) AS TimeUp');
      $conds  = 'enable = 1 AND Datum < NOW()';
      $opts   = 'GROUP BY ID ORDER BY Hits DESC, Datum DESC';
      $limit  = array('LIMIT ?, ?', 'ii', array(0, 10));
      $res    = $db->select('news', $fields, $conds, $opts, $limit);

      if ($res) {
        foreach ($res as $row) {
          $article  = new Article($row['ID']);
          $per_day  = $row['Hits'] / ($row['TimeUp'] < 1 ? 1 : $row['TimeUp']);
          $this->top[] = array(
                        'title'   => $article->getTitle(),
                        'link'    => $article->getLink(),
                        'id'      => $row['ID'],
                        'date'    => $article->getDateFormatted('d.m.Y'),
                        'hits'    => $row['Hits'],
                        'per_day' => number_format($per_day, 2, '.', ','));
        }
      }

      # get last 10 article statistics
      $opts   = 'GROUP BY ID ORDER BY Datum DESC, Hits DESC';
      $res    = $db->select('news', $fields, $conds, $opts, $limit);

      if ($res) {
        foreach ($res as $row) {
          $article  = new Article($row['ID']);
          $per_day  = $row['Hits'] / ($row['TimeUp'] < 1 ? 1 : $row['TimeUp']);
          $this->last[] = array(
                        'title'   => $article->getTitle(),
                        'link'    => $article->getLink(),
                        'id'      => $row['ID'],
                        'date'    => $article->getDateFormatted('d.m.Y'),
                        'hits'    => $row['Hits'],
                        'per_day' => number_format($per_day, 2, '.', ','));
        }
      }

      # get download statistics
      $fields = array('file_name', 'downloads');
      $res    = $db->select('attachments', $fields);

      if ($res) {
        foreach ($res as $row) {
          $this->down[] = array('name' => $row['file_name'],
                                'down' => $row['downloads']);
        }
      }
    }

    public function show() {
      include 'system/views/admin/statistics.php';
    }
  }

?>