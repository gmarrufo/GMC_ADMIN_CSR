<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

if ((!isset($_SESSION['IsRevitalashLoggedIn'])) || ($_SESSION['IsRevitalashLoggedIn'] == 0))
{
	header("Location: login.php");
	exit;
}

?>

<head>
	<title>Administration Control Panel | Revitalash Administration</title>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="/csradmin/styles/revitalash.css" type="text/css" />
</head>

<body>

	<div id="wrapper">
		
        <!-- GMC - 12/03/08 - Domestic vs International 3rd Phase
		<div><img src="/csradmin/images/bg_masthead.gif" alt="Revitalash Administration" width="950" height="91" /></div>
        -->
        
		<?php include("includes/dspMasthead.php"); ?>
		
		<div id="content">
        
        	<h1>Marketing Materials</h1>
           
			<table width="100%" cellpadding="10" cellspacing="0">
            
            <tr>
            	<td width="25%"><img src="/csradmin/images/marketing/tbACC0003.jpg" /><br />Revitalash<br />ACC 0003</td>
            	<td width="25%"><img src="/csradmin/images/marketing/tbACC2005.jpg" /><br />Acrylic Display - Large Kit<br />ACC 2005</td>
                <td width="25%"><img src="/csradmin/images/marketing/tbACC2006.jpg" /><br />Acrylic Display - Small Kit<br />ACC 2006</td>
                <td width="25%"><img src="/csradmin/images/marketing/tbACC2009.jpg" /><br />Breast Cancer Awareness Promo Kit<br />ACC 2009</td>
          	</tr>
            
            <tr>
            	<td>&nbsp;<br />POP Assembly Kit-Sample Only<br />ACC 3006</td>
            	<td><img src="/csradmin/images/marketing/tbACC0003.jpg" /><br />Marketing Brochures<br />ACC 3007</td>
                <td>&nbsp;<br />Sample Only Revitalash Conditioner<br />ACC 4000</td>
                <td><img src="/csradmin/images/marketing/tbACC0003.jpg" /><br />Athena Thank You Post Cards 4x6 (25p/Pack)<br />ACC 9011</td>
          	</tr>
            
            <tr>
            	<td>&nbsp;<br />Athena Envelopes 4.75 x 6.5<br />ACC 9012</td>
            	<td><img src="/csradmin/images/marketing/tbACC9015.jpg" /><br />Revitalash 4x6 Blank Postcard (25p/pack)<br />ACC 9015</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
          	</tr>
            
            </table>
		
		</div>
		
	</div>

</body>

</html>
