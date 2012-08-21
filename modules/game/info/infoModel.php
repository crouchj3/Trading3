<?php
class infoModel extends gameModel {
	var $tradedb;
	var $gamedb;
	var $stockdb;
	function __construct() {
		$this->tradedb = dataClass::load("tradedb");
		$this->gamedb = dataClass::load("gamedb");
		$this->stockdb = dataClass::load("stockdb");
	}
	
	function removePlayer($userId, $gameId) {
		$sql = "UPDATE game_user SET enabled = '0' WHERE player_id = '$userId' AND game_id = '$gameId'";
		$this->tradedb->query($sql);
		$sql = "UPDATE game_history SET enabled = '0' WHERE player_id = '$userId' AND game_id = '$gameId'";
		$this->tradedb->query($sql);
		$sql = "UPDATE game_trade SET enabled = '0' WHERE player_id = '$userId' AND game_id = '$gameId'";
		$this->tradedb->query($sql);
		$sql = "UPDATE game_stocks SET enabled = '0' WHERE player_id = '$userId' AND `game_id` = '$gameId'";
		$this->stockdb->query($sql);
	}
	
	function deleteGame($gameId) {
		$sql = "UPDATE game_user SET enabled = '0' WHERE game_id = '$gameId'";
		$this->tradedb->query($sql);
		$sql = "UPDATE game_history SET enabled = '0' WHERE game_id = '$gameId'";
		$this->tradedb->query($sql);
		$sql = "UPDATE game_trade SET enabled = '0' WHERE game_id = '$gameId'";
		$this->tradedb->query($sql);
		$sql = "UPDATE comp_list SET enabled = '0' WHERE `id` = '$gameId'";
		$this->tradedb->query($sql);
		$sql = "UPDATE game_stocks SET enabled = '0' WHERE `game_id` = '$gameId'";
		$this->stockdb->query($sql);
	}
	
	function getGameName($gameId) {
		$sql = "SELECT name FROM comp_list WHERE id = '$gameId'";
		$this->tradedb->query($sql);
	}
	
	function getPlayerGameStatus($gameId, $userId) {
		$sql = "SELECT count(*) FROM game_user WHERE game_id = '$gameId' AND player_id = '$userId' AND enabled = '1'";
		$this->tradedb->query($sql);
		$count = $this->tradedb->fetchResult();
		if($count > 0) return true;
		else return false;	
	}
}
?>