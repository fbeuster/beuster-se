<?php

/**
 * contains a DownloadFile.
 *
 * \file classes/DownloadFile.php
 * \author Felix Beuster
 */

/**
 * contains a DownloadFile.
 *
 * \class DownloadFile
 * \author Felix Beuster
 */
class DownloadFile {

	private $id;			/**< id of DownloadFile */
	private $name;			/**< name of DownloadFile */
	private $description;	/**< raw description of DownloadFile */
	private $file;			/**< File of this DownloadFile */

	private $loaded = false;	/**< load status */

	/*** PUBLIC ***/

	/**
	 * constructor
	 */
	public function __construct($id) {
		$this->id = $id;
		$this->loadDownloadFile();
	}

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
	 * getter for name
	 * @return String
	 */
	public function getName() { return $this->name; }

	/**
	 * setter for name
	 * @param String $name to set
	 */
	public function setName($name) { $this->name = $name; }

	/**
	 * getter for description
	 * @return String
	 */
	public function getDescription() { return $this->description; }

	/**
	 * getter for parsed description
	 * @return String
	 */
	public function getDescriptionParsed() {
		return Parser::parse($this->description, Parser::TYPE_PREVIEW);
	}

	/**
	 * setter for description
	 * @param String $description to set
	 */
	public function setDescription($description) { $this->description = $description; }

	/**
	 * getter for file
	 * @return File
	 */
	public function getFile() { return $this->file; }

	/**
	 * setter for file
	 * @param File $file to set
	 */
	public function setFile($file) { $this->file = $file; }

	/*** PRIVATE ***/

	/**
	 * loads the DownloadFile
	 */
	private function loadDownloadFile() {

		// download file itself

		$fields = array('Name', 'Description', 'File');
		$conds = array('ID = ?', 'i', array($this->id));
		$db = Database::getDB();
		$res = $db->select('downloads', $fields, $conds);

		if(count($res) != 1)
			return;

		$this->name 		= $res[0]['Name'];
		$this->description	= $res[0]['Description'];
		$this->file 		= new File($res[0]['File']);

		$this->loaded = true;
	}
}

?>