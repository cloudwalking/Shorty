<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

$query = $_SERVER['QUERY_STRING'];
$what = ereg_replace("[0-9]+", "", $query);
$num = ereg_replace("[a-z]+", "", $query);
if($num == ""){ $num = "1"; }

require("functions.php");
require("configuration.php");

connect();

// Figure out where on the server the urls are
$where = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
$where = substr_replace($where, "", -7); // Remove /api.php

header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
	<channel>
	<title>Shorty Feed</title>
    <link>'.$where.'</link>
    <description>Shorty Feed</description>';

switch ($what) {
	case 'recent':
		$query = "SELECT * FROM `".$main_table."` ORDER BY `id` DESC LIMIT ".$num;
		$result = mysql_query($query);
		// 	Output
		while($array = mysql_fetch_array($result)){
		    
		    if(!$array['key1']){ $array['key1'] = ''; }
			if(!$array['key2']){ $array['key2'] = ''; } else { $array['key2'] = '/'.$array['key2']; }
			if(!$array['key3']){ $array['key3'] = ''; } else { $array['key3'] = '/'.$array['key3']; }
			if(!$array['key4']){ $array['key4'] = ''; } else { $array['key4'] = '/'.$array['key4']; }
			
			$real_shorty = $where.$array['key1'].$array['key2'].$array['key3'].$array['key4'];
			
			echo '<item><date>'.$array['year'].'-'.$array['month'].'-'.$array['day'].'</date><shorty>'.$real_shorty.'</shorty><tags>'.$array['key1'].$array['key2'].$array['key3'].$array['key4'].'</tags><clicks>'.$array['clicks'].'</clicks></item>';
		}
	break;
	case 'popular':
		$query = "SELECT * FROM `".$main_table."` ORDER BY `clicks` DESC LIMIT ".$num;
		$result = mysql_query($query);
		// 	Output
		while($array = mysql_fetch_array($result)){
		    
		    if(!$array['key1']){ $array['key1'] = ''; }
			if(!$array['key2']){ $array['key2'] = ''; } else { $array['key2'] = '/'.$array['key2']; }
			if(!$array['key3']){ $array['key3'] = ''; } else { $array['key3'] = '/'.$array['key3']; }
			if(!$array['key4']){ $array['key4'] = ''; } else { $array['key4'] = '/'.$array['key4']; }
			
			$real_shorty = $where.$array['key1'].$array['key2'].$array['key3'].$array['key4'];
			
			echo '<item><date>'.$array['year'].'-'.$array['month'].'-'.$array['day'].'</date><shorty>'.$real_shorty.'</shorty><tags>'.$array['key1'].$array['key2'].$array['key3'].$array['key4'].'</tags><clicks>'.$array['clicks'].'</clicks></item>';
		}
	break;
}

if(isset($_GET['keyword'])){
    $tag = $_GET['keyword'];
    $query = "SELECT * FROM `".$main_table."` WHERE 
                   `key1` LIKE '%".$tag."%' 
                OR `key2` LIKE '%".$tag."%' 
                OR `key3` LIKE '%".$tag."%' 
                OR `key4` LIKE '%".$tag."%' 
                OR `key5` LIKE '%".$tag."%'
                ORDER BY `id` DESC";
	$result = mysql_query($query);
	// 	Output
	while($array = mysql_fetch_array($result)){
	    
	    if(!$array['key1']){ $array['key1'] = ''; }
		if(!$array['key2']){ $array['key2'] = ''; } else { $array['key2'] = '/'.$array['key2']; }
		if(!$array['key3']){ $array['key3'] = ''; } else { $array['key3'] = '/'.$array['key3']; }
		if(!$array['key4']){ $array['key4'] = ''; } else { $array['key4'] = '/'.$array['key4']; }
		
		$real_shorty = $where.$array['key1'].$array['key2'].$array['key3'].$array['key4'];
		
		echo '<item><date>'.$array['year'].'-'.$array['month'].'-'.$array['day'].'</date><shorty>'.$real_shorty.'</shorty><tags>'.$array['key1'].$array['key2'].$array['key3'].$array['key4'].'</tags><clicks>'.$array['clicks'].'</clicks></item>';
    }
}

echo '</channel>
</rss>';

?>