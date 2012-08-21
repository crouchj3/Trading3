<?php
class updateportfoliosModel extends gameModel {
	
	var $stockdb;
	var $tradedb;
	var $defaultRate = 10;  //seconds
	
	function __construct() {
		$this->researchStock = new researchModel();
		$this->tradedb = dataClass::load("tradedb");
		$this->stockdb = dataClass::load("stockdb");
	}
	
	function getRate($rate) {
		if($rate) {
			return $_GET['rate'] * 1000;
		} else {
			return 10 * 1000;
		}
	}
	
	function updateHistories() {
		$sql = "SELECT *
				FROM game_stocks
				WHERE enabled = '1'
				ORDER BY game_id, player_id
				";
		$this->stockdb->query($sql);
		$playerStocks = $this->stockdb->fetchAll(MYSQLI_ASSOC);
		
		end($playerStocks);
		$lastKey = key($playerStocks);
		reset($playerStocks);
		
		$lastPlayer = NULL;
		$lastGame = NULL;
		$playerValue = 0;
		$toLookup = array();
		$stocksQuant = array();
		$stocksValue = array();
		foreach($playerStocks as $key => $playerStock) {
			if(($lastPlayer != NULL && ($lastPlayer != $playerStock['player_id'] || $lastGame != $playerStock['game_id']))
				|| $key == $lastKey) {
					if($key == $lastKey) {
						$toLookup[] = $playerStock['ticker'];
						$stocksQuant[$playerStock['ticker']] = $playerStock['quant'];
						
						$lastPlayer = $playerStock['player_id'];
						$lastGame = $playerStock['game_id'];
					}
				$stocksValue = $this->researchStock->getArray($toLookup);
				$playerValue = $this->getCashBalance($lastGame, $lastPlayer);
				foreach($stocksValue as $key => $value) {
					$playerValue += $value * $stocksQuant[$key];
				}
				$sql = "UPDATE `game_user` SET `portfolio_value` = '$playerValue' WHERE `game_id` = '$lastGame' AND `player_id` = '$lastPlayer' AND enabled = '1'";
				$this->tradedb->query($sql);
				$sql = "INSERT INTO `game_history` (`player_id`, `game_id`, `value`, `enabled`) VALUE ('$lastPlayer', '$lastGame', '$playerValue', '1')";
				$this->tradedb->query($sql);

				$toLookup = array();
				$stocksQuant = array();
				$stocksValue = array();
				$playerValue = 0;
			}
			
			$toLookup[] = $playerStock['ticker'];
			$stocksQuant[$playerStock['ticker']] = $playerStock['quant'];
			
			$lastPlayer = $playerStock['player_id'];
			$lastGame = $playerStock['game_id'];
		}
		
		return "Completed";
	}
}
?>