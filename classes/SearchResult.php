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
      $title        = $this->article->getTitle();
      $marked_title = $this->search_marks ? $this->addSearchMarks($title) : $title;
      return Parser::parse($marked_title, Parser::TYPE_PREVIEW);
    }

    public function getMarkedContent() {
      $content        = $this->article->getContent();
      $marked_content = $this->search_marks ? $this->addSearchMarks($content) : $content;
      return Parser::parse($marked_content, Parser::TYPE_PREVIEW);
    }

    private function addSearchMarks($text) {
      $lower  = mb_strtolower($this->search_str, 'UTF-8');
      $offset = 0;
      $length = mb_strlen($lower);

      while ($offset !== false) {
        $offset = mb_strpos(mb_strtolower($text, 'UTF-8'), $lower, $offset);

        if($offset !== false && $offset <= mb_strlen($text) - $length) {
          $t1     = mb_substr($text, 0, $offset);
          $t2     = '[mark]';
          $t3     = mb_substr($text, $offset, $length);
          $t4     = '[/mark]';
          $t5     = mb_substr($text, $offset + $length);
          $text   = $t1.$t2.$t3.$t4.$t5;

          # add length of marks to offset
          $offset += 13 + $length;
        }
      }

      return $text;
    }
  }
?>
