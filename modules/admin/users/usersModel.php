<?php
class usersModel extends shareClass {
	var $gamedb;
	var $userdb;
	var $share;
	
	function __construct() {
		$this->userdb = dataClass::load("userdb");
		$this->gamedb = dataClass::load("gamedb");
	}
	
	function editUser($group, $display_name, $email, $id, $password) {
		$sql = "UPDATE users 
				SET `group` = '$group' WHERE id = '$id'";
		$this->gamedb->query($sql);
		$sql = "UPDATE users
				SET display_name = '$display_name', user_email = '$email' WHERE id = '$id'";
		$this->userdb->query($sql);
		if($_POST['password'] != "") {
			$pwd = $this->hashNewPass($_POST['password']);
			$sql = "UPDATE users SET pwd = '$pwd' WHERE id = '$id'";
			$this->userdb->query($sql);
		}
		echo "Edit Saved";
	}
	
	function getUsers($pagesize, $page, $search, $searchGroup) {
		if(!isset($pagesize)) $pagesize = 20;
	
		if(isset($page)) $lower = ($page - 1) * $pagesize;
		else {
			$lower = 0;
			$page = 1;
		}
		$upper = ($page) * $pagesize - 1;
	
		if($search != "") {
			$sql = "SELECT * FROM users
					WHERE id = '$search' OR display_name ='$search' OR `user_email`='$search'
					ORDER BY id ASC
					LIMIT $lower, $upper";
			$this->userdb->query($sql);
			$list = $this->userdb->fetchAll();
			$count = $this->userdb->fetchNumRows();
		} else if($searchGroup != "") {
			$sql = "SELECT * FROM users
					users WHERE `group` = '$searchGroup'
					ORDER BY id ASC
					LIMIT $lower, $upper";
			$this->gamedb->query($sql);
			$list = $this->gamedb->fetchAll();
			$count = $this->gamedb->fetchNumRows();
		} else {
			$sql = "SELECT * FROM users ORDER BY id ASC LIMIT $lower, $upper";
			$this->userdb->query($sql);
			$list = $this->userdb->fetchAll();
			$count = $this->userdb->fetchNumRows();
		}
		
		if($count > 0) {
			$sql = 'SELECT * FROM users WHERE ';
			$first = TRUE;
			foreach($list as $row) {
				if(!$first) {
					$sql .= "OR ";
				} else {
					$first = FALSE;
				}
				$sql .= "id = '{$row[id]}' ";
			}
			$sql .= "ORDER BY id ASC";
			
			if($searchGroup != "") {
				$this->userdb->query($sql);
				$listadd = $this->gamedb->fetchAll();
			} else {
				$this->gamedb->query($sql);
				$listadd = $this->gamedb->fetchAll();
			} 
		
			foreach($list as $key => $row) {
				$list[$key] = array_merge($listadd[$key], $row);
			}
		}
		$list['list'] = $list;
		$list['count'] = $count;
		return $list;
	}

	function fetchPermNames() {
		$sql = "SELECT `group` FROM `perm`";
		$this->gamedb->query($sql);
		return $this->gamedb->fetchAll();
	}
	
	function fetchTotalUsersSite() {
		$sql = "SELECT count(*) FROM `users`";
		$this->gamedb->query($sql);
		return $this->gamedb->fetchResult();
		
	}
	
	function fetchTotalUsersOverall() {
		$sql = "SELECT count(*) FROM `users`";
		$this->userdb->query($sql);
		return $this->userdb->fetchResult();
	}

	function deleteUsers($list) {
		if(!empty($list)) {
			foreach($list as $userId) {
				$sql = "DELETE FROM users WHERE id = '$userId'";
				$this->gamedb->query($sql);
				$sql = "DELETE FROM users WHERE id = '$userId'";
				$this->userdb->query($sql);
			}
		}
	}
}
?>