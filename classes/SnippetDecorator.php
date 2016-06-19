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
      $db = Database::getDB();

      $fields = array('content_de');
      $conds  = array('name = ?', 's', array($name));
      $res    = $db->select('snippets', $fields, $conds);

      if(count($res) != 1)
        return '';

      $content = $res[0]['content_de'];

      return Parser::parse($content, Parser::TYPE_CONTENT);
    }
  }

?>