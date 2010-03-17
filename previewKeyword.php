<?php session_start();

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

if(isset($_SESSION['posturl'])){
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
       setcookie(session_name(), '', time()-42000, '/');
    }
    session_destroy();
}

require("functions.php");
require("configuration.php");

connect();
authenticate();

$domain = @ereg_replace("\.[a-z]+", "", parse_domain($_GET['url']));

if($domain != '') {
  echo $domain . '/';
}
?>