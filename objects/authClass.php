<?php
class authClass {
	function __construct() {
		$this->verify();
	}
	
	function verify() {
		if(isset($_SESSION['user_agent']) && $_SESSION['user_agent'] != $_SERVER['HTTP_USER_AGENT']) {
			$this->logout();
			header("Location: /s/account/login");
			exit();
		} else if(!isset($_SESSION['user_agent'])) {
			session_regenerate_id(TRUE);
			$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
		}
	}
	
	function login($email, $pwd) {
		$loggedin = FALSE;
		$userDb = dataClass::load("userDb");
		$userDb->query("SELECT * FROM users WHERE user_email = '$email' AND activated = '1'");
		if($userDb->fetchNumRows() != 0) {
			$userRow = $userDb->fetchArray();
			$hashed = $userRow['pwd'];
			$userId = $userRow['id'];
			$salt = substr($hashed, 0, SALT_LENGTH);
			
			$myhashed = $salt.sha1($pwd.$salt);
			if($hashed == $myhashed) {
				$loggedin = TRUE;
				
				session_regenerate_id(TRUE);
				$_SESSION['userId'] = $userId;
				$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
				$this->storePermGroup($userId);
			}
		}
		return $loggedin;
	}
	
	function logout() {
		session_unset();
		session_destroy();
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
	    );
		session_start();
		session_regenerate_id(TRUE);
		$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	}
	
	function storePermGroup($userId) {
		$gamedb = dataClass::load("gamedb");
		$gamedb->query("SELECT `group` FROM users WHERE id = '$userId'");
		$group = $gamedb->fetchResult();
		$gamedb->query("SELECT perm FROM perm WHERE `group` = '$group'");
		if($gamedb->fetchNumRows() == 0) {
			$gamedb->query("SELECT perm FROM perm WHERE `group` = 'user'");
		}
		$_SESSION['group'] = $group;
		$_SESSION['permissions'] = $gamedb->fetchResult();
	}

	function isLoggedIn() {
		if(isset($_SESSION['userId'])) return TRUE;
		else return FALSE;
	}
	
	function checkGroup($group) {
		$arg = str_replace(" ","",$group);
		$arg = explode(",",$arg);
		
		foreach($arg as $value) {
			if($value == $_SESSION['group']) {
				return TRUE;
			}
		}
		return FALSE;
	}

	function checkPerm($perm) {
		$gamedb = dataClass::load("gamedb");
		
		$arg = str_replace(" ","",$perm);
		$arg = explode(",",$arg);
		
		$sql = "SELECT id FROM permList WHERE ";
		$first = TRUE;
		foreach($arg as $value) {
			if(!$first) $sql .= "OR ";
			$sql .= "name = '$value' ";
		}
		
		$gamedb->query($sql);
		$permId = $gamedb->fetchAll();
		
		foreach($permId as $id) {
			$a = $_SESSION['permissions'][$id['id']];
			if($a == 1) {
				return TRUE;
			}
		}
		return FALSE;
	}
	
	function bounce($perm, $home = false) {
		$uri = $_SERVER['REQUEST_URI'];
		if($perm == "isLoggedIn") {
			$bounce = !$this->isLoggedIn();
		} else {
			$bounce = !$this->checkPerm($perm);
		}

		if(($bounce && !$home) || (!$bounce && $home)) {
			$redirect = NULL;
			if($home) {
				$redirect = SITEHOME;
			} else {
				$redirect = SITELOGIN."?redirect=$uri";
			}
			header("Location: $redirect");
			exit();
		}
	}
}
?>