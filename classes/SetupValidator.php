<?php

class SetupValidator extends AbstractValidator {

  private $isValid = false;
  private $step;
  private $step_list = array( 'welcome', 'database', 'content',
                              'admin_user', 'custom', 'advanced',
                              'finish' );
  private $values;

  public function __construct() {
    $this->step = Setup::getSetup()->getStepName();
  }

  public function getCurrentStep() {
    return $this->step;
  }

  public function getNextStep() {
    $index  = array_search( $this->step, $this->step_list );
    $size   = sizeof( $this->step_list );
    $lookup = 0;

    if ($index !== false)  {
      if ($index < $size - 1) {
        $lookup = $index + 1;

      } else {
        $lookup = $index;
      }
    }

    return $this->step_list[ $lookup ];
  }

  public function isValid() {
    return $this->validateStep();
  }

  private function isMissing($post_field) {
    return !isset( $_POST[$post_field] ) || trim( $_POST[$post_field] ) == '';
  }

  public function readyForAdminUser() {
    $db       = Database::getDB();
    $hasUsers = $db->tableExists('users');

    return $this->readyForContent() && $hasUsers;
  }

  public function readyForAdvanced() {
    $content    = $this->readyForContent();
    $admin_user = $this->readyForAdminUser();
    $custom     = $this->readyForCustom();
    $config     = Config::getConfig('../');

    return  $content && $admin_user &&
            $custom && $config->get('theme') !== null;
  }

  public function readyForContent() {
    return  defined('DB_HOST') && defined('DB_NAME') &&
            defined('DB_PASS') && defined('DB_USER');
  }

  public function readyForCustom() {
    $content    = $this->readyForContent();
    $admin_user = $this->readyForAdminUser();

    $db     = Database::getDB();
    $result = $db->select('users',
                          array('ID'),
                          array('ID = ? AND Password = ?',
                                'is',
                                array(10001, 'empty')));

    return $content && $admin_user && empty($result);
  }

  private function validateAdminUser() {
    if (!$this->readyForAdminUser()) {
      $this->addMessage(I18n::t('setup.admin_user.not_ready'));

    } else {
      $this->values = array('admin_username'  => $_POST['admin_username'],
                            'admin_realname'  => $_POST['admin_realname'],
                            'admin_mail'      => $_POST['admin_mail'],
                            'admin_website'   => $_POST['admin_website']);

      if ($this->isMissing('admin_username')) {
        $this->addError('admin_username', 'setup.admin_user.missing_username');
      }

      if ($this->isMissing('admin_realname')) {
        $this->addError('admin_realname', 'setup.admin_user.missing_realname');
      }

      if ($this->isMissing('admin_password')) {
        $this->addError('admin_password', 'setup.admin_user.missing_password');

      } else {
        if ($this->isMissing('admin_password_2')) {
          $this->addError('admin_password_2', 'setup.admin_user.missing_password_2');
        }

        if ($_POST['admin_password'] !== $_POST['admin_password_2']) {
          $this->addError('admin_password_2', 'setup.admin_user.invalid_password_2');
        }
      }

      if ($this->isMissing('admin_mail')) {
        $this->addError('admin_mail', 'setup.admin_user.missing_mail');

      } else {
        if (!checkMail($_POST['admin_mail'])) {
          $this->addError('admin_mail', 'setup.admin_user.invalid_mail');
        }
      }
    }
  }

  private function validateAdvanced() {
    if (!$this->readyForAdvanced()) {
      $this->addMessage(I18n::t('setup.advanced.not_ready'));

    } else {
      if (isset($_POST['devServer'])) {
        if ($this->isMissing('devServerAddress')) {
          $this->addError('devServerAddress', 'setup.advanced.missing_dev_server_address');

        } else {
          $dev = $_POST['devServerAddress'];

          if (!preg_match('#https?://#i', $dev)) {
            $dev = 'http://' . $dev;
          }

          if (!isValidUserUrl($dev)) {
            $this->addError('devServerAddress', 'setup.advanced.invalid_dev_server_address');
          }
        }

        if ($this->isMissing('remoteServerAddress')) {
          $this->addError('remoteServerAddress', 'setup.advanced.missing_remote_server_address');

        } else {
          $remote = $_POST['remoteServerAddress'];

          if (!preg_match('#https?://#i', $remote)) {
            $remote = 'http://' . $remote;
          }

          if (!isValidUserUrl($remote)) {
            $this->addError('remoteServerAddress', 'setup.advanced.invalid_remote_server_address');
          }
        }
      }
    }
  }

