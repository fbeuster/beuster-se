<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    $user->refreshCookies();

    $a['filename']  = 'snippetnew.php';
    $a['data']      = array();
    $a['title']     = I18n::t('admin.snippet.new.label');

    $db = Database::getDB();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      $content  = Parser::parse($_POST['content'], Parser::TYPE_NEW);
      $name     = trim($_POST['name']);
      $errors   = array();
      $values   = array(  'content' => $content,
                          'name'    => $name);

      if ($name == '') {
        $errors['name'] = array(
          'message' => I18n::t('admin.snippet.new.errors.empty_name'),
          'value'   => $name);
      }

      if (strlen($name) > 20) {
        $errors['name'] = array(
          'message' => I18n::t('admin.snippet.new.errors.long_name'),
          'value'   => $name);
      }

      if (!preg_match('#^[A-Za-z0-9]*$#', $name)) {
        $errors['name'] = array(
          'message' => I18n::t('admin.snippet.new.errors.invalid_characters'),
          'value'   => $name);
      }

      if (Snippet::exists($name)) {
        $errors['name'] = array(
          'message' => I18n::t('admin.snippet.new.errors.exists'),
          'value'   => $name);
      }

      if ($content == '') {
        $errors['content'] = array(
          'message' => I18n::t('admin.snippet.new.errors.empty_content'),
          'value'   => $content);
      }

      if (!empty($errors)) {
        $a['data']['errors'] = $errors;
        $a['data']['values'] = $values;

      } else {
        $now    = date("Y-m-d H:i:s", time());
        $fields = array('name', 'content_de', 'content_en',
                        'created', 'edited');
        $values = array('sssss', array( $name, $content, $content,
                                        $now, $now));
        $res    = $db->insert('snippets', $fields, $values);

        $link = ' <br /><a href="/admin">'.I18n::t('admin.back_link').'</a>';
        return showInfo(I18n::t('admin.snippet.new.success').$link, 'admin');
      }
    }

    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }
?>