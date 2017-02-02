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

	public function getDownloads() {
		return $this->downloads;
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