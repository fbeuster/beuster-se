<?php

/**
* 
*/
class Database {
	
	private static $instance;
	private $con;

	/**
	 * constructor
	 */
	function __construct() {
	}

	/**
	 * retrievieng the current or a new database connection
	 * @return Database
	 */
	public static function getDB() {
		if(!self::$instance)
			self::$instance = new Database();
		return self::$instance;
	}

	public function getCon() {
		if(!$this->con) {
			$this->con = @new MySQLi(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	    	$this->con->query("SET NAMES 'utf8'");
	    	$this->con->query("SET CHARACTER SET 'utf8'");
		}
		return $this->con;
	}

	private function newConnection() {
	}
}

?>