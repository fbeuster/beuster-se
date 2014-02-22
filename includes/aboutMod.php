<?php
  $a = array();
  $a['data'] = array();
  if(!isset($_GET['aut'])) {
  } else {
    $a['filename'] = 'aboutMod.tpl';
    $uName = $db->real_escape_string(stripslashes(trim($_GET['aut'])));
    $uID = getUserIDbyName($db, $uName);
    if(!is_int($uID)) {
      $a['data']['err'] = $uName;
    } else {
      $uTxt = '';
      $sql = 'SELECT
                About
              FROM
                users
              WHERE
                ID = ?';
      $stmt = $db->prepare($sql);
      if (!$stmt) return $db->error;
      $stmt->bind_param('i', $uID);
      if (!$stmt->execute()) return $stmt->error;
      $stmt->bind_result($uTxt);
      if(!$stmt->fetch()) return $stmt-error;
      $stmt->close();
      $a['data']['about'] = array('id' => $uID,
                                  'txt' => str_replace('[contactmail]', '</p><address>'.str_replace('@', ' [at] ', getContactMail($db, $uID)).'</address><p>', changetext($uTxt, 'inhalt', $mob)),
                                  'ClearName' => getClearname($db, $uID),
                                  'Name' => $uName);
    }
  }
  return $a;
?>