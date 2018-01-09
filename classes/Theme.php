<?php

class Theme {
  const DEFAULT_THEME_NAME = 'default';

  private $name;
  private $path;
  private $thumbnail_sizes = array();
  private $user_path  = 'user/theme/';

  public function __construct($name) {
    if (!Theme::isValidTheme($name)) {
      $name = 'default';
    }

    $this->name = $name;
    $this->path = 'theme/'.$name.'/';
  }

  public function addThumbnailSize($width, $height) {
    if ($width > 0 && $height > 0) {
      $this->thumbnail_sizes[] = array($width, $height);
    }
  }

  public static function getAllThemes() {
    $themes = scandir('theme');

    foreach ($themes as $key => $theme) {
      if (!is_dir('theme/'.$theme) ||
          $theme == '.' || $theme == '..') {
        unset($themes[$key]);
      }
    }

    return $themes;
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

  public function getThumbnailSizes() {
    return $this->thumbnail_sizes;
  }

  public static function isValidTheme($name) {
    return $name !== null && $name !== '' && file_exists('theme/'.$name.'/');
  }
}

?>