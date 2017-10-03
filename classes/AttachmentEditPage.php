<?php

  class AttachmentEditPage extends AbstractAdminPage {

    const MAX_LENGTH_LICENSE  = 64;
    const MAX_LENGTH_NAME     = 128;
    const MAX_LENGTH_VERSION  = 64;

    private $action       = 'edit';
    private $errors       = array();
    private $form_action  = 'attachment-edit';
    private $submit       = 'formsubmit';
    private $values       = array();

    public function __construct() {
      $this->handlePost();
      $this->load();
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD'] &&
          isset($_POST['formsubmit'])) {
        $db       = Database::getDB();

        $id       = trim($_POST['attachment_id']);
        $license  = trim($_POST['license']);
        $name     = trim($_POST['name']);
        $version  = trim($_POST['version']);

        $this->values = array(
                          'license' => $license,
                          'name'    => $name,
                          'version' => $version);

        if ($id == null || $id == '' || $id == 0 || !is_numeric($id)) {
          $this->errors['attachment_id'] = array(
            'message' => I18n::t('admin.attachment.error.invalid_attachment_id'),
            'value'   => $id);

        }

        if ($license == '') {
          # empty license
          $this->errors['license'] = array(
            'message' => I18n::t('admin.attachment.error.empty_license'),
            'value'   => $license);

        } else if (mb_strlen($license, 'UTF-8') > self::MAX_LENGTH_LICENSE) {
          # license too long
          $this->errors['license'] = array(
            'message' => I18n::t('admin.attachment.error.license_too_long',
              array(self::MAX_LENGTH_LICENSE)),
            'value'   => $license);
        }

        if ($name == '') {
          # empty name
          $this->errors['name'] = array(
            'message' => I18n::t('admin.attachment.error.empty_name'),
            'value'   => $name);

        } else if (mb_strlen($name, 'UTF-8') > self::MAX_LENGTH_NAME) {
          # name too long
          $this->errors['name'] = array(
            'message' => I18n::t('admin.attachment.error.name_too_long',
              array(self::MAX_LENGTH_NAME)),
            'value'   => $name);
        }

        if ($version == '') {
          # empty version
          $this->errors['version'] = array(
            'message' => I18n::t('admin.attachment.error.empty_version'),
            'value'   => $version);

        } else if (mb_strlen($version, 'UTF-8') > self::MAX_LENGTH_VERSION) {
          # version too long
          $this->errors['version'] = array(
            'message' => I18n::t('admin.attachment.error.version_too_long',
              array(self::MAX_LENGTH_VERSION)),
            'value'   => $version);
        }

        if (empty($this->errors)) {
          # update the attachment
          $sql = "UPDATE
                    attachments
                  SET
                    file_name = ?,
                    version = ?,
                    license = ?
                  WHERE
                    id = ?";

          if (!$stmt = $db->getCon()->prepare($sql)) {
            return $db->getCon()->error;
          }

          $stmt->bind_param('sssi', $name, $version, $license, $id);

          if (!$stmt->execute()) {
            return $stmt->error;
          }

          $stmt->close();
        }

        if (empty($this->errors)) {
          $this->showMessage( I18n::t('admin.attachment.editor.actions.edit.success'),
                              'admin');
        }

      } else if ( ( 'GET' == $_SERVER['REQUEST_METHOD'] &&
                    isset($_GET['data']) ) ||
                  ( 'POST' == $_SERVER['REQUEST_METHOD'] &&
                    isset($_POST['formselect']) )) {

        if (isset($_GET['data'])) {
          $id = trim($_GET['data']);

        } else if(isset($_POST['attachment'])) {
          $id = trim($_POST['attachment']);

        } else {
          $id = null;
        }

        if ($id == null || $id == '' || $id == 0 || !is_numeric($id)) {
          $this->errors['attachment'] = array(
            'message' => I18n::t('admin.attachment.error.no_attachment_selected'),
            'value'   => $id);

        } else {

          $db     = Database::getDB();
          $fields = array('file_name', 'file_path',
                          'license', 'version');
          $conds  = array('id = ?', 'i', array($id));
          $res    = $db->select('attachments', $fields, $conds);

          $this->values = array(
                            'id'      => $id,
                            'license' => $res[0]['license'],
                            'name'    => $res[0]['file_name'],
                            'path'    => $res[0]['file_path'],
                            'version' => $res[0]['version']);
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.attachment.editor.actions.edit.label'));

      $db     = Database::getDB();
      $fields = array('id', 'file_name');
      $this->attachments = $db->select('attachments', $fields);
    }

    public function show() {
      if ($this->has_message) {
        include 'system/views/admin/static.php';

      } else {
        include 'system/views/admin/attachment_editor.php';
      }
    }
  }

?>
