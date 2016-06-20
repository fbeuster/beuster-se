<?php

  class SnippetDecorator extends Decorator {

    public function __construct($content) {
      parent::__construct($content, '#\[snip ([A-Za-z0-9]*)\]#', '#([A-Za-z0-9]*)#');
    }

    public function decorate() {
      while($this->hasDecoration()) {
        $name = $this->getDecorationValue();

        $this->replaceDecoration($this->getSnippet($name));
      }
    }

    private function getSnippet($name) {
      $snippet = new Snippet($name);

      if ($snippet->isLoaded()) {
        return $snippet->getContentParsed('de');

      } else {
        return '';
      }
    }
  }

?>