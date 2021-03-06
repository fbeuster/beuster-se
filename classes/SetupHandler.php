<?php

class SetupHandler {

  private $config;
  private $messages = array();
  private $step;

  public function __construct() {
    $this->config = Config::getConfig();
    $this->step   = Setup::getSetup()->getStepName();
  }

  private function addMessage($message) {
    $this->messages[] = $message;
  }

  public function getMessages() {
    return $this->messages;
  }

  private function handleAdminUser() {
    $mysqli = Database::getDB()->getCon();
    $sql    = ' UPDATE users
                SET username = ?,
                    screen_name = ?,
                    mail = ?,
                    contact_mail = ?,
                    website = ?,
                    password_hash = ?
                WHERE id = ?';

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
      $this->addMessage(I18n::t('errors.mysql_error', $mysqli->error));

    } else {
      $password = hash('sha512', $_POST['admin_password']);
      $user_id  = 10001;

      $stmt->bind_param('ssssssi', $_POST['admin_username'],
                                  $_POST['admin_realname'],
                                  $_POST['admin_mail'],
                                  $_POST['admin_mail'],
                                  $_POST['admin_website'],
                                  $password,
                                  $user_id);

      if (!$stmt->execute()) {
        $this->addMessage(I18n::t('errors.execute_error'));

      } else {
        $stmt->close();
      }
    }
  }

  private function handleAdvanced() {
    if (isset($_POST['devServer'])) {
      $dev    = $_POST['devServerAddress'];
      $remote = $_POST['remoteServerAddress'];

      $dev    = preg_replace('#https?://#i', '', $dev);
      $remote = preg_replace('#https?://#i', '', $remote);

      $saved = $this->config->set('dev', 'dev_server_address', $dev);
      $saved = $saved && $this->config->set('site',
                                            'remote_server_address',
                                            $remote);

      if (!$saved) {
        $this->addMessage(
          I18n::t('setup.advanced.configuration_save_error'));
      }
    }
  }

  private function handleContent() {
    $base_path      = '../';
    $local_file     = 'user/local.php';
    $sql_file       = 'lixter_setup.sql';
    $from_existing  = $_POST['new_db'] == 'from_existing';

    if (file_exists($base_path . $local_file)) {
      $data = array(
                "define('DB_CHAR', '" . $_POST['db_char'] . "');\n"
              );
      $output = file_put_contents($base_path . $local_file, implode('', $data), FILE_APPEND);

      if ($output === false) {
        $this->addMessage(I18n::t('setup.content.file_write_error', $local_file));
      }

      if (  ( $from_existing && isset($_FILES['sql_file']) ) ||
            ( !$from_existing && file_exists($sql_file) ) ) {

        $mysqli = Database::getDB()->getCon();

        if ($from_existing) {
          $sql = file_get_contents($_FILES['sql_file']['tmp_name']);

        } else {
          $sql = file_get_contents($sql_file);
        }

        $result = $mysqli->multi_query($sql);

        if (!$result) {
          # todo improve error catching from multi query
          if ($from_existing) {
            $this->addMessage(I18n::t('setup.content.backup_loading_error'));

          } else {
            $this->addMessage(I18n::t('setup.content.db_loading_error'));
          }

        }

      } else {
        $this->addMessage(I18n::t('setup.content.file_missing', $sql_file));
      }

    } else {
      $this->addMessage(I18n::t('setup.content.file_missing', $local_file));
    }
  }

  private function handleCustom() {
    $base_path    = '../';
    $local_file   = 'user/local.php';

    $saved = $this->config->set('site', 'language', $_POST['language']);
    $saved = $saved && $this->config->set('site', 'theme',
                                          $_POST['theme']);

    if (!$saved) {
      $this->addMessage(
        I18n::t('setup.custom.configuration_save_error'));
    }

    if (file_exists($base_path . $local_file)) {
      $data = array(
                "date_default_timezone_set('" . $_POST['timezone'] . "');\n",
                "\$rssFeedPath = '" . $_POST['rss_path'] . "';\n"
              );
      $output = file_put_contents($base_path . $local_file, implode('', $data), FILE_APPEND);

      if ($output === false) {
        $this->addMessage(I18n::t('setup.custom.file_write_error', $local_file));
      }

    } else {
      $this->addMessage(I18n::t('setup.custom.file_missing', $local_file));
    }
  }

  private function handleDatabase() {
    $base_path  = '../';
    $local_file = 'user/local.php';

    if (!file_exists($base_path . $local_file)) {
      $data = array(
                "<?php\n",
                "define('DB_HOST', '" . $_POST['db_host'] . "');\n",
                "define('DB_NAME', '" . $_POST['db_name'] . "');\n",
                "define('DB_PASS', '" . $_POST['db_pass'] . "');\n",
                "define('DB_USER', '" . $_POST['db_user'] . "');\n"
              );

      $output = file_put_contents($base_path . $local_file, implode('', $data));

      if ($output === false) {
        $this->addMessage(I18n::t('setup.database.file_write_error', $local_file));
      }

    } else {
      $this->addMessage(I18n::t('setup.database.file_already_exists', $local_file));
    }
  }

  public function handleStep() {
    switch ($this->step) {
      case 'welcome'  : break;
      case 'finish'   : break;

      case 'database'   : $this->handleDatabase();  break;
      case 'content'    : $this->handleContent();   break;
      case 'admin_user' : $this->handleAdminUser(); break;
      case 'custom'     : $this->handleCustom();    break;
      case 'advanced'   : $this->handleAdvanced();  break;

      default : $this->handleInvalidStep(); break;
    }
  }

  public function handleInvalidStep() {
    $this->addMessage(I18n::t('setup.invalid_step'));
  }

  public function hasError() {
    return !empty($this->messages);
  }
}

?>