<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

// GMC - 09/23/09 - Show FedEx Netherlands
$ShowFedExEU = "";

if ((!isset($_SESSION['IsRevitalashLoggedIn'])) || ($_SESSION['IsRevitalashLoggedIn'] == 0))
{
	header("Location: login.php");
	exit;
}

if ((!isset($_GET['Action'])) || ($_GET['Action'] == 'Index') || ($_GET['Action'] == 'Overview'))
{
	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
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
			
            // GMC - 07/29/09 - Date and Time show by JS
            // $tblOrderList .= '<td>' . date("F d, Y", strtotime($row["OrderDate"])) . '</td>';
            $tblOrderList .= '<td>' . $row["OrderDate"] . '</td>';

            $tblOrderList .= '<td>' . $row["StatusDisplay"] . '</td>';
            
            // GMC - 12/25/09 - Ship From Location
            $tblOrderList .= '<td>' . $row["Location"] . '</td>';

			$tblOrderList .= '<td>' . $row["ShippingMethodDisplay"] . '</td>';

            // GMC - 04/10/12 - SubTotal OrderRecent
			$tblOrderList .= '<td>$' . number_format($row["OrderSubTotal"], 2, '.', '') . '</td>';

			$tblOrderList .= '<td>$' . number_format($row["OrderTotal"], 2, '.', '') . '</td>';

            // GMC - 01/26/10 - Email in Order List and Order Excel
            $tblOrderList .= '<td>' . wordwrap($row["EmailAddress"], 20, "\n", true) . '</td>';

			$tblOrderList .= '</tr>';
		}
		
		mssql_next_result($rs);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connOrders);
}
else if ($_GET['Action']== 'Tradeshow')
{
	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);
		
	$qryOrderList = mssql_init("spOrders_RecentTradeshowList", $connOrders);
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

            // GMC - 07/29/09 - Date and Time show by JS
            // $tblOrderList .= '<td>' . date("F d, Y", strtotime($row["OrderDate"])) . '</td>';
            $tblOrderList .= '<td>' . $row["OrderDate"] . '</td>';

            $tblOrderList .= '<td>' . $row["StatusDisplay"] . '</td>';
            
            // GMC - 12/25/09 - Ship From Location
            $tblOrderList .= '<td>' . $row["Location"] . '</td>';

			$tblOrderList .= '<td>' . $row["ShippingMethodDisplay"] . '</td>';
			$tblOrderList .= '<td>$' . number_format($row["OrderTotal"], 2, '.', '') . '</td>';
			$tblOrderList .= '</tr>';
		}
		
		mssql_next_result($rs);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connOrders);
}

// GMC - 03/31/14 - Create Order Summary for CSRADMIN
else if ($_GET['Action']== 'Summary')
{
	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);

	$qrySummaryList = mssql_init("spOrders_SummaryList", $connOrders);

	$tblSummaryList = '';

	// EXECUTE QUERY
	$rs = mssql_execute($qrySummaryList);

	if (is_resource($rs) && mssql_num_rows($rs) > 0)
	{
		while($row = mssql_fetch_array($rs))
		{
			$tblSummaryList .= '<tr class="tdwhite">';
			$tblSummaryList .= '<td width="*"><a href="/csradmin/orders.php?Action=Detail&RecordCreatedBy=' . $row["OrderBy"] . '">' . $row["FirstName"] . ' ' . $row["LastName"] . '</a></td>';
			$tblSummaryList .= '<td width="*">' . $row["NumberOfOrders"] . '</td>';
			$tblSummaryList .= '<td>$' . number_format($row["ValueOfOrders"], 2, '.', ',') . '</td>';
			$tblSummaryList .= '</tr>';
		}

		mssql_next_result($rs);
	}

 	$qrySummaryTotal = mssql_init("spOrders_SummaryTotal", $connOrders);
	$tblSummaryTotal = '';

	// EXECUTE QUERY
	$rst = mssql_execute($qrySummaryTotal);

	if (is_resource($rst) && mssql_num_rows($rst) > 0)
	{
		while($row = mssql_fetch_array($rst))
		{
			$tblSummaryTotal .= '<tr class="tdwhite">';
			$tblSummaryTotal .= '<td width="*">' . $row["TotalNumberOfOrders"] . '</td>';
			$tblSummaryTotal .= '<td>$' . number_format($row["TotalValueOfOrders"], 2, '.', ',') . '</td>';
			$tblSummaryTotal .= '</tr>';
		}

		mssql_next_result($rst);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connOrders);
}

