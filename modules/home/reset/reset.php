<?php
class reset extends Controller {
	function __construct() {
		$this->Controller();
	}
	function control() {
		$this->auth->bounce("isLoggedIn");
		
		if(isset($_POST['submit'])) {
			$this->model->reset(strtolower($_POST['db']));
		}
		$this->assign("content", "dbDrop");
	}
}
?>