<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/
 
if (!isset ($_SESSION)) session_start();
$_SESSION['action'] = 'home';

$where = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
$where = substr_replace($where, "", -9); // Remove /home.php

if(isset($_SESSION['posturl'])){
	$posturl = $_SESSION['posturl'];
} else {
	$posturl = '';
}


require("functions.php");
require("configuration.php");

connect();
authenticate();

// There is really a better way to do this sql
$user_query = "SELECT * FROM `".$user_table."`";
$user_result = mysql_query($user_query);
$user = mysql_fetch_array($user_result);
$tab = strtolower($user['mode']);

echo '
  <form action="create.php" method="post" onsubmit="return false">
         <input name="selectedTab" id="selectedTab" type="hidden" value="'.$tab.'" readonly="readonly" />
         <img id="main-spinner" src="skin/'.$user['theme'].'/spinner.gif" />
         <h2 id="superCenteredText">Choose one of these methods to generate your Shorty</h2>

         <div id="tabbedModes">

            <ul id="tabs">
               <li id="autoTab"><a href="#">Auto</a></li>
               <li id="keywordsTab"><a href="#">Keywords</a></li>
               <li id="randomTab"><a href="#">Random</a></li>
            </ul>

            <div class="tabContent">
               <p><span>1. </span>Enter URL</p>
               <input type="text" name="url" value="'.$posturl.'" id="int-url" class="url" />
               <p><span>2. </span>If you want you can add a keyword to further customize your URL</p>
               <fieldset id="auto">
                  <label>'.$where.'/<span id="namespace"></span>
                     <input type="text" value="" name="descriptor" id="descriptor" />
                  </label>
               </fieldset>
               <p><span>3. </span>Click this button to generate your Shorty</p>
               <input type="submit" value="GET SHORTY!" name="auto" class="submitBtn" id="autoBtn" /> 

               <p><span>4. </span>Control-click or right-click to copy your Shorty</p>
               <div id="response1"></div>

               <p><input type="checkbox" name="prefMethod" value="auto" id="autoCheckbox" />
                  <label for="autoCheckbox">Set <strong>Auto</strong> as my preferred Shorty method</label></p>
               </div>

               <div class="tabContent">
                  <p><span>1. </span>Enter URL</p>
                  <input type="text" name="url" value="'.$posturl.'" id="desc-url" class="url" />
               
                  <p><span>2. </span>Enter <strong>at least one</strong> keyword below to create a customized version of your URL</p>
                  <fieldset id="keywords">
                     <label>'.$where.'/
                        <input type="text" value="" name="key-1" id="key1" /> /
                        <input type="text" value="" name="key-2" id="key2" /> /
                        <input type="text" value="" name="key-3" id="key3" /> /
                        <input type="text" value="" name="key-4" id="key4" />
                     </label>
                  </fieldset>
                  <p><span>3. </span>Click this button to generate your Shorty</p>
                  <input type="submit" value="GET SHORTY!" name="keywords" class="submitBtn" id="keywordBtn" /> 

                  <p><span>4. </span>Control-click or right-click to copy your Shorty</p>
                  <div id="response2"></div>

                  <p><input type="checkbox" name="prefMethod" value="keywords" id="keywordsCheckbox" />
                     <label for="keywordsCheckbox">Set <strong>Keywords</strong> as my preferred Shorty method</label></p>
                  </div>

                  <div class="tabContent">
                     <p><span>1. </span>Enter URL</p>
                     <input type="text" name="url" value="'.$posturl.'" id="rand-url" class="url" />
                     
                     <p><span>2. </span>Click this button to generate your Shorty</p>
                     <input type="submit" value="GET SHORTY!" name="submitrandom" class="submitBtn" id="randomBtn" />

                     <p><span>3. </span>Control-click or right-click to copy your Shorty</p>
                     
                     <div id="response3"></div>

                     <p><input type="checkbox" name="method" value="random" id="randomCheckbox" />
                        <label for="randomCheckbox">Set <strong>Random</strong> as my preferred Shorty method</label></p>
                     </div>
                  </form>
                  <script type="text/javascript" language="javascript" charset="utf-8">
                    // <![CDATA[
                    menu.unfold("homeOption");
                    new accordion("tabbedModes");
                    buttonCollector.init("tabbedModes")
                    creator.init()
                    // ]]>
                  </script>
';
?>