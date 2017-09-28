<?php

  class StaticPageEditPage extends AbstractAdminPage {

    private $action       = 'edit';
    private $errors       = array();
    private $form_action  = 'static-page-edit';
    private $static_pages = array();
    private $submit       = 'formactionchange';
    private $values       = array();

    public function __construct() {
      $this->handlePost();
      $this->handleGet();
      $this->load();
    }

    private function handleGet() {
      if (  ( 'GET' == $_SERVER['REQUEST_METHOD'] &&
              isset($_GET['data']) ) ||
            ( 'POST' == $_SERVER['REQUEST_METHOD'] &&
              isset($_POST['formactionchoose']) ) ) {
        $db = Database::getDB();

        if (isset($_GET['data'])) {
          $url = trim($_GET['data']);

        } else if(isset($_POST['static_page'])) {
          $url = trim($_POST['static_page']);

        } else {
          $url = null;
        }

        if ($url == null || $url == '') {
          $this->errors['static_page'] = array(
            'message' => I18n::t('admin.static_page.editor.error.no_article_selected'),
            'value'   => $url);

        } else {
          $fields = array('title', 'content', 'feedback');
          $conds  = array('url = ?', 's', array($url));
          $res    = $db->select('static_pages', $fields, $conds);

          if (count($res) == 0) {
            $this->showMessage( I18n::t('admin.article.edit.not_found'),
                                'static-page-edit');

          } else {
            $this->values = array(
                              'content'       => $res[0]['content'],
                              'has_feedback'  => $res[0]['feedback'],
                              'title'         => $res[0]['title'],
                              'url'           => $url);
          }
        }
      }
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD'] &&
          isset($_POST['formactionchange'])) {
        $db           = Database::getDB();
        $content      = Parser::parse($_POST['content'],
                                      Parser::TYPE_NEW);
        $has_feedback = isset($_POST['has_feedback']);
        $title        = trim($_POST['title']);
        $url          = trim($_POST['url']);
        $old_url      = trim($_POST['old_url']);
        $errors       = array();
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

        if (StaticPage::exists($url) && $url !== $old_url) {
          $this->errors['url'] = array(
            'message' => I18n::t('admin.static_page.editor.errors.exists'),
            'value'   => $url);
        }

        if (!StaticPage::exists($old_url)) {
          $this->errors['url'] = array(
            'message' => I18n::t('admin.static_page.editor.errors.no_old_exists'),
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

        if (empty($errors)) {
          $db2 = $db->getCon();

          $sql = 'UPDATE
                    static_pages
                  SET
                    url = ?,
                    title = ?,
                    content = ?,
                    feedback = ?
                  WHERE
                    url LIKE ?';
          if (!$stmt = $db2->prepare($sql)) {return $db2->error;}
          $stmt->bind_param('sssis', $url, $title, $content, $has_feedback, $old_url);
          if(!$stmt->execute()) {return $stmt->error;}
          $stmt->close();

          $link     = '<br /><a href="/admin">'.
                      I18n::t('admin.back_link').'</a>';
          $message  = I18n::t('admin.static_page.editor.actions.edit.success').$link;
          $this->showMessage($message, 'admin');
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.static_page.editor.actions.edit.label'));

      $db     = Database::getDB();
      $fields = array('url', 'title');
      $pages  = $db->select('static_pages', $fields);

      if (count($pages)) {
        $this->static_pages = $pages;
      }
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
