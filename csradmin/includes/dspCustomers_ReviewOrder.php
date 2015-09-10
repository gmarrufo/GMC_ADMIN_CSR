<?php

// GMC - 03/26/12 - MediaKit Process
if($_SESSION['OrderType'] == 'MediaKit')
{
    echo '<h1>New Order (Media Kit or Media Kit Thank You)</h1>';
}
else
{
    // GMC - 09/29/14 - UnWash in Admin
    if($_SESSION['UserTypeID'] == 4)
    {
        echo '<h1>New Order (UnWash)</h1>';
    }
    else
    {
        echo '<h1>New Order (Standard)</h1>';
    }
}

?>

<table width="900" cellpadding="2" cellspacing="0" style="margin:10px;">

<?php

// GMC - 08/17/10 - FedEx Box Project
require_once('../library/fedex-common.php5');
$path_to_wsdl = "../wsdl/RateService_v8.wsdl";

while($rowGetCustomer = mssql_fetch_array($qryGetCustomer))
{
    $intCustomerType = $rowGetCustomer["CustomerTypeID"];
	
	if ($rowGetCustomer["CountryCode"] == 'US')
		$blnIsInternational = 0;
	else
		$blnIsInternational = 1;
	
	echo '<tr>
        <th width="140" style="text-align:left;">Name:</th>
        <td width="*">' . $rowGetCustomer["FirstName"] . ' ' . $rowGetCustomer["LastName"] . '</td>
    </tr>
    
    <tr>
        <th style="text-align:left;">Company:</th>
        <td>' . $rowGetCustomer["CompanyName"] . '</td>
    </tr>
	
	<tr>
        <th style="text-align:left;">Country:</th>
        <td>' . $rowGetCustomer["CountryCode"] . '</td>
    </tr>';
	
    // GMC - 07/14/11 - Distributors Change CSRADMIN
	if ($intCustomerType == 1)
    {
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Consumer</td></tr>';
    }
	elseif ($intCustomerType == 2)
    {
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Spa/Reseller</td></tr>';
		echo '<tr><th style="text-align:left;">Reseller/VAT ID:</th><td>' . $rowGetCustomer["ResellerNumber"] . '</td></tr>';
    }
	elseif ($intCustomerType == 3)
    {
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Distributor</td></tr>';
    }
		
    // GMC - 03/08/09 - Customer Type "REP"
	elseif ($intCustomerType == 4)
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Rep</td></tr>';

    // GMC - 12/04/12 - Change Item 700 for 853 - 854 or 855
    $_SESSION['CustomerTypeID'] =    $intCustomerType;
}

// CONNECT TO SQL SERVER DATABASE
$connItems = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connItems);

// EXECUTE SQL QUERY FOR CART ITEM DETAIL
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

// GET SHIPPING METHOD
$qryShippingMethod = mssql_init("spConstants_GetShippingMethod", $connItems);
mssql_bind($qryShippingMethod, "@prmShipMethodID", $_SESSION['ShippingMethod'], SQLINT4);
mssql_bind($qryShippingMethod, "@prmCustomerShipToID", $_SESSION['CustomerShipToID'], SQLINT4);
$rsShippingMethod = mssql_execute($qryShippingMethod);

// GET ORDER SHIP TO INFORMATION
$qryGetOrderShipTo = mssql_query("SELECT * FROM tblCustomers_ShipTo WHERE RecordID = " . $_SESSION['CustomerShipToID']);
while($rowGetShipTo = mssql_fetch_array($qryGetOrderShipTo))
{
	$Address1 = $rowGetShipTo['Address1'];
	$Address2 = $rowGetShipTo['Address2'];
	$AddressCity = $rowGetShipTo['City'];
	$AddressState = $rowGetShipTo['State'];
	$AddressPostalCode = $rowGetShipTo['PostalCode'];
	$AddressCountryCode = $rowGetShipTo['CountryCode'];
}

// CLOSE DATABASE CONNECTION
mssql_close($connItems);

// GMC - 02/22/09 - WillCallInt - Set Session to blank
$_SESSION['WillCallInt'] = '';

// GMC - 04/02/09 - Activate Fedex Web Services (Domestic - International - Exclude Netherlands)
$blnIsError = 0;
$ResultCode = 0;

// GMC - 08/24/09 - Calculate when Weight is more than 150 lbs
$origWeight = $_SESSION['OrderWeight'];
$baseWeight = 150;
$repeatWeight = 35;
$remainWeight = 0;
$numberCharges = 0;

