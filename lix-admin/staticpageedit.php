<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    $user->refreshCookies();

    $a['filename']  = 'static_page_editor.php';
    $a['data']      = array();
    $a['title']     = I18n::t('admin.static_page.edit.label');

    $db = Database::getDB();

    if ('POST' == $_SERVER['REQUEST_METHOD'] &&
        isset($_POST['formactionchange'])) {
      $content      = Parser::parse($_POST['content'], Parser::TYPE_NEW);
      $has_feedback = isset($_POST['has_feedback']);
      $title        = trim($_POST['title']);
      $url          = trim($_POST['url']);
      $old_url      = trim($_POST['old_url']);
      $errors       = array();
      $values       = array(  'content'       => $content,
                              'has_feedback'  => $has_feedback,
                              'title'         => $title,
                              'url'           => $url);

      if ($url == '') {
        $errors['url'] = array(
          'message' => I18n::t('admin.static_page.edit.errors.empty_url'),
          'value'   => $url);
      }

      if (strlen($url) > 50) {
        $errors['url'] = array(
          'message' => I18n::t('admin.static_page.edit.errors.long_url'),
          'value'   => $url);
      }

      if (!preg_match('#^[A-Za-z0-9]*$#', $url)) {
        $errors['url'] = array(
          'message' => I18n::t('admin.static_page.edit.errors.invalid_characters'),
          'value'   => $url);
      }

      if (StaticPage::exists($url) && $url !== $old_url) {
        $errors['url'] = array(
          'message' => I18n::t('admin.static_page.edit.errors.exists'),
          'value'   => $url);
      }

      if (!StaticPage::exists($old_url)) {
        $errors['url'] = array(
          'message' => I18n::t('admin.static_page.edit.errors.no_old_exists'),
          'value'   => $url);
      }

      if ($title == '') {
        $errors['title'] = array(
          'message' => I18n::t('admin.static_page.edit.errors.empty_title'),
          'value'   => $title);
      }

      if (strlen($title) > 100) {
        $errors['title'] = array(
          'message' => I18n::t('admin.static_page.edit.errors.long_title'),
          'value'   => $title);
      }

      if ($content == '') {
        $errors['content'] = array(
          'message' => I18n::t('admin.static_page.edit.errors.empty_content'),
          'value'   => $content);
      }

      if (!empty($errors)) {
        $a['data']['errors'] = $errors;
        $a['data']['values'] = $values;

      } else {
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
        if(!$stmt = $db2->prepare($sql)) {return $db2->error;}
        $stmt->bind_param('sssis', $url, $title, $content, $has_feedback, $old_url);
        if(!$stmt->execute()) {return $stmt->error;}
        $stmt->close();

        $link = '<br /><a href="/admin">'.I18n::t('admin.back_link').'</a>';
        return showInfo(I18n::t('admin.static_page.edit.success').$link, 'admin');
      }

    } else if ( ('GET' == $_SERVER['REQUEST_METHOD'] && isset($_GET['static_page'])) ||
                ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['formactionchoose']))) {

      if (isset($_GET['static_page'])) {
        $url = trim($_GET['static_page']);

      } else if(isset($_POST['static_page'])) {
        $url = trim($_POST['static_page']);

      } else {
        $url = null;
      }

      if ($url == null || $url == '') {
        $errors['static_page'] = array(
          'message' => I18n::t('admin.static_page.edit.error.no_article_selected'),
          'value'   => $url);

        $a['data']['errors'] = $errors;

      } else {
        $fields = array('title', 'content', 'feedback');
        $conds  = array('url = ?', 's', array($url));
        $res    = $db->select('static_pages', $fields, $conds);

        if (count($res) == 0) {
          return showInfo(I18n::t('admin.article.edit.not_found'), 'newsedit');

        } else {
          $a['data']['values'] = array(
                                  'content'       => $res[0]['content'],
                                  'has_feedback'  => $res[0]['feedback'],
                                  'title'         =>$res[0]['title'],
                                  'url'           => $url);
        }
      }
    }

    $fields = array('url', 'title');
    $res    = $db->select('static_pages', $fields);

    $static_pages = array();

    foreach ($res as $result) {
      $static_pages[] = array(  'title' => $result['title'],
                                'url'   => $result['url']);
    }

    $a['data']['static_pages'] = $static_pages;

    $a['data']['action']        = 'edit';
    $a['data']['form_action']   = 'staticpageedit';
    $a['data']['submit']        = 'formactionchange';

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }

?>