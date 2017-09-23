<?php

  class DefaultLinkBuilder extends LinkBuilder {

    public function __construct() {
    }

    public function makeArchiveMonthLink($year, $month) {
      return '/'.$year.'/'.$month;
    }

    public function makeArchiveYearLink($year) {
      return '/'.$year;
    }

    public function makeArticleLink($id, $category, $title) {
      $category = $this->replaceStrokes($category);
      $category = mb_strtolower($category, 'UTF-8');

      return  '/'.$id.
              '/'.$category.
              '/'.$this->removeSpecialCharacters($title);
    }

    public function makeCategoryLink($category_name) {
      $category_name = $this->replaceStrokes($category_name);
      $category_name = mb_strtolower($category_name, 'UTF-8');

      return '/'.$category_name;
    }

    public function makeOtherPageLink($page_name, $snippet = null) {
      $page_name = mb_strtolower($page_name, 'UTF-8');

      $link = '/'.$page_name;

      if ($snippet !== null) {
        $link .= '/s/'.$snippet;
      }

      return $link;
    }

    public function makePageAppendix() {
      return '/page';
    }

    public function makePageLink() {
      return '/page';
    }

    public function makeSearchLink($search_term) {
      return '/search/'.$search_term;
    }
  }

?>
