<?php
class loginModel {
	function login($auth, $email, $pwd) {
		if($auth->login($email, $pwd)) {
			header("Location: ../home/index");
			exit();
		} else {
			return FALSE;
		}
	}
}
?>