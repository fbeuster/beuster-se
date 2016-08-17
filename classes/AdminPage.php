<?php

class AdminPage extends Page {

  private $file_name = 'static.php';
  private $id;
  private $refresh = '';
  private $type = Page::ADMIN_PAGE;

  private $title;
  private $content;

  public function __construct($id) {
    $this->id = $id;
    $this->loadPage();
  }

  public static function exists($id) {
    return file_exists('lix-admin/'.$id.'.php') && file_exists('system/views/admin/'.$id.'.php');
  }

  public function getContent() {
    return $this->content;
  }

  public function getFileName() {
    return $this->file_name;
  }

  public function getParsedContent() {
    return $this->content;
  }

  public function getRefreshName() {
    return $this->refresh;
  }

  public function getTitle() {
    return $this->title;
  }

  public function getType() {
    return $this->type;
  }

  private function loadPage() {
    global $file;
    $data = include 'lix-admin/'.$this->id.'.php';

    if(!isset($data['data'], $data['filename']))
      return;

    $this->content = $data['data'];
    $this->file_name = $data['filename'];

    if(isset($data['title'])) {
      $this->title = $data['title'];

    } else {
      # TODO all admin includes will set the title property
      $this->title = $file[$this->id][1];
    }

    if (isset($data['refresh'])) {
      $this->refresh = $data['refresh'];
    }
  }
}

?>
