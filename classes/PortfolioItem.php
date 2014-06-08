<?php

/**
* Holding a single Portfolio item
* 
* \class PortfolioItem
* \author Felix Beuster
*/
class PortfolioItem extends Article {

	private $image = null; /**< the Image of the PortfolioItem */

	private $loaded = false; /**< load status */
	
	/**
	 * constructor
	 * @param int $id the item id
	 */
	function __construct($id) {
		$this->setId($id);
		$this->loadPortfolioItem();
	}

	/**
	 * getter for image
	 * @return Image
	 */
	public function getImage() { return $this->image; }
	
	/**
	 * setter for image
	 * @param Image $image to set
	 */
	public function setImage($image) { $this->image = $image; }
	
	/**
	 * Loads the PortfolioItem.
	 */
	private function loadPortfolioItem() {

		# item content itself
		$fields = array('Titel', 'Autor',  'Inhalt', 'UNIX_TIMESTAMP(Datum) AS Date');
		$conds = array('ID = ?', 'i', array($this->getId()));
		$db = Database::getDB();
		$res = $db->select('news', $fields, $conds);

		if(count($res) != 1)
			return;

		$this->setTitle($res[0]['Titel']);
		$this->setAuthor(User::newFromId($res[0]['Autor']));
		$this->setContent($res[0]['Inhalt']);
		$this->setDate($res[0]['Date']);

		# item image
		$fields = array('ID');
		$conds = array('NewsID = ?', 'i', array($this->getId()));
		$res = $db->select('pics', $fields, $conds);

		if(count($res) != 1)
			return;
		$this->image = new Image($res[0]['ID']);

		# set load status
		$this->loaded = true;
	}
}

?>