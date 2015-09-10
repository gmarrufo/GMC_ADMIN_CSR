<?php

require_once("../modules/currency.php");
$strConfirmation = '';

// GMC - 12/03/08 - Domestic vs International 3rd Phase
if ($_SESSION['UserTypeID'] == 1)
{
     // GMC - 12/12/08 - Change Text on Order Confirmation
     // $strConfirmation .= '<h2>Rep Order Confirmation/Invoice</h2>';
     $strConfirmation .= '<h2>REP Order#: ' . $_SESSION['OrderID'] . '</h2>';
}
else if ($_SESSION['UserTypeID'] == 2)
{
     // GMC - 12/12/08 - Change Text on Order Confirmation
     // $strConfirmation .= '<h2>CSR Order Confirmation/Invoice</h2>';
     // GMC - 10/02/09 - To include Information for EU Orders
     // GMC - 03/18/10 - Add 10 Line Items Admin
     if($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH')
     {
         if(($_SESSION['CustomerTypeIDFedExEu'] == 1 || $_SESSION['CustomerTypeIDFedExEu'] == 2 || $_SESSION['CustomerTypeIDFedExEu'] == 3 || $_SESSION['CustomerTypeIDFedExEu'] == 4) && $_SESSION['CountryCodeFedExEu'] != '')
         {
             $strConfirmation = '<h2><div align="center"><font color="red">*** INVOICE ***</font></div></h2>';
             $strConfirmation .= '<h2>CSR Order#: ' . $_SESSION['OrderID'] . '</h2>';
         }
     }
     else
     {
         $strConfirmation .= '<h2>CSR Order#: ' . $_SESSION['OrderID'] . '</h2>';
     }
}

// GMC - 12/12/08 - Change Text on Order Confirmation
// $strConfirmation .= '<h2>Order: ' . $_SESSION['OrderID'] . '</h2>';
$strConfirmation .= '<h2>Amount: ' . $_SESSION['OrderTotal'] . ' USD</h2>';
$strConfirmation .= '<table width="100%" cellpadding="0" cellspacing="0">';
          
while($rowGetCustomer = mssql_fetch_array($qryGetCustomer))
{
    $intCustomerType = $rowGetCustomer["CustomerTypeID"];
	$strCustomerEMail = $rowGetCustomer["EMailAddress"];
	
	if ($rowGetCustomer["CountryCode"] == 'US')
	{
		$blnIsInternational = 0;
	}
	else
    {
    	$blnIsInternational = 1;
	}

	$strConfirmation .= '<tr>';
	$strConfirmation .= '<th width="140" style="text-align:left;">Name:</th>';
    $strConfirmation .= '<td width="*">' . $rowGetCustomer["FirstName"] . ' ' . $rowGetCustomer["LastName"] . '</td>';
    $strConfirmation .= '</tr>';
    $strConfirmation .= '<tr>';
    $strConfirmation .= '<th style="text-align:left;">Company:</th>';
    $strConfirmation .= '<td>' . $rowGetCustomer["CompanyName"] . '</td>';
    $strConfirmation .= '</tr>';
	$strConfirmation .= '<tr>';
    $strConfirmation .= '<th style="text-align:left;">Country:</th>';
    $strConfirmation .= '<td>' . $rowGetCustomer["CountryCode"] . '</td>';
    $strConfirmation .= '</tr>';

    // GMC - 08/13/10 - Show Sales Rep Email
	$strConfirmation .= '<tr>';
    $strConfirmation .= '<th style="text-align:left;">Sales Rep Email:</th>';
    $strConfirmation .= '<td>' . $_SESSION['CusSalRepEmailID'] . '</td>';
    $strConfirmation .= '</tr>';

    // GMC - 04/05/11 - Add the tblCustomers_SalesRepId into the Customers Order Confirmation
	$strConfirmation .= '<tr>';
    $strConfirmation .= '<th style="text-align:left;">Entered by:</th>';
    $strConfirmation .= '<td>' . $_SESSION['SalesRepIdEmailAddress'] . '</td>';
    $strConfirmation .= '</tr>';

	if ($intCustomerType == 1)
		$strConfirmation .= '<tr><th style="text-align:left;">Customer Type:</th><td>Consumer</td></tr>';
	elseif ($intCustomerType == 2)
		$strConfirmation .= '<tr><th style="text-align:left;">Customer Type:</th><td>Spa/Reseller</td></tr>';
	elseif ($intCustomerType == 3)
		$strConfirmation .= '<tr><th style="text-align:left;">Customer Type:</th><td>Distributor</td></tr>';

	// GMC - 03/08/09 - Customer Type "REP"
	elseif ($intCustomerType == 4)
		$strConfirmation .= '<tr><th style="text-align:left;">Customer Type:</th><td>Rep</td></tr>';

    // GMC - 03/12/12 - Add Billing Information to Order Confirmations
    $BillAddress1 = $rowGetCustomer["Address1"];
    $BillAddress2 = $rowGetCustomer["Address2"];
    $BillCity = $rowGetCustomer["City"];
    $BillState = $rowGetCustomer["State"];
    $BillPostalCode = $rowGetCustomer["PostalCode"];
    $BillCountryCode = $rowGetCustomer["CountryCode"];
}

// CONNECT TO SQL SERVER DATABASE
$connItems = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connItems);

// EXECUTE SQL QUERY
if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
{
	$qryCart = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID1']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID1']);
	$qryCartBundleExt = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID1']);
}

if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
{
	$qryCart2 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID2']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle2 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID2']);
	$qryCartBundleExt2 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID2']);
}

if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
{
	$qryCart3 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID3']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle3 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID3']);
	$qryCartBundleExt3 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID3']);
}

if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
{
	$qryCart4 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID4']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle4 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID4']);
	$qryCartBundleExt4 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID4']);
}

if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
{
	$qryCart5 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID5']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle5 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID5']);
	$qryCartBundleExt5 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID5']);
}

if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
{
	$qryCart6 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID6']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle6 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID6']);
	$qryCartBundleExt6 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID6']);
}

if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
{
	$qryCart7 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID7']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle7 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID7']);
	$qryCartBundleExt7 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID7']);
}

if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
{
	$qryCart8 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID8']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle8 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID8']);
	$qryCartBundleExt8 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID8']);
}

if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
{
	$qryCart9 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID9']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle9 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID9']);
	$qryCartBundleExt9 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID9']);
}

if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
{
	$qryCart10 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID10']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle10 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID10']);
	$qryCartBundleExt10 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID10']);
}

// GMC - 03/18/10 - Add 10 Line Items Admin

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
{
	$qryCart11 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID11']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle11 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID11']);
	$qryCartBundleExt11 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID11']);
}

if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
{
	$qryCart12 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID12']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle12 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID12']);
	$qryCartBundleExt12 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID12']);
}

if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
{
	$qryCart13 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID13']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle13 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID13']);
	$qryCartBundleExt13 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID13']);
}

if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
{
	$qryCart14 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID14']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle14 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID14']);
	$qryCartBundleExt14 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID14']);
}

if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
{
	$qryCart15 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID15']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle15 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID15']);
	$qryCartBundleExt15 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID15']);
}

