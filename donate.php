<?php

 /*

	(c) 2006 Reed Morse <firstname.lastname at gmail.com>
		 and Pau Santesmasses  <firstname at lastname.net>

*/

if (!isset ($_SESSION)) session_start();
$_SESSION['action'] = 'donate';

require("functions.php");
require("configuration.php");

connect();

// There is really a better way to do this sql
$user_query = "SELECT * FROM `".$user_table."`";
$user_result = mysql_query($user_query);
$user = mysql_fetch_array($user_result);

echo '
<div id="donate">
		<p><strong>We worked hard to bring you Shorty for free.</strong></p>
		<p>You can use it as much as you like for as long as you like &#8212; we hope you do!</p>
		<p>But if you use it a lot, we ask that you make <span>a donation of US$10</span> or more.</p>
		<p id="thanks"><strong>Thank you!</strong></p>
		
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="donate@get-shorty.com">
            <input type="hidden" name="item_name" value="Shorty">
            <input type="hidden" name="item_number" value="001">
            <input type="hidden" name="no_shipping" value="1">
            <input type="hidden" name="return" value="http://www.get-shorty.com/thankyou/">
            <input type="hidden" name="cancel_return" value="http://www.get-shorty.com/">
            <input type="hidden" name="cn" value="Comments on Shorty">
            <input type="hidden" name="no_note" value="1">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="tax" value="0">
            <input type="hidden" name="bn" value="PP-DonationsBF">
            <input type="image" src="skin/'.$user['theme'].'/donate.gif" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!" id="donateBtn">
            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
		<h2><img src="skin/'.$user['theme'].'/paypal.png" /><br />Donations are handled via PayPal.<br />Visa, MasterCard, American Express<br /> and Discover cards accepted.</h2>
</div>

<script type="text/javascript" charset="utf-8">
    // <![CDATA[
    menu.unfold("donateOption");
    // ]]>
</script>

';

?>