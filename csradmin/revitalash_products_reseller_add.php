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
    if ($_POST['product_id'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['qty_required'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['discount_price'] == '')
    {
       $bolSwitch = "False";
    }

    if($bolSwitch == "True")
    {
    // Define the SQL Statement
	$strSQL = "INSERT INTO tblProducts_ResellerTier
    (
        ProductID,
		QtyRequired,
		DiscountPrice
     )
     VALUES
     (
        '" . $_POST["product_id"] . "',
		'" . $_POST["qty_required"] . "',
		'" . $_POST["discount_price"] . "'
     )";

    // echo $strSQL;

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    echo '<script language="javascript">alert("Product Reseller Tier Added Sucessfully.")</script>;';

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
    $ProductID = "";
	$QtyRequired= "";
	$DiscountPrice = "";

    if (isset($_POST['product_id']))
    {
        $ProductID = $_POST['product_id'];
    }
    if (isset($_POST['qty_required']))
    {
        $QtyRequired = $_POST['qty_required'];
    }
    if (isset($_POST['discount_price']))
    {
        $DiscountPrice = $_POST['discount_price'];
    }

    $strBody = "";
    $strBody .= '<h1>Add Revitalash Product Reseller Tier</h1>';
    $strBody .= '<body>';
    $strBody .= '<p><font color="red">* Required</font></p>';
    $strBody .= '<form method="post" action="revitalash_products_reseller_add.php">';
    $strBody .= '<div class="bluediv_content">';
    $strBody .= '<table width="80%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3"> ';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Product ID:</td><td><input type="text" name="product_id" value="' . $ProductID . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Qty Required:</td><td><input type="text" name="qty_required" value="' . $QtyRequired . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Discount Price:</td><td><input type="text" name="discount_price" value="' . $DiscountPrice . '" /><font color="red">*</font></td>';
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