if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
{
	$qryCart16 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID16']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle16 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID16']);
	$qryCartBundleExt16 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID16']);
}

if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
{
	$qryCart17 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID17']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle17 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID17']);
	$qryCartBundleExt17 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID17']);
}

if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
{
	$qryCart18 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID18']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle18 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID18']);
	$qryCartBundleExt18 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID18']);
}

if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
{
	$qryCart19 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID19']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle19 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID19']);
	$qryCartBundleExt19 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID19']);
}

if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
{
	$qryCart20 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID20']);

    // GMC - 10/24/10 - Bundles Project Oct 2010
	$qryCartBundle20 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID20']);
	$qryCartBundleExt20 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID20']);
}

$qryShippingMethod = mssql_query("SELECT ShippingMethodDisplay FROM conShippingMethods WHERE RecordID = " . $_SESSION['ShippingMethod']);

// EXECUTE STORED PROC
$qryGetShippingMethods = mssql_init("spConstants_CSRShippingMethods", $connItems);
mssql_bind($qryGetShippingMethods, "@prmCustomerShipToID", $_SESSION['CustomerShipToID'], SQLINT4);

// GMC - 01/05/09 - To Hide Will Call from Reps
if ($_SESSION['UserTypeID'] == 1)
{
 $usertype = 1;
 mssql_bind($qryGetShippingMethods, "@prmUserType", $usertype, SQLINT4);
}
else
{
 $usertype = 2;
 mssql_bind($qryGetShippingMethods, "@prmUserType", $usertype, SQLINT4);
}

$rsGetShippingMethods = mssql_execute($qryGetShippingMethods);

$qryGetOrderShipTo = mssql_query("SELECT * FROM tblCustomers_ShipTo WHERE RecordID = " . $_SESSION['CustomerShipToID'] . " AND IsDefault = 'True'");
while($rowGetShipTo = mssql_fetch_array($qryGetOrderShipTo))
{
    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
    $CompanyName =  $rowGetShipTo['CompanyName'];
    $Attn =  $rowGetShipTo['Attn'];

    if($Attn == "")
    {
	    $qryGetShipToNameAlt = mssql_query("SELECT top 1 * FROM tblCustomers_ShipTo WHERE CustomerID = " . $_SESSION['CustomerID'] . " AND Attn is not NULL");

     	while($rowGetShipToNameAlt = mssql_fetch_array($qryGetShipToNameAlt))
	    {
           // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
		   $CompanyName = $rowGetShipToNameAlt['CompanyName'];

		   $Attn = $rowGetShipToNameAlt['Attn'];
	       $Address1 = $rowGetShipToNameAlt['Address1'];
	       $Address2 = $rowGetShipToNameAlt['Address2'];
	       $AddressCity = $rowGetShipToNameAlt['City'];
	       $AddressState = $rowGetShipToNameAlt['State'];
	       $AddressPostalCode = $rowGetShipToNameAlt['PostalCode'];
	       $AddressCountryCode = $rowGetShipToNameAlt['CountryCode'];
	    }
    }
    else
    {
	    $Address1 = $rowGetShipTo['Address1'];
	    $Address2 = $rowGetShipTo['Address2'];
	    $AddressCity = $rowGetShipTo['City'];
	    $AddressState = $rowGetShipTo['State'];
	    $AddressPostalCode = $rowGetShipTo['PostalCode'];
	    $AddressCountryCode = $rowGetShipTo['CountryCode'];
    }
}

// CLOSE DATABASE CONNECTION
mssql_close($connItems);

$decSubtotal = 0;

// SET FREE ITEMS - IF APPLICABLE
if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
	$ItemFree1 = $_POST['ItemFree1'];
if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
	$ItemFree2 = $_POST['ItemFree2'];
if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
	$ItemFree3 = $_POST['ItemFree3'];
if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
	$ItemFree4 = $_POST['ItemFree4'];
if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
	$ItemFree5 = $_POST['ItemFree5'];
if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
	$ItemFree6 = $_POST['ItemFree6'];
if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
	$ItemFree7 = $_POST['ItemFree7'];
if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
	$ItemFree8 = $_POST['ItemFree8'];
if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
	$ItemFree9 = $_POST['ItemFree9'];
if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
	$ItemFree10 = $_POST['ItemFree10'];

// GMC - 03/18/10 - Add 10 Line Items Admin

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
	$ItemFree11 = $_POST['ItemFree11'];
if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
	$ItemFree12 = $_POST['ItemFree12'];
if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
	$ItemFree13 = $_POST['ItemFree13'];
if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
	$ItemFree14 = $_POST['ItemFree14'];
if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
	$ItemFree15 = $_POST['ItemFree15'];
if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
	$ItemFree16 = $_POST['ItemFree16'];
if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
	$ItemFree17 = $_POST['ItemFree17'];
if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
	$ItemFree18 = $_POST['ItemFree18'];
if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
	$ItemFree19 = $_POST['ItemFree19'];
if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
	$ItemFree20 = $_POST['ItemFree20'];

while($rowSM = mssql_fetch_array($qryShippingMethod))
{
	$strShippingMethod = $rowSM["ShippingMethodDisplay"];
}

// RETRIEVE ITEM DETAILS
if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_1'] != '')
    {
	    while($row1 = mssql_fetch_array($qryCart))
	    {
		    $strProductName1 = $row1["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice1'] * $_POST['ItemQty1'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty1'], 2, '.', '');
        }
    }
    else
    {
	    while($row1 = mssql_fetch_array($qryCart))
	    {
		    $strProductName1 = $row1["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice1'] * $_POST['ItemQty1'];
	    }
    }
}

if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_2'] != '')
    {
	    while($row2 = mssql_fetch_array($qryCart2))
	    {
		    $strProductName2 = $row2["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice2'] * $_POST['ItemQty2'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt2))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty2'], 2, '.', '');
        }
    }
    else
    {
	    while($row2 = mssql_fetch_array($qryCart2))
	    {
		    $strProductName2 = $row2["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice2'] * $_POST['ItemQty2'];
	    }
    }
}

if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_3'] != '')
    {
	    while($row3 = mssql_fetch_array($qryCart3))
	    {
		    $strProductName3 = $row3["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice3'] * $_POST['ItemQty3'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt3))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty3'], 2, '.', '');
        }
    }
    else
    {
	    while($row3 = mssql_fetch_array($qryCart3))
	    {
		    $strProductName3 = $row3["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice3'] * $_POST['ItemQty3'];
	    }
    }
}

if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_4'] != '')
    {
	    while($row4 = mssql_fetch_array($qryCart4))
	    {
		    $strProductName4 = $row4["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice4'] * $_POST['ItemQty4'];
	     }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt4))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty4'], 2, '.', '');
        }
    }
    else
    {
	    while($row4 = mssql_fetch_array($qryCart4))
	    {
		    $strProductName4 = $row4["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice4'] * $_POST['ItemQty4'];
	     }
    }
}

if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_5'] != '')
    {
	    while($row5 = mssql_fetch_array($qryCart5))
     	{
		    $strProductName5 = $row5["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice5'] * $_POST['ItemQty5'];
	     }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt5))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty5'], 2, '.', '');
        }
    }
    else
    {
	    while($row5 = mssql_fetch_array($qryCart5))
	    {
		    $strProductName5 = $row5["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice5'] * $_POST['ItemQty5'];
	    }
    }
}