// GMC - 03/31/14 - Create Order Summary for CSRADMIN
else if ($_GET['Action']== 'SummarySearch')
{
	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);

    // Set Variables
	if (isset($_GET['SummaryOrderDateFrom']))
         $SummaryOrderDateFrom = $_GET['SummaryOrderDateFrom'];
	else
         $SummaryOrderDateFrom = '';

	if (isset($_GET['SummaryOrderDateTo']))
         $SummaryOrderDateTo = $_GET['SummaryOrderDateTo'];
	else
         $SummaryOrderDateTo = '';

    if($SummaryOrderDateFrom == '' && $SummaryOrderDateTo == '') // 0-0
    {
        $strSQLA = 'select count(*) as NumberOfOrders, sum(OrderTotal) as ValueOfOrders, B.FirstName as FirstName, B.LastName as LastName, RecordCreatedBy as OrderBy from tblOrders A inner join tblRevitalash_users B on A.RecordCreatedBy = b.RecordID group by RecordCreatedBy, FirstName, LastName order by RecordCreatedBy ASC';
        $strSQLB = 'select count(*) as TotalNumberOfOrders, sum(OrderTotal) as TotalValueOfOrders from tblOrders';
    }
    else if($SummaryOrderDateFrom == '' && $SummaryOrderDateTo != '') //0-1
    {
        $strSQLA = "select count(*) as NumberOfOrders, sum(OrderTotal) as ValueOfOrders, B.FirstName as FirstName, B.LastName as LastName, RecordCreatedBy as OrderBy from tblOrders A inner join tblRevitalash_users B on A.RecordCreatedBy = b.RecordID where A.OrderDate  <= '" . $SummaryOrderDateTo . "' group by RecordCreatedBy, FirstName, LastName order by RecordCreatedBy ASC";
        $strSQLB = "select count(*) as TotalNumberOfOrders, sum(OrderTotal) as TotalValueOfOrders from tblOrders A where A.OrderDate <= '" . $SummaryOrderDateTo . "'";
    }
    else if($SummaryOrderDateFrom != '' && $SummaryOrderDateTo == '') // 1-0
    {
        $strSQLA = "select count(*) as NumberOfOrders, sum(OrderTotal) as ValueOfOrders, B.FirstName as FirstName, B.LastName as LastName, RecordCreatedBy as OrderBy from tblOrders A inner join tblRevitalash_users B on A.RecordCreatedBy = b.RecordID where A.OrderDate  >= '" . $SummaryOrderDateFrom . "' group by RecordCreatedBy, FirstName, LastName order by RecordCreatedBy ASC";
        $strSQLB = "select count(*) as TotalNumberOfOrders, sum(OrderTotal) as TotalValueOfOrders from tblOrders A where A.OrderDate >= '" . $SummaryOrderDateFrom . "'";
    }
    else if($SummaryOrderDateFrom != '' && $SummaryOrderDateTo != '') // 1-1
    {
        $strSQLA = "select count(*) as NumberOfOrders, sum(OrderTotal) as ValueOfOrders, B.FirstName as FirstName, B.LastName as LastName, RecordCreatedBy as OrderBy from tblOrders A inner join tblRevitalash_users B on A.RecordCreatedBy = b.RecordID where A.OrderDate  >= '" . $SummaryOrderDateFrom . "' and A.OrderDate  <= '" . $SummaryOrderDateTo . "' group by RecordCreatedBy, FirstName, LastName order by RecordCreatedBy ASC";
        $strSQLB = "select count(*) as TotalNumberOfOrders, sum(OrderTotal) as TotalValueOfOrders from tblOrders A where A.OrderDate >= '" . $SummaryOrderDateFrom . "' and A.OrderDate  <= '" . $SummaryOrderDateTo . "'";
    }

	$tblSummaryList = '';

	// EXECUTE QUERY
    $rs = mssql_query($strSQLA);

	if (is_resource($rs) && mssql_num_rows($rs) > 0)
	{
		while($row = mssql_fetch_array($rs))
		{
			$tblSummaryList .= '<tr class="tdwhite">';
			$tblSummaryList .= '<td width="*"><a href="/csradmin/orders.php?Action=Detail&RecordCreatedBy=' . $row["OrderBy"] . '">' . $row["FirstName"] . ' ' . $row["LastName"] . '</a></td>';
			$tblSummaryList .= '<td width="*">' . $row["NumberOfOrders"] . '</td>';
			$tblSummaryList .= '<td>$' . number_format($row["ValueOfOrders"], 2, '.', ',') . '</td>';
			$tblSummaryList .= '</tr>';
		}

		mssql_next_result($rs);
	}

	$tblSummaryTotal = '';

	// EXECUTE QUERY
    $rst = mssql_query($strSQLB);

	if (is_resource($rst) && mssql_num_rows($rst) > 0)
	{
		while($row = mssql_fetch_array($rst))
		{
			$tblSummaryTotal .= '<tr class="tdwhite">';
			$tblSummaryTotal .= '<td width="*">' . $row["TotalNumberOfOrders"] . '</td>';
			$tblSummaryTotal .= '<td>$' . number_format($row["TotalValueOfOrders"], 2, '.', ',') . '</td>';
			$tblSummaryTotal .= '</tr>';
		}

		mssql_next_result($rst);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connOrders);
}

