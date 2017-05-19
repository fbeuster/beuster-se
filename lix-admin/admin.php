<?php
  $a = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    $user->refreshCookies();
    $a['filename'] = 'admin.php';
    $a['data'] = array();
    $a['title'] = I18n::t('admin.label');
    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $a['filename'] = 'login.php';
    $a['data'] = array();
    $a['title'] = I18n::t('login.label');
    return $a;
  }
?>