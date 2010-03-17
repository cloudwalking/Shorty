<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

require("functions.php");
require("configuration.php");

connect();
authenticate();

$user_id = getUserID();

if(isset($_POST['pref'])){ $pref = $_POST['pref']; }
if(isset($_POST['value'])){ $value = $_POST['value']; }

if(isset($pref) && isset($value)){
    $query = "UPDATE `".$user_table."` SET `".$pref."`='".$value."' WHERE `id`='".$user_id."'";
	mysql_query($query);
//	echo $query;
	
	$query_confirm = "SELECT * FROM `".$user_table."` WHERE `".$pref."`='".$value."' AND `id`='".$usenam."'";
    mysql_query($query_confirm);
    $num_rows = @mysql_num_rows($mysql_query);
    if($num_rows !== "" && $num_rows !== "0"){
        echo 'success';
    } else {
        echo 'failure';
    }
}

if(isset($_POST['old'])){ $old = $_POST['old']; }
if(isset($_POST['new1'])){ $new1 = $_POST['new1']; }
if(isset($_POST['new2'])){ $new2 = $_POST['new2']; }

if(isset($old) && isset($new1) && isset($new2)){
    // Could count rows here, but in the future we might have multiple users and two could have the same password...
	$pass_query = "SELECT * FROM `".$user_table."`";
	$pass_result = mysql_query($pass_query);
	$array = mysql_fetch_array($pass_result);
	$db_password = $array['password'];
	
	if(md5($old) == $db_password){ // Confirm old P
		if($new1 == $new2){ // Confirm equivalent new Ps
			$pass_md5 = md5($new1);
			$query_update = "UPDATE `".$user_table."` SET `password`='".$pass_md5."' WHERE `id`='".$user_id."'";
			mysql_query($query_update);
			
			$query_confirm = "SELECT * FROM `".$user_table."` WHERE `password`='".$pass_md5."' AND `id`='".$usenam."'";
            mysql_query($query_confirm);
            $num_rows = @mysql_num_rows($mysql_query);
            if($num_rows !== "" && $num_rows !== "0"){
                echo 'success';
            } else {
                echo 'failure';
            }
            
		} else { // If new Ps not same
			echo 'New passwords don\'t match.';
		}
	} else { // If old P not same
		echo 'Old password not correct.';
	}
}
?>
