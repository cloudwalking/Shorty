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

/* Begin Big Confusing URL thinger */
	$string = '';

	//$post_url = $_POST['args'];
	/*if(isset($_GET['descriptor'])){
		$descriptor = $_GET['descriptor'];
	} else {
		$descriptor = '';
	}
	
	if(isset($_GET['method'])){
		$method = $_GET['method'];
	} else {
		$method = '';
	}
	
	if(isset($_GET['key-1'])){
		$key1 = $_GET['key-1'];
	} else {
		$key1 = '';
	}
	if(isset($_GET['key-2'])){
		$key2 = $_GET['key-2'];
	} else {
		$key2 = '';
	}
	if(isset($_GET['key-3'])){
		$key3 = $_GET['key-3'];
	} else {
		$key3 = '';
	}
	if(isset($_GET['key-4'])){
		$key4 = $_GET['key-4'];
	} else {
		$key4 = '';
	}
	*/
	$descriptor = @$_GET['descriptor'];
	$method = @$_GET['method'];
	$key1 = @$_GET['key-1'];
	$key2 = @$_GET['key-2'];
	$key3 = @$_GET['key-3'];
	$key4 = @$_GET['key-4'];

	foreach($_POST as $key => $value){ // Thanks Tynan Smith!
		$key .= "=";
		$value .= "&";
		$string .= $key.$value;
		// Could we just impload instead?
	}

	$string = ereg_replace("args=", "", $string); // Fix goofy stuff
	$string = ereg_replace("&_=&", "", $string);  // Fix goofy stuff
	$post_url = $string;
/* End Big Confusing URL thinger */

$descriptor = ereg_replace(" ", "", $descriptor); // We could just use the strip whitespace function...

// Figure out where on the server the new url will be
$where = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
$where = substr_replace($where, "", -11); // Remove /create.php

if(!$post_url && !$_GET['post_url']) {
	exit('You need to enter a url, silly.');
}

if($method == "int"){ // Auto
	if(!($key_prime = parse_domain($post_url))){
	    exit('Invalid URL');
    }
	$key_prime = ereg_replace("\.[a-z]+", "", $key_prime);
	
	// Check to see if that domain has already been used as a first-level keyword.
		$query_check = "SELECT * FROM `".$main_table."` WHERE `key1`='".$key_prime."'";
		$result_check = mysql_query($query_check);
		$rows = @mysql_num_rows($result_check);
		if($rows){
			// Go one keyword level deeper.
			$query_check .= " AND `key2`='".$descriptor."'";
			$result_check = mysql_query($query_check);
			$rows = @mysql_num_rows($result_check);

			// Check again.
			if($rows){
				// Not going to work. Use a different descriptor.
				echo 'Oh dear. That Shorty already exists.';
			} else {
				$query_insert = "INSERT INTO `".$main_table."` (`target`, `day`, `month`, `year`, `key1`, `key2`) VALUES ('".$post_url."', '".date('j')."', '".date('n')."', '".date('Y')."', '".$key_prime."', '".$descriptor."')";
				mysql_query($query_insert);
				// Could add some confirmation sql here.
				echo '<a href="'.$where.'/'.$key_prime.'/'.$descriptor.'/">'.$where.'/'.$key_prime.'/'.$descriptor.'/</a>';
			}
		} else {
			$query_insert = "INSERT INTO `".$main_table."` (`target`, `day`, `month`, `year`, `key1`) VALUES ('".$post_url."', '".date('j')."', '".date('n')."', '".date('Y')."', '".$key_prime."')";
			mysql_query($query_insert);
			// Could add some confirmation sql here.
			echo '<a href="'.$where.'/'.$key_prime.'/">'.$where.'/'.$key_prime.'/</a>';
		}
	// /
}

if($method == "rand"){ // Random
	$integer = rand(10000, 99999);
	
	// Make a loop that checks the integer and recreates the integer if it already exists.
	// Might be smarter to just do them numerically
	while(is_row($integer)){
		$integer = rand(10000, 99999);
	}
	// Create Shorty when integer hasn't already been used
	if(!is_row($integer)){
		$query_insert = "INSERT INTO `".$main_table."` (`target`, `day`, `month`, `year`, `key1`) VALUES ('".$post_url."', '".date('j')."', '".date('n')."', '".date('Y')."', '".$integer."')";
		mysql_query($query_insert);
		// Could add some confirmation sql here.
		echo '<a href="'.$where.'/'.$integer.'/">'.$where.'/'.$integer.'/</a>';
	}
}

if($method == "desc"){
	// Check to see if the specified keywords have already been used.
		$query_check = "SELECT * FROM `".$main_table."` WHERE `key1`='".$key1."' AND `key2`='".$key2."' AND `key3`='".$key3."' AND `key4`='".$key4."'";
		$result_check = mysql_query($query_check);
		$rows = @mysql_num_rows($result_check);
		if($rows){
			// Whoops it already exists.
			echo 'Oh dear. That Shorty already exists.';
		} else {
			$query_insert = "INSERT INTO `".$main_table."` (`target`, `day`, `month`, `year`, `key1`, `key2`, `key3`, `key4`) VALUES ('".$post_url."', '".date('j')."', '".date('n')."', '".date('Y')."', '".$key1."', '".$key2."', '".$key3."', '".$key4."')";
			mysql_query($query_insert);
			// Could add some confirmation sql here.
			
			// Set up the keywords to make an easy url.
				if(!$key2){ $key2 = ''; } else { $key2 = '/'.$key2; }
				if(!$key3){ $key3 = ''; } else { $key3 = '/'.$key3; }
				if(!$key4){ $key4 = ''; } else { $key4 = '/'.$key4; }
			// /
			echo '<a href="'.$where.'/'.$key1.$key2.$key3.$key4.'/">'.$where.'/'.$key1.$key2.$key3.$key4.'/</a>';
		}
}



?>