// GMC - 04/09/14 - Create Order Summary - SalesRepID for CSRADMIN
else if ($_GET['Action']== 'SummarySalesRepId')
{
	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);

	$qrySummaryList = mssql_init("spOrders_SummarySalesRepIdList", $connOrders);

	$tblSummaryList = '';

	// EXECUTE QUERY
	$rs = mssql_execute($qrySummaryList);

	if (is_resource($rs) && mssql_num_rows($rs) > 0)
	{
		while($row = mssql_fetch_array($rs))
		{
			$tblSummaryList .= '<tr class="tdwhite">';
			$tblSummaryList .= '<td width="*"><a href="/csradmin/orders.php?Action=Detail&SalesRepID=' . $row["SalesRepID"] . '">' . $row["FirstName"] . ' ' . $row["LastName"] . '</a></td>';
			$tblSummaryList .= '<td width="*">' . $row["NumberOfOrders"] . '</td>';
			$tblSummaryList .= '<td>$' . number_format($row["ValueOfOrders"], 2, '.', ',') . '</td>';
			$tblSummaryList .= '</tr>';
		}

		mssql_next_result($rs);
	}

 	$qrySummaryTotal = mssql_init("spOrders_SummaryTotal", $connOrders);
	$tblSummaryTotal = '';

	// EXECUTE QUERY
	$rst = mssql_execute($qrySummaryTotal);

	if (is_resource($rst) && mssql_num_rows($rst) > 0)
	{
		while($row = mssql_fetch_array($rst))
		{
			$tblSummaryTotal .= '<tr class="tdwhite">';
			$tblSummaryTotal .= '<td width="*">' . $row["TotalNumberOfOrders"] . '</td>';
			$tblSummaryTotal .= '<td>$' . number_format($row["TotalValueOfOrders"], 2, '.', ',') . '</td>';
			$tblSummaryTotal .= '</tr>';
		}

		mssql_next_result($rst);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connOrders);
}

