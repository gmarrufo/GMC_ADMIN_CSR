<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

// GMC - 02/03/14 - Add Discount to tblCampaigns
if (isset($_GET['id']))
{
    $Id = $_GET['id'];
    $_SESSION['edit_id'] = $Id;

	$strSQL = "SELECT * FROM tblCampaigns WHERE RecordID = " . $Id . "";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetProducts))
	{
		$RecordID = $row["RecordID"];
		$NavisionCode = $row["NavisionCode"];
		$CampaignName = $row["CampaignName"];
		$StartDate = $row["StartDate"];
		$EndDate = $row["EndDate"];
		$IsActive = $row["IsActive"];
		$Location = $row["Location"];
        $Discount = $row["Discount"];
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

?>

<h1>Edit Revitalash Campaign</h1>
<body>
<form method="post" action="revitalash_campaigns_edit.php">
<div class="bluediv_content">
    <table width="80%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="20%">RecordID:</td><td><?php echo $RecordID ?></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Navision Code:</td><td><input type="text" name="navision_code" value="<?php echo $NavisionCode ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Campaign Name:</td><td><input type="text" name="campaign_name" value="<?php echo $CampaignName ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Start Date:</td><td><input type="text" name="start_date" value="<?php echo $StartDate ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>End Date:</td><td><input type="text" name="end_date" value="<?php echo $EndDate ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Is Active:</td><td><input type="text" name="is_active" value="<?php echo $IsActive ?>" /> 1 = True, 0 = False</td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Location:</td><td><input type="text" name="location" value="<?php echo $Location ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Discount:</td><td><input type="text" name="discount" value="<?php echo $Discount ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td colspan="3">
        "SUBMIT" = Save Changes Or "BACK"
        </td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>
        <input type="submit" name="edit_submit" value="Submit" />
        <input type="submit" name="edit_back" value="Back" />
        </td>
    </tr>
    </table>
</div>
</form>
</body>

<?php
}
$Submit = "";
$Back = "";

if (isset($_POST['edit_back']))
{
    $Back = $_POST['edit_back'];
}

if($Back == "Back")
{
    include("dbmaint.php");
}

if (isset($_POST['edit_submit']))
{
    $Submit = $_POST['edit_submit'];
}

if($Submit == "Submit")
{
    // Define the SQL Statement
	$strSQL = "UPDATE tblCampaigns SET
		NavisionCode = '" . $_POST["navision_code"] . "',
		CampaignName = '" . $_POST["campaign_name"] . "',
		StartDate = '" . $_POST["start_date"] . "',
		EndDate = '" . $_POST["end_date"] . "',
		IsActive = '" . $_POST["is_active"] . "',
		Location = '" . $_POST["location"] . "',
        Discount = '" . $_POST["discount"] . "'
        where RecordID = " . $_SESSION['edit_id'] . "";

    // echo $strSQL;

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    // Now Present the Information
    include("dbmaint.php");
}

?>

</html>
