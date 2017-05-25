<?php

  class StaticPageOverviewPage extends AbstractAdminPage {

    private $pages = array();

    public function __construct() {
      $this->load();
    }

    private function load() {
      $this->setTitle(I18n::t('admin.static_page.overview.label'));

      $db       = Database::getDB();
      $fields   = array('url', 'title');
      $options  = 'ORDER BY url ASC';
      $pages    = $db->select('static_pages', $fields, null, $options);

      if (count($pages)) {
        $this->pages = $pages;
      }
    }

    public function show() {
      include 'system/views/admin/static_page_overview.php';
    }
  }

?>
