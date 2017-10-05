<?php

  include ('classes/Database.php');
  include ('classes/FileLoader.php');
  include ('classes/Config.php');
  include ('user/local.php');

  # parameters

  $delete_configuration_files = false;
  $drop_configuration_table   = true;

  if (php_sapi_name() == 'cli') {
    $line_end = "\n";

  } else {
    $line_end = '<br>' . "\n";
  }


  # database connection

  $db     = Database::getDB();
  $mysqli = $db->getCon();


  # drop configuration table before creating

  if ($drop_configuration_table) {
    $sql = 'DROP TABLE IF EXISTS `configuration`;';
    $result = $mysqli->query($sql);

    if (!$result) {
      echo 'Error: Could not drop table `configuration`.' . $line_end;
      die('Error: ' . $mysqli->error . $line_end);

    } else {
      while ($mysqli->next_result()) {;}
      echo 'Success: Dropped table `configuration`.' . $line_end;
    }
  }


  # create configuration table

  $sql = 'CREATE TABLE `configuration` (
            `option_set` VARCHAR(128) NOT NULL,
            `option_name` VARCHAR(128) NOT NULL,
            `option_value` VARCHAR(128) NOT NULL,
            PRIMARY KEY (`option_set`(128), `option_name`(128))
          ) CHARSET=utf8 COLLATE utf8_unicode_ci;';
  $result = $mysqli->query($sql);

  if (!$result) {
    echo 'Error: Could not create table `configuration`.' . $line_end;
    die('Error: ' . $mysqli->error . $line_end);

  } else {
    echo 'Success: Created table `configuration`.' . $line_end;
  }


  # load configuration from file
  $system_config  = FileLoader::loadIni('system/config.ini');
  $user_config    = FileLoader::loadIni('user/config.ini');
  $config         = array_merge($system_config, $user_config);

  # store configuration to database
  $fields     = array('option_set', 'option_name', 'option_value');
  $value_rows = array();

  ### site configuration
  $site_set = 'site';

  $site_config_copy = array('url_schema', 'language', 'theme',
                            'category_page_length', 'amazon_tag',
                            'google_analytics');
  foreach ($site_config_copy as $option_name) {
    $value_rows[] = array($site_set, $option_name,
                          $config[$option_name]);
  }

  $value_rows[] = array($site_set, 'dev_server_address',
                        $config['devServer']);
  $value_rows[] = array($site_set, 'remote_server_address',
                        $config['remote_address']);
  $value_rows[] = array($site_set, 'mail', $config['server_mail']);
  $value_rows[] = array($site_set, 'name', $config['site_name']);
  $value_rows[] = array($site_set, 'title', $config['site_title']);

  ### search configuration
  $search_set   = 'search';
  $value_rows[] = array($search_set, 'marks',
                        $config['search.marks']);
  $value_rows[] = array($search_set, 'case_sensitive',
                        $config['search.case_sensitive']);

  $values = array('sss', $value_rows);
  $result = $db->insertMany('configuration', $fields, $values);

  if ($result === null) {
    echo 'Error: Could not copy configuration values to DB.' . $line_end;
    die('Error: ' . $mysqli->error . $line_end);

  } else {
    echo 'Success: Copied configuration values to DB.' . $line_end;
  }

  $config = Config::getConfig();

  echo '<pre>'; print_r($config); echo '</pre>';

  # delete configuration files
  if ($delete_configuration_files) {

  }

?>
