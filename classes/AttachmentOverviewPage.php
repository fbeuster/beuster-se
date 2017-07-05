<?php

  class AttachmentOverviewPage extends AbstractAdminPage {

    private $attachments        = array();
    private $total_attachments  = 0;

    public function __construct() {
      $this->load();
    }

    private function load() {
      $this->setTitle(I18n::t('admin.attachment.overview.label'));

      $db = Database::getDB();

      $fields = array('id', 'file_name', 'file_path', 'downloads', 'version');
      $files  = $db->select('attachments', $fields);

      foreach ($files as $k => $attachment) {
        $files[$k] = array(
                        'id'        => $attachment['id'],
                        'file_name' => $attachment['file_name'],
                        'file_path' => $attachment['file_path'],
                        'downloads' => $attachment['downloads'],
                        'version'   => $attachment['version']);
        $this->total_attachments++;
      }

      $this->attachments = $files;
    }

    public function show() {
      include 'system/views/admin/attachment_overview.php';
    }
  }

?>
