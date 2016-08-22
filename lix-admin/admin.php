<?php
  $a = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    refreshCookies();
    $a['filename'] = 'admin.php';
    $a['data'] = array();
    return $a;

  } else if ($user) {
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $a['filename'] = 'login.php';
    $a['data'] = array();
    return $a;
  }
?>