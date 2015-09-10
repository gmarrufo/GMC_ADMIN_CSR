<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

if (isset($_GET['id']))
{
    $Id = $_GET['id'];
    $_SESSION['edit_id'] = $Id;

	$strSQL = "SELECT * FROM tblRevitalash_Users WHERE RecordID = " . $Id . "";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetProducts))
	{
		$RecordID = $row["RecordID"];
		$RevitalashID = $row["RevitalashID"];
		$FirstName = $row["FirstName"];
		$LastName = $row["LastName"];
		$UserTypeID = $row["UserTypeID"];
		$NavisionUserID = $row["NavisionUserID"];
		$EmailAddress = $row["EMailAddress"];
		$Password = $row["Password"];
		$IsActive = $row["IsActive"];
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

?>

<h1>Edit Revitalash User</h1>
<body>
<form method="post" action="revitalash_users_edit.php">
<div class="bluediv_content">
    <table width="80%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="20%">RecordID:</td><td><?php echo $RecordID ?></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Revitalash ID:</td><td><input type="text" name="revitalash_id" value="<?php echo $RevitalashID ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>First Name:</td><td><input type="text" name="first_name" value="<?php echo $FirstName ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Last Name:</td><td><input type="text" name="last_name" value="<?php echo $LastName ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>User Type ID:</td><td><input type="text" name="user_type_id" value="<?php echo $UserTypeID ?>" /> 1 = Rep, 2 = CSR</td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Navision User ID:</td><td><input type="text" name="navision_user_id" value="<?php echo $NavisionUserID ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Email Address:</td><td><input type="text" size="50" name="email_address" value="<?php echo $EmailAddress ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Password:</td><td><input type="text" size="50" name="password" value="<?php echo $Password ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Is Active:</td><td><input type="text" name="is_active" value="<?php echo $IsActive ?>" /> 1 = True, 0 = False</td>
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
	$strSQL = "UPDATE tblRevitalash_Users SET
		RevitalashID = '" . $_POST["revitalash_id"] . "',
		FirstName = '" . $_POST["first_name"] . "',
		LastName = '" . $_POST["last_name"] . "',
		UserTypeID = '" . $_POST["user_type_id"] . "',
		NavisionUserID = '" . $_POST["navision_user_id"] . "',
		EmailAddress = '" . $_POST["email_address"] . "',
		Password = '" . $_POST["password"] . "',
		IsActive = '" . $_POST["is_active"] . "'
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
