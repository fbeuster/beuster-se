<?php

  class LinkBuilder {
    # schemas
    const CUSTOM_SCHEMA     = 0;
    const DEFAULT_SCHEMA    = 1;
    const PARAMETER_SCHEMA  = 2;

    # archive schemas
    const DEFAULT_YEAR_SCHEMA    = '/#year#';
    const PARAMETER_YEAR_SCHEMA  = '/index.php?y=#year#';

    const DEFAULT_MONTH_SCHEMA    = '/#year#/#month#';
    const PARAMETER_MONTH_SCHEMA  = '/index.php?y=#year#&m=#month#';

    # article schemas
    const DEFAULT_ARTICLE_SCHEMA    = '/#id#/#category#/#title#';
    const PARAMETER_ARTICLE_SCHEMA  = '/index.php?p=blog&n=#id#';

    # category schemas
    const DEFAULT_CATEGORY_SCHEMA = '/#name#';
    const PARAMETER_CATEGORY_SCHEMA = '/index.php?p=#name#';

    # other page schemas
    const DEFAULT_OTHER_PAGE_SCHEMA = '/#page#';
    const PARAMETER_OTHER_PAGE_SCHEMA = 'index.php?p=#page#';

    # paging schemas
    const DEFAULT_PAGING_SCHEMA = '/page';
    const PARAMETER_PAGING_SCHEMA = 'page=';

    # search schemas
    const DEFAULT_SEARCH_SCHEMA = '/search/#term#';
    const PARAMETER_SEARCH_SCHEMA = '/index.php?p=search&s=#term#';

    private $article_link;
    private $category_link;
    private $month_link;
    private $other_link;
    private $search_link;
    private $selected_schema;
    private $year_schema;

    public function __construct() {
      # TODO
      # getting current schema from database

      $this->setSchema();
    }

    public function makeArchiveMonthLink($year, $month) {
      $link = str_replace('#year#',  $year,   $this->month_link);
      $link = str_replace('#month#', $month,  $link);

      return $link;
    }

    public function makeArchiveYearLink($year) {
      return str_replace('#year#',  $year,   $this->year_link);
    }

    public function makeArticleLink($id, $category, $title) {
      $link = str_replace('#id#',       $id,        $this->article_link);
      $link = str_replace('#category#', $category,  $link);
      $link = str_replace('#title#',
                          $this->removeSpecialCharacters($title),
                          $link);
      return $link;
    }

    public function makeCategoryLink($category_name) {
      $category_name = $this->replaceStrokes($category_name);
      $category_name = mb_strtolower($category_name, 'UTF-8');

      $link = str_replace('#name#', $category_name, $this->category_link);

      return $link;
    }

    public function makeOtherPageLink($page_name) {
      $page_name = mb_strtolower($page_name, 'UTF-8');

      $link = str_replace('#page#', $page_name, $this->other_link);

      return $link;
    }

    public function makePageAppendix() {
      return $this->makePageParameter('&');
    }

    public function makePageLink() {
      return $this->makePageParameter('?');
    }

    private function makePageParameter($prepend) {
      switch ($this->selected_schema) {
        case self::CUSTOM_SCHEMA :
          return '';

        case self::PARAMETER_SCHEMA :
          return $prepend.self::PARAMETER_PAGING_SCHEMA;

        case self::DEFAULT_SCHEMA :
        default :
          return self::DEFAULT_PAGING_SCHEMA;
      }
    }

    public function makeSearchLink($search_term) {
      return str_replace('#term#', $search_term,  $this->search_link);
    }

    public static function replaceStrokes($string) {
      $strokes = array(' ', '---', '--');

      foreach($strokes as $char) {
        $string = str_replace($char, '-', $string);
      }

      return $string;
    }

    public static function replaceUmlaute($string) {
      $string = str_replace('ä', 'ae', $string);
      $string = str_replace('ö', 'oe', $string);
      $string = str_replace('ü', 'ue', $string);
      $string = str_replace('Ä', 'Ae', $string);
      $string = str_replace('Ö', 'Oe', $string);
      $string = str_replace('Ü', 'Ue', $string);
      $string = str_replace('ß', 'ss', $string);

      return $string;
    }

    public function removeSpecialCharacters($string) {
      $removes = '#?|().,;:{}[]/%';

      for($i = 0; $i < strlen($removes); $i++) {
        $string = str_replace($removes[$i], '', $string);
      }

      $string = $this->replaceStrokes($string);

      return $this->replaceUmlaute($string);
    }

    private function setSchema() {
      $this->selected_schema = self::DEFAULT_SCHEMA;

      switch ($this->selected_schema) {
        case self::CUSTOM_SCHEMA :
          break;

        case self::PARAMETER_SCHEMA :
          $this->article_link   = self::PARAMETER_ARTICLE_SCHEMA;
          $this->category_link  = self::PARAMETER_CATEGORY_SCHEMA;
          $this->month_link     = self::PARAMETER_MONTH_SCHEMA;
          $this->other_link     = self::PARAMETER_OTHER_PAGE_SCHEMA;
          $this->search_link    = self::PARAMETER_SEARCH_SCHEMA;
          $this->year_link      = self::PARAMETER_YEAR_SCHEMA;
          break;

        case self::DEFAULT_SCHEMA :
        default :
          $this->article_link   = self::DEFAULT_ARTICLE_SCHEMA;
          $this->category_link  = self::DEFAULT_CATEGORY_SCHEMA;
          $this->month_link     = self::DEFAULT_MONTH_SCHEMA;
          $this->other_link     = self::DEFAULT_OTHER_PAGE_SCHEMA;
          $this->search_link    = self::DEFAULT_SEARCH_SCHEMA;
          $this->year_link      = self::DEFAULT_YEAR_SCHEMA;
          break;
      }
    }
  }

?>
