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


  # rename news fields
  $sql = 'ALTER TABLE `news`
          CHANGE `ID` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
          CHANGE `Autor` `author` SMALLINT( 6 ) NOT NULL ,
          CHANGE `Titel` `title` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
          CHANGE `Inhalt` `content` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci ,
          CHANGE `Datum` `created` DATETIME NOT NULL ,
          CHANGE `enable` `public` TINYINT( 1 ) NOT NULL ,
          CHANGE `Hits` `hits` INT( 11 ) NOT NULL ,
          ADD `edited` DATETIME NOT NULL ;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not change fields in table news' . $line_end;

  } else {
    echo 'changed fields in table news' . $line_end;
  }

  # drop deprecated fields from news table
  $sql = 'ALTER TABLE `news`
          DROP `Status`;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not change fields for table news' . $line_end;

  } else {
    echo 'changed fields for table news' . $line_end;
  }

  # drop deprecated fields from news table
  $sql = 'UPDATE `news`
          SET `edited` = `created`;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not update edited date in table news' . $line_end;

  } else {
    echo 'updated edited date in table news' . $line_end;
  }

  # rename news table
  $sql    = 'RENAME TABLE `'.DB_NAME.'`.`news` TO `'.DB_NAME.'`.`articles` ;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not rename table';

  } else {
    echo 'renamed table news to articles' . $line_end;
  }

?>
