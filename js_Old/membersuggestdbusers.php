<?php 
header("Content-type: text/javascript");

//<input type="text" onfocus="actb(this,event,memberarray);" autocomplete="off" />

include("../global_includes/dbc.php");

$sql = "SELECT full_name FROM `users`";
$rs_results = query($sql);
  
while ($rrows = mysql_fetch_array($rs_results)) {
	$name = addslashes($rrows['full_name']);
	$namelist .= $name;  
	$namelist .= ',';
}


// Collect all the words to build the suggestion list out of.
$string = strtolower( $namelist );

// Separate phrases (titles) into suggestion words.
//$rawkeywords = preg_split('/\s*[\s+\.|\?|,|(|)|\-+|\'|\"|=|;|×|\$|\/|:|{|}]\s*/i', $string);
$rawkeywords = preg_split('/\s*[,]\s*/i', $namelist);

// Remove duplicates (including uc/lc).
$keywords = array_unique ( $rawkeywords );

// Sort them alphabettically.
sort( $keywords );

//Build the JavaScript array.
echo 'var memberarray = new Array( ';

foreach ($keywords as $value) {
  echo "'$value', \n"; }

echo "'' );";

?>