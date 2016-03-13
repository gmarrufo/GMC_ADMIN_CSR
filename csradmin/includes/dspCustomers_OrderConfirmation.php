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

// GMC - 03/17/14 - New User ID 3 Sales Specialist
else if ($_SESSION['UserTypeID'] == 3)
{
    $strConfirmation .= '<h2>SALES Order#: ' . $_SESSION['OrderID'] . '</h2>';
}

 // GMC - 09/29/14 - UnWash in Admin
else if($_SESSION['UserTypeID'] == 4)
{
    $strConfirmation .= '<h2>UNWASH Order#: ' . $_SESSION['OrderID'] . '</h2>';
}

// GMC - 12/12/08 - Change Text on Order Confirmation
// $strConfirmation .= '<h2>Order: ' . $_SESSION['OrderID'] . '</h2>';
$strConfirmation .= '<h2>Amount: ' . $_SESSION['OrderTotal'] . ' USD</h2>';
$strConfirmation .= '<table width="100%" cellpadding="0" cellspacing="0">';
          
while($rowGetCustomer = mssql_fetch_array($qryGetCustomer))
{
    $intCustomerType = $rowGetCustomer["CustomerTypeID"];
    
    // GMC - 10/31/15 - Replace ; for , in strCustomerEmail
	$strCustomerEMail = $rowGetCustomer["EMailAddress"];
    $strCustomerEMail = str_replace(";",",",$strCustomerEMail);

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

// GMC - 10/16/13 - Add 20 Line Items Admin

if (isset($_POST['ItemID21']) && $_POST['ItemID21'] != 0)
{
	$qryCart21 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID21']);
	$qryCartBundle21 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID21']);
	$qryCartBundleExt21 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID21']);
}

if (isset($_POST['ItemID22']) && $_POST['ItemID22'] != 0)
{
	$qryCart22 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID22']);
	$qryCartBundle22 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID22']);
	$qryCartBundleExt22 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID22']);
}

if (isset($_POST['ItemID23']) && $_POST['ItemID23'] != 0)
{
	$qryCart23 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID23']);
	$qryCartBundle23 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID23']);
	$qryCartBundleExt23 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID23']);
}

if (isset($_POST['ItemID24']) && $_POST['ItemID24'] != 0)
{
	$qryCart24 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID24']);
	$qryCartBundle24 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID24']);
	$qryCartBundleExt24 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID24']);
}

if (isset($_POST['ItemID25']) && $_POST['ItemID25'] != 0)
{
	$qryCart25 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID25']);
	$qryCartBundle25 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID25']);
	$qryCartBundleExt25 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID25']);
}

if (isset($_POST['ItemID26']) && $_POST['ItemID26'] != 0)
{
	$qryCart26 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID26']);
	$qryCartBundle26 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID26']);
	$qryCartBundleExt26 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID26']);
}

if (isset($_POST['ItemID27']) && $_POST['ItemID27'] != 0)
{
	$qryCart27 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID27']);
	$qryCartBundle27 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID27']);
	$qryCartBundleExt27 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID27']);
}

if (isset($_POST['ItemID28']) && $_POST['ItemID28'] != 0)
{
	$qryCart28 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID28']);
	$qryCartBundle28 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID28']);
	$qryCartBundleExt28 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID28']);
}

if (isset($_POST['ItemID29']) && $_POST['ItemID29'] != 0)
{
	$qryCart29 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID29']);
	$qryCartBundle29 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID29']);
	$qryCartBundleExt29 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID29']);
}

if (isset($_POST['ItemID30']) && $_POST['ItemID30'] != 0)
{
	$qryCart30 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID30']);
	$qryCartBundle30 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID30']);
	$qryCartBundleExt30 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID30']);
}

if (isset($_POST['ItemID31']) && $_POST['ItemID31'] != 0)
{
	$qryCart31 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID31']);
	$qryCartBundle31 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID31']);
	$qryCartBundleExt31 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID31']);
}

if (isset($_POST['ItemID32']) && $_POST['ItemID32'] != 0)
{
	$qryCart32 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID32']);
	$qryCartBundle32 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID32']);
	$qryCartBundleExt32 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID32']);
}

if (isset($_POST['ItemID33']) && $_POST['ItemID33'] != 0)
{
	$qryCart33 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID33']);
	$qryCartBundle33 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID33']);
	$qryCartBundleExt33 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID33']);
}

if (isset($_POST['ItemID34']) && $_POST['ItemID34'] != 0)
{
	$qryCart34 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID34']);
	$qryCartBundle34 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID34']);
	$qryCartBundleExt34 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID34']);
}

if (isset($_POST['ItemID35']) && $_POST['ItemID35'] != 0)
{
	$qryCart35 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID35']);
	$qryCartBundle35 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID35']);
	$qryCartBundleExt35 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID35']);
}

if (isset($_POST['ItemID36']) && $_POST['ItemID36'] != 0)
{
	$qryCart36 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID36']);
	$qryCartBundle36 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID36']);
	$qryCartBundleExt36 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID36']);
}

if (isset($_POST['ItemID37']) && $_POST['ItemID37'] != 0)
{
	$qryCart37 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID37']);
	$qryCartBundle37 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID37']);
	$qryCartBundleExt37 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID37']);
}

if (isset($_POST['ItemID38']) && $_POST['ItemID38'] != 0)
{
	$qryCart38 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID38']);
	$qryCartBundle38 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID38']);
	$qryCartBundleExt38 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID38']);
}

if (isset($_POST['ItemID39']) && $_POST['ItemID39'] != 0)
{
	$qryCart39 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID39']);
	$qryCartBundle39 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID39']);
	$qryCartBundleExt39 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID39']);
}

if (isset($_POST['ItemID40']) && $_POST['ItemID40'] != 0)
{
	$qryCart40 = mssql_query("SELECT RecordID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID, Weight FROM tblProducts WHERE RecordID = " . $_POST['ItemID40']);
	$qryCartBundle40 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID40']);
	$qryCartBundleExt40 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID40']);
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

// GMC - 03/17/14 - New User ID 3 Sales Specialist
// else
else if ($_SESSION['UserTypeID'] == 2)
{
 $usertype = 2;
 mssql_bind($qryGetShippingMethods, "@prmUserType", $usertype, SQLINT4);
}
else if ($_SESSION['UserTypeID'] == 3)
{
 $usertype = 3;
 mssql_bind($qryGetShippingMethods, "@prmUserType", $usertype, SQLINT4);
}

// GMC - 09/29/14 - UnWash in Admin
else if ($_SESSION['UserTypeID'] == 4)
{
 $usertype = 4;
 mssql_bind($qryGetShippingMethods, "@prmUserType", $usertype, SQLINT4);
}

$rsGetShippingMethods = mssql_execute($qryGetShippingMethods);

$qryGetOrderShipTo = mssql_query("SELECT * FROM tblCustomers_ShipTo WHERE RecordID = " . $_SESSION['CustomerShipToID'] . " AND IsDefault = 'True'");
while($rowGetShipTo = mssql_fetch_array($qryGetOrderShipTo))
{
    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
    $CompanyName =  $rowGetShipTo['CompanyName'];
    $Attn =  $rowGetShipTo['Attn'];

    // GMC - 04/01/14 - Fix issue with ATTN NULL in Shipping Information always force to DEFAULT AND ACIVE
    /*
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
    */
    
    $Address1 = $rowGetShipTo['Address1'];
    $Address2 = $rowGetShipTo['Address2'];
    $AddressCity = $rowGetShipTo['City'];
    $AddressState = $rowGetShipTo['State'];
    $AddressPostalCode = $rowGetShipTo['PostalCode'];
    $AddressCountryCode = $rowGetShipTo['CountryCode'];
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

// GMC - 10/16/13 - Add 20 Line Items Admin

if (isset($_POST['ItemID21']) && $_POST['ItemID21'] != 0)
	$ItemFree21 = $_POST['ItemFree21'];
if (isset($_POST['ItemID22']) && $_POST['ItemID22'] != 0)
	$ItemFree22 = $_POST['ItemFree22'];
if (isset($_POST['ItemID23']) && $_POST['ItemID23'] != 0)
	$ItemFree23 = $_POST['ItemFree23'];
if (isset($_POST['ItemID24']) && $_POST['ItemID24'] != 0)
	$ItemFree24 = $_POST['ItemFree24'];
if (isset($_POST['ItemID25']) && $_POST['ItemID25'] != 0)
	$ItemFree25 = $_POST['ItemFree25'];
if (isset($_POST['ItemID26']) && $_POST['ItemID26'] != 0)
	$ItemFree26 = $_POST['ItemFree26'];
if (isset($_POST['ItemID27']) && $_POST['ItemID27'] != 0)
	$ItemFree27 = $_POST['ItemFree27'];
if (isset($_POST['ItemID28']) && $_POST['ItemID28'] != 0)
	$ItemFree28 = $_POST['ItemFree28'];
if (isset($_POST['ItemID29']) && $_POST['ItemID29'] != 0)
	$ItemFree29 = $_POST['ItemFree29'];
if (isset($_POST['ItemID30']) && $_POST['ItemID30'] != 0)
	$ItemFree30 = $_POST['ItemFree30'];
if (isset($_POST['ItemID31']) && $_POST['ItemID31'] != 0)
	$ItemFree31 = $_POST['ItemFree31'];
if (isset($_POST['ItemID32']) && $_POST['ItemID32'] != 0)
	$ItemFree32 = $_POST['ItemFree32'];
if (isset($_POST['ItemID33']) && $_POST['ItemID33'] != 0)
	$ItemFree33 = $_POST['ItemFree33'];
if (isset($_POST['ItemID34']) && $_POST['ItemID34'] != 0)
	$ItemFree34 = $_POST['ItemFree34'];
if (isset($_POST['ItemID35']) && $_POST['ItemID35'] != 0)
	$ItemFree35 = $_POST['ItemFree35'];
if (isset($_POST['ItemID36']) && $_POST['ItemID36'] != 0)
	$ItemFree36 = $_POST['ItemFree36'];
if (isset($_POST['ItemID37']) && $_POST['ItemID37'] != 0)
	$ItemFree37 = $_POST['ItemFree37'];
if (isset($_POST['ItemID38']) && $_POST['ItemID38'] != 0)
	$ItemFree38 = $_POST['ItemFree38'];
if (isset($_POST['ItemID39']) && $_POST['ItemID39'] != 0)
	$ItemFree39 = $_POST['ItemFree39'];
if (isset($_POST['ItemID40']) && $_POST['ItemID40'] != 0)
	$ItemFree40 = $_POST['ItemFree40'];

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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_01'] == 'True' || $_SESSION['RegenesisReorderRVProd_01'] == 'True'))
    {
	    while($row1 = mssql_fetch_array($qryCart))
	    {
		    $strProductName1 = $row1["ProductName"];
		    $decSubtotal = $decSubtotal + (($_POST['ItemPrice1'] * $_POST['ItemQty1']) - ($_POST['ItemPrice1'] * $_POST['ItemQty1'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_02'] == 'True' || $_SESSION['RegenesisReorderRVProd_02'] == 'True'))
    {
	    while($row2 = mssql_fetch_array($qryCart2))
	    {
		   $strProductName2 = $row2["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice2'] * $_POST['ItemQty2']) - ($_POST['ItemPrice2'] * $_POST['ItemQty2'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_03'] == 'True' || $_SESSION['RegenesisReorderRVProd_03'] == 'True'))
    {
	    while($row3 = mssql_fetch_array($qryCart3))
	    {
		   $strProductName3 = $row3["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice3'] * $_POST['ItemQty3']) - ($_POST['ItemPrice3'] * $_POST['ItemQty3'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_04'] == 'True' || $_SESSION['RegenesisReorderRVProd_04'] == 'True'))
    {
	     while($row4 = mssql_fetch_array($qryCart4))
	     {
		    $strProductName4 = $row4["ProductName"];
		    $decSubtotal = $decSubtotal + (($_POST['ItemPrice4'] * $_POST['ItemQty4']) - ($_POST['ItemPrice4'] * $_POST['ItemQty4'] * $_SESSION['RegenesisReorderDiscount']));
	     }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_05'] == 'True' || $_SESSION['RegenesisReorderRVProd_05'] == 'True'))
    {
	    while($row5 = mssql_fetch_array($qryCart5))
	    {
		   $strProductName5 = $row5["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice5'] * $_POST['ItemQty5']) - ($_POST['ItemPrice5'] * $_POST['ItemQty5'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_06'] == 'True' || $_SESSION['RegenesisReorderRVProd_06'] == 'True'))
    {
	    while($row6 = mssql_fetch_array($qryCart6))
	    {
		   $strProductName6 = $row6["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice6'] * $_POST['ItemQty6']) - ($_POST['ItemPrice6'] * $_POST['ItemQty6'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_07'] == 'True' || $_SESSION['RegenesisReorderRVProd_07'] == 'True'))
    {
	    while($row7 = mssql_fetch_array($qryCart7))
	    {
		   $strProductName7 = $row7["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice7'] * $_POST['ItemQty7']) - ($_POST['ItemPrice7'] * $_POST['ItemQty7'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_08'] == 'True' || $_SESSION['RegenesisReorderRVProd_08'] == 'True'))
    {
	    while($row8 = mssql_fetch_array($qryCart8))
	    {
		   $strProductName8 = $row8["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice8'] * $_POST['ItemQty8']) - ($_POST['ItemPrice8'] * $_POST['ItemQty8'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_09'] == 'True' || $_SESSION['RegenesisReorderRVProd_09'] == 'True'))
    {
	    while($row9 = mssql_fetch_array($qryCart9))
	    {
		   $strProductName9 = $row9["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice9'] * $_POST['ItemQty9']) - ($_POST['ItemPrice9'] * $_POST['ItemQty9'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_10'] == 'True' || $_SESSION['RegenesisReorderRVProd_10'] == 'True'))
    {
	    while($row10 = mssql_fetch_array($qryCart10))
	    {
		   $strProductName10 = $row10["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice10'] * $_POST['ItemQty10']) - ($_POST['ItemPrice10'] * $_POST['ItemQty10'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_11'] == 'True' || $_SESSION['RegenesisReorderRVProd_11'] == 'True'))
    {
	    while($row11 = mssql_fetch_array($qryCart11))
	    {
		   $strProductName11 = $row11["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice11'] * $_POST['ItemQty11']) - ($_POST['ItemPrice11'] * $_POST['ItemQty11'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_12'] == 'True' || $_SESSION['RegenesisReorderRVProd_12'] == 'True'))
    {
	    while($row12 = mssql_fetch_array($qryCart12))
	    {
		   $strProductName12 = $row12["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice12'] * $_POST['ItemQty12']) - ($_POST['ItemPrice12'] * $_POST['ItemQty12'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_13'] == 'True' || $_SESSION['RegenesisReorderRVProd_13'] == 'True'))
    {
	    while($row13 = mssql_fetch_array($qryCart13))
	    {
		   $strProductName13 = $row13["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice13'] * $_POST['ItemQty13']) - ($_POST['ItemPrice13'] * $_POST['ItemQty13'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_14'] == 'True' || $_SESSION['RegenesisReorderRVProd_14'] == 'True'))
    {
	    while($row14 = mssql_fetch_array($qryCart14))
	    {
		   $strProductName14 = $row14["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice14'] * $_POST['ItemQty14']) - ($_POST['ItemPrice14'] * $_POST['ItemQty14'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_15'] == 'True' || $_SESSION['RegenesisReorderRVProd_15'] == 'True'))
    {
	    while($row15 = mssql_fetch_array($qryCart15))
	    {
		   $strProductName15 = $row15["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice15'] * $_POST['ItemQty15']) - ($_POST['ItemPrice15'] * $_POST['ItemQty15'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_16'] == 'True' || $_SESSION['RegenesisReorderRVProd_16'] == 'True'))
    {
	    while($row16 = mssql_fetch_array($qryCart16))
	    {
		   $strProductName16 = $row16["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice16'] * $_POST['ItemQty16']) - ($_POST['ItemPrice16'] * $_POST['ItemQty16'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_17'] == 'True' || $_SESSION['RegenesisReorderRVProd_17'] == 'True'))
    {
	    while($row17 = mssql_fetch_array($qryCart17))
	    {
		   $strProductName17 = $row17["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice17'] * $_POST['ItemQty17']) - ($_POST['ItemPrice17'] * $_POST['ItemQty17'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_18'] == 'True' || $_SESSION['RegenesisReorderRVProd_18'] == 'True'))
    {
	    while($row18 = mssql_fetch_array($qryCart18))
	    {
		   $strProductName18 = $row18["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice18'] * $_POST['ItemQty18']) - ($_POST['ItemPrice18'] * $_POST['ItemQty18'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_19'] == 'True' || $_SESSION['RegenesisReorderRVProd_19'] == 'True'))
    {
	    while($row19 = mssql_fetch_array($qryCart19))
	    {
		   $strProductName19 = $row19["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice19'] * $_POST['ItemQty19']) - ($_POST['ItemPrice19'] * $_POST['ItemQty19'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
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

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_20'] == 'True' || $_SESSION['RegenesisReorderRVProd_20'] == 'True'))
    {
	    while($row20 = mssql_fetch_array($qryCart20))
	    {
		   $strProductName20 = $row20["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice20'] * $_POST['ItemQty20']) - ($_POST['ItemPrice20'] * $_POST['ItemQty20'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row20 = mssql_fetch_array($qryCart20))
	    {
		    $strProductName20 = $row20["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice20'] * $_POST['ItemQty20'];
	    }
    }
}

// GMC - 10/16/13 - Add 20 Line Items Admin

if (isset($_POST['ItemID21']) && $_POST['ItemID21'] != 0)
{
    if($_SESSION['Bundles2010_21'] != '')
    {
	    while($row21 = mssql_fetch_array($qryCart21))
	    {
		    $strProductName21 = $row21["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice21'] * $_POST['ItemQty21'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt21))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty21'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_21'] == 'True' || $_SESSION['RegenesisReorderRVProd_21'] == 'True'))
    {
	    while($row21 = mssql_fetch_array($qryCart21))
	    {
		   $strProductName21 = $row21["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice21'] * $_POST['ItemQty21']) - ($_POST['ItemPrice21'] * $_POST['ItemQty21'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row21 = mssql_fetch_array($qryCart21))
	    {
		    $strProductName21 = $row21["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice21'] * $_POST['ItemQty21'];
	    }
    }
}

if (isset($_POST['ItemID22']) && $_POST['ItemID22'] != 0)
{
    if($_SESSION['Bundles2010_22'] != '')
    {
	    while($row22 = mssql_fetch_array($qryCart22))
	    {
		    $strProductName22 = $row22["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice22'] * $_POST['ItemQty22'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt22))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty22'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_22'] == 'True' || $_SESSION['RegenesisReorderRVProd_22'] == 'True'))
    {
	    while($row22 = mssql_fetch_array($qryCart22))
	    {
		   $strProductName22 = $row22["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice22'] * $_POST['ItemQty22']) - ($_POST['ItemPrice22'] * $_POST['ItemQty22'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row22 = mssql_fetch_array($qryCart22))
	    {
		    $strProductName22 = $row22["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice22'] * $_POST['ItemQty22'];
	    }
    }
}

if (isset($_POST['ItemID23']) && $_POST['ItemID23'] != 0)
{
    if($_SESSION['Bundles2010_23'] != '')
    {
	    while($row23 = mssql_fetch_array($qryCart23))
	    {
		    $strProductName23 = $row23["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice23'] * $_POST['ItemQty23'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt23))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty23'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_23'] == 'True' || $_SESSION['RegenesisReorderRVProd_23'] == 'True'))
    {
	    while($row23 = mssql_fetch_array($qryCart23))
	    {
		   $strProductName23 = $row23["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice23'] * $_POST['ItemQty23']) - ($_POST['ItemPrice23'] * $_POST['ItemQty23'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row23 = mssql_fetch_array($qryCart23))
	    {
		    $strProductName23 = $row23["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice23'] * $_POST['ItemQty23'];
	    }
    }
}

if (isset($_POST['ItemID24']) && $_POST['ItemID24'] != 0)
{
    if($_SESSION['Bundles2010_24'] != '')
    {
	    while($row24 = mssql_fetch_array($qryCart24))
	    {
		    $strProductName24 = $row24["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice24'] * $_POST['ItemQty24'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt24))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty24'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_24'] == 'True' || $_SESSION['RegenesisReorderRVProd_24'] == 'True'))
    {
	    while($row24 = mssql_fetch_array($qryCart24))
	    {
		   $strProductName24 = $row24["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice24'] * $_POST['ItemQty24']) - ($_POST['ItemPrice24'] * $_POST['ItemQty24'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row24 = mssql_fetch_array($qryCart24))
	    {
		    $strProductName24 = $row24["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice24'] * $_POST['ItemQty24'];
	    }
    }
}

if (isset($_POST['ItemID25']) && $_POST['ItemID25'] != 0)
{
    if($_SESSION['Bundles2010_25'] != '')
    {
	    while($row25 = mssql_fetch_array($qryCart25))
	    {
		    $strProductName25 = $row25["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice25'] * $_POST['ItemQty25'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt25))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty25'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_25'] == 'True' || $_SESSION['RegenesisReorderRVProd_25'] == 'True'))
    {
	    while($row25 = mssql_fetch_array($qryCart25))
	    {
		   $strProductName25 = $row25["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice25'] * $_POST['ItemQty25']) - ($_POST['ItemPrice25'] * $_POST['ItemQty25'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row25 = mssql_fetch_array($qryCart25))
	    {
		    $strProductName25 = $row25["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice25'] * $_POST['ItemQty25'];
	    }
    }
}

if (isset($_POST['ItemID26']) && $_POST['ItemID26'] != 0)
{
    if($_SESSION['Bundles2010_26'] != '')
    {
	    while($row26 = mssql_fetch_array($qryCart26))
	    {
		    $strProductName26 = $row26["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice26'] * $_POST['ItemQty26'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt26))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty26'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_26'] == 'True' || $_SESSION['RegenesisReorderRVProd_26'] == 'True'))
    {
	    while($row26 = mssql_fetch_array($qryCart26))
	    {
		   $strProductName26 = $row26["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice26'] * $_POST['ItemQty26']) - ($_POST['ItemPrice26'] * $_POST['ItemQty26'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row26 = mssql_fetch_array($qryCart26))
	    {
		    $strProductName26 = $row26["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice26'] * $_POST['ItemQty26'];
	    }
    }
}

if (isset($_POST['ItemID27']) && $_POST['ItemID27'] != 0)
{
    if($_SESSION['Bundles2010_27'] != '')
    {
	    while($row27 = mssql_fetch_array($qryCart27))
	    {
		    $strProductName27 = $row27["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice27'] * $_POST['ItemQty27'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt27))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty27'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_27'] == 'True' || $_SESSION['RegenesisReorderRVProd_27'] == 'True'))
    {
	    while($row27 = mssql_fetch_array($qryCart27))
	    {
		   $strProductName27 = $row27["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice27'] * $_POST['ItemQty27']) - ($_POST['ItemPrice27'] * $_POST['ItemQty27'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row27 = mssql_fetch_array($qryCart27))
	    {
		    $strProductName27 = $row27["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice27'] * $_POST['ItemQty27'];
	    }
    }
}

if (isset($_POST['ItemID28']) && $_POST['ItemID28'] != 0)
{
    if($_SESSION['Bundles2010_28'] != '')
    {
	    while($row28 = mssql_fetch_array($qryCart28))
	    {
		    $strProductName28 = $row28["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice28'] * $_POST['ItemQty28'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt28))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty28'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_28'] == 'True' || $_SESSION['RegenesisReorderRVProd_28'] == 'True'))
    {
	    while($row28 = mssql_fetch_array($qryCart28))
	    {
		   $strProductName28 = $row28["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice28'] * $_POST['ItemQty28']) - ($_POST['ItemPrice28'] * $_POST['ItemQty28'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row28 = mssql_fetch_array($qryCart28))
	    {
		    $strProductName28 = $row28["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice28'] * $_POST['ItemQty28'];
	    }
    }
}

if (isset($_POST['ItemID29']) && $_POST['ItemID29'] != 0)
{
    if($_SESSION['Bundles2010_29'] != '')
    {
	    while($row29 = mssql_fetch_array($qryCart29))
	    {
		    $strProductName29 = $row29["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice29'] * $_POST['ItemQty29'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt29))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty29'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_29'] == 'True' || $_SESSION['RegenesisReorderRVProd_29'] == 'True'))
    {
	    while($row29 = mssql_fetch_array($qryCart29))
	    {
		   $strProductName29 = $row29["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice29'] * $_POST['ItemQty29']) - ($_POST['ItemPrice29'] * $_POST['ItemQty29'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row29 = mssql_fetch_array($qryCart29))
	    {
		    $strProductName29 = $row29["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice29'] * $_POST['ItemQty29'];
	    }
    }
}

if (isset($_POST['ItemID30']) && $_POST['ItemID30'] != 0)
{
    if($_SESSION['Bundles2010_30'] != '')
    {
	    while($row30 = mssql_fetch_array($qryCart30))
	    {
		    $strProductName30 = $row30["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice30'] * $_POST['ItemQty30'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt30))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty30'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_30'] == 'True' || $_SESSION['RegenesisReorderRVProd_30'] == 'True'))
    {
	    while($row30 = mssql_fetch_array($qryCart30))
	    {
		   $strProductName30 = $row30["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice30'] * $_POST['ItemQty30']) - ($_POST['ItemPrice30'] * $_POST['ItemQty30'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row30 = mssql_fetch_array($qryCart30))
	    {
		    $strProductName30 = $row30["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice30'] * $_POST['ItemQty30'];
	    }
    }
}

if (isset($_POST['ItemID31']) && $_POST['ItemID31'] != 0)
{
    if($_SESSION['Bundles2010_31'] != '')
    {
	    while($row31 = mssql_fetch_array($qryCart31))
	    {
		    $strProductName31 = $row31["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice31'] * $_POST['ItemQty31'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt31))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty31'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_31'] == 'True' || $_SESSION['RegenesisReorderRVProd_31'] == 'True'))
    {
	    while($row31 = mssql_fetch_array($qryCart31))
	    {
		   $strProductName31 = $row31["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice31'] * $_POST['ItemQty31']) - ($_POST['ItemPrice31'] * $_POST['ItemQty31'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row31 = mssql_fetch_array($qryCart31))
	    {
		    $strProductName31 = $row31["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice31'] * $_POST['ItemQty31'];
	    }
    }
}

if (isset($_POST['ItemID32']) && $_POST['ItemID32'] != 0)
{
    if($_SESSION['Bundles2010_32'] != '')
    {
	    while($row32 = mssql_fetch_array($qryCart32))
	    {
		    $strProductName32 = $row32["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice32'] * $_POST['ItemQty32'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt32))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty32'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_32'] == 'True' || $_SESSION['RegenesisReorderRVProd_32'] == 'True'))
    {
	    while($row32 = mssql_fetch_array($qryCart32))
	    {
		   $strProductName32 = $row32["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice32'] * $_POST['ItemQty32']) - ($_POST['ItemPrice32'] * $_POST['ItemQty32'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row32 = mssql_fetch_array($qryCart32))
	    {
		    $strProductName32 = $row32["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice32'] * $_POST['ItemQty32'];
	    }
    }
}

if (isset($_POST['ItemID33']) && $_POST['ItemID33'] != 0)
{
    if($_SESSION['Bundles2010_33'] != '')
    {
	    while($row33 = mssql_fetch_array($qryCart33))
	    {
		    $strProductName33 = $row33["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice33'] * $_POST['ItemQty33'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt33))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty33'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_33'] == 'True' || $_SESSION['RegenesisReorderRVProd_33'] == 'True'))
    {
	    while($row33 = mssql_fetch_array($qryCart33))
	    {
		   $strProductName33 = $row33["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice33'] * $_POST['ItemQty33']) - ($_POST['ItemPrice33'] * $_POST['ItemQty33'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row33 = mssql_fetch_array($qryCart33))
	    {
		    $strProductName33 = $row33["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice33'] * $_POST['ItemQty33'];
	    }
    }
}

if (isset($_POST['ItemID34']) && $_POST['ItemID34'] != 0)
{
    if($_SESSION['Bundles2010_34'] != '')
    {
	    while($row34 = mssql_fetch_array($qryCart34))
	    {
		    $strProductName34 = $row34["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice34'] * $_POST['ItemQty34'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt34))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty34'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_34'] == 'True' || $_SESSION['RegenesisReorderRVProd_34'] == 'True'))
    {
	    while($row34 = mssql_fetch_array($qryCart34))
	    {
		   $strProductName34 = $row34["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice34'] * $_POST['ItemQty34']) - ($_POST['ItemPrice34'] * $_POST['ItemQty34'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row34 = mssql_fetch_array($qryCart34))
	    {
		    $strProductName34 = $row34["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice34'] * $_POST['ItemQty34'];
	    }
    }
}

if (isset($_POST['ItemID35']) && $_POST['ItemID35'] != 0)
{
    if($_SESSION['Bundles2010_35'] != '')
    {
	    while($row35 = mssql_fetch_array($qryCart35))
	    {
		    $strProductName35 = $row35["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice35'] * $_POST['ItemQty35'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt35))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty35'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_35'] == 'True' || $_SESSION['RegenesisReorderRVProd_35'] == 'True'))
    {
	    while($row35 = mssql_fetch_array($qryCart35))
	    {
		   $strProductName35 = $row35["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice35'] * $_POST['ItemQty35']) - ($_POST['ItemPrice35'] * $_POST['ItemQty35'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row35 = mssql_fetch_array($qryCart35))
	    {
		    $strProductName35 = $row35["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice35'] * $_POST['ItemQty35'];
	    }
    }
}

if (isset($_POST['ItemID36']) && $_POST['ItemID36'] != 0)
{
    if($_SESSION['Bundles2010_36'] != '')
    {
	    while($row36 = mssql_fetch_array($qryCart36))
	    {
		    $strProductName36 = $row36["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice36'] * $_POST['ItemQty36'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt36))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty36'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_36'] == 'True' || $_SESSION['RegenesisReorderRVProd_36'] == 'True'))
    {
	    while($row36 = mssql_fetch_array($qryCart36))
	    {
		   $strProductName36 = $row36["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice36'] * $_POST['ItemQty36']) - ($_POST['ItemPrice36'] * $_POST['ItemQty36'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row36 = mssql_fetch_array($qryCart36))
	    {
		    $strProductName36 = $row36["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice36'] * $_POST['ItemQty36'];
	    }
    }
}

if (isset($_POST['ItemID37']) && $_POST['ItemID37'] != 0)
{
    if($_SESSION['Bundles2010_37'] != '')
    {
	    while($row37 = mssql_fetch_array($qryCart37))
	    {
		    $strProductName37 = $row37["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice37'] * $_POST['ItemQty37'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt37))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty37'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_37'] == 'True' || $_SESSION['RegenesisReorderRVProd_37'] == 'True'))
    {
	    while($row37 = mssql_fetch_array($qryCart37))
	    {
		   $strProductName37 = $row37["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice37'] * $_POST['ItemQty37']) - ($_POST['ItemPrice37'] * $_POST['ItemQty37'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row37 = mssql_fetch_array($qryCart37))
	    {
		    $strProductName37 = $row37["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice37'] * $_POST['ItemQty37'];
	    }
    }
}

if (isset($_POST['ItemID38']) && $_POST['ItemID38'] != 0)
{
    if($_SESSION['Bundles2010_38'] != '')
    {
	    while($row38 = mssql_fetch_array($qryCart38))
	    {
		    $strProductName38 = $row38["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice38'] * $_POST['ItemQty38'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt38))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty38'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_38'] == 'True' || $_SESSION['RegenesisReorderRVProd_38'] == 'True'))
    {
	    while($row38 = mssql_fetch_array($qryCart38))
	    {
		   $strProductName38 = $row38["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice38'] * $_POST['ItemQty38']) - ($_POST['ItemPrice38'] * $_POST['ItemQty38'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row38 = mssql_fetch_array($qryCart38))
	    {
		    $strProductName38 = $row38["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice38'] * $_POST['ItemQty38'];
	    }
    }
}

if (isset($_POST['ItemID39']) && $_POST['ItemID39'] != 0)
{
    if($_SESSION['Bundles2010_39'] != '')
    {
	    while($row39 = mssql_fetch_array($qryCart39))
	    {
		    $strProductName39 = $row39["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice39'] * $_POST['ItemQty39'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt39))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty39'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_39'] == 'True' || $_SESSION['RegenesisReorderRVProd_39'] == 'True'))
    {
	    while($row39 = mssql_fetch_array($qryCart39))
	    {
		   $strProductName39 = $row39["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice39'] * $_POST['ItemQty39']) - ($_POST['ItemPrice39'] * $_POST['ItemQty39'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row39 = mssql_fetch_array($qryCart39))
	    {
		    $strProductName39 = $row39["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice39'] * $_POST['ItemQty39'];
	    }
    }
}

if (isset($_POST['ItemID40']) && $_POST['ItemID40'] != 0)
{
    if($_SESSION['Bundles2010_40'] != '')
    {
	    while($row40 = mssql_fetch_array($qryCart40))
	    {
		    $strProductName40 = $row40["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice40'] * $_POST['ItemQty40'];
      	}
        while($rowGetBundle = mssql_fetch_array($qryCartBundleExt40))
        {
            // $decSubtotal += number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $_POST['ItemQty40'], 2, '.', '');
        }
    }

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    else if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_40'] == 'True' || $_SESSION['RegenesisReorderRVProd_40'] == 'True'))
    {
	    while($row40 = mssql_fetch_array($qryCart40))
	    {
		   $strProductName40 = $row40["ProductName"];
		   $decSubtotal = $decSubtotal + (($_POST['ItemPrice40'] * $_POST['ItemQty40']) - ($_POST['ItemPrice40'] * $_POST['ItemQty40'] * $_SESSION['RegenesisReorderDiscount']));
	    }
    }
    */
    
    else
    {
	    while($row40 = mssql_fetch_array($qryCart40))
	    {
		    $strProductName40 = $row40["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice40'] * $_POST['ItemQty40'];
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

        // GMC - 08/22/14 - FL - NJ - PA - OK - WA - IL State Tax Activation
        // GMC - 05/07/14 - State Tax Deactivate - FL - OK - PA and activate MD - MN - NY
        // elseif(strtoupper($_SESSION['Ship_State']) == 'FL')
        elseif(strtoupper($_SESSION['Ship_State']) == 'MD')
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

        elseif(strtoupper($_SESSION['Ship_State']) == 'NJ')
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

        // elseif(strtoupper($_SESSION['Ship_State']) == 'PA')
        elseif(strtoupper($_SESSION['Ship_State']) == 'MN')
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

        // GMC - 05/17/15 - State Tax Deactivate - NY and Activate MA
        // elseif(strtoupper($_SESSION['Ship_State']) == 'OK')
        // elseif(strtoupper($_SESSION['Ship_State']) == 'NY')
        elseif(strtoupper($_SESSION['Ship_State']) == 'MA')
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

        elseif(strtoupper($_SESSION['Ship_State']) == 'WA')
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

        elseif(strtoupper($_SESSION['Ship_State']) == 'IL')
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

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_1'] != "")
    {
	    $strConfirmation .= '<td>' . $strProductName1 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_1'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName1 . '</td>';
    }

    $strConfirmation .= '<td>$' . number_format($_POST['ItemPrice1'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty1'];
	if ($ItemFree1 > 0) $strConfirmation .= ' + ' . $ItemFree1 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_01'] == 'True' || $_SESSION['RegenesisReorderRVProd_01'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice1'] * $_POST['ItemQty1']) - ($_POST['ItemPrice1'] * $_POST['ItemQty1'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice1'] * $_POST['ItemQty1'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_1'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_1'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_1'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_1'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_2'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName2 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_2'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName2 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice2'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty2'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty2'];
	if ($ItemFree2 > 0) $strConfirmation .= ' + ' . $ItemFree2 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_02'] == 'True' || $_SESSION['RegenesisReorderRVProd_02'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice2'] * $_POST['ItemQty2']) - ($_POST['ItemPrice2'] * $_POST['ItemQty2'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice2'] * $_POST['ItemQty2'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_2'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_2'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_2'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_2'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_3'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName3 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_3'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName3 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice3'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty3'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty3'];
	if ($ItemFree3 > 0) $strConfirmation .= ' + ' . $ItemFree3 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_03'] == 'True' || $_SESSION['RegenesisReorderRVProd_03'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice3'] * $_POST['ItemQty3']) - ($_POST['ItemPrice3'] * $_POST['ItemQty3'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice3'] * $_POST['ItemQty3'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_3'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_3'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_3'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_3'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_4'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName4 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_4'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName4 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice4'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty4'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty4'];
	if ($ItemFree4 > 0) $strConfirmation .= ' + ' . $ItemFree4 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_04'] == 'True' || $_SESSION['RegenesisReorderRVProd_04'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice4'] * $_POST['ItemQty4']) - ($_POST['ItemPrice4'] * $_POST['ItemQty4'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice4'] * $_POST['ItemQty4'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_4'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_4'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_4'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_4'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_5'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName5 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_5'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName5 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice5'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty5'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty5'];
	if ($ItemFree5 > 0) $strConfirmation .= ' + ' . $ItemFree5 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_05'] == 'True' || $_SESSION['RegenesisReorderRVProd_05'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice5'] * $_POST['ItemQty5']) - ($_POST['ItemPrice5'] * $_POST['ItemQty5'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice5'] * $_POST['ItemQty5'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_5'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_5'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_5'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_5'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_6'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName6 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_6'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName6 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice6'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty6'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty6'];
	if ($ItemFree6 > 0) $strConfirmation .= ' + ' . $ItemFree6 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_06'] == 'True' || $_SESSION['RegenesisReorderRVProd_06'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice6'] * $_POST['ItemQty6']) - ($_POST['ItemPrice6'] * $_POST['ItemQty6'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice6'] * $_POST['ItemQty6'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_6'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_6'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_6'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_6'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_7'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName7 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_7'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName7 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice7'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty7'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty7'];
	if ($ItemFree7 > 0) $strConfirmation .= ' + ' . $ItemFree7 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_07'] == 'True' || $_SESSION['RegenesisReorderRVProd_07'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice7'] * $_POST['ItemQty7']) - ($_POST['ItemPrice7'] * $_POST['ItemQty7'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice7'] * $_POST['ItemQty7'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_7'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_7'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_7'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_7'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_8'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName8 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_8'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName8 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice8'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty8'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty8'];
	if ($ItemFree8 > 0) $strConfirmation .= ' + ' . $ItemFree8 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_08'] == 'True' || $_SESSION['RegenesisReorderRVProd_08'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice8'] * $_POST['ItemQty8']) - ($_POST['ItemPrice8'] * $_POST['ItemQty8'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice8'] * $_POST['ItemQty8'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_8'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_8'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_8'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_8'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_9'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName9 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_9'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName9 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice9'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty9'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty9'];
	if ($ItemFree9 > 0) $strConfirmation .= ' + ' . $ItemFree9 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_09'] == 'True' || $_SESSION['RegenesisReorderRVProd_09'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice9'] * $_POST['ItemQty9']) - ($_POST['ItemPrice9'] * $_POST['ItemQty9'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice9'] * $_POST['ItemQty9'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_9'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_9'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_9'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_9'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_10'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName10 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_10'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName10 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice10'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty10'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty10'];
	if ($ItemFree10 > 0) $strConfirmation .= ' + ' . $ItemFree10 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_10'] == 'True' || $_SESSION['RegenesisReorderRVProd_10'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice10'] * $_POST['ItemQty10']) - ($_POST['ItemPrice10'] * $_POST['ItemQty10'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice10'] * $_POST['ItemQty10'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_10'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_10'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_10'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_10'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

// GMC - 03/18/10 - Add 10 Line Items Admin

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_11'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName11 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_11'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName11 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice11'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty11'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty11'];
	if ($ItemFree11 > 0) $strConfirmation .= ' + ' . $ItemFree11 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_11'] == 'True' || $_SESSION['RegenesisReorderRVProd_11'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice11'] * $_POST['ItemQty11']) - ($_POST['ItemPrice11'] * $_POST['ItemQty11'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice11'] * $_POST['ItemQty11'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_11'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_11'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_11'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_11'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_12'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName12 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_12'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName12 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice12'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty12'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty12'];
	if ($ItemFree12 > 0) $strConfirmation .= ' + ' . $ItemFree12 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_12'] == 'True' || $_SESSION['RegenesisReorderRVProd_12'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice12'] * $_POST['ItemQty12']) - ($_POST['ItemPrice12'] * $_POST['ItemQty12'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice12'] * $_POST['ItemQty12'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_12'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_12'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_12'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_12'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_13'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName13 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_13'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName13 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice13'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty13'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty13'];
	if ($ItemFree13 > 0) $strConfirmation .= ' + ' . $ItemFree13 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_13'] == 'True' || $_SESSION['RegenesisReorderRVProd_13'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice13'] * $_POST['ItemQty13']) - ($_POST['ItemPrice13'] * $_POST['ItemQty13'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice13'] * $_POST['ItemQty13'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_13'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_13'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_13'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_13'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_14'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName14 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_14'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName14 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice14'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty14'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty14'];
	if ($ItemFree14 > 0) $strConfirmation .= ' + ' . $ItemFree14 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_14'] == 'True' || $_SESSION['RegenesisReorderRVProd_14'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice14'] * $_POST['ItemQty14']) - ($_POST['ItemPrice14'] * $_POST['ItemQty14'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice14'] * $_POST['ItemQty14'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_14'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_14'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_14'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_14'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_15'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName15 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_15'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName15 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice15'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty15'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty15'];
	if ($ItemFree15 > 0) $strConfirmation .= ' + ' . $ItemFree15 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_15'] == 'True' || $_SESSION['RegenesisReorderRVProd_15'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice15'] * $_POST['ItemQty15']) - ($_POST['ItemPrice15'] * $_POST['ItemQty15'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice15'] * $_POST['ItemQty15'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_15'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_15'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_15'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_15'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_16'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName16 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_16'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName16 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice16'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty16'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty16'];
	if ($ItemFree16 > 0) $strConfirmation .= ' + ' . $ItemFree16 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_16'] == 'True' || $_SESSION['RegenesisReorderRVProd_16'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice16'] * $_POST['ItemQty16']) - ($_POST['ItemPrice16'] * $_POST['ItemQty16'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice16'] * $_POST['ItemQty16'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_16'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_16'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_16'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_16'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_17'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName17 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_17'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName17 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice17'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty17'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty17'];
	if ($ItemFree17 > 0) $strConfirmation .= ' + ' . $ItemFree17 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_17'] == 'True' || $_SESSION['RegenesisReorderRVProd_17'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice17'] * $_POST['ItemQty17']) - ($_POST['ItemPrice17'] * $_POST['ItemQty17'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice17'] * $_POST['ItemQty17'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_17'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_17'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_17'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_17'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_18'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName18 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_18'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName18 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice18'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty18'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty18'];
	if ($ItemFree18 > 0) $strConfirmation .= ' + ' . $ItemFree18 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_18'] == 'True' || $_SESSION['RegenesisReorderRVProd_18'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice18'] * $_POST['ItemQty18']) - ($_POST['ItemPrice18'] * $_POST['ItemQty18'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice18'] * $_POST['ItemQty18'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_18'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_18'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_18'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_18'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_19'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName19 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_19'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName19 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice19'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty19'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty19'];
	if ($ItemFree19 > 0) $strConfirmation .= ' + ' . $ItemFree19 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_19'] == 'True' || $_SESSION['RegenesisReorderRVProd_19'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice19'] * $_POST['ItemQty19']) - ($_POST['ItemPrice19'] * $_POST['ItemQty19'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice19'] * $_POST['ItemQty19'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_19'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_19'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_19'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_19'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
{
	$strConfirmation .= '<tr>';

    // GMC - 06/11/13 - Special Discount Process System
    if($_SESSION['SpecDisc_20'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName20 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_20'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName20 . '</td>';
    }

	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice20'], 2, '.', '') . '</td>';

    // GMC - 08/13/12 - Fix Bug with Free Items not showing
	// $strConfirmation .= '<td>' . $_POST['ItemQty20'] . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty20'];
	if ($ItemFree20 > 0) $strConfirmation .= ' + ' . $ItemFree20 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_20'] == 'True' || $_SESSION['RegenesisReorderRVProd_20'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice20'] * $_POST['ItemQty20']) - ($_POST['ItemPrice20'] * $_POST['ItemQty20'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice20'] * $_POST['ItemQty20'], 2, '.', '') . '</td>';
    }
    */
    
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

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_20'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_20'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_20'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_20'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

// GMC - 10/16/13 - Add 20 Line Items Admin

if (isset($_POST['ItemID21']) && $_POST['ItemID21'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_21'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName21 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_21'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName21 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice21'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty21'];
	if ($ItemFree21 > 0) $strConfirmation .= ' + ' . $ItemFree21 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_21'] == 'True' || $_SESSION['RegenesisReorderRVProd_21'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice21'] * $_POST['ItemQty21']) - ($_POST['ItemPrice21'] * $_POST['ItemQty21'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice21'] * $_POST['ItemQty21'], 2, '.', '') . '</td>';
    }
    */

    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice21'] * $_POST['ItemQty21'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_21'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_21'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_21'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_21'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_21'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_21'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_21'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_21'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_21'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_21'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_21'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_21'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_21'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_21']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle21))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_21'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_21'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_21'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_21'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID22']) && $_POST['ItemID22'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_22'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName22 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_22'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName22 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice22'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty22'];
	if ($ItemFree22 > 0) $strConfirmation .= ' + ' . $ItemFree22 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_22'] == 'True' || $_SESSION['RegenesisReorderRVProd_22'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice22'] * $_POST['ItemQty22']) - ($_POST['ItemPrice22'] * $_POST['ItemQty22'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice22'] * $_POST['ItemQty22'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice22'] * $_POST['ItemQty22'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_22'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_22'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_22'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_22'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_22'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_22'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_22'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_22'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_22'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_22'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_22'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_22'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_22'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_22']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle22))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_22'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_22'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_22'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_22'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID23']) && $_POST['ItemID23'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_23'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName23 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_23'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName23 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice23'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty23'];
	if ($ItemFree23 > 0) $strConfirmation .= ' + ' . $ItemFree23 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_23'] == 'True' || $_SESSION['RegenesisReorderRVProd_23'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice23'] * $_POST['ItemQty23']) - ($_POST['ItemPrice23'] * $_POST['ItemQty23'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice23'] * $_POST['ItemQty23'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice23'] * $_POST['ItemQty23'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_23'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_23'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_23'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_23'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_23'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_23'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_23'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_23'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_23'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_23'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_23'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_23'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_23'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_23']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle23))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_23'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_23'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_23'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_23'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID24']) && $_POST['ItemID24'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_24'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName24 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_24'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName24 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice24'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty24'];
	if ($ItemFree24 > 0) $strConfirmation .= ' + ' . $ItemFree24 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_24'] == 'True' || $_SESSION['RegenesisReorderRVProd_24'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice24'] * $_POST['ItemQty24']) - ($_POST['ItemPrice24'] * $_POST['ItemQty24'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice24'] * $_POST['ItemQty24'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice24'] * $_POST['ItemQty24'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_24'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_24'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_24'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_24'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_24'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_24'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_24'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_24'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_24'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_24'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_24'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_24'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_24'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_24']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle24))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_24'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_24'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_24'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_24'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID25']) && $_POST['ItemID25'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_25'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName25 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_25'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName25 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice25'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty25'];
	if ($ItemFree25 > 0) $strConfirmation .= ' + ' . $ItemFree25 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_25'] == 'True' || $_SESSION['RegenesisReorderRVProd_25'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice25'] * $_POST['ItemQty25']) - ($_POST['ItemPrice25'] * $_POST['ItemQty25'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice25'] * $_POST['ItemQty25'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice25'] * $_POST['ItemQty25'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_25'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_25'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_25'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_25'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_25'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_25'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_25'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_25'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_25'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_25'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_25'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_25'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_25'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_25']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle25))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_25'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_25'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_25'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_25'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID26']) && $_POST['ItemID26'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_26'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName26 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_26'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName26 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice26'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty26'];
	if ($ItemFree26 > 0) $strConfirmation .= ' + ' . $ItemFree26 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_26'] == 'True' || $_SESSION['RegenesisReorderRVProd_26'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice26'] * $_POST['ItemQty26']) - ($_POST['ItemPrice26'] * $_POST['ItemQty26'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice26'] * $_POST['ItemQty26'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice26'] * $_POST['ItemQty26'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_26'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_26'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_26'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_26'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_26'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_26'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_26'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_26'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_26'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_26'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_26'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_26'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_26'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_26']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle26))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_26'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_26'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_26'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_26'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID27']) && $_POST['ItemID27'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_27'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName27 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_27'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName27 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice27'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty27'];
	if ($ItemFree27 > 0) $strConfirmation .= ' + ' . $ItemFree27 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_27'] == 'True' || $_SESSION['RegenesisReorderRVProd_27'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice27'] * $_POST['ItemQty27']) - ($_POST['ItemPrice27'] * $_POST['ItemQty27'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice27'] * $_POST['ItemQty27'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice27'] * $_POST['ItemQty27'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_27'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_27'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_27'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_27'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_27'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_27'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_27'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_27'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_27'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_27'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_27'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_27'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_27'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_27']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle27))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_27'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_27'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_27'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_27'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID28']) && $_POST['ItemID28'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_28'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName28 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_28'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName28 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice28'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty28'];
	if ($ItemFree28 > 0) $strConfirmation .= ' + ' . $ItemFree28 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_28'] == 'True' || $_SESSION['RegenesisReorderRVProd_28'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice28'] * $_POST['ItemQty28']) - ($_POST['ItemPrice28'] * $_POST['ItemQty28'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice28'] * $_POST['ItemQty28'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice28'] * $_POST['ItemQty28'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_28'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_28'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_28'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_28'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_28'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_28'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_28'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_28'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_28'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_28'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_28'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_28'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_28'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_28']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle28))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_28'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_28'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_28'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_28'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID29']) && $_POST['ItemID29'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_29'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName29 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_29'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName29 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice29'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty29'];
	if ($ItemFree29 > 0) $strConfirmation .= ' + ' . $ItemFree29 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_29'] == 'True' || $_SESSION['RegenesisReorderRVProd_29'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice29'] * $_POST['ItemQty29']) - ($_POST['ItemPrice29'] * $_POST['ItemQty29'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice29'] * $_POST['ItemQty29'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice29'] * $_POST['ItemQty29'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_29'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_29'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_29'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_29'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_29'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_29'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_29'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_29'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_29'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_29'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_29'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_29'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_29'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_29']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle29))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_29'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_29'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_29'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_29'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID30']) && $_POST['ItemID30'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_30'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName30 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_30'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName30 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice30'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty30'];
	if ($ItemFree30 > 0) $strConfirmation .= ' + ' . $ItemFree30 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_30'] == 'True' || $_SESSION['RegenesisReorderRVProd_30'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice30'] * $_POST['ItemQty30']) - ($_POST['ItemPrice30'] * $_POST['ItemQty30'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice30'] * $_POST['ItemQty30'], 2, '.', '') . '</td>';
    }
    */

    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice30'] * $_POST['ItemQty30'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_30'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_30'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_30'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_30'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_30'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_30'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_30'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_30'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_30'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_30'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_30'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_30'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_30'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_30']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle30))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_30'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_30'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_30'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_30'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID31']) && $_POST['ItemID31'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_31'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName31 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_31'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName31 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice31'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty31'];
	if ($ItemFree31 > 0) $strConfirmation .= ' + ' . $ItemFree31 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_31'] == 'True' || $_SESSION['RegenesisReorderRVProd_31'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice31'] * $_POST['ItemQty31']) - ($_POST['ItemPrice31'] * $_POST['ItemQty31'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice31'] * $_POST['ItemQty31'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice31'] * $_POST['ItemQty31'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_31'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_31'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_31'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_31'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_31'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_31'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_31'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_31'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_31'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_31'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_31'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_31'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_31'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_31']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle31))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_31'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_31'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_31'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_31'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID32']) && $_POST['ItemID32'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_32'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName32 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_32'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName32 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice32'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty32'];
	if ($ItemFree32 > 0) $strConfirmation .= ' + ' . $ItemFree32 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_32'] == 'True' || $_SESSION['RegenesisReorderRVProd_32'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice32'] * $_POST['ItemQty32']) - ($_POST['ItemPrice32'] * $_POST['ItemQty32'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice32'] * $_POST['ItemQty32'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice32'] * $_POST['ItemQty32'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_32'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_32'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_32'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_32'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_32'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_32'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_32'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_32'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_32'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_32'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_32'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_32'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_32'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_32']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle32))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_32'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_32'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_32'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_32'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID33']) && $_POST['ItemID33'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_33'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName33 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_33'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName33 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice33'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty33'];
	if ($ItemFree33 > 0) $strConfirmation .= ' + ' . $ItemFree33 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_33'] == 'True' || $_SESSION['RegenesisReorderRVProd_33'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice33'] * $_POST['ItemQty33']) - ($_POST['ItemPrice33'] * $_POST['ItemQty33'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice33'] * $_POST['ItemQty33'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice33'] * $_POST['ItemQty33'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_33'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_33'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_33'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_33'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_33'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_33'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_33'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_33'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_33'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_33'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_33'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_33'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_33'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_33']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle33))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_33'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_33'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_33'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_33'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID34']) && $_POST['ItemID34'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_34'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName34 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_34'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName34 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice34'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty34'];
	if ($ItemFree34 > 0) $strConfirmation .= ' + ' . $ItemFree34 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_34'] == 'True' || $_SESSION['RegenesisReorderRVProd_34'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice34'] * $_POST['ItemQty34']) - ($_POST['ItemPrice34'] * $_POST['ItemQty34'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice34'] * $_POST['ItemQty34'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice34'] * $_POST['ItemQty34'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_34'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_34'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_34'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_34'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_34'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_34'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_34'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_34'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_34'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_34'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_34'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_34'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_34'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_34']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle34))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_34'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_34'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_34'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_34'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID35']) && $_POST['ItemID35'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_35'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName35 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_35'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName35 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice35'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty35'];
	if ($ItemFree35 > 0) $strConfirmation .= ' + ' . $ItemFree35 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_35'] == 'True' || $_SESSION['RegenesisReorderRVProd_35'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice35'] * $_POST['ItemQty35']) - ($_POST['ItemPrice35'] * $_POST['ItemQty35'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice35'] * $_POST['ItemQty35'], 2, '.', '') . '</td>';
    }
    */
    
	$strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice35'] * $_POST['ItemQty35'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_35'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_35'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_35'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_35'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_35'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_35'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_35'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_35'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_35'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_35'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_35'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_35'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_35'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_35']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle35))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_35'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_35'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_35'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_35'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID36']) && $_POST['ItemID36'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_36'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName36 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_36'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName36 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice36'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty36'];
	if ($ItemFree36 > 0) $strConfirmation .= ' + ' . $ItemFree36 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_36'] == 'True' || $_SESSION['RegenesisReorderRVProd_36'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice36'] * $_POST['ItemQty36']) - ($_POST['ItemPrice36'] * $_POST['ItemQty36'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice36'] * $_POST['ItemQty36'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice36'] * $_POST['ItemQty36'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_36'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_36'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_36'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_36'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_36'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_36'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_36'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_36'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_36'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_36'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_36'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_36'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_36'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_36']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle36))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_36'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_36'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_36'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_36'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID37']) && $_POST['ItemID37'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_37'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName37 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_37'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName37 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice37'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty37'];
	if ($ItemFree37 > 0) $strConfirmation .= ' + ' . $ItemFree37 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_37'] == 'True' || $_SESSION['RegenesisReorderRVProd_37'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice37'] * $_POST['ItemQty37']) - ($_POST['ItemPrice37'] * $_POST['ItemQty37'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice37'] * $_POST['ItemQty37'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice37'] * $_POST['ItemQty37'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_37'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_37'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_37'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_37'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_37'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_37'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_37'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_37'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_37'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_37'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_37'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_37'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_37'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_37']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle37))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_37'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_37'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_37'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_37'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID38']) && $_POST['ItemID38'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_38'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName38 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_38'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName38 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice38'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty38'];
	if ($ItemFree38 > 0) $strConfirmation .= ' + ' . $ItemFree38 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_38'] == 'True' || $_SESSION['RegenesisReorderRVProd_38'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice38'] * $_POST['ItemQty38']) - ($_POST['ItemPrice38'] * $_POST['ItemQty38'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice38'] * $_POST['ItemQty38'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice38'] * $_POST['ItemQty38'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_38'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_38'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_38'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_38'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_38'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_38'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_38'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_38'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_38'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_38'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_38'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_38'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_38'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_38']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle38))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_38'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_38'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_38'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_38'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID39']) && $_POST['ItemID39'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_39'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName39 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_39'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName39 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice39'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty39'];
	if ($ItemFree39 > 0) $strConfirmation .= ' + ' . $ItemFree39 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_39'] == 'True' || $_SESSION['RegenesisReorderRVProd_39'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice39'] * $_POST['ItemQty39']) - ($_POST['ItemPrice39'] * $_POST['ItemQty39'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice39'] * $_POST['ItemQty39'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice39'] * $_POST['ItemQty39'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_39'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_39'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_39'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_39'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_39'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_39'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_39'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_39'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_39'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_39'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_39'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_39'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_39'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_39']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle39))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_39'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_39'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_39'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_39'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
}

if (isset($_POST['ItemID40']) && $_POST['ItemID40'] != 0)
{
	$strConfirmation .= '<tr>';
    if($_SESSION['SpecDisc_40'] == "True")
    {
	    $strConfirmation .= '<td>' . $strProductName40 . ' -  Item Product Discount - ' . $_SESSION['SpecDisc_40'] . '% </td>';
    }
    else
    {
	    $strConfirmation .= '<td>' . $strProductName40 . '</td>';
    }
	$strConfirmation .= '<td>$' . number_format($_POST['ItemPrice40'], 2, '.', '') . '</td>';
    $strConfirmation .= '<td>' . $_POST['ItemQty40'];
	if ($ItemFree40 > 0) $strConfirmation .= ' + ' . $ItemFree40 . ' FREE';
	$strConfirmation .= '</td>';

    // GMC - 10/12/15 - Regenesis Reorder Discount Program
    // GMC - 03/10/16 - Cancel Regenesis Reorder Discount Program
    /*
    if($_SESSION['RegenesisReorderThreshold'] == 'True' && ($_SESSION['RegenesisReorderRGProd_40'] == 'True' || $_SESSION['RegenesisReorderRVProd_40'] == 'True'))
    {
        $strConfirmation .= '<td style="text-align:right;">$' . number_format(($_POST['ItemPrice40'] * $_POST['ItemQty40']) - ($_POST['ItemPrice40'] * $_POST['ItemQty40'] * $_SESSION['RegenesisReorderDiscount']), 2, '.', '') . '</td>';
    }
    else
    {
	    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice40'] * $_POST['ItemQty40'], 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td style="text-align:right;">$' . number_format($_POST['ItemPrice40'] * $_POST['ItemQty40'], 2, '.', '') . '</td>';
	$strConfirmation .= '</tr>';
	if($_SESSION['BreastCancerAugOct2009_40'] >= 12)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_40'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['BreastCancerAugOct2009_40'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_40'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009_40'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}
	if($_SESSION['ValentinesDay2010_40'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Candle Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
        else
        {
	        $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
            $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Compact Mirror Gift</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
            $strConfirmation .=  '<tr>';
	        $strConfirmation .=  '<td>Valentine Day Promotion Bag</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .=  '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
	        $strConfirmation .=  '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .=  '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_40'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitalash&reg; Hair</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        $strConfirmation .= '<tr>';
        $strConfirmation .= '<td>Revitabrow</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
        else
        {
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Raven</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
            $strConfirmation .= '<td>Mascara Espresso</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_40'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_40'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_40'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
        else
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_40'] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
            $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>Mascara FR-Canadian</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $_SESSION['MothersDay2010_40'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        $strConfirmation .= '</tr>';
        }
    }
    if($_SESSION['Bundles2010_40'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_40']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle40))
        {
	        $strConfirmation .= '<tr>';
	        $strConfirmation .= '<td>' . $rowGetBundle['Description'] . '</td>';
	        $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
            $strConfirmation .= '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            $strConfirmation .= '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            $strConfirmation .= '</tr>';
        }
    }

	// GMC - 06/23/15 - Regenesis - Promotion 3 + 1
	if($_SESSION['Regenesis_2286_40'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Conditioner</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2286_40'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
	}

	if($_SESSION['Regenesis_2285_40'] > 0)
	{
	    $strConfirmation .= '<tr>';
	    $strConfirmation .= '<td>ReGenesis Back Bar Shampoo</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
        $strConfirmation .= '<td>' . $_SESSION['Regenesis_2285_40'] . '</td>';
	    $strConfirmation .= '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    $strConfirmation .= '</tr>';
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
// GMC - 07/09/13 - Revert back to Revitalash from BlueSkiesDist on Confirmation Email
// GMC - 04/09/15 - Implement UNWASH and Revitalash options for Mail Subject
// $mailsubject = 'BlueSkies Distribution Order Confirmation';
// $mailsubject = 'Revitalash Order Confirmation';
if($_SESSION['UserTypeID'] == 4)
{
    $mailsubject = 'UNWASH Order Confirmation';
}
else
{
    $mailsubject = 'Revitalash Order Confirmation';

}

$mailheader = 'MIME-Version: 1.0' . "\r\n";
$mailheader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// GMC - 09/29/12 - Changes from Revitalash to BlueSkiesDist on Confirmation Email
// GMC - 07/09/13 - Revert back to Revitalash from BlueSkiesDist on Confirmation Email

// $mailheader .= "From:" . 'sales@revitalash.com' . "\r\n";
$mailheader .= "From:" . 'services@revitalash.com' . "\r\n";

// $mailheader .= "From:" . 'sales@blueskiesdist.com' . "\r\n";

//$mailheader .= 'Bcc: jstancarone@revitalash.com' . "\r\n";
// GMC - 07/29/09 - Add Linda Peterson to list by JS

// GMC - 10/02/09 - To include Information for EU Orders
// GMC - 10/07/09 - Add email address to EU Orders (Holland@revitalash.com)
// GMC - 03/18/10 - Add 10 Line Items Admin
// GMC - 07/20/10 - Add ar@revitalash.com at EU orders
// GMC - 062613 - Clean Up BCC Email depending of the Domestic-International and UserID and Add Jerry Rudolph
/*
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
*/

// Most of these are related to Dariel Sidney User ID
if ($_SESSION['UserID'] == 100 || $_SESSION['UserID'] == 128 || $_SESSION['UserID'] == 113 || $_SESSION['UserID'] == 99 || $_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 44)
{
    if($_SESSION['OrderTotal'] >= 25000)
    {
        // GMC - 07/18/13 - Take out Gayle B from email confirmations
        // GMC - 07/31/13 - Add mbell@revitalash.com like Jerry Rudolph
        // GMC - 04/03/15 - Take Jerry Rudolph from Domestic and Add Cory Forsstrom
        // GMC - 04/30/15 - Add derekh@athenacosmetics.com
        // GMC - 06/24/15 - Add lvoc@athenacosmetics.com
        // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,dsidney@revitalash.com,Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,mbell@revitalash.com, dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com, dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com, dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        $mailheader .= 'Bcc: jrudolph@athenacosmetics.com, lvoc@athenacosmetics.com, derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com, dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
    }
    else
    {
        // GMC - 07/18/13 - Take out Gayle B from email confirmations
        // GMC - 04/30/15 - Add derekh@athenacosmetics.com
        // GMC - 06/24/15 - Add lvoc@athenacosmetics.com
        // $mailheader .= 'Bcc: dsidney@revitalash.com,Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        // $mailheader .= 'Bcc: dsidney@revitalash.com,derekh@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
        $mailheader .= 'Bcc: dsidney@revitalash.com, lvoc@athenacosmetics.com, derekh@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
    }
}
else
{
    // We need to check if International or Domestic
    if ($blnIsInternational == 0)
    {
       // GMC - 01/25/16 - Add Jerry Rudolph on greater than 12K on Domestic Orders Confirmation
       if($_SESSION['OrderTotal'] >= 12000)
       {
           // GMC - 07/18/13 - Take out Gayle B from email confirmations
           // GMC - 07/31/13 - Add mbell@revitalash.com like Jerry Rudolph
           // GMC - 12/14/13 - Add jmartone@suitablegroup.com for Magento Orders
           // GMC - 01/29/14 - Cancel jmartone@suitablegroup.com for Magento Orders
           /*
           if($_SESSION['MagentoFlag'] == 'True')
           {
               $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,jmartone@suitablegroup.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           else
           {
               // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           */

           // GMC - 11/18/14 - Include Michele Sellick Order Confirmation
           // GMC - 04/03/15 - Take Jerry Rudolph from Domestic and Add Cory Forsstrom
           // GMC - 04/30/15 - Add derekh@athenacosmetics.com
           // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: msellick@revitalash.com,jrudolph@athenacosmetics.com,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: msellick@revitalash.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // GMC - 05/14/15 - Add Don Stewart for Unwash
           // GMC - 06/24/15 - Add lvoc@athenacosmetics.com
           if($_SESSION['UserTypeID'] == 4)
           {
               // $mailheader .= 'Bcc: msellick@revitalash.com,don@unwash.com,derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               // $mailheader .= 'Bcc: msellick@revitalash.com, lvoc@athenacosmetics.com, don@unwash.com,derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com' . "\r\n";
           }
           else
           {
               // $mailheader .= 'Bcc: msellick@revitalash.com,derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               // $mailheader .= 'Bcc: msellick@revitalash.com, lvoc@athenacosmetics.com, derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: jrudolph@athenacosmetics.com, dhooper@athenacosmetics.com,revitalash1@gmail.com' . "\r\n";
           }
       }
       else if($_SESSION['OrderTotal'] >= 2000)
       {
           // GMC - 07/18/13 - Take out Gayle B from email confirmations
           // GMC - 07/31/13 - Add mbell@revitalash.com like Jerry Rudolph
           // GMC - 12/14/13 - Add jmartone@suitablegroup.com for Magento Orders
           // GMC - 01/29/14 - Cancel jmartone@suitablegroup.com for Magento Orders
           /*
           if($_SESSION['MagentoFlag'] == 'True')
           {
               $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,jmartone@suitablegroup.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           else
           {
               // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           */

           // GMC - 11/18/14 - Include Michele Sellick Order Confirmation
           // GMC - 04/03/15 - Take Jerry Rudolph from Domestic and Add Cory Forsstrom
           // GMC - 04/30/15 - Add derekh@athenacosmetics.com
           // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: msellick@revitalash.com,jrudolph@athenacosmetics.com,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: msellick@revitalash.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // GMC - 05/14/15 - Add Don Stewart for Unwash
           // GMC - 06/24/15 - Add lvoc@athenacosmetics.com
           if($_SESSION['UserTypeID'] == 4)
           {
               // $mailheader .= 'Bcc: msellick@revitalash.com,don@unwash.com,derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: msellick@revitalash.com, lvoc@athenacosmetics.com, don@unwash.com,derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           else
           {
               // $mailheader .= 'Bcc: msellick@revitalash.com,derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: msellick@revitalash.com, lvoc@athenacosmetics.com, derekh@athenacosmetics.com,CForsstrom@ATHENACOSMETICS.COM,mbell@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
       }
       else
       {
           // GMC - 07/18/13 - Take out Gayle B from email confirmations
           // GMC - 12/14/13 - Add jmartone@suitablegroup.com for Magento Orders
           // GMC - 01/29/14 - Cancel jmartone@suitablegroup.com for Magento Orders
           /*
           if($_SESSION['MagentoFlag'] == 'True')
           {
               $mailheader .= 'Bcc: dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,jmartone@suitablegroup.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           else
           {
               // $mailheader .= 'Bcc: Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           */

           // GMC -11/18/14 - Include Michele Sellick Order Confirmation
           // GMC - 04/03/15 - Take Jerry Rudolph from Domestic and Add Cory Forsstrom
           // GMC - 04/30/15 - Add derekh@athenacosmetics.com
           // $mailheader .= 'Bcc: dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: msellick@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: msellick@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";

           // GMC - 05/14/15 - Add Don Stewart for Unwash
           // GMC - 06/24/15 - Add lvoc@athenacosmetics.com
           if($_SESSION['UserTypeID'] == 4)
           {
               // $mailheader .= 'Bcc: msellick@revitalash.com,don@unwash.com,derekh@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: msellick@revitalash.com, lvoc@athenacosmetics.com, don@unwash.com,derekh@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           else
           {
               // $mailheader .= 'Bcc: msellick@revitalash.com,derekh@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: msellick@revitalash.com, lvoc@athenacosmetics.com, derekh@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
       }
    }
    else
    {
       if($_SESSION['OrderTotal'] >= 25000)
       {
           // GMC - 07/18/13 - Take out Gayle B from email confirmations
           // GMC - 07/31/13 - Add mbell@revitalash.com like Jerry Rudolph
           // GMC - 04/03/15 - Take Jerry Rudolph from Domestic and Add Cory Forsstrom
           // GMC - 04/30/15 - Add derekh@athenacosmetics.com
           // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,dsidney@revitalash.com,Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,mbell@revitalash.com,dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,mbell@revitalash.com,CForsstrom@ATHENACOSMETICS.COM,dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";

           // GMC - 05/14/15 - Add Don Stewart for Unwash
           // GMC - 06/24/15 - Add lvoc@athenacosmetics.com
           if($_SESSION['UserTypeID'] == 4)
           {
               // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,don@unwash.com,derekh@athenacosmetics.com,mbell@revitalash.com,CForsstrom@ATHENACOSMETICS.COM,dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: jrudolph@athenacosmetics.com, lvoc@athenacosmetics.com, don@unwash.com,derekh@athenacosmetics.com,mbell@revitalash.com,CForsstrom@ATHENACOSMETICS.COM,dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           else
           {
               // $mailheader .= 'Bcc: jrudolph@athenacosmetics.com,derekh@athenacosmetics.com,mbell@revitalash.com,CForsstrom@ATHENACOSMETICS.COM,dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: jrudolph@athenacosmetics.com, lvoc@athenacosmetics.com, derekh@athenacosmetics.com,mbell@revitalash.com,CForsstrom@ATHENACOSMETICS.COM,dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
       }
       else
       {
           // GMC - 07/18/13 - Take out Gayle B from email confirmations
           // GMC - 04/03/15 - Take Jerry Rudolph from Domestic and Add Cory Forsstrom
           // GMC - 04/30/15 - Add derekh@athenacosmetics.com
           // $mailheader .= 'Bcc: dsidney@revitalash.com,Gayleyy@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // $mailheader .= 'Bcc: dsidney@revitalash.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           // GMC - 05/14/15 - Add Don Stewart for Unwash
           // GMC - 06/24/15 - Add lvoc@athenacosmetics.com
           if($_SESSION['UserTypeID'] == 4)
           {
               // $mailheader .= 'Bcc: dsidney@revitalash.com,don@unwash.com,derekh@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: dsidney@revitalash.com,don@unwash.com, lvoc@athenacosmetics.com, derekh@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
           else
           {
               // $mailheader .= 'Bcc: dsidney@revitalash.com,derekh@athenacosmetics.com,dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
               $mailheader .= 'Bcc: dsidney@revitalash.com,derekh@athenacosmetics.com, lvoc@athenacosmetics.com, dhooper@athenacosmetics.com,revitalash1@gmail.com,gmarrufo@unimerch.com,jstancarone@athenacosmetics.com,' . $_SESSION['SalesRepIdEmailAddress'] . "\r\n";
           }
       }
    }

    // GMC - 11/05/15 - Add Tina Jimenez for Terms Orders
    if ($_SESSION['PaymentType'] == 'Terms')
    {
        $mailheader = str_replace("jstancarone@athenacosmetics.com","tjimenez@athenacosmetics.com",$mailheader);
    }

}

// mail($mailrecepient, $mailsubject, $strConfirmationEmail, $mailheader);
}

// RESET SESSION VARIABLES
foreach ($_SESSION as $key => $value)
{
	if ($key != 'IsRevitalashLoggedIn' && $key != 'UserID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress' && $key != 'UserTypeID')	
		unset($_SESSION[$key]);
}

?>
