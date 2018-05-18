<?php

  include_once ('classes/Database.php');
  include_once ('user/local.php');

  # database options
  $db     = Database::getDB();
  $mysqli = $db->getCon();

  # line endings
  if (php_sapi_name() == 'cli') {
    $line_end = "\n";

  } else {
    $line_end = '<br>' . "\n";
  }


  # add token field
  $sql = 'ALTER TABLE `users`
          CHANGE `registred` `registered` DATETIME NOT NULL,
          ADD `token` VARCHAR(128) NOT NULL;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not add field to table users' . $line_end;

  } else {
    echo 'added field to table users' . $line_end;
  }

  # adding tokens
  $fields = array('id');
  $res    = $db->select('users', $fields);

  if (count($res)) {
    foreach ($res as $user) {
      do {
        $token  = hash('sha256', microtime() + random_int(0, 1000));
        $conds  = array('token = ?', 's', array($token));
        $unique = $db->select('users', $fields, $conds);
      } while (count($unique) > 0);

      $sql = 'UPDATE
                users
              SET
                token = ?
              WHERE
                id = ?';
      $stmt = $mysqli->prepare($sql);

      if (!$stmt) {
        return $mysqli->error;
      }

      $stmt->bind_param('si', $token, $user['id']);
      if (!$stmt->execute()) {
        return $stmt->error;
      }

      $stmt->close();
    }
  }

  # make tokens unique
  $sql = 'ALTER TABLE `users`
          ADD UNIQUE `token` (`token`(128));';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not make tokens unique' . $line_end;

  } else {
    echo 'made tokens unique' . $line_end;
  }

?>
