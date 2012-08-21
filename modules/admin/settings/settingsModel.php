<?php
class settingsModel {
	var $gamedb;
	
	function __construct() {
		$this->gamedb = dataClass::load("gamedb");
	}
	
	function getAll() {
		$sql = "SELECT * FROM `settings`";
		$this->gamedb->query($sql);	
		
		$ar = array();
		while($rs = $this->gamedb->fetchArray()) {
			$rs['options'] = explode(",",$rs['options']);
			$rs['optionnames'] = explode(",",$rs['optionnames']);
			switch ($rs['type']) {
				case 'text':
					$ar[] = "<label for=\"".$rs['name']."\">".$rs['longname']."</label>
							<input type=\"text\" name=\"".$rs['name']."\" id=\"".$rs['name']."\" value=\"".$rs['value']."\" />";
					break;
				case 'select':
					$str = "<label for=\"".$rs['name']."\">".$rs['longname']."</label>
							<select name=\"".$rs['name']."\" id=\"".$rs['name']."\">";
					foreach($rs['options'] as $key => $value) {
						$str .= "<option id=\"".$value."\" value=\"".$value."\" ";
						if($rs['value'] == $value) {
							$str .= "selected=\"selected\"";
						}
						$str .= ">";
						$str .= $rs['optionnames'][$key]."</option>";
					}
					$str .= "</select>";
					$ar[] = $str;
					break;
			}
		}
		
		return $ar;
	}


	function changeSetting($setting,$value) {
		$sql = "SELECT COUNT(*) FROM `settings` WHERE `name` = '$setting'";
		$this->gamedb->query($sql);
		if($this->gamedb->fetchResult() == 0) return FALSE;
		
		$sql = "UPDATE `settings` SET `value` = '$value' WHERE `name` = '$setting'";
		$this->gamedb->query($sql);
		return TRUE;
	}
}
?>