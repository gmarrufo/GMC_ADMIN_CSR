<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

$_SESSION['Country_Customer'] = "NON_US";

if ((!isset($_GET['Action'])) || ($_GET['Action'] == 'NewOrder'))
{
	// CONNECT TO SQL SERVER DATABASE
	// $connNewOrder = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	// $selected = mssql_select_db($dbName, $connNewOrder);
	
	$connectionInfo = array( "Database"=>$dbName, "UID"=>$dbUser, "PWD"=>$dbPass);
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	
	if( $conn ) {
		 // echo "Connection established.<br />";
	}else{
		 echo "Connection could not be established.<br />";
		 die( print_r( sqlsrv_errors(), true));
	}	
	
    // OBTAIN LIST OF PRODUCTS
	/*
    $cboProducts1 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts2 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts3 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts4 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts5 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts6 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts7 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts8 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts9 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts10 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts11 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts12 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts13 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts14 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts15 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts16 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts17 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts18 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts19 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts20 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
	*/
	
    $cboProducts1 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts2 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts3 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts4 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts5 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts6 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts7 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts8 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts9 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts10 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts11 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts12 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts13 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts14 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts15 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts16 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts17 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts18 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts19 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
    $cboProducts20 = sqlsrv_query($conn, "SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
	
	// CLOSE DATABASE CONNECTION
	// mssql_close($connNewOrder);
	// sqlsrv_close($conn);
}

?>

<head>
	<title>Customer Management | Revitalash Administration | Calculate Boxes and Rates Prototype FeDex (International)</title>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="/csradmin/styles/revitalash.css" type="text/css" />
</head>

<body>

	<div id="wrapper">

		<?php include("includes/dspMasthead.php"); ?>

		<div id="content">

			<?php

            if ((!isset($_GET['Action'])))
            {
                include("input_prod_qty_int.php");
            }
			elseif ($_GET['Action'] == 'NewOrder')
			{
               if (isset($_POST['cmdContinue']))
               {
                   include("calculate_boxes.php");
               }
               else
               {
                   include("input_prod_qty_int.php");
               }
			}

			?>

		</div>

	</div>

</body>

</html>
