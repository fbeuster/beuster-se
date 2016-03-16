<?php
  $user = User::newFromCookie();
  if (!$user)
    return NOT_LOGGED_IN;

  $ret = array();
  $ret['data'] = array();
  setcookie('UserID', null, -1, '/');
  setcookie('Password', null, -1, '/');
  unset($_COOKIE['UserID']);
  unset($_COOKIE['Password']);
  return showInfo('Sie sind nun ausgeloggt.', 'blog');
?>