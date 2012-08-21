<?php
class queryClass {
	var $dbConn = array( 
				'userdb' => array(
						"server" => "localhost",
						"user" => "",
						"pass" => "",
						"name" => "user"
						),
				'tradedb' => array(
						"server" => "localhost",
						"user" => "",
						"pass" => "",
						"name" => "trade"						
						),
				'stockdb' => array(
						"server" => "localhost",
						"user" => "",
						"pass" => "",
						"name" => "stock"						
						),
				'gamedb' => array(
						"server" => "localhost",
						"user" => "",
						"pass" => "",
						"name" => "gamesite"
						)
				);
					
	private $dbShort;	
	private $dblink;
	private $result;
	
	function __construct($db) {
		if(key_exists($db, $this->dbConn)) {
			$info = $this->dbConn[$db];
			$this->dblink = new mysqli($info['server'], $info['user'], $info['pass'], $info['name']);
			$this->dbShort = $db;
			
			if ($this->dblink->connect_errno) {
			    printf("Connect failed: %s\n", $this->dblink->connect_error);
			    exit();
			}
		} else {
			print "db conn does not exist";
			exit();
		}
	}
	
	function getDbName() {
		return $this->dbConn[$this->dbShort]['name'];
	}
	
	function query($query) {
		$this->result = $this->dblink->query($query) or die($this->dblink->error);
		return $this->result;
	}
	
	function fetchArray($type = NULL) {
		$type = ($type == NULL) ? MYSQLI_BOTH : $type;
		return $this->result->fetch_array($type);
	}
	
	function fetchAll($type = NULL) {
		$type = ($type == NULL) ? MYSQLI_BOTH : $type;
		if (method_exists('mysqli_result', 'fetch_all')) # Compatibility layer with PHP < 5.3
            $res = $this->result->fetch_all($type);
        else
            for ($res = array(); $tmp = $this->fetchArray($type);) $res[] = $tmp;

        return $res;
	}
	
	function fetchResult() {
		$array = $this->fetchArray();
		return $array[0];
	}
	
	function fetchNumRows() {
		return $this->result->num_rows;
	}
	
	function fetchLastId() {
		return $this->dblink->insert_id;
	}
	
	function __destruct() {
		if(isset($this->dblink)) $this->dblink->close();
	}
}
?>