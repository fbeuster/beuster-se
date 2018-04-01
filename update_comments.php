<?php

  include ('classes/Database.php');
  include ('user/local.php');

  # database options
  $db     = Database::getDB();
  $mysqli = $db->getCon();

  # line endings
  if (php_sapi_name() == 'cli') {
    $line_end = "\n";

  } else {
    $line_end = '<br>' . "\n";
  }


  # rename comment fields
  $sql = 'ALTER TABLE `kommentare`
          CHANGE `ID` `id` SMALLINT( 6 ) NOT NULL AUTO_INCREMENT ,
          CHANGE `UID` `user_id` SMALLINT( 6 ) NOT NULL ,
          CHANGE `NewsID` `article_id` SMALLINT( 6 ) NOT NULL ,
          CHANGE `ParentID` `parent_comment_id` SMALLINT( 6 ) NOT NULL ,
          CHANGE `Inhalt` `content` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
          CHANGE `Datum` `date` DATETIME NOT NULL,
          CHANGE `Frei` `enabled` TINYINT( 1 ) NOT NULL;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not change fields in table kommentare' . $line_end;

  } else {
    echo 'changed fields in table kommentare' . $line_end;
  }

  $sql    = 'RENAME TABLE `'.DB_NAME.'`.`kommentare` TO `'.DB_NAME.'`.`comments` ;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not rename table';

  } else {
    echo 'renamed table kommentare to comments' . $line_end;
  }

?>
