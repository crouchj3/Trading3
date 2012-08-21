<?php
class dataClass {
	public function load($db) {
		$db = strtolower($db);
		static $dbArray = array();
		load::object("query");
		if(!isset($dbArray[$db])) {
			$dbArray[$db] = new queryClass($db);
		}
		return $dbArray[$db];
	}
}
?>