// GMC - 08/03/09 - Add the standard distribution sites by JS
// GMC - 05/06/09 - FedEx Netherlands
// GMC - 03/18/10 - Add 10 Line Items Admin
if($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH' || $_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH')
{
  if(($_SESSION['CustomerTypeIDFedExEu'] == 1 || $_SESSION['CustomerTypeIDFedExEu'] == 2 || $_SESSION['CustomerTypeIDFedExEu'] == 3 || $_SESSION['CustomerTypeIDFedExEu'] == 4) && $_SESSION['CountryCodeFedExEu'] != '')
  {
    // Find which FedEx EU Charge
    if($_POST['ShipMethodID'] == 208)
    {
        $ResultCode = $_SESSION['IntraEuroGroundCharge'];
    }
    else if($_POST['ShipMethodID'] == 209)
    {
        $ResultCode = $_SESSION['IntraEuroStandardCharge'];
    }
    else if($_POST['ShipMethodID'] == 210)
    {
        $ResultCode = $_SESSION['IntraEuroExpressCharge'];
    }
  }
}
else
{
// GMC - 08/24/09 - Calculate when Weight is more than 150 lbs
if(((int) $_SESSION['OrderWeight']) <= 0)
{
    $weight = "1";
}
else
{
    $weight = $_SESSION['OrderWeight'];

    if($origWeight > $baseWeight)
    {
        //Calculate remaining weight
        $remainWeight = $origWeight - $baseWeight;

        // Calculate number of times for repetitive weight (35 lbs)
        $numberCharges = round(($remainWeight/$repeatWeight), 2);
    }
}

// GET RATE QUOTE
while($row = mssql_fetch_array($rsShippingMethod))
{
	$strShippingMethod = $row["ShippingMethodDisplay"];

	if ($row["Carrier"] == 'UPS')
	// UPS RATE QUOTES
	{		
		$blnIsError = 0;

        // GMC - 08/24/09 - Calculate when Weight is more than 150 lbs
        if($numberCharges > 0)
        {
            // Submit the base weight first (150)

             // GMC - 02/24/10 - Change Origin Shipping Address by JS
             /*
            $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
			"10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."90014","19_destPostal=".$row["ShipToZIP"],
			"22_destCountry="."US","23_weight=".$baseWeight,"47_rateChart="."Regular+Daily+Pickup","48_container="."00","49_residential="."01"));
            */

            // GMC - 01/07/11 - UPS Rate and Service DataStream API - Change
            /*
            $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
			"10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."89074","19_destPostal=".$row["ShipToZIP"],
			"22_destCountry="."US","23_weight=".$baseWeight,"47_rateChart="."Regular+Daily+Pickup","48_container="."00","49_residential="."01"));
            */

            $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
			"10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."89074","19_destPostal=".$row["ShipToZIP"],
			"22_destCountry="."US","23_weight=".$baseWeight,"47_rateChart="."Letter+Center","48_container="."00","49_residential="."01"));

			$strResponse = fopen($urlUPS, "r");

			while(!feof($strResponse))
			{
			    $Result = fgets($strResponse, 500);
			    $Result = explode("%", $Result);
			    $Err = substr($Result[0], -1);

			    switch($Err)
			    {
				    case 3: $ResultCode1 = $Result[8]; break;
				    case 4: $ResultCode1 = $Result[8]; break;
				    case 5: $ResultCode1 = $Result[1]; $blnIsError = 1; break;
				    case 6: $ResultCode1 = $Result[1]; $blnIsError = 1; break;
			    }
			}

			fclose($strResponse);

			if(!$ResultCode1)
			{
			     $blnIsError = 1;
				 $ResultCode1 = "An error occured.";
			}

            // Now we submit the repetitive weight $repeatWeight
            // GMC - 02/24/10 - Change Origin Shipping Address by JS
            /*
            $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
			"10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."90014","19_destPostal=".$row["ShipToZIP"],
			"22_destCountry="."US","23_weight=".$repeatWeight,"47_rateChart="."Regular+Daily+Pickup","48_container="."00","49_residential="."01"));
            */

            // GMC - 01/07/11 - UPS Rate and Service DataStream API - Change
            /*
            $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
			"10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."89074","19_destPostal=".$row["ShipToZIP"],
			"22_destCountry="."US","23_weight=".$repeatWeight,"47_rateChart="."Regular+Daily+Pickup","48_container="."00","49_residential="."01"));
            */
            
            $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
			"10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."89074","19_destPostal=".$row["ShipToZIP"],
			"22_destCountry="."US","23_weight=".$repeatWeight,"47_rateChart="."Letter+Center","48_container="."00","49_residential="."01"));

			$strResponse = fopen($urlUPS, "r");

			while(!feof($strResponse))
			{
			    $Result = fgets($strResponse, 500);
			    $Result = explode("%", $Result);
			    $Err = substr($Result[0], -1);

			    switch($Err)
			    {
				    case 3: $ResultCode2 = $Result[8]; break;
				    case 4: $ResultCode2 = $Result[8]; break;
				    case 5: $ResultCode2 = $Result[1]; $blnIsError = 1; break;
				    case 6: $ResultCode2 = $Result[1]; $blnIsError = 1; break;
			    }
			}

			fclose($strResponse);

			if(!$ResultCode2)
			{
			    $blnIsError = 1;
				$ResultCode2 = "An error occured.";
			}

             // Now we join all weight charges
            $ResultCode = $ResultCode1 + ($ResultCode2 * $numberCharges);
        }
        else
        {
             // GMC - 02/24/10 - Change Origin Shipping Address by JS
             /*
             $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
		     "10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."90014","19_destPostal=".$row["ShipToZIP"],
		     "22_destCountry="."US","23_weight=".$_SESSION['OrderWeight'],"47_rateChart="."Regular+Daily+Pickup","48_container="."00","49_residential="."01"));
             */

             // GMC - 01/07/11 - UPS Rate and Service DataStream API - Change
             /*
             $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
		     "10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."89074","19_destPostal=".$row["ShipToZIP"],
		     "22_destCountry="."US","23_weight=".$_SESSION['OrderWeight'],"47_rateChart="."Regular+Daily+Pickup","48_container="."00","49_residential="."01"));
             */

             $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
		     "10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."89074","19_destPostal=".$row["ShipToZIP"],
		     "22_destCountry="."US","23_weight=".$_SESSION['OrderWeight'],"47_rateChart="."Letter+Center","48_container="."00","49_residential="."01"));

		     $strResponse = fopen($urlUPS, "r");

		     while(!feof($strResponse))
		     {
		         $Result = fgets($strResponse, 500);
		         $Result = explode("%", $Result);
		         $Err = substr($Result[0], -1);

		         switch($Err)
		         {
			         case 3: $ResultCode = $Result[8]; break;
			         case 4: $ResultCode = $Result[8]; break;
			         case 5: $ResultCode = $Result[1]; break;
			         case 6: $ResultCode = $Result[1]; break;
                 }
             }

		     fclose($strResponse);

		     if(!$ResultCode)
		     {
			     $ResultCode = 0;
		     }
        }
	}
	elseif ($row["Carrier"] == 'FedEx')
	// FEDEX RATE QUOTES
	{
		$blnIsError = 0;

            // GMC - 04/02/09 - Activate Fedex Web Services (Domestic - International - Exclude Netherlands)
            // GMC - 08/17/10 - FedEx Box Project
            // require_once('../library/fedex-common.php');

            //The WSDL is not included with the sample code.
            //Please include and reference in $path_to_wsdl variable.
            // GMC - 08/17/10 - FedEx Box Project
            $path_to_wsdl = "../wsdl/RateService_v8.wsdl";

            ini_set("soap.wsdl_cache_enabled", "0");

            $client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

            $request['WebAuthenticationDetail'] = array('UserCredential' =>

            // array('Key' => 'XXX', 'Password' => 'YYY')); // Replace 'XXX' and 'YYY' with FedEx provided credentials
            array('Key' => 'hRnybIZX3PKne28q', 'Password' => 'yDjVeSkK252f1kFblX1AXy31b')); // Replace 'XXX' and 'YYY' with FedEx provided credentials

            // $request['ClientDetail'] = array('AccountNumber' => 'XXX', 'MeterNumber' => 'XXX');// Replace 'XXX' with your account and meter number
            $request['ClientDetail'] = array('AccountNumber' => '462227000', 'MeterNumber' => '100677102');// Replace 'XXX' with your account and meter number

            // GMC - 08/17/10 - FedEx Box Project
            /*
            $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Available Services Request v4 using PHP ***');

            // $request['Version'] = array('ServiceId' => 'crs', 'Major' => '4', 'Intermediate' => '0', Minor => '0');
            $request['Version'] = array('ServiceId' => 'crs', 'Major' => '5', 'Intermediate' => '0', 'Minor' => '0');
            */
            
            $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Available Services Request v8 using PHP ***');
            $request['Version'] = array('ServiceId' => 'crs', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
            $request['ReturnTransitAndCommit'] = true;

            $request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
            $request['RequestedShipment']['ShipTimestamp'] = date('c');

            // Service Type and Packaging Type are not passed in the request
            // GMC - 02/24/10 - Change Origin Shipping Address by JS
            /*
            $request['RequestedShipment']['Shipper'] = array('Address' => array(
                                          'StreetLines' => array('11065 SW 11th St # 300'), // Origin details
                                          'City' => 'Beaverton',
                                          'StateOrProvinceCode' => 'OR',
                                          'PostalCode' => '97005',
                                          'CountryCode' => 'US'));
            */
            $request['RequestedShipment']['Shipper'] = array('Address' => array(
                                          'StreetLines' => array('701 N. Green Valley Parkway St # 200'), // Origin details
                                          'City' => 'Henderson',
                                          'StateOrProvinceCode' => 'NV',
                                          'PostalCode' => '89074',
                                          'CountryCode' => 'US'));

            $request['RequestedShipment']['Recipient'] = array('Address' => array (
                                          'StreetLines' => array('123 Main St'), // Destination details
                                          'City' => $row["City"],
                                          'StateOrProvinceCode' => $row["State"],
                                          'PostalCode' => $row["ShipToZIP"],
                                          'CountryCode' => $row["ShipToCountry"]));
            $request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
                                          'Payor' => array('AccountNumber' => '462227000', // Replace "XXX" with payor's account number
                                          'CountryCode' => 'US'));
            $request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT';
            // $request['RequestedShipment']['RateRequestTypes'] = 'LIST';

            // $request['RequestedShipment']['PackageCount'] = '1';
            $request['RequestedShipment']['PackageCount'] = $_SESSION['TotalBoxCount'];

            $request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';

            // GMC - 08/17/10 - FedEx Box Project
            /*
            $request['RequestedShipment']['RequestedPackages'] = array('0' => array('SequenceNumber' => '1',
                                          'InsuredValue' => array('Amount' => 0.0,
                                          'Currency' => 'USD'),
                                          'ItemDescription' => 'Athena Products',

                                          // GMC - 04/06/10 - Add 1.2 lb to weight
                                          // 'Weight' => array('Value' => $weight,
                                          'Weight' => array('Value' => $weight + 1.2,

                                          'Units' => 'LB'),
                                          'Dimensions' => array('Length' => 5,
                                          'Width' => 1,
                                          'Height' => 1,
                                          'Units' => 'IN'),
                                          'CustomerReferences' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE',
                                          'Value' => 'Undergraduate application')));
            */
            
            /*
             $request['RequestedShipment']['RequestedPackageLineItems'] = array('0' => array('Weight' => array('Value' => $weight + 1.2,
                                                                                    'Units' => 'LB'),
                                                                                    'Dimensions' => array('Length' => 5,
                                                                                    'Width' => 1,
                                                                                    'Height' => 1,
                                                                                    'Units' => 'IN')));
            */
            
            // GMC - 04/25/11 - Calculate Boxes for FedEx - From Prototype To Production
            // GMC - 10/18/10 - Correct the Fedex Web Service Calculation
            // $request['RequestedShipment']['RequestedPackageLineItems'] = $_SESSION['Array_Boxes'];
            /*
            $request['RequestedShipment']['RequestedPackageLineItems'] = array('0' => array('Weight' => array('Value' => $_SESSION['OrderWeight'] + 1.2,
                                                                                    'Units' => 'LB'),
                                                                                    'Dimensions' => array('Length' => 12,
                                                                                    'Width' => 12,
                                                                                    'Height' => 12,
                                                                                    'Units' => 'IN')));
            */
            $request['RequestedShipment']['RequestedPackageLineItems'] = $_SESSION['Array_Boxes'];

             // 08/12/15 - Separate FedEx Service Call based on Number of Boxes
             /*
            try
            {
                 $response = $client -> getRates($request);

                 // print_r($response);
                 // echo '<br/>';
                 // echo '<br/>';
                 // var_dump($response);

                 if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
                 {
                      foreach ($response -> RateReplyDetails as $rateReply)
                      {
                          $serviceType = $rateReply -> ServiceType;

                          // GMC - 04/14/10 - Add FedEx Ground
                          if($serviceType == "FEDEX_GROUND")
                          {
                              if($strShippingMethod == "FedEx Ground")
                              {
                                  if(is_array($response -> RateReplyDetails))
                                  {
                                      // GMC - 04/25/11 - Calculate Boxes for FedEx - From Prototype To Production
                                      // $ResultCode1 = $rateReply -> RatedShipmentDetails -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                      // GMC - 05/11/15 - Integrate CAP Products into Admin
                                      if($_SESSION['CapFlag'] && $_SESSION['CapRateReal'] != 0.00)
                                      {
                                          $ResultCode1 = 50.00;
                                      }
                                      else
                                      {

                                          if($_SESSION['TotalBoxCount'] <= 4)
                                          {
                                              $ResultCode1 = $rateReply -> RatedShipmentDetails -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                          }
                                          else
                                          {
                                              $tnfec = $rateReply -> RatedShipmentDetails;
                                              foreach ($tnfec as $wha)
                                              {
                                                  $ResultCode1 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                              }
                                          }
                                      }
                                  }
                                  $blnIsError = 0;
                              }
                          }

                          elseif($serviceType == "FEDEX_2_DAY")
                          {
                              if($strShippingMethod == "FedEx 2Day")
                              {
                                  if(is_array($response -> RateReplyDetails))
                                  {
                                      $tnfec = $rateReply -> RatedShipmentDetails;
                                      foreach ($tnfec as $wha)
                                      {
                                          $ResultCode2 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                      }
                                  }
                                  $blnIsError = 0;
                              }
                          }
                          elseif($serviceType == "FEDEX_EXPRESS_SAVER")
                          {
                              if($strShippingMethod == "FedEx Express Saver")
                              {
                                  if(is_array($response -> RateReplyDetails))
                                  {
                                      $tnfec = $rateReply -> RatedShipmentDetails;
                                      foreach ($tnfec as $wha)
                                      {
                                          $ResultCode3 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                      }
                                   }
                                   $blnIsError = 0;
                              }
                          }

                          // GMC - 08/17/10 - FedEx Box Project
                          elseif($serviceType == "FEDEX_1_DAY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode4 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $blnIsError = 0;
                          }

                          elseif($serviceType == "FEDEX_2_DAY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode5 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "FEDEX_3_DAY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode6 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $blnIsError = 0;
                          }

                          elseif($serviceType == "INTERNATIONAL_PRIORITY")
                          {
                              if($strShippingMethod == "FedEx International Priority")
                              {
                                   if(is_array($response -> RateReplyDetails))
                                   {
                                       $tnfec = $rateReply -> RatedShipmentDetails;
                                       foreach ($tnfec as $wha)
                                       {
                                           $ResultCode7 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                       }
                                   }
                                   $blnIsError = 0;
                              }
                          }
                          elseif($serviceType == "INTERNATIONAL_ECONOMY")
                          {
                              if($strShippingMethod == "FedEx International Economy")
                              {
                                  if(is_array($response -> RateReplyDetails))
                                  {
                                      $tnfec = $rateReply -> RatedShipmentDetails;
                                      foreach ($tnfec as $wha)
                                      {
                                          $ResultCode8 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge->Amount;
                                      }
                                  }
                                  $blnIsError = 0;
                              }
                          }

                          // GMC - 08/17/10 - FedEx Box Project
                          elseif($serviceType == "INTERNATIONAL_PRIORITY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode9 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge->Amount;
                                  }
                              }
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "INTERNATIONAL_ECONOMY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode10 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $blnIsError = 0;
                          }

                          elseif($serviceType == "INTERNATIONAL_FIRST")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode11 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $blnIsError = 0;
                          }
                          
                          // GMC - 03/11/11 - Add Standard Overnight to Shipping Methods
                          elseif($serviceType == "STANDARD_OVERNIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode12 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $blnIsError = 0;
                          }

                      }
                 }
                 else
                 {
                      foreach ($response -> Notifications as $notification)
                      {
                          if(is_array($response -> Notifications))
                          {
                              $blnIsError = 1;
                          }
                          else
                          {
                              $blnIsError = 1;
                          }
                      }
                 }
            }
            catch (SoapFault $exception)
            {
                $blnIsError = 1;
            }

            if($_POST['ShipMethodID'] == 199)
            {
                $ResultCode = $ResultCode1;
            }
            elseif($_POST['ShipMethodID'] == 201)
            {
                $ResultCode = $ResultCode2;
            }
            else if($_POST['ShipMethodID'] == 200)
            {
                $ResultCode = $ResultCode3;
            }
            else if($_POST['ShipMethodID'] == 211)
            {
                $ResultCode = $ResultCode4;
            }
            else if($_POST['ShipMethodID'] == 212)
            {
                $ResultCode = $ResultCode5;
            }
            else if($_POST['ShipMethodID'] == 213)
            {
                $ResultCode = $ResultCode6;
            }
            else if($_POST['ShipMethodID'] == 205)
            {
                $ResultCode = $ResultCode7;
            }
            else if($_POST['ShipMethodID'] == 206)
            {
                $ResultCode = $ResultCode8;
            }
            else if($_POST['ShipMethodID'] == 214)
            {
                $ResultCode = $ResultCode9;
            }
            else if($_POST['ShipMethodID'] == 215)
            {
                $ResultCode = $ResultCode10;
            }
            else if($_POST['ShipMethodID'] == 207)
            {
                $ResultCode = $ResultCode11;
            }

            // GMC - 03/11/11 - Add Standard Overnight to Shipping Methods
            else if($_POST['ShipMethodID'] == 202)
            {
                $ResultCode = $ResultCode12;
            }

        /*
        }
        */

            if($_POST['ShipMethodID'] == 199)
            {
                $ResultCode = $_SESSION['RC1'];
            }
            elseif($_POST['ShipMethodID'] == 201)
            {
                $ResultCode = $_SESSION['RC2'];
            }
            else if($_POST['ShipMethodID'] == 200)
            {
                $ResultCode = $_SESSION['RC3'];
            }
            else if($_POST['ShipMethodID'] == 211)
            {
                $ResultCode = $_SESSION['RC5'];
            }
            else if($_POST['ShipMethodID'] == 212)
            {
                $ResultCode = $_SESSION['RC6'];
            }
            else if($_POST['ShipMethodID'] == 213)
            {
                $ResultCode = $_SESSION['RC7'];
            }
            else if($_POST['ShipMethodID'] == 205)
            {
                $ResultCode = $_SESSION['RC8'];
            }
            else if($_POST['ShipMethodID'] == 206)
            {
                $ResultCode = $_SESSION['RC9'];
            }
            else if($_POST['ShipMethodID'] == 214)
            {
                $ResultCode = $_SESSION['RC10'];
            }
            else if($_POST['ShipMethodID'] == 215)
            {
                $ResultCode = $_SESSION['RC11'];
            }
            else if($_POST['ShipMethodID'] == 207)
            {
                $ResultCode = $_SESSION['RC12'];
            }

            // GMC - 03/11/11 - Add Standard Overnight to Shipping Methods
            else if($_POST['ShipMethodID'] == 202)
            {
                $ResultCode =  $_SESSION['RC4'];
            }

	}
	elseif ($row["Carrier"] == 'USPS')
	// USPS RATE QUOTE
	{
		$blnIsError = 0;
		
         /*
		require_once("../modules/xmlparser.php");
		
		// may need to urlencode xml portion
		//$str2 = "http://testing.shippingapis.com/ShippingAPITest.dll" . "?API=RateV2&XML=<RateV2Request%20USERID=\"";
		$str2 = "http://Production.ShippingAPIs.com/ShippingAPI.dll" . "?API=RateV2&XML=<RateV2Request%20USERID=\"";
		$str2 .= "004GEEKT1462" . "\"%20PASSWORD=\"" . "931HN41XW201" . "\"><Package%20ID=\"0\"><Service>";
		$str2 .= "All" . "</Service><ZipOrigination>" . "90014" . "</ZipOrigination>";
			$str2 .= "<ZipDestination>" . $row["ShipToZIP"] . "</ZipDestination>";
		$str2 .= "<Pounds>" . floor($_SESSION['OrderWeight']) . "</Pounds><Ounces>" . ceil(($_SESSION['OrderWeight'] - floor($_SESSION['OrderWeight'])) * 16) . "</Ounces>";
		$str2 .= "<Container>VARIABLE</Container><Size>REGULAR</Size>";
		$str2 .= "<Machinable>true</Machinable></Package></RateV2Request>";
		
		$ch2 = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch2, CURLOPT_URL, $str2);
		curl_setopt($ch2, CURLOPT_HEADER, 0);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

		// grab URL and pass it to the browser
		$data2 = curl_exec($ch2);

		// close curl resource, and free up system resources
		curl_close($ch2);
		$xmlParser2 = new xmlparser();
		$array2 = $xmlParser2 -> GetXMLTree($data2);
		//$xmlParser->printa($array);
		if (isset($array2['RATEV2RESPONSE']) && count($array2['RATEV2RESPONSE']))
		{
			foreach ($array2['RATEV2RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value2)
			{
				if ($value2['MAILSERVICE'][0]['VALUE'] == 'Priority Mail')
				{
					$ResultCode = $value2['RATE'][0]['VALUE'];
				}
			}
		}
		else
			$blnIsError = 1;
	    }
        */
        
        // GMC - 03/20/13 - Reactivate USPS and DutyTaxAccount - DutyTax Drop - Transportation Changes
        require_once("../modules/xmlparser.php");

        // may need to urlencode xml portion
        $str2 = "http://production.shippingapis.com/ShippingAPI.dll" . "?API=RateV4&XML=<RateV4Request%20USERID=\"";
        $str2 .= "004GEEKT1462" . "\"%20PASSWORD=\"" . "931HN41XW201" . "\"><Package%20ID=\"0\"><Service>";
        $str2 .= "All" . "</Service><ZipOrigination>" . "89074" . "</ZipOrigination>";
        $str2 .= "<ZipDestination>" .  $row["ShipToZIP"] . "</ZipDestination>";
        $str2 .= "<Pounds>" . floor($_SESSION['OrderWeight']) . "</Pounds><Ounces>" . ceil(($_SESSION['OrderWeight'] - floor($_SESSION['OrderWeight'])) * 16) . "</Ounces>";
        $str2 .= "<Container>VARIABLE</Container><Size>REGULAR</Size>";
        $str2 .= "<Machinable>true</Machinable></Package></RateV4Request>";
        $ch2 = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch2, CURLOPT_URL, $str2);
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

        // grab URL and pass it to the browser
        $data2 = curl_exec($ch2);

        // close curl resource, and free up system resources
        curl_close($ch2);

        $xmlParser2 = new xmlparser();
        $array2 = $xmlParser2->GetXMLTree($data2);

        // $xmlParser2->printa($array2);

        if (isset($array2['RATEV4RESPONSE']) && count($array2['RATEV4RESPONSE']))
        {
            foreach ($array2['RATEV4RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value2)
            {
                if ($value2['MAILSERVICE'][0]['VALUE'] == 'Priority Mail&lt;sup&gt;&amp;reg;&lt;/sup&gt;')
                {
                    $ResultCode1 = $value2['RATE'][0]['VALUE'];
                    // echo '<tr><td>Priority Mail ' . ' ($' . number_format($ResultCode1, 2, '.', '') . ')</td></tr>';
                    // echo '<option value="401">USPS Priority Mail' . ' ($' . number_format($ResultCode1, 2, '.', '') . ')</option>';
                }

                /*
                if ($value2['MAILSERVICE'][0]['VALUE'] == 'Priority Mail&lt;sup&gt;&amp;reg;&lt;/sup&gt;  Large Flat Rate Box')
                {
                    $ResultCode2 = $value2['RATE'][0]['VALUE'];
                    // echo '<tr><td>Priority Mail Large Flat Rate Box' . ' ($' . number_format($ResultCode2, 2, '.', '') . ')</td></tr>';
                    // echo '<option value="401">USPS Priority Mail Large Flat Rate Box' . ' ($' . number_format($ResultCode2, 2, '.', '') . ')</option>';
                }
                */
                        
                if ($value2['MAILSERVICE'][0]['VALUE'] == 'Express Mail&lt;sup&gt;&amp;reg;&lt;/sup&gt;')
                {
                    $ResultCode3 = $value2['RATE'][0]['VALUE'];
                    // echo '<tr><td>Express Mail ' . ' ($' . number_format($ResultCode3, 2, '.', '') . ')</td></tr>';
                    // echo '<option value="403">USPS Express Mail' . ' ($' . number_format($ResultCode3, 2, '.', '') . ')</option>';
                }

                /*
                if ($value2['MAILSERVICE'][0]['VALUE'] == 'Express Mail&lt;sup&gt;&amp;reg;&lt;/sup&gt; Flat Rate Boxes')
                {
                    $ResultCode4 = $value2['RATE'][0]['VALUE'];
                    // echo '<tr><td>Express Mail Flat Rate Boxes' . ' ($' . number_format($ResultCode4, 2, '.', '') . ')</td></tr>';
                    // echo '<option value="403">USPS Express Mail Flat Rate Boxes' . ' ($' . number_format($ResultCode4, 2, '.', '') . ')</option>';
                }
                */

                // GMC - 05/31/13 - Remove USPS Standard Ground and Post
                /*
                if ($value2['MAILSERVICE'][0]['VALUE'] == 'Standard Post&lt;sup&gt;&amp;reg;&lt;/sup&gt;')
                {
                    $ResultCode5 = $value2['RATE'][0]['VALUE'];
                    // echo '<tr><td>Standard Post' . ' ($' . number_format($ResultCode5, 2, '.', '') . ')</td></tr>';
                    // echo '<option value="402">USPS Standard Post' . ' ($' . number_format($ResultCode5, 2, '.', '') . ')</option>';
                }
                */
                
                if ($value2['MAILSERVICE'][0]['VALUE'] == 'First-Class Mail&lt;sup&gt;&amp;reg;&lt;/sup&gt; Parcel')
                {
                    $ResultCode6 = $value2['RATE'][0]['VALUE'];
                    // echo '<tr><td>Standard Post' . ' ($' . number_format($ResultCode5, 2, '.', '') . ')</td></tr>';
                    // echo '<option value="400">USPS First Class' . ' ($' . number_format($ResultCode6, 2, '.', '') . ')</option>';
                }
            }
        }
        else
        {
            $blnIsError = 1;
        }

        if($_POST['ShipMethodID'] == 400)
        {
            $ResultCode = $ResultCode6;
        }
        elseif($_POST['ShipMethodID'] == 401)
        {
            $ResultCode = $ResultCode1;
        }

        // GMC - 05/31/13 - Remove USPS Standard Ground and Post
        /*
        else if($_POST['ShipMethodID'] == 402)
        {
            $ResultCode = $ResultCode5;
        }
        */
        
        else if($_POST['ShipMethodID'] == 403)
        {
            $ResultCode = $ResultCode3;
        }
    }
    }
}

