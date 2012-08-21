<?php
class portfolio extends game {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("isLoggedIn");
		
		$userId = $_SESSION['userId'];
		$gameId = $_GET['portfolio'];
		
		if($_POST['cmd'] == "cancel") {
			$this->model->cancelTrade($userId, $_POST['tradeid']);
			echo "Trade Cancelled";
			exit();
		}
	
		$this->assign("pending", $this->model->getPendingTrades($gameId, $userId));
		$this->assign("executed", $this->model->getExecutedTrades($gameId, $userId));
		$portfolio = $this->model->getPortfolio($gameId, $userId);
		$this->assign("portfolio", $portfolio);
		$this->assign("prices", $this->model->getPortfolioPrices($portfolio));
		$this->assign("userId", $_SESSION['userId']);
		$this->assign("gameId", $_GET['portfolio']);
		
		$this->assign("history", $this->model->getPortfolioHistory($gameId, $userId));
		$this->assign("balance", $this->model->getCashBalance($gameId, $userId));
		$this->assign("portfolioValue", $this->model->getPortfolioValue($gameId, $userId));
		$this->assign("gameId", $gameId);
		$this->assign("content", "portfolio");
	}
}
?>