// GMC - 04/09/14 - Create Order Summary - SalesRepID for CSRADMIN
else if ($_GET['Action']== 'SummarySalesRepIdSearch')
{
	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);

    // Set Variables
	if (isset($_GET['SummaryOrderDateFrom']))
         $SummaryOrderDateFrom = $_GET['SummaryOrderDateFrom'];
	else
         $SummaryOrderDateFrom = '';

	if (isset($_GET['SummaryOrderDateTo']))
         $SummaryOrderDateTo = $_GET['SummaryOrderDateTo'];
	else
         $SummaryOrderDateTo = '';

    if($SummaryOrderDateFrom == '' && $SummaryOrderDateTo == '') // 0-0
    {
        $strSQLA = 'select count(*) as NumberOfOrders, sum(OrderTotal) as ValueOfOrders, C.FirstName as FirstName, C.LastName as LastName, B.SalesRepID as SalesRepID from tblOrders A  inner join tblCustomers B  on A.CustomerID = B.RecordID  inner join tblRevitalash_Users C  on B.SalesRepID = C.RecordID group by SalesRepID, C.FirstName, C.LastName order by SalesRepID ASC';
        $strSQLB = 'select count(*) as TotalNumberOfOrders, sum(OrderTotal) as TotalValueOfOrders from tblOrders';
    }
    else if($SummaryOrderDateFrom == '' && $SummaryOrderDateTo != '') //0-1
    {
        $strSQLA = "select count(*) as NumberOfOrders, sum(OrderTotal) as ValueOfOrders, C.FirstName as FirstName, C.LastName as LastName, B.SalesRepID as SalesRepID from tblOrders A  inner join tblCustomers B  on A.CustomerID = B.RecordID  inner join tblRevitalash_Users C  on B.SalesRepID = C.RecordID where A.OrderDate  <= '" . $SummaryOrderDateTo . "' group by SalesRepID, C.FirstName, C.LastName order by SalesRepID ASC";
        $strSQLB = "select count(*) as TotalNumberOfOrders, sum(OrderTotal) as TotalValueOfOrders from tblOrders A where A.OrderDate <= '" . $SummaryOrderDateTo . "'";
    }
    else if($SummaryOrderDateFrom != '' && $SummaryOrderDateTo == '') // 1-0
    {
        $strSQLA = "select count(*) as NumberOfOrders, sum(OrderTotal) as ValueOfOrders, C.FirstName as FirstName, C.LastName as LastName, B.SalesRepID as SalesRepID from tblOrders A  inner join tblCustomers B  on A.CustomerID = B.RecordID  inner join tblRevitalash_Users C  on B.SalesRepID = C.RecordID where A.OrderDate  >= '" . $SummaryOrderDateFrom . "' group by SalesRepID, C.FirstName, C.LastName order by SalesRepID ASC";
        $strSQLB = "select count(*) as TotalNumberOfOrders, sum(OrderTotal) as TotalValueOfOrders from tblOrders A where A.OrderDate >= '" . $SummaryOrderDateFrom . "'";
    }
    else if($SummaryOrderDateFrom != '' && $SummaryOrderDateTo != '') // 1-1
    {
        $strSQLA = "select count(*) as NumberOfOrders, sum(OrderTotal) as ValueOfOrders, C.FirstName as FirstName, C.LastName as LastName, B.SalesRepID as SalesRepID from tblOrders A  inner join tblCustomers B  on A.CustomerID = B.RecordID  inner join tblRevitalash_Users C  on B.SalesRepID = C.RecordID where A.OrderDate  >= '" . $SummaryOrderDateFrom . "' and A.OrderDate  <= '" . $SummaryOrderDateTo . "' group by SalesRepID, C.FirstName, C.LastName order by SalesRepID ASC";
        $strSQLB = "select count(*) as TotalNumberOfOrders, sum(OrderTotal) as TotalValueOfOrders from tblOrders A where A.OrderDate >= '" . $SummaryOrderDateFrom . "' and A.OrderDate  <= '" . $SummaryOrderDateTo . "'";
    }

	$tblSummaryList = '';

	// EXECUTE QUERY
    $rs = mssql_query($strSQLA);

	if (is_resource($rs) && mssql_num_rows($rs) > 0)
	{
		while($row = mssql_fetch_array($rs))
		{
			$tblSummaryList .= '<tr class="tdwhite">';
			$tblSummaryList .= '<td width="*"><a href="/csradmin/orders.php?Action=Detail&SalesRepID=' . $row["SalesRepID"] . '">' . $row["FirstName"] . ' ' . $row["LastName"] . '</a></td>';
			$tblSummaryList .= '<td width="*">' . $row["NumberOfOrders"] . '</td>';
			$tblSummaryList .= '<td>$' . number_format($row["ValueOfOrders"], 2, '.', ',') . '</td>';
			$tblSummaryList .= '</tr>';
		}

		mssql_next_result($rs);
	}

	$tblSummaryTotal = '';

	// EXECUTE QUERY
    $rst = mssql_query($strSQLB);

	if (is_resource($rst) && mssql_num_rows($rst) > 0)
	{
		while($row = mssql_fetch_array($rst))
		{
			$tblSummaryTotal .= '<tr class="tdwhite">';
			$tblSummaryTotal .= '<td width="*">' . $row["TotalNumberOfOrders"] . '</td>';
			$tblSummaryTotal .= '<td>$' . number_format($row["TotalValueOfOrders"], 2, '.', ',') . '</td>';
			$tblSummaryTotal .= '</tr>';
		}

		mssql_next_result($rst);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connOrders);
}

