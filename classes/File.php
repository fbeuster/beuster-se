<?php

/**
 * contains a File.
 *
 * \file classes/File.php
 * \author Felix Beuster
 */

/**
 * contains a File.
 *
 * \class File
 * \author Felix Beuster
 */
class File {

  const ATTACHMENT_PATH = 'files/';
  const DEFAULT_TYPE    = 0;
  const FILE_MAX_SIZE   = 5242880;
  const FILE_MIN_SIZE   = 0;

	private $id;		/**< id of File */
	private $name;		/**< name of File */
	private $path;		/**< path to File */
	private $downloads;	/**< number of downloads of this File */
	private $license;
	private $version;
	private $type;

	private $loaded = false;	/**< load status */

	/*** PUBLIC ***/

	/**
	 * constructor
	 */
	public function __construct($id) {
		$this->id = $id;
		$this->loadFile();
	}

  public static function delete($path) {
    if (file_exists($path)) {
      return unlink($path);
    }

    return false;
  }

	public static function exists($id) {
    $db = Database::getDB();

    $fields = array('file_name');
    $conds  = array('id = ?', 'i', array($id));
    $res    = $db->select('attachments', $fields, $conds);

    return count($res) == 1;
	}

	public function getDownloads() {
		return $this->downloads;
	}

  public function getId() {
    return $this->id;
  }

	public function getName() {
		return $this->name;
	}

	public function getLicense() {
		return $this->license;
	}

	public function getPath() {
		return $this->path;
	}

	public function getVersion() {
		return $this->version;
	}

  public static function incrementDownloadCount($id) {
    $db   = Database::getDB()->getCon();
    $sql  = " UPDATE
                attachments
              SET
                downloads = downloads + 1
              WHERE
                id = ?";

    if (!$stmt = $db->prepare($sql)) {
      return $db->error;
    }

    $stmt->bind_param('i', $id);

    if (!$stmt->execute()) {
      return $stmt->error;
    }

    $stmt->close();
  }

  public static function isValidSize($size) {
    return $size > self::FILE_MIN_SIZE && $size <= self::FILE_MAX_SIZE;
  }

  public static function saveUploadedFile($file, $tmp_name) {
    $pre_path = self::ATTACHMENT_PATH;

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

      move_uploaded_file($tmp_name, $path);

      return $path;
    }
  }

	/*** PRIVATE ***/

	/**
	 * loads the File
	 */
	private function loadFile() {

		// file itself

		$fields = array('file_name', 'file_path', 'downloads', 'version', 'license', 'type');
		$conds = array('id = ?', 'i', array($this->id));
		$db = Database::getDB();
		$res = $db->select('attachments', $fields, $conds);

		if(count($res) != 1)
			return;

		$this->downloads 	= $res[0]['downloads'];
		$this->name 			= $res[0]['file_name'];
		$this->path				= $res[0]['file_path'];
		$this->license		= $res[0]['license'];
		$this->type				= $res[0]['type'];
		$this->version		= $res[0]['version'];

		$this->loaded = true;
	}
}

?>