if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_6'] != '')
    {
	    while($row6 = mssql_fetch_array($qryCart6))
	    {
		    $strProductName6 = $row6["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice6'] * $_POST['ItemQty6'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt6))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty6'], 2, '.', '');
        }
    }
    else
    {
	    while($row6 = mssql_fetch_array($qryCart6))
	    {
		    $strProductName6 = $row6["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice6'] * $_POST['ItemQty6'];
	    }
    }
}

if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_7'] != '')
    {
	   while($row7 = mssql_fetch_array($qryCart7))
	   {
           $strProductName7 = $row7["ProductName"];
           $decSubtotal = $decSubtotal + $_POST['ItemPrice7'] * $_POST['ItemQty7'];
       }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt7))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty7'], 2, '.', '');
        }
    }
    else
    {
	    while($row7 = mssql_fetch_array($qryCart7))
	    {
		    $strProductName7 = $row7["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice7'] * $_POST['ItemQty7'];
	    }
    }
}

if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_8'] != '')
    {
	    while($row8 = mssql_fetch_array($qryCart8))
	    {
		    $strProductName8 = $row8["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice8'] * $_POST['ItemQty8'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt8))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty8'], 2, '.', '');
        }
    }
    else
    {
	    while($row8 = mssql_fetch_array($qryCart8))
	    {
		    $strProductName8 = $row8["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice8'] * $_POST['ItemQty8'];
	    }
    }
}

if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_9'] != '')
    {
	    while($row9 = mssql_fetch_array($qryCart9))
	    {
		    $strProductName9 = $row9["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice9'] * $_POST['ItemQty9'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt9))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty9'], 2, '.', '');
        }
    }
    else
    {
	    while($row9 = mssql_fetch_array($qryCart9))
	    {
		    $strProductName9 = $row9["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice9'] * $_POST['ItemQty9'];
	    }
    }
}

if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_10'] != '')
    {
	    while($row10 = mssql_fetch_array($qryCart10))
	    {
		    $strProductName10 = $row10["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice10'] * $_POST['ItemQty10'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt10))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty10'], 2, '.', '');
        }
    }
    else
    {
	    while($row10 = mssql_fetch_array($qryCart10))
	    {
		    $strProductName10 = $row10["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice10'] * $_POST['ItemQty10'];
	    }
    }
}

// GMC - 03/18/10 - Add 10 Line Items Admin

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_11'] != '')
    {
	    while($row11 = mssql_fetch_array($qryCart11))
	    {
	        $strProductName11 = $row11["ProductName"];
            $decSubtotal = $decSubtotal + $_POST['ItemPrice11'] * $_POST['ItemQty11'];
        }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt11))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty11'], 2, '.', '');
        }
    }
    else
    {
	    while($row11 = mssql_fetch_array($qryCart11))
	    {
		    $strProductName11 = $row11["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice11'] * $_POST['ItemQty11'];
	    }
    }
}

if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_12'] != '')
    {
	    while($row12 = mssql_fetch_array($qryCart12))
	    {
		    $strProductName12 = $row12["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice12'] * $_POST['ItemQty12'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt12))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty12'], 2, '.', '');
        }
    }
    else
    {
	    while($row12 = mssql_fetch_array($qryCart12))
	    {
		    $strProductName12 = $row12["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice12'] * $_POST['ItemQty12'];
	    }
    }
}

if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_13'] != '')
    {
	    while($row13 = mssql_fetch_array($qryCart13))
	    {
		    $strProductName13 = $row13["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice13'] * $_POST['ItemQty13'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt13))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty13'], 2, '.', '');
        }
    }
    else
    {
	    while($row13 = mssql_fetch_array($qryCart13))
	    {
		    $strProductName13 = $row13["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice13'] * $_POST['ItemQty13'];
	    }
    }
}

if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_14'] != '')
    {
	    while($row14 = mssql_fetch_array($qryCart14))
	    {
		    $strProductName14 = $row14["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice14'] * $_POST['ItemQty14'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt14))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty14'], 2, '.', '');
        }
    }
    else
    {
	    while($row14 = mssql_fetch_array($qryCart14))
	    {
		    $strProductName14 = $row14["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice14'] * $_POST['ItemQty14'];
	    }
    }
}

if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_15'] != '')
    {
	    while($row15 = mssql_fetch_array($qryCart15))
	    {
		    $strProductName15 = $row15["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice15'] * $_POST['ItemQty15'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt15))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty15'], 2, '.', '');
        }
    }
    else
    {
	    while($row15 = mssql_fetch_array($qryCart15))
	    {
		    $strProductName15 = $row15["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice15'] * $_POST['ItemQty15'];
	    }
    }
}

if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_16'] != '')
    {
	    while($row16 = mssql_fetch_array($qryCart16))
	    {
		    $strProductName16 = $row16["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice16'] * $_POST['ItemQty16'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt16))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty16'], 2, '.', '');
        }
    }
    else
    {
	    while($row16 = mssql_fetch_array($qryCart16))
	    {
		    $strProductName16 = $row16["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice16'] * $_POST['ItemQty16'];
	    }
    }
}

if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_17'] != '')
    {
	    while($row17 = mssql_fetch_array($qryCart17))
	    {
		    $strProductName17 = $row17["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice17'] * $_POST['ItemQty17'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt17))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty17'], 2, '.', '');
        }
    }
    else
    {
	    while($row17 = mssql_fetch_array($qryCart17))
	    {
		    $strProductName17 = $row17["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice17'] * $_POST['ItemQty17'];
	    }
    }
}

if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_18'] != '')
    {
	    while($row18 = mssql_fetch_array($qryCart18))
	    {
		    $strProductName18 = $row18["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice18'] * $_POST['ItemQty18'];
	    }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt18))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty18'], 2, '.', '');
        }
    }
    else
    {
	    while($row18 = mssql_fetch_array($qryCart18))
	    {
		    $strProductName18 = $row18["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice18'] * $_POST['ItemQty18'];
        }
    }
}

if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_19'] != '')
    {
	    while($row19 = mssql_fetch_array($qryCart19))
	    {
		    $strProductName19 = $row19["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice19'] * $_POST['ItemQty19'];
	     }

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt19))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty19'], 2, '.', '');
        }
    }
    else
    {
	    while($row19 = mssql_fetch_array($qryCart19))
	    {
		    $strProductName19 = $row19["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice19'] * $_POST['ItemQty19'];
	    }
    }
}

if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
{
    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_20'] != '')
    {
	    while($row20 = mssql_fetch_array($qryCart20))
	    {
		    $strProductName20 = $row20["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice20'] * $_POST['ItemQty20'];
      	}

        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt20))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty20'], 2, '.', '');
        }
    }
    else
    {
	    while($row20 = mssql_fetch_array($qryCart20))
	    {
		    $strProductName20 = $row20["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice20'] * $_POST['ItemQty20'];
	    }
    }
}

// GMC - 01/14/09 - CA - NV Sales Tax Calculation
if ($intCustomerType == 1)
{
        if(strtoupper($_SESSION['Ship_State']) == 'CA')
        {
           $_SESSION['OrderSubtotal'] = $decSubtotal;

           // GMC - 09/28/10 - Force Sales Tax Value by JS
           if($_SESSION['SalesTaxForced'] != 0)
           {
               $_SESSION['OrderTax'] = $_SESSION['SalesTaxForced'];
           }
           else
           {
               $_SESSION['OrderTax'] = $decSubtotal * $_SESSION['OrderTaxRate'];
           }

           $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }
        elseif(strtoupper($_SESSION['Ship_State']) == 'NV')
        {
	        $_SESSION['OrderSubtotal'] = $decSubtotal;

           // GMC - 09/28/10 - Force Sales Tax Value by JS
           if($_SESSION['SalesTaxForced'] != 0)
           {
               $_SESSION['OrderTax'] = $_SESSION['SalesTaxForced'];
           }
           else
           {
	           $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
           }

            $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }
        
        // GMC - 05/28/09 - UT State Tax Activation
        elseif(strtoupper($_SESSION['Ship_State']) == 'UT')
        {
	        $_SESSION['OrderSubtotal'] = $decSubtotal;

           // GMC - 09/28/10 - Force Sales Tax Value by JS
           if($_SESSION['SalesTaxForced'] != 0)
           {
               $_SESSION['OrderTax'] = $_SESSION['SalesTaxForced'];
           }
           else
           {
	           $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
           }

            $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }

        else
        {
            $_SESSION['OrderSubtotal'] = $decSubtotal;

           // GMC - 09/28/10 - Force Sales Tax Value by JS
           if($_SESSION['SalesTaxForced'] != 0)
           {
               $_SESSION['OrderTax'] = $_SESSION['SalesTaxForced'];
           }
           else
           {
               $_SESSION['OrderTax'] = $decSubtotal * $_SESSION['OrderTaxRate'];
           }

            $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }
}
else
{
    // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
    // GMC - 09/05/09 - Promotion Section - Drop Down for CSR's Only
    if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0) && ($_SESSION['Summer2012FineLinePrimer'] == 0))
    {
        // GMC - 09/21/09 - Fix Bug in Detail Discount
        $originalValue = ($decSubtotal/(1 - $_SESSION['Promo_Code_Discount']));
        $discountValue = ((($decSubtotal/(1 - $_SESSION['Promo_Code_Discount']))) * $_SESSION['Promo_Code_Discount']);
        // $decSubtotal = ($decSubtotal - ($decSubtotal * $_SESSION['Promo_Code_Discount']));
        $_SESSION['OrderSubtotal'] = $decSubtotal;

        // GMC - 09/28/10 - Force Sales Tax Value by JS
        if($_SESSION['SalesTaxForced'] != 0)
        {
            $_SESSION['OrderTax'] = $_SESSION['SalesTaxForced'];
        }
        else
        {
            $_SESSION['OrderTax'] = $decSubtotal * $_SESSION['OrderTaxRate'];
        }

        $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
    }
    else
    {
        $_SESSION['OrderSubtotal'] = $decSubtotal;

        // GMC - 09/28/10 - Force Sales Tax Value by JS
        if($_SESSION['SalesTaxForced'] != 0)
        {
            $_SESSION['OrderTax'] = $_SESSION['SalesTaxForced'];
        }
        else
        {
            $_SESSION['OrderTax'] = $decSubtotal * $_SESSION['OrderTaxRate'];
        }

        $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
    }

}

