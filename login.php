<?php session_start();

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

require('functions.php');

if(verify()){
	$action = (!isset ($_SESSION['action'])) ? 'home' : $_SESSION['action'] ;

	switch ($action){
		case 'home':
		  header("Location:home.php");
		  break;
		case 'admin':
		  header("Location:admin.php");
		  break;
		case 'prefs' :
		  header("Location:prefs.php");
		  break;
		case 'about' :
		  header("Location:credits.php");
		  break;
		case 'donate' :
  		  header("Location:donate.php");
  		  break;   
		default:
		  break;
	}
}

$notice = (isset($_GET['failed']))? '<p class="notice">Authentication failed.</p>' : '';
$fold = (isset($_GET['logout']))? 'menu.fold();' : '';

$news = news();

echo '
<div id="welcome">
<h1>Welcome</h1>
<p>Shorty is a tool for creating shorter, human-readable links from long URLs.</p>
</div>

<div id="login">
<h2> Start Using Shorty</h2>
'.$notice.'
<form name="loginForm" method="post" action="login.php" onsubmit="return false;">
<label for="username">Name</label><input type="text" name="username" id="username" />
<label for="password">Password</label><input type="password" name="password" id="password" />
<a id="forgot-link" href="forgot.php" onclick="forward(\'forgot.php\');return false;">Forgot Your Password?</a>
<input type="submit" name="submit" value="LOGIN" class="submitBtn" onclick="login();return false;" />
</form>
</div>

<div id="news">
'.$news.'
</div>

<script type="text/javascript" charset="utf-8">
    // <![CDATA[
    '.$fold.'
    // ]]>
</script>
';
?>