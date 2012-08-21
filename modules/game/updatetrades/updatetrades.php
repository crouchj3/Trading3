<?php
class updatetrades extends game {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("gameupdater");
		
		$this->model->updateCache();
		$this->assign("updatetrades", $this->model->scanTrades());
		$this->assign("rate", $this->model->getRate($_GET['rate']));
		$this->assign("content", "updatetrades");
	}
}
?>