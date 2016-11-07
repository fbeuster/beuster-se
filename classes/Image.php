<?php

class Image {

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

    $pic['path']  = $path_arr['dirname'].'/th'.$path_arr['filename'].'_'.$path_arr['extension'].'.jpg';
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

  /*** PUBLIC ***/

  /**
   * get path of thumbnail version
   * @return string
   */
  public function getPathThumb() {
    if($this->thumb) {
          $path = str_replace('blog/id', 'blog/thid', $this->path);
          $path = str_replace('.', '_', $path);
          return $path.'.jpg';
      } else {
        return '';
      }
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
    $fields = array('NewsID', 'Name', 'Pfad', 'Thumb');
    $cond = array('ID = ?', 'i', array($this->id));
    $db = Database::getDB();
    $res = $db->select('pics', $fields, $cond);

    if(count($res) != 1)
      return;
    $this->articleId = $res[0]['NewsID'];
    $this->title = $res[0]['Name'];
    $this->path = $res[0]['Pfad'];
    $this->thumb = $res[0]['Thumb'];

    $this->loaded = true;
  }
}

?>