<?php
class register extends Controller {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("isLoggedIn", true);
		
		//$display_name,$email,$pwd1,$pwd2,$mail=true
		if(isset($_POST['submit'])) {
			$errorMsg = $this->model->validation($_POST['display_name'], $_POST['email'], $_POST['pwd1'], $_POST['pwd2']);
			$this->assign("errorMsg", $errorMsg);
			if(empty($errorMsg)) {
				$activcode = $this->model->register($_POST['display_name'], $_POST['email'], $_POST['pwd1']);
				$this->model->sendEmail($_POST['display_name'], $_POST['email'], $activcode);
				$this->assign("active", $this->model->getActive());
				$this->assign("content", "thankyou");
			} else {
				$this->assign("content", "register");
			}
		} else {
			$this->assign("content", "register");
		}
	}
}
?>