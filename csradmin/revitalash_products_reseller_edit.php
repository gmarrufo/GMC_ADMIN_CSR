<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

if (isset($_GET['id']))
{
    $Id = $_GET['id'];
    $_SESSION['edit_id'] = $Id;

	$strSQL = "SELECT * FROM tblProducts_ResellerTier WHERE RecordID = " . $Id . "";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetProducts))
	{
		$RecordID = $row["RecordID"];
		$ProductID = $row["ProductID"];
		$QtyRequired = $row["QtyRequired"];
		$DiscountPrice = $row["DiscountPrice"];
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

?>

<h1>Edit Revitalash Product Reseller Tier</h1>
<body>
<form method="post" action="revitalash_products_reseller_edit.php">
<div class="bluediv_content">
    <table width="80%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="20%">RecordID:</td><td><?php echo $RecordID ?></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Product ID:</td><td><input type="text" name="product_id" value="<?php echo $ProductID ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Qty Required:</td><td><input type="text" name="qty_required" value="<?php echo $QtyRequired ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Discount Price:</td><td><input type="text" name="discount_price" value="<?php echo $DiscountPrice ?>" /></td>
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
    // Now Present the Information
    include("dbmaint.php");
}

if (isset($_POST['edit_submit']))
{
    $Submit = $_POST['edit_submit'];
}

if($Submit == "Submit")
{
    // Define the SQL Statement
	$strSQL = "UPDATE tblProducts_ResellerTier SET
        ProductID = '" . $_POST["product_id"] . "',
		QtyRequired = '" .$_POST["qty_required"] . "',
		DiscountPrice = '" . $_POST["discount_price"] . "'
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
