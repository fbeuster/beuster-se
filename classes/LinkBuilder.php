<?php

  class LinkBuilder {
    #article schemas
    const DEFAULT_ARTICLE_SCHEMA    = '/#id#/#category#/#title#';
    const PARAMETER_ARTICLE_SCHEMA  = 'index.php?p=blog&n=#id#';

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
      $strokes = array(' ', '---', '--');

      for($i = 0; $i < strlen($removes); $i++) {
        $string = str_replace($removes[$i], '', $string);
      }

      foreach($strokes as $char) {
        $string = str_replace($char, '-', $string);
      }
      return $this->replaceUmlaute($string);
    }
  }

?>
