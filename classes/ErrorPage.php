<?php

class ErrorPage extends Page {
  private $file_name = 'static.php';
  private $message;
  private $type = Page::STATIC_PAGE;
  private $title = 'Fehler';

  public function __construct($message) {
    $this->message = $message;
  }

  public function getContent() {
    return $this->message;
  }

  public function getParsedContent() {
    return '<p>'.$this->message.'</p>';
  }

  public function getTitle() {
    return $this->title;
  }

  public function getFileName() {
    return $this->file_name;
  }

  public function getType() {
    return $this->type;
  }
}

?>