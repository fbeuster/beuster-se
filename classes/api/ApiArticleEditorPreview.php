<?php

  class ApiArticleEditorPreview implements ApiModule {

    private $data;
    private $db;

    public function __construct() {
    }

    public function init($data) {
      $this->data = $data;
      $this->db   = Database::getDB();
    }

    public function requiresAdmin() {
      return true;
    }

    public function run() {
      $preApp   = ('[yt]' == substr($this->data['content'],0,4)) ? '<p style="text-indent:0;">' : '<p>';
      $content  = $preApp.
                  Parser::parse($this->data['content'],
                                Parser::TYPE_CONTENT).
                  '</p>';
      $sd       = new SnippetDecorator($content);

      echo $sd->getContent();
    }
  }

?>