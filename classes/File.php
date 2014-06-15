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

	private $loaded = false;	/**< load status */

	/*** PUBLIC ***/
	
	/**
	 * constructor
	 */
	public function __construct($id) {
		$this->id = $id;
		$this->loadFile();
	}

	/*** PRIVATE ***/

	/**
	 * loads the File
	 */
	private function loadFile() {

		// file itself

		$fields = array('Name', 'Path', 'downloads');
		$conds = array('ID = ?', 'i', array($this->id));
		$db = Database::getDB();
		$res = $db->select('files', $fields, $conds);

		if(count($res) != 1)
			return;

		$this->name 		= $res[0]['Name'];
		$this->path			= $res[0]['Path'];
		$this->downloads	= $res[0]['downloads'];

		$this->loaded = true;
	}
}

?>