$strConfirmation .= '</table>';

// GMC - 03/12/12 - Add Billing Information to Order Confirmations
$strConfirmation .= '<p>&nbsp;</p>';
$strConfirmation .= '<p><span style="font-weight:bold;">Billing Information</span><br />';
$strConfirmation .= $BillAddress1 . '<br />';
if ($BillAddress2 != '') $strConfirmation .= $BillAddress2 . '<br />';
$strConfirmation .= $BillCity . ', ' . $BillState . ' ' . $BillPostalCode . '<br />';
$strConfirmation .= $BillCountryCode . '</p>';

$strConfirmation .= '<p>&nbsp;</p>';
$strConfirmation .= '<p><span style="font-weight:bold;">Shipping Information</span><br />';

// GMC - 05/25/10 - Proper Shipping Information at Confirmation
// GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
$strConfirmation .= $CompanyName . '<br />';
$strConfirmation .= $Attn . '<br />';

$strConfirmation .= $Address1 . '<br />';
if ($Address2 != '') $strConfirmation .= $Address2 . '<br />';
$strConfirmation .= $AddressCity . ', ' . $AddressState . ' ' . $AddressPostalCode . '<br />';
$strConfirmation .= $AddressCountryCode . '</p>';
$strConfirmation .= '<p><span style="font-weight:bold;">Payment Information</span><br />';

if ($_SESSION['PaymentType'] == 'CreditCard')
{
	$strConfirmation .= 'Credit Card Ending in ' . substr($_SESSION['PaymentCC_Number'],-5,5) . '<br />';
	$strConfirmation .= 'Expires ' . $_SESSION['PaymentCC_ExpMonth'] . ' / ' . $_SESSION['PaymentCC_ExpYear'] . '</p>';
}
elseif ($_SESSION['PaymentType'] == 'CreditCardSwiped')
{
	$strConfirmation .= 'Preswiped Authorization ' . $_SESSION['PaymentCC_SwipedAuth'] . '</p>';
}
elseif ($_SESSION['PaymentType'] == 'ECheck')
{
	$strConfirmation .= 'Bank Routing Number: ' . $_SESSION['PaymentCK_BankRouting'] . '<br />';
	$strConfirmation .= 'Bank Account Number ' . $_SESSION['PaymentCK_BankAccount'] . '</p>';
}
elseif ($_SESSION['PaymentType'] == 'Terms')
{
	$strConfirmation .= 'Purchase Order Number: ' . $_SESSION['PaymentPO_Number'] . '</p>';
}

$strConfirmation .= '<p>&nbsp;</p>';
$strConfirmation .= '<table width="100%" cellpadding="0" cellspacing="0">';
$strConfirmation .= '<tr>';
$strConfirmation .= '<th width="*" style="text-align:left">Product</th>';
$strConfirmation .= '<th width="50" style="text-align:left">Unit Price</th>';
$strConfirmation .= '<th width="50" style="text-align:left">Qty</th>';
$strConfirmation .= '<th width="50" style="text-align:right;">Total Price</th>';
$strConfirmation .= '</tr>';

