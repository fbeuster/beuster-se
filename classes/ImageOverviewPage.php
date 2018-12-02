<?php

  class ImageOverviewPage extends AbstractAdminPage {

    private $images         = null;
    private $total_images   = 0;

    public function __construct() {
      $this->load();
    }

    private function load() {
      $this->setTitle(I18n::t('admin.image.overview.label'));

      $db = Database::getDB();

      # commen vars
      $fields       = array('id');
      $options      = 'ORDER BY upload_date DESC';
      $this->images = $db->select('images', $fields, null, $options);

      foreach ($this->images as $k => $image) {
        $this->images[$k] = new Image($image['id']);
        $this->total_images++;
      }
    }

    public function show() {
      include 'system/views/admin/image_overview.php';
    }
  }

?>
