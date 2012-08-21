<?php
	class index extends Controller {
		
		function __construct() {
			$this->Controller();
		}
		
		function control() {
			$this->assign("content", "home");
		}
	}
?>