if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName1 . '</td>';
    $strConfirmation .= '<td>$' . number_format($_POST['ItemPrice1'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty1'];
	if ($ItemFree1 > 0) $strConfirmation .= ' + ' . $ItemFree1 . ' FREE';
	$strConfirmation .= '</td>';
	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice1'] * $_POST['ItemQty1'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_1'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_1'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_1'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_1'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_1'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_1'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_1'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*10 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_1'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_1'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_1'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_1'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_1'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_1'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_1']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName2 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice2'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty2'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty2'];
	if ($ItemFree2 > 0) $strConfirmation .= ' + ' . $ItemFree2 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice2'] * $_POST['ItemQty2'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_2'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_2'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_2'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_2'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_2'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_2'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_2'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*10 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_2'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_2'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_2'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_2'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_2'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_2'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_2']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle2))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName3 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice3'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty3'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty3'];
	if ($ItemFree3 > 0) $strConfirmation .= ' + ' . $ItemFree3 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice3'] * $_POST['ItemQty3'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_3'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_3'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_3'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_3'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_3'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_3'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_3'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*10 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_3'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_3'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_3'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_3'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_3'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_3'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_3']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle3))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName4 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice4'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty4'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty4'];
	if ($ItemFree4 > 0) $strConfirmation .= ' + ' . $ItemFree4 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice4'] * $_POST['ItemQty4'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_4'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_4'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_4'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_4'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_4'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_4'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_4'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*10 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_4'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_4'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_4'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_4'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_4'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_4'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_4']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle4))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName5 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice5'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty5'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty5'];
	if ($ItemFree5 > 0) $strConfirmation .= ' + ' . $ItemFree5 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice5'] * $_POST['ItemQty5'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_5'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_5'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_5'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_5'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_5'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_5'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_5'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*10 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_5'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_5'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_5'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_5'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_5'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_5'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_5']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle5))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName6 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice6'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty6'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty6'];
	if ($ItemFree6 > 0) $strConfirmation .= ' + ' . $ItemFree6 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice6'] * $_POST['ItemQty6'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_6'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_6'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_6'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_6'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_6'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_6'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_6'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*10 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_6'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_6'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_6'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_6'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_6'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_6'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_6']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle6))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName7 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice7'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty7'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty7'];
	if ($ItemFree7 > 0) $strConfirmation .= ' + ' . $ItemFree7 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice7'] * $_POST['ItemQty7'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_7'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_7'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_7'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_7'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_7'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_7'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_7'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*10 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_7'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_7'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_7'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_7'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_7'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_7'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_7']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle7))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName8 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice8'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty8'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty8'];
	if ($ItemFree8 > 0) $strConfirmation .= ' + ' . $ItemFree8 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice8'] * $_POST['ItemQty8'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_8'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_8'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_8'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_8'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_8'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_8'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_8'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*10 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_8'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_8'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_8'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_8'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_8'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_8'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_8']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle8))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName9 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice9'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty9'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty9'];
	if ($ItemFree9 > 0) $strConfirmation .= ' + ' . $ItemFree9 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice9'] * $_POST['ItemQty9'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_9'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_9'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_9'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_9'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_9'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_9'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_9'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*10 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_9'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_9'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_9'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_9'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_9'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_9'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_9']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle9))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName10 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice10'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty10'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty10'];
	if ($ItemFree10 > 0) $strConfirmation .= ' + ' . $ItemFree10 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice10'] * $_POST['ItemQty10'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_10'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_10'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_10'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_10'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_10'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_10'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_10'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_10'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_10'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_10'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_10'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_10'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_10'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_10']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle10))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

// GMC - 03/18/10 - Add 10 Line Items Admin

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName11 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice11'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty11'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty11'];
	if ($ItemFree11 > 0) $strConfirmation .= ' + ' . $ItemFree11 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice11'] * $_POST['ItemQty11'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_11'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_11'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_11'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_11'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_11'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_11'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_11'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_11'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_11'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_11'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_11'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_11'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_11'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_11']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle11))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName12 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice12'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty12'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty12'];
	if ($ItemFree12 > 0) $strConfirmation .= ' + ' . $ItemFree12 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice12'] * $_POST['ItemQty12'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_12'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_12'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_12'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_12'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_12'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_12'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_12'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_12'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_12'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_12'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_12'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_12'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_12'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_12']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle12))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName13 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice13'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty13'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty13'];
	if ($ItemFree13 > 0) $strConfirmation .= ' + ' . $ItemFree13 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice13'] * $_POST['ItemQty13'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_13'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_13'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_13'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_13'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_13'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_13'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_13'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_13'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_13'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_13'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_13'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_13'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_13'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_13']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle13))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName14 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice14'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty14'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty14'];
	if ($ItemFree14 > 0) $strConfirmation .= ' + ' . $ItemFree14 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice14'] * $_POST['ItemQty14'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_14'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_14'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_14'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_14'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_14'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_14'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_14'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_14'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_14'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_14'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_14'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_14'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_14'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_14']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle14))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName15 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice15'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty15'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty15'];
	if ($ItemFree15 > 0) $strConfirmation .= ' + ' . $ItemFree15 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice15'] * $_POST['ItemQty15'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_15'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_15'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_15'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_15'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_15'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_15'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_15'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_15'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_15'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_15'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_15'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_15'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_15'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_15']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle15))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName16 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice16'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty16'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty16'];
	if ($ItemFree16 > 0) $strConfirmation .= ' + ' . $ItemFree16 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice16'] * $_POST['ItemQty16'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_16'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_16'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_16'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_16'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_16'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_16'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_16'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_16'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_16'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_16'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_16'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_16'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_16'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_16']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle16))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName17 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice17'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty17'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty17'];
	if ($ItemFree17 > 0) $strConfirmation .= ' + ' . $ItemFree17 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice17'] * $_POST['ItemQty17'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_17'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_17'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_17'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_17'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_17'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_17'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_17'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_17'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_17'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_17'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_17'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_17'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_17'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_17']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle17))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName18 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice18'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty18'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty18'];
	if ($ItemFree18 > 0) $strConfirmation .= ' + ' . $ItemFree18 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice18'] * $_POST['ItemQty18'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_18'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_18'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_18'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_18'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_18'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_18'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_18'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_18'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_18'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_18'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_18'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_18'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_18'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_18']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle18))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName19 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice19'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty19'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty19'];
	if ($ItemFree19 > 0) $strConfirmation .= ' + ' . $ItemFree19 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice19'] * $_POST['ItemQty19'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_19'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_19'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_19'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_19'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_19'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_19'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_19'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_19'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_19'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_19'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_19'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_19'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_19'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_19']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle19))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
{
	$strConfirmation .= '<tr>';
	$strConfirmation .= '<td>' . $strProductName20 . '</td>';
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice20'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty20'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty20'];
	if ($ItemFree20 > 0) $strConfirmation .= ' + ' . $ItemFree20 . ' FREE';
	$strConfirmation .= '</td>';

	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice20'] * $_POST['ItemQty20'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_20'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_20'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_20'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_20'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_20'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_20'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_20'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_20'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_20'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_20'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_20'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_20'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    // GMC - 11/15/10 - Adjust the ItemPrice and ExtendedPrice of Bundles Project Oct 2010
    if($_SESSION['Bundles2010_20'] != '')
    {
        // Separate values from Session
        $sess_values = explode("~", $_SESSION['Bundles2010_20']);

        // Iterate thru the other items in the tblBundles
        while($rowGetBundle = mssql_fetch_array($qryCartBundle20))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';

	        // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';

            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // $strConfirmation .= '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            $strConfirmation .= '</tr>';
        }
    }
}

