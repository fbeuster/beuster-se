<?php

class ContentPage extends Page {
  private $id;
  private $content;
  private $type = Page::CONTENT_PAGE;

  public function __construct($id) {
    $this->id = $id;
    $this->loadContent();
  }
  
  public static function exists($id) {
    global $file;
    return file_exists('includes/'.$file[$id][0]);
  }

  public function getContent() {
    return $this->content;
  }
  
  public function getFileName() {
    return $this->file_name;
  }
  
  public function getType() {
    return $this->type;
  }
  
  private function loadContent() {
    global $file;
    $data = include 'includes/'.$file[$this->id][0];
    $this->content = $data['data'];
    $this->file_name = $data['filename'];
  }
}

?>