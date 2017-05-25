<?php

  class LoginPage extends AbstractAdminPage {

    public function __construct() {
      $this->handlePost();
      $this->load();
    }

    private function handlePost() {
      $db   = Database::getDB();

      if ('POST' == $_SERVER['REQUEST_METHOD']) {
        # set go back link
        $back = '<br /><a href="/login">'.
                I18n::t('login.try_again').'</a>';

        # check form completeness
        if (!isset( $_POST['user_name'],
                    $_POST['password'],
                    $_POST['formaction_login'])) {
          $this->showMessage(I18n::t('login.incomplete_form'), 'login');
          return;
        }

        $user_name  = trim($_POST['user_name']);
        $password   = trim($_POST['password']);

        if ('' == $user_name OR '' == $password) {
          $this->showMessage(I18n::t('login.incomplete_form'), 'login');
          return;
        }

        # check user name
        $fields = array('ID');
        $conds  = array('Name = ?', 's', array($user_name));
        $res    = $db->select('users', $fields, $conds);

        if (count($res) != 1) {
          $this->showMessage(I18n::t('login.invalid_user'), 'login');
          return;
        }

        $user_id = $res[0]['ID'];

        # check password
        $password_hash = hash('sha512', $password);

        $conds  = array('ID = ? AND Password = ?', 'is',
                        array($user_id, $password_hash));
        $res    = $db->select('users', $fields, $conds);

        if (count($res) != 1) {
          $this->showMessage(I18n::t('login.invalid_password'), 'login');
          return;
        }

        $user_id = $res[0]['ID'];

        # set cookies
        setcookie('user_id',  $user_id,       strtotime("+1 day"), '/');
        setcookie('password', $password_hash, strtotime("+1 day"), '/');

        $_COOKIE['user_id']   = $user_id;
        $_COOKIE['password']  = $password_hash;

        $this->showMessage(I18n::t('login.success'), 'admin');
      }
    }

    private function load() {
      $this->setTitle(I18n::t('login.label'));
    }

    public function show() {
      if ($this->has_message) {
        include 'system/views/admin/static.php';

      } else {
        include 'system/views/admin/login.php';
      }
    }
  }

?>
