<?php

  abstract class SidebarModule {

    private $classes;
    private $id;
    private $title;
    private $valid = false;

    public function __construct($config) {
      if (!$this->isValid($config)) {
        # TODO some error

      } else {
        $this->classes  = isset($config['classes']) ? $config['classes'] : '';
        $this->id       = isset($config['id']) ? $config['id'] : '';

        $this->title    = $config['title'];
      }
    }

    public function getHTML() {
      $id     = $this->id !== '' ? " id='$this->id'" : '';
      $class  = " class='module $this->classes'";

      $pre    = '<section'.$id.$class.">\n";
      $post   = '</section>'."\n";
      return $pre . $this->makeTitle() . $this->makeContent() . $post;
    }

    protected function isValid($config) {
      $requirements = isset($config['requirements']) ? $config['requirements'] : array();
      $requirements[] = 'title';

      foreach ($requirements as $requirement) {
        if (!isset($config[$requirement])) {
          return false;
        }
      }

      return true;
    }

    protected abstract function makeContent();

    protected function makeTitle() {
      return $this->title === null ? '' : '<h4>' . $this->title . '</h4>'."\n";
    }

    protected function wrapContent($content) {
      $pre  = '<div class="module_inside">'."\n";
      $post = '</div>'."\n";
      return $pre . $content . $post;
    }
  }

?>