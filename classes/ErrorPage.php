<?php

class ErrorPage extends Page {
  private $file_name = 'static.php';
  private $message;
  private $refresh = '';
  private $type = Page::STATIC_PAGE;
  private $title;

  public function __construct($message, $refresh = '') {
    $this->message  = $message;
    $this->refresh  = $refresh;
    $this->title    = I18n::t('page.error.title');
  }

  public function getContent() {
    return $this->message;
  }

  public function getDecoratedContent() {
    return $this->getParsedContent();
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

  public function getRefreshName() {
    return $this->refresh;
  }

  public function getType() {
    return $this->type;
  }
}

?>