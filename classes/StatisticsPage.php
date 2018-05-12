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
      $fields = array('id', 'hits', 'TO_DAYS(NOW()) - TO_DAYS(created) AS uptime');
      $conds  = 'public = 1 AND created < NOW()';
      $opts   = 'GROUP BY id ORDER BY hits DESC, created DESC';
      $limit  = array('LIMIT ?, ?', 'ii', array(0, 10));
      $res    = $db->select('articles', $fields, $conds, $opts, $limit);

      if ($res) {
        foreach ($res as $row) {
          $article  = new Article($row['id']);
          $per_day  = $row['hits'] / ($row['uptime'] < 1 ? 1 : $row['uptime']);
          $this->top[] = array(
                        'title'   => $article->getTitle(),
                        'link'    => $article->getLink(),
                        'id'      => $row['id'],
                        'date'    => $article->getDateFormatted('d.m.Y'),
                        'hits'    => $row['hits'],
                        'per_day' => number_format($per_day, 2, '.', ','));
        }
      }

      # get last 10 article statistics
      $opts   = 'GROUP BY id ORDER BY created DESC, hits DESC';
      $res    = $db->select('articles', $fields, $conds, $opts, $limit);

      if ($res) {
        foreach ($res as $row) {
          $article  = new Article($row['id']);
          $per_day  = $row['hits'] / ($row['uptime'] < 1 ? 1 : $row['uptime']);
          $this->last[] = array(
                        'title'   => $article->getTitle(),
                        'link'    => $article->getLink(),
                        'id'      => $row['id'],
                        'date'    => $article->getDateFormatted('d.m.Y'),
                        'hits'    => $row['hits'],
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