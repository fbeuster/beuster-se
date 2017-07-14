<?php

  class ApiAttachmentDelete implements ApiModule {

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
      if (File::exists($this->data['id'])) {

        # get file path
        $fields = array('file_path');
        $conds  = array('id = ?', 'i', array($this->data['id']));
        $res    = $this->db->select('attachments', $fields, $conds);

        # delete file
        $deleted = File::delete($res[0]['file_path']);

        if ($deleted) {
          # remove article_attachments
          $conds  = array('attachment_id = ?', 'i', array($this->data['id']));
          $res    = $this->db->delete('article_attachments', $conds);

          # remove attachment
          $conds  = array('id = ?', 'i', array($this->data['id']));
          $res    = $this->db->delete('attachments', $conds);

          if ($res) {
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