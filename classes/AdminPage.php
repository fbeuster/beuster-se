<?php

  class AdminPage extends AbstractAdminPage {

    public function __construct() {
      $this->load();
    }

    private function load() {
      $this->setTitle(I18n::t('admin.label'));
    }

    public function show() {
      include 'system/views/admin/admin.php';
    }
  }

?>
