<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

if (!isset ($_SESSION)) session_start();
$_SESSION['action'] = 'about';

require("functions.php");

$news = news();

echo '
<div id="about">
  
  <h2>Shorty '.$version.'
  <span>August 23rd 2006</span>
  <span>Copyright 2006 by Khoi Vinh and Reed Morse</span>
  </h2>

  <ul id="credits">
    <li><h3>Concept and Design</h3>
    <p class="name">Khoi Vinh</p>
    <p class="site"><a href="http://www.subtraction.com">Subtraction.com</a></p>
    </li>
    <li><h3>Server Side Development</h3>
    <p class="name">Reed Morse</p>
    <p class="site"><a href="http://www.morsedesigns.com">morsedesigns.com</a></p>
    </li>
    <li><h3>Front End Development</h3>
    <p class="name">Pau Santesmasses</p>
    <p class="site"><a href="http://pau.santesmasses.net">pau.santesmasses.net</a></p>
    </li>
    <li><h3>Special Thanks to</h3>
    <p class="name">Michael Simmons</p>
    <p class="site"><a href="http://www.growinglogic.com">growinglogic.com</a></p>
    </li>
  </ul>

  <p class="feedback">Feedback? write to us at <a href="http://somesite.com/">feedback@get-shorty.com </a></p>
  <br />

  <h2>Version History</h2>
  <dl id="versionHistory">
    '.$news.'
  </dl>
  
  <h2>System Requirements</h2>
  <ul>
    <li>A Web Server</li>
    <li>PHP 4 or higher</li>
    <li>MySQL 3.23 or higher</li>
    <li>Apache 1.2 or higher</li>
  </ul>
  </div>
  
  <script type="text/javascript" charset="utf-8">
      // <![CDATA[
      menu.unfold("aboutOption");
      // ]]>
  </script>
';

?>