<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

function connect(){
	global $db;
	if (mysql_connect($db['server'],$db['username'],$db['password'])) {
		if (@!mysql_select_db($db['database'])) {
			echo "Database doesn't exist.";
			return false;
		} else {
		    return true;
	    }
	} else {
		echo "Connection failure.";
		return false;
	}
}

function parse_domain($link){ 
	if(!($raw_url = parse_url($link))){ // Invalid URL
	    return false;
    }
	@preg_match("/([^\.]+\.[^\.]+$)/", $raw_url['host'], $domain);
	return @strtolower($domain[1]); 
}

function is_row($int){
	global $main_table;
	$query_check = "SELECT * FROM `".$main_table."` WHERE `key1`='".$int."'";
	$result_check = mysql_query($query_check);
	$rows = @mysql_num_rows($result_check);
	if($rows){
		return true;
	} else {
		return false;
	}
}

function authenticate(){
	$cookie = @$_COOKIE['snickerdoodle'];
	if($cookie == "polarbears"){
		//
	} else {
		exit("Not logged in.");
	}
}

function verify(){
	if(@$_COOKIE['snickerdoodle']){
	    $cookie = $_COOKIE['snickerdoodle'];
    } else {
        $cookie = '';
    }
	if($cookie == "polarbears"){
		return 1;
	} else {
		return 0;
	}
}

function getUserID(){
	$cookie = @$_COOKIE['usename'];
	if($cookie){
		return $cookie;
	}
}

function news(){
    $url = 'http://get-shorty.com/news.php';
    if(function_exists('curl_init')) {
		$curl = @curl_init();
		@curl_setopt($curl, CURLOPT_URL, $url);
		@curl_setopt($curl, CURLOPT_HEADER, 0);
		@curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		
		$output = @curl_exec($curl);
		$info = @curl_getinfo($curl);
		
		@curl_close($curl);
		
		if($output === false || $info['http_code'] != 200)
			return "Couldn't find the Shorty server for the latest news.";
		else
			return $output;
	} else {
		return '(Unable to parse Shorty news feed. Your sever does not support the cURL library. Please see <a href="http://get-shorty.com">http://get-shorty.com</a>.)';
	}
}

$version = "0.7.1(a) Beta";

?>