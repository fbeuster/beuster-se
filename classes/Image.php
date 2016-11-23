<?php

class Image {

  const ARTICLE_IMAGE_PATH  = 'images/blog/';
  const IMAGE_MAX_SIZE      = 5242880;
  const IMAGE_MIN_SIZE      = 0;

  private $loaded = false;

  private $id;
  private $articleId;
  private $path;
  private $title;
  private $thumb;

  /**
   * constructor
   * @param int $id image id
   */
  public function __construct($id) {
    $this->id = $id;
    $this->loadImage();
  }

  public static function createThumbnail($path, $width, $height) {
    $pic      = array();
    $path_arr = pathinfo($path);

    $dimensions   = '_' . $width . 'x'. $height;
    $pic['path']  = self::ARTICLE_IMAGE_PATH . $path_arr['filename'] . $dimensions .'.'. $path_arr['extension'];
    $pic['dim']   = getimagesize($path);
    $pic['dim']   = array('w' => $pic['dim'][0],
                          'h' => $pic['dim'][1]);

    $pic['t'] = getimagesize($path);
    $pic['t'] = $pic['t'][2];

    switch($pic['t']) {
      case "1":
        $original = imagecreatefromgif($path);  break;
      case "2":
        $original = imagecreatefromjpeg($path); break;
      case "3":
        $original = imagecreatefrompng($path);  break;
      default:
        $original = imagecreatefromjpeg($path); break;
    }

    $thumb = imagecreatetruecolor($width, $height);
    imagecopyresampled( $thumb, $original,
                        0, 0, 0, 0,
                        $width, $height,
                        $pic['dim']['w'], $pic['dim']['h']);
    imagejpeg($thumb, $pic['path'], 100);

    imagedestroy($thumb);
    imagedestroy($original);
  }

  public static function delete($path) {
    $file_name      = pathinfo($path, PATHINFO_FILENAME);
    $file_extension = pathinfo($path, PATHINFO_EXTENSION);

    foreach (glob(self::ARTICLE_IMAGE_PATH . $file_name.'_*x*.'.$file_extension) as $file) {
      if (file_exists($file)) {
        unlink($file);
      }
    }

    if (file_exists(self::ARTICLE_IMAGE_PATH . $path)) {
      unlink(self::ARTICLE_IMAGE_PATH . $path);
    }
  }

  public static function isValidFormat($format) {
    return preg_match('/^image\/(gif|p?jpeg|png)$/', $format);
  }

  public static function isValidSize($size) {
    return $size > self::IMAGE_MIN_SIZE && $size <= self::IMAGE_MAX_SIZE;
  }

  public static function saveUploadedImage($file, $tmp_name, $article_id, $thumb_key, $key) {
    $pre_path = Image::ARTICLE_IMAGE_PATH;

    if (!is_writable($pre_path)) {
      return false;

    } else {
      $counter        = 0;
      $file_name      = pathinfo($file, PATHINFO_FILENAME);
      $file_extension = pathinfo($file, PATHINFO_EXTENSION);
      $path           = $pre_path.$file_name . '.' . $file_extension;
      $save_name      = $file_name . '.' . $file_extension;

      while (file_exists($path)) {
        $save_name  = $file_name . '_' . $counter . '.' . $file_extension;
        $path       = $pre_path . $save_name;
        $counter++;
      }


      $thumb = 0;
      if (is_int($thumb_key) && '' != $thumb_key
        && $thumb_key == $key + 1) {
        $thumb = 1;
      }

      move_uploaded_file($tmp_name, $path);

      $db     = Database::getDB();
      $fields = array('article_id', 'caption', 'file_name', 'is_thumb', 'upload_date');
      $values = array('issi&', array($article_id, $save_name, $save_name, $thumb, 'NOW()'));
      $maxid  = $db->insert('images', $fields, $values);

      # create thumbnail
      Image::createThumbnail($pre_path . $save_name, 295, 190);
      Image::createThumbnail($pre_path . $save_name, 800, 450);

      # todo
      # this needs to be improved so that i
      #   don#t have to list all resolutions
      #   unused sizes are not necessarily created

      return $maxid;
    }
  }

