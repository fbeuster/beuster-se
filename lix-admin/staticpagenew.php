<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    refreshCookies();

    $a['filename']  = 'static_page_editor.php';
    $a['data']      = array();
    $a['title']     = I18n::t('admin.static_page.new.label');

    $db = Database::getDB();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      $content      = Parser::parse($_POST['content'], Parser::TYPE_NEW);
      $has_feedback = isset($_POST['has_feedback']);
      $title        = trim($_POST['title']);
      $url          = trim($_POST['url']);
      $errors       = array();
      $values       = array(  'content'       => $content,
                              'has_feedback'  => $has_feedback,
                              'title'         => $title,
                              'url'           => $url);

      if ($url == '') {
        $errors['url'] = array(
          'message' => I18n::t('admin.static_page.new.errors.empty_url'),
          'value'   => $url);
      }

      if (strlen($url) > 50) {
        $errors['url'] = array(
          'message' => I18n::t('admin.static_page.new.errors.long_url'),
          'value'   => $url);
      }

      if (!preg_match('#^[A-Za-z0-9]*$#', $url)) {
        $errors['url'] = array(
          'message' => I18n::t('admin.static_page.new.errors.invalid_characters'),
          'value'   => $url);
      }

      if (StaticPage::exists($url)) {
        $errors['url'] = array(
          'message' => I18n::t('admin.static_page.new.errors.exists'),
          'value'   => $url);
      }

      if ($title == '') {
        $errors['title'] = array(
          'message' => I18n::t('admin.static_page.new.errors.empty_title'),
          'value'   => $title);
      }

      if (strlen($title) > 100) {
        $errors['title'] = array(
          'message' => I18n::t('admin.static_page.new.errors.long_title'),
          'value'   => $title);
      }

      if ($content == '') {
        $errors['content'] = array(
          'message' => I18n::t('admin.static_page.new.errors.empty_content'),
          'value'   => $content);
      }

      if (!empty($errors)) {
        $a['data']['errors'] = $errors;
        $a['data']['values'] = $values;

      } else {
        $fields = array('url', 'title', 'content', 'feedback');
        $values = array('sssi', array( $url, $title, $content, $has_feedback ));
        $res    = $db->insert('static_pages', $fields, $values);

        $link = ' <br /><a href="/admin">'.I18n::t('admin.back_link').'</a>';
        return showInfo(I18n::t('admin.static_page.new.success').$link, 'admin');
      }
    }

    $a['data']['action']        = 'new';
    $a['data']['form_action']   = 'staticpagenew';
    $a['data']['submit']        = 'formaction';

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>