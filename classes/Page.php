<?php

abstract class Page {
  const ADMIN_PAGE    = 1;
  const ARTICLE_PAGE  = 2;
  const CATEGORY_PAGE = 3;
  const CONTENT_PAGE  = 4;
  const FEEDBACK_PAGE = 5;
  const GDPR_PAGE     = 6;
  const INDEX_PAGE    = 7;
  const SEARCH_PAGE   = 8;
  const STATIC_PAGE   = 9;

  protected $scripts;
  protected $styles;

  public function __construct() {
    $this->scripts = array();
  }

  protected function addScript($script) {
    $this->scripts[] = $script;
  }

  protected function addStyle($style) {
    $this->styles[] = $style;
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
      case Page::CATEGORY_PAGE:
      case Page::INDEX_PAGE;
      case Page::SEARCH_PAGE;
        return 'multipleArticles';
      case Page::ARTICLE_PAGE:
      case Page::STATIC_PAGE:
        return 'singleArticle';
      default:
        $page     = getPage();
        $content  = $this->getContent();
        if( isset($content['articles']) && count($content['articles']) > 0 &&
          !isset($content['admin_news']) && $page != 'single' && $page != 'page') {
          return 'multipleArticles';
        }
        return 'singleArticle';
    }
  }

  public abstract function getType();

  public abstract function getContent();

  public function getInfo() {
    return array();
  }

  public abstract function getParsedContent();

  public abstract function getTitle();

  public function hasScripts() {
    return !empty($this->scripts);
  }

  public function hasStyles() {
    return !empty($this->styles);
  }

  public function includeScripts() {
    if ($this->hasScripts()) {
      foreach ($this->scripts as $script) {
        echo '<script src="'.$script.'"></script>'."\n";
      }
    }
  }

  public function includeStyles() {
    if ($this->hasStyles()) {
      foreach ($this->styles as $style) {
        echo '<link href="'.$style.'" rel="stylesheet">'."\n";
      }
    }
  }
}

?>