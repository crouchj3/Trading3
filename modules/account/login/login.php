<?php
class login extends Controller {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("isLoggedIn", true);

		if(isset($_POST['submit'])) {
			if($this->model->login($this->auth, $_POST['email'], $_POST['pwd'])) {
				$this->assign("errorMsg", "Incorrect Login Information");
			}
		}
		$this->assign("content", "accountLogin");
	}
}
?>