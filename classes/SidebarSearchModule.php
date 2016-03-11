<?php

  class SidebarSearchModule extends SidebarModule {

      public function __construct($config) {
        $config["title"] = "";
        parent::__construct($config);
      }

      public function makeContent() {
        return parent::wrapContent( $this->makeSearchForm() );
      }

      private function makeSearchForm() {
        $ret = "<form action='/search' method='post'>\n";
        $ret .= " <input type='text' name='s' id='field' placeholder='Wonach möchtest du suchen?'>\n";
        $ret .= " <input type='submit' value='' name='search' title='Suchen'>\n";
        $ret .= " <br class='clear'>\n";
        $ret .= "</form>\n";
        return $ret;
      }
  }

?>