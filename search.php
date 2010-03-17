<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

require("functions.php");
require("configuration.php");

connect();
authenticate();

$string = $_POST['query'];

$user_id = getUserID();

$longURL = '';

$query_user = "SELECT * FROM `".$user_table."` WHERE `id`='".$user_id."'";
$result_user = mysql_query($query_user);
$user = mysql_fetch_array($result_user);

if($user['longURL'] == 1){
	$visibility = 'visible';
} else {
    $visibility = '';
}

$query_shorties = "SELECT * FROM `".$main_table."` 
                   WHERE `target` LIKE '%".$string."%' 
                   OR `key1` LIKE '%".$string."%' 
                   OR `key2` LIKE '%".$string."%' 
                   OR `key3` LIKE '%".$string."%' 
                   OR `key4` LIKE '%".$string."%' 
                   OR `key5` LIKE '%".$string."%'
                   ORDER BY `id` ASC
";

$result_shorties = @mysql_query($query_shorties);
$num_shorties = @mysql_num_rows($result_shorties);

// Figure out where on the server the urls are
$where = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
$where = substr_replace($where, "", -11); // Remove /search.php
//$where = "";

$i = 1;

echo '
    <tr>
      <th class="selected">&nbsp;</th>
      <th class="shortyURL">Shorty</th>
      <th class="date">Modified</th>
      <th class="nosort buttons">Actions</th>
    </tr>
';

if($num_shorties > 0){
	while($array = mysql_fetch_array($result_shorties)){
	
		$n = $i % 2;
		if($n == 0){
			$tr_class = "evenrow";
		} else {
			$tr_class = "oddrow";
		}
		
		if(!$array['key1']){ $array['key1'] = ''; } else { $array['key1'] = '/'.$array['key1']; }
		if(!$array['key2']){ $array['key2'] = ''; } else { $array['key2'] = '/'.$array['key2']; }
		if(!$array['key3']){ $array['key3'] = ''; } else { $array['key3'] = '/'.$array['key3']; }
		if(!$array['key4']){ $array['key4'] = ''; } else { $array['key4'] = '/'.$array['key4']; }
		$display_shorty = '<span class="hostname '.$visibility.'">'.$where.'</span>'.$array['key1'].$array['key2'].$array['key3'].$array['key4'];
		$real_shorty = $where.$array['key1'].$array['key2'].$array['key3'].$array['key4'];
		
		if(strlen($i) == '1'){ // Add leading zero
			$i = '0'.$i;
		}
		if(strlen($array['day']) == '1'){ // Add leading zero
			$array['day'] = '0'.$array['day'];
		}
		/*if(strlen($array['month']) == '1'){ // Add leading zero
			$array['month'] = '0'.$array['month'];
		}*/
		if(strlen($array['year']) == '1'){ // Add leading zero
			$array['year'] = '0'.$array['year'];
		}
		
		echo '
		 <tr class="'.$tr_class.'">
		 <td class="rowNum">'.$i.'. </td> 
		 <td class="shortyURL">'.$display_shorty.'/ ('.$array['clicks'].' clicks) <a href="'.$real_shorty.'/" title="Follow Shorty (New Window)" target="_blank"><img src="skin/'.$user['theme'].'/follow.gif" border="0" alt="Follow Shorty (New Window)"/></a></td>
		 <td class="date">'.$array['month'].'.'.$array['day'].'.'.$array['year'].'</td>
	   <td class="buttons">
	     <input type="button" class="edit" name="'.$array['id'].'" value="EDIT" id="SEdBtn'.$i.'" /><input type="button" class="save" name="'.$array['id'].'" value="SAVE" id="SSaveBtn'.$i.'" /><input type="button" class="delete" name="'.$array['id'].'"  value="DELETE" id="SDelBtn'.$i.'"  /><input type="button" class="cancel" name="'.$array['id'].'"  value="CANCEL" id="SCancelBtn'.$i.'"  /></td></tr>
		';
		$i++;
	}
}
else{
  echo '<tr><td colspan="4">No results</td></tr>';
}

echo '</table>
<script type="text/javascript" charset="utf-8">
// <![CDATA[
buttonCollector.init(\'shortyList\');
sortables_init();
// ]]>
</script>
';

?>