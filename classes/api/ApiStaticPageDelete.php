<?php

  class ApiStaticPageDelete implements ApiModule {

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
      if (StaticPage::exists($this->data['url'])) {

        # remove news
        $conds  = array('url LIKE ?', 's', array($this->data['url']));
        $res    = $this->db->delete('static_pages', $conds);

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