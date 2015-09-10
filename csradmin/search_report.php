<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

if ((!isset($_SESSION['IsRevitalashLoggedIn'])) || ($_SESSION['IsRevitalashLoggedIn'] == 0))
{
	header("Location: login.php");
	exit;
}

if($_GET['ReportFilename'] != '')
{
    // CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);

    $file = "Order,Entered By,Customer,Type,State,Country,CC#,Date,Status,Ship From,Shipping,Total,Email\r\n";
    $result = mssql_query($_SESSION['SearchReportSQL']);
    $strCustomerType = "";

    while($row = mssql_fetch_array($result))
    {
	    if (strlen($row["CompanyName"]) <= 1 || $row["CompanyName"] == 'Individual')
	    {
			if($row["CustomerTypeID"] == 1)
			{
                 $strCustomerType = "Consumer";
                 $file .= $row["RecordID"] . ',' . $row["Seller"] . ',' . $row["FirstName"] . ' ' . $row["LastName"] . ',' . $strCustomerType . ',' . $row["State"] . ',' . $row["CountryCode"] . ',' . $row["CCNumber"] . ',' . $row["OrderDate"] . ',' . $row["StatusDisplay"] . ',' . $row["Location"] . ',' . $row["ShippingMethodDisplay"] . ',' . number_format($row["OrderTotal"], 2, '.', '') . ',' . $row["EmailAddress"] ."\r\n";
            }
            elseif($row["CustomerTypeID"] == 2)
            {
                 $strCustomerType = "Spa/Reseller";
                 $file .= $row["RecordID"] . ',' . $row["Seller"] . ',' . $row["FirstName"] . ' ' . $row["LastName"] . ',' . $strCustomerType . ',' . $row["State"] . ',' . $row["CountryCode"] . ',' . $row["CCNumber"] . ',' . $row["OrderDate"] . ',' . $row["StatusDisplay"] . ',' . $row["Location"] . ',' . $row["ShippingMethodDisplay"] . ',' . number_format($row["OrderTotal"], 2, '.', '') . ',' . $row["EmailAddress"] ."\r\n";
            }
            elseif($row["CustomerTypeID"] == 3)
            {
                 $strCustomerType = "Distributor";
                 $file .= $row["RecordID"] . ',' . $row["Seller"] . ',' . $row["FirstName"] . ' ' . $row["LastName"] . ',' . $strCustomerType . ',' . $row["State"] . ',' . $row["CountryCode"] . ',' . $row["CCNumber"] . ',' . $row["OrderDate"] . ',' . $row["StatusDisplay"] . ',' . $row["Location"] . ',' . $row["ShippingMethodDisplay"] . ',' . number_format($row["OrderTotal"], 2, '.', '') . ',' . $row["EmailAddress"] ."\r\n";
            }
            elseif($row["CustomerTypeID"] == 4)
            {
                 $strCustomerType = "Rep";
                 $file .= $row["RecordID"] . ',' . $row["Seller"] . ',' . $row["FirstName"] . ' ' . $row["LastName"] . ',' . $strCustomerType . ',' . $row["State"] . ',' . $row["CountryCode"] . ',' . $row["CCNumber"] . ',' . $row["OrderDate"] . ',' . $row["StatusDisplay"] . ',' . $row["Location"] . ',' . $row["ShippingMethodDisplay"] . ',' . number_format($row["OrderTotal"], 2, '.', '') . ',' . $row["EmailAddress"] ."\r\n";
            }
	    }
	    else
	    {
			if($row["CustomerTypeID"] == 1)
			{
                 $strCustomerType = "Consumer";
                 $file .= $row["RecordID"] . ',' . $row["Seller"] . ',' . $row["CompanyName"] . ','  . $strCustomerType . ',' . $row["State"] . ',' . $row["CountryCode"] . ',' . $row["CCNumber"] . ',' . $row["OrderDate"] . ',' . $row["StatusDisplay"] . ',' . $row["Location"] . ',' . $row["ShippingMethodDisplay"] . ',' . number_format($row["OrderTotal"], 2, '.', '') . ',' . $row["EmailAddress"] ."\r\n";
            }
            elseif($row["CustomerTypeID"] == 2)
            {
                 $strCustomerType = "Spa/Reseller";
                 $file .= $row["RecordID"] . ',' . $row["Seller"] . ',' . $row["CompanyName"] . ','  . $strCustomerType . ',' . $row["State"] . ',' . $row["CountryCode"] . ',' . $row["CCNumber"] . ',' . $row["OrderDate"] . ',' . $row["StatusDisplay"] . ',' . $row["Location"] . ',' . $row["ShippingMethodDisplay"] . ',' . number_format($row["OrderTotal"], 2, '.', '') . ',' . $row["EmailAddress"] ."\r\n";
            }
            elseif($row["CustomerTypeID"] == 3)
            {
                 $strCustomerType = "Distributor";
                 $file .= $row["RecordID"] . ',' . $row["Seller"] . ',' . $row["CompanyName"] . ',' . $strCustomerType . ',' . $row["State"] . ',' . $row["CountryCode"] . ',' . $row["CCNumber"] . ',' . $row["OrderDate"] . ',' . $row["StatusDisplay"] . ',' . $row["Location"] . ',' . $row["ShippingMethodDisplay"] . ',' . number_format($row["OrderTotal"], 2, '.', '') . ',' . $row["EmailAddress"] ."\r\n";
            }
            elseif($row["CustomerTypeID"] == 4)
            {
                 $strCustomerType = "Rep";
                 $file .= $row["RecordID"] . ',' . $row["Seller"] . ',' . $row["CompanyName"] . ',' . $strCustomerType . ',' . $row["State"] . ',' . $row["CountryCode"] . ',' . $row["CCNumber"] . ',' . $row["OrderDate"] . ',' . $row["StatusDisplay"] . ',' . $row["Location"] . ',' . $row["ShippingMethodDisplay"] . ',' . number_format($row["OrderTotal"], 2, '.', '') . ',' . $row["EmailAddress"] ."\r\n";
            }
	    }
    }

    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=". $_GET['ReportFilename'] ."_csvfile.csv");
    echo $file;
}

