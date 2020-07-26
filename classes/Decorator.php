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

    public function getDecorationOptions() {
      preg_match($this->pattern, $this->content, $snippets);
      return isset($snippets[2]) ? $snippets[2] : '';
    }

    public function getDecorationValue() {
      preg_match($this->pattern, $this->content, $snippets);
      preg_match($this->value_pattern, $snippets[1], $values);
      return $values[1];
    }

    public function hasDecoration() {
      return preg_match($this->pattern, $this->content);
    }

    public function replaceDecoration($replace, $value = null) {
      if ($value == null) {
        $pattern = $this->pattern;

      } else {
        $value_pattern  = substr($this->value_pattern, 1, strlen($this->value_pattern) - 2);
        $pattern        = str_replace($value_pattern, $value, $this->pattern);
      }

      $this->content = preg_replace($pattern, $replace, $this->content, 1);
    }
  }

?>