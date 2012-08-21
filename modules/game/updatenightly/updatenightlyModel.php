<?php
class updatenightlyModel extends gameModel {
	var $tradedb;
	
	function __construct() {
		$this->tradedb = dataClass::load("tradedb");
	}
	
	function updateGameStatus() {
		$sql = "UPDATE comp_list SET started = '1' WHERE start_date = CURDATE()";
		$this->tradedb->query($sql);
		$sql = "UPDATE comp_list SET started = '0' WHERE end_date = CURDATE()";
		$this->tradedb->query($sql);
		return "Completed";
	}
}
?>