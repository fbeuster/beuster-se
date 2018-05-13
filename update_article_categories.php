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


  # rename fields
  $sql = 'ALTER TABLE `newscatcross`
          CHANGE `NewsID` `article_id` SMALLINT( 6 ) NOT NULL AUTO_INCREMENT ,
          CHANGE `Cat` `category_id` SMALLINT( 6 ) NOT NULL;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not change fields in table newscatcross' . $line_end;

  } else {
    echo 'changed fields in table newscatcross' . $line_end;
  }

  # drop deprecated fields from newscatcross table
  $sql = 'ALTER TABLE `newscatcross`
          DROP `CatID`;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not drop fields from table newscatcross' . $line_end;

  } else {
    echo 'dropped fields from table newscatcross' . $line_end;
  }

  # rename table enwscatcross to article_categories
  $sql    = 'RENAME TABLE `'.DB_NAME.'`.`newscatcross` TO `'.DB_NAME.'`.`article_categories` ;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not rename table newscatcross';

  } else {
    echo 'renamed table newscatcross to article_categories' . $line_end;
  }

?>
