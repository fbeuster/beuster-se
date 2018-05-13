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

        # remove article_categories
        $conds  = array('article_id = ?', 'i', array($this->data['id']));
        $res    = $this->db->delete('article_categories', $conds);

        # remove tags from db
        $conds  = array('article_id = ?', 'i', array($this->data['id']));
        $res    = $this->db->delete('tags', $conds);

        # remove articles
        $conds  = array('id = ?', 'i', array($this->data['id']));
        $res    = $this->db->delete('articles', $conds);

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