// GMC - 04/02/09 - Activate Fedex Web Services (Domestic - International - Exclude Netherlands)
// GMC - 11/06/08 - To accomodate the International Shipping in CSR/ADMIN
if($blnIsInternational == 1)
{
    if($blnIsError == 1)
    {
        $ResultCode = 36;
    }
}

$decSubtotal = 0;

// SET FREE ITEMS IF APPLICABLE
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
    else
    {
        while($row40 = mssql_fetch_array($qryCart40))
	    {
		    $strProductName40 = $row40["ProductName"];
		    $decSubtotal = $decSubtotal + $_POST['ItemPrice40'] * $_POST['ItemQty40'];
	    }
    }
}

// SET SESSION VARIABLES

// GMC - 07/14/11 - Distributors Change CSRADMIN
$blnIsTransChg = 0;

// Prepaid
if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Prepaid' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] == '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] == 0)
{
    $blnIsTransChg = 1;
}
else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Prepaid' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] == '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] != 0)
{
    $_SESSION['Transportation_Charges'] = $_POST['Transportation_Charges'];
}
else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Prepaid' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] != '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] != 0)
{
    $blnIsTransChg = 1;
}

// Collect
else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Collect' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] == '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] != 0)
{
    $blnIsTransChg = 1;
}
else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Collect' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] == '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] == 0)
{
    $blnIsTransChg = 1;
}
else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Collect' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] != '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] == 0)
{
    $_SESSION['Transportation_Charges'] = $_POST['Transportation_Charges'];
    $_SESSION['Transportation_Charges_Value'] = $_POST['Transportation_Charges_Account'];
    $_SESSION['OrderHandling'] = 0;
    
    // GMC - 12/29/11 - Change Shipping Method to Will Call for Transportation Charges when Collect or Third Party
    // $_SESSION['ShippingMethod'] = 601;
    $_SESSION['ShippingMethod'] = 999;
}

// Third Party
else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Third Party' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] == '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] != 0)
{
    $blnIsTransChg = 1;
}
else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Third Party' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] == '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] == 0)
{
    $blnIsTransChg = 1;
}

// GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Third Party' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] != '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] == 0)
{
    $blnIsTransChg = 1;
}
// else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Third Party' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] != '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] == 0)
else if (isset($_POST['Transportation_Charges']) && $_POST['Transportation_Charges'] == 'Third Party' &&  isset($_POST['Transportation_Charges_Account']) && $_POST['Transportation_Charges_Account'] != '' &&  isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] != 0)
{
    $_SESSION['Transportation_Charges'] = $_POST['Transportation_Charges'];
    $_SESSION['Transportation_Charges_Value'] = $_POST['Transportation_Charges_Account'];
    $_SESSION['OrderHandling'] = 0;
    $_SESSION['OrderShipping'] = 0;

    // GMC - 12/29/11 - Change Shipping Method to Will Call for Transportation Charges when Collect or Third Party
    // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
    // $_SESSION['ShippingMethod'] = 602;
    // $_SESSION['ShippingMethod'] = 999;
    $_SESSION['ShippingMethod'] = $_POST['ShipMethodID'];
}

if (isset($_POST['Duty_Tax']))
{
    $_SESSION['Duty_Tax'] = $_POST['Duty_Tax'];
}

// GMC - 03/20/13 - Reactivate USPS and DutyTaxAccount - DutyTax Drop - Transportation Changes
// $_SESSION['Duty_Tax_Value'] = $_POST['Duty_Tax_Account'];
$_SESSION['Duty_Tax_Value'] = "";

if (isset($_POST['IsFreeShipping']) || $_POST['ShipMethodID'] == 99)
{
   // GMC - 09/16/09 - Fix the Shipping Override when PromoCode
   if ($intCustomerType == 2)
   {
        // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
        // GMC - 09/05/09 - Promotion Section - Drop Down for CSR's Only
        if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0) && ($_SESSION['Summer2012FineLinePrimer'] == 0))
        {
            $discountValue = ($decSubtotal * $_SESSION['Promo_Code_Discount']);
            $decSubtotal = ($decSubtotal - ($decSubtotal * $_SESSION['Promo_Code_Discount']));
	        $_SESSION['OrderSubtotal'] = $decSubtotal;
	        $_SESSION['OrderShipping'] = 0;

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
	            $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	            $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
            }
            else
            {
	            $_SESSION['OrderTax'] = ($decSubtotal * $_SESSION['OrderTaxRate']);
            }

	        $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }
        else
        {
	        $_SESSION['OrderSubtotal'] = $decSubtotal;
	        $_SESSION['OrderShipping'] = 0;

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
	            $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	            $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
            }
            else
            {
	            $_SESSION['OrderTax'] = ($decSubtotal * $_SESSION['OrderTaxRate']);
            }

	        $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }
   }
   else
   {
	   $_SESSION['OrderSubtotal'] = $decSubtotal;
	   $_SESSION['OrderShipping'] = 0;

       // GMC - 09/28/10 - Force Sales Tax Value by JS
       if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
       {
           $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
           $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
       }
       else
       {
           $_SESSION['OrderTax'] = ($decSubtotal * $_SESSION['OrderTaxRate']);
       }

	   $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
   }
}
elseif (isset($_POST['ShippingOverride']) && $_POST['ShippingOverride'] != '')
{
   // GMC - 09/16/09 - Fix the Shipping Override when PromoCode
   if ($intCustomerType == 2)
   {
        // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
        // GMC - 09/05/09 - Promotion Section - Drop Down for CSR's Only
        if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0) && ($_SESSION['Summer2012FineLinePrimer'] == 0))
        {
            $discountValue = ($decSubtotal * $_SESSION['Promo_Code_Discount']);
            $decSubtotal = ($decSubtotal - ($decSubtotal * $_SESSION['Promo_Code_Discount']));
            $_SESSION['OrderSubtotal'] = $decSubtotal;
	        $_SESSION['OrderShipping'] = $_POST['ShippingOverride'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	            $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
            }
            else
            {
                $_SESSION['OrderTax'] = ($decSubtotal * $_SESSION['OrderTaxRate']);
            }

	        $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }
        else
        {
            $_SESSION['OrderSubtotal'] = $decSubtotal;
	        $_SESSION['OrderShipping'] = $_POST['ShippingOverride'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	            $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
            }
            else
            {
                $_SESSION['OrderTax'] = ($decSubtotal * $_SESSION['OrderTaxRate']);
            }

	        $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }
   }
   else
   {
       $_SESSION['OrderSubtotal'] = $decSubtotal;
	   $_SESSION['OrderShipping'] = $_POST['ShippingOverride'];

       // GMC - 09/28/10 - Force Sales Tax Value by JS
       if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
       {
            $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
            $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
       }
       else
       {
            $_SESSION['OrderTax'] = ($decSubtotal * $_SESSION['OrderTaxRate']);
       }

	   $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
   }
}

// GMC - 02/15/09 - To include Will Call in International
elseif (($blnIsInternational == 1) && ($_POST['ShipMethodID'] == 999))
{
    $_SESSION['WillCallInt'] = 'Ok';
    $_SESSION['OrderSubtotal'] = $decSubtotal;
	$_SESSION['OrderShipping'] = 0;
	$_SESSION['OrderHandling'] = 0;

    // GMC - 09/28/10 - Force Sales Tax Value by JS
    if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
    {
        $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
        $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
    }
    else
    {
        $_SESSION['OrderTax'] = ($decSubtotal * $_SESSION['OrderTaxRate']);
    }

	$_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
}

