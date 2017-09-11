<?php

  class AttachmentCreatePage extends AbstractAdminPage {

    const MAX_LENGTH_LICENSE  = 64;
    const MAX_LENGTH_NAME     = 128;
    const MAX_LENGTH_VERSION  = 64;

    private $action       = 'new';
    private $errors       = array();
    private $form_action  = 'attachment-create';
    private $submit       = 'formaction';
    private $values       = array();

    public function __construct() {
      $this->handlePost();
      $this->load();
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD']) {
        $is_new_category  = false;
        $is_new_playlist  = false;

        $db       = Database::getDB();
        $user     = User::newFromCookie();

        $license  = trim($_POST['license']);
        $name     = trim($_POST['name']);
        $version  = trim($_POST['version']);

        # check if $_FILES is empty or not
        if (empty($_FILES)) {
          $has_uploads = false;
        } else if (is_array($_FILES['file']['error'])) {
          $has_uploads = $_FILES['file']['error'][0] == 0;

        } else {
          $has_uploads = $_FILES['file']['error'] == 0;
        }

        $this->values = array(
                          'license' => $license,
                          'name'    => $name,
                          'version' => $version);

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

        if (!$has_uploads) {
          # no file
          $this->errors['file'] = array(
            'message' => I18n::t('admin.attachment.error.no_file'),
            'value'   => '');
        }

        if (empty($this->errors)) {
          # upload files
          foreach ($_FILES['file']['name'] as $key => $value) {
            if (!File::isValidSize($_FILES['file']['size'][$key])) {
              # invlaid file size
              $this->errors[] = array(
                'message' => I18n::t( 'admin.attachment.error.invalid_file_size',
                                      $_FILES['file']['name'][$key]),
                'value'   => $_FILES['file']['name'][$key]);

            } else {
              $path = File::saveUploadedFile(
                              $_FILES['file']['name'][$key],
                              $_FILES['file']['tmp_name'][$key] );
              if ($path === false) {
                # saving error
                $this->errors[] = array(
                  'message' => I18n::t('admin.attachment.error.file_save_failure'),
                  'value'   => $_FILES['file']['name'][$key]);

              }
            }
          }

          if (empty($this->errors)) {
            # insert the attachment
            $fields = array('file_name', 'file_path',
                            'license', 'version', 'type');
            $values = array('ssssi', array(
                                      $name,
                                      $path,
                                      $license,
                                      $version,
                                      File::DEFAULT_TYPE));
            $id = $db->insert('attachments', $fields, $values);
          }
        }

        if (empty($this->errors)) {
          $this->showMessage( I18n::t('admin.attachment.editor.actions.new.success'),
                              'admin');
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.attachment.editor.actions.new.label'));
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
