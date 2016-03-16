<?php

class ContentPage extends Page {
  private $id;
  private $content;
  private $refresh = '';
  private $title;
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

  public function getParsedContent() {
    return $this->content;
  }

  public function getRefreshName() {
    return $this->refresh;
  }

  public function getTags() {
    if (is_string($this->content))                return '';
    if (!isset($this->content['articles']))       return '';
    if (count($this->content['articles']) !== 1)  return '';

    return $this->content['articles'][0]->getTagsString();
  }

  public function getTitle() {
    return $this->title;
  }

  public function getType() {
    return $this->type;
  }

  private function loadContent() {
    global $file;
    $data = include 'includes/'.$file[$this->id][0];

    if(!isset($data['data'], $data['filename']))
      return;

    $this->content = $data['data'];
    $this->file_name = $data['filename'];

    if(isset($data['title'])) {
      $this->title = $data['title'];
    }

    if (isset($data['refresh'])) {
      $this->refresh = $data['refresh'];
    }
  }
}

?>