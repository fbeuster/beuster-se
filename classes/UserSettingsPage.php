<?php

  class UserSettingsPage extends AbstractAdminPage {

    private $errors           = array();
    private $has_email_errors = false;
    private $user;
    private $values           = array();

    public function __construct() {
      $this->user = User::newFromCookie();
      $this->handlePost();
      $this->load();
    }

    private function handleChangeEmail() {
      $email_errors = array();

      if (isset($_POST['change_email']) || isset($_POST['change_all'])) {

        $this->values = array('email_old' => trim($_POST['email_old']),
                              'email_new' => trim($_POST['email_new']));

        if ($this->values['email_old'] == '') {
          # empty old email
          $email_errors['email_old'] = array(
            'message' => I18n::t('user.settings.email.error.empty_old'),
            'value'   => $this->values['email_old']);

        } else if (!checkmail($this->values['email_old'])) {
          # invalid old email
          $email_errors['email_old'] = array(
            'message' => I18n::t('user.settings.email.error.invalid_old'),
            'value'   => $this->values['email_old']);
        }

        if ($this->values['email_new'] == '') {
          # empty new email
          $email_errors['email_new'] = array(
            'message' => I18n::t('user.settings.email.error.empty_new'),
            'value'   => $this->values['email_new']);

        } else if (!checkmail($this->values['email_new'])) {
          # invalid new email
          $email_errors['email_new'] = array(
            'message' => I18n::t('user.settings.email.error.invalid_new'),
            'value'   => $this->values['email_new']);
        }

        if (empty($email_errors)) {
          $db   = Database::getDB()->getCon();
          $sql  = ' UPDATE
                      users
                    SET
                      Email = ?
                    WHERE
                      ID = ?';
          $stmt = $db->prepare($sql);

          if (!$stmt) {
            return $db->error;
          }

          $user_id = $this->user->getId();
          $stmt->bind_param('si', $this->values['email_new'], $user_id);

          if (!$stmt->execute()) {
            return $stmt->error;
          }

          $stmt->close();
        }
      }

      return $email_errors;
    }

    private function handleChangePassword() {
      $password_errors = array();

      if (isset($_POST['change_password']) ||
          isset($_POST['change_all'])) {
        $password_old     = trim($_POST['password_old']);
        $password_new     = trim($_POST['password_new']);
        $password_repeat  = trim($_POST['password_repeat']);

        if ($password_old == '') {
          # empty old password
          $password_errors['password_old'] = array(
            'message' => I18n::t('user.settings.password.error.empty_old'),
            'value'   => '');
        }

        if ($password_new == '') {
          # empty new password
          $password_errors['password_new'] = array(
            'message' => I18n::t('user.settings.password.error.empty_new'),
            'value'   => '');
        }

        if ($password_repeat == '') {
          # empty repeat password
          $password_errors['password_repeat'] = array(
            'message' => I18n::t('user.settings.password.error.empty_repeat'),
            'value'   => '');
        }

        $password_old_hash = hash('sha512', $password_old);

        if (empty($password_errors) &&
            !$this->user->checkPassword($password_old_hash)) {
          # wrong old password
          $password_errors['password_old'] = array(
            'message' => I18n::t('user.settings.password.error.invalid_old'),
            'value'   => '');
        }

        if (empty($password_errors) &&
            $password_new !== $password_repeat) {
          # unequal passwords
          $password_errors['password_repeat'] = array(
            'message' => I18n::t('user.settings.password.error.unequal_password'),
            'value'   => '');
        }

        if (empty($password_errors)) {
          # all fine, update password
          $password_new_hash = hash('sha512', $password_new);
          $db   = Database::getDB()->getCon();
          $sql  = ' UPDATE
                      users
                    SET
                      Password = ?
                    WHERE
                      ID = ?';
          $stmt = $db->prepare($sql);

          if (!$stmt) {
            return $db->error;
          }

          $user_id = $this->user->getId();
          $stmt->bind_param('si', $password_new_hash, $user_id);

          if (!$stmt->execute()) {
            return $stmt->error;
          }

          $stmt->close();
          $this->user->refreshCookies($password_new_hash);
        }
      }

      return $password_errors;
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD']) {
        $this->errors = array_merge(
          $this->handleChangeEmail(),
          $this->handleChangePassword() );

        if (empty($this->errors)) {
          if (isset($_POST['change_all'])) {
            $this->showMessage( I18n::t('user.settings.all.success'),
                                'user-settings');
          }

          if (isset($_POST['change_password'])) {
            $this->showMessage( I18n::t('user.settings.password.success'),
                                'user-settings');
          }

          if (isset($_POST['change_email'])) {
            $this->showMessage( I18n::t('user.settings.email.success'),
                                'user-settings');
          }
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('user.settings.label'));
    }

    public function show() {
      if ($this->has_message) {
        include 'system/views/admin/static.php';

      } else {
        include 'system/views/admin/user_settings.php';
      }
    }
  }

?>