$strConfirmation .= '</table>';
$strConfirmation .= '<table width="100%" cellpadding="0" cellspacing="0">';
$strConfirmation .= '<tr><td colspan="5">&nbsp;</td></tr>';
$strConfirmation .= '<tr>';
$strConfirmation .= '<td colspan="2" rowspan="6">Notes: ' . $_POST['ShippingNotes'] . '</td>';

// GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
// GMC - 09/05/09 - Promotion Section - Drop Down for CSR's Only
if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0) && ($intCustomerType == 2) && ($_SESSION['Summer2012FineLinePrimer'] == 0))
{
    // GMC - 09/21/09 - Fix Bug in Detail Discount
    $strConfirmation .= '<th colspan="2" align="left">Original Subtotal:</th>';
    $strConfirmation .= '<td style="text-align:right;">' . number_format($originalValue, 2, '.', '') . '</td>';
    $strConfirmation .= '</tr>';
    $strConfirmation .= '<tr>';
    $strConfirmation .= '<th colspan="2" align="left"><font color="red">Less' . ($_SESSION['Promo_Code_Discount'] * 100) . '% Discount ' . $_SESSION['Promo_Code_Description'] . '</font></th>';
    $strConfirmation .= '<td style="text-align:right;"><font color="red">-' . number_format($discountValue, 2, '.', '') . '</font></td>';
    $strConfirmation .= '</tr>';
    $strConfirmation .= '<tr>';
    $strConfirmation .= '<th colspan="2" align="left">New Subtotal:</th>';
    $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderSubtotal'], 2, '.', '') . '</td>';
}
else
{
    $strConfirmation .= '<th colspan="2" align="left">Subtotal:</th>';
    $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderSubtotal'], 2, '.', '') . '</td>';
}

$strConfirmation .= '</tr>';
$strConfirmation .= '<tr>';

// GMC - 12/03/08 - Domestic Vs. International 3rd Phase
if ($blnIsInternational == 0)
{
   $strConfirmation .= '<th colspan="2" align="left">Sales Tax - VAT:</th>';
}
else
{
   // GMC - 08/03/09 - Add the standard distribution sites by JS
   // GMC - 03/18/10 - Add 10 Line Items Admin
   if($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH')
   {
       // GMC - 05/06/09 - FedEx Netherlands
       if($_SESSION['CountryCodeFedExEu'] == 'GB')
       {
           // GMC - 07/20/10 - Reseller EU has reseller number no VAT charge
           // $strConfirmation .= '<th colspan="2" align="left">VAT (GBP):</th>';
           
           if($_SESSION['ResellerEUHasNumber'] == ' ')
           {
               $strConfirmation .= '<th colspan="2" align="left">VAT (GBP):</th>';
           }
           else if($_SESSION['ResellerEUHasNumber'] == '')
           {
               $strConfirmation .= '<th colspan="2" align="left">VAT (GBP):</th>';
           }
           else if($_SESSION['ResellerEUHasNumber'] == NULL)
           {
               $strConfirmation .= '<th colspan="2" align="left">VAT (GBP):</th>';
           }
           else if($_SESSION['ResellerEUHasNumber'] == null)
           {
               $strConfirmation .= '<th colspan="2" align="left">VAT (GBP):</th>';
           }
           else
           {
               $strConfirmation .= '<th colspan="2" align="left">Reseller Number/VAT ID: ' . $_SESSION['ResellerEUHasNumber'] . ' 0% Dutch VAT IntraCommunity Sale VAT (GBP):</th>';
           }
        }
        else
        {
           // GMC - 07/20/10 - Reseller EU has reseller number no VAT charge
           // $strConfirmation .= '<th colspan="2" align="left">VAT (EUR):</th>';
           
           if($_SESSION['ResellerEUHasNumber'] == ' ')
           {
               $strConfirmation .= '<th colspan="2" align="left">VAT (EUR):</th>';
           }
           else if($_SESSION['ResellerEUHasNumber'] == '')
           {
               $strConfirmation .= '<th colspan="2" align="left">VAT (EUR):</th>';
           }
           else if($_SESSION['ResellerEUHasNumber'] == NULL)
           {
               $strConfirmation .= '<th colspan="2" align="left">VAT (EUR):</th>';
           }
           else if($_SESSION['ResellerEUHasNumber'] == null)
           {
               $strConfirmation .= '<th colspan="2" align="left">VAT (EUR):</th>';
           }
           else
           {
               $strConfirmation .= '<th colspan="2" align="left">Reseller Number/VAT ID: ' . $_SESSION['ResellerEUHasNumber'] . ' 0% Dutch VAT IntraCommunity Sale VAT (EUR):</th>';
           }
        }
    }
    else
    {
          echo '<th colspan="2" align="right">Sales Tax:</th>';
    }

}

// GMC - 12/22/08 - NO CHARGE - Then No Shipping and No handling Values
if ($_SESSION['PaymentType'] == 'NOCHARGE')
{
    $_SESSION['OrderTax'] = 0;
    $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderTax'], 2, '.', '') . '</td>';
}
else
{
    // GMC - 08/03/09 - Add the standard distribution sites by JS
    // GMC - 03/18/10 - Add 10 Line Items Admin
    if($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH')
    {
      // GMC - 05/06/09 - FedEx Netherlands
      if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
      {
        if($_SESSION['CountryCodeFedExEu'] == 'GB')
        {
            $strConfirmation .= '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']), 2, '.', '') . '</td>';
        }
        else
        {
            $strConfirmation .= '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']), 2, '.', '') . '</td>';
        }
      }
      else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
      {
        if($_SESSION['CountryCodeFedExEu'] == 'GB')
        {
            // GMC - 07/20/10 - Reseller EU has reseller number no VAT charge
            // $strConfirmation .= '<td style="text-align:right;">' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']), 2, '.', '') . '</td>';
        }
        else
        {
            // GMC - 07/20/10 - Reseller EU has reseller number no VAT charge
            // $strConfirmation .= '<td style="text-align:right;">' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']), 2, '.', '') . '</td>';
        }
      }
      else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
      {
        if($_SESSION['CountryCodeFedExEu'] == 'GB')
        {
            $strConfirmation .= '<td style="text-align:right;">' . number_format(0, 2, '.', '') . '</td>';
        }
        else
        {
            $strConfirmation .= '<td style="text-align:right;">' . number_format(0, 2, '.', '') . '</td>';
        }
      }
      else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
      {
        if($_SESSION['CountryCodeFedExEu'] == 'GB')
        {
            $strConfirmation .= '<td style="text-align:right;">' . number_format(0, 2, '.', '') . '</td>';
        }
        else
        {
            $strConfirmation .= '<td style="text-align:right;">' . number_format(0, 2, '.', '') . '</td>';
        }
      }
      
    }
    else
    {
        $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderTax'], 2, '.', '') . '</td>';
    }
}

