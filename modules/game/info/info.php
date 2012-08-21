<?php
class info extends game {
	function __construct() {
		$this->Controller();
	}
	function control() {
		$this->auth->bounce("isLoggedIn");
		
		$gameId = $_GET['info'];
		
		if($_POST['submit'] == "Leave") {
			$this->model->removePlayer($_SESSION['userId'], $gameId);
		} else if($_POST['submit'] == "Join") {
			$this->model->addPlayer($_SESSION['userId'], $gameId);
		} else if($_POST['submit'] == "Delete") {
			$this->model->deleteGame($gameId);
			header("Location: ../");
			exit();
		}
	
		$this->assign("gameName", $this->model->getGameName($gameId));
		$this->assign("gameId", $gameId);
		$this->assign("isPlayerInGame", $this->model->getPlayerGameStatus($gameId, $_SESSION['userId']));
		$this->assign("gameInfo", $this->model->getGameInfo($gameId));
		$this->assign("content", "info");
	}
}
?>