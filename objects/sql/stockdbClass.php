<?php
class stockdbClass {
	var $db;
	function __construct() {
		$this->db = dataClass::load("stockdb");
		$this->gameStocksTable();
		echo "Game Stocks Table Created<br />";
		$this->initRTCache();
		echo "Stock Cache Table Created<br />";
	}
	//game_stocks
	public function gameStocksTable() {
		$sql = "CREATE TABLE `game_stocks` (
				`id` bigint(4) NOT NULL auto_increment,
				`game_id` bigint(4) NOT NULL,
				`player_id` bigint(4) NOT NULL,
				`ticker` varchar(220) NOT NULL,
				`avgprice` decimal (20,2) NOT NULL,
				`quant` int(4) NOT NULL,
				`enabled` tinyint(2) NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `check` (`game_id`, `player_id`, `ticker`),
				KEY `player_id` (`player_id`),
				KEY `game_id` (`game_id`),
				KEY `enabled` (`enabled`)
				)";
		$this->db->query($sql);		
	}	
	
	//rtcache
	public function initRTCache() {
		$tbl = "rtcache";
		$sql = "
			CREATE TABLE `$tbl` (
				`id` bigint(4) NOT NULL auto_increment,
				`symbol` varchar(220) NOT NULL,
				`timestamp` timestamp ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`price` decimal(20,2) NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `symbol` (`symbol`),
				KEY `timestamp` (`timestamp`)
			) ENGINE = InnnoDB DEFAULT CHARSET = utf8;";
		$this->db->query($sql);
		$sql = "ALTER TABLE `$tbl` ORDER BY  `symbol` DESC";
		$this->db->query($sql);
	}
	
}
?>