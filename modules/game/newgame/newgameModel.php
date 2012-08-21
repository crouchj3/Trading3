<?php
class newgameModel extends gameModel {
	var $tradedb;
	function __construct() {
		$this->tradedb = dataClass::load("tradedb");
	}
	
	function makeGame ($ar) {
		foreach($ar as $key => $value) {
			$$key = $value;
		}
		
		if($this->checkDuplicateGame($name)) {
			return FALSE;
		} else {
		
			$user_id = $_SESSION['userId'];
			$comp_id = $this->insertGameList($name, $descript, $start_date, $end_date, $joinable_post_start, $public_game,
				$password, $start_balance, $commission, $max_portfolio_percent, 
				$short_sell, $limit_order, $stop_loss, 
				$user_id, $entry_fee);
			
			$this->addPlayer($user_id,$comp_id);
		}
		return $comp_id;
	}
	
	function checkDuplicateGame($name) {
		$sql = "SELECT count(*) FROM `comp_list` WHERE `name` = '$name' AND enabled = '1'";
		$this->tradedb->query($sql);
		$count = $this->tradedb->fetchResult();
		if($count == 0)	return FALSE;
		else 			return TRUE;
	}
	
	function insertGameList($name,$description, $start_date, $end_date, $joinable_post_start, $public_game, 
				$password, $start_balance, $commission, $max_portfolio_percent,
				$short_sell, $limit_order, $stop_loss, $user_id, $entry_fee) {
		$sql = "INSERT INTO `comp_list` (
					`name`,`description`,`start_date`,`end_date`,
					`joinable_post_start`,`public_game`,`password`,
					`start_balance`,`commission`,`max_portfolio_percent`,
					`short_sell`,`limit_order`,`stop_loss`,`admin`, `entry_fee`,`enabled`
				) VALUES (
					'$name','$description','$start_date', '$end_date', 
					'$joinable_post_start', '$public_game', '$password', 
					'$start_balance', '$commission', '$max_portfolio_percent',
					'$short_sell', '$limit_order', '$stop_loss', '$user_id', '$entry_fee', '1'
				)";
		
		$this->tradedb->query($sql);
		return $this->tradedb->fetchLastId();
	}
}
?>