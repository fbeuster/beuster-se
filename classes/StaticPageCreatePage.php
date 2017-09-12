<?php

  class StaticPageCreatePage extends AbstractAdminPage {

    private $action       = 'new';
    private $errors       = array();
    private $form_action  = 'static-page-create';
    private $submit       = 'formaction';
    private $values       = array();

    public function __construct() {
      $this->handlePost();
      $this->load();
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD']) {
        $db = Database::getDB();
        $content      = Parser::parse($_POST['content'], Parser::TYPE_NEW);
        $has_feedback = isset($_POST['has_feedback']);
        $title        = trim($_POST['title']);
        $url          = trim($_POST['url']);
        $this->values = array(  'content'       => $content,
                                'has_feedback'  => $has_feedback,
                                'title'         => $title,
                                'url'           => $url);

        if ($url == '') {
          $this->errors['url'] = array(
            'message' => I18n::t('admin.static_page.editor.errors.empty_url'),
            'value'   => $url);
        }

        if (strlen($url) > 50) {
          $this->errors['url'] = array(
            'message' => I18n::t('admin.static_page.editor.errors.long_url'),
            'value'   => $url);
        }

        if (!preg_match('#^[A-Za-z0-9]*$#', $url)) {
          $this->errors['url'] = array(
            'message' => I18n::t('admin.static_page.editor.errors.invalid_characters'),
            'value'   => $url);
        }

        if (StaticPage::exists($url)) {
          $this->errors['url'] = array(
            'message' => I18n::t('admin.static_page.editor.errors.exists'),
            'value'   => $url);
        }

        if ($title == '') {
          $this->errors['title'] = array(
            'message' => I18n::t('admin.static_page.editor.errors.empty_title'),
            'value'   => $title);
        }

        if (strlen($title) > 100) {
          $this->errors['title'] = array(
            'message' => I18n::t('admin.static_page.editor.errors.long_title'),
            'value'   => $title);
        }

        if ($content == '') {
          $this->errors['content'] = array(
            'message' => I18n::t('admin.static_page.editor.errors.empty_content'),
            'value'   => $content);
        }

        # TODO
        # url needs to be checked against admin pages

        if (empty($this->errors)) {
          $fields = array('url', 'title', 'content', 'feedback');
          $values = array('sssi', array( $url, $title, $content, $has_feedback ));
          $res    = $db->insert('static_pages', $fields, $values);

          if ($res !== null) {
            $link     = ' <br /><a href="/admin">'.
                        I18n::t('admin.back_link').'</a>';
            $message  = I18n::t('admin.static_page.editor.actions.new.success').$link;

            $this->showMessage($message, 'admin');
          }
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.static_page.editor.actions.new.label'));
    }

    public function show() {
      if ($this->has_message) {
        include 'system/views/admin/static.php';

      } else {
        include 'system/views/admin/static_page_editor.php';
      }
    }
  }

?>
