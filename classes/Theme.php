<?php

class Theme {
  const DEFAULT_THEME_NAME = 'default';

  private $name;
  private $path;
  private $user_path  = 'user/theme/';

  public function __construct($name) {
    $this->name = $name;
    $this->path = 'theme/'.$name.'/';
  }

  public function getFile($filename) {
    if (file_exists($this->user_path.$filename))
      return $this->user_path.$filename;

    if (file_exists($this->path.$filename))
      return $this->path.$filename;

    return false;
  }

  public function getName() {
    return $this->name;
  }
}

?>