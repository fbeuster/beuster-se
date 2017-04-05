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
      $max_year = (int) date("Y");
      $min_year = 2010;

      for ($year = $max_year; $year >= $min_year; $year--) {

        if (articlesInDate($year) === 0) {
          continue;
        }

        $month_list = array();
        for ($month = 12; $month >= 1; $month--) {

          $numberMonth = articlesInDate($year, $month);
          if ($numberMonth === 0) {
            continue;
          }

          $month_str  = '<a href="/'.$year.'/'.$month.'">';
          $month_str  .= I18n::t('datetime.month.' . $month);
          $month_str  .= ' <span class="number" style="color: #999999;">('.$numberMonth.')</span>';
          $month_str  .= '</a>';
          $month_list[] = $month_str;
        }

        $year_title = '<span class="articleArchiveYear" style="cursor: pointer;">'.$year."</span>";

        $this->list[$year_title] = $month_list;
      }
    }

    public static function html() {
      $archive = new self();
      echo $archive->getHTML();
    }
  }