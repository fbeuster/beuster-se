<?php

  class LogoutPage extends AbstractAdminPage {

    public function __construct() {
      $this->load();
    }

    private function load() {
      setcookie('user_id', null, -1, '/');
      setcookie('password', null, -1, '/');

      unset($_COOKIE['user_id']);
      unset($_COOKIE['password']);

      $this->showMessage(I18n::t('logout.success'), 'blog');
    }

    public function show() {
      include 'system/views/admin/static.php';
    }
  }

?>
