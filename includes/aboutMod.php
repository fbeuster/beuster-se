<?php
  $a = array();
  $a['data'] = array();
  if(!isset($_GET['aut'])) {
  } else {
    $a['filename'] = 'aboutMod.php';
    $uName = stripslashes(trim($_GET['aut']));

    $db = Database::getDB();
    $user = User::newFromName($uName);
    if(!$user->isLoaded() || !$user->isAdmin()) {
      $a['data']['err'] = $uName;
    } else {
      $a['data']['about'] = $user;
    }
  }
  return $a;
?>