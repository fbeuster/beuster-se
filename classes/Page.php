<?php

class Page {
  const STATIC_PAGE = 1;

  public function __construct() {
  }

  public function getPageType() {
    /* this will change over time and use the page type */
    return 'singleArticle'; // error page
  }

  public abstract function getContent();
}

?>