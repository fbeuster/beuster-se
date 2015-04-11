<?php

/**
* Managing a Portfolio set
* 
* \class PortfolioSet
* \author Felix Beuster
*/
class PortfolioSet {

	private $id;	/**< the PortfolioSet id */
	private $name;	/**< name of portfolio set */

	private $items = array();	/**< array of PortfolioItem */

	private $loaded = false;	/**< load status */

	/**
	 * constructor
	 * 
	 * @param int $id PortfolioSet id
	 */
	function __construct($id) {
		$this->id = $id;
		$this->loadPortfolioSet();
	}

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
	 * getter for items
	 * @return array
	 */
	public function getItems() { return $this->items; }
	
	/**
	 * setter for items
	 * @param array $items to set
	 */
	public function setItems($items) { $this->items = $items; }
	
	/**
	 * Loads the current PortfolioSet.
	 */
	private function loadPortfolioSet() {

		# PortfolioSet meta
		$fields = array('Cat');
		$conds = array('ID = ?', 'i', array($this->id));
		$db = Database::getDB();
		$res = $db->select('newscat', $fields, $conds);

		if(count($res) != 1)
			return;

		$this->name = $res[0]['Cat'];

		# get all PortfolioItems for this set
		$fields = array('NewsID');
		$conds = array('Cat = ?', 'i', array($this->id));
		$db = Database::getDB();
		$res = $db->select('newscatcross', $fields, $conds);

		foreach ($res as $item) {
			$this->items[] = new PortfolioItem($item['NewsID']);
		}

		# sort PortfoilioItems
		usort($this->items, array('Sorting', 'ArticleDesc'));

		# set load status
		$this->loaded = true;
	}
}

?>