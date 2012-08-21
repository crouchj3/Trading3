<?php
class lookupModel extends gameModel {
	var $tradedb;
	var $stockdb;
	
	function __construct() {
		$this->researchStock = new researchModel();
		$this->tradedb = dataClass::load("tradedb");
		$this->stockdb = dataClass::load("stockdb");
	}
	
	function trade($userId, $symbol, $shares, $gameId, $type, $order, $price) {
		if($order == "limit" && !$this->getGameInfo($gameId, 'limit_order')) {
			return FALSE;
		} else if($order == "stop" && !$this->getGameInfo($gameId, 'stop_loss')) {
			return FALSE;
		}

		if($shares < 1) {
			echo "Invalid Trade";
			exit();
		} else if($gameId == "") {
			echo "Choose a Game";
			exit();
		} else if (($order == "limit" || $order == "stop") && ($price == "" || $price < 0)) {
			echo "Invalid Order";
			exit();
		} else {
			$price = 0;
		}
		
		if($type == "buy") {
			$shareprice = $this->researchStock->get($symbol);
			$amt = $shareprice * $shares;
			switch ($order) {
				case 'market':
					$ordertype = 'buy';
					$price = 0;
					break;
				case 'limit':
					$ordertype = 'limitbuy';
					break;
				case 'stop':
					echo "Invalid Trade";
					exit();
					break;
			}
		} else if ($type == "sell") {
			switch ($order) {
				case 'market':
					$ordertype = 'sell';
					$price = 0;
					break;
				case 'limit':
					$ordertype = 'limitsell';
					break;
				case 'stop':
					$ordertype = 'stopsell';
					break;
			}
		}
		if($this->addTrade($userId,$gameId,$ordertype,$symbol,$price,$shares))
			echo "Order Placed.";
		else
			echo "Invalid Order";
		
		exit();	
	}
	
	function getStock($symbol) {
		return $this->researchStock->getArray($symbol);
	}
	
	function getPlayerComps($userId) {
		$sql = "SELECT `game_id` FROM `game_user` WHERE `player_id` = '$userId' AND enabled = '1'";
		$this->tradedb->query($sql);
		
		$comps = array();
		while($row = $this->tradedb->fetchArray()) {
			$comps[] = "id = '{$row['game_id']}'";
		}
		
		$sql = "SELECT id, name FROM comp_list WHERE ";
		$sql .= implode(" AND ", $comps);

		if(!empty($comps)) {
			$this->tradedb->query($sql);
		}
		
		$return = array();
		while($row = $this->tradedb->fetchArray()) {
			$return[$row['id']] = $row['name'];
		}
		
		return $return;
	}
	
	private function addTrade($user_id,$comp_id,$type,$symbol,$price,$quant) {
		if($quant != floor($quant)) return FALSE;  //checks for non-integers
		
		switch ($type) {
			case 'buy':
				$total =  $this->researchStock->get($symbol) * $quant;
				$balance = $this->getCashBalance($comp_id, $user_id);
				if($balance < $total) return FALSE;
				break;
			case 'limitbuy':
				$total =  $price * $quant;
				$balance = $this->getCashBalance($comp_id, $user_id);
				if($balance < $total) return FALSE;
				break;
			case 'sell':
				$numStocks = $this->getPortfolioStock($comp_id,$user_id,$symbol);
				if($quant > $numStocks) return FALSE;
				break;
			case 'limitsell':
				$numStocks = $this->getPortfolioStock($comp_id,$user_id,$symbol);
				if($quant > $numStocks) return FALSE;
				break;
			case 'stopsell':
				$numStocks = $this->getPortfolioStock($comp_id,$user_id,$symbol);
				if($quant > $numStocks) return FALSE;
				break;
			default:
				echo "Error";
				exit();
		}
		
		$sql = "INSERT INTO `game_trade` (`player_id`, `game_id`, `type`,`ticker`,`price`,`quant`,`status`, enabled)
					VALUES ('$user_id','$comp_id', '$type','$symbol','$price','$quant','pending', '1')";
		$this->tradedb->query($sql);
		
		return TRUE;
	}
}
?>