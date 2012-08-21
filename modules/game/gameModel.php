<?php
require("researchModel.php");
class gameModel {
	protected $tradeType = array(
					"buy" => array("id"=>0,"name"=>"Market Order Buy"),
					"limitbuy" => array("id"=>1,"name"=>"Limit Order Buy"),
					"sell" => array("id"=>2,"name"=>"Limit Order Sell"),
					"limitsell" => array("id"=>4,"name"=>"Market Order Sell"),
					"stopsell" => array("id"=>5,"name"=>"Stop Order Sell")
				);
	protected $researchStock;
	
	function getTradeTypeList() {
		return $this->tradeType;
	}
	
	function getGameList() {
		$tradedb = dataClass::load("tradedb");
		$sql = "SELECT `id`,`name`,`enabled`,`public_game` FROM `comp_list` WHERE end_date > CURDATE() AND enabled = '1'";
		$tradedb->query($sql);
		return $tradedb->fetchAll(MYSQLI_ASSOC);
	}
	
	function addPlayer($userId, $gameId) {
		$tradedb = dataClass::load("tradedb");
		$start_balance = $this->getGameInfo($gameId, "start_balance");

		$sql = "INSERT INTO `game_user` (`game_id`, `player_id`, `cash_remaining`, `portfolio_value`, enabled) VALUES ('$gameId', '$userId', '$start_balance', '$start_balance', '1')";
		$tradedb->query($sql);
		
		return TRUE;
	}
	
	function getGameInfo($gameId, $option = NULL) {
		if($option) {
			$select = "`$option`";
		} else {
			$select = "*";
		}
		$sql = "SELECT $select FROM comp_list WHERE id = '$gameId'";

		$this->tradedb->query($sql);
		if($option) {
			$result = $this->tradedb->fetchResult();
		} else {
			$result = $this->tradedb->fetchArray(MYSQLI_ASSOC);
		}  
		return $result;
	}
	
	function getCashBalance($gameId, $userId) {
			$sql = "SELECT `cash_remaining` 
					FROM `game_user` 
					WHERE `game_id` = '$gameId' AND `player_id` = '$userId' AND enabled = '1'";
			$this->tradedb->query($sql);
			$remaining = $this->tradedb->fetchResult();
			return $remaining;
	}
	
	function getPortfolioValue($gameId, $userId) {
		$portfolio = $this->getPortfolio($gameId, $userId);
		$symbols = array();
		foreach($portfolio as $key => $value) {
			$symbols[] = $key;
		}
		$prices = $this->researchStock->getArray($symbols);
		
		$portfolioValue = 0;
		if(!empty($prices)) {
			foreach($prices as $key => $value) {
				$portfolioValue += $portfolio[$key]['quant'] * $value;
			}
		}
		return $portfolioValue + $this->getCashBalance($gameId, $userId);
	}

	function getPortfolioStock($comp_id,$player_id,$symbol) {
		$sql = "SELECT * FROM `game_stocks` WHERE `game_id` = '$comp_id' AND `player_id` = '$player_id' AND `ticker` = '$symbol' AND enabled = '1'";
		$this->stockdb->query($sql);
		if($this->stockdb->fetchNumRows() == 0) {
			return FALSE;
		} else {
			return $this->stockdb->fetchArray();
		}
	}
	
	function getPortfolio($gameId, $userId) {
		$sql = "SELECT * FROM `game_stocks` WHERE `game_id` = '$gameId' AND `player_id` = '$userId' AND enabled = '1'";
		$this->stockdb->query($sql);
		$result = $this->stockdb->fetchAll();
		$stocks = array();
		foreach($result as $row) {
			$key = $row['ticker'];
			$stocks[$key]['quant'] = $row['quant'];
			$stocks[$key]['avgprice'] = $row['avgprice']; 
		}
		return $stocks;
	}
}
?>