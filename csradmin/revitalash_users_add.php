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

if($Submit == "Submit")
{
    if ($_POST['revitalash_id'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['first_name'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['last_name'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['user_type_id'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['navision_user_id'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['email_address'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['password'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['is_active'] == '')
    {
       $bolSwitch = "False";
    }

    if($bolSwitch == "True")
    {
    // Define the SQL Statement
	$strSQL = "INSERT INTO tblRevitalash_Users
    (
		RevitalashID,
		FirstName,
		LastName,
		UserTypeID,
		NavisionUserID,
		EmailAddress,
		Password,
		IsActive
     )
     VALUES
     (
        '" . $_POST["revitalash_id"] . "',
		'" . $_POST["first_name"] . "',
		'" . $_POST["last_name"] . "',
		'" . $_POST["user_type_id"] . "',
        '" . $_POST["navision_user_id"] . "',
		'" . $_POST["email_address"] . "',
		'" . $_POST["password"] . "',
		'" . $_POST["is_active"] . "'
     )";

    // echo $strSQL;

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    echo '<script language="javascript">alert("User Added Sucessfully.")</script>;';

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
	$RevitalashID = "";
	$FirstName = "";
	$LastName = "";
	$UserTypeID = "";
	$NavisionUserID = "";
	$EmailAddress = "";
	$Password = "";
	$IsActive = "";

    if (isset($_POST['revitalash_id']))
    {
        $RevitalashID = $_POST['revitalash_id'];
    }
    if (isset($_POST['first_name']))
    {
        $FirstName = $_POST['first_name'];
    }
    if (isset($_POST['last_name']))
    {
        $LastName = $_POST['last_name'];
    }
    if (isset($_POST['user_type_id']))
    {
        $UserTypeID = $_POST['user_type_id'];
    }
    if (isset($_POST['navision_user_id']))
    {
        $NavisionUserID = $_POST['navision_user_id'];
    }
    if (isset($_POST['email_address']))
    {
        $EmailAddress = $_POST['email_address'];
    }
    if (isset($_POST['password']))
    {
        $Password = $_POST['password'];
    }
    if (isset($_POST['is_active']))
    {
        $IsActive = $_POST['is_active'];
    }

    $strBody = "";
    $strBody .= '<h1>Add Revitalash User</h1>';
    $strBody .= '<body>';
    $strBody .= '<p><font color="red">* Required</font></p>';
    $strBody .= '<form method="post" action="revitalash_users_add.php">';
    $strBody .= '<div class="bluediv_content">';
    $strBody .= '<table width="80%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3"> ';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Revitalash ID:</td><td><input type="text" name="revitalash_id" value="' . $RevitalashID . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>First Name:</td><td><input type="text" name="first_name" value="' . $FirstName . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Last Name:</td><td><input type="text" name="last_name" value="' . $LastName . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>User Type ID:</td><td><input type="text" name="user_type_id" value="' . $UserTypeID . '" /><font color="red">*</font> 1 = Rep, 2 = CSR</td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Navision User ID:</td><td><input type="text" name="navision_user_id" value="' . $NavisionUserID . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Email Address:</td><td><input type="text" size="50" name="email_address" value="' . $EmailAddress . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Password:</td><td><input type="text" size="50" name="password" value="' . $Password . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Is Active:</td><td><input type="text" name="is_active" value="' . $IsActive . '" /><font color="red">*</font> 1 = True, 0 = False</td>';
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
