<?php
class portfolioModel extends gameModel {
	var $tradedb;
	var $stockdb;
	
	function __construct() {
		$this->researchStock = new researchModel();
		$this->tradedb = dataClass::load("tradedb");
		$this->stockdb = dataClass::load("stockdb");
	}
	
	function cancelTrade($userId, $tradeId) {
		$sql = "UPDATE `game_trade` SET `status`='cancelled' WHERE `id`='$tradeId'";
		$this->tradedb->query($sql);
		return TRUE;
	}
	
	function getPendingTrades($gameId, $userId) {
		$sql = "SELECT * FROM `game_trade` 
				WHERE `game_id` = '$gameId' AND `player_id` = '$userId' AND status = 'pending' AND enabled = '1'
				ORDER BY `timestamp` DESC";
		$this->tradedb->query($sql);
		$result = $this->tradedb->fetchAll();
		foreach($result as $row) {
			$type = $row['type'];
			$row['type'] = $this->tradeType[$row['type']]['name'];
		}
		return $result;
	}
	
	function getExecutedTrades($gameId, $userId) {
		$sql = "SELECT * FROM `game_trade` 
				WHERE `game_id` = '$gameId' AND enabled = '1' AND (`player_id` = '$playerId' OR status = 'cancelled' OR status = 'executed') 
				ORDER BY `timestamp` DESC";
		$this->tradedb->query($sql);
		
		$result = $this->tradedb->fetchAll();
		foreach($result as $row) {
			$type = $row['type'];
			$row['type'] = $this->tradeType[$row['type']]['name'];
		}
		return $result;
	}
	
	function getPortfolioHistory($gameId, $userId) {
		$sql = "SELECT `timestamp`,`value` FROM `game_history` 
				WHERE `game_id` = '$gameId' AND `player_id` = '$userId' AND enabled = '1'
				ORDER BY `timestamp` DESC";
		$this->tradedb->query($sql);
		return $this->tradedb->fetchAll();
	}
	
	function getPortfolioPrices($portfolio) {
		$stocks = array();
		foreach($portfolio as $key => $value) {
			if(!is_numeric($key)) {
				$stocks[] = $key;
			} 
		}
		$prices = $this->researchStock->getArray($stocks);
		return $prices;
	}
}
?>