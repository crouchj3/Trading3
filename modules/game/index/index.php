<?php
class index extends game {
	function __construct() {
		$this->Controller();
	}
	function control() {
		$this->auth->bounce("isLoggedIn");
		
		$this->assign("list", $this->model->getGameList());
		$this->assign("content", "index");
	}
}
?>