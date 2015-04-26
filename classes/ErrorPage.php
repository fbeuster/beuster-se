<?php

class ErrorPage extends Page {
  private $message;
  private $type = Page::STATIC_PAGE;

  public function __construct($message) {
    $this->message = $message;
  }

  public function getContent() {
    return $this->message;
  }
}

?>