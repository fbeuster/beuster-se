<?php

  include ('classes/Category.php');
  include ('classes/Database.php');
  include ('classes/Image.php');
  include ('user/local.php');

  $db     = Database::getDB();
  $mysqli = $db->getCon();

  #
  # line endings
  #

  if (php_sapi_name() == 'cli') {
    $line_end = "\n";

  } else {
    $line_end = '<br>' . "\n";
  }

  #
  # restore backup
  #

  $sql    = 'DROP TABLE IF EXISTS `'.DB_NAME.'`.`attachments`;';
  $result = $mysqli->query($sql);

  $sql    = 'DROP TABLE IF EXISTS `'.DB_NAME.'`.`article_attachments`;';
  $result = $mysqli->query($sql);

  $sql_file = 'backup_beusterse.sql';
  $sql      = file_get_contents($sql_file);
  $result   = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'Error: Could not restore database backup.' . $line_end;
    die('Error: ' . $mysqli->error . $line_end);

  } else {
    while ($mysqli->next_result()) {;}
    echo 'Success: Restored database backup.' . $line_end;
  }

  #
  # alter table `files`
  #

  $sql = 'ALTER TABLE `files`
          CHANGE `ID` `id` SMALLINT( 6 ) NOT NULL AUTO_INCREMENT ,
          CHANGE `Name` `file_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
          CHANGE `Path` `file_path` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
          ADD `license` VARCHAR( 10 ) NOT NULL,
          ADD `type` TINYINT(4) NOT NULL,
          ADD `version` VARCHAR( 10 ) NOT NULL ;';
  $result = $mysqli->query($sql);

  if (!$result) {
    echo 'Error: Could not alter table `files`.' . $line_end;
    die('Error: ' . $mysqli->error . $line_end);

  } else {
    echo 'Success: Altered table `files`.' . $line_end;
  }

  #
  # rename table `files` to `attachments`
  #

  $sql    = 'RENAME TABLE `'.DB_NAME.'`.`files` TO `'.DB_NAME.'`.`attachments` ;';
  $result = $mysqli->query($sql);

  if (!$result) {
    echo 'Error: Could not rename table `files` into `attachments`.' . $line_end;
    die('Error: ' . $mysqli->error . $line_end);

  } else {
    echo 'Success: Renamed table `files` into `attachments`.' . $line_end;
  }

  #
  # create `news_x_attachment` table
  #

  $sql    = 'CREATE TABLE `article_attachments` (
              `article_id` TINYINT(4) NOT NULL,
              `attachment_id` TINYINT(4) COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
  $result = $mysqli->query($sql);

  if (!$result) {
    echo 'Error: Could not create table `article_attachments`.' . $line_end;
    die('Error: ' . $mysqli->error . $line_end);

  } else {
    echo 'Success: Created table `article_attachments`.' . $line_end;
  }

  $sql    = ' ALTER TABLE `article_attachments`
              ADD PRIMARY KEY (`article_id`, `attachment_id`);';
  $result = $mysqli->query($sql);

  if (!$result) {
    echo 'Error: Could not add primary key to table `article_attachments`.' . $line_end;
    die('Error: ' . $mysqli->error . $line_end);

  } else {
    echo 'Success: Added primary key to table `article_attachments`.' . $line_end;
  }

  #
  # migrate file info from `downloads` into `attachments`
  #

  $fields     = array('Version', 'License', 'File', 'Log');
  $downloads  = $db->select('downloads', $fields);

  foreach ($downloads as $download) {
    if ($download['File'] > 0) {
      $sql = "UPDATE
                attachments
              SET
                license = ?,
                type = ?,
                version = ?
              WHERE
                ID = ?";

      if (!$stmt = $mysqli->prepare($sql)) {
        echo 'Error: Could not migrate file info from `downloads` into `attachments`.' . $line_end;
        die('Error: ' . $mysqli->error . $line_end);
      }

      $type = 0;

      $stmt->bind_param('sisi', $download['License'], $type, $download['Version'], $download['File']);

      if (!$stmt->execute()) {
        echo 'Error: Could not migrate file info from `downloads` into `attachments`.' . $line_end;
        die('Error: ' . $stmt->error . $line_end);
      }

      $stmt->close();
    }

    if ($download['Log'] > 0) {
      $sql = "UPDATE
                attachments
              SET
                license = ?,
                type = ?,
                version = ?
              WHERE
                ID = ?";

      if (!$stmt = $mysqli->prepare($sql)) {
        echo 'Error: Could not migrate log info from `downloads` into `attachments`.' . $line_end;
        die('Error: ' . $mysqli->error . $line_end);
      }

      $type = 1;

      $stmt->bind_param('sisi', $download['License'], $type, $download['Version'], $download['Log']);

      if (!$stmt->execute()) {
        echo 'Error: Could not migrate log info from `downloads` into `attachments`.' . $line_end;
        die('Error: ' . $stmt->error . $line_end);
      }

      $stmt->close();
    }
  }

  echo 'Success: Migrated file and log info from `downloads` into `attachments`.' . $line_end;

  #
  # migrate `downcats` into `categories`
  #

  $fields = array('ID');
  $conds  = array('Cat = ?', 's', array('Downloads'));
  $cat    = $db->select('newscat', $fields, $conds);

  if (!$cat) {
    $fields     = array('Cat', 'ParentID', 'Typ');
    $values     = array('sii', array('Downloads', 0, 0));
    $parent_id  = $db->insert('newscat', $fields, $values);

  } else {
    $parent_id  = $cat[0]['ID'];
  }

  $fields   = array('ID', 'Catname');
  $downcats = $db->select('downcats', $fields);

  foreach ($downcats as $downcat) {
    $fields = array('Cat', 'ParentID', 'Typ');
    $values = array('sii', array($downcat['Catname'], $parent_id, 2));
    $cat_id = $db->insert('newscat', $fields, $values);

    $sql = "UPDATE
              downloads
            INNER JOIN
              downcats
              ON downcats.ID = downloads.CatID
            SET
              downloads.CatID = ?
            WHERE
              downcats.ID = ?";

    if (!$stmt = $mysqli->prepare($sql)) {
      echo 'Error: Could not update category id in `downloads`.' . $line_end;
      die('Error: ' . $mysqli->error . $line_end);
    }

    $stmt->bind_param('ii', $cat_id, $downcat['ID']);

    if (!$stmt->execute()) {
      echo 'Error: Could update category id in `downloads`.' . $line_end;
      die('Error: ' . $stmt->error . $line_end);
    }

    $stmt->close();
  }

  echo 'Success: Migrated categories from `downcats` into `newscat`.' . $line_end;

  #
  # migrate `downloads` entries into `news`
  #

  $fields = array('ID');
  $conds  = array('Rights = ?', 's', array('admin'));
  $users  = $db->select('users', $fields, $conds);

  if (!$users) {
    die('Error: No admin user was found.' . $line_end);

  } else {
    $admin_id = $users[0]['ID'];
  }

  $fields     = array('Name', 'Description', 'File', 'Log', 'CatID');
  $downloads  = $db->select('downloads', $fields);

  foreach ($downloads as $download) {
    $fields     = array('Autor', 'Titel', 'Inhalt', 'Datum', 'enable');
    $values     = array('iss&i', array( $admin_id,
                                        $download['Name'],
                                        $download['Description'],
                                        'NOW()', 1));
    $article_id = $db->insert('news', $fields, $values);

    $axa    = array(array($article_id, $download['File']),
                    array($article_id, $download['Log']));
    $fields = array('article_id', 'attachment_id');
    $values = array('ii', $axa);
    $result = $db->insertMany('article_attachments', $fields, $values);

    $category     = new Category($download['CatID']);
    $category_id  = $category->getMaxArticleId() + 1;

    $fields = array('NewsID', 'Cat', 'CatID');
    $values = array('iii',  array($article_id, $download['CatID'],
                                  $category_id));
    $result = $db->insert('newscatcross', $fields, $values);
  }

  echo 'Success: Migrated entries from `downloads` into `news`.' . $line_end;

  #
  # drop `downcats`and `downloads`
  #

  $sql = 'DROP TABLE IF EXISTS `downcats`;
          DROP TABLE IF EXISTS `downloads`;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo 'Error: Could not drop tables `downcats` and `downloads`.' . $line_end;
    die('Error: ' . $mysqli->error . $line_end);

  } else {
    while ($mysqli->next_result()) {;}
    echo 'Success: Dropped tables `downcats` and `downloads`.' . $line_end;
  }

  echo 'Done.' . $line_end;

?>
