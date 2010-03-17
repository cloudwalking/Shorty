<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

if (!isset ($_SESSION)) session_start();

require("functions.php");
require("configuration.php");

connect();

if(isset($_GET['post'])){
	$_SESSION['posturl'] = $_GET['post'];
}

$user_id = @getUserID();
$query_user = "SELECT * FROM `".$user_table."` WHERE `id`='".$user_id."'";
$result_user = @mysql_query($query_user);
$userA = @mysql_fetch_array($result_user);

if(!$userA['theme']){
  $userA['theme'] = 'originhal';
}

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
   <meta name="generator" content="HTML Tidy for Mac OS X (vers 1st December 2004), see www.w3.org" />
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   <title>Shorty - Beta</title>
   <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
   <link type="text/css" rel="stylesheet" href="skin/'.$userA['theme'].'/style.css" />
   <script src="js/prototype.js" type="text/javascript"></script>
   <script src="js/shorty.js" type="text/javascript"></script>
</head>

<body>

   <div id="brand">
      <h1>Shorty</h1>
   </div>

   <div id="menu">
      <ul>
         <li id="loginOption"><a href="login.php" class="selected">Login</a></li>
         <li id="homeOption"><a href="home.php">Home</a></li>
         <li id="adminOption"><a href="admin.php">Admin</a></li>
         <li id="prefsOption"><a href="prefs.php">Prefs</a></li>
         <li id="aboutOption"><a href="credits.php">About</a></li>
         <li id="donateOption"><a href="donate.php">Donate</a></li>
         <li id="logoutOption"><a href="logout.php">Log-out</a></li>
      </ul>
   </div>
   
   <div id="beta">
         <ul>
            <li><a href="http://get-shorty.com/forum/">Beta Forum</a></li>
         </ul>
  </div>

   <div id="board">
        <noscript><p>Shorty requires a javascript enabled Browser other than Internet Explorer.</p></noscript>
   </div>
   
   <div id="legal">
      Copyright 2006 by Khoi Vinh, Reed Morse | <a href="http://get-shorty.com">Get-Shorty.com</a> | v'.$version.'
   </div>

</body>

</html>
';

?>