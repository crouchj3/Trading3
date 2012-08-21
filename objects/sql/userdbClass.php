<?php
	class userdbClass {
		var $db;
		function __construct() {
			$this->db = dataClass::load("userdb");
			$this->usersTable();
			echo "Users Table Created <br />";
			$this->activationTable();
			echo "Activation Table Created <br />";
		}
		
		function activationTable() {
			$sql = "CREATE TABLE IF NOT EXISTS `activation` (
					`id` bigint(12) NOT NULL auto_increment,
					`userId` bigint(12) NOT NULL,
					`email` varchar(220) NOT NULL,
					`code` varchar(220) NOT NULL,
					PRIMARY KEY(`id`),
					KEY `code` (`code`),
					KEY `email` (`email`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
					";
			$this->db->query($sql);
		}
		
		function usersTable() {
			$sql = "CREATE TABLE IF NOT EXISTS `users` (
				  `id` bigint(12) NOT NULL,
				  `display_name` varchar(220) COLLATE latin1_general_ci NOT NULL,
				  `user_email` varchar(220) COLLATE latin1_general_ci NOT NULL DEFAULT '',
				  `pwd` varchar(220) COLLATE latin1_general_ci NOT NULL DEFAULT '',
				  `date` date NOT NULL DEFAULT '0000-00-00',
				  `activated` int(1) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`),
				  UNIQUE KEY `user_email` (`user_email`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
				";
			$this->db->query($sql);
			$sql ="INSERT INTO `users` VALUES (728712125, 'Renjay', 'renjay@umich.edu', 'f75a77174ac8747f6006915fccfcd9ef30273502f472aeb2b', '2012-07-07', 1);";
			$this->db->query($sql);
			/*
			registerClass::registerUser("Tester","tester@renjar.com","tester","tester", false);
			registerClass::registerUser("Ringil","ringil.sword@gmail.com","111111","111111", false);
			registerClass::registerUser("Kdroge","kurtisroxs@gmail.com","kdroge","kdroge", false);
			 * 
			 */
		}
	}
?>