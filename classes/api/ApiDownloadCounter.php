<?php

  class ApiDownloadCounter implements ApiModule {

    private $data;
    private $db;

    public function __construct() {
    }

    public function init($data) {
      $this->data = $data;
      $this->db   = Database::getDB();
    }

    public function requiresAdmin() {
      return false;
    }

    public function run() {

      if (isset($_COOKIE['api_token']) &&
          ApiToken::isValid($_COOKIE['api_token'])) {

        if (isset($this->data['file'])) {
          $fields = array('id');
          $conds  = array('md5(id) = ?', 's',
                          array($this->data['file']));
          $res    = $this->db->select('attachments', $fields, $conds);

          if (count($res) == 1) {
            # increase download counter
            File::incrementDownloadCount($res[0]['id']);

            # delete token
            ApiToken::delete($_COOKIE['api_token']);

            echo 'success';

          } else {
            echo 'error';
          }
        } else {
          echo 'error';
        }
      } else {
        echo 'error';
      }
    }
  }

?>