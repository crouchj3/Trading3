<?php
class updatenightly extends game {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("gameupdater");
		
		$this->assign("updateGameStatus", $this->model->updateGameStatus());
		$this->assign("content", "updateNightly");
	}
}
?>