<?php
class lookup extends game {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("isLoggedIn");
		
		if($_POST['cmd'] == "trade") {
			$this->model->trade($_SESSION['userId'], $_POST['symbol'], $_POST['shares'], $_POST['gameid'], $_POST['type'], $_POST['order'], $_POST['price']);
		}
		if($_POST['submit'] == "Get" && !empty($_POST['ticker'])) {
			$this->assign("stocks", $this->model->getStock($_POST['ticker']));
		}
		$this->assign("loggedIn", $this->auth->isLoggedIn());
		if($this->auth->isLoggedIn()) {
			$this->assign("playercomps", $this->model->getPlayerComps($_SESSION['userId']));
		}
		
		$this->assign("gameId", $_GET['lookup']);
		$this->assign("content", "lookup");
	}
}
?>