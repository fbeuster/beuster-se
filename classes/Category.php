<?php

/**
 * Holding a Category.
 * \file classes/Category.php
 */

/**
* Holding a Category.
* 
* \class Category
* \author Felix Beuster
* 
* Class to hold and handle category functions
*/
class Category {

	const CAT_TYPE_TOP			= 0;	/**< category type for top categories */
	const CAT_TYPE_PLAYLIST 	= 1;	/**< category type for playlist categories */
	const CAT_TYPE_SUB 			= 2;	/**< category type for sub categories */
	const CAT_TYPE_PORTFOLIO	= 3;	/**< category type for portfolio categories */

	private $loaded = false;	/**< load status of category */

	private $id;			/**< category id */
	private $name;			/**< category name */
	private $type;			/**< type of the category */
	private $parent;		/**< id of parent category */
	private $description;	/**< category description */
	
	/**
	 * constructor
	 * 
	 * @param int $id an category id
	 */
	function __construct($id) {
		$this->id = $id;
		$this->loadCategory();
	}

	/*** STATIC ***/

	/**
	 * check, if a given name is a category
	 * @param String $name name of category to check
	 * @return int
	 */
	public static function isCategoryName($name) {

		$name = replaceUml(self::getNameUrlStatic($name));

		$return = 0;

		$dbs = Database::getDB();
		$fields = array('ID', 'Cat');
        $res = $dbs->select('newscat', $fields);
        foreach ($res as $cId) {
        	if($name == replaceUml(self::getNameUrlStatic($cId['Cat'])))
        		$return = $cId['ID'];
        }

        return $return;
	}

	public static function newFromName($name) {
		if($i = self::isCategoryName($name)) {
			return new Category($i);
		}
		return new Category(1);
	}

	/**
	 * static variant of getNameUrl()
	 * 
	 * @param String $name the text to transform
	 * @return String
	 */
	public static function getNameUrlStatic($name = null) {
		if($name == null)
			return '';
        $strokes = array(' ', '---', '--');
        foreach($strokes as $char) {
            $name = str_replace($char, '-', $name);
        }
        return mb_strtolower($name, 'UTF-8');
	}

	/*** PUBLIC ***/

	public function isLoaded() {
		return $this->loaded;
	}

	public function isTopCategory() {
		return $this->type == self::CAT_TYPE_TOP;
	}

	public function isPortfolio() {
		return $this->type == self::CAT_TYPE_PORTFOLIO;
	}


	/*** GET / SET ***/

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
	 * getter for name as url save variant
	 * @return String
	 */
	public function getNameUrl() {
		$name = $this->name;
        $strokes = array(' ', '---', '--');
        foreach($strokes as $char) {
            $name = str_replace($char, '-', $name);
        }
        return mb_strtolower($name, 'UTF-8');
	}
	
	/**
	 * getter for type
	 * @return int
	 */
	public function getType() { return $this->type; }
	
	/**
	 * setter for type
	 * @param int $type to set
	 */
	public function setType($type) { $this->type = $type; }
	
	/**
	 * getter for parent
	 * @return Category
	 */
	public function getParent() { return $this->parent == 0 ? null : new Category($this->parent); }
	
	/**
	 * setter for parent
	 * @param Category $parent to set
	 */
	public function setParent($parent) { $this->parent = $parent->getId(); }

	/**
	 * getter for description
	 * @return String
	 */
	public function getDescription() { return $this->description; }
	
	/**
	 * setter for description
	 * @param String $description to set
	 */
	public function setDescription($description) { $this->description = $description; }
	
	/*** PRIVATE ***/

	/**
	 * loads a category
	 */
	private function loadCategory() {

		$dbs = Database::getDB();
		$fields = array(
			'ID',
			'Cat',
			'ParentID',
			'Typ',
			'Beschreibung');
		$conds = array('ID = ?', 'i', array($this->id));
		$res = $dbs->select('newscat', $fields, $conds);

		if(count($res) != 1)
			return;

		$this->name = $res[0]['Cat'];
		$this->type = $res[0]['Typ'];
		$this->parent = $res[0]['ParentID'];
		$this->description = $res[0]['Beschreibung'];


		$this->loaded = true;
	}
}

?>