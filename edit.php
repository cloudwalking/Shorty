<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

require("functions.php");
require("configuration.php");

connect();

$id = $_GET['id'];

$query_shorties = "SELECT * FROM `".$main_table."` WHERE `id`='".$id."' ORDER BY `id` ASC";
$result_shorties = @mysql_query($query_shorties);
$array = mysql_fetch_array($result_shorties);

echo '
<tr><td colspan="4" class="editForm"><input type="text" value="'.$array['target'].'" /></td></tr>
';

?>