else
{
    if ($intCustomerType == 1)
    {
        if(strtoupper($_SESSION['Ship_State']) == 'CA')
        {
            $_SESSION['OrderSubtotal'] = $decSubtotal;
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 // GMC - 02/12/13 - Fix Bug on CA - NV - UT State Calculation if Override Unit Price
                if(($decSubtotal * $_SESSION['OrderTaxRate']) == $_POST['SalesTaxForced'])
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = $decSubtotal * $_SESSION['OrderTaxRate'];
                    $_SESSION['SalesTaxForced'] = $decSubtotal * $_SESSION['OrderTaxRate'];
                }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderShipping'] = 0;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
            }

            else
            {
                $_SESSION['OrderTax'] = ($decSubtotal * $_SESSION['OrderTaxRate']);
            }

            $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }
        elseif(strtoupper($_SESSION['Ship_State']) == 'NV')
        {
	        $_SESSION['OrderSubtotal'] = $decSubtotal;
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 // GMC - 02/12/13 - Fix Bug on CA - NV - UT State Calculation if Override Unit Price
                if((($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate']) == $_POST['SalesTaxForced'])
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                    $_SESSION['SalesTaxForced'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderShipping'] = 0;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
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
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 // GMC - 02/12/13 - Fix Bug on CA - NV - UT State Calculation if Override Unit Price
                if((($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate']) == $_POST['SalesTaxForced'])
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                    $_SESSION['SalesTaxForced'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderShipping'] = 0;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
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
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 // GMC - 02/12/13 - Fix Bug on CA - NV - UT State Calculation if Override Unit Price
                if((($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate']) == $_POST['SalesTaxForced'])
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                    $_SESSION['SalesTaxForced'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderShipping'] = 0;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
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
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 // GMC - 02/12/13 - Fix Bug on CA - NV - UT State Calculation if Override Unit Price
                if((($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate']) == $_POST['SalesTaxForced'])
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                    $_SESSION['SalesTaxForced'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderShipping'] = 0;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
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
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 // GMC - 02/12/13 - Fix Bug on CA - NV - UT State Calculation if Override Unit Price
                if((($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate']) == $_POST['SalesTaxForced'])
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                    $_SESSION['SalesTaxForced'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderShipping'] = 0;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
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
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 // GMC - 02/12/13 - Fix Bug on CA - NV - UT State Calculation if Override Unit Price
                if((($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate']) == $_POST['SalesTaxForced'])
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                    $_SESSION['SalesTaxForced'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderShipping'] = 0;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
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
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 // GMC - 02/12/13 - Fix Bug on CA - NV - UT State Calculation if Override Unit Price
                if((($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate']) == $_POST['SalesTaxForced'])
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                    $_SESSION['SalesTaxForced'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderShipping'] = 0;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
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
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 // GMC - 02/12/13 - Fix Bug on CA - NV - UT State Calculation if Override Unit Price
                if((($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate']) == $_POST['SalesTaxForced'])
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                    $_SESSION['SalesTaxForced'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
                }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderShipping'] = 0;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
            }

            else
            {
	            $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderShipping']) * $_SESSION['OrderTaxRate'];
            }

            $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
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
                $_SESSION['OrderSubtotal'] = $decSubtotal;
	            $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
                    $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
              }
              else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
              {
                $_SESSION['OrderSubtotal'] = $decSubtotal;
	            $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
              }
              else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
              {
                $_SESSION['OrderSubtotal'] = $decSubtotal;
	            $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
              }
              else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
              {
                $_SESSION['OrderSubtotal'] = $decSubtotal;
	            $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
                    $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
              }
            }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderSubtotal'] = $decSubtotal;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
            }

            else
            {
                $_SESSION['OrderSubtotal'] = $decSubtotal;
	            $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderHandling']) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
            }
        }
    }
    else
    {
        // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
        // GMC - 09/05/09 - Promotion Section - Drop Down for CSR's Only
        if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0) && ($_SESSION['Summer2012FineLinePrimer'] == 0))
        {
            $discountValue = ($decSubtotal * $_SESSION['Promo_Code_Discount']);
            $decSubtotal = ($decSubtotal - ($decSubtotal * $_SESSION['Promo_Code_Discount']));
            $_SESSION['OrderSubtotal'] = $decSubtotal;
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	             $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
            }
            else
            {
	             $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderHandling']) * $_SESSION['OrderTaxRate'];
            }

            $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }

            // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
            elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
            {
                $_SESSION['OrderSubtotal'] = $decSubtotal;

                // GMC - 09/28/10 - Force Sales Tax Value by JS
                if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
                {
                    $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	                $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
                }
                else
                {
	                $_SESSION['OrderTax'] = ($decSubtotal) * $_SESSION['OrderTaxRate'];
                }

                $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'];
            }

        else
        {
            $_SESSION['OrderSubtotal'] = $decSubtotal;
	        $_SESSION['OrderShipping'] = $ResultCode + $_SESSION['OrderHandling'];

            // GMC - 09/28/10 - Force Sales Tax Value by JS
            if(isset($_POST['SalesTaxForced']) && $_POST['SalesTaxForced'] != 0)
            {
                 $_SESSION['OrderTax'] = $_POST['SalesTaxForced'];
	             $_SESSION['SalesTaxForced'] = $_POST['SalesTaxForced'];
            }
            else
            {
	             $_SESSION['OrderTax'] = ($decSubtotal + $_SESSION['OrderHandling']) * $_SESSION['OrderTaxRate'];
            }

            $_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderTax'] + $_SESSION['OrderShipping'];
        }
    }
}

?>
</table>

<p>&nbsp;</p>

<p><span style="font-weight:bold;">Shipping Information</span> (

<?php

 echo '<a href="/csradmin/customers.php?Action=SelectAddress&CustomerID=' . $_GET['CustomerID'] . '">edit</a>';
 
?>

)<br />

<?php
echo $Address1 . '<br />';
if ($Address2 != '')
	echo $Address2 . '<br />';
echo $AddressCity . ', ' . $AddressState . ' ' . $AddressPostalCode . '<br />';
echo $AddressCountryCode . '</p>';
?>

<p><span style="font-weight:bold;">Payment Information</span> (<?php echo '<a href="/csradmin/customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '&EditPayment">edit</a>'; ?>)<br />
<?php

if ($_SESSION['PaymentType'] == 'CreditCard')
{
	echo 'Credit Card Ending in ' . substr($_SESSION['PaymentCC_Number'],-5,5) . '<br />';
	echo 'Expires ' . $_SESSION['PaymentCC_ExpMonth'] . ' / ' . $_SESSION['PaymentCC_ExpYear'] . '</p>';
}
elseif ($_SESSION['PaymentType'] == 'CreditCardSwiped')
{
	echo 'Preauthorized Payment: ' . $_SESSION['PaymentCC_SwipedAuth'] . '</p>';
}
elseif ($_SESSION['PaymentType'] == 'ECheck')
{
	echo 'Bank Routing Number: ' . $_SESSION['PaymentCK_BankRouting'] . '<br />';
	echo 'Bank Account Number ' . $_SESSION['PaymentCK_BankAccount'] . '</p>';
}
elseif ($_SESSION['PaymentType'] == 'Terms')
{
	echo 'Purchase Order Number: ' . $_SESSION['PaymentPO_Number'] . '</p>';
}
?>

<?php

if ($_SESSION['FORMNavisionCampaign'] != '0')
{
	echo '<p><span style="font-weight:bold;">Tradeshow Code</span><br />' . $_SESSION['FORMNavisionCampaign'];
}
?>

<p>&nbsp;</p>

<form action="/csradmin/customers.php?Action=NewOrder&CustomerID=<?php echo $_GET['CustomerID']; ?>" method="post">

<table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">

<tr>
	<th width="*" style="text-align:left">Product</th>
    <th width="120" style="text-align:left">Stock Location</th>
    <th width="100" style="text-align:left;">Unit Price</th>
    <th width="100" style="text-align:left">Qty</th>
    <th width="150" style="text-align:left;">Total Price</th>
</tr>

<?php

if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
{
	echo '<input type="hidden" name="ItemID1" value="' . $_POST['ItemID1'] . '">';
	echo '<input type="hidden" name="ItemStockLocation1" value="' . $_POST['ItemStockLocation1'] . '">';
	echo '<input type="hidden" name="ItemQty1" value="' . $_POST['ItemQty1'] . '">';
	echo '<input type="hidden" name="ItemPrice1" value="' . $_POST['ItemPrice1'] . '">';
	echo '<input type="hidden" name="ItemFree1" value="' . $_POST['ItemFree1'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName1 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice1'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty1'];
    
    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree1 > 0)
    {
     if ($_POST['ItemID1'] == '124' || $_POST['ItemID1'] == '139' || $_POST['ItemID1'] == '141' || $_POST['ItemID1'] == '142')
     {
         echo ' + ' . $ItemFree1 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree1 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice1'] * $_POST['ItemQty1'], 2, '.', '') . '</td>';

	echo '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_1'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_1'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_1'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_1'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_1'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_1'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_1'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_1']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
    
    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_1'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_1'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_1'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_1'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_1'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_1'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
    
    if($_SESSION['Regenesis_2285_1'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation1'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_1'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
{
	echo '<input type="hidden" name="ItemID2" value="' . $_POST['ItemID2'] . '">';
	echo '<input type="hidden" name="ItemStockLocation2" value="' . $_POST['ItemStockLocation2'] . '">';
	echo '<input type="hidden" name="ItemQty2" value="' . $_POST['ItemQty2'] . '">';
	echo '<input type="hidden" name="ItemPrice2" value="' . $_POST['ItemPrice2'] . '">';
	echo '<input type="hidden" name="ItemFree2" value="' . $_POST['ItemFree2'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName2 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice2'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty2'];
    
    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree2 > 0)
    {
     if ($_POST['ItemID2'] == '124' || $_POST['ItemID2'] == '139' || $_POST['ItemID2'] == '141' || $_POST['ItemID2'] == '142')
     {
         echo ' + ' . $ItemFree2 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree2 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice2'] * $_POST['ItemQty2'], 2, '.', '') . '</td>';

	echo '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_2'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_2'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_2'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_2'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_2'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_2'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_2'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_2']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_2'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_2'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_2'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_2'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_2'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_2'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_2'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation2'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_2'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
{
	echo '<input type="hidden" name="ItemID3" value="' . $_POST['ItemID3'] . '">';
	echo '<input type="hidden" name="ItemStockLocation3" value="' . $_POST['ItemStockLocation3'] . '">';
	echo '<input type="hidden" name="ItemQty3" value="' . $_POST['ItemQty3'] . '">';
	echo '<input type="hidden" name="ItemPrice3" value="' . $_POST['ItemPrice3'] . '">';
	echo '<input type="hidden" name="ItemFree3" value="' . $_POST['ItemFree3'] . '">';
	echo '<tr>';
	
	echo '<td>' . $strProductName3 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice3'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty3'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree3 > 0)
    {
     if ($_POST['ItemID3'] == '124' || $_POST['ItemID3'] == '139' || $_POST['ItemID3'] == '141' || $_POST['ItemID3'] == '142')
     {
         echo ' + ' . $ItemFree3 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree3 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice3'] * $_POST['ItemQty3'], 2, '.', '') . '</td>';

	echo '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_3'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_3'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_3'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_3'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_3'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_3'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_3'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_3']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_3'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_3'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_3'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_3'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_3'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_3'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_3'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation3'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_3'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
{
	echo '<input type="hidden" name="ItemID4" value="' . $_POST['ItemID4'] . '">';
	echo '<input type="hidden" name="ItemStockLocation4" value="' . $_POST['ItemStockLocation4'] . '">';
	echo '<input type="hidden" name="ItemQty4" value="' . $_POST['ItemQty4'] . '">';
	echo '<input type="hidden" name="ItemPrice4" value="' . $_POST['ItemPrice4'] . '">';
	echo '<input type="hidden" name="ItemFree4" value="' . $_POST['ItemFree4'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName4 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice4'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty4'];
    
    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100 (NOT DEPLOYED YET)
	if ($ItemFree4 > 0)
    {
     if ($_POST['ItemID4'] == '124' || $_POST['ItemID4'] == '139' || $_POST['ItemID4'] == '141' || $_POST['ItemID4'] == '142')
     {
         echo ' + ' . $ItemFree4 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree4 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice4'] * $_POST['ItemQty4'], 2, '.', '') . '</td>';

	echo '</tr>';
	
    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_4'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_4'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_4'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_4'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_4'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_4'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_4'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
             echo '<tr>';
             echo '<td>Mascara Raven</td>';
             echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*10 . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '</tr>';
        }
        else
        {
             echo '<tr>';
             echo '<td>Mascara Raven</td>';
             echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '</tr>';
             echo '<tr>';
             echo '<td>Mascara Espresso</td>';
             echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_4']*5 . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_4'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_4'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_4'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_4'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_4'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_4'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_4'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation4'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_4'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
{
	echo '<input type="hidden" name="ItemID5" value="' . $_POST['ItemID5'] . '">';
	echo '<input type="hidden" name="ItemStockLocation5" value="' . $_POST['ItemStockLocation5'] . '">';
	echo '<input type="hidden" name="ItemQty5" value="' . $_POST['ItemQty5'] . '">';
	echo '<input type="hidden" name="ItemPrice5" value="' . $_POST['ItemPrice5'] . '">';
	echo '<input type="hidden" name="ItemFree5" value="' . $_POST['ItemFree5'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName5 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice5'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty5'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree5 > 0)
    {
     if ($_POST['ItemID5'] == '124' || $_POST['ItemID5'] == '139' || $_POST['ItemID5'] == '141' || $_POST['ItemID5'] == '142')
     {
         echo ' + ' . $ItemFree5 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree5 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice5'] * $_POST['ItemQty5'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_5'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_5'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_5'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_5'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_5'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_5'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_5'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_5']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_5'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_5'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_5'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_5'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_5'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_5'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_5'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation5'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_5'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
{
	echo '<input type="hidden" name="ItemID6" value="' . $_POST['ItemID6'] . '">';
	echo '<input type="hidden" name="ItemStockLocation6" value="' . $_POST['ItemStockLocation6'] . '">';
	echo '<input type="hidden" name="ItemQty6" value="' . $_POST['ItemQty6'] . '">';
	echo '<input type="hidden" name="ItemPrice6" value="' . $_POST['ItemPrice6'] . '">';
	echo '<input type="hidden" name="ItemFree6" value="' . $_POST['ItemFree6'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName6 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice6'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty6'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree6 > 0)
    {
     if ($_POST['ItemID6'] == '124' || $_POST['ItemID6'] == '139' || $_POST['ItemID6'] == '141' || $_POST['ItemID6'] == '142')
     {
         echo ' + ' . $ItemFree6 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree6 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice6'] * $_POST['ItemQty6'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_6'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_6'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_6'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_6'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_6'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_6'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_6'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
             echo '<tr>';
             echo '<td>Mascara Raven</td>';
             echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*10 . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '</tr>';
        }
        else
        {
             echo '<tr>';
             echo '<td>Mascara Raven</td>';
             echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '</tr>';
             echo '<tr>';
             echo '<td>Mascara Espresso</td>';
             echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_6']*5 . '</td>';
             echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
             echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_6'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_6'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_6'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_6'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_6'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_6'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_6'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation6'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_6'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
{
	echo '<input type="hidden" name="ItemID7" value="' . $_POST['ItemID7'] . '">';
	echo '<input type="hidden" name="ItemStockLocation7" value="' . $_POST['ItemStockLocation7'] . '">';
	echo '<input type="hidden" name="ItemQty7" value="' . $_POST['ItemQty7'] . '">';
	echo '<input type="hidden" name="ItemPrice7" value="' . $_POST['ItemPrice7'] . '">';
	echo '<input type="hidden" name="ItemFree7" value="' . $_POST['ItemFree7'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName7 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice7'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty7'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree7 > 0)
    {
     if ($_POST['ItemID7'] == '124' || $_POST['ItemID7'] == '139' || $_POST['ItemID7'] == '141' || $_POST['ItemID7'] == '142')
     {
         echo ' + ' . $ItemFree7 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree7 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice7'] * $_POST['ItemQty7'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_7'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_7'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_7'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_7'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_7'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_7'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_7'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_7']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_7'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_7'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_7'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_7'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_7'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_7'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_7'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation7'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_7'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
{
	echo '<input type="hidden" name="ItemID8" value="' . $_POST['ItemID8'] . '">';
	echo '<input type="hidden" name="ItemStockLocation8" value="' . $_POST['ItemStockLocation8'] . '">';
	echo '<input type="hidden" name="ItemQty8" value="' . $_POST['ItemQty8'] . '">';
	echo '<input type="hidden" name="ItemPrice8" value="' . $_POST['ItemPrice8'] . '">';
	echo '<input type="hidden" name="ItemFree8" value="' . $_POST['ItemFree8'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName8 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice8'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty8'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree8 > 0)
    {
     if ($_POST['ItemID8'] == '124' || $_POST['ItemID8'] == '139' || $_POST['ItemID8'] == '141' || $_POST['ItemID8'] == '142')
     {
         echo ' + ' . $ItemFree8 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree8 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice8'] * $_POST['ItemQty8'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_8'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_8'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_8'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_8'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_8'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_8'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_8'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_8']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_8'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_8'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_8'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_8'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_8'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_8'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_8'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation8'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_8'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
{
	echo '<input type="hidden" name="ItemID9" value="' . $_POST['ItemID9'] . '">';
	echo '<input type="hidden" name="ItemStockLocation9" value="' . $_POST['ItemStockLocation9'] . '">';
	echo '<input type="hidden" name="ItemQty9" value="' . $_POST['ItemQty9'] . '">';
	echo '<input type="hidden" name="ItemPrice9" value="' . $_POST['ItemPrice9'] . '">';
	echo '<input type="hidden" name="ItemFree9" value="' . $_POST['ItemFree9'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName9 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice9'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty9'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree9 > 0)
    {
     if ($_POST['ItemID9'] == '124' || $_POST['ItemID9'] == '139' || $_POST['ItemID9'] == '141' || $_POST['ItemID9'] == '142')
     {
         echo ' + ' . $ItemFree9 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree9 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice9'] * $_POST['ItemQty9'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_9'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_9'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_9'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_9'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_9'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_9'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_9'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_9']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_9'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_9'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_9'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_9'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_9'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_9'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_9'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation9'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_9'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
{
	echo '<input type="hidden" name="ItemID10" value="' . $_POST['ItemID10'] . '">';
	echo '<input type="hidden" name="ItemStockLocation10" value="' . $_POST['ItemStockLocation10'] . '">';
	echo '<input type="hidden" name="ItemQty10" value="' . $_POST['ItemQty10'] . '">';
	echo '<input type="hidden" name="ItemPrice10" value="' . $_POST['ItemPrice10'] . '">';
	echo '<input type="hidden" name="ItemFree10" value="' . $_POST['ItemFree10'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName10 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice10'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty10'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree10 > 0)
    {
     if ($_POST['ItemID10'] == '124' || $_POST['ItemID10'] == '139' || $_POST['ItemID10'] == '141' || $_POST['ItemID10'] == '142')
     {
         echo ' + ' . $ItemFree10 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree10 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice10'] * $_POST['ItemQty10'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_10'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_10'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_10'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}
 
    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_10'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_10'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_10'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_10'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_10']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_10'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_10'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_10'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_10'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_10'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_10'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_10'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation10'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_10'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

// GMC - 03/18/10 - Add 10 Line Items Admin

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
{
	echo '<input type="hidden" name="ItemID11" value="' . $_POST['ItemID11'] . '">';
	echo '<input type="hidden" name="ItemStockLocation11" value="' . $_POST['ItemStockLocation11'] . '">';
	echo '<input type="hidden" name="ItemQty11" value="' . $_POST['ItemQty11'] . '">';
	echo '<input type="hidden" name="ItemPrice11" value="' . $_POST['ItemPrice11'] . '">';
	echo '<input type="hidden" name="ItemFree11" value="' . $_POST['ItemFree11'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName11 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice11'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty11'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree11 > 0)
    {
     if ($_POST['ItemID11'] == '124' || $_POST['ItemID11'] == '139' || $_POST['ItemID11'] == '141' || $_POST['ItemID11'] == '142')
     {
         echo ' + ' . $ItemFree11 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree11 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice11'] * $_POST['ItemQty11'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_11'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_11'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_11'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_11'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_11'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_11'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_11'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_11']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_11'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_11'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_11'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_11'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_11'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_11'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_11'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation11'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_11'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
{
	echo '<input type="hidden" name="ItemID12" value="' . $_POST['ItemID12'] . '">';
	echo '<input type="hidden" name="ItemStockLocation12" value="' . $_POST['ItemStockLocation12'] . '">';
	echo '<input type="hidden" name="ItemQty12" value="' . $_POST['ItemQty12'] . '">';
	echo '<input type="hidden" name="ItemPrice12" value="' . $_POST['ItemPrice12'] . '">';
	echo '<input type="hidden" name="ItemFree12" value="' . $_POST['ItemFree12'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName12 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice12'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty12'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree12 > 0)
    {
     if ($_POST['ItemID12'] == '124' || $_POST['ItemID12'] == '139' || $_POST['ItemID12'] == '141' || $_POST['ItemID12'] == '142')
     {
         echo ' + ' . $ItemFree12 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree12 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice12'] * $_POST['ItemQty12'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_12'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_12'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_12'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_12'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_12'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_12'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_12'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_12']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_12'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_12'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_12'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_12'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_12'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_12'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_12'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation12'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_12'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
{
	echo '<input type="hidden" name="ItemID13" value="' . $_POST['ItemID13'] . '">';
	echo '<input type="hidden" name="ItemStockLocation13" value="' . $_POST['ItemStockLocation13'] . '">';
	echo '<input type="hidden" name="ItemQty13" value="' . $_POST['ItemQty13'] . '">';
	echo '<input type="hidden" name="ItemPrice13" value="' . $_POST['ItemPrice13'] . '">';
	echo '<input type="hidden" name="ItemFree13" value="' . $_POST['ItemFree13'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName13 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice13'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty13'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree13 > 0)
    {
     if ($_POST['ItemID13'] == '124' || $_POST['ItemID13'] == '139' || $_POST['ItemID13'] == '141' || $_POST['ItemID13'] == '142')
     {
         echo ' + ' . $ItemFree13 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree13 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice13'] * $_POST['ItemQty13'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_13'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_13'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_13'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_13'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_13'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_13'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_13'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_13']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_13'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_13'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_13'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_13'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_13'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_13'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_13'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation13'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_13'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
{
	echo '<input type="hidden" name="ItemID14" value="' . $_POST['ItemID14'] . '">';
	echo '<input type="hidden" name="ItemStockLocation14" value="' . $_POST['ItemStockLocation14'] . '">';
	echo '<input type="hidden" name="ItemQty14" value="' . $_POST['ItemQty14'] . '">';
	echo '<input type="hidden" name="ItemPrice14" value="' . $_POST['ItemPrice14'] . '">';
	echo '<input type="hidden" name="ItemFree14" value="' . $_POST['ItemFree14'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName14 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice14'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty14'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree14 > 0)
    {
     if ($_POST['ItemID14'] == '124' || $_POST['ItemID14'] == '139' || $_POST['ItemID14'] == '141' || $_POST['ItemID14'] == '142')
     {
         echo ' + ' . $ItemFree14 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree14 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice14'] * $_POST['ItemQty14'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_14'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_14'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_14'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_14'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_14'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_14'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_14'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_14']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_14'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_14'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_14'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_14'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_14'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_14'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_14'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation14'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_14'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
{
	echo '<input type="hidden" name="ItemID15" value="' . $_POST['ItemID15'] . '">';
	echo '<input type="hidden" name="ItemStockLocation15" value="' . $_POST['ItemStockLocation15'] . '">';
	echo '<input type="hidden" name="ItemQty15" value="' . $_POST['ItemQty15'] . '">';
	echo '<input type="hidden" name="ItemPrice15" value="' . $_POST['ItemPrice15'] . '">';
	echo '<input type="hidden" name="ItemFree15" value="' . $_POST['ItemFree15'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName15 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice15'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty15'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree15 > 0)
    {
     if ($_POST['ItemID15'] == '124' || $_POST['ItemID15'] == '139' || $_POST['ItemID15'] == '141' || $_POST['ItemID15'] == '142')
     {
         echo ' + ' . $ItemFree15 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree15 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice15'] * $_POST['ItemQty15'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_15'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_15'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_15'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_15'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_15'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_15'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_15'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_15']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_15'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_15'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_15'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_15'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_15'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_15'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_15'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation15'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_15'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
{
	echo '<input type="hidden" name="ItemID16" value="' . $_POST['ItemID16'] . '">';
	echo '<input type="hidden" name="ItemStockLocation16" value="' . $_POST['ItemStockLocation16'] . '">';
	echo '<input type="hidden" name="ItemQty16" value="' . $_POST['ItemQty16'] . '">';
	echo '<input type="hidden" name="ItemPrice16" value="' . $_POST['ItemPrice16'] . '">';
	echo '<input type="hidden" name="ItemFree16" value="' . $_POST['ItemFree16'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName16 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice16'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty16'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree16 > 0)
    {
     if ($_POST['ItemID16'] == '124' || $_POST['ItemID16'] == '139' || $_POST['ItemID16'] == '141' || $_POST['ItemID16'] == '142')
     {
         echo ' + ' . $ItemFree16 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree16 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice16'] * $_POST['ItemQty16'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_16'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_16'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_16'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_16'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_16'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_16'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_16'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_16']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_16'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_16'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_16'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_16'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_16'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_16'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_16'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation16'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_16'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
{
	echo '<input type="hidden" name="ItemID17" value="' . $_POST['ItemID17'] . '">';
	echo '<input type="hidden" name="ItemStockLocation17" value="' . $_POST['ItemStockLocation17'] . '">';
	echo '<input type="hidden" name="ItemQty17" value="' . $_POST['ItemQty17'] . '">';
	echo '<input type="hidden" name="ItemPrice17" value="' . $_POST['ItemPrice17'] . '">';
	echo '<input type="hidden" name="ItemFree17" value="' . $_POST['ItemFree17'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName17 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice17'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty17'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree17 > 0)
    {
     if ($_POST['ItemID17'] == '124' || $_POST['ItemID17'] == '139' || $_POST['ItemID17'] == '141' || $_POST['ItemID17'] == '142')
     {
         echo ' + ' . $ItemFree17 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree17 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice17'] * $_POST['ItemQty17'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_17'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_17'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_17'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_17'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_17'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_17'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_17'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_17']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_17'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_17'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_17'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_17'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_17'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_17'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_17'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation17'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_17'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
{
	echo '<input type="hidden" name="ItemID18" value="' . $_POST['ItemID18'] . '">';
	echo '<input type="hidden" name="ItemStockLocation18" value="' . $_POST['ItemStockLocation18'] . '">';
	echo '<input type="hidden" name="ItemQty18" value="' . $_POST['ItemQty18'] . '">';
	echo '<input type="hidden" name="ItemPrice18" value="' . $_POST['ItemPrice18'] . '">';
	echo '<input type="hidden" name="ItemFree18" value="' . $_POST['ItemFree18'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName18 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice18'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty18'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree18 > 0)
    {
     if ($_POST['ItemID18'] == '124' || $_POST['ItemID18'] == '139' || $_POST['ItemID18'] == '141' || $_POST['ItemID18'] == '142')
     {
         echo ' + ' . $ItemFree18 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree18 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice18'] * $_POST['ItemQty18'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_18'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_18'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_18'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_18'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_18'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_18'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_18'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_18']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_18'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_18'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_18'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_18'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_18'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_18'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_18'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation18'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_18'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
{
	echo '<input type="hidden" name="ItemID19" value="' . $_POST['ItemID19'] . '">';
	echo '<input type="hidden" name="ItemStockLocation19" value="' . $_POST['ItemStockLocation19'] . '">';
	echo '<input type="hidden" name="ItemQty19" value="' . $_POST['ItemQty19'] . '">';
	echo '<input type="hidden" name="ItemPrice19" value="' . $_POST['ItemPrice19'] . '">';
	echo '<input type="hidden" name="ItemFree19" value="' . $_POST['ItemFree19'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName19 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice19'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty19'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree19 > 0)
    {
     if ($_POST['ItemID19'] == '124' || $_POST['ItemID19'] == '139' || $_POST['ItemID19'] == '141' || $_POST['ItemID19'] == '142')
     {
         echo ' + ' . $ItemFree19 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree19 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice19'] * $_POST['ItemQty19'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_19'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_19'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_19'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_19'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_19'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_19'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_19'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_19']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_19'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_19'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_19'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_19'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_19'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_19'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_19'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation19'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_19'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
{
	echo '<input type="hidden" name="ItemID20" value="' . $_POST['ItemID20'] . '">';
	echo '<input type="hidden" name="ItemStockLocation20" value="' . $_POST['ItemStockLocation20'] . '">';
	echo '<input type="hidden" name="ItemQty20" value="' . $_POST['ItemQty20'] . '">';
	echo '<input type="hidden" name="ItemPrice20" value="' . $_POST['ItemPrice20'] . '">';
	echo '<input type="hidden" name="ItemFree20" value="' . $_POST['ItemFree20'] . '">';
	echo '<tr>';

	echo '<td>' . $strProductName20 . '</td>';

	echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice20'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty20'];

    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
	// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
	if ($ItemFree20 > 0)
    {
     if ($_POST['ItemID20'] == '124' || $_POST['ItemID20'] == '139' || $_POST['ItemID20'] == '141' || $_POST['ItemID20'] == '142')
     {
         echo ' + ' . $ItemFree20 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree20 . ' FREE';
     }
    }

    echo '</td>';

	echo '<td>$' . number_format($_POST['ItemPrice20'] * $_POST['ItemQty20'], 2, '.', '') . '</td>';

	echo '</tr>';

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
	if($_SESSION['BreastCancerAugOct2009_20'] >= 12)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_20'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Mascara by Revitalash&reg;</td>';
	echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['BreastCancerAugOct2009_20'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC - 11/02/09 - 2009 Holiday Gift Box Set
	if($_SESSION['HolidayGiftBoxSet2009_20'] > 0)
	{
	echo '<tr>';
	echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
    echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_20'] . '</td>';
	echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	echo '</tr>';
	}

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
	if($_SESSION['ValentinesDay2010_20'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
	if($_SESSION['TradeShowFamilyBundle2010_20'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';

        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_20']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
	if($_SESSION['MothersDay2010_20'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_20'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_20'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_20'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
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
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';

	        // echo '<td>$' . number_format($rowGetBundle['UnitPrice'], 2, '.', '') . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';

            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';

            // echo '<td>$' . number_format($rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';

            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_20'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_20'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_20'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation20'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_20'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

// GMC - 10/16/13 - Add 20 Line Items Admin

if (isset($_POST['ItemID21']) && $_POST['ItemID21'] != 0)
{
	echo '<input type="hidden" name="ItemID21" value="' . $_POST['ItemID21'] . '">';
	echo '<input type="hidden" name="ItemStockLocation21" value="' . $_POST['ItemStockLocation21'] . '">';
	echo '<input type="hidden" name="ItemQty21" value="' . $_POST['ItemQty21'] . '">';
	echo '<input type="hidden" name="ItemPrice21" value="' . $_POST['ItemPrice21'] . '">';
	echo '<input type="hidden" name="ItemFree21" value="' . $_POST['ItemFree21'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName21 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice21'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty21'];
	if ($ItemFree21 > 0)
    {
     if ($_POST['ItemID21'] == '124' || $_POST['ItemID21'] == '139' || $_POST['ItemID21'] == '141' || $_POST['ItemID21'] == '142')
     {
         echo ' + ' . $ItemFree21 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree21 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice21'] * $_POST['ItemQty21'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_21'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_21'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_21'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_21'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_21'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_21'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_21'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_21']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_21'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_21'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_21'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_21'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_21']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle21))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_21'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_21'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_21'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation21'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_21'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID22']) && $_POST['ItemID22'] != 0)
{
	echo '<input type="hidden" name="ItemID22" value="' . $_POST['ItemID22'] . '">';
	echo '<input type="hidden" name="ItemStockLocation22" value="' . $_POST['ItemStockLocation22'] . '">';
	echo '<input type="hidden" name="ItemQty22" value="' . $_POST['ItemQty22'] . '">';
	echo '<input type="hidden" name="ItemPrice22" value="' . $_POST['ItemPrice22'] . '">';
	echo '<input type="hidden" name="ItemFree22" value="' . $_POST['ItemFree22'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName22 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice22'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty22'];
	if ($ItemFree22 > 0)
    {
     if ($_POST['ItemID22'] == '124' || $_POST['ItemID22'] == '139' || $_POST['ItemID22'] == '141' || $_POST['ItemID22'] == '142')
     {
         echo ' + ' . $ItemFree22 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree22 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice22'] * $_POST['ItemQty22'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_22'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_22'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_22'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_22'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_22'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_22'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_22'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_22']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_22'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_22'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_22'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_22'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_22']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle22))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_22'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_22'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_22'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation22'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_22'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID23']) && $_POST['ItemID23'] != 0)
{
	echo '<input type="hidden" name="ItemID23" value="' . $_POST['ItemID23'] . '">';
	echo '<input type="hidden" name="ItemStockLocation23" value="' . $_POST['ItemStockLocation23'] . '">';
	echo '<input type="hidden" name="ItemQty23" value="' . $_POST['ItemQty23'] . '">';
	echo '<input type="hidden" name="ItemPrice23" value="' . $_POST['ItemPrice23'] . '">';
	echo '<input type="hidden" name="ItemFree23" value="' . $_POST['ItemFree23'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName23 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice23'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty23'];
	if ($ItemFree23 > 0)
    {
     if ($_POST['ItemID23'] == '124' || $_POST['ItemID23'] == '139' || $_POST['ItemID23'] == '141' || $_POST['ItemID23'] == '142')
     {
         echo ' + ' . $ItemFree23 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree23 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice23'] * $_POST['ItemQty23'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_23'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_23'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_23'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_23'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_23'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_23'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_23'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_23']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_23'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_23'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_23'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_23'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_23']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle23))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_23'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_23'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_23'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation23'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_23'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID24']) && $_POST['ItemID24'] != 0)
{
	echo '<input type="hidden" name="ItemID24" value="' . $_POST['ItemID24'] . '">';
	echo '<input type="hidden" name="ItemStockLocation24" value="' . $_POST['ItemStockLocation24'] . '">';
	echo '<input type="hidden" name="ItemQty24" value="' . $_POST['ItemQty24'] . '">';
	echo '<input type="hidden" name="ItemPrice24" value="' . $_POST['ItemPrice24'] . '">';
	echo '<input type="hidden" name="ItemFree24" value="' . $_POST['ItemFree24'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName24 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice24'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty24'];
	if ($ItemFree24 > 0)
    {
     if ($_POST['ItemID24'] == '124' || $_POST['ItemID24'] == '139' || $_POST['ItemID24'] == '141' || $_POST['ItemID24'] == '142')
     {
         echo ' + ' . $ItemFree24 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree24 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice24'] * $_POST['ItemQty24'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_24'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_24'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_24'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_24'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_24'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_24'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_24'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_24']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_24'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_24'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_24'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_24'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_24']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle24))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_24'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_24'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_24'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation24'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_24'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID25']) && $_POST['ItemID25'] != 0)
{
	echo '<input type="hidden" name="ItemID25" value="' . $_POST['ItemID25'] . '">';
	echo '<input type="hidden" name="ItemStockLocation25" value="' . $_POST['ItemStockLocation25'] . '">';
	echo '<input type="hidden" name="ItemQty25" value="' . $_POST['ItemQty25'] . '">';
	echo '<input type="hidden" name="ItemPrice25" value="' . $_POST['ItemPrice25'] . '">';
	echo '<input type="hidden" name="ItemFree25" value="' . $_POST['ItemFree25'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName25 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice25'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty25'];
	if ($ItemFree25 > 0)
    {
     if ($_POST['ItemID25'] == '124' || $_POST['ItemID25'] == '139' || $_POST['ItemID25'] == '141' || $_POST['ItemID25'] == '142')
     {
         echo ' + ' . $ItemFree25 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree25 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice25'] * $_POST['ItemQty25'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_25'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_25'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_25'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_25'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_25'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_25'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_25'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode251'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_25']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_25'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_25'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_25'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_25'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_25']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle25))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_25'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_25'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_25'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation25'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_25'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID26']) && $_POST['ItemID26'] != 0)
{
	echo '<input type="hidden" name="ItemID26" value="' . $_POST['ItemID26'] . '">';
	echo '<input type="hidden" name="ItemStockLocation26" value="' . $_POST['ItemStockLocation26'] . '">';
	echo '<input type="hidden" name="ItemQty26" value="' . $_POST['ItemQty26'] . '">';
	echo '<input type="hidden" name="ItemPrice26" value="' . $_POST['ItemPrice26'] . '">';
	echo '<input type="hidden" name="ItemFree26" value="' . $_POST['ItemFree26'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName26 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice26'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty26'];
	if ($ItemFree26 > 0)
    {
     if ($_POST['ItemID26'] == '124' || $_POST['ItemID26'] == '139' || $_POST['ItemID26'] == '141' || $_POST['ItemID26'] == '142')
     {
         echo ' + ' . $ItemFree26 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree26 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice26'] * $_POST['ItemQty26'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_26'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_26'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_26'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_26'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_26'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_26'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_26'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode261'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_26']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_26'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_26'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_26'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_26'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_26']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle26))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_26'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_26'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_26'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation26'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_26'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID27']) && $_POST['ItemID27'] != 0)
{
	echo '<input type="hidden" name="ItemID27" value="' . $_POST['ItemID27'] . '">';
	echo '<input type="hidden" name="ItemStockLocation27" value="' . $_POST['ItemStockLocation27'] . '">';
	echo '<input type="hidden" name="ItemQty27" value="' . $_POST['ItemQty27'] . '">';
	echo '<input type="hidden" name="ItemPrice27" value="' . $_POST['ItemPrice27'] . '">';
	echo '<input type="hidden" name="ItemFree27" value="' . $_POST['ItemFree27'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName27 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice27'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty27'];
	if ($ItemFree27 > 0)
    {
     if ($_POST['ItemID27'] == '124' || $_POST['ItemID27'] == '139' || $_POST['ItemID27'] == '141' || $_POST['ItemID27'] == '142')
     {
         echo ' + ' . $ItemFree27 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree27 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice27'] * $_POST['ItemQty27'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_27'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_27'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_27'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_27'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_27'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_27'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_27'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode271'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_27']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_27'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_27'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_27'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_27'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_27']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle27))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_27'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_27'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_27'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation27'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_27'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID28']) && $_POST['ItemID28'] != 0)
{
	echo '<input type="hidden" name="ItemID28" value="' . $_POST['ItemID28'] . '">';
	echo '<input type="hidden" name="ItemStockLocation28" value="' . $_POST['ItemStockLocation28'] . '">';
	echo '<input type="hidden" name="ItemQty28" value="' . $_POST['ItemQty28'] . '">';
	echo '<input type="hidden" name="ItemPrice28" value="' . $_POST['ItemPrice28'] . '">';
	echo '<input type="hidden" name="ItemFree28" value="' . $_POST['ItemFree28'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName28 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice28'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty28'];
	if ($ItemFree28 > 0)
    {
     if ($_POST['ItemID28'] == '124' || $_POST['ItemID28'] == '139' || $_POST['ItemID28'] == '141' || $_POST['ItemID28'] == '142')
     {
         echo ' + ' . $ItemFree28 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree28 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice28'] * $_POST['ItemQty28'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_28'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_28'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_28'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_28'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_28'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_28'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_28'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode281'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_28']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_28'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_28'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_28'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_28'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_28']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle28))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_28'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_28'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_28'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation28'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_28'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID29']) && $_POST['ItemID29'] != 0)
{
	echo '<input type="hidden" name="ItemID29" value="' . $_POST['ItemID29'] . '">';
	echo '<input type="hidden" name="ItemStockLocation29" value="' . $_POST['ItemStockLocation29'] . '">';
	echo '<input type="hidden" name="ItemQty29" value="' . $_POST['ItemQty29'] . '">';
	echo '<input type="hidden" name="ItemPrice29" value="' . $_POST['ItemPrice29'] . '">';
	echo '<input type="hidden" name="ItemFree29" value="' . $_POST['ItemFree29'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName29 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice29'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty29'];
	if ($ItemFree29 > 0)
    {
     if ($_POST['ItemID29'] == '124' || $_POST['ItemID29'] == '139' || $_POST['ItemID29'] == '141' || $_POST['ItemID29'] == '142')
     {
         echo ' + ' . $ItemFree29 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree29 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice29'] * $_POST['ItemQty29'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_29'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_29'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_29'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_29'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_29'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_29'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_29'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode291'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_29']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_29'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_29'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_29'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_29'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_29']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle29))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_29'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_29'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_29'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation29'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_29'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID30']) && $_POST['ItemID30'] != 0)
{
	echo '<input type="hidden" name="ItemID30" value="' . $_POST['ItemID30'] . '">';
	echo '<input type="hidden" name="ItemStockLocation30" value="' . $_POST['ItemStockLocation30'] . '">';
	echo '<input type="hidden" name="ItemQty30" value="' . $_POST['ItemQty30'] . '">';
	echo '<input type="hidden" name="ItemPrice30" value="' . $_POST['ItemPrice30'] . '">';
	echo '<input type="hidden" name="ItemFree30" value="' . $_POST['ItemFree30'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName30 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice30'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty30'];
	if ($ItemFree30 > 0)
    {
     if ($_POST['ItemID30'] == '124' || $_POST['ItemID30'] == '139' || $_POST['ItemID30'] == '141' || $_POST['ItemID30'] == '142')
     {
         echo ' + ' . $ItemFree30 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree30 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice30'] * $_POST['ItemQty30'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_30'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_30'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_30'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_30'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_30'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_30'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_30'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode301'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_30']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_30'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_30'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_30'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_30'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_30']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle30))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_30'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_30'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_30'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation30'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_30'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID31']) && $_POST['ItemID31'] != 0)
{
	echo '<input type="hidden" name="ItemID31" value="' . $_POST['ItemID31'] . '">';
	echo '<input type="hidden" name="ItemStockLocation31" value="' . $_POST['ItemStockLocation31'] . '">';
	echo '<input type="hidden" name="ItemQty31" value="' . $_POST['ItemQty31'] . '">';
	echo '<input type="hidden" name="ItemPrice31" value="' . $_POST['ItemPrice31'] . '">';
	echo '<input type="hidden" name="ItemFree31" value="' . $_POST['ItemFree31'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName31 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice31'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty31'];
	if ($ItemFree31 > 0)
    {
     if ($_POST['ItemID31'] == '124' || $_POST['ItemID31'] == '139' || $_POST['ItemID31'] == '141' || $_POST['ItemID31'] == '142')
     {
         echo ' + ' . $ItemFree31 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree31 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice31'] * $_POST['ItemQty31'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_31'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_31'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_31'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_31'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_31'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_31'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_31'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode311'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_31']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_31'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_31'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_31'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_31'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_31']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle31))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_31'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_31'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_31'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation31'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_31'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID32']) && $_POST['ItemID32'] != 0)
{
	echo '<input type="hidden" name="ItemID32" value="' . $_POST['ItemID32'] . '">';
	echo '<input type="hidden" name="ItemStockLocation32" value="' . $_POST['ItemStockLocation32'] . '">';
	echo '<input type="hidden" name="ItemQty32" value="' . $_POST['ItemQty32'] . '">';
	echo '<input type="hidden" name="ItemPrice32" value="' . $_POST['ItemPrice32'] . '">';
	echo '<input type="hidden" name="ItemFree32" value="' . $_POST['ItemFree32'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName32 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice32'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty32'];
	if ($ItemFree32 > 0)
    {
     if ($_POST['ItemID32'] == '124' || $_POST['ItemID32'] == '139' || $_POST['ItemID32'] == '141' || $_POST['ItemID32'] == '142')
     {
         echo ' + ' . $ItemFree32 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree32 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice32'] * $_POST['ItemQty32'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_32'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_32'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_32'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_32'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_32'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_32'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_32'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode321'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_32']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_32'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_32'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_32'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_32'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_32']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle32))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_32'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_32'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_32'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation32'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_32'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID33']) && $_POST['ItemID33'] != 0)
{
	echo '<input type="hidden" name="ItemID33" value="' . $_POST['ItemID33'] . '">';
	echo '<input type="hidden" name="ItemStockLocation33" value="' . $_POST['ItemStockLocation33'] . '">';
	echo '<input type="hidden" name="ItemQty33" value="' . $_POST['ItemQty33'] . '">';
	echo '<input type="hidden" name="ItemPrice33" value="' . $_POST['ItemPrice33'] . '">';
	echo '<input type="hidden" name="ItemFree33" value="' . $_POST['ItemFree33'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName33 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice33'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty33'];
	if ($ItemFree33 > 0)
    {
     if ($_POST['ItemID33'] == '124' || $_POST['ItemID33'] == '139' || $_POST['ItemID33'] == '141' || $_POST['ItemID33'] == '142')
     {
         echo ' + ' . $ItemFree33 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree33 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice33'] * $_POST['ItemQty33'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_33'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_33'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_33'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_33'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_33'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_33'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_33'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode331'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_33']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_33'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_33'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_33'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_33'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_33']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle33))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_33'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_33'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_33'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation33'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_33'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID34']) && $_POST['ItemID34'] != 0)
{
	echo '<input type="hidden" name="ItemID34" value="' . $_POST['ItemID34'] . '">';
	echo '<input type="hidden" name="ItemStockLocation34" value="' . $_POST['ItemStockLocation34'] . '">';
	echo '<input type="hidden" name="ItemQty34" value="' . $_POST['ItemQty34'] . '">';
	echo '<input type="hidden" name="ItemPrice34" value="' . $_POST['ItemPrice34'] . '">';
	echo '<input type="hidden" name="ItemFree34" value="' . $_POST['ItemFree34'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName34 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice34'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty34'];
	if ($ItemFree34 > 0)
    {
     if ($_POST['ItemID34'] == '124' || $_POST['ItemID34'] == '139' || $_POST['ItemID34'] == '141' || $_POST['ItemID34'] == '142')
     {
         echo ' + ' . $ItemFree34 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree34 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice34'] * $_POST['ItemQty34'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_34'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_34'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_34'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_34'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_34'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_34'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_34'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode341'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_34']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_34'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_34'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_34'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_34'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_34']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle34))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_34'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_34'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_34'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation34'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_34'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID35']) && $_POST['ItemID35'] != 0)
{
	echo '<input type="hidden" name="ItemID35" value="' . $_POST['ItemID35'] . '">';
	echo '<input type="hidden" name="ItemStockLocation35" value="' . $_POST['ItemStockLocation35'] . '">';
	echo '<input type="hidden" name="ItemQty35" value="' . $_POST['ItemQty35'] . '">';
	echo '<input type="hidden" name="ItemPrice35" value="' . $_POST['ItemPrice35'] . '">';
	echo '<input type="hidden" name="ItemFree35" value="' . $_POST['ItemFree35'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName35 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice35'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty35'];
	if ($ItemFree35 > 0)
    {
     if ($_POST['ItemID35'] == '124' || $_POST['ItemID35'] == '139' || $_POST['ItemID35'] == '141' || $_POST['ItemID35'] == '142')
     {
         echo ' + ' . $ItemFree35 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree35 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice35'] * $_POST['ItemQty35'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_35'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_35'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_35'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_35'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_35'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_35'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_35'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode351'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_35']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_35'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_35'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_35'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_35'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_35']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle35))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_35'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_35'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_35'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation35'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_35'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID36']) && $_POST['ItemID36'] != 0)
{
	echo '<input type="hidden" name="ItemID36" value="' . $_POST['ItemID36'] . '">';
	echo '<input type="hidden" name="ItemStockLocation36" value="' . $_POST['ItemStockLocation36'] . '">';
	echo '<input type="hidden" name="ItemQty36" value="' . $_POST['ItemQty36'] . '">';
	echo '<input type="hidden" name="ItemPrice36" value="' . $_POST['ItemPrice36'] . '">';
	echo '<input type="hidden" name="ItemFree36" value="' . $_POST['ItemFree36'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName36 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice36'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty36'];
	if ($ItemFree36 > 0)
    {
     if ($_POST['ItemID36'] == '124' || $_POST['ItemID36'] == '139' || $_POST['ItemID36'] == '141' || $_POST['ItemID36'] == '142')
     {
         echo ' + ' . $ItemFree36 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree36 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice36'] * $_POST['ItemQty36'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_36'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_36'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_36'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_36'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_36'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_36'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_36'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode361'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_36']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_36'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_36'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_36'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_36'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_36']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle36))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_36'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_36'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_36'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation36'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_36'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID37']) && $_POST['ItemID37'] != 0)
{
	echo '<input type="hidden" name="ItemID37" value="' . $_POST['ItemID37'] . '">';
	echo '<input type="hidden" name="ItemStockLocation37" value="' . $_POST['ItemStockLocation37'] . '">';
	echo '<input type="hidden" name="ItemQty37" value="' . $_POST['ItemQty37'] . '">';
	echo '<input type="hidden" name="ItemPrice37" value="' . $_POST['ItemPrice37'] . '">';
	echo '<input type="hidden" name="ItemFree37" value="' . $_POST['ItemFree37'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName37 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice37'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty37'];
	if ($ItemFree37 > 0)
    {
     if ($_POST['ItemID37'] == '124' || $_POST['ItemID37'] == '139' || $_POST['ItemID37'] == '141' || $_POST['ItemID37'] == '142')
     {
         echo ' + ' . $ItemFree37 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree37 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice37'] * $_POST['ItemQty37'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_37'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_37'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_37'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_37'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_37'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_37'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_37'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode371'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_37']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_37'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_37'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_37'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_37'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_37']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle37))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_37'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_37'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_37'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation37'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_37'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID38']) && $_POST['ItemID38'] != 0)
{
	echo '<input type="hidden" name="ItemID38" value="' . $_POST['ItemID38'] . '">';
	echo '<input type="hidden" name="ItemStockLocation38" value="' . $_POST['ItemStockLocation38'] . '">';
	echo '<input type="hidden" name="ItemQty38" value="' . $_POST['ItemQty38'] . '">';
	echo '<input type="hidden" name="ItemPrice38" value="' . $_POST['ItemPrice38'] . '">';
	echo '<input type="hidden" name="ItemFree38" value="' . $_POST['ItemFree38'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName38 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice38'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty38'];
	if ($ItemFree38 > 0)
    {
     if ($_POST['ItemID38'] == '124' || $_POST['ItemID38'] == '139' || $_POST['ItemID38'] == '141' || $_POST['ItemID38'] == '142')
     {
         echo ' + ' . $ItemFree38 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree38 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice38'] * $_POST['ItemQty38'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_38'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_38'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_38'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_38'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_38'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_38'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_38'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode381'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_38']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_38'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_38'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_38'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_38'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_38']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle38))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_38'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_38'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_38'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation38'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_38'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID39']) && $_POST['ItemID39'] != 0)
{
	echo '<input type="hidden" name="ItemID39" value="' . $_POST['ItemID39'] . '">';
	echo '<input type="hidden" name="ItemStockLocation39" value="' . $_POST['ItemStockLocation39'] . '">';
	echo '<input type="hidden" name="ItemQty39" value="' . $_POST['ItemQty39'] . '">';
	echo '<input type="hidden" name="ItemPrice39" value="' . $_POST['ItemPrice39'] . '">';
	echo '<input type="hidden" name="ItemFree39" value="' . $_POST['ItemFree39'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName39 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice39'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty39'];
	if ($ItemFree39 > 0)
    {
     if ($_POST['ItemID39'] == '124' || $_POST['ItemID39'] == '139' || $_POST['ItemID39'] == '141' || $_POST['ItemID39'] == '142')
     {
         echo ' + ' . $ItemFree39 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree39 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice39'] * $_POST['ItemQty39'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_39'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_39'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_39'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_39'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_39'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_39'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_39'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode391'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_39']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_39'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_39'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_39'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_39'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_39']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle39))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_39'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_39'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_39'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation39'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_39'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

if (isset($_POST['ItemID40']) && $_POST['ItemID40'] != 0)
{
	echo '<input type="hidden" name="ItemID40" value="' . $_POST['ItemID40'] . '">';
	echo '<input type="hidden" name="ItemStockLocation40" value="' . $_POST['ItemStockLocation40'] . '">';
	echo '<input type="hidden" name="ItemQty40" value="' . $_POST['ItemQty40'] . '">';
	echo '<input type="hidden" name="ItemPrice40" value="' . $_POST['ItemPrice40'] . '">';
	echo '<input type="hidden" name="ItemFree40" value="' . $_POST['ItemFree40'] . '">';
	echo '<tr>';
	echo '<td>' . $strProductName40 . '</td>';
	echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice40'], 2, '.', '') . '</td>';
    echo '<td>' . $_POST['ItemQty40'];
	if ($ItemFree40 > 0)
    {
     if ($_POST['ItemID40'] == '124' || $_POST['ItemID40'] == '139' || $_POST['ItemID40'] == '141' || $_POST['ItemID40'] == '142')
     {
         echo ' + ' . $ItemFree40 . ' Free Revitalash';
     }
     else
     {
         echo ' + ' . $ItemFree40 . ' FREE';
     }
    }
    echo '</td>';
	echo '<td>$' . number_format($_POST['ItemPrice40'] * $_POST['ItemQty40'], 2, '.', '') . '</td>';
	echo '</tr>';
	if($_SESSION['BreastCancerAugOct2009_40'] >= 12)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_40'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>Mascara by Revitalash&reg;</td>';
		echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['BreastCancerAugOct2009_40'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['HolidayGiftBoxSet2009_40'] > 0)
	{
		echo '<tr>';
		echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
		echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '<td>' . $_SESSION['HolidayGiftBoxSet2009_40'] . '</td>';
		echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
		echo '</tr>';
	}
	if($_SESSION['ValentinesDay2010_40'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Candle Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Compact Mirror Gift</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
            echo '<tr>';
	        echo '<td>Valentine Day Promotion Bag</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['ValentinesDay2010_40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
	if($_SESSION['TradeShowFamilyBundle2010_40'] > 0)
	{
	    echo '<tr>';
	    echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitalash&reg; Hair</td>';
        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>Revitabrow</td>';
        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '</tr>';
        if($_SESSION['TradeShowFamilyBundleCode401'] == 1)
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*10 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
        else
        {
            echo '<tr>';
            echo '<td>Mascara Raven</td>';
            echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Mascara Espresso</td>';
            echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['TradeShowFamilyBundle2010_40']*5 . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }
	if($_SESSION['MothersDay2010_40'] > 0)
	{
        if ($blnIsInternational == 0)
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_40'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
        else
        {
	        echo '<tr>';
	        echo '<td>Revitalash&reg; Eyelash Conditioner FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_40'] . '</td>';
            echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '</tr>';
            echo '<tr>';
	        echo '<td>Mascara FR-Canadian</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $_SESSION['MothersDay2010_40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	        echo '</tr>';
        }
    }
    if($_SESSION['Bundles2010_40'] != '')
    {
        $sess_values = explode("~", $_SESSION['Bundles2010_40']);
        while($rowGetBundle = mssql_fetch_array($qryCartBundle40))
        {
	        echo '<tr>';
	        echo '<td>' . $rowGetBundle['Description'] . '</td>';
	        echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	        echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
            echo '<td>' . $rowGetBundle['Qty'] * $sess_values[1] . '</td>';
            echo '<td>$' . number_format(0 * $rowGetBundle['Qty'] * $sess_values[1], 2, '.', '') . '</td>';
            echo '</tr>';
        }
    }

    // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
    if($_SESSION['Regenesis_2286_40'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Conditioner</td>';
	    echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2286_40'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}

    if($_SESSION['Regenesis_2285_40'] > 0)
	{
	    echo '<tr>';
	    echo '<td>ReGenesis Back Bar Shampoo</td>';
	    echo '<td>' . $_POST['ItemStockLocation40'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
        echo '<td>' . $_SESSION['Regenesis_2285_40'] . '</td>';
	    echo '<td>$' . number_format(0, 2, '.', '') . '</td>';
	    echo '</tr>';
	}
}

?>

<tr><td colspan="5">&nbsp;</td></tr>

<input type="hidden" name="ShippingNotes" value="<?php echo $_POST['ShippingNotes']; ?>" />

<tr>
	<td colspan="2" rowspan="6">Notes: <?php echo $_POST['ShippingNotes']; ?></td>

<?php

  // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
  // GMC - 09/05/09 - Promotion Section - Drop Down for CSR's Only
  if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0) && ($intCustomerType == 2) && ($_SESSION['Summer2012FineLinePrimer'] == 0))
  {
    echo '<th colspan="2" align="right"><font color="red">Less ' . ($_SESSION['Promo_Code_Discount'] * 100) . '% Discount ' . $_SESSION['Promo_Code_Description'] . '</font></th>';
    echo '<td>';
    echo '<font color="red">- $' . number_format(($discountValue), 2, '.', '') . '</font>';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th colspan="2" align="right">Subtotal:</th>';
    echo '<td>';
    echo '$' . number_format($decSubtotal, 2, '.', '');
    echo '</td>';
  }
  else
  {
    echo '<th colspan="2" align="right">Subtotal:</th>';
    echo '<td>';
    echo '$' . number_format($decSubtotal, 2, '.', '');
    echo '</td>';
  }

?>

</tr>

<tr>

   <!-- GMC - 12/03/08 - Domestic Vs. International 3rd Phase -->
    <?php

    if ($blnIsInternational == 0)
    {
       echo '<th colspan="2" align="right">Sales Tax - VAT:</th>';
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
            echo '<th colspan="2" align="right">VAT (GBP):</th>';
        }
        else
        {
            echo '<th colspan="2" align="right">VAT (EUR):</th>';
        }
      }
      else
      {
          echo '<th colspan="2" align="right">Sales Tax:</th>';
      }
    }
    ?>

    <td>
     <?php

     // GMC - 12/22/08 - NO CHARGE - Then No Shipping and No handling Values
     if ($_SESSION['PaymentType'] == 'NOCHARGE')
     {
         $_SESSION['OrderTax'] = 0;
         echo '$' . number_format($_SESSION['OrderTax'], 2, '.', '');
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
                 echo '£' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']), 2, '.', '');
             }
             else
             {
                 echo '€' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']), 2, '.', '');
             }
           }
           else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
           {
             if($_SESSION['CountryCodeFedExEu'] == 'GB')
             {
                 // GMC - 07/20/10 - Reseller EU has reseller number no VAT charge
                 // echo '£' . number_format(0, 2, '.', '');
                 echo '£' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']), 2, '.', '');
             }
             else
             {
                 // GMC - 07/20/10 - Reseller EU has reseller number no VAT charge
                 // echo '€' . number_format(0, 2, '.', '');
                 echo '€' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']), 2, '.', '');
             }
           }
           else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
           {
             if($_SESSION['CountryCodeFedExEu'] == 'GB')
             {
                 echo '£' . number_format(0, 2, '.', '');
             }
             else
             {
                 echo '€' . number_format(0, 2, '.', '');
             }
           }
           else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
           {
             if($_SESSION['CountryCodeFedExEu'] == 'GB')
             {
                 echo '£' . number_format(0, 2, '.', '');
             }
             else
             {
                 echo '€' . number_format(0, 2, '.', '');
             }
           }
         }
         else
         {
             echo '$' . number_format($_SESSION['OrderTax'], 2, '.', '');
         }
     }

     ?>
     </td>
</tr>

<tr>

    <?php

      // GMC - 01/26/11 - To display the Total Box Count and Total Order Weight
      echo '<td><font color="red"><b>Total Box Count: ' . $_SESSION["TotalBoxCount"] . ' , Total Order Weight: ' . $_SESSION["OrderWeight"] + 1.2 . ' lbs.</b></font></td>';

    ?>

</tr>

<tr>
	<th colspan="2" align="right">Shipping:</th>
    <td>
    <?php

    // GMC - 11/06/08 - To accomodate the International Shipping in CSR/ADMIN
    if ($blnIsInternational == 0)
    {
        // GMC - 05/21/15 - Include LTL Economy - Priority Quote Drop Down Selection
        // if($_SESSION['UserTypeID'] == 4 && $_POST['ShipMethodID'] == 889)
        if($_POST['ShipMethodID'] == 889)
        {
            $strShippingMethod = "LTL Economy Quote";
            echo $strShippingMethod;
        }
        // else if($_SESSION['UserTypeID'] == 4 && $_POST['ShipMethodID'] == 890)
        else if($_POST['ShipMethodID'] == 890)
        {
            $strShippingMethod = "LTL Priority Quote";
            echo $strShippingMethod;
        }
        else
        {
            echo $strShippingMethod;
        }
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
            // Find which FedEx EU Charge
            if($_POST['ShipMethodID'] == 208)
            {
                $strShippingMethod = 'FedEx EU Ground';
                $_SESSION['ShipMethodIDFedExEu'] =  $strShippingMethod;
                echo $strShippingMethod;
            }
            else if($_POST['ShipMethodID'] == 209)
            {
                $strShippingMethod = 'FedEx EU Standard';
                $_SESSION['ShipMethodIDFedExEu'] =  $strShippingMethod;
                echo $strShippingMethod;
            }
            else if($_POST['ShipMethodID'] == 210)
            {
                $strShippingMethod = 'FedEx EU Express';
                $_SESSION['ShipMethodIDFedExEu'] =  $strShippingMethod;
                echo $strShippingMethod;
            }
          }
        }
        else
        {
            // GMC - 05/21/15 - Include LTL Economy - Priority Quote Drop Down Selection
            // if($_SESSION['UserTypeID'] == 4 && $_POST['ShipMethodID'] == 889)
            if($_POST['ShipMethodID'] == 889)
            {
                $strShippingMethod = "LTL Economy Quote";
                echo $strShippingMethod;
            }
            // else if($_SESSION['UserTypeID'] == 4 && $_POST['ShipMethodID'] == 890)
            else if($_POST['ShipMethodID'] == 890)
            {
                $strShippingMethod = "LTL Priority Quote";
                echo $strShippingMethod;
            }
            else
            {
               $strShippingMethod = 'International';
               echo $strShippingMethod;
            }
        }
    }
    
    ?>
    </td>
</tr>

<tr>
	<th colspan="2" align="right">Shipping and Handling Cost:</th>
    <td>$

    <?php

    // GMC - 07/27/09 - Shipping and Handling Combined
    if ($blnIsInternational == 0)
    {
       if (isset($_POST['IsFreeShipping']) || $_POST['ShipMethodID'] == 99)
       {
           $_SESSION['OrderShipping'] = 0;
           $_SESSION['OrderHandling'] = 0;
           $_SESSION['IsFreeShipping'] = 'Ok';
           echo number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '');
       }
       elseif (isset($_POST['ShippingOverride']) && $_POST['ShippingOverride'] != '')
       {
          $_SESSION['ShippingOverride'] = 'Ok';
           echo number_format($_SESSION['OrderShipping'], 2, '.', '');
       }
       elseif ($_SESSION['PaymentType'] == 'NOCHARGE')
       {
          $_SESSION['OrderShipping'] = 0;
          $_SESSION['OrderHandling'] = 0;
          echo number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '');
       }

       // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
       elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
       {
          $_SESSION['OrderShipping'] = 0;
          $_SESSION['OrderHandling'] = 0;
          echo number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '');
       }

       // GMC - 05/21/15 - Include LTL Economy - Priority Quote Drop Down Selection
       // else if($_SESSION['UserTypeID'] == 4 && ($_POST['ShipMethodID'] == 889 || $_POST['ShipMethodID'] == 890))
       else if($_POST['ShipMethodID'] == 889 || $_POST['ShipMethodID'] == 890)
       {
          $_SESSION['OrderShipping'] = 0;
          $_SESSION['OrderHandling'] = 0;
          echo number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '');
       }

       else
       {
          echo number_format($_SESSION['OrderShipping'] , 2, '.', '');
       }
    }
    else
    {
        if (isset($_POST['IsFreeShipping']) || $_POST['ShipMethodID'] == 99)
        {
           $_SESSION['OrderShipping'] = 0;
           $_SESSION['OrderHandling'] = 0;
           $_SESSION['IsFreeShipping'] = 'Ok';
           echo number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '');
        }
        elseif (isset($_POST['ShippingOverride']) && $_POST['ShippingOverride'] != '')
        {
           $_SESSION['ShippingOverride'] = 'Ok';
           echo number_format($_SESSION['OrderShipping'], 2, '.', '');
        }
        elseif ($_SESSION['PaymentType'] == 'NOCHARGE')
        {
            $_SESSION['OrderShipping'] = 0;
            $_SESSION['OrderHandling'] = 0;
            echo number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '');
        }
        elseif ($_POST['ShipMethodID'] == 999)
        {
            $_SESSION['OrderShipping'] = 0;
            $_SESSION['OrderHandling'] = 0;
            echo number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '');
        }

       // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
       elseif ($_SESSION['Transportation_Charges'] != '' && $_SESSION['Transportation_Charges_Value'] != '')
       {
          $_SESSION['OrderShipping'] = 0;
          $_SESSION['OrderHandling'] = 0;
          echo number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '');
       }

       // GMC - 05/21/15 - Include LTL Economy - Priority Quote Drop Down Selection
       // else if($_SESSION['UserTypeID'] == 4 && ($_POST['ShipMethodID'] == 889 || $_POST['ShipMethodID'] == 890))
       else if($_POST['ShipMethodID'] == 889 || $_POST['ShipMethodID'] == 890)
       {
          $_SESSION['OrderShipping'] = 0;
          $_SESSION['OrderHandling'] = 0;
          echo number_format($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'], 2, '.', '');
       }

        else
        {
            echo number_format($_SESSION['OrderShipping'] , 2, '.', '');
        }
    }

    ?>

    </td>
</tr>

<tr>
	<th colspan="2" align="right">Total:</th>
    <td>$
     <?php

     // GMC - 12/22/08 - NO CHARGE - Then No Shipping and No handling Values
     if ($_SESSION['PaymentType'] == 'NOCHARGE')
     {
        $_SESSION['OrderTotal'] = 0;
        echo number_format($_SESSION['OrderTotal'], 2, '.', '');
     }
     else
     {
        echo number_format($_SESSION['OrderTotal'], 2, '.', '');
     }

     ?>
    </td>
</tr>

<tr><td colspan="5">&nbsp;</td></tr>

<!-- GMC - 07/14/11 - Distributors Change CSRADMIN -->
<?php

if ($blnIsTransChg == 1)
{
    unset($_POST['ShipMethodID']);

    // GMC - 04/25/12 - Change context Error Message Shipping Method
    // echo "<tr><td><div align='center'><font color='red'><strong>SHIPPING METHOD IS NOT ALLOWED WHEN A TRANSPORTATION CHARGES OTHER THAN --SHIPPED-- IS SELECTED.</strong></font></div></td></tr>";
    echo "<tr><td><div align='center'><font color='red'><strong>INVALID SHIPPING METHOD SELECTED - SELECT A VALID SHIP METHOD TO CONTINUE.</strong></font></div></td></tr>";

    echo "<tr><td colspan='5'>&nbsp;</td></tr>";
	echo '<tr><td><a href="customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '">Go Back to New Order</a></td></tr>';
}
else
{
    echo '<tr><td colspan="5">By placing this order, you verify the information is correct and acknowledge that this order will be processed immediately.</td></tr><tr><td colspan="5"><input type="submit" name="cmdProcess" value="Place Order" class="formSubmit" /></td></tr>';
}
?>

</table>

<!-- GMC - 06/20/13 - Back buttons for some pages -->
<table width="100%" cellpadding="0">
    <tr>
        <td width="625"></td>
        <td width="*"><a href="/csradmin/customers.php"><font color="red">Return to Customer Management</font></a></td>
    </tr>
</table>

</form>

<p>&nbsp;</p>

<?php

// GMC - 12/22/08 - NO CHARGE - Then No Shipping and No handling Values
if ($_SESSION['PaymentType'] == 'NOCHARGE')
{
    $_SESSION['OrderShipping'] = 0;
    $_SESSION['OrderHandling'] = 0;
    // HACK
}
else if($_SESSION['IsFreeShipping'] == 'Ok')
{
    $_SESSION['OrderShipping'] = 0;
    $_SESSION['OrderHandling'] = 0;
    // HACK
}
else
{
    $_SESSION['OrderShipping'] = $_SESSION['OrderShipping'] - $_SESSION['OrderHandling'];
    // HACK
}

?>
