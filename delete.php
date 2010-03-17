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

$query_update = "DELETE FROM `".$main_table."` WHERE `id`='".$id."'";
mysql_query($query_update);

$query_confirm = "SELECT * FROM `".$main_table."` WHERE `id`='".$id."'";
mysql_query($query_confirm);
$num_rows = @mysql_num_rows($mysql_query);
if($num_rows == "" || $num_rows == "0"){
    echo 'success';
} else {
    echo 'failure';
}

?>