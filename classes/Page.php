<?php

abstract class Page {
  const STATIC_PAGE   = 1;
  const CONTENT_PAGE  = 2;
  const ADMIN_PAGE    = 3;

  public function __construct() {
  }

  public function addUriSnippets() {
    $appending = '';

    if (Utilities::hasUriSnippets()) {
      $snippet_name_delimiter = '-';
      $snippet_names = explode( $snippet_name_delimiter,
                                Utilities::getUriSnippets());

      foreach ($snippet_names as $snippet_name) {
        $snippet = new Snippet($snippet_name);

        if ($snippet->isLoaded()) {
          $appending .= $snippet->getContentParsed('de');
        }
      }
    }

    return $appending;
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

  public abstract function getParsedContent();

  public abstract function getTitle();
}

?>