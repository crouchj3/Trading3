<?php
class resetModel {
	function reset($database_name) {
		$db = dataClass::load($database_name);
		$dbName = $db->getDbName();
		
		/* query all tables */
		$sql = "SHOW TABLES FROM $dbName";
		
		$found_tables = array();
		if($db->query($sql)) {
			while($row = $db->fetchResult()){
				$found_tables[]=$row;
			}
		}
		  
		/* loop through and drop each table */
		foreach($found_tables as $table_name){
		  $sql = "DROP TABLE $table_name";
		  if($result = $db->query($sql)){
		    echo "Success - table $database_name"." - "."$table_name deleted.<br />";
		  } else {
		    echo "Error deleting $database_name"." - "."$table_name. MySQL Error: " . mysql_error() . "<br />";
		  }
		}
		echo "Done clearing $database_name <br />";
		
		load::object("sql/".$database_name);
		$obj = $database_name.'Class';
		$init = new $obj();
	}
}
?>
