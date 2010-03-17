<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

require("functions.php");
require("configuration.php");

connect();
authenticate();

$id = $_GET['id'];

foreach($_POST as $key => $value){ // Thanks Tynan Smith!
	$key .= "=";
	$value .= "&";
	$string .= $key.$value;
}

$string = ereg_replace("newURL=", "", $string);
$string = ereg_replace("&_=&", "", $string);
$newURL = $string;

$query_update = "UPDATE `".$main_table."` SET `target`='".$newURL."', `day`='".date('j')."', `month`='".date('n')."', `year`='".date('Y')."' WHERE `id`='".$id."'";
mysql_query($query_update);

$query_confirm = "SELECT * FROM `".$main_table."` WHERE `target`='".$newURL."'";
$result_confirm = mysql_query($query_confirm);
$num_rows = @mysql_num_rows($result_confirm);
if($num_rows !== "" && $num_rows !== "0"){
    echo 'success';
} else {
    echo 'failure';
}

?>