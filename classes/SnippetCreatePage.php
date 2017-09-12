<?php

  class SnippetCreatePage extends AbstractAdminPage {

    private $action       = 'new';
    private $errors       = array();
    private $form_action  = 'snippet-create';
    private $submit       = 'formaction';
    private $values       = array();

    public function __construct() {
      $this->handlePost();
      $this->load();
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD']) {
        $db           = Database::getDB();
        $content      = Parser::parse($_POST['content'],
                                      Parser::TYPE_NEW);
        $name         = trim($_POST['name']);
        $this->values = array('content' => $content,
                              'name'    => $name);

        if ($name == '') {
          $this->errors['name'] = array(
            'message' => I18n::t('admin.snippet.editor.errors.empty_name'),
            'value'   => $name);
        }

        if (strlen($name) > 20) {
          $this->errors['name'] = array(
            'message' => I18n::t('admin.snippet.editor.errors.long_name'),
            'value'   => $name);
        }

        if (!preg_match('#^[A-Za-z0-9]*$#', $name)) {
          $this->errors['name'] = array(
            'message' => I18n::t('admin.snippet.editor.errors.invalid_characters'),
            'value'   => $name);
        }

        if (Snippet::exists($name)) {
          $this->errors['name'] = array(
            'message' => I18n::t('admin.snippet.editor.errors.exists'),
            'value'   => $name);
        }

        if ($content == '') {
          $this->errors['content'] = array(
            'message' => I18n::t('admin.snippet.editor.errors.empty_content'),
            'value'   => $content);
        }

        if (empty($this->errors)) {
          $now    = date("Y-m-d H:i:s", time());
          $fields = array('name', 'content_de', 'content_en',
                          'created', 'edited');
          $values = array('sssss', array( $name, $content, $content,
                                          $now, $now));
          $res    = $db->insert('snippets', $fields, $values);

          $link     = ' <br /><a href="/admin">'.
                      I18n::t('admin.back_link').'</a>';
          $message  = I18n::t('admin.snippet.editor.actions.new.success').$link;
          $this->showMessage($message, 'admin');
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.snippet.editor.actions.new.label'));
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