else if ($_GET['Action'] == 'Detail' && isset($_GET['OrderID']))
{
	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);
		
	$qryOrderDetail = mssql_init("spOrders_Detail", $connOrders);
	mssql_bind($qryOrderDetail, "@prmOrderID", $_GET['OrderID'], SQLINT4);
	mssql_bind($qryOrderDetail, "@prmUserID", $_SESSION['UserID'], SQLINT4);
	mssql_bind($qryOrderDetail, "@prmUserTypeID", $_SESSION['UserTypeID'], SQLINT4);

	// EXECUTE QUERY
	$rsOrderDetail = mssql_execute($qryOrderDetail);
	
	if (mssql_num_rows($rsOrderDetail) > 0)
	{
		while($row = mssql_fetch_array($rsOrderDetail))
		{
			$OrderID = $row["RecordID"];

            // GMC - 07/29/09 - Date and Time show by JS
			// $OrderDate = date("F d, Y", strtotime($row["OrderDate"]));
			$OrderDate = $row["OrderDate"];

			// GMC - 01/20/09 - Present CustomerID in Order Detail
			$CustomerID = $row["NavisionCustomerID"];

			$CompanyName = $row["CompanyName"];
			$FirstName = $row["FirstName"];
			$LastName = $row["LastName"];
			$Address1 = $row["Address1"];
			$Address2 = $row["Address2"];
			$City = $row["City"];
			$State = $row["State"];
			$PostalCode = $row["PostalCode"];
			$CountryCode = $row["CountryCode"];
			$Telephone = $row["Telephone"];
			$sAttn = $row["sAttn"];
			$sAddress1 = $row["sAddress1"];
			$sAddress2 = $row["sAddress2"];
			$sCity = $row["sCity"];
			$sState = $row["sState"];
			$sPostalCode = $row["sPostalCode"];
			$sCountryCode = $row["sCountryCode"];
			$ShippingMethod = $row["ShippingMethodDisplay"];
			$PaymentType = $row["PaymentType"];
			$CCNumber = $row["CCNumber"];
			$CCAuth = $row["CCAuthorization"];
			$CCTrans = $row["CCTransactionID"];
			$CKBankAccount = $row["CKBankAccount"];
			$PONumber = $row["PONumber"];
			$OrderStatus = $row["StatusDisplay"];
			$OrderTracking = $row["OrderTracking"];
			$OrderSubtotal = $row["OrderSubtotal"];
			$OrderShipping = $row["OrderShipping"];
			$OrderTax = $row["OrderTax"];
			$OrderTotal = $row["OrderTotal"];

            // GMC - 12/03/08 - Domestic vs International 3rd Phase
			$RecordCreatedBy = $row["RecordCreatedBy"];
			
			// GMC - 12/22/08 - Order Notes to Show in Order Detail
			$OrderNotes = $row["OrderNotes"];
			
			// GMC - 02/18/09 - CustomerTypeID to Show in Order Detail
            $CustomerTypeID = $row["CustomerTypeID"];
            
			// GMC - 02/18/09 - RevitalashID to Show in Order Detail
            $RevitalashID = $row["RevitalashID"];

            // GMC - 09/20/09 - To show discount details at the Order Detail Level
            $PromoCode = $row["PromoCode"];

            // GMC - 02/01/11 - Order Closed By CSR ADMIN Partner - Rep
            $OrderClosedBy = $row["OrderClosedBy"];
		}
		
		mssql_next_result($rsOrderDetail);
	}

	// BUILD AND EXECUTE ORDERS ITEMS QUERY
	$qryOrderItemDetail = mssql_init("spOrdersItems_Detail", $connOrders);
	mssql_bind($qryOrderItemDetail, "@prmOrderID", $_GET['OrderID'], SQLINT4);
	$rsOrderItemDetail = mssql_execute($qryOrderItemDetail);
	
	$tblOrderItems = '';
	
	if (mssql_num_rows($rsOrderItemDetail) > 0)
	{
		while($rowItems = mssql_fetch_array($rsOrderItemDetail))
		{
			$tblOrderItems .= '<tr class="tdwhite" style="font-size:11px;">';
			$tblOrderItems .= '<td>' . $rowItems["CartDescription"] . '</td>';
			$tblOrderItems .= '<td>' . $rowItems["Location"] . '</td>';
			$tblOrderItems .= '<td>' . $rowItems["TrackingInformation"] . '</td>';
			$tblOrderItems .= '<td>' . $rowItems["Qty"] . '</td>';
			$tblOrderItems .= '<td>$' . number_format($rowItems["UnitPrice"], 2, '.', '') . '</td>';
			$tblOrderItems .= '<td style="text-align:right;">$' . number_format($rowItems["ExtendedPrice"], 2, '.', '') . '</td>';
			$tblOrderItems .= '</tr>';
			
			// GMC - 09/23/09 - Show FedEx Netherlands
			if($rowItems["Location"] == "FEDEXNETH")
			{
                $ShowFedExEU = "Yes";
			}
		}
		
		mssql_next_result($rsOrderItemDetail);
	}

    // GMC - 12/03/08 - Domestic vs International 3rd Phase
	// BUILD AND EXECUTE GET ENTERED BY
	$qryOrderEnteredBy = mssql_init("spGetEnteredBy", $connOrders);
	mssql_bind($qryOrderEnteredBy, "@prmRecordID", $RecordCreatedBy, SQLINT4);
	$rsOrderEnteredBy = mssql_execute($qryOrderEnteredBy);

	if (mssql_num_rows($rsOrderEnteredBy) > 0)
	{
		while($rowEnteredBy = mssql_fetch_array($rsOrderEnteredBy))
		{
			$EnteredBy = $rowEnteredBy["RevitalashID"];
		}

		mssql_next_result($rsOrderEnteredBy);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connOrders);
}

