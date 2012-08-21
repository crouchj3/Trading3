<?php
class updatetradesModel extends gameModel {
	var $stockdb;
	var $tradedb;
	var $defaultRate = 10;  //seconds
	var $realtimeDelay = 0;	//seconds
	
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
	
	function updateCache() {
		$this->researchStock->update();
	}
			
	function scanTrades() {
		//get all pending trades
		$delay = $this->realtimeDelay;
		$time = time() - $delay;
		$time = date('Y-m-d H:i:s', $time);
		
		$sql = "SELECT * FROM game_trade WHERE enabled = '1' AND status = 'pending' AND timestamp <= '$time'";
		$this->tradedb->query($sql);
		//loops through all pending trades after the delay
		while($trade = $this->tradedb->fetchArray()) {
			//echo $trade['ticker']."<br />";
			//flush();
			$price = $this->researchStock->get($trade['ticker']);
			switch ($trade['type']) {
				case 'limitbuy':
					if($trade['price'] >= $price) {
						$this->executeTrade($trade,$price);
					}
					break;
				case 'limitsell':
					if($trade['price'] <= $price) {
						$this->executeTrade($trade,$price);
					}
					break;
				case 'stopsell':
					if($trade['price'] >= $price) {
						$this->executeTrade($trade,$price);
					}
					break;
				case 'buy':
				case 'sell':
					$this->executeTrade($trade,$price);
					break;
			}
		}
		return "Completed";
	}

	private function executeTrade($trade,$price) {
		$comp_id = $trade['game_id'];
		$user_id = $trade['player_id'];
		$ticker = $trade['ticker'];
		$type = $trade['type'];
		$trade_id = $trade['id'];
		$quant = $trade['quant'];
		$balance = $this->getCashBalance($comp_id, $user_id);
		switch($type) {
			case 'buy':
			case 'limitbuy':
				$amt = $price * $quant;
				if($balance < $amt) {
					return FALSE;
				}
				if(!$this->checkTradePercentage($ticker, $price, $quant, $comp_id, $user_id)) {
					return FALSE;
				}
				$commission = $this->getGameInfo($comp_id, 'commission');
				$newbalance = $balance - $amt - $commission;
				$sql = "UPDATE `game_user` SET `cash_remaining` = '$newbalance' WHERE `player_id` = '$user_id' AND enabled = '1'";
				$this->tradedb->query($sql);
				$add = $quant;
				break;
			case 'limitsell':
			case 'sell':
			case 'stopsell':
				$amt = $price * $quant;
				$newbalance = $balance  + $amt;
				$sql = "UPDATE `game_user` SET `cash_remaining` = '$balance' WHERE `player_id` = '$user_id' AND enabled = '1'";
				$this->tradedb->query($sql);
				$add = -1 * $quant;
				break;
		}
		$this->modifyPortfolio($comp_id,$user_id,$ticker,$add,$price);
		
		$sql = "UPDATE `game_trade` SET `price`='$price', `status`='executed' WHERE `id` = '$trade_id'";
		$this->tradedb->query($sql);
		return TRUE;
	}
	
	private function checkTradePercentage($symbol, $price, $quant, $comp_id, $player_id) {
		$percent = $this->getGameInfo($comp_id, 'max_portfolio_percent');
		$value = $this->getPortfolioValue($comp_id, $player_id);
		$stock = $this->getPortfolioStock($comp_id,$player_id,$symbol);
		$newpercent = ((($quant + $stock['quant']) * $price) / $value);
		if($newpercent < $percent / 100) 	return TRUE;
		else 								return FALSE;
	}
		
	private function modifyPortfolio($comp_id, $player_id, $symbol, $quant, $price) {
		$stock = $this->getPortfolioStock($comp_id, $player_id, $symbol);
		if($stock) {
			if($quant > 0) {
				$avgprice = ($quant * $price + $stock['quant'] * $stock['avgprice']) / ($quant + $stock['quant']);
			}
			$quant += $stock['quant'];
			if($quant == 0) {
				$sql = "UPDATE game_stocks SET quant = '$quant', avgprice = '0' WHERE `id` = '$stock[id]'";
				$this->stockdb->query($sql);
			} else {
				$sql = "UPDATE `game_stocks` SET `quant` = '$quant', `avgprice` = '$avgprice' WHERE `id` = '$stock[id]'";//`game_id` = '$comp_id' AND `player_id` = '$player_id' AND `ticker` = '$symbol'";
				$this->stockdb->query($sql);
			}
		} else {
			$sql = "INSERT INTO `game_stocks` (`game_id`, `player_id`, `ticker`, `avgprice`, `quant`, enabled)
						VALUES ('$comp_id','$player_id','$symbol','$price', '$quant', '1')";
			$this->stockdb->query($sql);
		}
	}
		
}
?>