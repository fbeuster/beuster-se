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


  # rename comment fields
  $sql = 'ALTER TABLE `newscat`
          CHANGE `ID` `id` SMALLINT( 6 ) NOT NULL AUTO_INCREMENT ,
          CHANGE `Cat` `name` VARCHAR( 64 ) CHARACTER SET utf8 NOT NULL,
          CHANGE `ParentID` `parent_category_id` SMALLINT( 6 ) NOT NULL ,
          CHANGE `Typ` `type` SMALLINT( 6 ) NOT NULL ,
          CHANGE `Beschreibung` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not change fields in table newscat' . $line_end;

  } else {
    echo 'changed fields in table newscat' . $line_end;
  }

  $sql    = 'RENAME TABLE `'.DB_NAME.'`.`newscat` TO `'.DB_NAME.'`.`categories` ;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not rename table';

  } else {
    echo 'renamed table newscat to categories' . $line_end;
  }

?>
