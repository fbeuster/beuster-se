<?php

class SetupHandler {

  private $error = false;
  private $step;

  public function __construct() {
    $this->step = Setup::getSetup()->getStepName();
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
                SET Name = ?,
                    Clearname = ?,
                    Email = ?,
                    Contactmail = ?,
                    Website = ?,
                    Password = ?
                WHERE ID = ?';

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
      $this->error = true;

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
        $this->error = true;

      } else {
        $stmt->close();
      }
    }
  }

  private function handleAdvanced() {
    if (isset($_POST['devServer'])) {
      $config_file = '../user/config.ini';

      if (file_exists($config_file)) {
        $dev    = $_POST['devServerAddress'];
        $remote = $_POST['remoteServerAddress'];

        $dev    = preg_replace('#https?://#i', '', $dev);
        $remote = preg_replace('#https?://#i', '', $remote);

        $data = array(
                  "devServer = \"" . $dev . "\"\n",
                  "remote_address = \"" . $remote . "\"\n"
                );
        $output = file_put_contents($config_file, implode('', $data), FILE_APPEND);

        if ($output === false) {
          $this->error = true;
        }
      }
    }
  }

  private function handleContent() {
    $local_file = '../user/local.php';
    if (file_exists($local_file)) {
      $data = array(
                "define('DB_CHAR', '" . $_POST['db_char'] . "');\n"
              );
      $output = file_put_contents($local_file, implode('', $data), FILE_APPEND);

      if ($output === false) {
        $this->error = true;
      }

      $mysqli = Database::getDB()->getCon();
      $sql    = file_get_contents('lixter_setup.sql');
      $result = $mysqli->multi_query($sql);

      if (!$result) {
        $this->error = true;
      }

    } else {
      # todo handle missing file
    }
  }

  private function handleCustom() {
    $base_path    = '../';
    $config_file  = 'user/config.ini';
    $local_file   = 'user/local.php';

    if (!file_exists($base_path . $config_file)) {
      $data = array(
                "language = \"" . $_POST['language'] . "\"\n",
                "theme = \"" . $_POST['theme'] . "\"\n"
              );

      $output = file_put_contents($base_path . $config_file, implode('', $data));

      if ($output === false) {
        $this->addMessage(I18n::t('setup.custom.file_write_error', $config_file));
        $this->error = true;
      }
    } else {
      $this->addMessage(I18n::t('setup.custom.file_missing', $config_file));
      $this->error = true:
    }

    if (file_exists($base_path . $local_file)) {
      $data = array(
                "date_default_timezone_get('" . $_POST['timezone'] . "');\n",
                "\$rssFeedPath = '" . $_POST['rss_path'] . "';\n"
              );
      $output = file_put_contents($base_path . $local_file, implode('', $data), FILE_APPEND);

      if ($output === false) {
        $this->addMessage(I18n::t('setup.custom.file_write_error', $local_file));
        $this->error = true;
      }

    } else {
      $this->addMessage(I18n::t('setup.custom.file_missing', $local_file));
      $this->error = true;
    }
  }

  private function handleDatabase() {
    $local_file = '../user/local.php';
    if (!file_exists($local_file)) {
      $data = array(
                "<?php\n",
                "define('DB_HOST', '" . $_POST['db_host'] . "');\n",
                "define('DB_NAME', '" . $_POST['db_name'] . "');\n",
                "define('DB_PASS', '" . $_POST['db_pass'] . "');\n",
                "define('DB_USER', '" . $_POST['db_user'] . "');\n"
              );

      $output = file_put_contents($local_file, implode('', $data));

      if ($output === false) {
        $this->error = true;
      }
    } else {
      # todo handle existing file
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

  public function hasError() {
    return $this->error;
  }
}

?>