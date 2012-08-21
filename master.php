<?php
define("SITEROOT","/home/docjay/public_html/one/");
define("PAGESROOT", "modules/");
define("CLASSROOT", "objects/");
define("VIEWROOT", "view/");
define("SITEHOME","/s/home/index");
define("SITENAME", "one");
define("SALT_LENGTH", 9);
define("SITELOGIN", "/s/account/login");
define("DENY", "/s/error/deny");
define("NOTFOUND", "/s/error/404");
			
include(SITEROOT.CLASSROOT."load.php");

load::object("view");
load::object("data");
load::object("query");
load::object("auth");
load::object("share");

session_start();

function outputArray($array) {
	echo "<pre>";
	var_dump($array);
	echo "</pre>";
}

class Controller {
	//use by Controller
	private $control;
	
	//use by child class
	//objects
	var $auth;
	var $model;
	private $view;
	
	//var
	private $otherIncludes;
	private $viewStr;
	private $contentVar;
	private $showTemplate;
	
	function __construct() {
		$this->explodeURI();
		$section = $_GET['section'];
		$page = $_GET['page'];
		
		load::page($section, $page);
		$this->control = new $_GET['page']();
		$this->control->control();
		$this->control->view();
	}
	
	function Controller() {
		$this->otherIncludes = array();
		$this->contentVar = array();
		
		$this->showTemplate = true;
		$this->auth = new authClass();
		
		$model = $_GET['page'].'Model';
		if(class_exists($model)) $this->model = new $model();
		
		$this->assign("isLoggedIn", $this->auth->isLoggedIn());
		$this->assign("adminMenu", $this->auth->checkPerm("adminmenu"));
		$this->assign("menuPerm", $this->auth->checkPerm("permissions"));
		$this->assign("menuSettings", $this->auth->checkPerm("sitesettings"));
		$this->assign("menuUsers", $this->auth->checkPerm("siteusers"));
		$this->assign("menuUpdateGame", $this->auth->checkPerm("gameupdater"));
	}
	
	function view() {
		if($this->viewStr != NULL) {
			$this->view = new viewClass($_GET['section'], $this->viewStr, $this->showTemplate, $this->contentVar, $this->otherIncludes);
		}
	}
	
	function control() {
	}
	
	function assign($name, $contents) {
		if($name == 'content') {
			$this->viewStr = $contents;
		} else if ($name == 'showTemplate') {
			$this->showTemplate = $contents;
		} else if ($name == 'otherIncludes') {
			$this->otherIncludes[] = $contents;
		} else {
			$this->contentVar[$name] = $contents;
		}
	}
	
	private function explodeURI() {
		$uri = explode('/', $_SERVER['REQUEST_URI']);
		$last = NULL;
		foreach($uri as $value) {
			if($last != NULL) {
				$_GET[$last] = $value;
			}
			$last = $value;
		}
		$_GET['section'] = ($uri[2]) ? strtolower($uri[2]) : 'home';
		$_GET['page'] = ($uri[3]) ? preg_replace('/\?.*$/', '', strtolower($uri[3])) : 'index';
	}
	
}
$instance = new Controller;
?>