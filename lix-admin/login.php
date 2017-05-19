<?php
  $user = User::newFromCookie();

  if ($user) {
    return showInfo(I18n::t('login.already_signed_in'), 'admin');
  }

  $db   = Database::getDB();
  $ret  = array();

  $ret['data']      = array();
  $ret['filename']  = 'login.php';
  $ret['title']     = I18n::t('login.label');

  if ('POST' == $_SERVER['REQUEST_METHOD']) {
    # set go back link
    $back = '<br /><a href="/login">'.
            I18n::t('login.try_again').'</a>';

    # check form completeness
    if (!isset( $_POST['user_name'],
                $_POST['password'],
                $_POST['formaction_login'])) {
      return  showInfo(I18n::t('login.incomplete_form').$back, 'login');
    }

    $user_name  = trim($_POST['user_name']);
    $password   = trim($_POST['password']);

    if ('' == $user_name OR '' == $password) {
      return  showInfo(I18n::t('login.incomplete_form').$back, 'login');
    }

    # check user name
    $fields = array('ID');
    $conds  = array('Name = ?', 's', array($user_name));
    $res    = $db->select('users', $fields, $conds);

    if (count($res) != 1) {
      return  showInfo(I18n::t('login.invalid_user').$back, 'login');
    }

    $user_id = $res[0]['ID'];

    # check password
    $password_hash = hash('sha512', $password);

    $conds  = array('ID = ? AND Password = ?', 'is',
                    array($user_id, $password_hash));
    $res    = $db->select('users', $fields, $conds);

    if (count($res) != 1) {
      return  showInfo(I18n::t('login.invalid_password').$back, 'login');
    }

    $user_id = $res[0]['ID'];

    # set cookies
    setcookie('user_id',  $user_id,       strtotime("+1 day"), '/');
    setcookie('password', $password_hash, strtotime("+1 day"), '/');

    $_COOKIE['user_id']   = $user_id;
    $_COOKIE['password']  = $password_hash;

    return showInfo(I18n::t('login.success'), 'admin');

  } else {
    return $ret;
  }
?>