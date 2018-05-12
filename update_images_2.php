<?php

  include_once ('classes/Database.php');
  include_once ('user/local.php');

  $db     = Database::getDB();
  $mysqli = $db->getCon();

  # line endings
  if (php_sapi_name() == 'cli') {
    $line_end = "\n";

  } else {
    $line_end = '<br>' . "\n";
  }

  # create article_images table
  $sql = 'CREATE TABLE `article_images`(
              PRIMARY KEY (`article_id`, `image_id` )
          )
          SELECT
            `news`.`id` as `article_id`,
            `images`.`id` as `image_id`,
            `images`.`is_thumb` as `is_thumbnail`
          FROM
            `news`
          JOIN
            `images`
          ON
            `images`.`article_id` = `news`.`id`;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not create table article_images' . $line_end;

  } else {
    echo 'created table article_images' . $line_end;
  }

  # drop deprecated fields from images table
  $sql = 'ALTER TABLE `images`
          DROP `is_thumb`,
          DROP `article_id`;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not change fields for table images' . $line_end;

  } else {
    echo 'changed fields for table images' . $line_end;
  }

?>