  private function validateContent() {
    if (!$this->readyForContent()) {
      $this->addMessage(I18n::t('setup.content.not_ready'));

    } else {
      if ($this->isMissing('db_char')) {
        $this->addError('db_char', 'setup.content.missing_db_char');

      } else {
        if ( !in_array($_POST['db_char'], SetupHelper::getCharsets()) ) {
          $this->addError('db_char', 'setup.content.invalid_db_char');
        }
      }

      if (!isset($_POST['new_db'])) {
        $this->addError('new_db', 'setup.content.missing_new_db');

      } else {
        switch ($_POST['new_db']) {
          case 'new_db' :

            if (!file_exists('lixter_setup.sql')) {
              $this->addError('new_db', 'setup.content.missing_new_db_file');
            }
            break;

          case 'from_existing':
            if(isset($_FILES['sql_file'])) {
              # todo validate this file
            } else {
              $this->addError('new_db', 'setup.content.missing_from_existing_file');
            }
            break;

          default:
            $this->addError('new_db', 'setup.content.invalid_new_db_value');
            break;
        }
      }
    }
  }

  private function validateCustom() {
    if (!$this->readyForCustom()) {
      $this->addMessage(I18n::t('setup.custom.not_ready'));

    } else {
      echo '<pre>'; print_r($_POST); echo '</pre>';
      if ($this->isMissing('theme')) {
        $this->addError('theme', 'setup.custom.missing_theme');
      } else {
        $themes = SetupHelper::getAvailableThemes();

        if (!in_array($_POST['theme'], $themes)) {
          $this->addError('theme', 'setup.custom.invalid_theme');
        }
      }

      if ($this->isMissing('language')) {
        $this->addError('language', 'setup.custom.missing_language');
      } else {
        $languages = SetupHelper::getAvailableLanguages();

        if (!in_array($_POST['language'], array_keys($languages))) {
          $this->addError('language', 'setup.custom.invalid_language');
        }
      }

      if ($this->isMissing('timezone')) {
        $this->addError('timezone', 'setup.custom.missing_timezone');

      } else {
        $timezones = SetupHelper::getAvailableTimezones();

        if (!in_array($_POST['timezone'], array_keys($timezones))) {
          $this->addError('timezone', 'setup.custom.invalid_timezone');
        }
      }

      if ($this->isMissing('rss_path')) {
        $rss_path = 'rss.xml';
      } else {
        $rss_path = trim($_POST['rss_path']);
      }

      if (!file_exists('../'.$rss_path)) {
        if (file_put_contents('../'.$rss_path, '') === false) {
          $this->addError('rss_path', 'setup.custom.rss_not_writable');
        } else {
          unlink('../'.$rss_path);
        }
      } else {
        if (!is_writable('../'.$rss_path)) {
          $this->addError('rss_path', 'setup.custom.rss_not_writable');
        }
      }
    }
  }

  private function validateDatabase() {
    $this->values = array('db_host' => $_POST['db_host'],
                          'db_name' => $_POST['db_name'],
                          'db_user' => $_POST['db_user']);

    if ($this->isMissing('db_host')) {
      $this->addError('db_host', 'setup.database.missing_db_host');
    }

    if ($this->isMissing('db_name')) {
      $this->addError('db_name', 'setup.database.missing_db_name');
    }

    if ($this->isMissing('db_user')) {
      $this->addError('db_user', 'setup.database.missing_db_user');
    }

    if ($this->isMissing('db_pass')) {
      $this->addError('db_pass', 'setup.database.missing_db_pass');
    }

    if ( !$this->hasErrors() ) {
      $con = @new MySQLi( $_POST['db_host'], $_POST['db_user'],
                          $_POST['db_pass'], $_POST['db_name']);

      if ($con->connect_error) {
        switch ($con->connect_errno) {
          case 1045 :
            $this->addMessage(I18n::t('setup.database.access_denied', $_POST['db_user']));
            break;

          case 1049 :
            $this->addMessage(I18n::t('setup.database.unknown_database', $_POST['db_name']));
            break;

          case 2002 :
            $this->addMessage(I18n::t('setup.database.unknown_host', $_POST['db_host']));
            break;

          default:
            $this->addMessage(I18n::t('setup.database.connection_error'));
            break;
        }
      }
    }
  }

  private function validateInvalidStep() {

  }

  private function validateStep() {
    $this->values = array();

    $_SESSION['setup_values'] = array();

    switch ($this->step) {
      case 'welcome'  : break;
      case 'finish'   : break;

      case 'database'   : $this->validateDatabase();  break;
      case 'content'    : $this->validateContent();   break;
      case 'admin_user' : $this->validateAdminUser(); break;
      case 'custom'     : $this->validateCustom();    break;
      case 'advanced'   : $this->validateAdvanced();  break;

      default : $this->validateInvalidStep(); break;
    }

    $_SESSION['setup_values'] = $this->values;

    if ($this->hasErrors() || $this->hasMessages()) {
      return false;
    } else {
      return true;
    }
  }
}

?>