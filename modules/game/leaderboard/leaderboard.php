<?php
class leaderboard extends game {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("isLoggedIn");
		
		$gameId = $_GET['leaderboard'];
		$start = (isset($_GET['start'])) ? $_GET['start'] : 0;
		$amt = (isset($_GET['amt'])) ? $_GET['amt'] : 10;
		$this->assign("rankings", $this->model->getRankings($gameId, $start, $amt));
		$this->assign("numPlayers", $this->model->getNumPlayers($gameid));
		$this->assign("gameId", $gameId);
		$this->assign("content", "leaderboard");
	}
}
?>