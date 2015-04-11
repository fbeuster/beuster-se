<?php

/**
 * Provide Sorting algorithms.
 * 
 * \file classes/Sorting.php
 * \author Felix Beuster
 */

/**
* Provide Sorting algorithms.
* 
* \class Sorting
* \author Felix Beuster
*/
class Sorting {

	/**
	 * Sorts two Article from old to new.
	 * 
	 * @param Article $a first item
	 * @param Article $b second item
	 * @return int
	 */
	public static function articleAsc($a, $b) {
		if($a->getDate() == $b->getDate())
			return 0;
		return $a->getDate() > $b->getDate() ? 1 : -1;
	}

	/**
	 * Sorts two Article from new to old.
	 * 
	 * @param Article $a first item
	 * @param Article $b second item
	 * @return int
	 */
	public static function articleDesc($a, $b) {
		if($a->getDate() == $b->getDate())
			return 0;
		return $a->getDate() < $b->getDate() ? 1 : -1;
	}

	/**
	 * Sorts two DownloadFile by name, ascending oder.
	 * 
	 * @param DownloadFile $a first item
	 * @param DownloadFile $b second item
	 * @return int
	 */
	public static function downloadFileByNameAsc($a, $b) {
		$a = strtolower($a->getName());
		$b = strtolower($b->getName());
		return strcmp($a, $b);
	}

	/**
	 * Sorts two DownloadFile by name, descending oder.
	 * 
	 * @param DownloadFile $a first item
	 * @param DownloadFile $b second item
	 * @return int
	 */
	public static function downloadFileByNameDesc($a, $b) {
		$a = strtolower($a->getName());
		$b = strtolower($b->getName());
		return -1 * strcmp($a, $b);
	}
}

?>