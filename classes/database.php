<?php

/**
* 
*/
class Database {
	
	// class instance
	private static $instance;

	// connection to database
	private $con;

	// error messages
	public $error;

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

	/**
	 * selects data from database
	 * function retuns an array of rows or null
	 * 
	 * @param String $table name for table to select
	 * @param array $fields array of field names
	 * @param array $cond WHERE conidtions, given as array(condition_string, types, array(vars))
	 * @param String $options phrase as GROUP BY, ORDER BY as single string
	 * @param String $join tables to join, given as single string
	 * @return null|Array
	 */
	public function select($table, $fields, $cond = null, $options = null, $join = null) {
		
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
		if($join == null)
			$join == '';

		// conditions
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

			// building bind_param
			$cond_vars = array();
			$cond_vars[] = $cond[1];
			foreach ($cond[2] as $k => $v) {
				$var = 'con'.$k;
				$$var = $cond[2][$k];
				$cond_vars[] = &$$var;
			}
		}

		// buildung sql
		$sql = 'SELECT '.$fields.' FROM '.$table.$join.$cond_string.$options;

		// prepare request
		$stmt = $this->con->prepare($sql);
		if(!$stmt) {
			$this->error = $this->con->error;
			return null;
		}

		// bind_param
		if($cond != '') {
			if(!call_user_func_array(array($stmt, 'bind_param'), $cond_vars)) {
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