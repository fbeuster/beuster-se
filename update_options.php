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
  $sql = 'ALTER TABLE `configuration`
          CHANGE `option_value` `option_value` TEXT NOT NULL;';
  $result = $mysqli->multi_query($sql);

  if (!$result) {
    echo '<pre>'; print_r($mysqli); echo '</pre>';
    echo 'could not change fields in table configuration' . $line_end;

  } else {
    echo 'changed fields in table configuration' . $line_end;
  }

  $fields = array('option_set', 'option_name', 'option_value');
  $values = array('sss', array('ext', 'google_adsense_ad', ''));
  $id     = $db->insert('configuration', $fields, $values);

  if (!$id) {
    echo 'could not insert option' . $line_end;

  } else {
    echo 'added option' . $line_end;
  }

?>
