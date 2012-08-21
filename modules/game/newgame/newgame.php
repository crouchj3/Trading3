<?php
class newgame extends game {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("isLoggedIn");
		
		if(!$this->auth->checkPerm("login")) {
			header("Location: /s/home/index");
			exit();
		}
		
		if($_POST['submit'] == "Create") {
			$ar = $_POST;
			if(empty($_POST['name'])) {
				$msg[] = "Game Name Required";
			} else {
				$id = $this->model->makeGame($ar);
				if($id != FALSE) {	//game created
					header("Location: /s/game/info/$id");
				} else {
					$msg[] = "Game Name Already Taken.  Please Choose Another!";
				}
			}
			$this->assign("errorMsg", $msg);
		}
		$this->assign("content", "newgame");
	}
}
?>