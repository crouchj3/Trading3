<?php
class load {
	function object($object) {
		include_once(CLASSROOT.$object."Class.php");
	}
	function page($section, $page) {
		$control = PAGESROOT.$section."/".$page."/".$page.".php";
		$model = PAGESROOT.$section."/".$page."/".$page."Model.php";
		$failed = false;
		
		$moduleClass = PAGESROOT.$section."/".$section.".php";
		$moduleModelClass = PAGESROOT.$section."/".$section."Model.php";
		
		if(file_exists($moduleClass)) require_once($moduleClass);
		if(file_exists($moduleModelClass)) require_once($moduleModelClass);
		
		if(file_exists($control)) {
			require_once($control);
		} else {
			die("Not Found: $control");
			$failed = true;
		}
		
		if(file_exists($model)) {
			require_once($model);
		} else {
			die("Not Found: $model");
			$failed = true;
		}
		
		if($failed) {
			header("Location: /s/error/notFound");
		}
	}
}
?>