$strConfirmation .= '</tr>';
$strConfirmation .= '<tr>';
$strConfirmation .= '<th colspan="2" align="left">Shipping:</th>';

    // GMC - 07/14/11 - Distributors Change CSRADMIN
    if($_SESSION['Transportation_Charges'] != 'Prepaid')
    {
        $strShippingMethod = $_SESSION['Transportation_Charges'];
    }

    // GMC - 11/06/08 - To accomodate the International Shipping in CSR/ADMIN
    if ($blnIsInternational == 0)
    {
       $strConfirmation .= '<td style="text-align:right;">' . $strShippingMethod . '</td>';
    }
    else
    {
        // GMC - 08/03/09 - Add the standard distribution sites by JS
        // GMC - 03/18/10 - Add 10 Line Items Admin
        if($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH')
        {
          // GMC - 05/06/09 - FedEx Netherlands
          if(($_SESSION['CustomerTypeIDFedExEu'] == 1 || $_SESSION['CustomerTypeIDFedExEu'] == 2 || $_SESSION['CustomerTypeIDFedExEu'] == 3 || $_SESSION['CustomerTypeIDFedExEu'] == 4) && $_SESSION['CountryCodeFedExEu'] != '')
          {
            $strShippingMethod = $_SESSION['ShipMethodIDFedExEu'];
            $strConfirmation .= '<td style="text-align:right;">' . $strShippingMethod . '</td>';
          }
        }
        else
        {
            $strShippingMethod = 'International';
            $strConfirmation .= '<td style="text-align:right;">' . $strShippingMethod . '</td>';
         }
    }

$strConfirmation .= '</tr>';
$strConfirmation .= '<tr>';

// GMC - 07/27/09 - Shipping and Handling one single value
$strConfirmation .= '<th colspan="2" align="left">Shipping and Handling Cost:</th>';

// GMC - 07/14/11 - Distributors Change CSRADMIN
if($_SESSION['Transportation_Charges'] != 'Prepaid')
{
    $_SESSION['OrderHandling'] = 0;
}

if ($blnIsInternational == 0)
{
     if ($_SESSION['IsFreeShipping'] == 'Ok')
     {
        $_SESSION['OrderShipping'] = 0;
        $_SESSION['OrderHandling'] = 0;
        $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '') . '</td>';
     }
     elseif ($_SESSION['ShippingOverride'] == 'Ok')
     {
        $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderShipping'], 2, '.', '') . '</td>';
     }
     elseif ($_SESSION['PaymentType'] == 'NOCHARGE')
     {
        $_SESSION['OrderShipping'] = 0;
        $_SESSION['OrderHandling'] = 0;
        $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '') . '</td>';
     }
     else
     {
        $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderShipping'], 2, '.', '') . '</td>';
     }
}
else
{
     if ($_SESSION['IsFreeShipping'] == 'Ok')
     {
        $_SESSION['OrderShipping'] = 0;
        $_SESSION['OrderHandling'] = 0;
        $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '') . '</td>';
     }
     elseif ($_SESSION['ShippingOverride'] == 'Ok')
     {
        $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderShipping'], 2, '.', '') . '</td>';
     }
     elseif ($_SESSION['PaymentType'] == 'NOCHARGE')
     {
        $_SESSION['OrderShipping'] = 0;
        $_SESSION['OrderHandling'] = 0;
        $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '') . '</td>';
     }
     elseif ($_SESSION['WillCallInt'] == 'Ok')
     {
        $_SESSION['OrderShipping'] = 0;
        $_SESSION['OrderHandling'] = 0;
        $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '') . '</td>';
     }
     else
     {
         $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderShipping'], 2, '.', '') . '</td>';
     }
}

$strConfirmation .= '</tr>';

$strConfirmation .= '<tr>';

 // GMC - 09/21/09 - Fix Bug in Detail Discount
if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0) && ($intCustomerType == 2))
{
     $strConfirmation .= '<th colspan="4" align="left">Total:</th>';
}
else
{
     $strConfirmation .= '<th colspan="2" align="left">Total:</th>';
}

// GMC - 12/22/08 - NO CHARGE - Then No Shipping and No handling Values
if ($_SESSION['PaymentType'] == 'NOCHARGE')
{
    $_SESSION['OrderTotal'] = 0;
    $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderTotal'], 2, '.', '') . '</td>';
}
else
{
    $strConfirmation .= '<td style="text-align:right;">' . number_format($_SESSION['OrderTotal'], 2, '.', '') . '</td>';
}

$strConfirmation .= '</tr>';
$strConfirmation .= '<tr><td colspan="5">&nbsp;</td></tr>';
$strConfirmation .= '</table>';
$strConfirmation .= '<p>&nbsp;</p>';

