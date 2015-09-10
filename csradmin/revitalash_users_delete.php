<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

if (isset($_GET['id']))
{
    $Id = $_GET['id'];
    $_SESSION['delete_id'] = $Id;

?>

<h1>Delete Revitalash User</h1>
<body>
<form method="post" action="revitalash_users_delete.php">
<div class="bluediv_content">
    <div align="center">
    <table width="80%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    <tr class="tdwhite" style="font-weight:bold;">
        <td>
        You are about to delete Record #: <?php echo $Id ?><br>Click "SUBMIT" to Confirm
        </td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>
        <input type="submit" name="delete_submit" value="Submit" />
        </td>
    </tr>
    </table>
    </div>
</div>
</form>
</body>

<?php
}
$Submit = "";

if (isset($_POST['delete_submit']))
{
    $Submit = $_POST['delete_submit'];
}

if($Submit == "Submit")
{
    // Define the SQL Statement
	$strSQL = "UPDATE tblRevitalash_Users SET IsActive = 'False' where RecordID = " . $_SESSION['delete_id'] . "";

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
