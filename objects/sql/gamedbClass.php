<?php
class gamedbClass {
	var $db;
	function __construct() {
		$this->db = dataClass::load("gamedb");
		$this->settingsTable();
		echo "Settings Table Created<br />";
		$this->permListTable();
		echo "Perm Descript Table Created<br />";
		$this->permTable();
		echo "Permissions Table Created<br />";
		$this->usersTable();
		echo "Site Users Table Created<br />";
	}
	
	function usersTable() {
		$sql = "CREATE TABLE IF NOT EXISTS `users` (
					`id` bigint(20) NOT NULL,
					`group` varchar(220) NOT NULL,
				  	`mailinglist` tinyint(1) NOT NULL DEFAULT '1',
					PRIMARY KEY (`id`),
					KEY `group` (`group`),
					KEY `mailinglist` (`mailinglist`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			";
		$this->db->query($sql);
		$sql = "INSERT INTO `users` VALUES (728712125, 'admin', 1);";
		$this->db->query($sql);
	}
	
	function settingsTable() {
		$sql = "CREATE TABLE IF NOT EXISTS `settings` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `longname` varchar(220) NOT NULL,
				  `name` varchar(220) NOT NULL,
				  `value` text NOT NULL,
				  `type` ENUM('select', 'text') NOT NULL,
				  `optionnames` varchar(220) NOT NULL,
				  `options` varchar(220) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;
				";
		$this->db->query($sql);
		$sql = "INSERT INTO `settings` (`id`, `longname`, `name`, `value`, `type`, `optionnames`, `options`) VALUES
				(2, 'User Registration', 	'user_reg', 	'user', 						'select', 	'user,admin', 'user,admin'),
				(3, 'Enabled Registration', 'endi_reg', 	'disabled', 					'select', 	'enabled,disabled', 'enabled,disabled'),
				(4, 'Site Email', 			'site_email', 	'admin@renjar.com', 			'text', 	'', ''),
				(5, 'Site Email Name', 		'email_name', 	'Renjar', 						'text', 	'', ''),
				(6, 'Hosting Service URL', 	'hosturl', 		'http://www.renjar.com/cpanel', 'text', 	'', ''),
				(7, 'PhpMyAdmin', 			'phpmyadmin', 	'https://gator237.hostgator.com:2083/3rdparty/phpMyAdmin/index.php?db=docjay_keyapp&token=37fa2ba9e302b72cd6b83720e100334d', 'text', '', ''),
				(8, 'Organization Name', 	'orgname', 		'Renjar', 						'text', 	'', ''),
				(9, 'Org Shorthand', 		'shorthand', 	'Renjar', 						'text', 	'', '');
				";
		$this->db->query($sql);
	}
		
	public function permListTable() {
		$sql = "CREATE TABLE IF NOT EXISTS `permList` (
				  `id` bigint(4) NOT NULL,
				  `fullname` varchar(220) NOT NULL,
				  `name` varchar(220) NOT NULL,
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `fullname` (`fullname`),
				  UNIQUE KEY `name` (`name`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;
				";
		$this->db->query($sql);
		$sql = "INSERT INTO `permList` (`id`, `fullname`, `name`) VALUES
				(0, 'Login', 'login'),
				(1, 'Edit Permissions', 'permissions'),
				(2, 'Site Users', 'siteusers'),
				(3, 'Site Settings', 'sitesettings');";
		$this->db->query($sql);
	}
	
	public function permTable() {
		$sql = "CREATE TABLE IF NOT EXISTS `perm` (
				  `id` bigint(20) NOT NULL AUTO_INCREMENT,
				  `group` varchar(220) NOT NULL,
				  `perm` varchar(220) NOT NULL DEFAULT '00000000000000000000000000',
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `group` (`group`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;";
		$this->db->query($sql);
		$sql = "INSERT INTO `perm` VALUES
				(1, 'admin', '11111'),
				(2, 'guest', '00000'),
				(3, 'user', '1000');";
		$this->db->query($sql);
	}
}
?>