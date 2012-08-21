<?php
class shareClass {
	function hashNewPass($pwd) {
		$salt = $this->random_string(SALT_LENGTH);
		$hashed = $salt.sha1($pwd.$salt);
		return $hashed;
	}
}
