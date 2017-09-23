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
      $counts     = '';
      $db         = Database::getDB();
      $last_year  = null;
      $lb         = Lixter::getLix()->getLinkBuilder();

      $fields     = array('COUNT(`ID`) AS `amount`',
                          'MONTH(`Datum`) AS `month`',
                          'YEAR(`Datum`) AS `year`');
      $group      = 'GROUP BY MONTH(`Datum`), YEAR(`Datum`) '.
                    'ORDER BY YEAR(`Datum`) DESC, MONTH(`Datum`) DESC';
      $months     = $db->select('news', $fields, null, $group);

      $month_list = array();
      foreach ($months as $key => $row) {
        if ($row['year'] !== $last_year) {
          if ($last_year !== null) {
            $year_title   = '<span class="articleArchiveYear" style="cursor: pointer;">';
            $year_title   .= $last_year;
            $year_title   .= '</span>';

            $this->list[$year_title] = $month_list;
          }

          $month_list = array();
          $last_year = $row['year'];
        }

        $month_str    = '<a href="'.
                        $lb->makeArchiveMonthLink($row['year'],
                                                  $row['month']).
                        '">';
        $month_str    .= I18n::t('datetime.month.' . $row['month']);
        $month_str    .= ' <span class="number" style="color: #999999;">';
        $month_str    .= '('.$row['amount'].')';
        $month_str    .= '</span>';
        $month_str    .= '</a>';
        $month_list[] = $month_str;
      }

      $year_title   = '<span class="articleArchiveYear" style="cursor: pointer;">';
      $year_title   .= $last_year;
      $year_title   .= '</span>';
      $this->list[$year_title] = $month_list;
    }

    public static function html() {
      $archive = new self();
      echo $archive->getHTML();
    }
  }