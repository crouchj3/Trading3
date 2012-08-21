<?php
class researchModel {
	private $rtcache_delay = 20;
	var $stockdb;
	
	
	function __construct() {
		$this->stockdb = dataClass::load("stockdb");
	}
	
	function update() {
		echo "Updating Cache...";
		flush();
		
		$sql = "SELECT `symbol` FROM `rtcache`";
		$this->stockdb->query($sql);
		$ar = array();
		while($symbol = $this->stockdb->fetchResult()) {
			$ar[] = $symbol;
		}
		$quotes = $this->getArray($ar);
		if(is_array($quotes)) {
			foreach($quotes as $key => $value) {
				$sql = "UPDATE `rtcache` SET `timestamp` = NOW(), `price` = '$value' WHERE `symbol` = '$key'";
				$this->stockdb->query($sql);
			}
			echo "Done<br />";
			flush();
		} else {
			echo "No stocks to update<br />";
			flush();
		}
	}
	
	function get($symbol) {
		if(empty($symbol)) return FALSE;
		
		$timelimit = time() - $this->rtcache_delay;
		$sql = "SELECT `price` FROM `rtcache` WHERE `symbol` = '$symbol' AND `timestamp` > '$timelimit'";
		$this->stockdb->query($sql);
		if($this->stockdb->fetchNumRows == 0) {
			$price = $this->getQuoteSingle($symbol);
		} else {
			$price = $this->stockdb->fetchResult();
		}
		return $price;
	}
	
	function getArray($symbol) {
		if(empty($symbol)) return FALSE;
		if(!is_array($symbol)) {
			$symbol = str_replace(" ","",$symbol);
			$symbol = strtoupper($symbol);
			$symbol = explode(",",$symbol);
		}
		
		$first = true;
		$cond = "";
		foreach($symbol as $value) {
			if($first == false) $cond .= " OR ";
			$first = false;
			$cond .= "`symbol` = '$value'";
		}
		
		$timelimit = time() - $this->rtcache_delay;
		$sql = "SELECT `symbol`, `price` FROM `rtcache` WHERE ($cond) AND `timestamp` > '$timelimit'";
		$this->stockdb->query($sql);
		
		$array = array();
		while(list($sym,$price) = $this->stockdb->fetchArray($result)) {
			$array[$sym] = $price;
		}
		
		$lookup = array();
		foreach($symbol as $value) {
			if(!in_array($value, $array)) {
				$lookup[] = $value;
			}
		}
		
		$result = $this->getQuoteArray($lookup);
		
		foreach($result as $key => $value) {
			if(is_numeric($value)) {
				$this->insert($key, $value);
			}
		}
		
		$return = array_merge($result, $array);
		return $return;
	}
	
	function insert($symbol,$price) {
		$sql = "INSERT INTO `rtcache` (`symbol`,`price`) VALUES ('$symbol','$price') ON DUPLICATE KEY UPDATE `price` = '$price'";
		$this->stockdb->query($sql);
	}
	
	
	private function getQuoteArray($symbol) {
		if(empty($symbol)) return FALSE;
		if(!is_array($symbol)) {
			$symbol = str_replace(" ","",$symbol);
			$symbol = strtoupper($symbol);
			$symbol = explode(",",$symbol);
		}
		
		$url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.quotes%20where%20symbol%20in%20";
		$url .= "(";
		
		$first = true;
		foreach($symbol as $value) {
			if(!$first) $url .= ",";
			else 		$first = FALSE;
			$url .= "%22".$value."%22";
		}
		$url .= ")&env=store://datatables.org/alltableswithkeys";
		
		if($file = file_get_contents($url)) {
			foreach($symbol as $value) {
				$search = '<quote symbol="'.$value.'">,<ErrorIndicationreturnedforsymbolchangedinvalid>';
				$err = $this->XMLparse($file,$search);

				if($err == FALSE) {
					$search = '<quote symbol="'.$value.'">,<LastTradePriceOnly>';
					$price[$value] = $this->XMLparse($file,$search);
					$price[$value] = $price[$value][0];
				} else {
					$price[$value] = "Invalid Ticker";
				}
			}
			return $price;
		}
	}

	private function getQuoteSingle($symbol) {
		$symbol = str_replace(" ","",$symbol);
		$symbol = strtoupper($symbol);
		
		$url = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.quotes%20where%20symbol%20in%20";
		$url .= "(";
		$url .= "%22".$symbol."%22";
		$url .= ")&env=store://datatables.org/alltableswithkeys";
		
		if($file = file_get_contents($url)) {
			$search = '<quote symbol="'.$symbol.'">,<ErrorIndicationreturnedforsymbolchangedinvalid>';
			$err = $this->XMLparse($file,$search);
			if($err[0] == FALSE) {
				$search = '<quote symbol="'.$symbol.'">,<LastTradePriceOnly>';
				$price = $this->XMLparse($file,$search);
				$price = $price[0];
			} else {
				$price = "Invalid Ticker";
			}
			return $price;
		}
	}


	private function XMLparse($xml,$search) {
		$ar = explode(',',$search);
		
		//creates an end tag array
		foreach($ar as $key => $value) {
			$end = strpos($value,' ');
			if(!($end === FALSE)) 	$endtag = substr($value,0,$end);
			else 					$endtag = $value;
			$arend[$key] = str_replace('<','</',$endtag);
		}
		
		//searches through $xml for all instances of $search
		do {
			$str = $xml;
			//loops through $ar to find the value of last tag
			foreach($ar as $key => $value) {
				$taglen = strlen($value);
				
				$begin = strpos($str,$value);
				if($begin === FALSE) {	//if tag not found
					return FALSE;
				}
				$end = strpos($str,$arend[$key],$begin);
				
				$str = substr($str,$begin + $taglen,$end - $begin - $taglen);
			}
			//Cuts out what has already been found.
			$offset = strpos($xml,$ar[0]);	//finds the offset of the beginning tag used
			$len = strpos($xml,$arend[0],$offset);	//finds the end tag from the $offset so that an used tag won't be used
			$taglen = strlen($arend[0]);
			$xml = substr($xml,$len + $taglen+1);
			
			//stores into $res
			$res[] = $str;
			
			$check = strpos($xml,$ar[0]);	//check if first tag still exists in rest of $xml
			if($check === FALSE) break;
		} while(1);
		
		return $res;
	}
}
?>