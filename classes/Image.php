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
  private $upload_date;

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

    if (!preg_match('#^'.self::ARTICLE_IMAGE_PATH.'.*#', $path)) {
      $path   = self::ARTICLE_IMAGE_PATH . $path;
    }

    if (!file_exists($path)) {
      # given image doesn't exists
      return false;
    }

    $dimensions   = '_' . $width . 'x'. $height;
    $pic['path']  = self::ARTICLE_IMAGE_PATH . $path_arr['filename'] . $dimensions .'.'. $path_arr['extension'];

    if (file_exists($pic['path'])) {
      # thumb already exists
      return false;
    }

    $pic['dim']   = getimagesize($path);
    $pic['dim']   = array('w' => $pic['dim'][0],
                          'h' => $pic['dim'][1]);

    if ( $pic['dim']['w'] < $width && $pic['dim']['h'] < $height) {
      # image too small
      return false;
    }

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

    return true;
  }

  public static function delete($id) {
    $db = Database::getDB();

    $fields = array('file_name');
    $conds  = array('id = ?', 'i', array($id));
    $image  = $db->select('images', $fields, $conds);

    if (count($res) == 1) {
      $path = $res[0]['file_name'];

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

      $cond = array('id = ?', 'i', array($id));
      $db->delete('images', $cond);
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
      $thumb_sizes    = Lixter::getLix()->getTheme()->getThumbnailSizes();

      while (file_exists($path)) {
        $save_name  = $file_name . '_' . $counter . '.' . $file_extension;
        $path       = $pre_path . $save_name;
        $counter++;
      }

      $thumb = 0;
      if (ctype_digit($thumb_key) && $thumb_key == $key + 1) {
        $thumb = 1;
      }

      move_uploaded_file($tmp_name, $path);

      $db     = Database::getDB();
      $fields = array('caption', 'file_name', 'upload_date');
      $values = array('ss&', array($save_name, $save_name, 'NOW()'));
      $maxid  = $db->insert('images', $fields, $values);

      $fields = array('article_id', 'image_id', 'is_thumbnail');
      $values = array('iii', array($article_id, $maxid, $thumb));
      $a_i    = $db->insert('article_images', $fields, $values);

      # create thumbnail
      foreach ($thumb_sizes as $thumb_size) {
        Image::createThumbnail($pre_path . $save_name, $thumb_size[0], $thumb_size[1]);
      }

      return $maxid;
    }
  }

  public static function storeRemoteImage($remote, $store_path) {
    if (!file_exists($store_path)) {
      $source_image = imagecreatefromjpeg($remote);

      if (!$source_image) {
        return false;
      }

      $thumb_width  = imagesx($source_image);
      $thumb_height = imagesy($source_image);

      # these are the dimensions of YouTube's blank thumbnail
      if ($thumb_width == 120 && $thumb_height == 90) {
        return false;
      }

      $scaled_image = imagecreatetruecolor($thumb_width, $thumb_height);

      if (!$scaled_image) {
        return false;
      }

      imagecopy($scaled_image, $source_image, 0, 0, 0, 0, $thumb_width, $thumb_height);
      imagedestroy($source_image);

      $scaled_image = imagescale($scaled_image, 480, 270);

      if (!imagejpeg($scaled_image, $store_path)) {
        return false;
      }

      imagedestroy($scaled_image);

      return true;
    }

    return true;
  }

  /*** PUBLIC ***/

  /**
   * get path of thumbnail version
   *
   * If the thumbnail doesn't exists, this method tries to create it.
   * @return string
   */
  public function getPathThumb($width = 0, $height = 0) {
    if (!file_exists(self::ARTICLE_IMAGE_PATH . $this->path)
      || !is_numeric($width) || !is_numeric($height)
      || $width <= 0 || $height <= 0) {

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
        $created = self::createThumbnail($this->path, $width, $height);

        if ($created) {
          return self::ARTICLE_IMAGE_PATH . $path;

        } else {
          return self::ARTICLE_IMAGE_PATH . $this->path;
        }
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

  public function getUploadDate() {
    return $this->upload_date;
  }

  public function getMetaInformation() {
    return array(
      'absolute_path' => $this->getAbsolutePath(),
      'caption' => $this->title,
      'file_name' => $this->path,
      'added' => $this->upload_date
    );
  }

  /*** PRIVATE ***/

  /**
   * Loads the image.
   */
  private function loadImage() {
    $db = Database::getDB();

    $fields = array('images.caption', 'images.file_name', 'images.upload_date',
                    'article_images.article_id', 'article_images.is_thumbnail');
    $conds  = array('images.id = ?', 'i', array($this->id));
    $join   = ' JOIN article_images ON images.id = article_images.image_id';
    $res    = $db->select('images', $fields, $conds, null, null, $join);

    if(count($res) != 1) {
      return;
    }

    $this->articleId    = $res[0]['article_id'];
    $this->title        = $res[0]['caption'];
    $this->upload_date  = $res[0]['upload_date'];
    $this->path         = $res[0]['file_name'];
    $this->thumb        = $res[0]['is_thumbnail'];

    $this->loaded = true;
  }
}

?>
