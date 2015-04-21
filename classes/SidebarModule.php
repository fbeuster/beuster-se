<?php

  class SidebarModule {

    private $classes;
    private $content;
    private $title;
    private $id;

    public function __construct($title, $content, $classes = '', $id = '') {
      $this->content  = $content;
      $this->classes  = $classes;
      $this->title    = $title;
      $this->id       = $id;
    }

    public function getModuleHTML() {
      $pre  = '<section class="module '.$this->classes.'" id="'.$this->id.'">'."\n";
      $post = '</section>'."\n";
      return $pre . $this->makeTitle() . $this->makeContent() . $post;
    }

    private function makeTitle() {
      return $this->title === null ? '' : '<h4>' . $this->title . '</h4>'."\n";
    }

    private function makeContent() {
      $pre  = '<div class="module_inside">'."\n";
      $post = '</div>'."\n";
      return $pre . $this->content . $post;
    }
  }

?>