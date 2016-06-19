<?php

  abstract class Decorator {

    private $content;
    private $pattern;
    private $value_pattern;

    public function __construct($content, $pattern, $value_pattern) {
      $this->content = $content;
      $this->pattern = $pattern;
      $this->value_pattern = $value_pattern;
      $this->decorate();
    }

    public abstract function decorate();

    public function getContent() {
      return $this->content;
    }

    public function getDecorationValue() {
      preg_match($this->pattern, $this->content, $snippets);
      preg_match($this->value_pattern, $snippets[1], $values);
      return $values[1];
    }

    public function hasDecoration() {
      return preg_match($this->pattern, $this->content);
    }

    public function replaceDecoration($replace) {
      $this->content = preg_replace($this->pattern, $replace, $this->content);
    }
  }

?>