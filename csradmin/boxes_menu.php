<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

if (isset($_POST['radBoxes']))
{
    // Check what selection was made
    if ($_POST['radBoxes'] == 'fedex_domestic')
    {
        // include("boxes_dom.php");
        header("Location: http://localhost/csradmin/boxes_dom.php");
        // header("Location: https://ae.revitalash.com/csradmin/boxes_dom.php");
    }
    else if($_POST['radBoxes'] == 'fedex_international')
    {
        // include("boxes_int.php");
        header("Location: http://localhost/csradmin/boxes_int.php");
        // header("Location: https://ae.revitalash.com/csradmin/boxes_int.php");
    }
    else if($_POST['radBoxes'] == 'usps_domestic')
    {
        // include("usps_dom.php");
        header("Location: http://localhost/csradmin/usps_dom.php");
        // header("Location: https://ae.revitalash.com/csradmin/usps_dom.php");
    }
}

?>

<head>
	<title>Customer Management | Revitalash Administration | Calculate Boxes and Rates Prototype FeDex - (Domestic and International) | USPS - (Domestic)</title>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="/csradmin/styles/revitalash.css" type="text/css" />
</head>

<body>
	<div id="wrapper">
	<?php include("includes/dspMasthead.php"); ?>
	<div id="content">
	<form action="/csradmin/boxes_menu.php" method="post">
    <table width="50%" border="0">
    <tr>
    <td>
    <table width="100%" border="0">
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%" colspan="3">
    <b>Select the Shipping Estimate Process:</b>
    </td>
    <td>
    &nbsp;
    </td>
    </tr>
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%">
    FedEx Domestic:
    </td>
    <td>
    <input type="radio" name="radBoxes" value="fedex_domestic">
    </td>
    </tr>
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%">
    FedEx International:
    </td>
    <td>
    <input type="radio" name="radBoxes" value="fedex_international">
    </td>
    </tr>
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%">
    USPS Domestic:
    </td>
    <td>
    <input type="radio" name="radBoxes" value="usps_domestic">
    </td>
    </tr>
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%">
    <input type="submit" name="dbmaint_submit" value="Submit">
    </td>
    <td>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </form>
    </div>
    </div>
</body>
</html>
