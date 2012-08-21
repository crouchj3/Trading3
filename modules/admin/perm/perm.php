<?php
class perm extends Controller {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("permissions");
		
		if($_POST['cmd'] == 'change') {
			$this->model->changePerm($_POST['id']);  //exits
		}
		
		if($_GET['cmd'] == 'permDelete') {
			$this->model->deletePerm($_GET['permDelete']);
			header("Location: /s/admin/perm");
			exit();
		} else if($_GET['cmd'] == 'groupDelete') {
			$this->model->deleteGroup($_GET['groupDelete']);
			header("Location: /s/admin/perm");
			exit();
		}
		
		if(isset($_SESSION['msg'])) {
			$msg[] = $_SESSION['msg'];
			unset($_SESSION['msg']);
		}
		
		if($_POST['submit'] == 'Create') {
			$msg[] = $this->model->createGroup($_POST['group']);
		} else if($_POST['submit'] == 'Add') {
			$msg[] = $this->model->createPerm($_POST['fullname'], $_POST['name']);
		}
		$this->assign("msg", $msg);
		$this->assign("sitename", SITENAME);
		$group = $this->model->getPermGroups();
		$this->assign("groupperm", $group['perm']);
		$this->assign("groupname", $group['name']);
		$this->assign("permname", $this->model->getPermName());
		$this->assign("content", "permissions");
	}
}
?>