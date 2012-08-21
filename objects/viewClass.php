<?php
class viewClass {
	function __construct($section, $page, $showTemplate, $var, $otherInclude) {
		$view = VIEWROOT.$section.'/'.$page.'.php';
		foreach($var as $key => $value) {
			$$key = $value; 
		}
		if($showTemplate) {
			include("includes/header.php");
			include("includes/msg.php");
		}
		foreach($otherInclude as $otherPage) {
			include(VIEWROOT.$section.'/'.$otherPage.'.php');
		}
		if(file_exists($view)) include_once($view);
		if($showTemplate) {
			include("includes/footer.php");
		}
	}
}
?>