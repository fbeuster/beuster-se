<?php

  class SidebarModuleContent extends SidebarModule {

    private $content = null;

      public function __construct($config) {
        $config['requirements'] = array('content');

        parent::__construct($config);
        $this->content = $config['content'];
      }

      public function makeContent() {
        return parent::wrapContent( $this->content );
      }
  }

?>