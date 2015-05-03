<?php

  class SearchResult {
    
    private $article;
    private $search_str;
    private $search_marks;

    public function __construct($article_id, $search_str) {
      $this->article      = new Article($article_id);
      $this->search_str   = $search_str;
      $this->search_marks = Config::getConfig()->get('search.marks');
    }

    public function getArticle() {
      return $this->article;
    }

    public function getMarkedTitle() {
      $title = $this->article->getTitle();
      return $this->search_marks ? searchMark($title, $this->search_str, true) : $title;
    }

    public function getMarkedContent() {
      $content        = $this->article->getContent();
      $marked_content = $this->search_marks ? searchMark($content, $this->search_str, true) : $content;
      return changetext($marked_content, 'vorschau');
    }
  }
?>