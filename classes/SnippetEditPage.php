<?php

  class SnippetEditPage extends AbstractAdminPage {

    private $action       = 'edit';
    private $errors       = array();
    private $form_action  = 'snippet-edit';
    private $snippets     = array();
    private $submit       = 'formactionchange';
    private $values       = array();

    public function __construct() {
      $this->handlePost();
      $this->handleGet();
      $this->load();
    }

    private function handleGet() {
      if ('GET' == $_SERVER['REQUEST_METHOD']) {
        if (isset($_GET['snip'])) {
          $db     = Database::getDB();
          $name   = trim($_GET['snip']);
          $fields = array('name', 'content_de');
          $conds  = array('name = ?', 's', array($name));
          $res    = $db->select('snippets', $fields, $conds);

          if (count($res) > 0) {
            $this->values = array(
                              'name'    => $name,
                              'content' => $res[0]['content_de']);
          }
        }
      }
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD']) {
        if (isset($_POST['formactionchange'])) {
          /*** hier ändern ***/
          $db           = Database::getDB();
          $content      = Parser::parse($_POST['content'],
                                        Parser::TYPE_NEW);
          $name         = trim($_POST['name']);
          $old_name     = trim($_POST['old_name']);
          $this->values = array('content' => $content,
                                'name'    => $name);

          if ($name == '') {
            $this->errors['name'] = array(
              'message' => I18n::t('admin.snippet.edit.errors.empty_name'),
              'value'   => $name);
          }

          if (strlen($name) > 20) {
            $this->errors['name'] = array(
              'message' => I18n::t('admin.snippet.edit.errors.long_name'),
              'value'   => $name);
          }

          if (!preg_match('#^[A-Za-z0-9]*$#', $name)) {
            $this->errors['name'] = array(
              'message' => I18n::t('admin.snippet.edit.errors.invalid_characters'),
              'value'   => $name);
          }

          if (Snippet::exists($name) && $name !== $old_name) {
            $this->errors['name'] = array(
              'message' => I18n::t('admin.snippet.edit.errors.exists'),
              'value'   => $name);
          }

          if (!Snippet::exists($old_name)) {
            $this->errors['name'] = array(
              'message' => I18n::t('admin.snippet.edit.errors.no_old_exists'),
              'value'   => $name);
          }

          if ($content == '') {
            $this->errors['content'] = array(
              'message' => I18n::t('admin.snippet.edit.errors.empty_content'),
              'value'   => $content);
          }

          if (empty($this->errors)) {
            $db2 = $db->getCon();
            $now = date("Y-m-d H:i:s", time());

            $sql = 'UPDATE
                      snippets
                    SET
                      name = ?,
                      content_de = ?,
                      content_en = ?,
                      edited = ?
                    WHERE
                      name LIKE ?';
            $stmt = $db2->prepare($sql);

            if (!$stmt) {
              return $db2->error;
            }

            $stmt->bind_param('sssss',  $name, $content, $content,
                                        $now, $old_name);
            if (!$stmt->execute()) {
              return $stmt->error;
            }

            $stmt->close();


            $link     = '<br /><a href="/admin">'.
                        I18n::t('admin.back_link').'</a>';
            $message  = I18n::t('admin.snippet.edit.success').$link;
            $this->showMessage($message, 'admin');
          }

        } else if (isset($_POST['formactionchoose'])) {
          $db     = Database::getDB();
          $name   = trim($_POST['snippetname']);
          $fields = array('name', 'content_de');
          $conds  = array('name = ?', 's', array($name));
          $res    = $db->select('snippets', $fields, $conds);

          if (count($res) > 0) {
            $this->values = array(
                              'name'    => $name,
                              'content' => $res[0]['content_de']);
          }
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.snippet.edit.label'));

      $db     = Database::getDB();
      $fields = array('name');
      $res    = $db->select('snippets', $fields);

      foreach ($res as $result) {
        $this->snippets[] = $result['name'];
      }
    }

    public function show() {
      if ($this->has_message) {
        include 'system/views/admin/static.php';

      } else {
        include 'system/views/admin/snippet_editor.php';
      }
    }
  }

?>
