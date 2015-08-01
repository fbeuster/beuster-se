<?php

class StaticPage extends Page {
  private $file_name = 'static.php';
  private $url;
  private $type = Page::STATIC_PAGE;

  private $title;
  private $content;

  public function __construct($url) {
    $this->url = $url;
    $this->loadPage();
  }

  public static function exists($url) {
    $db   = Database::getDB();

    if(!$db->tableExists('static_pages'))
      return false;

    $fields = array('title');
    $conds = array('url = ?', 's', array($url));

    $res  = $db->select('static_pages', $fields, $conds);

    return count($res) > 0;
  }

  public function getContent() {
    return $this->content;
  }

  public function getParsedContent() {
    return '<p>'.Parser::parse($this->content, Parser::TYPE_CONTENT).'</p>';
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

  private function loadPage() {
    $fields = array('title', 'content');
    $conds = array('url = ?', 's', array($this->url));

    $db   = Database::getDB();
    $res  = $db->select('static_pages', $fields, $conds);

    foreach ($res as $page) {
      $this->title    = $page['title'];
      $this->content  = $page['content'];
    }
  }
}

?>