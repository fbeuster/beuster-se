<?php

abstract class AbstractAdminPage extends Page {

  private $content;
  private $file_name      = 'static.php';
  protected $has_message  = false;
  private $id;
  private $refresh        = '';
  private $title;
  private $type           = Page::ADMIN_PAGE;

  public function __construct($id) {
    $this->id = $id;
  }

  public static function exists($id) {
    return file_exists( 'classes/'.self::getClass($id).'.php' );
  }

  public static function getClass() {
    $ps = explode('-', $_GET['p']);
    $ps = array_map('ucfirst', $ps);
    return implode('', $ps).'Page';
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

  protected function setTitle($title) {
    if (!$this->has_message) {
      $this->title = $title;
    }
  }

  abstract public function show();

  protected function showMessage($message, $refresh) {
    $this->content      = $message;
    $this->has_message  = true;
    $this->refresh      = $refresh;
    $this->title        = I18n::t('page.info.title');
  }
}

?>
