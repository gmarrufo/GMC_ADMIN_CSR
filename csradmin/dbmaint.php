<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

?>

<head>
	<title>Administration Control Panel | Revitalash Administration</title>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="/csradmin/styles/revitalash.css" type="text/css" />
</head>

<body>
	<div id="wrapper">
	<?php include("includes/dspMasthead.php"); ?>
	<div id="content">
	<form action="/csradmin/dbmaint_main.php" method="post">
    <table width="50%" border="0">
    <tr>
    <td>
    <table width="100%" border="0">
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%" colspan="3">
    <b>Select the Table to Maintain:</b>
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
    Revitalash Products:
    </td>
    <td>
    <input type="radio" name="radDBMaint" value="revitalash_products">
    </td>
    </tr>
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%">
    Revitalash Users:
    </td>
    <td>
    <input type="radio" name="radDBMaint" value="revitalash_users">
    </td>
    </tr>
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%">
    Revitalash Products Reseller Tier:
    </td>
    <td>
    <input type="radio" name="radDBMaint" value="revitalash_products_reseller">
    </td>
    </tr>
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%">
    Revitalash Campaigns:
    </td>
    <td>
    <input type="radio" name="radDBMaint" value="revitalash_campaigns">
    </td>
    </tr>
    <!-- GMC - 07/28/11 - Monthly Newsletters -->
    <tr>
    <td width="2%">
    &nbsp;
    </td>
    <td width="30%">
    Monthly Newsletters:
    </td>
    <td>
    <input type="radio" name="radDBMaint" value="monthly_newsletters">
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
