<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

if (!isset ($_SESSION)) session_start();
$_SESSION['action'] = 'admin';

require("functions.php");
require("configuration.php");

connect();
authenticate();

$user_id = getUserID();

$selected12 = '';
$selected48 = '';
$selectedAll = '';
$longURL = '';
$visibility = '';
$limit = '';

$query_user = "SELECT * FROM `".$user_table."` WHERE `id`='".$user_id."'";
$result_user = mysql_query($query_user);
$user = mysql_fetch_array($result_user);
if($user['perpage'] == "12"){
	$selected12 = 'checked ';
	$limit = " LIMIT ".$user['perpage'];
}
if($user['perpage'] == "48"){
	$selected48 = 'checked ';
	$limit = " LIMIT ".$user['perpage'];
}
if(strtolower($user['perpage']) == "all"){
	$selectedAll = 'checked ';
	$limit = "";
}
if($user['longURL'] == 1){
	$longURL = 'checked ';
	$visibility = 'visible';
}

if(isset($_GET['start'])){
    $start = $_GET['start'];
} else { $start = 0; }
$next = $start + $user['perpage'];
$previous = $start - $user['perpage'];
if($previous < 0){ $previous = 0; }
if(isset($_GET['start'])){
    $limit = " LIMIT ".$start.", ".$user['perpage'];
}
if(isset($_GET['force'])){
    if($_GET['force'] !== 'all'){
        $limit = " LIMIT ".$_GET['force'];
        if($_GET['force'] == '12'){
            $selected12 = 'checked ';
            $selected48 = '';
            $selectedAll = '';
        }
        if($_GET['force'] == '48'){
            $selected48 = 'checked ';
            $selected12 = '';
            $selectedAll = '';
        }
    } else {
        $limit = '';
        $selectedAll = 'checked ';
        $selected12 = '';
        $selected48 = '';
    }
}
$query_count = "SELECT COUNT(*) AS total FROM `".$main_table."`";
$result_count = @mysql_query($query_count);
$count = @mysql_fetch_object($result_count); 
$total = $count -> total;

$query_shorties = "SELECT * FROM `".$main_table."` ORDER BY `id` ASC".$limit;
$result_shorties = @mysql_query($query_shorties);
$num_shorties = @mysql_num_rows($result_shorties);

// Figure out where on the server the urls are
$where = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
$where = substr_replace($where, "", -10); // Remove /admin.php

if(isset($_GET['start'])){
    $i = $start;
    $i++;
    $offset = $i;
} else {
    $i = 1;
    $offset = $i;
}
echo '
   <div id="search">
      <form id="searchForm" onsubmit="return false">
      <img id="search-spinner" src="skin/'.$user['theme'].'/spinner.gif" />
      <input type="text" value="" id="searchTerm" />
      <input type="image" class="searchButton" name="search" value="SEARCH" id="searchBtn" src="skin/originhal/search.gif" onclick="administrator.srch();" />
      <input type="image" class="clearButton" name="clear" value="CLEAR" id="clearBtn" src="skin/originhal/clear.gif" onclick="administrator.clr();" />
      </form>
   </div>
  ';
  
echo '
  <img id="main-spinner" src="skin/'.$user['theme'].'/spinner.gif" />
  <table id="shortyList" class="sortable">
    <tr>
    <th class="selected">&nbsp;</th>
    <th class="shortyURL">Shorty</th>
    <th class="date">Modified</th>
    <th class="nosort buttons">Actions
    </th>
    </tr>
';
if($result_shorties){
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
		 <td class"buttons">
		 <input type="button" class="edit" name="'.$array['id'].'" value="EDIT" id="edBtn'.$i.'" /><input type="button" class="save" name="'.$array['id'].'" value="SAVE" id="saveBtn'.$i.'" /><input type="button" class="delete" name="'.$array['id'].'"  value="DELETE" id="delBtn'.$i.'"  /><input type="button" class="cancel" name="'.$array['id'].'"  value="CANCEL" id="cancelBtn'.$i.'"  /></td></tr>
		';
		$i++;
	}
	$new_i = $i-1;
} else {
  echo ' 
  <tr class="'.$tr_class.'">
    <td colspan=4>
        No results
    </td>
  </tr>';
}

echo '
</table>                                                                                                                                                                                                                                                
<div id="adminOptions">
   <ul id="pagingNav">
      <li><a href="admin.php?start='.$previous.'" onclick="forward(admin.php?start='.$previous.')">Previous</a></li>
      <li><a href="admin.php?start='.$next.'" onclick="forward(admin.php?start='.$next.')">Next</a></li>
   </ul>
   <div id="totalResultsYay">
     <p>Displaying '.$offset.'-'.$new_i.' of '.$total.' Shorties</p>
   </div>
   <form id="displayOptions" action="admin.php" method="get">
      <label id="urlLength"><input type="checkbox" name="longURL" value="longURL" '.$longURL.'/>Show long URLs</label>
      <label><input type="radio" name="shortysPerPage" value="12" '.$selected12.'/>12 Shortys at a time</label>
      <label><input type="radio" name="shortysPerPage" value="48" '.$selected48.'/>48 Shortys at a time</label>
      <label><input type="radio" name="shortysPerPage" value="all" '.$selectedAll.'/>All Shortys at a time</label>
   </form>
</div>

<script type="text/javascript" charset="utf-8">
    // <![CDATA[
    menu.unfold("adminOption");
    new nav("adminOptions");
    buttonCollector.init("shortyList");
    buttonCollector.init("displayOptions");
    buttonCollector.init("searchForm");
    administrator.init();
    sortables_init();
    // ]]>
</script>
';

?>
