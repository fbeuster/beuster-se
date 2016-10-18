<?php

class IndexPage extends CategoryPage {
  private $type;

  public function __construct() {
    parent::__construct();

    $this->type = Page::INDEX_PAGE;
  }

  public function getType() {
    return $this->type;
  }
}