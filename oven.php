<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

require("functions.php");
require("configuration.php");

connect();

$username = $_POST['username'];
$password = $_POST['password'];

$query_confirm = "SELECT * FROM `".$user_table."` WHERE `username`='".$username."'";
$result_confirm = mysql_query($query_confirm);
$numrows = @mysql_num_rows($result_confirm);
$user = @mysql_fetch_array($result_confirm);

$pass_md5 = md5($password);

if(!($numrows == 0) && !($numrows == "") && $pass_md5 == $user['password']){
	setcookie('snickerdoodle', 'polarbears', time()+60*60*24*365, '/');
	setcookie('usename', $user['id'], time()+60*60*24*365, '/');
	header("Location: home.php");
} else {
  header("Location: login.php?failed");
    // echo '<div id="fail"><p>Authentication failed. Please refresh page.</p></div>';
}

?>