// GMC - 03/31/14 - Create Order Summary for CSRADMIN
else if ($_GET['Action'] == 'Detail' && isset($_GET['RecordCreatedBy']))
{
	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);

	$qryOrderList = mssql_init("spOrders_RecordCreatedByRecentList", $connOrders);
	mssql_bind($qryOrderList, "@prmUserID",  $_GET['RecordCreatedBy'], SQLINT2);
    $RecordCreatedBy = $_GET['RecordCreatedBy'];

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

            // GMC - 07/29/09 - Date and Time show by JS
            // $tblOrderList .= '<td>' . date("F d, Y", strtotime($row["OrderDate"])) . '</td>';
            $tblOrderList .= '<td>' . $row["OrderDate"] . '</td>';

            $tblOrderList .= '<td>' . $row["StatusDisplay"] . '</td>';

            // GMC - 12/25/09 - Ship From Location
            $tblOrderList .= '<td>' . $row["Location"] . '</td>';

			$tblOrderList .= '<td>' . $row["ShippingMethodDisplay"] . '</td>';

            // GMC - 04/10/12 - SubTotal OrderRecent
			$tblOrderList .= '<td>$' . number_format($row["OrderSubTotal"], 2, '.', '') . '</td>';

			$tblOrderList .= '<td>$' . number_format($row["OrderTotal"], 2, '.', '') . '</td>';

            // GMC - 01/26/10 - Email in Order List and Order Excel
            $tblOrderList .= '<td>' . wordwrap($row["EmailAddress"], 20, "\n", true) . '</td>';

			$tblOrderList .= '</tr>';
		}

		mssql_next_result($rs);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connOrders);
}

