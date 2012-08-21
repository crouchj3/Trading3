<?php
class notFound extends Controller {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		header("Status: 404 Not Found");
		$this->assign("content", "notFound");
	}
}
?>