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

	public static function create($name, $parent_id, $type = self::CAT_TYPE_SUB) {
		$db 		= Database::getDB();
		$fields	= array('Cat', 'ParentID', 'Typ');
		$values = array('sii', array($name, $parent_id, $type));

		return $db->insert('newscat', $fields, $values);
	}

	public static function delete($id) {
		$db 	= Database::getDB();
		$cond = array('ID = ?', 'i', array($id));

		return $db->delete('newscat', $cond);
	}

	public static function exists($id_or_name) {
		$db 		= Database::getDB();
		$fields = array('ID');

		if (is_numeric($id_or_name)) {
			$conds = array('ID = ?', 'i', array($id_or_name));

		} else {
			$conds = array('Cat = ?', 's', array($id_or_name));
		}

		return $db->select('newscat', $fields, $conds) == true;
	}

	/**
	 * check, if a given name is a category
	 * @param String $name name of category to check
	 * @return int
	 */
	public static function isCategoryName($name) {
		$return = 0;
		$name 	= replaceUml(self::getNameUrlStatic($name));
		$dbs 		= Database::getDB();
		$fields = array('ID', 'Cat');
    $res 		= $dbs->select('newscat', $fields);

    foreach ($res as $cId) {
    	if ($name == replaceUml(self::getNameUrlStatic($cId['Cat']))) {
    		$return = $cId['ID'];
    	}
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

	public function assignToParent($parent_id) {
    $con = Database::getDB()->getCon();
    $sql = 'UPDATE
              newscat
            SET
              ParentID = ?
            WHERE
              ID = ?';
    $stmt = $con->prepare($sql);

    if (!$stmt) {
      return $con->error;
    }

    $stmt->bind_param('ii', $parent_id, $this->id);
    if (!$stmt->execute()) {
      return $stmt->error;
    }

    $stmt->close();
	}

	public function isLoaded() {
		return $this->loaded;
	}

	public function isPlaylist() {
		return $this->type == self::CAT_TYPE_PLAYLIST;
	}

	public function isTopCategory() {
		return $this->type == self::CAT_TYPE_TOP;
	}

  public function getChildren() {
    return $this->children;
  }

	public function getMaxArticleId() {
		$db = Database::getDB();

		$fields = array('MAX(CatID) as max_article_id');
		$conds  = array('Cat = ?', 'i', array($this->id));
		$res   	= $db->select('newscatcross', $fields, $conds);

		if (count($res) && $res[0]['max_article_id'] !== null) {
			return $res[0]['max_article_id'];
		}

		return 0;
	}

	public function getPlaylistId() {
		$db = Database::getDB();

		$fields = array('ytID');
		$conds 	= array('catID = ?', 'i', array($this->id));
		$res		= $db->select('playlist', $fields, $conds);

		if (count($res)) {
			return $res[0]['ytID'];
		}

		return '';
	}

	public function moveArticles($target_category) {
    $con = Database::getDB()->getCon();
    $sql = 'UPDATE
              newscatcross
            SET
              Cat = ?
            WHERE
              Cat = ?';
    $stmt = $con->prepare($sql);

    if (!$stmt) {
      return $con->error;
    }

    $stmt->bind_param('ii', $target_category, $this->id);
    if (!$stmt->execute()) {
      return $stmt->error;
    }

    $stmt->close();
	}

	public function moveChildren($target_category) {
    $con = Database::getDB()->getCon();
    $sql = 'UPDATE
              newscat
            SET
              ParentID = ?
            WHERE
              ParentID = ?';
    $stmt = $con->prepare($sql);

    if (!$stmt) {
      return $con->error;
    }

    $stmt->bind_param('ii', $target_category, $this->id);
    if (!$stmt->execute()) {
      return $stmt->error;
    }

    $stmt->close();
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

    $this->children = array();

    if ($this->type == self::CAT_TYPE_TOP) {
      $fields = array('ID');
      $conds  = array('ParentID = ?', 'i', array($this->id));
      $res    = $dbs->select('newscat', $fields, $conds);

      if (count($res)) {
        foreach ($res as $sub_category) {
          $this->children[] = new Category($sub_category['ID']);
        }
      }
    }


		$this->loaded = true;
	}
}

?>