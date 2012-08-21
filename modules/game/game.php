<?php
class game extends Controller {
	function Controller() {
		parent::Controller();
		
		if(!$this->auth->isLoggedIn()) {
			header("Location: /s/account/login");
			exit();
		}
		
		$this->assign("otherIncludes", "gameHeader");
		
		$notAllow = array("index","newgame","updatetrades","updateportfolios", "");
		if(!is_numeric(array_search(strtolower($_GET['game']),$notAllow))) {
			$this->assign("inGame", true);
		}
	}
}
?>