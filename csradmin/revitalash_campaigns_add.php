<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

$Submit = "";
$bolSwitch = "True";

if (isset($_POST['add_submit']))
{
    $Submit = $_POST['add_submit'];
}

// GMC - 02/03/14 - Add Discount to tblCampaigns
if($Submit == "Submit")
{
    if ($_POST['navision_code'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['campaign_name'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['start_date'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['end_date'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['is_active'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['location'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['discount'] == '')
    {
       $bolSwitch = "False";
    }

    if($bolSwitch == "True")
    {
    // Define the SQL Statement
	$strSQL = "INSERT INTO tblCampaigns
    (
		NavisionCode,
		CampaignName,
		StartDate,
		EndDate,
		IsActive,
		Location,
        Discount
     )
     VALUES
     (
        '" . $_POST["navision_code"] . "',
		'" . $_POST["campaign_name"] . "',
		'" . $_POST["start_date"] . "',
		'" . $_POST["end_date"] . "',
        '" . $_POST["is_active"] . "',
		'" . $_POST["location"] . "',
		'" . $_POST["discount"] . "'
     )";

    // echo $strSQL;

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    echo '<script language="javascript">alert("Campaign Added Sucessfully.")</script>;';

     include("dbmaint.php");

    }
    else
    {
        echo '<script language="javascript">alert("You have not entered one or more of the required fields, please try again.")</script>;';
    }
}

$Back = "";

if (isset($_POST['add_back']))
{
    $Back = $_POST['add_back'];
}

if($Back == "Back")
{
    include("dbmaint.php");
}
else
{
    if($Submit == "")
    {
	$NavisionCode = "";
	$CampaignName = "";
	$StartDate = "";
	$EndDate = "";
	$IsActive = "";
	$Location = "";
    $Discount = "";

    if (isset($_POST['navision_code']))
    {
        $NavisionCode = $_POST['navision_code'];
    }
    if (isset($_POST['campaign_name']))
    {
        $CampaignName = $_POST['campaign_name'];
    }
    if (isset($_POST['start_date']))
    {
        $StartDate = $_POST['start_date'];
    }
    if (isset($_POST['end_date']))
    {
        $EndDate = $_POST['end_date'];
    }
    if (isset($_POST['is_active']))
    {
        $IsActive = $_POST['is_active'];
    }
    if (isset($_POST['location']))
    {
        $Location = $_POST['location'];
    }
    if (isset($_POST['discount']))
    {
        $Discount = $_POST['discount'];
    }

    $strBody = "";
    $strBody .= '<h1>Add Revitalash Campaign</h1>';
    $strBody .= '<body>';
    $strBody .= '<p><font color="red">* Required</font></p>';
    $strBody .= '<form method="post" action="revitalash_campaigns_add.php">';
    $strBody .= '<div class="bluediv_content">';
    $strBody .= '<table width="80%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3"> ';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Navision Code:</td><td><input type="text" name="navision_code" value="' . $NavisionCode . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Campaign Name:</td><td><input type="text" name="campaign_name" value="' . $CampaignName . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Start Date:</td><td><input type="text" name="start_date" value="' . $StartDate . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>End Date:</td><td><input type="text" name="end_date" value="' . $EndDate . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Is Active:</td><td><input type="text" name="is_active" value="' . $IsActive . '" /><font color="red">*</font> 1 = True, 0 = False</td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Location:</td><td><input type="text" name="location" value="' . $Location . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Discount:</td><td><input type="text" name="discount" value="' . $Discount . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td colspan="3">';
    $strBody .= '"SUBMIT" = Save New Record Or "BACK"';
    $strBody .= '</td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>';
    $strBody .= '<input type="submit" name="add_submit" value="Submit" />';
    $strBody .= '<input type="submit" name="add_back" value="Back" />';
    $strBody .= '</td>';
    $strBody .= '</tr>';
    $strBody .= '</table>';
    $strBody .= '</div>';
    $strBody .= '</form>';
    $strBody .= '</body>';

    echo $strBody;
    }
}

?>

</html>
