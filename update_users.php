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


  # rename user fields
  $sql = 'ALTER TABLE `users`
          CHANGE `ID` `id` SMALLINT( 6 ) NOT NULL AUTO_INCREMENT ,
          CHANGE `Name` `username` VARCHAR( 64 ) CHARACTER SET utf8 NOT NULL,
          CHANGE `Password` `password_hash` VARCHAR( 128 ) CHARACTER SET utf8 NOT NULL,
          CHANGE `Rights` `rights` VARCHAR( 20 ) CHARACTER SET utf8 NOT NULL ,
          CHANGE `Email` `mail` VARCHAR( 128 ) CHARACTER SET utf8 NOT NULL ,
          CHANGE `regDate` `registred` DATETIME NOT NULL ,
          CHANGE `Contactmail` `contact_mail` VARCHAR( 128 ) CHARACTER SET utf8 NOT NULL ,
          CHANGE `Clearname` `screen_name` VARCHAR( 128 ) CHARACTER SET utf8 NOT NULL,
          CHANGE `About` `description` TEXT CHARACTER SET utf8 NOT NULL,
          CHANGE `Website` `website` VARCHAR( 128 ) CHARACTER SET utf8 NOT NULL,
          ADD `profile_image` TEXT CHARACTER SET utf8 NOT NULL,
          DROP `cmtNotify`;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not change fields in table users' . $line_end;

  } else {
    echo 'changed fields in table users' . $line_end;
  }


  # add notification fields
  $sql = 'ALTER TABLE `comments`
          ADD `notifications` TINYINT( 1 ) DEFAULT 1 NOT NULL;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not change fields in table comments' . $line_end;

  } else {
    echo 'changed fields in table comments' . $line_end;
  }

?>
