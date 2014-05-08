<?php

/**
 * Database handler.
 * \file classes/database.php
 */

/**
 * Database handler.
 * \class Database
 * \author Felix Beuster
 * 
 * Provides a simple and global access to the database.
 * 
 * \todo function for INSERT
 * \todo function for DELETE
 * \todo function for UPDATE
 */
class Database {

	/** database instance */
	private static $instance;

	/** current connection to database */
	private $con;

	/** most recent error message */
	public $error;

	/**
	 * constructor
	 */
	function __construct() {
		$this->getCon();
	}

	/**
	 * Get Database instance.
	 * 
	 * retrievieng the current or a new database instance
	 * 
	 * @return Database
	 */
	public static function getDB() {
		if(!self::$instance)
			self::$instance = new Database();
		return self::$instance;
	}

	/**
	 * Get connection to database.
	 * 
	 * retrieving and generating a MySQLi connection
	 * 
	 * @return MySQLi
	 */
	public function getCon() {
		if(!$this->con) {
			$this->con = @new MySQLi(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	    	$this->con->query("SET NAMES 'utf8'");
	    	$this->con->query("SET CHARACTER SET 'utf8'");
		}
		return $this->con;
	}

	/**
	 * Select from database.
	 * 
	 * selects data from database
	 * function retuns an array of rows or null
	 * 
	 * @param String $table name for table to select
	 * @param array $fields array of field names
	 * @param array $cond WHERE conidtions, given as array(condition_string, types, array(vars))
	 * @param String $options phrase as GROUP BY, ORDER BY as single string
	 * @param array $limit LIMIT conditions, given as array(limit_strin, types, array(vars))
	 * @param String $join tables to join, given as single string
	 * @return null|Array
	 */
	public function select($table, $fields, $cond = null, $options = null, $limit = null, $join = null) {
		
		// concatenate fields
		if(!is_array($fields) || empty($fields)) {
			$this->error = 'empty fields';
			return null;
		}
		$fields = implode(', ', $fields);

		// table name
		if($table == null || $table == '') return null;
		$table .= ' ';

		// joins
		if($join == null) $join == '';
		$join .= ' ';

		// limit
		$limit_vars = array();
		if($limit == null || (is_array($limit) && empty($limit))) {
			$limit_string = '';
		} else {
			// validate $limit
			if(!is_array($limit)) {
				$this->error = 'limit has to be an array';
				return null;
			}
			if(count($limit) != 3) {
				$this->error = 'wrong limit length';
				return null;
			}
			if($limit[0] == null || !is_string($limit[0]) || $limit[0] == '') {
				$this->error = 'wrong limit string';
				return null;
			}
			if($limit[1] == null || !is_string($limit[1]) || $limit[1] == '') {
				$this->error = 'wrong limit types';
				return null;
			}
			if(!is_array($limit[2])) {
				$this->error = 'limit variables have to be an array';
				return null;
			}
			if(!strlen($limit[1]) == count($limit[2])) {
				$this->error = 'limit type and variable missmatch';
				return null;
			}

			// limit string
			$limit_string = ' '.$limit[0];

			// building bind_param for limits
			$limit_vars[] = $limit[1];
			foreach ($limit[2] as $k => $v) {
				$var = 'lim'.$k;
				$$var = $limit[2][$k];
				$limit_vars[] = &$$var;
			}
		}

		// conditions
		$cond_vars = array();
		if($cond == null || (is_array($cond) && empty($cond))) {
			$cond_string = '';
		} else {
			// validate $cond
			if(!is_array($cond)) {
				$this->error = 'condition has to be an array';
				return null;
			}
			if(count($cond) != 3) {
				$this->error = 'wrong condition length';
				return null;
			}
			if($cond[0] == null || !is_string($cond[0]) || $cond[0] == '') {
				$this->error = 'wrong condition string';
				return null;
			}
			if($cond[1] == null || !is_string($cond[1]) || $cond[1] == '') {
				$this->error = 'wrong condition types';
				return null;
			}
			if(!is_array($cond[2])) {
				$this->error = 'condition variables have to be an array';
				return null;
			}
			if(!strlen($cond[1]) == count($cond[2])) {
				$this->error = 'condition type and variable missmatch';
				return null;
			}

			// condition string
			$cond_string = ' WHERE '.$cond[0];

			// building bind_param for conditions
			$cond_vars[] = $cond[1];
			foreach ($cond[2] as $k => $v) {
				$var = 'con'.$k;
				$$var = $cond[2][$k];
				$cond_vars[] = &$$var;
			}
		}

		// combine limit and condition vars
		$vars = array();
		if($cond_string != '' && $limit_string != '') {
			$vars[0] = $cond_vars[0] . $limit_vars[0];
			for($i = 1; $i < count($cond_vars); $i++) {
				$v = 'varC'.$i;
				$$v = $cond_vars[$i];
				$vars[] = &$$v;
			}
			for($i = 1; $i < count($limit_vars); $i++) {
				$v = 'varL'.$i;
				$$v = $limit_vars[$i];
				$vars[] = &$$v;
			}
		} else if($cond_string != '') {
			$vars = $cond_vars;
		} else if($limit_string != '') {
			$vars = $limit_vars;
		}

		// options
		$options = ' '.$options;

		// buildung sql
		$sql = 'SELECT '.$fields.' FROM '.$table.$join.$cond_string.$options.$limit_string;

		// prepare request
		$stmt = $this->con->prepare($sql);
		if(!$stmt) {
			$this->error = $this->con->error;
			return null;
		}

		// bind_param
		if($cond_string != '' || $limit_string != '') {
			if(!call_user_func_array(array($stmt, 'bind_param'), $vars)) {
				$this->error = $stmt->error;
				return null;
			}
		}

		// execute
		if(!$stmt->execute()) {
			$this->error = $stmt->error;
			return null;
		}
		
		// bind_result
		$rs = array();
		$meta = $stmt->result_metadata();
		while($f = $meta->fetch_field()) {
			$var = $f->name;
			$$var = null;
			$rs[$var] = &$$var;
		}
		call_user_func_array(array($stmt, 'bind_result'), $rs);

		// fetch
		$result = array();
		while($stmt->fetch()) {
			$row = array();
			foreach ($rs as $k => $v) {
				$row[$k] = $v;
			}
			$result[] = $row;
		}

		// close
		$stmt->close();

		return $result;
	}
}

?>