// GMC - 06/08/09 - Search Criteria for Orders
// GMC - 06/15/09 - Add CCNumber to Search
// GMC - 07/15/09 - Add Country to Search
elseif ($_GET['OrderDateFrom'] != '' || $_GET['OrderDateTo'] != '' || $_GET['radSelect'] != '' || $_GET['OrderBy'] != '' || $_GET['CCNumber'] != '')
{
    // Set Variables
	if (isset($_GET['OrderDateFrom']))
         $OrderDateFrom = $_GET['OrderDateFrom'];
	else
         $OrderDateFrom = '';

	if (isset($_GET['OrderDateTo']))
         $OrderDateTo = $_GET['OrderDateTo'];
	else
         $OrderDateTo = '';

    // GMC - 07/15/09 - Add Country to Search
	if (isset($_GET['radSelect']))
	{
         if($_GET['radSelect'] == "USSelect")
         {
              $CountrySelect = "US";
         }
         else
         {
              $CountrySelect = "NOT_US";
         }
    }
    else
    {
         $CountrySelect = '';
    }

	if (isset($_GET['OrderBy']))
         $OrderBy = $_GET['OrderBy'];
	else
         $OrderBy = '';

    // GMC - 06/15/09 - Add CCNumber to Search
	if (isset($_GET['CCNumber']))
         $CCNumber = $_GET['CCNumber'];
	else
         $CCNumber = '';

    $strSQL = '';
	$tblOrderList = '';
    $_SESSION['SearchReportSQL'] ='';

	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);

    // GMC - 06/15/09 - Add CCNumber to Search
    if($OrderBy != "" && $CCNumber != '')
    {
        echo '<script language="javascript">alert("You can not do both, select either OrderBy or CCNumber")</script>;';
    }
    elseif($OrderBy == "" && $CCNumber == '')
    {
        echo '<script language="javascript">alert("You must enter at least one, select either OrderBy or CCNumber")</script>;';
    }
    else
    {
         if($OrderBy != '')
         {
           if($_SESSION['UserTypeID'] == 2)
           {
             if($OrderDateFrom == '' && $OrderDateTo == '') // 0-0
             {
                 if($OrderBy == 'All')
                 {
                     if($CountrySelect == "US")
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
                     		 (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US'
                             ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
                     		 (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US'
		                     ORDER BY tblOrders.RecordID DESC";
                     }
                     else
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
                     		 (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US'
                             ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
                     		 (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US'
		                     ORDER BY tblOrders.RecordID DESC";
                     }
                 }
                 else
                 {
                     if($CountrySelect == "US")
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
                     		 (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" .$OrderBy . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
                     		 (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" .$OrderBy . "' ORDER BY tblOrders.RecordID DESC";
                     }
                     else
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
                     		 (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" .$OrderBy . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
                     		 (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" .$OrderBy . "' ORDER BY tblOrders.RecordID DESC";
                     }
                 }
            }
            else if($OrderDateFrom == '' && $OrderDateTo != '') //0-1
            {
                if($OrderBy == 'All')
                {
                     if($CountrySelect == "US")
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                     }
                     else
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                     }
                 }
                 else
                 {
                     if($CountrySelect == "US")
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" .$OrderBy . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE  tblCustomers.CountryCode = 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" .$OrderBy . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                     }
                     else
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" .$OrderBy . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE  tblCustomers.CountryCode <> 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" .$OrderBy . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                     }
                 }
           }
           else if($OrderDateFrom != '' && $OrderDateTo == '') // 1-0
           {
               if($OrderBy == 'All')
               {
                     if($CountrySelect == "US")
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";
                     }
                     else
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";
                     }
                }
                else
                {
                     if($CountrySelect == "US")
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";
                     }
                     else
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";
                     }
                }
             }
             else if($OrderDateFrom != '' && $OrderDateTo != '') // 1-1
             {
                 if($OrderBy == 'All')
                 {
                     if($CountrySelect == "US")
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";
                     }
                     else
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";
                     }
                 }
                 else
                 {
                     if($CountrySelect == "US")
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";
                     }
                     else
                     {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";
                     }
                 }
              }
            }
            
            // GMC - 03/17/14 - New User ID 3 Sales Specialist
            // else if($_SESSION['UserTypeID'] == 1)
            else if($_SESSION['UserTypeID'] == 1 || $_SESSION['UserTypeID'] == 3)
            {
                if($OrderDateFrom == '' && $OrderDateTo == '') // 0-0
                {
                     if($OrderBy == 'All')
                     {
                        if($CountrySelect == "US")
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' ORDER BY tblOrders.RecordID DESC";
                        }
                        else
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' ORDER BY tblOrders.RecordID DESC";
                        }
                     }
                     else
                     {
                        if($CountrySelect == "US")
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";
                        }
                        else
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";
                        }
                     }
                }
                else if($OrderDateFrom == '' && $OrderDateTo != '') //0-1
                {
                    if($OrderBy == 'All')
                    {
                        if($CountrySelect == "US")
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US'AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                        }
                        else
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US'AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                        }
                    }
                    else
                    {
                        if($CountrySelect == "US")
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";
                        }
                        else
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";
                        }
                    }
                }
                else if($OrderDateFrom != '' && $OrderDateTo == '') // 1-0
                {
                    if($OrderBy == 'All')
                    {
                        if($CountrySelect == "US")
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' ORDER BY tblOrders.RecordID DESC";
                        }
                        else
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' ORDER BY tblOrders.RecordID DESC";
                        }
                    }
                    else
                    {
                        if($CountrySelect == "US")
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";
                        }
                        else
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' ORDER BY tblOrders.RecordID DESC";
                        }
                    }
                }
                else if($OrderDateFrom != '' && $OrderDateTo != '') // 1-1
                {
                    if($OrderBy == 'All')
                    {
                        if($CountrySelect == "US")
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                        }
                        else
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                        }
                    }
                    else
                    {
                        if($CountrySelect == "US")
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                        }
                        else
                        {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName = '" . $OrderBy . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                        }
                    }
               }
            }
         }

         if($CCNumber != '')
         {
           if($_SESSION['UserTypeID'] == 2)
           {
             if($OrderDateFrom == '' && $OrderDateTo == '') // 0-0
             {
                 if($CountrySelect == "US")
                 {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
		                     INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" .$CCNumber . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
		                     INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" .$CCNumber . "' ORDER BY tblOrders.RecordID DESC";
                 }
                 else
                 {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
		                     INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" .$CCNumber . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
                             tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
		                     INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" .$CCNumber . "' ORDER BY tblOrders.RecordID DESC";
                 }
            }
            else if($OrderDateFrom == '' && $OrderDateTo != '') //0-1
            {
                 if($CountrySelect == "US")
                 {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" .$CCNumber . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" .$CCNumber . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                 }
                 else
                 {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" .$CCNumber . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
              		        tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
                	        FROM tblOrders
                 	        INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" .$CCNumber . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                 }
           }
           else if($OrderDateFrom != '' && $OrderDateTo == '') // 1-0
           {
                 if($CountrySelect == "US")
                 {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";
                 }
                 else
                 {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "'";

                 }
             }
             else if($OrderDateFrom != '' && $OrderDateTo != '') // 1-1
             {
                 if($CountrySelect == "US")
                 {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";
                 }
                 else
                 {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay,
		                    tblOrders.OrderTotal, LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "'";
                 }
              }
            }
            
            // GMC - 03/17/14 - New User ID 3 Sales Specialist
            // else if($_SESSION['UserTypeID'] == 1)
            else if($_SESSION['UserTypeID'] == 1 || $_SESSION['UserTypeID'] == 3)
            {
                if($OrderDateFrom == '' && $OrderDateTo == '') // 0-0
                {
                   if($CountrySelect == "US")
                   {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";
                   }
                   else
                   {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                             $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                     conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                     LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                     FROM tblOrders
		                     INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                             INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                             INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                     INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                     INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                     WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";
                   }
                }
                else if($OrderDateFrom == '' && $OrderDateTo != '') //0-1
                {
                   if($CountrySelect == "US")
                   {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";
                   }
                   else
                   {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";
                   }
                }
                else if($OrderDateFrom != '' && $OrderDateTo == '') // 1-0
                {
                   if($CountrySelect == "US")
                   {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";
                   }
                   else
                   {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' ORDER BY tblOrders.RecordID DESC";
                   }
                }
                else if($OrderDateFrom != '' && $OrderDateTo != '') // 1-1
                {
                   if($CountrySelect == "US")
                   {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode = 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                   }
                   else
                   {
                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $strSQL = "SELECT TOP 100 tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";

                             // GMC - 01/13/10 - Ship From Location
                             // GMC - 01/26/10 - Email in Order List and Order Excel
                            $_SESSION['SearchReportSQL'] = "SELECT tblOrders.RecordID, tblCustomers.CustomerTypeID, tblCustomers.EmailAddress, tblCustomers.State, tblCustomers.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, RIGHT(tblCustomers_PayMethods.CCNumber, 4) AS CCNumber, tblOrders.OrderDate,
		                    conOrderStatus.StatusDisplay, conShippingMethods.ShippingMethodDisplay, tblOrders.OrderTotal,
		                    LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller,
               		        (SELECT TOP 1 tblOrders_Items.Location from tblOrders_Items where tblOrders.RecordID = tblOrders_Items.OrderID) as Location
		                    FROM tblOrders
		                    INNER JOIN tblCustomers ON tblCustomers.RecordID = tblOrders.CustomerID
                            INNER JOIN tblCustomers_PayMethods ON tblCustomers_PayMethods.RecordID = tblOrders.PayMethodID
                            INNER JOIN conOrderStatus ON conOrderStatus.RecordID = tblOrders.OrderStatusID
		                    INNER JOIN conShippingMethods ON conShippingMethods.RecordID = tblOrders.ShipMethodID
		                    INNER JOIN tblRevitalash_Users ON tblOrders.RecordCreatedBy = tblRevitalash_Users.RecordID
		                    WHERE tblCustomers.CountryCode <> 'US' AND tblCustomers.SalesRepID = '" . $_SESSION['UserID'] . "' AND RIGHT(tblCustomers_PayMethods.CCNumber, 4) = '" . $CCNumber . "' AND tblOrders.OrderDate >= '" . $OrderDateFrom . "' AND tblOrders.OrderDate <= '" . $OrderDateTo . "' ORDER BY tblOrders.RecordID DESC";
                   }
               }
            }
         }

    $result = mssql_query($strSQL);

    /*
    print_r($rs);
    echo "";
    var_dump($rs);
    */

    while($row = mssql_fetch_array($result))
    {
			 $tblOrderList .= '<tr class="tdwhite">';
			 $tblOrderList .= '<td width="*"><a href="/csradmin/orders.php?Action=Detail&OrderID=' . $row["RecordID"] . '">' . $row["RecordID"] . '</a></td>';
			 $tblOrderList .= '<td width="*">' . $row["Seller"] . '</td>';

             if (strlen($row["CompanyName"]) <= 1 || $row["CompanyName"] == 'Individual')
             {
				$tblOrderList .= '<td width="250">' . $row["FirstName"] . ' ' . $row["LastName"] . '</td>';
             }
             else
             {
            	$tblOrderList .= '<td width="250">' . $row["CompanyName"] . '</td>';
			 }


			 if($row["CustomerTypeID"] == 1)
			 {
            	$tblOrderList .= '<td>Consumer</td>';
             }
             elseif($row["CustomerTypeID"] == 2)
             {
            	$tblOrderList .= '<td>Spa/Reseller</td>';
             }
             elseif($row["CustomerTypeID"] == 3)
             {
            	$tblOrderList .= '<td>Distributor</td>';
             }
             elseif($row["CustomerTypeID"] == 4)
             {
            	$tblOrderList .= '<td>Rep</td>';
             }

			 $tblOrderList .= '<td>' . $row["State"] . '</td>';
			 $tblOrderList .= '<td>' . $row["CountryCode"] . '</td>';
			 $tblOrderList .= '<td>' . $row["CCNumber"] . '</td>';
			 $tblOrderList .= '<td>' . date("F d, Y", strtotime($row["OrderDate"])) . '</td>';
			 $tblOrderList .= '<td>' . $row["StatusDisplay"] . '</td>';

             // GMC - 01/13/10 - Ship From Location
             $tblOrderList .= '<td>' . $row["Location"] . '</td>';

			 $tblOrderList .= '<td>' . $row["ShippingMethodDisplay"] . '</td>';
			 $tblOrderList .= '<td>$' . number_format($row["OrderTotal"], 2, '.', '') . '</td>';

            // GMC - 01/26/10 - Email in Order List and Order Excel
            $tblOrderList .= '<td>' . wordwrap($row["EmailAddress"], 20, "\n", true) . '</td>';

			 $tblOrderList .= '</tr>';
    }

    }


	// CLOSE DATABASE CONNECTION
	mssql_close($connOrders);

}

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
			<?php
            if($_GET['ReportFilename'] != '')
            {
            }
            // GMC - 06/08/09 - Search Criteria for Orders
            elseif ($_GET['OrderDateFrom'] != '' || $_GET['OrderDateTo'] != '' || $_GET['OrderBy'] != '' || $_GET['CCNumber'] != '')
            {
                 if($tblOrderList != '')
                 {
					include("includes/dspOrders_Index.php");
                 }
                 else
                 {
                	include("includes/dspOrders_NotFound.php");
                 }
            }
			?>
		</div>
	</div>
</body>
</html>
