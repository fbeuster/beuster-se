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


  # rename tag fields
  $sql = 'ALTER TABLE `playlist`
          CHANGE `ID` `id` SMALLINT( 6 ) NOT NULL AUTO_INCREMENT ,
          CHANGE `ytID` `playlist_id` VARCHAR( 64 ) CHARACTER SET utf8 NOT NULL,
          CHANGE `catID` `category_id` SMALLINT( 6 ) NOT NULL COLLATE utf8_unicode_ci NOT NULL;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not change fields in table playlist' . $line_end;

  } else {
    echo 'changed fields in table playlist' . $line_end;
  }

?>