  public static function storeRemoteImage($remote, $store_path) {
    if (!file_exists($store_path)) {
      $source_image = imagecreatefromjpeg($remote);
      $thumb_width  = imagesx($source_image);
      $thumb_height = imagesy($source_image);
      $scaled_image = imagecreatetruecolor($thumb_width, $thumb_height);

      imagecopy($scaled_image, $source_image, 0, 0, 0, 0, $thumb_width, $thumb_height);
      imagedestroy($source_image);

      $scaled_image = imagescale($scaled_image, 480, 270);

      imagejpeg($scaled_image, $store_path);
      imagedestroy($scaled_image);
    }
  }

  /*** PUBLIC ***/

  /**
   * get path of thumbnail version
   *
   * If the thumbnail doesn't exists, this method tries to create it.
   * @return string
   */
  public function getPathThumb($width = 0, $height = 0) {
    if (!file_exists(self::ARTICLE_IMAGE_PATH . $this->path
      || !is_numeric($width) || !is_numeric($height)
      || $width <= 0 || $height <= 0)) {

      return self::ARTICLE_IMAGE_PATH . $this->path;
    }

    $dimensions = '_' . $width . 'x'. $height;
    $file_name  = pathinfo($this->path, PATHINFO_FILENAME);
    $extension  = pathinfo($this->path, PATHINFO_EXTENSION);
    $path       = $file_name . $dimensions . '.' . $extension;

    if (!file_exists(self::ARTICLE_IMAGE_PATH . $path)) {
      if (!is_writable(self::ARTICLE_IMAGE_PATH)) {
        return self::ARTICLE_IMAGE_PATH . $this->path;

      } else {
        self::createThumbnail($this->path, $width, $height);
        return self::ARTICLE_IMAGE_PATH . $path;
      }
    }

    return self::ARTICLE_IMAGE_PATH . $path;
  }

  /*** GETTER / SETTER ***/

  /**
   * getter for id
   * @return int
   */
  public function getId() { return $this->id; }

  /**
   * setter for id
   * @param int $id to set
   */
  public function setId($id) { $this->id = $id; }

  /**
   * getter for articleId
   * @return int
   */
  public function getArticleId() { return $this->articleId; }

  /**
   * setter for articleId
   * @param int $articleId to set
   */
  public function setArticleId($articleId) { $this->articleId = $articleId; }

  /**
   * getter for path
   * @return string
   */
  public function getPath() { return $this->path; }

  /**
   * setter for path
   * @param string $path to set
   */
  public function setPath($path) { $this->path = $path; }

  public function getAbsolutePath() {
    return makeAbsolutePath(self::ARTICLE_IMAGE_PATH . $this->path, '', true);
  }

  public function getAbsoluteThumbnailPath($width = 0, $height = 0) {
    return makeAbsolutePath($this->getPathThumb($width, $height), '', true);
  }

  /**
   * getter for title
   * @return string
   */
  public function getTitle() { return $this->title; }

  /**
   * setter for title
   * @param string $title to set
   */
  public function setTitle($title) { $this->title = $title; }

  /*** PRIVATE ***/

  /**
   * Loads the image.
   */
  private function loadImage() {
    $fields = array('article_id', 'caption', 'file_name', 'is_thumb');
    $cond = array('ID = ?', 'i', array($this->id));
    $db = Database::getDB();
    $res = $db->select('images', $fields, $cond);

    if(count($res) != 1)
      return;
    $this->articleId = $res[0]['article_id'];
    $this->title = $res[0]['caption'];
    $this->path = $res[0]['file_name'];
    $this->thumb = $res[0]['is_thumb'];

    $this->loaded = true;
  }
}

?>