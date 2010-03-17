<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

session_start();
$_SESSION['action'] = 'prefs';

require("functions.php");
require("configuration.php");

connect();
authenticate();

$user_id = getUserID();

$query_confirm = "SELECT * FROM `".$user_table."`";
$result_confirm = mysql_query($query_confirm);
$numrows = @mysql_num_rows($result_confirm);
$user = @mysql_fetch_array($result_confirm);
// I'm not sure why there are two of essentially the same thing, but the second will be better with multiple users
$query_user = "SELECT * FROM `".$user_table."` WHERE `id`='".$user_id."'";
$result_user = mysql_query($query_user);
$userA = mysql_fetch_array($result_user);

// Styles
$styles = opendir("./skin");
while($file = readdir($styles)){
	$styleArray[] = $file;
}
$styleCount = sizeof($styleArray);

echo '
  <img id="main-spinner" src="skin/'.$user['theme'].'/spinner.gif" />

	<div id="prefs">
	<h2>Preferences</h2>
  <div class="pane">
	<form id="userInfo" action="setprefs.php" method="post" onsubmit="return false">
	<fieldset><label for="username"><input type="text" name="username" id="username" value="'.$user['username'].'" />Login Name</label>
	<label for="email"><input type="text" name="email" id="email" value="'.$user['email'].'" />Email</label>
	<label for="theme"><select name="theme" id="theme">';
	
	for($i = 0; $i <= $styleCount-1; $i++){
		if($styleArray[$i][0] !== "."){
			if($userA['theme'] == $styleArray[$i]){
				$sel = ' selected';
			} else {
				$sel = '';
			}
			echo '<option value="'.$styleArray[$i].'"'.$sel.'>'.$styleArray[$i].'</option>';
		}
	}
	
	echo '</select>Theme</label></fieldset>
	<input type="submit" name="submit" value="SAVE PREFS" id="prefsBtn" class="submitBtn" />
	</form>
	
	<form id="changePassword" action="setprefs.php" method="post" onsubmit="return false">
  	<fieldset><label for="old"><input type="password" name="old" id="old" />Old Password</label>
    <label for="new1"><input type="password" name="new1" id="new1" />New Password</label>
    <label for="new2"><input type="password" name="new2" id="new2" />Again</label></fieldset>
    <input type="submit" name="submit" value="CHANGE" id="changePasswordButton" class="submitBtn" />
	</form>
	</div>
	</div>
</div>
    
    <script type="text/javascript" charset="utf-8">
        // <![CDATA[
        menu.unfold("prefsOption");
        buttonCollector.init("prefs");
        // ]]>
    </script>

';

?>