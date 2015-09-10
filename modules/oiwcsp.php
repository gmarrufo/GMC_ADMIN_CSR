<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
    require_once("../modules/session.php");
	$strMessage = '<p>An Order was tried to place in the Web Consumer Shopping Cart but failed the Online Orders Policy.</p><br/>';
	$strMessage .= '<p>The Customer information for your review is as follows:</p><br/>';
	$strMessage .= '<p>Customer Name: ' . $_SESSION["CustomerName"] . '</p><br/>';
	$strMessage .= '<p>Customer Address: ' . $_SESSION["CustomerAddress"] . '</p><br/>';
	$strMessage .= '<p>Customer City/State/Zip: ' . $_SESSION["CustomerCityStateZIP"] . '</p><br/>';
	$strMessage .= '<p>Customer Country: ' . $_SESSION["CustomerCountryCode"] . '</p><br/>';
	$strMessage .= '<p>Customer Email: ' . $_SESSION["CustomerEmail"] . '</p><br/>';
	$strMessage .= '<p>Customer Phone: ' . $_SESSION["CustomerTelephone"] . '</p>';
    $mailrecepient = "customerservice@revitalash.com";
    $mailsubject = 'Web Consumer Shopping Cart - Order Issue';
	$mailheader = 'MIME-Version: 1.0' . "\r\n";
	$mailheader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$mailheader .= "From:" . 'webmaster@revitalash.com' . "\r\n";
    $mailheader .= 'Bcc: gmarrufo@unimerch.com ' . "\r\n";
	// $mailheader .= 'Cc: MCB-inquiry@revitalash.com' . "\r\n";
	mail($mailrecepient, $mailsubject, $strMessage, $mailheader);
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Checkout | Revitalash.com</title>
	<link rel="stylesheet" type="text/css" href="/styles/revitalash.css" />
</head>
<body>
<div id="masthead"><img src="/images/interface/masthead_logo.jpg" alt="Masthead Logo" width="730" height="100" /></div>
<div id="wrapper">
    <div align="center">
    <p><strong>"Please excuse the delay in processing your order, a member of the Customer Service Team will contact you."</strong></p>
    <p><strong>"Or you can contact us at (877) 909-5274"</strong></p>
	<p style="margin:0px;">&nbsp;</p>
    <p><a href="http://www.revitalash.com">GO BACK TO MAIN WEBSITE</a></p>
    </div>
</div>
</body>
</html>
