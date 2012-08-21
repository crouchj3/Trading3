<?php
class tradedbClass {
	var $db;
	function __construct() {
		$this->db = dataClass::load("tradedb");
		$this->gameTradeTable();
		echo "Trade Table Created<br />";
		$this->gamePortfolioTable();
		echo "Portfolio History Table Created<br />";
		$this->gameUserTable();
		echo "Game User Table Created<br />";
		$this->compList();
		echo "Competitions List Table Created<br />";
	}
		
	//game_trade
	public function gameTradeTable() {
		$sql="CREATE TABLE `game_trade` (
			  `id` bigint(4) NOT NULL auto_increment,
			  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `player_id` bigint(4) NOT NULL,
			  `game_id` bigint(4) NOT NULL,
			  `type` ENUM('buy', 'limitbuy', 'sell', 'limitsell', 'stopsell') NOT NULL,
			  `ticker` varchar(220) NOT NULL,
			  `price` decimal(20,2) NOT NULL,
			  `quant` int(4) NOT NULL,
			  `status` ENUM('pending','cancelled','executed') NOT NULL,
			  `enabled` tinyint(2) NOT NULL,
			  PRIMARY KEY  (`id`), 
			  KEY `timestamp` (`timestamp`),
			  KEY `player_id` (`player_id`),
			  KEY `game_id` (`game_id`),
			  KEY `status` (`status`),
			  KEY `enabled` (`enabled`)
			) ENGINE=InnoDb DEFAULT CHARSET=utf8;
			";
		$this->db->query($sql);
		$sql = "ALTER TABLE `game_trade` ORDER BY  `timestamp` DESC";
		$this->db->query($sql);
	}
	
	//game_history
	public function gamePortfolioTable() {
		$sql = "CREATE TABLE `game_history` (
				`id` bigint(4) NOT NULL auto_increment,
				`player_id` bigint(4) NOT NULL,
				`game_id` bigint(4) NOT NULL,
				`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`value` decimal(20,2) NOT NULL,
				`enabled` tinyint(2) NOT NULL,
				PRIMARY KEY (`id`),
				KEY `player_id` (`player_id`),
				KEY `timestamp` (`timestamp`),
				KEY `game_id` (`game_id`),
				KEY `enabled` (`enabled`)
				) ENGINE=InnoDb DEFAULT CHARSET=utf8;
				";
		$this->db->query($sql);
	}
	//game_user
	public function gameUserTable() {
		$sql="CREATE TABLE `game_user` (
				  `id` bigint(4) NOT NULL auto_increment,
				  `player_id` bigint(4) NOT NULL, 
				  `game_id` bigint(4) NOT NULL,
				  `cash_remaining` decimal(20,2) NOT NULL,
				  `portfolio_value` decimal(20,2) NOT NULL,
				  `enabled` tinyint(2) NOT NULL,
				  PRIMARY KEY  (`id`),
				  KEY `player_id` (`player_id`),
				  KEY `game_id` (`game_id`),
				  KEY `portfolio_value` (`portfolio_value`),
				  KEY `enabled` (`enabled`)
				) ENGINE=InnoDb DEFAULT CHARSET=utf8;
			";
		$this->db->query($sql);
	}
		
	public function compList() {
		$sql = "CREATE TABLE IF NOT EXISTS `comp_list` (
				  `id` bigint(4) NOT NULL AUTO_INCREMENT,
				  `name` varchar(220) NOT NULL,
				  `description` varchar(220) NOT NULL,
				  `start_date` date NOT NULL,
				  `end_date` date NOT NULL,
				  `entry_fee` decimal(20,2) NOT NULL,
				  `joinable_post_start` tinyint(1) NOT NULL,
				  `public_game` tinyint(1) NOT NULL,
				  `password` varchar(220) NOT NULL,
				  `start_balance` decimal(20,2) NOT NULL,
				  `commission` decimal(20,2) NOT NULL,
				  `max_portfolio_percent` tinyint(4) NOT NULL,
				  `short_sell` tinyint(1) NOT NULL,
				  `limit_order` tinyint(1) NOT NULL,
				  `stop_loss` tinyint(1) NOT NULL,
				  `admin` bigint(4) NOT NULL,
				  `enabled` tinyint(2) NOT NULL,
				  `started` tinyint(2) NOT NULL,
			 	  PRIMARY KEY  (`id`),
				  KEY `name` (`name`),
				  KEY `admin` (`admin`),
				  KEY `enabled` (`enabled`),
				  KEY `started` (`started`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
				";
		$this->db->query($sql);
	}
}
?>