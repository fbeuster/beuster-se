<?php

  class SystemSettingsPage extends AbstractAdminPage {

    private $errors           = array();
    private $settings         = array();
    private $values           = array();

    public function __construct() {
      $this->handlePost();
      $this->load();
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD']) {
        # reading in the submitted values
        foreach ($_POST as $key => $value) {
          if ($key !== 'change_all') {
            $option = explode('-', $key, 2);

            if (!isset($this->settings[$option[0]])) {
              $this->settings[$option[0]] = array();
            }

            $this->settings[$option[0]][$option[1]] = trim($value);
          }
        }

        # validation
        $this->validate();

        # fill in missing settings
        $this->setDefaultSettings();

        # saving in database
        if (empty($this->errors)) {
          $con = Database::getDB()->getCon();
          foreach ($this->settings as $set_name => $set_settings) {
            foreach ($set_settings as $option_name => $option_value) {
              $sql = "UPDATE
                        configuration
                      SET
                        option_value = ?
                      WHERE
                        option_set = ? AND
                        option_name = ?";

              if (!$stmt = $con->prepare($sql)) {
                $this->errors['db'] = array(
                  'message' => 'database error message',
                  'value'   => $con->error);

              } else {
                $stmt->bind_param('sss',  $option_value,
                                          $set_name,
                                          $option_name);

                if (!$stmt->execute()) {
                  $this->errors['db'] = array(
                    'message' => 'database error message',
                    'value'   => $stmt->error);
                }

                $stmt->close();
              }
            }
          }

          # switch to new url schema
          # language and theme can't be changed here,
          # as they require includes unique class loads
          $lix = Lixter::getLix();
          $lix->setLinkBuilder($this->settings['site']['url_schema']);
        }

        # fill values for display
        $this->mapSettingsToValues();

        if (empty($this->errors)) {
          $this->showMessage( I18n::t('admin.article.editor.actions.new.success'),
                              'system-settings');
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.system_settings.label'));

      if (empty($this->values)) {
        $config = Config::getConfig();

        $sets = array(
          'dev'    => array( 'debug', 'dev_server_address',
                              'remote_server_address'),
          'ext'     => array( 'amazon_tag', 'google_analytics'),
          'meta'    => array( 'name', 'title', 'mail'),
          'search'  => array( 'case_sensitive', 'marks'),
          'site'    => array( 'theme', 'language',
                              'category_page_length', 'url_schema')
        );

        foreach ($sets as $set_name => $set_values) {
          foreach ($set_values as $option_name) {
            if ($config->get($set_name, $option_name) !== null) {
              if (!isset($this->settings[$set_name]) ||
                  !is_array($this->settings[$set_name])) {
                $this->settings[$set_name] = array();
              }

              if (!isset($this->settings[$set_name][$option_name])) {
                $this->settings[$set_name][$option_name] = $config->get($set_name, $option_name);
              }
            }
          }
        }

        $this->setDefaultSettings();
        $this->mapSettingsToValues();
      }

      $this->languages    = Locale::getAllLanguages();
      $this->url_schemas  = array();
      $this->themes       = Theme::getAllThemes();

      $this->url_schemas[LinkBuilder::DEFAULT_SCHEMA]   = 'Default Schema';
      $this->url_schemas[LinkBuilder::PARAMETER_SCHEMA] = 'Parameter Schema';
    }

    private function mapSettingsToValues() {
      foreach ($this->settings as $set_name => $set_settings) {
        foreach ($set_settings as $option_name => $option_value) {
          $this->values[$set_name.'.'.$option_name] = $option_value;
        }
      }
    }

    private function setDefaultSettings() {
      $defaults = array(
        'dev'    => array(
          'debug'                  => 0,
          'dev_server_address'    => '',
          'remote_server_address' => ''),
        'ext'     => array(
          'amazon_tag'            => '',
          'google_analytics'      => ''),
        'meta'    => array(
          'name'                  => 'My Blog',
          'title'                 => 'Runs with Lixter CMS',
          'mail'                  => 'no-reply@myblog.com'),
        'search'  => array(
          'case_sensitive'        => 0,
          'marks'                 => 1),
        'site'    => array(
          'theme'                 => 'default',
          'language'              => 'en',
          'category_page_length'  => CategoryPage::DEFAULT_PAGE_LENGTH,
          'url_schema'            => LinkBuilder::DEFAULT_SCHEMA)
      );

      foreach ($defaults as $set_name => $default_set) {
        if (!isset($this->settings[$set_name]) ||
            !is_array($this->settings[$set_name])) {
          $this->settings[$set_name] = array();
        }

        foreach ($default_set as $option_name => $option_value) {
          if (!isset($this->settings[$set_name][$option_name])) {
            $this->settings[$set_name][$option_name] = $option_value;
          }
        }
      }
    }

    public function show() {
      if ($this->has_message) {
        include 'system/views/admin/static.php';

      } else {
        include 'system/views/admin/system_settings.php';
      }
    }

    private function validate() {
      if (isset($this->settings['dev']['dev_server_address']) &&
          $this->settings['dev']['dev_server_address'] != $_SERVER['SERVER_NAME']) {
        # invalid dev server address
        $this->errors['dev-dev_server_address'] = array(
              'message' => I18n::t('admin.system_settings.error.invalid_dev_server_address'),
              'value'   => '');
      }

      if (!isset($this->settings['meta']['mail']) ||
          $this->settings['meta']['mail'] == '') {
        # empty mail
        $this->errors['meta-mail'] = array(
              'message' => I18n::t('admin.system_settings.error.empty_mail'),
              'value'   => '');

      } else {
        if (!checkMail($this->settings['meta']['mail'])) {
          # invalid mail
          $this->errors['meta-mail'] = array(
                'message' => I18n::t('admin.system_settings.error.empty_mail'),
                'value'   => '');
        }
      }

      if (!isset($this->settings['meta']['name']) ||
          $this->settings['meta']['name'] == '') {
        # empty name
        $this->errors['meta-name'] = array(
              'message' => I18n::t('admin.system_settings.error.empty_name'),
              'value'   => '');
      }

      if (!isset($this->settings['meta']['title']) ||
          $this->settings['meta']['title'] == '') {
        # empty title
        $this->errors['meta-title'] = array(
              'message' => I18n::t('admin.system_settings.error.empty_title'),
              'value'   => '');
      }

      if (!isset($this->settings['site']['category_page_length'])) {
        # empty category_page_length
        $this->errors['site-category_page_length'] = array(
              'message' => I18n::t('admin.system_settings.error.empty_category_page_length'),
              'value'   => '');

      } else {
        if (!ctype_digit(
              strval(
                $this->settings['site']['category_page_length']))) {
          # category_page_length not integer
          $this->errors['site-category_page_length'] = array(
                'message' => I18n::t('admin.system_settings.error.invalid_category_page_length'),
                'value'   => '');

        } else {
          if ($this->settings['site']['category_page_length'] < 1) {
            # category_page_length too small
            $this->errors['site-category_page_length'] = array(
                  'message' => I18n::t('admin.system_settings.error.invalid_category_page_length'),
                  'value'   => '');
          }
        }
      }

      if (!isset($this->settings['site']['language'])) {
        # language not set
        $this->errors['site-language'] = array(
              'message' => I18n::t('admin.system_settings.error.no_language'),
              'value'   => '');

      } else {
        if (!Locale::exists($this->settings['site']['language'])) {
          # invalid language
          $this->errors['site-language'] = array(
                'message' => I18n::t('admin.system_settings.error.invalid_language'),
                'value'   => '');
        }
      }

      if (!isset($this->settings['site']['theme'])) {
        # theme not set
        $this->errors['site-theme'] = array(
              'message' => I18n::t('admin.system_settings.error.no_theme'),
              'value'   => '');

      } else {
        if (!Theme::isValidTheme($this->settings['site']['theme'])) {
          # invalid theme
          $this->errors['site-theme'] = array(
                'message' => I18n::t('admin.system_settings.error.invalid_theme'),
                'value'   => '');
        }
      }

      if (!isset($this->settings['site']['url_schema'])) {
        # url_schema not set
        $this->errors['site-url_schema'] = array(
              'message' => I18n::t('admin.system_settings.error.no_url_schema'),
              'value'   => '');

      } else {
        if (!ctype_digit(
              strval(
                $this->settings['site']['url_schema']))) {
          # url_schema not integer
          $this->errors['site-url_schema'] = array(
                'message' => I18n::t('admin.system_settings.error.invalid_url_schema'),
                'value'   => '');

        } else {
          if (!LinkBuilder::schemaExists($this->settings['site']['url_schema'])) {
            # url_schema too small
            $this->errors['site-url_schema'] = array(
                  'message' => I18n::t('admin.system_settings.error.invalid_url_schema'),
                  'value'   => '');
          }
        }
      }
    }
  }

?>
