<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    refreshCookies();

    $a['filename']  = 'snippetnew.php';
    $a['data']      = array();

    $err  = 0;
    $db   = Database::getDB();

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      $content  = Parser::parse($_POST['content'], Parser::TYPE_NEW);
      $name     = trim($_POST['name']);

      $eRet     = array(  'content' => $content,
                          'name'    => $name);

      if ('' == $name || '' == $content) {
        # empty name or content
        $err = 1;

      } else if (strlen($name) > 20) {
        # too long
        $err = 2;

      } else if (!preg_match('#^[A-Za-z0-9]*$#', $name)) {
        # invalid characters
        $err = 3;

      } else if (Snippet::exists($name)) {
        # already exists
        $err = 4;

      } else {
        $now    = date("Y-m-d H:i:s", time());
        $fields = array('name', 'content_de', 'content_en',
                        'created', 'edited');
        $values = array('sssss', array( $name, $content, $content,
                                        $now, $now));
        $res    = $db->insert('snippets', $fields, $values);
      }

      if ($err != 0) {
        $eRet['t']        = $err;
        $a['data']['fe']  = $eRet;

      } else {
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