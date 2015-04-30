<?php

abstract class Page {
  const STATIC_PAGE  = 1;
  const CONTENT_PAGE = 2;

  public function __construct() {
  }

  public function getPageClass() {
    switch($this->getType()) {
      case Page::STATIC_PAGE:
        return 'singleArticle';
      default:
        $page     = getPage();
        $content  = $this->getContent();
        if( isset($content['articles']) && count($content['articles']) > 0 &&
          !isset($content['admin_news']) && $page != 'single' && $page != 'page' ||
          $page == 'portfolio') {
          return 'multipleArticles';
        }
        return 'singleArticle';
    }
  }

  public abstract function getType();

  public abstract function getContent();
}

?>