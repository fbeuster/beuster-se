<?php

  class ApiSnippetDelete implements ApiModule {

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
      if (Snippet::exists($this->data['name'])) {
        $cond = array('name = ?', 's', array($this->data['name']));
        $res = $this->db->delete('snippets', $cond);

        if ($res) {
          echo 'success';

        } else {
          echo 'error';
        }

      } else {
        echo 'error';
      }
    }
  }

?>