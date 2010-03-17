<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

require("functions.php");

$cookie = @$_COOKIE['snickerdoodle'];
if($cookie == "polarbears"){
	setcookie('snickerdoodle', '', time()-60*60*24*365*2, '/');
} else {
	//
}

$anotherCookie = @$_COOKIE['usename'];
if($anotherCookie){
	setcookie('usename', '', time()-60*60*24*365*2, '/');
} else {
	//
}

header("Location:login.php?logout");

?>