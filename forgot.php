<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

require("functions.php");
require("configuration.php");

connect();

$username = strtolower(@$_GET['username']);

$sent = @$_GET['sent'];

$query_confirm = "SELECT * FROM `".$user_table."` WHERE `username`='".$username."'";
$result_confirm = mysql_query($query_confirm);
$numrows = @mysql_num_rows($result_confirm);
$user = @mysql_fetch_array($result_confirm);

$new = rand(1000000, 999999999);

if($sent == "yes"){
    if(!$user){ exit('<p class="white">You don\'t exist.</p>'); }
	$passmd5 = md5($new);
	$query = "UPDATE `".$user_table."` SET `password`='".$passmd5."' WHERE `username`='".$username."'";
	$result = mysql_query($query);
	
	$to = $user['email'];
	$headers  = 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
	$headers .= 'From: Shorty Robot <robot@get-shorty.com>'."\r\n"; //This header may be lousy
	$subject = 'New Shorty Password';
	$body = 'Here\'s your new password:<br /><br />'.$new.'<br /><br />You should probably change your password to something a little easier to remember. You can do so via the Preferences pane.';
	mail($to, $subject, $body, $headers);
	echo '<p class="white">Your password has been reset, and a new one has been emailed to you.</p>';
} else {
    echo '
    <div id="forgotPassword">
	    <form name="forgotForm" id="forgotForm" method="post" action="forgot.php?sent=yes" onsubmit="return false">
    	<label for="username" class="white">Name </label><input type="text" name="username" id="username" />
    	<input type="submit" name="submit" value="REMIND ME" id="username" class="submitBtn" />
    	</form>
    </div>
    <script type="text/javascript" charset="utf-8">
        // <![CDATA[
        menu.fold();
        buttonCollector.init("forgotPassword");
        // ]]>
    </script>
';
}

?>