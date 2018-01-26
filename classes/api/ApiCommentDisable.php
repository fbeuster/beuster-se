<?php

  class ApiCommentDisable implements ApiModule {

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
      if (Comment::exists($this->data['id'])) {
        Comment::disable($this->data['id']);

        echo 'success';

      } else {
        echo 'error';
      }
    }
  }

?>