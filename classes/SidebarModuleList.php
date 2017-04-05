<?php

  class SidebarModuleList extends SidebarModule {

    private $list = null;

      public function __construct($config) {
        $config['requirements'] = array('list');

        parent::__construct($config);
        $this->list = $config['list'];
      }

      public function makeContent() {
        return parent::wrapContent( $this->listToString($this->list) );
      }

      private function listToString($list) {
        $listString = "<ul>\n";

        if (!is_array($list)) {
          $listString .= $this->listItemToString($list);

        } else {
          foreach ($list as $key => $item) {
            $listString .= $this->listItemToString($key, $item);
          }
        }

        $listString .= "</ul>\n";

        return $listString;
      }

      private function listItemToString($key, $item) {
        if (!is_array($item)) {
          return "<li>$item</li>\n";

        } else {
          return "<li>$key" . $this->listToString($item) . "</li>\n";
        }
      }
  }

?>