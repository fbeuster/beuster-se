<?php

class Page {
  const PAGE_TYPE_ERROR = 1;

  public function __construct() {
  }

  public function getPageType() {
    /* this will change over time and use the page type */
    return 'singleArticle'; // error page
  }
}

?>