// GMC - 04/09/14 - Create Order Summary - SalesRepID for CSRADMIN
else if ($_GET['Action'] == 'Detail' && isset($_GET['SalesRepID']))
{
	// CONNECT TO SQL SERVER DATABASE
	$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connOrders);

	$qryOrderList = mssql_init("spOrders_SalesRepIDRecentList", $connOrders);
	mssql_bind($qryOrderList, "@prmUserID",  $_GET['SalesRepID'], SQLINT2);
    $SalesRepID = $_GET['SalesRepID'];

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

            // GMC - 07/29/09 - Date and Time show by JS
            // $tblOrderList .= '<td>' . date("F d, Y", strtotime($row["OrderDate"])) . '</td>';
            $tblOrderList .= '<td>' . $row["OrderDate"] . '</td>';

            $tblOrderList .= '<td>' . $row["StatusDisplay"] . '</td>';

            // GMC - 12/25/09 - Ship From Location
            $tblOrderList .= '<td>' . $row["Location"] . '</td>';

			$tblOrderList .= '<td>' . $row["ShippingMethodDisplay"] . '</td>';

            // GMC - 04/10/12 - SubTotal OrderRecent
			$tblOrderList .= '<td>$' . number_format($row["OrderSubTotal"], 2, '.', '') . '</td>';

			$tblOrderList .= '<td>$' . number_format($row["OrderTotal"], 2, '.', '') . '</td>';

            // GMC - 01/26/10 - Email in Order List and Order Excel
            $tblOrderList .= '<td>' . wordwrap($row["EmailAddress"], 20, "\n", true) . '</td>';

			$tblOrderList .= '</tr>';
		}

		mssql_next_result($rs);
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
		
        <!-- GMC - 12/03/08 - Domestic vs International 3rd Phase
		<div><img src="/csradmin/images/bg_masthead.gif" alt="Revitalash Administration" width="950" height="91" /></div>
        -->
        
		<?php include("includes/dspMasthead.php"); ?>
		
		<div id="content">

			<?php
			if ((!isset($_GET['Action'])) || ($_GET['Action'] == 'Overview'))
			{
				include("includes/dspOrders_Index.php");
            }
            elseif ($_GET['Action'] == 'Tradeshow')
			{
            	include("includes/dspOrders_Tradeshow.php");
            }

            // GMC - 03/31/14 - Create Order Summary for CSRADMIN
            elseif ($_GET['Action'] == 'Summary' || $_GET['Action'] == 'SummarySearch')
			{
            	include("includes/dspOrders_Summary.php");
            }

            // GMC - GMC - 04/09/14 - Create Order Summary - SalesRepID for CSRADMIN
            elseif ($_GET['Action'] == 'SummarySalesRepId' || $_GET['Action'] == 'SummarySalesRepIdSearch')
			{
            	include("includes/dspOrders_SummarySalesRepID.php");
            }

            // GMC - 03/31/14 - Create Order Summary for CSRADMIN
            // GMC - 04/09/14 - Create Order Summary - SalesRepID for CSRADMIN
            elseif ($_GET['Action'] == 'Detail')
			{
                if(isset($OrderID))
                {
			        include("includes/dspOrders_Detail.php");
                }
                elseif (isset($RecordCreatedBy))
                {
			        include("includes/dspOrders_Index.php");
                }
                elseif (isset($SalesRepID))
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
