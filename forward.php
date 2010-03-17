<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

require("functions.php");
require("configuration.php");

connect();

// Fetch Keys
$keys = explode("/", $_SERVER['REQUEST_URI']);

// Fetch URL
$query = "SELECT * FROM `".$main_table."` WHERE `key1`='".$keys['2']."' AND `key2`='".$keys['3']."' AND `key3`='".$keys['4']."' AND `key4`='".$keys['5']."' AND `key5`='".$keys['6']."'";
$result = mysql_query($query);
$array = mysql_fetch_array($result);


// Update click-throughs
$array['clicks-aug'] = $array['clicks']+1;
$query_update_clicks = "UPDATE `".$main_table."` SET `clicks`='".$array['clicks-aug']."' WHERE `id`='".$array['id']."'";
mysql_query($query_update_clicks);


// Forward
header("Location:".$array['target']);


?>