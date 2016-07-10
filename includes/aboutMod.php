<?php
  $a = array();
  $a['data'] = array();
  $a['filename'] = 'static.php';

  if(!isset($_GET['aut'])) {
    $a['data'] = I18n::t('aboutMod.no_username');

  } else {
    $uName = stripslashes(trim($_GET['aut']));

    $db   = Database::getDB();
    $user = User::newFromName($uName);

    if(!$user->isLoaded() || !$user->isAdmin()) {
      $a['data'] = I18n::t('aboutMod.invalid_username', array($uName));

    } else {
      $image = '<img src="/images/mods/' . $user->getName() . '.jpg" class="about">';
      $a['data'] = $image . $user->buildInfo();
    }
  }
  return $a;
?>