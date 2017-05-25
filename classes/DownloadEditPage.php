<?php

  class DownloadEditPage extends AbstractAdminPage {

    private $action       = 'edit';
    private $form_action  = 'download-edit';
    private $submit       = 'action_change';

    public function __construct() {
      $this->handleGet();
      $this->handlePost();
      $this->load();
    }

    private function handleGet() {

    }

    private function handlePost() {

    }

    private function load() {
      $this->setTitle(I18n::t('admin.download.edit.label'));
    }

    public function show() {
      if ($this->has_message) {
        include 'system/vies/admin/static.php';

      } else {
        include 'system/views/admin/download_editor.php';
      }
    }
  }

?>
