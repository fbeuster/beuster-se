<?php

/**
 * contains a DownloadSet.
 * 
 * \file classes/DownloadSet.php
 * \author Felix Beuster
 */

/**
 * contains a DownloadSet.
 * 
 * \class DownloadSet
 * \author Felix Beuster
 */
class DownloadSet {

	private $id;		/**< id of the DownloadSet */
	private $catName;	/**< name of the DownloadSet */
	private $count;		/**< number of DonwloadFile */

	private $downloadFiles = array();	/**< array of DownloadFile */

	private $loaded = false;	/**< DownloadSet load status */

	/*** PUBLIC ***/
	
	/**
	 * constructor
	 */
	public function __construct($id) {
		$this->id = $id;
		$this->loadDownloadSet();
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
	 * getter for catName
	 * @return String
	 */
	public function getCatName() { return $this->catName; }
	
	/**
	 * setter for catName
	 * @param String $catName to set
	 */
	public function setCatName($catName) { $this->catName = $catName; }
	
	
	/**
	 * getter for downloadFiles
	 * @return array
	 */
	public function getDownloadFiles() { return $this->downloadFiles; }

	/**
	 * getter for count
	 * @return int
	 */
	public function getCount() { return $this->count; }
	
	/**
	 * setter for count
	 * @param int $count to set
	 */
	public function setCount($count) { $this->count = $count; }
	
	
	/**
	 * setter for downloadFiles
	 * @param array $downloadFiles to set
	 */
	public function setDownloadFiles($downloadFiles) { $this->downloadFiles = $downloadFiles; }

	/*** PRIVATE ***/

	/**
	 * loads the DownloadSet
	 */
	private function loadDownloadSet() {

		// download set itself

		$fields = array('Catname');
		$conds = array('ID = ?', 'i', array($this->id));
		$db = Database::getDB();
		$res = $db->select('downcats', $fields, $conds);

		if(count($res) != 1)
			return;

		$this->catName 		= $res[0]['Catname'];

		// get download files

		$fields = array('ID');
		$conds = array('CatID = ?', 'i', array($this->id));
		$db = Database::getDB();
		$res = $db->select('downloads', $fields, $conds);

		foreach ($res as $k => $down) {
			$this->downloadFiles[] = new DownloadFile($down['ID']);
			$this->count++;
		}
		usort($this->downloadFiles, array('Sorting', 'downloadFileByNameAsc'));

		$this->loaded = true;
	}
}

?>