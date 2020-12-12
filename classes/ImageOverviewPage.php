<?php

  class ImageOverviewPage extends AbstractAdminPage {

    private $errors         = array();
    private $images         = null;
    private $total_images   = 0;
    private $values         = array();

    public function __construct() {
      $this->handlePost();
      $this->load();
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD'] &&
          isset($_POST['formsubmit'])) {

        $img_id = $_POST['img_id'];
        $img_caption = $_POST['img_caption'];

        if (!Image::exists($img_id)) {
          $this->errors['img_id'] = array(
            'message' => I18n::t('admin.image.overview.error.not_found'));
        }

        if (trim($img_caption) === '') {
          $this->errors['img_caption'] = array(
            'message' => I18n::t('admin.image.overview.error.empty_caption'));
        }

        if (!empty($this->errors)) {
          $this->values['img_id'] = $img_id;
          $this->values['img_caption'] = $img_caption;

        } else {
          # update caption
          $dbCon = Database::getDB()->getCon();
          $sql = "UPDATE
                    images
                  SET
                    caption = ?
                  WHERE
                    id = ?";

          if (!$stmt = $dbCon->prepare($sql)) {
            return $dbCon->error;
          }

          $stmt->bind_param('si', $img_caption, $img_id);

          if (!$stmt->execute()) {
            return $stmt->error;
          }

          $stmt->close();
        }
      }
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
