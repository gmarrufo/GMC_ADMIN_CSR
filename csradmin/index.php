<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

// LOGOUT
if (isset($_GET['logout']))
	session_unset();

//ATTEMPT TO LOGIN
if (isset($_POST['cmdLogin']))
	include("modules/revitalash_login.php");

if ((!isset($_SESSION['IsRevitalashLoggedIn'])) || ($_SESSION['IsRevitalashLoggedIn'] == 0))
{
	header("Location: login.php");
	exit;
}

// GMC - 02/01/11 - Order Closed By CSR ADMIN Partner - Rep
$_SESSION['OrderClosedBy'] = "";

// GMC - 12/03/08 - Domestic vs International 3rd Phase
/*

// CONNECT TO SQL SERVER DATABASE
$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
$selected = mssql_select_db($dbName, $connOrders);
	
$qryOrderList = mssql_init("spOrders_RecentList", $connOrders);
mssql_bind($qryOrderList, "@prmUserID", $_SESSION['UserID'], SQLINT2);

$tblOrderList = '';

// EXECUTE QUERY
$rs = mssql_execute($qryOrderList);

if (is_resource($rs) && mssql_num_rows($rs) > 0)
{

	while($row = mssql_fetch_array($rs))
	{
		$tblOrderList .= '<tr class="tdwhite">';
		$tblOrderList .= '<td width="*"><a href="/csradmin/orders.php?Action=Detail&OrderID=' . $row["RecordID"] . '">' . $row["RecordID"] . '</a></td>';
		$tblOrderList .= '<td width="*">' . $row["Seller"] . '</td>';
		if (strlen($row["CompanyName"]) <= 1 || $row["CompanyName"] == 'Individual')
			$tblOrderList .= '<td width="250">' . $row["FirstName"] . ' ' . $row["LastName"] . '</td>';
		else
			$tblOrderList .= '<td width="250">' . $row["CompanyName"] . '</td>';
		$tblOrderList .= '<td>' . date("F d, Y", strtotime($row["OrderDate"])) . '</td>';
		$tblOrderList .= '<td>' . $row["StatusDisplay"] . '</td>';
		$tblOrderList .= '<td>' . $row["ShippingMethodDisplay"] . '</td>';
		$tblOrderList .= '<td>$' . number_format($row["OrderTotal"], 2, '.', '') . '</td>';
		$tblOrderList .= '</tr>';
	}
	
	mssql_next_result($rs);

}

// CLOSE DATABASE CONNECTION
mssql_close($connOrders);

*/

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
  
		<?php // include("includes/dspMasthead.php"); ?>
		
        <!-- GMC - 12/03/08 - Domestic vs International 3rd Phase
		<div id="content">

			<h1>Recent Orders</h1>
            
            <p>Below is a list of recent orders. To view a specific order, please use the view button below.</p>
            
            <form action="/csradmin/orders.php" method="get">
            <input type="hidden" name="Action" value="Detail">
            <div class="bluediv_header"><input type="text" name="OrderID" size="6" /> <input type="submit" name="cmdViewOrder" value="View" class="formSubmit_small" /></div>
            </form>
                        
            <div class="bluediv_content">
            
                <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    
                <tr class="tdwhite" style="font-weight:bold;">
                    <td width="75">Order</td>
                    <td width="100">Entered By</td>
                    <td width="*">Customer</td>
                    <td width="150">Date</td>
                    <td width="125">Status</td>
                    <td width="150">Shipping</td>
                    <td width="80">Total</td>
                </tr>
                
                <?php // echo $tblOrderList; ?>
               
                </table>
            
            </div>

            <p>&nbsp;</p>
		
		</div>
        -->

		<?php include("customers.php"); ?>

	</div>

</body>

</html>
