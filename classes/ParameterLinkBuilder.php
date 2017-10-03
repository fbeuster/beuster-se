<?php

  class ParameterLinkBuilder extends LinkBuilder {

    public function __construct() {
    }

    public function makeAdminLink($page, $data = null) {
      if ($data === null) {
        $append = '';
      } else {
        $append = '&data='.$data;
      }
      return '/index.php?p='.$page.$append;
    }

    public function makeArchiveMonthLink($year, $month) {
      return '/index.php?y='.$year.'&m='.$month;
    }

    public function makeArchiveYearLink($year) {
      return '/index.php?y='.$year;
    }

    public function makeArticleLink($id, $category, $title) {
      return '/index.php?p=blog&n='.$id;
    }

    public function makeCategoryLink($category_name) {
      $category_name = $this->replaceStrokes($category_name);
      $category_name = mb_strtolower($category_name, 'UTF-8');

      return '/index.php?p='.$category_name;
    }

    public function makeOtherPageLink($page_name, $snippet = null) {
      $page_name = mb_strtolower($page_name, 'UTF-8');

      $link = '/index.php?p='.$page_name;

      if ($snippet !== null) {
        $link .= '&snip='.$snippet;
      }

      return $link;
    }

    public function makePageAppendix() {
      return '&page=';
    }

    public function makePageLink() {
      return '?page=';
    }

    public function makeSearchLink($search_term) {
      return '/index.php?p=search&s='.$search_term;
    }
  }

?>
