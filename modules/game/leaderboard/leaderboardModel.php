<?php
class leaderboardModel extends gameModel {
	var $tradedb;
	var $userdb;
	function __construct() {
		$this->tradedb = dataClass::load("tradedb");
		$this->userdb = dataClass::load("userdb");
	}
	function getRankings($gameId, $start, $amt) {
			$sql = "SELECT `player_id`,`portfolio_value` FROM `game_user`
						WHERE `game_id` = '$gameId' AND enabled = '1'
						ORDER BY `portfolio_value` DESC";
			$this->tradedb->query($sql);
			$rankings = $this->tradedb->fetchAll();
			$i = 1;
			$lastBalance = -1.1111;
			$result = array();
			foreach($rankings as $row) {
				$processed = array();
				$processed['name'] = $this->getName($row['player_id']);
				$processed['balance'] = $row['portfolio_value'];
				$processed['rank'] = $i;
				$result[] = $processed;
				
				if($lastBalance != $processed['balance']) {
					$i++;
				}
			}
			return $result;
	}
	
	function getName($id) {
		$sql = "SELECT `display_name` FROM `users` WHERE `id` = '$id'";
		$this->userdb->query($sql);
		return $this->userdb->fetchResult();
	}
	
	function getNumPlayers($gameId) {
		$sql = "SELECT count(*) FROM game_user WHERE game_id='$gameId' AND enabled = '1'";
		$this->tradedb->query($sql);
		return $this->tradedb->fetchResult();
	
	}
}
?>