<?php

  class ApiCommentDelete implements ApiModule {

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
        Comment::delete($this->data['id']);

        echo 'success';

      } else {
        echo 'error';
      }
    }
  }

?>