<?php

  class ApiArticleDelete implements ApiModule {

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
      if (Article::exists($this->data['id'])) {

        # unlink image files
        $fields = array('image_id');
        $conds  = array('article_id = ?', 'i', array($this->data['id']));
        $images = $this->db->select('article_images', $fields, $conds);

        foreach ($images as $image) {
            Image::delete($image['image_id']);
        }

        # remove newscatcross
        $conds  = array('NewsID = ?', 'i', array($this->data['id']));
        $res    = $this->db->delete('newscatcross', $conds);

        # remove tags from db
        $conds  = array('article_id = ?', 'i', array($this->data['id']));
        $res    = $this->db->delete('tags', $conds);

        # remove news
        $conds  = array('ID = ?', 'i', array($this->data['id']));
        $res    = $this->db->delete('news', $conds);

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