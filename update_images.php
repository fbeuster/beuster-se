<?php

  include ('classes/Database.php');
  include ('classes/Image.php');
  include ('user/local.php');

  $db     = Database::getDB();
  $mysqli = $db->getCon();

  $sql = 'ALTER TABLE `pics`
          CHANGE `ID` `id` SMALLINT( 6 ) NOT NULL AUTO_INCREMENT ,
          CHANGE `NewsID` `article_id` SMALLINT( 6 ) NOT NULL ,
          CHANGE `Name` `caption` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
          CHANGE `Pfad` `file_name` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
          CHANGE `Thumb` `is_thumb` TINYINT( 1 ) NOT NULL,
          DROP `Titel` ,
          ADD `upload_date` DATETIME NOT NULL ;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not change table';
  }

  $sql    = 'RENAME TABLE `'.DB_NAME.'`.`pics` TO `'.DB_NAME.'`.`images` ;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'could not rename table';
  }

  $fields = array('images.id', 'images.file_name', 'news.Datum');
  $joins  = ' JOIN news ON news.ID = images.article_id';
  $images = $db->select('images', $fields, null, null, null, $joins);

  foreach ($images as $image) {
    if (!file_exists($image['file_name'])) {
      continue;
    }

    # get file extension
    $path_info  = pathinfo($image['file_name']);
    $extension  = '.' . $path_info['extension'];

    # get image type
    $image_size = getimagesize($image['file_name']);
    $image_type = $image_size[2];

    # make original image resource
    switch($image_type) {
      case "1":
        $original = imagecreatefromgif($image['file_name']);
        break;

      case "2":
        $original = imagecreatefromjpeg($image['file_name']);
        break;

      case "3":
        $original = imagecreatefrompng($image['file_name']);
        break;

      default:
        $original = imagecreatefromjpeg($image['file_name']);
        break;
    }

    $thumb_dimensions = array(  array(295, 190),
                                array(800, 450));

    foreach ($thumb_dimensions as $thumb_dimension) {
      # current thumb size
      $width  = $thumb_dimension[0];
      $height = $thumb_dimension[1];

      # get thumbnail dimensions
      $thumb_old  = $path_info['dirname'].'/th'.$path_info['filename'].'_'.$path_info['extension'].'.jpg';
      $thumb_size = getimagesize($thumb_old);
      $dimensions = '_' . $width . 'x' . $height;

      # make new names
      $new_name   = $path_info['filename'] . $extension;
      $thumb_name = $path_info['filename'] . $dimensions . $extension;
      $thumb_path = $path_info['dirname'] . '/' . $thumb_name;

      # make new thumbnail resource
      $thumb = imagecreatetruecolor($width, $height);
      imagecopyresampled( $thumb, $original,
                          0, 0, 0, 0,
                          $width, $height,
                          $image_size[0], $image_size[1]);

      # save new thumbail
      switch($image_type) {
        case "1":
          imagegif($thumb, $thumb_path);
          break;

        case "2":
          imagejpeg($thumb, $thumb_path, 100);
          break;

        case "3":
          imagepng($thumb, $thumb_path, 0);
          break;

        default:
          imagejpeg($thumb, $thumb_path, 100);
          break;
      }

      # remove old thumbnail
      unlink($thumb_old);

      # destroy resources
      imagedestroy($thumb);
    }

    # destroy resources
    imagedestroy($original);

    $sql = 'UPDATE images
            SET upload_date = ?,
                file_name = ?
            WHERE id = ?';

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
      echo $mysqli->error;

    } else {
      $stmt->bind_param('ssi',  $image['Datum'],
                                $new_name,
                                $image['id']);

      if (!$stmt->execute()) {
        echo $stmt->error;

      } else {
        $stmt->close();
      }
    }
  }

?>
