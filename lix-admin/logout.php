<?php
  $user = User::newFromCookie();

  if (!$user) {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }

  $ret          = array();
  $ret['data']  = array();

  setcookie('user_id', null, -1, '/');
  setcookie('password', null, -1, '/');

  unset($_COOKIE['user_id']);
  unset($_COOKIE['password']);

  return showInfo(I18n::t('logout.success'), 'blog');
?>
