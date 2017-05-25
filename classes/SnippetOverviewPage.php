<?php

  class SnippetOverviewPage extends AbstractAdminPage {

    private $snippets = array();

    public function __construct() {
      $this->load();
    }

    private function load() {
      $this->setTitle(I18n::t('admin.snippet.overview.label'));

      $db       = Database::getDB();
      $fields   = array('name', 'created', 'edited');
      $options  = 'ORDER BY name ASC';
      $snippets = $db->select('snippets', $fields, null, $options);

      if (count($snippets)) {
        foreach ($snippets as $snippet) {
          $this->snippets[] = array(
            'name'    => $snippet['name'],
            'created' => date("d.m.Y H:i", strtotime($snippet['created'])),
            'edited'  => date("d.m.Y H:i", strtotime($snippet['edited']))
          );
        }
      }
    }

    public function show() {
      include 'system/views/admin/snippet_overview.php';
    }
  }

?>
