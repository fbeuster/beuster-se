<?php

  class SidebarModuleListArticles extends SidebarModuleList {

    const DEFAULT_LENGTH = 5;

    const TYPE_MOST = 1;
    const TYPE_LAST = 2;

    private $list;
    private $n;
    private $type;

    public function __construct($n, $type) {
      $this->list = array();
      $this->n    = $n;
      $this->type = $type;

      $this->generateList();

      $config = array("title"   => $this->getTitle(),
                      "classes" => "top list",
                      "list"    => $this->list);

      parent::__construct($config);
    }

    private function generateList() {
      if ($this->type == self::TYPE_MOST) {
        $options = 'GROUP BY ID ORDER BY Hits DESC, Datum DESC';

      } else {
        $options = 'GROUP BY ID ORDER BY Datum DESC';
      }

      $db         = Database::getDB();
      $res        = array();
      $fields     = array('ID');
      $conds      = array('enable = ? AND Datum < NOW()', 'i', array(1));
      $limit      = array('LIMIT 0, ?', 'i', array($this->n));
      $articles   = $db->select('news', $fields, $conds, $options, $limit);

      foreach ($articles as $article) {
          $article    = new Article($article['ID']);
          $title      = $article->getTitle();
          $res[]      = '<a href="'.$article->getLink().'" title="'.$title.'">'.shortenTitle($title, 25).'</a>';
      }

      $this->list = $res;
    }

    private function getTitle() {
      if ($this->type == self::TYPE_MOST) {
        return I18n::t('sidebar.module.most_read.title');

      } else {
        return I18n::t('sidebar.module.last.title');
      }
    }


    public static function html($n = sel::DEFAULT_LENGTH, $type = self::TYPE_LAST) {
      if (!is_int($n) && $n < 1) {
        $n = sel::DEFAULT_LENGTH;
      }

      if ($type !== self::TYPE_LAST &&
          $type !== self::TYPE_MOST) {
        $type = self::TYPE_LAST;
      }

      $archive = new self($n, $type);
      echo $archive->getHTML();
    }
  }