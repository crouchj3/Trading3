<?php
class logout extends Controller {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->logout();
		header("Location: ../home/index");
		exit();
	}
}
?>