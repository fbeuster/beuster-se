<?php

class ErrorPage extends Page {
  private $message;
  private $type = Page::PAGE_TYPE_ERROR;

  public function __construct($message) {
    $this->message = $message;
  }

  public function getMessage() {
    return $this->message;
  }
}

?>