// GMC - 08/03/09 - Add the standard distribution sites by JS
// GMC - 03/18/10 - Add 10 Line Items Admin
if($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH')
{
  // GMC - 10/25/10 - Disconnect Earnst&Young and VAT Charges for EU Orders - Consumers
  // GMC - 05/06/09 - FedEx Netherlands
  /*
  if(($_SESSION['CustomerTypeIDFedExEu'] == 1 || $_SESSION['CustomerTypeIDFedExEu'] == 2 || $_SESSION['CustomerTypeIDFedExEu'] == 3 || $_SESSION['CustomerTypeIDFedExEu'] == 4) && $_SESSION['CountryCodeFedExEu'] != '')
  {
    $strConfirmation .= '<br />';
    $strConfirmation .= '<table>';
    $strConfirmation .= '<tr>';
    $strConfirmation .= '<td>';
    $strConfirmation .= '<div align="center">Ernst & Young VAT Rep BV, A. Vivalditratt 150, 1083 HP, Amsterdam, acts as our general fiscal representative under VAT ID number 0030.25.263.B.01</div>';

    // GMC - 10/02/09 - To include Information for EU Orders
    $strConfirmation .= '<br/>';
    $strConfirmation .= '<div align="center">Athena Cosmetics, Inc. 701 N. Green Valley Parkway, Suite 200 Henderson, NV 89074 USA VAT ID number NL 8201.38.113.B.01</div>';

    $strConfirmation .= '</td>';
    $strConfirmation .= '</tr>';
    $strConfirmation .= '</table>';
  }
  */
}

// GMC - 12/03/08 - Domestic Vs. International 3rd Phase
$strConfirmationEmail = $strConfirmation;
$strConfirmation .= '<br />';
$strConfirmation .= '<table>';
$strConfirmation .= '<tr>';
$strConfirmation .= '<td>';
$strConfirmation .= '<div align="center"><a href="/csradmin/customers.php">Return to Customer Management</a></div>';
$strConfirmation .= '</td>';
$strConfirmation .= '</tr>';
$strConfirmation .= '</table>';

echo $strConfirmation;

/* MAIL SENDER */
if ($strCustomerEMail != '' && $_SESSION['UserID'] != 75)
{
$mailrecepient = $strCustomerEMail;

// GMC - 09/29/12 - Changes from Revitalash to BlueSkiesDist on Confirmation Email
// $mailsubject = 'Revitalash Order Confirmation';
$mailsubject = 'BlueSkies Distribution Order Confirmation';

$mailheader = 'MIME-Version: 1.0' . "\r\n";
$mailheader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// GMC - 09/29/12 - Changes from Revitalash to BlueSkiesDist on Confirmation Email
// $mailheader .= "From:" . 'sales@revitalash.com' . "\r\n";
$mailheader .= "From:" . 'sales@blueskiesdist.com' . "\r\n";

//$mailheader .= 'Bcc: jstancarone@revitalash.com' . "\r\n";
// GMC - 07/29/09 - Add Linda Peterson to list by JS

// GMC - 10/02/09 - To include Information for EU Orders
// GMC - 10/07/09 - Add email address to EU Orders (Holland@revitalash.com)
// GMC - 03/18/10 - Add 10 Line Items Admin
// GMC - 07/20/10 - Add ar@revitalash.com at EU orders

if($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH')
{
  if(($_SESSION['CustomerTypeIDFedExEu'] == 1 || $_SESSION['CustomerTypeIDFedExEu'] == 2 || $_SESSION['CustomerTypeIDFedExEu'] == 3 || $_SESSION['CustomerTypeIDFedExEu'] == 4) && $_SESSION['CountryCodeFedExEu'] != '')
  {
    // GMC - 10/25/10 - Disconnect Earnst&Young and VAT Charges for EU Orders - Consumers
    // GMC - 08/18/10 - International Receive Emails
    if ($_SESSION['UserID'] == 100 || $_SESSION['UserID'] == 128 || $_SESSION['UserID'] == 113 || $_SESSION['UserID'] == 99 || $_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 44)
    {
       // GMC - 11/23/09 - Take LashGro from email add revitalash1@gmail.com
       // GMC - 08/10/10 - Update Email Address on tblCustomers
       // GMC - 06/16/11 - Take wforrest out
       // GMC - 07/22/11 - Take lpeterson out
       // GMC - 12/05/11 - Change Gayle email address
       // GMC - 12/15/11 - Change Gayle email address back to what it was
       // GMC - 12/22/11 - Change Gayle email for the third to time to what they wanted on 120511
       // GMC - 09/29/12 - Changes from Revitalash to BlueSkiesDist on Confirmation Email
       // GMC - 02/04/13 - Exchange Group Order List Email Addresses to Replace hard coded values
       // GMC - 02/05/13 - Undo Exchange Group Order List Email Addresses to Replace hard coded values
       // GMC - 04/18/13 - Gayle B New Email
       // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,Holland@revitalash.com,marcel.schellekens@nl.ey.com,gayleb@revitalash.com,dhooper@revitalash.com,lashgro@aol.com,gmarrufo@unimerch.com,jstancarone@revitalash.com' . "\r\n";
       // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,dsidney@revitalash.com,wforrest@revitalash.com,ar@revitalash.com,Holland@revitalash.com,marcel.schellekens@nl.ey.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,dsidney@revitalash.com,wforrest@revitalash.com,ar@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,dsidney@revitalash.com,ar@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: dsidney@revitalash.com,ar@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: dsidney@revitalash.com,ar@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: dsidney@revitalash.com,ar@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: Orders-I@athenacosmetics.com,ar@revitalash.com,gmarrufo@unimerch.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: dsidney@revitalash.com,ar@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       $mailheader .= 'Bcc: dsidney@revitalash.com,ar@revitalash.com,Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
    }
    else
    {
       // GMC - 11/23/09 - Take LashGro from email add revitalash1@gmail.com
       // GMC - 08/10/10 - Update Email Address on tblCustomers
       // GMC - 07/22/11 - Take lpeterson out
       // GMC - 12/05/11 - Change Gayle email address
       // GMC - 12/15/11 - Change Gayle email address back to what it was
       // GMC - 12/22/11 - Change Gayle email for the third to time to what they wanted on 120511
       // GMC - 09/29/12 - Changes from Revitalash to BlueSkiesDist on Confirmation Email
       // GMC - 02/04/13 - Exchange Group Order List Email Addresses to Replace hard coded values
       // GMC - 02/05/13 - Undo Exchange Group Order List Email Addresses to Replace hard coded values
       // GMC - 04/18/13 - Gayle B New Email
       // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,Holland@revitalash.com,marcel.schellekens@nl.ey.com,gayleb@revitalash.com,dhooper@revitalash.com,lashgro@aol.com,gmarrufo@unimerch.com,jstancarone@revitalash.com' . "\r\n";
       // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,ar@revitalash.com,Holland@revitalash.com,marcel.schellekens@nl.ey.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,ar@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: ar@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: ar@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: ar@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: ar@revitalash.com,Orders-D@athanacosmetics.com,gmarrufo@unimerch.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       // $mailheader .= 'Bcc: ar@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
       $mailheader .= 'Bcc: ar@revitalash.com,Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
    }
  }
}
else
{
    // GMC - 08/18/10 - International Receive Emails
    if ($_SESSION['UserID'] == 100 || $_SESSION['UserID'] == 128 || $_SESSION['UserID'] == 113 || $_SESSION['UserID'] == 99 || $_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 44)
    {
        // GMC - 11/23/09 - Take LashGro from email add revitalash1@gmail.com
        // GMC - 08/10/10 - Update Email Address on tblCustomers
        // GMC - 06/16/11 - Take wforrest out
        // GMC - 07/22/11 - Take lpeterson out
        // GMC - 12/05/11 - Change Gayle email address
        // GMC - 12/15/11 - Change Gayle email address back to what it was
        // GMC - 12/22/11 - Change Gayle email for the third to time to what they wanted on 120511
        // GMC - 09/29/12 - Changes from Revitalash to BlueSkiesDist on Confirmation Email
        // GMC - 02/04/13 - Exchange Group Order List Email Addresses to Replace hard coded values
        // GMC - 02/05/13 - Undo Exchange Group Order List Email Addresses to Replace hard coded values
        // GMC - 04/18/13 - Gayle B New Email
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,lashgro@aol.com,gmarrufo@unimerch.com,jstancarone@revitalash.com' . "\r\n";
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,dsidney@revitalash.com,wforrest@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,dsidney@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: dsidney@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: dsidney@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: dsidney@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: Orders-I@athenacosmetics.com,gmarrufo@unimerch.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: dsidney@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        $mailheader .= 'Bcc: dsidney@revitalash.com,Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
    }
    else
    {
        // GMC - 11/23/09 - Take LashGro from email add revitalash1@gmail.com
        // GMC - 08/10/10 - Update Email Address on tblCustomers
        // GMC - 07/22/11 - Take lpeterson out
        // GMC - 12/05/11 - Change Gayle email address
        // GMC - 12/15/11 - Change Gayle email address back to what it was
        // GMC - 12/22/11 - Change Gayle email for the third to time to what they wanted on 120511
        // GMC - 09/29/12 - Changes from Revitalash to BlueSkiesDist on Confirmation Email
        // GMC - 02/04/13 - Exchange Group Order List Email Addresses to Replace hard coded values
        // GMC - 02/05/13 - Undo Exchange Group Order List Email Addresses to Replace hard coded values
        // GMC - 04/18/13 - Gayle B New Email
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,lashgro@aol.com,gmarrufo@unimerch.com,jstancarone@revitalash.com' . "\r\n";
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: gaylebrinkenhoff@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@revitalash.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: gaylebrinkenhoff@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: Orders-D@athanacosmetics.com,gmarrufo@unimerch.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: gaylebrinkenhoff@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        $mailheader .= 'Bcc: Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
    }
}

mail($mailrecepient, $mailsubject, $strConfirmationEmail, $mailheader);
}

// RESET SESSION VARIABLES
foreach ($_SESSION as $key => $value)
{
	if ($key != 'IsRevitalashLoggedIn' && $key != 'UserID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress' && $key != 'UserTypeID')	
		unset($_SESSION[$key]);
}

?>
