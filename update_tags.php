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
  $sql = 'ALTER TABLE `tags`
          CHANGE `ID` `id` SMALLINT( 6 ) NOT NULL AUTO_INCREMENT ,
          CHANGE `news_id` `article_id` SMALLINT( 6 ) NOT NULL ,
          CHANGE `tag` `tag` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not change fields in table tags' . $line_end;

  } else {
    echo 'changed fields in table tags' . $line_end;
  }

?>
