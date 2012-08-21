<?php
class permModel {
	var $gamedb;
	function __construct() {
		$this->gamedb = dataClass::load("gamedb");
	}
	
	function changePerm($id) {
		$pos = strpos($id,"_");
		$groupid = substr($id,0,$pos);
		$permid = substr($id,$pos + 1);
		
		$sql = "SELECT count(*), perm FROM perm WHERE id = '$groupid'";
		$this->gamedb->query($sql);
		list($count, $perm) = $this->gamedb->fetchArray();
		if($count == 0)	return FALSE;
		
		if($perm[$permid] == 0) {
			$perm[$permid] = 1;
		} else {
			$perm[$permid] = 0;
		}
		
		$sql = "UPDATE perm SET perm = '$perm' WHERE id='$groupid'";
		$this->gamedb->query($sql);
		
		echo "Saved";
		exit();
	}
	
	function deletePerm($id) {
		$sql = "DELETE FROM `permList` WHERE `id` = '$id'";
		$this->gamedb->query($sql);
		$_SESSION['msg'] = "Permission Deleted";
		
		//should switch all permissions to 0
	}
	
	function deleteGroup($id) {
		$sql = "DELETE FROM `perm` WHERE `id` = '$id'";
		$this->gamedb->query($sql);
		$_SESSION['msg'] = "Group Deleted";
		//should move all members in group to user
	}
	
	function createGroup($group) {
		$sql = "SELECT * FROM perm WHERE `group` = '$group'";
		$this->gamedb->query($sql);
		if($this->gamedb->fetchNumRows() > 0) {
			return "Group Already Exists";
		}
		
		$sql = "INSERT INTO perm VALUES (NULL, '$group', '')";
		$this->gamedb->query($sql);
		
		$this->extend();
		
		return "Group Created";
	}
		
	private function extend() {
		$sql = "SELECT * FROM `permList`";
		$this->gamedb->query($sql);
		$rows = $this->gamedb->fetchNumRows();
	
		$sql = "SELECT `id`,`perm` FROM `perm`";
		$this->gamedb->query($sql);
		$result = $this->gamedb->fetchAll();

		foreach($result as $row) {
			list($id,$perm) = $row;
			while(strlen($perm) < $rows) {
				$perm .= "0";
			}

			$sql = "UPDATE perm SET perm = '$perm' WHERE id = '$id'";
			$this->gamedb->query($sql);
		}
	}
	
	function createPerm($fullname,$name) {
		$sql = "SELECT * FROM permList WHERE name = '$name'";
		$this->gamedb->query($sql);
		if($this->gamedb->fetchNumRows > 0) return "Failed to Add Permission";
		
		$sql = "SELECT id FROM permList ORDER BY id ASC";
		$this->gamedb->query($sql);
		$permList = $this->gamedb->fetchAll();
		$last = $permList[0]['id'] - 1;
		foreach($permList as $row) {
			if($row['id'] - 1 != $last) {
				break;
			}
			$last = $row['id'];
		}
		$i = $last + 1;
		$sql = "INSERT INTO `permList` (`id`,`fullname`,`name`) VALUES ('$i','$fullname','$name')";
		$this->gamedb->query($sql);
		
		$this->extend();
		
		return "Permission Added";
	}
	
	function getPermGroups() {
		$group = array();
		
		$sql = "SELECT * FROM `perm`";
		$this->gamedb->query($sql);
		while($ar = $this->gamedb->fetchArray()) {
			$group['perm'][$ar['id']] = $ar['perm'];
			$group['name'][$ar['id']] = $ar['group'];
		}
		return $group;
	}
	
	function getPermName() {
		$permname = array();
		$sql = "SELECT * FROM permList";
		$this->gamedb->query($sql);
		while($ar = $this->gamedb->fetchArray()) {
			$permname[$ar[id]] = $ar['fullname']." (".$ar['name'].")";
		}
		return $permname;
	}
}
?>