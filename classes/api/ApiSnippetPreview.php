<?php

  class ApiSnippetPreview implements ApiModule {

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

      $snippet = new Snippet($this->data['name']);

      if ($snippet->isLoaded()) {
        echo $snippet->getContentParsed('de');
      }
    }
  }

?>