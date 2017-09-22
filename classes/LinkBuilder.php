<?php

  class LinkBuilder {
    # article schemas
    const DEFAULT_ARTICLE_SCHEMA    = '/#id#/#category#/#title#';
    const PARAMETER_ARTICLE_SCHEMA  = '/index.php?p=blog&n=#id#';

    # category schemas
    const DEFAULT_CATEGORY_SCHEMA = '/#name#';
    const PARAMETER_CATEGORY_SCHEMA = '/index.php?p=#name#';

    public function __construct() {
      # TODO
      # getting current schema from database
    }

    public function makeArticleLink($id, $category, $title) {
      $link = self::DEFAULT_ARTICLE_SCHEMA;

      $link = str_replace('#id#',       $id,        $link);
      $link = str_replace('#category#', $category,  $link);
      $link = str_replace('#title#',
                          $this->removeSpecialCharacters($title),
                          $link);
      return $link;
    }

    public function makeCategoryLink($category_name) {
      $category_name = $this->replaceStrokes($category_name);
      $category_name = mb_strtolower($category_name, 'UTF-8');

      $link = self::DEFAULT_CATEGORY_SCHEMA;
      $link = str_replace('#name#', $category_name,  $link);

      return $link;
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
  }

?>
