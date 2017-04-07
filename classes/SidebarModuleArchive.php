<?php

  class SidebarModuleArchive extends SidebarModuleList {

    private $list;

    public function __construct() {
      $this->list = array();

      $this->generateList();

      $config = array("title" => I18n::t('sidebar.module.archive.title'),
                      "classes" => "archive list",
                      "list" => $this->list);

      parent::__construct($config);
    }

    private function generateList() {
      $counts   = '';
      $db       = Database::getDB()->getCon();
      $max_year = (int) date("Y");
      $min_year = 2010;

      for ($year = $max_year; $year >= $min_year; $year--) {
        for ($month = 12; $month >= 1; $month--) {
          $counts     .= 'COUNT(case when YEAR(`Datum`) = ' . $year . ' and MONTH(`Datum`) = ' . $month . ' then ID end) AS `' . $month . '-' . $year .'`, ';
        }
      }

      $counts = substr($counts, 0, strlen($counts) - 2);
      $sql    = "SELECT $counts FROM news";

      # todo improve error handling, maybe just logging and leave method?
      if (!$result = $db->prepare($sql)) {
          $this->list['error'] = $db->error;
      }

      if (!$result->execute()) {
          $this->list['error'] = $result->error;
      }

      $rs   = array();
      $meta = $result->result_metadata();

      while ($f = $meta->fetch_field()) {
        $var      = $f->name;
        $$var     = null;
        $rs[$var] = &$$var;
      }

      call_user_func_array(array($result, 'bind_result'), $rs);

      if (!$result->fetch()) {
          $return = $result->error;
      }

      for ($year = $max_year; $year >= $min_year; $year--) {
        $month_list = array();

        for ($month = 12; $month >= 1; $month--) {

          $numberMonth = $rs[$month . '-' . $year];
          if ($numberMonth === 0) {
            continue;
          }

          $month_str    = '<a href="/'.$year.'/'.$month.'">';
          $month_str    .= I18n::t('datetime.month.' . $month);
          $month_str    .= ' <span class="number" style="color: #999999;">';
          $month_str    .= '('.$numberMonth.')';
          $month_str    .= '</span>';
          $month_str    .= '</a>';
          $month_list[] = $month_str;
        }

        if (empty($month_list)) {
          continue;
        }

        $year_title = '<span class="articleArchiveYear" style="cursor: pointer;">';
        $year_title .= $year;
        $year_title .= '</span>';

        $this->list[$year_title] = $month_list;
      }

      $result->close();
    }

    public static function html() {
      $archive = new self();
      echo $archive->getHTML();
    }
  }