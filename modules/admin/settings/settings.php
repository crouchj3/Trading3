<?php
class settings extends Controller {
	function __construct() {
		$this->Controller();
	}
	
	function control() {
		$this->auth->bounce("sitesettings");
	
		if(isset($_POST['submit'])) {
			function notSubmit($var) {
				if($var == "Update") 	return FALSE;
				else 					return TRUE;
			}
			$_POST = array_filter($_POST,"notSubmit");		
			
			foreach($_POST as $key => $value) {
				$this->model->changeSetting($key, $value);
			}
			$msg[] = "Settings Saved";
			$this->assign("msg", $msg);
		}
		
		$this->assign("settings", $this->model->getAll());
		$this->assign("content", "settings");
	}
}
?>