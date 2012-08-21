<?php
class updateportfolios extends game {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("gameupdater");
		
		$this->assign("updatehistories", $this->model->updateHistories());
		$this->assign("rate", $this->model->getRate($_GET['rate']));
		$this->assign("content", "updateportfolios");
	}
}
?>