<?php
class users extends Controller {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("siteusers");
		
		if($_POST['cmd'] == "edit") {
			$this->model->editUser($_POST['group'], $_POST['display_name'], $_POST['user_email'], $_POST['id'], $_POST['password']);
			exit();
		}
		
		if($_POST['submit'] == "Search") {
			$this->assign("users", $this->model->getUsers($_POST['pagesize'], $_POST['page'], $_POST['search'], $_POST['group']));	
		} else if($_POST['submit'] == "Delete") {
			$this->model->deleteUsers($_POST['u']);
		}
		$this->assign("groupnames", $this->model->fetchPermNames());
		$this->assign("numUserSite", $this->model->fetchTotalUsersSite());
		$this->assign("numUserOverall", $this->model->fetchTotalUsersOverall());
		
		if(isset($_SESSION['msg'])) {
			$msg[] = $_SESSION['msg'];
			unset($_SESSION['msg']);
		}
		$this->assign("msg", $msg);
		
		$this->assign("content", "users");
	}
}
?>