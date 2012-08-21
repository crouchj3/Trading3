<?php
class registerModel {
	var $userdb;
	var $gamedb;
	var $active;
	var $mail;
	
	function __construct() {
		load::object("mail");
		$this->userdb = dataClass::load("userdb");
		$this->gamedb = dataClass::load("gamedb");
		$this->mail = new mailClass();
	}
	
	function validation($display_name, $email, $pwd1, $pwd2) {
		$errorMsg = array();
		if(empty($display_name) || strlen($display_name) < 3) {
			$errorMsg[] = "Display Name must be at least 3 characters";
		}
		$pattern = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
		if(!preg_match($pattern, $email)) {
			$errorMsg[] = "Invalid Email Address";
		}
		if($pwd1 != $pwd2) {
			$errorMsg[] = "Passwords do not match";
		}
		
		$sql = "SELECT * FROM users WHERE user_email = '$email'";
		$this->userdb->query($sql);
		if($this->userdb->fetchNumRows() != 0) {
			$errorMsg[] = "Email already in use";
		}
		
		$sql = "SELECT * FROM users WHERE display_name = '$name'";
		$this->userdb->query($sql);
		if($this->userdb->fetchNumRows() != 0) {
			$errorMsg[] = "Display name already in use";
		}
		
		return $errorMsg;
	}
	
	function register($display_name, $email, $pwd) {
		$hashed = $this->hashNewPass($pwd);
		$activation_code = $this->makeNewActivCode();
		$id = $this->randomId(9);
		$active = $this->defaultActive();
		$this->insertUserDb($id, $display_name, $email, $hashed, $activation_code, $active);
		return $activation_code;
	}
	
	private function defaultActive() {
		$sql = "SELECT value FROM settings WHERE name = 'user_reg'";
		$this->gamedb->query($sql);
		$mode = $this->gamedb->fetchResult();
		switch($mode) {
			case 'user': $this->active = TRUE; break;
			case 'admin': $this->active = FALSE; break;
			default: $this->active = TRUE;
		}
		return $this->active;
	}
	
	function getActive() {
		return $this->active;
	}
	
	private function random_string($length) {
		$alpha = 'abcdef01234567';
		$string = '';
		for($i = 0; $i < $length; $i++) {
			$string .= $alpha[rand() % strlen($alpha)];
		}
		return $string;
	}
	
	private function randomId($length) {
		$alpha = '0123456789';
		$first = true;
		do {
			$string = '';
			for($i = 0; $i < $length; $i++) {
				do {
					$value = $alpha[rand() % strlen($alpha)];
				} while($first && $value == 0);	//disallows first digit to be 0
				$first = false;
				$string .= $value;
			}
			$sql = "SELECT * FROM users WHERE id = '$string'";
			$this->userdb->query($sql);
			$numUsers = $this->userdb->fetchNumRows();
		} while($numUsers > 0);
		return $string;
	}
	
	private function hashNewPass($pwd) {
		$salt = $this->random_string(SALT_LENGTH);
		$hashed = $salt.sha1($pwd.$salt);
		return $hashed;
	}
	
	private function makeNewActivCode() {
		return $this->random_string(SALT_LENGTH);
	}
	
	private function insertUserDb($id, $display_name, $email, $pwd, $code, $active) {
		$sql = "INSERT INTO users VALUES
			('$id', '$display_name', '$email', '$pwd', now(), '$active')";
		$this->userdb->query($sql);
		$sql = "INSERT INTO users VALUES ('$id', 'user', '1')";
		$this->gamedb->query($sql);
		$sql = "INSERT INTO activation VALUES
				(NULL, '$id', '$email', '$code')";
		$this->userdb->query($sql);
	}
	
	function sendEmail($name, $email, $activcode) {
		$host = $_SERVER['HTTP_HOST'];
		$host_upper = strtolower($host);
		$path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$serverName = $_SERVER['SERVER_NAME'];
		
		if($this->active) {
			$activation_msg = "To finalize your registration, please click on the below link or visit http://{$serverName}/s/account/activate <br />
								http://{$serverName}/s/account/activate/{$activcode} <br /><br />";
		}
		$message = 
			"Hello,<br />
			Thank you for registering. Here are your login details<br /><br />
			
			Name: $name <br />
			Email: $email <br /><br />
			
			$activation_msg
			
			Thank You<br />
			Administrator<br />
			$host_upper <br />
			";
			
		$sql = "SELECT `value` FROM `settings` WHERE `name` = 'shorthand'";
		$this->gamedb->query($sql);
		$shorthand = $this->gamedb->fetchResult();

		if($this->mail->mail($email,"[".$shorthand."] Login Details",$message))	return TRUE;
		else 	return FALSE;
	}
}
?>