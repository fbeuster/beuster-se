<?php

  abstract class LinkBuilder {
    # schemas
    const CUSTOM_SCHEMA     = 0;
    const DEFAULT_SCHEMA    = 1;
    const PARAMETER_SCHEMA  = 2;

    public function __construct() {
    }

    public abstract function makeAdminLink($page, $data = null);

    public abstract function makeArchiveMonthLink($year, $month);

    public abstract function makeArchiveYearLink($year);

    public abstract function makeArticleLink($id, $category, $title);

    public abstract function makeCategoryLink($category_name);

    public abstract function makeOtherPageLink($page_name, $snippet = null);

    public abstract function makePageAppendix();

    public abstract function makePageLink();

    public abstract function makeSearchLink($search_term);

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

    protected function removeSpecialCharacters($string) {
      $removes = '#?|().,;:{}[]/%';

      for($i = 0; $i < strlen($removes); $i++) {
        $string = str_replace($removes[$i], '', $string);
      }

      $string = $this->replaceStrokes($string);

      return $this->replaceUmlaute($string);
    }
  }

?>
