<?php

if (!isset($_SESSION['IsInternational']))
	$_SESSION['IsInternational'] = 0;

// CONNECT TO SQL SERVER DATABASE
$connProcess = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
mssql_select_db($dbName, $connProcess);

// INITIALIZE AND BIND PARAMETERS
if (isset($_SESSION['IsRevitalashLoggedIn']) && $_SESSION['IsRevitalashLoggedIn'] == 1)
{
	$intUserID = $_SESSION['UserID'];
}
else
{
    // GMC - 05/06/10 - Proper RecordIDs for Consumer-Reseller (Domestic and International)
    if($_SESSION['IsInternational'] == 0)
    {
        if($_SESSION["CustomerTypeID_SalesRepId"] == 1)
        {
	        $intUserID = 1;
        }
        else if($_SESSION["CustomerTypeID_SalesRepId"] == 2)
        {
	        $intUserID = 84;
        }
        else
        {
	        $intUserID = 1;
        }
    }
    else
    {
        if($_SESSION["CustomerTypeID_SalesRepId"] == 1)
        {
	        $intUserID = 125;
        }
        else if($_SESSION["CustomerTypeID_SalesRepId"] == 2)
        {
	        $intUserID = 128;
        }
        else
        {
	        $intUserID = 125;
        }
    }
}

$intCustomerID = $_SESSION['CustomerID'];
$strNull = '';

// GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
$Add_1022_If_967 = '';

// GMC - 04/05/11 - Add the tblCustomers_SalesRepId into the Customers Order Confirmation
$rsCusSalRepID = mssql_query("SELECT SalesRepID FROM tblCustomers WHERE RecordID = " . $intCustomerID);

while($rowCusSalRepID = mssql_fetch_array($rsCusSalRepID))
{
	$intCusSalRepID = $rowCusSalRepID["SalesRepID"];
}

$rsCusSalRepEmailID = mssql_query("SELECT EmailAddress FROM tblRevitalash_Users WHERE RecordID = " . $intCusSalRepID);

while($rowCusSalRepEmailID = mssql_fetch_array($rsCusSalRepEmailID))
{
    $_SESSION['CusSalRepEmailID'] = $rowCusSalRepEmailID["EmailAddress"];
}

// GMC - 02/02/10 - RevitalashIdInUserId_NAV_WebHeader
// GMC - 08/10/10 - Update Email Address on tblCustomers
$rsRevitalashID = mssql_query("SELECT RevitalashID, EmailAddress FROM tblRevitalash_Users WHERE RecordID = " . $intUserID);

while($rowRevitalashID = mssql_fetch_array($rsRevitalashID))
{
	$_SESSION['RevitalashID'] = $rowRevitalashID["RevitalashID"];
    $_SESSION['SalesRepIdEmailAddress'] = $rowRevitalashID["EmailAddress"];
}

// GET HANDLING RATE FROM DATABASE

// GMC - 04/02/09 - Activate Fedex Web Services (Domestic - International - Exclude Netherlands)
if($_SESSION['IsInternational'] == 0)
{
    $rsHandling = mssql_query("SELECT Handling FROM tblSite_Options WHERE RecordID = 1");
}
else
{
    $rsHandling = mssql_query("SELECT Handling FROM tblSite_Options WHERE RecordID = 2");
}

while($rowHandling = mssql_fetch_array($rsHandling))
{
	$_SESSION['OrderHandling'] = $rowHandling["Handling"];
}

// GMC - 10/24/10 - Bundles Project Oct 2010
// EXECUTE SQL QUERY
if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID1']);
	$qryCartBundle1_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID1']);
	$qryCartBundle1_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID1']);
}

if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle2 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID2']);
	$qryCartBundle2_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID2']);
	$qryCartBundle2_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID2']);
}

if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle3 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID3']);
	$qryCartBundle3_0 = mssql_query("SELECT BundleID,  NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID3']);
	$qryCartBundle3_1 = mssql_query("SELECT BundleID,  NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID3']);
}

if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle4 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID4']);
	$qryCartBundle4_0 = mssql_query("SELECT BundleID,  NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID4']);
	$qryCartBundle4_1 = mssql_query("SELECT BundleID,  NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID4']);
}

if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle5 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID5']);
	$qryCartBundle5_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID5']);
	$qryCartBundle5_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID5']);
}

if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle6 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID6']);
	$qryCartBundle6_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID6']);
	$qryCartBundle6_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID6']);
}

if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle7 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID7']);
	$qryCartBundle7_0 = mssql_query("SELECT BundleID,  NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID7']);
	$qryCartBundle7_1 = mssql_query("SELECT BundleID,  NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID7']);
}

if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle8 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID8']);
	$qryCartBundle8_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID8']);
	$qryCartBundle8_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID8']);
}

if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle9 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID9']);
	$qryCartBundle9_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID9']);
	$qryCartBundle9_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID9']);
}

if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle10 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID10']);
	$qryCartBundle10_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID10']);
	$qryCartBundle10_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID10']);
}

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle11 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID11']);
	$qryCartBundle11_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID11']);
	$qryCartBundle11_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID11']);
}

if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle12 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID12']);
	$qryCartBundle12_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID12']);
	$qryCartBundle12_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID12']);
}

if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle13 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID13']);
	$qryCartBundle13_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID13']);
	$qryCartBundle13_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID13']);
}

if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle14 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID14']);
	$qryCartBundle14_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID14']);
	$qryCartBundle14_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID14']);
}

if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle15 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID15']);
	$qryCartBundle15_0 = mssql_query("SELECT BundleID,NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID15']);
	$qryCartBundle15_1 = mssql_query("SELECT BundleID,NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID15']);
}

if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle16 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID16']);
	$qryCartBundle16_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID16']);
	$qryCartBundle16_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID16']);
}

if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle17 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID17']);
	$qryCartBundle17_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID17']);
	$qryCartBundle17_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID17']);
}

if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle18 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID18']);
	$qryCartBundle18_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID18']);
	$qryCartBundle18_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID18']);
}

if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle19 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID19']);
	$qryCartBundle19_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID19']);
	$qryCartBundle19_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID19']);
}

if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
{
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
	// $qryCartBundle20 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID20']);
	$qryCartBundle20_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID20']);
	$qryCartBundle20_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID20']);
}

// GMC - 10/16/13 - Add 20 Line Items Admin

if (isset($_POST['ItemID21']) && $_POST['ItemID21'] != 0)
{
	$qryCartBundle21_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID21']);
	$qryCartBundle21_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID21']);
}

if (isset($_POST['ItemID22']) && $_POST['ItemID22'] != 0)
{
	$qryCartBundle22_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID22']);
	$qryCartBundle22_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID22']);
}

if (isset($_POST['ItemID23']) && $_POST['ItemID23'] != 0)
{
	$qryCartBundle23_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID23']);
	$qryCartBundle23_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID23']);
}

if (isset($_POST['ItemID24']) && $_POST['ItemID24'] != 0)
{
	$qryCartBundle24_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID24']);
	$qryCartBundle24_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID24']);
}

if (isset($_POST['ItemID25']) && $_POST['ItemID25'] != 0)
{
	$qryCartBundle25_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID25']);
	$qryCartBundle25_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID25']);
}

if (isset($_POST['ItemID26']) && $_POST['ItemID26'] != 0)
{
	$qryCartBundle26_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID26']);
	$qryCartBundle26_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID26']);
}

if (isset($_POST['ItemID27']) && $_POST['ItemID27'] != 0)
{
	$qryCartBundle27_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID27']);
	$qryCartBundle27_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID27']);
}

if (isset($_POST['ItemID28']) && $_POST['ItemID28'] != 0)
{
	$qryCartBundle28_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID28']);
	$qryCartBundle28_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID28']);
}

if (isset($_POST['ItemID29']) && $_POST['ItemID29'] != 0)
{
	$qryCartBundle29_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID29']);
	$qryCartBundle29_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID29']);
}

if (isset($_POST['ItemID30']) && $_POST['ItemID30'] != 0)
{
	$qryCartBundle30_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID30']);
	$qryCartBundle30_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID30']);
}

if (isset($_POST['ItemID31']) && $_POST['ItemID31'] != 0)
{
	$qryCartBundle31_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID31']);
	$qryCartBundle31_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID31']);
}

if (isset($_POST['ItemID32']) && $_POST['ItemID32'] != 0)
{
	$qryCartBundle32_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID32']);
	$qryCartBundle32_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID32']);
}

if (isset($_POST['ItemID33']) && $_POST['ItemID33'] != 0)
{
	$qryCartBundle33_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID33']);
	$qryCartBundle33_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID33']);
}

if (isset($_POST['ItemID34']) && $_POST['ItemID34'] != 0)
{
	$qryCartBundle34_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID34']);
	$qryCartBundle34_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID34']);
}

if (isset($_POST['ItemID35']) && $_POST['ItemID35'] != 0)
{
	$qryCartBundle35_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID35']);
	$qryCartBundle35_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID35']);
}

if (isset($_POST['ItemID36']) && $_POST['ItemID36'] != 0)
{
	$qryCartBundle36_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID36']);
	$qryCartBundle36_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID36']);
}

if (isset($_POST['ItemID37']) && $_POST['ItemID37'] != 0)
{
	$qryCartBundle37_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID37']);
	$qryCartBundle37_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID37']);
}

if (isset($_POST['ItemID38']) && $_POST['ItemID38'] != 0)
{
	$qryCartBundle38_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID38']);
	$qryCartBundle38_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID38']);
}

if (isset($_POST['ItemID39']) && $_POST['ItemID39'] != 0)
{
	$qryCartBundle39_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID39']);
	$qryCartBundle39_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID39']);
}

if (isset($_POST['ItemID40']) && $_POST['ItemID40'] != 0)
{
	$qryCartBundle40_0 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID40']);
	$qryCartBundle40_1 = mssql_query("SELECT BundleID, NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID40']);
}

// GMC - 02/06/12 - Consumer Web Anti-Fraud Flags
// GMC - 02/14/12 - Only one flag in Consumer Web Anti-Fraud
// GMC - 01/07/14 - Web Anti-Fraud Flag only for web consumer
if($_SESSION['CustomerTypeID'] == 1 && isset($_SESSION['cart']))
{
    // if(($_SESSION['OrderSubtotal'] > 600) && ($_SESSION['ShippingMethod'] == 202) && ($_SESSION['ShipToZip'] <> $_SESSION['PaymentCC_BillingPostalCode']))
    if(($_SESSION['OrderSubtotal'] >= 600))
    {
        header("Location: https://secure.revitalash.com/modules/oiwcsp.php");
        // header("Location: http://localhost/modules/oiwcsp.php");
        exit();
    }
}

// ********** ADD PAYMENT METHOD TO DATABASE AND RETRIEVE KEY VALUE **********
// ADD PAYMENT METHOD INFORMATION TO DATABASE
$qryPayment = mssql_init("spOrders_SetPayment", $connProcess);
$intPaymentStatusCode = 0;

if ($_SESSION['PaymentType'] == 'CreditCard')
{
	$strPaymentType = $_SESSION['PaymentType'];
	$strCC_Number = $_SESSION['PaymentCC_Number'];
	$strCC_Cardholder = $_SESSION['PaymentCC_Cardholder'];
	$strCC_ExpMonth = $_SESSION['PaymentCC_ExpMonth'];
	$strCC_ExpYear = $_SESSION['PaymentCC_ExpYear'];
	$strCC_BillingPostalCode = $_SESSION['PaymentCC_BillingPostalCode'];

	mssql_bind($qryPayment, "@prmCustomerID", $intCustomerID, SQLINT4);
	mssql_bind($qryPayment, "@prmPaymentType", $strPaymentType, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_Number", $strCC_Number, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_Cardholder", $strCC_Cardholder, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_ExpMonth", $strCC_ExpMonth, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_ExpYear", $strCC_ExpYear, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_BillingPostalCode", $strCC_BillingPostalCode, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCK_AccountType", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankRouting", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankAccount", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "RETVAL", $intPaymentStatusCode, SQLINT4);
}
if ($_SESSION['PaymentType'] == 'ECheck')
{
	$strPaymentType = $_SESSION['PaymentType'];
	$strCK_AccountType = $_SESSION['PaymentCK_AccountType'];
	$strCK_BankName = $_SESSION['PaymentCK_BankName'];
	$strCK_AccountName = $_SESSION['PaymentCK_AccountName'];
	$strCK_BankRouting = $_SESSION['PaymentCK_BankRouting'];
	$strCK_BankAccount = $_SESSION['PaymentCK_BankAccount'];

	mssql_bind($qryPayment, "@prmCustomerID", $intCustomerID, SQLINT4);
	mssql_bind($qryPayment, "@prmPaymentType", $strPaymentType, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_Number", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_Cardholder", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpMonth", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpYear", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_BillingPostalCode", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountType", $strCK_AccountType, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCK_BankName", $strCK_BankName, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCK_AccountName", $strCK_AccountName, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCK_BankRouting", $strCK_BankRouting, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCK_BankAccount", $strCK_BankAccount, SQLVARCHAR);
	mssql_bind($qryPayment, "RETVAL", $intPaymentStatusCode, SQLINT4);
}
elseif ($_SESSION['PaymentType'] == 'Terms')
{
	$strPaymentType = $_SESSION['PaymentType'];

	mssql_bind($qryPayment, "@prmCustomerID", $intCustomerID, SQLINT4);
	mssql_bind($qryPayment, "@prmPaymentType", $strPaymentType, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_Number", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_Cardholder", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpMonth", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpYear", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_BillingPostalCode", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountType", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankRouting", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankAccount", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "RETVAL", $intPaymentStatusCode, SQLINT4);
}
elseif ($_SESSION['PaymentType'] == 'CreditCardSwiped')
{
	$strPaymentType = $_SESSION['PaymentType'];

	mssql_bind($qryPayment, "@prmCustomerID", $intCustomerID, SQLINT4);
	mssql_bind($qryPayment, "@prmPaymentType", $strPaymentType, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_Number", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_Cardholder", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpMonth", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpYear", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_BillingPostalCode", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountType", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankRouting", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankAccount", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "RETVAL", $intPaymentStatusCode, SQLINT4);
}
elseif ($_SESSION['PaymentType'] == 'NOCHARGE') // GMC - 10/31/08 - To accomodate the NOCHARGE Process -->
{
	$strPaymentType = $_SESSION['PaymentType'];

	mssql_bind($qryPayment, "@prmCustomerID", $intCustomerID, SQLINT4);
	mssql_bind($qryPayment, "@prmPaymentType", $strPaymentType, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_Number", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_Cardholder", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpMonth", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpYear", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_BillingPostalCode", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountType", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankRouting", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankAccount", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "RETVAL", $intPaymentStatusCode, SQLINT4);
}

//GMC - 02/20/09 - New Payment Types visible for CRSAdmins Only
elseif ($_SESSION['PaymentType'] == 'Check')
{
	$strPaymentType = $_SESSION['PaymentType'];
    $strCK_BankAccount = $_SESSION['PaymentCK_BankAccount'];

	mssql_bind($qryPayment, "@prmCustomerID", $intCustomerID, SQLINT4);
	mssql_bind($qryPayment, "@prmPaymentType", $strPaymentType, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_Number", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_Cardholder", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpMonth", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpYear", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_BillingPostalCode", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountType", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankRouting", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankAccount", $strCK_BankAccount, SQLVARCHAR);
	mssql_bind($qryPayment, "RETVAL", $intPaymentStatusCode, SQLINT4);
}
elseif ($_SESSION['PaymentType'] == 'Wire')
{
	$strPaymentType = $_SESSION['PaymentType'];
    $strCK_BankAccount = $_SESSION['PaymentCK_BankAccount'];

	mssql_bind($qryPayment, "@prmCustomerID", $intCustomerID, SQLINT4);
	mssql_bind($qryPayment, "@prmPaymentType", $strPaymentType, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_Number", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_Cardholder", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpMonth", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpYear", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_BillingPostalCode", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountType", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankRouting", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankAccount", $strCK_BankAccount, SQLVARCHAR);
	mssql_bind($qryPayment, "RETVAL", $intPaymentStatusCode, SQLINT4);
}
elseif ($_SESSION['PaymentType'] == 'Cash')
{
	$strPaymentType = $_SESSION['PaymentType'];

	mssql_bind($qryPayment, "@prmCustomerID", $intCustomerID, SQLINT4);
	mssql_bind($qryPayment, "@prmPaymentType", $strPaymentType, SQLVARCHAR);
	mssql_bind($qryPayment, "@prmCC_Number", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_Cardholder", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpMonth", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_ExpYear", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCC_BillingPostalCode", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountType", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_AccountName", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankRouting", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "@prmCK_BankAccount", $strNull, SQLVARCHAR, false, true);
	mssql_bind($qryPayment, "RETVAL", $intPaymentStatusCode, SQLINT4);
}

// EXECUTE QUERY & GET RETURN VALUE
$rsInsertPayment = mssql_execute($qryPayment);

$_SESSION['CustomerPayMethodID'] = $intPaymentStatusCode;

// ********** TOTALS **********
$OrderSubtotal = $_SESSION['OrderSubtotal'];

// GMC - 12/22/08 - NO CHARGE - Then No Shipping and No handling Values
if ($_SESSION['PaymentType'] == 'NOCHARGE')
{
    $OrderShipping = 0;

    // GMC - 11/16/11 - Fix Bug when NO CHARGE then NO Order Shipping and NO Order Handling
    $_SESSION['OrderShipping'] = 0;
    $_SESSION['OrderHandling'] = 0;
}
// GMC - 01/28/09 - Hair Product - Pre-Sales (01-30-09 to 01-31-09)
// GMC - 02/02/09 - Must Keep this code to enforce IsFreeShipping - No matter the product
else if($_SESSION['IsFreeShipping'] == 'Ok')
{
    $OrderShipping = 0;
    $_SESSION['OrderShipping'] = 0;
    $_SESSION['OrderHandling'] = 0;
    // HACK
}

// GMC - 02/15/09 - To include Will Call in International
else if($_SESSION['WillCallInt'] == 'Ok')
{
    $_SESSION['OrderShipping'] = 0;
    $_SESSION['OrderHandling'] = 0;
}

// GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
else if($_SESSION['Promo_Code'] == "MASCARA")
{
    $_SESSION['OrderShipping'] = 0;
    $_SESSION['OrderHandling'] = 0;
}

// GMC - 07/14/11 - Distributors Change CSRADMIN
if($_SESSION['Transportation_Charges'] == 'Collect' || $_SESSION['Transportation_Charges'] == 'Third Party')
{
    // GMC - 04/16/13 - Fix Issue with Third Party taking Shipping Method
    $_SESSION['OrderHandling'] = 0;
    $_SESSION['OrderShipping'] = 0;
    $OrderShipping = $_SESSION['OrderShipping'] + $_SESSION['OrderHandling'];
}
else
{
    $OrderShipping = $_SESSION['OrderShipping'] + $_SESSION['OrderHandling'];
}

$OrderTax = $_SESSION['OrderTax'];
$OrderTotal = $_SESSION['OrderTotal'];

// ********** SEED ORDER TO OBTAIN ORDER ID **********
$qrySeed = mssql_init("spOrders_Seed", $connProcess);
$intSeedReturn = 0;

mssql_bind($qrySeed, "@prmUserID", $intUserID, SQLINT4);
mssql_bind($qrySeed, "RETVAL", $intSeedReturn, SQLINT2);

$rsSeed = mssql_execute($qrySeed);

$OrderSeed = $intSeedReturn;

if ($_SESSION['PaymentType'] == 'CreditCard')
{
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  /*
 // GMC - 05/09/10 - To avoid an extra shipping only charge to the credit card
 // GMC - 03/04/11 - Fix of Bug OrderTotal instead of OrderSubTotal
 // GMC - 06/04/12 - Take USAEpay from CC Process (is in file renamed of the same day)
 // if($OrderSubtotal > 0)
 if($OrderTotal > 0)
 {
        // AUTHORIZE.NET CREDIT CARD PROCESSING
        // GMC - 02/15/10 - Authorize.NET extra values to reduce Interchange
		$qryGetCustomerName = mssql_query("SELECT FirstName, LastName, NavisionCustomerID, Address1, PostalCode FROM tblCustomers WHERE RecordID = " . $_SESSION['CustomerID']);

		while($rowGetCustomerName = mssql_fetch_array($qryGetCustomerName))
		{
			$CustomerFirstName = $rowGetCustomerName['FirstName'];
			$CustomerLastName = $rowGetCustomerName['LastName'];
			$CustomerName = $rowGetCustomerName['FirstName'] . ' ' . $rowGetCustomerName['LastName'];
			$CustomerNavisionID = $rowGetCustomerName['NavisionCustomerID'];

            // GMC - 02/15/10 - Authorize.NET extra values to reduce Interchange
            $CustomerAddress = $rowGetCustomerName['Address1'];
            $CustomerZipCode = $rowGetCustomerName['PostalCode'];
		}

		require_once('authorize_cc.php');

		$a = new authorizenet_class;

		$a->add_field('x_login', '5Qv27aBQJ3');

        // GMC - 05/26/11 - Change Transaction Key because a screw up by Accounting
		// $a->add_field('x_tran_key', '282K76ys6Lb2XBPq');
		$a->add_field('x_tran_key', '29M62XJ6vwsna426');

		$a->add_field('x_version', '3.1');
		$a->add_field('x_type', 'AUTH_CAPTURE');

        // GMC - Change from Test to Production
		$a->add_field('x_test_request', 'FALSE');     // Production transaction
		// $a->add_field('x_test_request', 'TRUE');    // Test transaction

		$a->add_field('x_relay_response', 'FALSE');
		$a->add_field('x_delim_data', 'TRUE');
		$a->add_field('x_delim_char', '|');
		$a->add_field('x_encap_char', '');
		$a->add_field('x_first_name', $CustomerFirstName);
		$a->add_field('x_last_name', $CustomerLastName);
		$a->add_field('x_invoice_num', $OrderSeed);

        // GMC - Change from Test to Production
		// $a->add_field('x_address', '1234 West Main St.');
		// $a->add_field('x_city', 'Some City');
		// $a->add_field('x_state', 'CA');
		// $a->add_field('x_zip', '12345');
		// $a->add_field('x_country', 'US');

        // GMC - 02/15/10 - Authorize.NET extra values to reduce Interchange
		$a->add_field('x_address', $CustomerAddress);
		$a->add_field('x_zip', $CustomerZipCode);
		$a->add_field('x_po_num', $CustomerAddress);
		$a->add_field('x_tax', $OrderTax);

		$a->add_field('x_method', 'CC');

        // GMC - Change from Test to Production
		$a->add_field('x_card_num', $_SESSION['PaymentCC_Number']);
		// $a->add_field('x_card_num', '4007000000027');   // test successful visa
		// $a->add_field('x_card_num', '4222222222222');    // test failure card number

		$a->add_field('x_amount', number_format($OrderTotal, 2, '.', ''));
		$ExpirationDate = $_SESSION['PaymentCC_ExpMonth'] . $_SESSION['PaymentCC_ExpYear'];
		$a->add_field('x_exp_date', $ExpirationDate);
		$a->add_field('x_card_code', $_SESSION['PaymentCC_CVV']);

		// Process the payment and output the results
		switch ($a->process())
		{
			case 1:  // Successs
			$CCAuthorization = $a->get_response_approval();
			$CCTransactionID = $a->get_response_transid();
			break;

			case 2:  // Declined
			$blnPaymentError = 1;
			break;

			case 3:  // Error
			$blnPaymentError = 2;
			break;
		}
  }
  */
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING
  //GMC - THIS SECTION TAKES CARE OF SENDING TO AUTHORIZE.NET - WARNING - WARNING - WARNING

  //GMC - THIS SECTION FOR TEST ONLY - WARNING - WARNING - WARNING
  // GMC - 03/04/11 - Fix of Bug OrderTotal instead of OrderSubTotal
  // if($OrderSubtotal > 0)
  /*
  if($OrderTotal > 0)
  {
    $CCAuthorization = "TEST";
    $CCTransactionID = "TEST";
  }
  */

}
elseif ($_SESSION['PaymentType'] == 'CreditCardSwiped')
// PRE SWIPED TRANSACTION
{
    // GMC - 12/11/13 - Magento Manual Process
    if($_SESSION['MagentoFlag'] == 'True')
    {
         $CCAuthorization = $_SESSION['PaymentCC_SwipedAuth'];
         $CCTransactionID = $_SESSION['PaymentCC_SwipedAuth'];
    }
    else
    {
        $CCAuthorization = $_SESSION['PaymentCC_SwipedAuth'];
		$CCTransactionID = 'TRADESHOW';
    }
}
elseif ($_SESSION['PaymentType'] == 'ECheck')
// AUTHORIZE.NET CHECK PROCESSING
{
	$CCAuthorization = 'ECHECK';
	$CCTransactionID = 'ECHECK';

	$qryGetCustomerName = mssql_query("SELECT FirstName, LastName, NavisionCustomerID FROM tblCustomers WHERE RecordID = " . $_SESSION['CustomerID']);

	while($rowGetCustomerName = mssql_fetch_array($qryGetCustomerName))
	{
		$CustomerFirstName = $rowGetCustomerName['FirstName'];
		$CustomerLastName = $rowGetCustomerName['LastName'];
		$CustomerName = $rowGetCustomerName['FirstName'] . ' ' . $rowGetCustomerName['LastName'];
		$CustomerNavisionID = $rowGetCustomerName['NavisionCustomerID'];
	}

	require_once('authorize_check.php');

	$a = new authorizenet_class;

	$a->add_field('x_login', '5Qv27aBQJ3');
	$a->add_field('x_tran_key', '282K76ys6Lb2XBPq');
	$a->add_field('x_version', '3.1');

    // GMC - Change from Test to Production
	$a->add_field('x_test_request', 'FALSE');
	//$a->add_field('x_test_request', 'TRUE');    // Test transaction

    $a->add_field('x_relay_response', 'FALSE');
	$a->add_field('x_delim_data', 'TRUE');
	$a->add_field('x_delim_char', '|');
	$a->add_field('x_encap_char', '');
	$a->add_field('x_first_name', $CustomerFirstName);
	$a->add_field('x_last_name', $CustomerLastName);
	$a->add_field('x_invoice_num', $OrderSeed);
	$a->add_field('x_method', 'ECHECK');
	$a->add_field('x_echeck_type', 'WEB');
	$a->add_field('x_bank_aba_code', $_SESSION['PaymentCK_BankRouting']);
	$a->add_field('x_bank_acct_num', $_SESSION['PaymentCK_BankAccount']);
	if ($_SESSION['PaymentCK_AccountType'] == 1)
		$a->add_field('x_bank_acct_type', 'CHECKING');
	elseif ($_SESSION['PaymentCK_AccountType'] == 2)
		$a->add_field('x_bank_acct_type', 'SAVINGS');
	$a->add_field('x_bank_name', $_SESSION['PaymentCK_BankName']);
	$a->add_field('x_bank_acct_name', $_SESSION['PaymentCK_AccountName']);
	$a->add_field('x_recurring_billing', 'FALSE');
	$a->add_field('x_amount', number_format($OrderTotal, 2, '.', ''));

	// Process the payment and output the results
	switch ($a->process())
	{
		case 1:  // Successs
		$CCAuthorization = $a->get_response_approval();
		$CCTransactionID = $a->get_response_transid();
		break;

		case 2:  // Declined
		$blnPaymentError = 1;
		break;

		case 3:  // Error
		$blnPaymentError = 2;
		//echo $a->dump_response();
		break;
	}
}

// GMC - 04/07/10 - check for Transaction and Authorization
elseif ($_SESSION['PaymentType'] == 'Terms')
// TERMS TRANSACTION
{
    $CCAuthorization = 'TERMS';
    $CCTransactionID = 'TERMS';
}

elseif ($_SESSION['PaymentType'] == 'NOCHARGE')
// NO CHARGE TRANSACTION
{
    $CCAuthorization = 'NO CHARGE';
    $CCTransactionID = 'NO CHARGE';
}

elseif ($_SESSION['PaymentType'] == 'Check')
// CHECK TRANSACTION
{
    $CCAuthorization = 'CHECK';
    $CCTransactionID = 'CHECK';
}

elseif ($_SESSION['PaymentType'] == 'Wire')
// WIRE TRANSACTION
{
    $CCAuthorization = 'WIRE';
    $CCTransactionID = 'WIRE';
}

elseif ($_SESSION['PaymentType'] == 'Cash')
// CASH TRANSACTION
{
    $CCAuthorization = 'CASH';
    $CCTransactionID = 'CASH';
}

// GMC - 04/07/10 - check for Transaction and Authorization
if (!isset($blnPaymentError) && isset($CCAuthorization) && isset($CCTransactionID))
{
	// SPECIFY SPROC TO EXECUTE
	$qryInsert = mssql_init("spOrders_Create", $connProcess);

	// INITIALIZE PARAMETERS
	$CustomerID = $_SESSION['CustomerID'];
	$ShipToID = $_SESSION['CustomerShipToID'];
	$ShipMethodID = $_SESSION['ShippingMethod'];
	$PayMethodID = $_SESSION['CustomerPayMethodID'];

    // GMC - 07/14/11 - Distributors Change CSRADMIN
    $DistributorCode = $_SESSION['Distributor_Code'];
    $PromisedDate = $_SESSION['Promised_Date'];
    $TransportationCharges = $_SESSION['Transportation_Charges'];
    $TransportationChargesValue = $_SESSION['Transportation_Charges_Value'];
    $DutyTax = $_SESSION['Duty_Tax'];
    $DutyTaxValue = $_SESSION['Duty_Tax_Value'];

    // GMC - 04/07/15 - Replace special characters for PO Number
    if (isset($_SESSION['PaymentPO_Number']))
    {
 		// $PONumber = $_SESSION['PaymentPO_Number'];
 		$PONumber = just_clean($_SESSION['PaymentPO_Number']);
    }
    else
    {
		$PONumber = '';
    }

	if (!isset($OrderAdjustment) || $OrderAdjustment == '')
		$OrderAdjustment = 0;

	$OrderNotes = $_POST['ShippingNotes'];

    // GMC - 02/01/11 - Order Closed By CSR ADMIN Partner - Rep
	if (isset($_SESSION['cart']))
	{
        $OrderClosedBy = "WEB";
    }
    else
    {
        $OrderClosedBy = $_SESSION['OrderClosedBy'];
    }

	// GMC - 03/19/09 - TradeShow - PromoCode Insert Into Orders
	// GMC - 06/29/09 - To Allow for Promo Codes other than TradeShow
	if($_SESSION['Promo_Code'] == "")
	{
	    $PromoCode = $_SESSION['FORMNavisionCampaign'];
	}
	else
	{
	    $PromoCode = $_SESSION['Promo_Code'];
	}

	// GMC - 03/26/09 - TradeShow - PromoCode Insert NULL if Blank
	if($PromoCode == "0")
	{
        $PromoCode = "";
	}

     // GMC - 03/18/10 - Add 10 Line Items Admin

    // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
    if ($_SESSION['BreastCancerAugOct2009_1'] >= 12 || $_SESSION['BreastCancerAugOct2009_2'] >= 12 || $_SESSION['BreastCancerAugOct2009_3'] >= 12 || $_SESSION['BreastCancerAugOct2009_4'] >= 12 || $_SESSION['BreastCancerAugOct2009_5'] >= 12 || $_SESSION['BreastCancerAugOct2009_6'] >= 12 || $_SESSION['BreastCancerAugOct2009_7'] >= 12 || $_SESSION['BreastCancerAugOct2009_8'] >= 12 || $_SESSION['BreastCancerAugOct2009_9'] >= 12 || $_SESSION['BreastCancerAugOct2009_10'] >= 12 || $_SESSION['BreastCancerAugOct2009_11'] >= 12 || $_SESSION['BreastCancerAugOct2009_12'] >= 12 || $_SESSION['BreastCancerAugOct2009_13'] >= 12 || $_SESSION['BreastCancerAugOct2009_14'] >= 12 || $_SESSION['BreastCancerAugOct2009_15'] >= 12 || $_SESSION['BreastCancerAugOct2009_16'] >= 12 || $_SESSION['BreastCancerAugOct2009_17'] >= 12 || $_SESSION['BreastCancerAugOct2009_18'] >= 12 || $_SESSION['BreastCancerAugOct2009_19'] >= 12 || $_SESSION['BreastCancerAugOct2009_20'] >= 12 || $_SESSION['BreastCancerAwarenessPromo_Pro'] >= 12)
    {
        $PromoCode = "BCAWP";
    }

    // GMC 01/05/10 - Valentine's Day 2010 Promotion
    if ($_SESSION['ValentinesDay2010_1'] > 0 || $_SESSION['ValentinesDay2010_2'] > 0 || $_SESSION['ValentinesDay2010_3'] > 0 || $_SESSION['ValentinesDay2010_4'] > 0 || $_SESSION['ValentinesDay2010_5'] > 0 || $_SESSION['ValentinesDay2010_6'] > 0 || $_SESSION['ValentinesDay2010_7'] > 0 || $_SESSION['ValentinesDay2010_8'] > 0 || $_SESSION['ValentinesDay2010_9'] > 0 || $_SESSION['ValentinesDay2010_10'] > 0 || $_SESSION['ValentinesDay2010_11'] > 0 || $_SESSION['ValentinesDay2010_12'] > 0 || $_SESSION['ValentinesDay2010_13'] > 0 || $_SESSION['ValentinesDay2010_14'] > 0 || $_SESSION['ValentinesDay2010_15'] > 0 || $_SESSION['ValentinesDay2010_16'] > 0 || $_SESSION['ValentinesDay2010_17'] > 0 || $_SESSION['ValentinesDay2010_18'] > 0 || $_SESSION['ValentinesDay2010_19'] > 0 || $_SESSION['ValentinesDay2010_20'] > 0 || $_SESSION['ValentinesDay2010Promo_Pro'] > 0)
    {
        $PromoCode = "VDP2010";
    }

    // GMC 03/09/10 - Mother's Day 2010 Promotion
    if ($_SESSION['MothersDay2010_1'] > 0 || $_SESSION['MothersDay2010_2'] > 0 || $_SESSION['MothersDay2010_3'] > 0 || $_SESSION['MothersDay2010_4'] > 0 || $_SESSION['MothersDay2010_5'] > 0 || $_SESSION['MothersDay2010_6'] > 0 || $_SESSION['MothersDay2010_7'] > 0 || $_SESSION['MothersDay2010_8'] > 0 || $_SESSION['MothersDay2010_9'] > 0 || $_SESSION['MothersDay2010_10'] > 0 || $_SESSION['MothersDay2010_11'] > 0 || $_SESSION['MothersDay2010_12'] > 0 || $_SESSION['MothersDay2010_13'] > 0 || $_SESSION['MothersDay2010_14'] > 0 || $_SESSION['MothersDay2010_15'] > 0 || $_SESSION['MothersDay2010_16'] > 0 || $_SESSION['MothersDay2010_17'] > 0 || $_SESSION['MothersDay2010_18'] > 0 || $_SESSION['MothersDay2010_19'] > 0 || $_SESSION['MothersDay2010_20'] > 0 || $_SESSION['MothersDay2010Promo_Pro'] > 0)
    {
        $PromoCode = "MDP2010";
    }

    // GMC - 10/07/10 - Commision Junction Project
    if($_SESSION['CJ_VAR'] == "Yes")
    {
        $PromoCode = "CJ";
    }

    // GMC - 10/24/10 - Bundles Project Oct 2010
    if($_SESSION['Bundles2010_1'] != '' || $_SESSION['Bundles2010_2'] != '' || $_SESSION['Bundles2010_3'] != '' || $_SESSION['Bundles2010_4'] != '' || $_SESSION['Bundles2010_5'] != '' || $_SESSION['Bundles2010_6'] != '' || $_SESSION['Bundles2010_7'] != '' || $_SESSION['Bundles2010_8'] != '' || $_SESSION['Bundles2010_9'] != '' || $_SESSION['Bundles2010_10'] != '' || $_SESSION['Bundles2010_11'] != '' || $_SESSION['Bundles2010_12'] != '' || $_SESSION['Bundles2010_13'] != '' || $_SESSION['Bundles2010_14'] != '' || $_SESSION['Bundles2010_15'] != '' || $_SESSION['Bundles2010_16'] != '' || $_SESSION['Bundles2010_17'] != '' || $_SESSION['Bundles2010_18'] != '' || $_SESSION['Bundles2010_19'] != '' || $_SESSION['Bundles2010_20'] != '')
    {
        $PromoCode = "BUNDLE";
    }

    // GMC 01/29/10 - TradeShow Family Bundle 01-2010
    // GMC - 02/03/10 - No Code Here - by JS
    /*
    if ($_SESSION['TradeShowFamilyBundle2010_1'] > 0 || $_SESSION['TradeShowFamilyBundle2010_2'] > 0 || $_SESSION['TradeShowFamilyBundle2010_3'] > 0 || $_SESSION['TradeShowFamilyBundle2010_4'] > 0 || $_SESSION['TradeShowFamilyBundle2010_5'] > 0 || $_SESSION['TradeShowFamilyBundle2010_6'] > 0 || $_SESSION['TradeShowFamilyBundle2010_7'] > 0 || $_SESSION['TradeShowFamilyBundle2010_8'] > 0 || $_SESSION['TradeShowFamilyBundle2010_9'] > 0 || $_SESSION['TradeShowFamilyBundle2010_10'] > 0)
    {
        $PromoCode = "FAMBUND0110";
    }
    */

    // GMC - 08/18/15 - Regenesis - Promotion 3 + 1 - Add PromoCode BBP
    if ($_SESSION['Regenesis_2286_1'] > 0 || $_SESSION['Regenesis_2286_2'] > 0 || $_SESSION['Regenesis_2286_3'] > 0 || $_SESSION['Regenesis_2286_4'] > 0 || $_SESSION['Regenesis_2286_5'] > 0 || $_SESSION['Regenesis_2286_6'] > 0 || $_SESSION['Regenesis_2286_7'] > 0 || $_SESSION['Regenesis_2286_8'] > 0 || $_SESSION['Regenesis_2286_9'] > 0 || $_SESSION['Regenesis_2286_10'] > 0 || $_SESSION['Regenesis_2286_11'] > 0 || $_SESSION['Regenesis_2286_12'] > 0 || $_SESSION['Regenesis_2286_13'] > 0 || $_SESSION['Regenesis_2286_14'] > 0 || $_SESSION['Regenesis_2286_15'] > 0 || $_SESSION['Regenesis_2286_16'] > 0 || $_SESSION['Regenesis_2286_17'] > 0 || $_SESSION['Regenesis_2286_18'] > 0 || $_SESSION['Regenesis_2286_19'] > 0 || $_SESSION['Regenesis_2286_20'] > 0)
    {
        $PromoCode = "BBP";
    }

    if($_SESSION['Regenesis_2286_21'] > 0 || $_SESSION['Regenesis_2286_22'] > 0 || $_SESSION['Regenesis_2286_23'] > 0 || $_SESSION['Regenesis_2286_24'] > 0 || $_SESSION['Regenesis_2286_25'] > 0 || $_SESSION['Regenesis_2286_26'] > 0 || $_SESSION['Regenesis_2286_27'] > 0 || $_SESSION['Regenesis_2286_28'] > 0 || $_SESSION['Regenesis_2286_29'] > 0 || $_SESSION['Regenesis_2286_30'] > 0 || $_SESSION['Regenesis_2286_31'] > 0 || $_SESSION['Regenesis_2286_32'] > 0 || $_SESSION['Regenesis_2286_33'] > 0 || $_SESSION['Regenesis_2286_34'] > 0 || $_SESSION['Regenesis_2286_35'] > 0 || $_SESSION['Regenesis_2286_36'] > 0 || $_SESSION['Regenesis_2286_37'] > 0 || $_SESSION['Regenesis_2286_38'] > 0 || $_SESSION['Regenesis_2286_39'] > 0 || $_SESSION['Regenesis_2286_40'] > 0)
    {
        $PromoCode = "BBP";
    }

    if ($_SESSION['Regenesis_2285_1'] > 0 || $_SESSION['Regenesis_2285_2'] > 0 || $_SESSION['Regenesis_2285_3'] > 0 || $_SESSION['Regenesis_2285_4'] > 0 || $_SESSION['Regenesis_2285_5'] > 0 || $_SESSION['Regenesis_2285_6'] > 0 || $_SESSION['Regenesis_2285_7'] > 0 || $_SESSION['Regenesis_2285_8'] > 0 || $_SESSION['Regenesis_2285_9'] > 0 || $_SESSION['Regenesis_2285_10'] > 0 || $_SESSION['Regenesis_2285_11'] > 0 || $_SESSION['Regenesis_2285_12'] > 0 || $_SESSION['Regenesis_2285_13'] > 0 || $_SESSION['Regenesis_2285_14'] > 0 || $_SESSION['Regenesis_2285_15'] > 0 || $_SESSION['Regenesis_2285_16'] > 0 || $_SESSION['Regenesis_2285_17'] > 0 || $_SESSION['Regenesis_2285_18'] > 0 || $_SESSION['Regenesis_2285_19'] > 0 || $_SESSION['Regenesis_2285_20'] > 0)
    {
        $PromoCode = "BBP";
    }

    if($_SESSION['Regenesis_2285_21'] > 0 || $_SESSION['Regenesis_2285_22'] > 0 || $_SESSION['Regenesis_2285_23'] > 0 || $_SESSION['Regenesis_2285_24'] > 0 || $_SESSION['Regenesis_2285_25'] > 0 || $_SESSION['Regenesis_2285_26'] > 0 || $_SESSION['Regenesis_2285_27'] > 0 || $_SESSION['Regenesis_2285_28'] > 0 || $_SESSION['Regenesis_2285_29'] > 0 || $_SESSION['Regenesis_2285_30'] > 0 || $_SESSION['Regenesis_2285_31'] > 0 || $_SESSION['Regenesis_2285_32'] > 0 || $_SESSION['Regenesis_2285_33'] > 0 || $_SESSION['Regenesis_2285_34'] > 0 || $_SESSION['Regenesis_2285_35'] > 0 || $_SESSION['Regenesis_2285_36'] > 0 || $_SESSION['Regenesis_2285_37'] > 0 || $_SESSION['Regenesis_2285_38'] > 0 || $_SESSION['Regenesis_2285_39'] > 0 || $_SESSION['Regenesis_2285_40'] > 0)
    {
        $PromoCode = "BBP";
    }

    // GMC - 02/16/13 - Special Customer Code passed to NAV
    if($_SESSION['SpecialCustomerCode'] == "True")
    {
        $intSpecialCustomerCode = 1;
    }
    else
    {
        $intSpecialCustomerCode = 0;
    }

	// BIND PARAMETERS
	$intStatusCode = 0;
	mssql_bind($qryInsert, "@prmUserID", $intUserID, SQLINT4);
	mssql_bind($qryInsert, "@prmOrderID", $OrderSeed, SQLINT4);
	mssql_bind($qryInsert, "@prmCustomerID", $CustomerID, SQLINT4);
	mssql_bind($qryInsert, "@prmShipToID", $ShipToID, SQLINT4);
	mssql_bind($qryInsert, "@prmShipMethodID", $ShipMethodID, SQLINT4);
	mssql_bind($qryInsert, "@prmPayMethodID", $PayMethodID, SQLINT4);
	if ($PONumber != '')
		mssql_bind($qryInsert, "@prmPONumber", $PONumber, SQLVARCHAR);
	else
		mssql_bind($qryInsert, "@prmPONumber", $PONumber, SQLVARCHAR, false, true);
	mssql_bind($qryInsert, "@prmCCAuthorization", $CCAuthorization, SQLVARCHAR);
	mssql_bind($qryInsert, "@prmCCTransactionID", $CCTransactionID, SQLVARCHAR);

    // GMC - 03/19/09 - TradeShow - PromoCode Insert Into Orders
	mssql_bind($qryInsert, "@prmPromoCode", $PromoCode, SQLVARCHAR);

	mssql_bind($qryInsert, "@prmOrderSubtotal", $OrderSubtotal, SQLFLT8);
	mssql_bind($qryInsert, "@prmOrderShipping", $OrderShipping, SQLFLT8);
	mssql_bind($qryInsert, "@prmOrderTax", $OrderTax, SQLFLT8);
	mssql_bind($qryInsert, "@prmOrderAdjustment", $OrderAdjustment, SQLFLT8);
	mssql_bind($qryInsert, "@prmOrderTotal", $OrderTotal, SQLFLT8);
	mssql_bind($qryInsert, "@prmOrderNotes", $OrderNotes, SQLTEXT);

    // GMC - 02/01/11 - Order Closed By CSR ADMIN Partner - Rep
	mssql_bind($qryInsert, "@prmOrderClosedBy", $OrderClosedBy, SQLTEXT);

    // GMC - 07/14/11 - Distributors Change CSRADMIN
	mssql_bind($qryInsert, "@prmDistributorCode", $DistributorCode, SQLTEXT);
	mssql_bind($qryInsert, "@prmPromisedDate", $PromisedDate, SQLTEXT);
	mssql_bind($qryInsert, "@prmTransportationCharges", $TransportationCharges, SQLTEXT);
	mssql_bind($qryInsert, "@prmTransportationChargesValue", $TransportationChargesValue, SQLTEXT);
	mssql_bind($qryInsert, "@prmDutyTax", $DutyTax, SQLTEXT);
	mssql_bind($qryInsert, "@prmDutyTaxValue", $DutyTaxValue, SQLTEXT);

    // GMC - 02/16/13 - Special Customer Code passed to NAV
    mssql_bind($qryInsert, "@prmSpecialCustomerCode", $intSpecialCustomerCode, SQLINT4);

    // GMC - 11/11/14 - Include Shipping Values for Fullfillment
    mssql_bind($qryInsert, "@prmTotalNetOrderShipping", $_SESSION['OrderShipping'], SQLFLT8);
    mssql_bind($qryInsert, "@prmTotalOrderWeight", $_SESSION['OrderWeight'], SQLFLT8);
    mssql_bind($qryInsert, "@prmTotalBoxCount", $_SESSION['TotalBoxCount'], SQLFLT8);

    // GMC - 05/11/15 - Integrate CAP Products into Admin
    mssql_bind($qryInsert, "@prmActualShippingCharge", $_SESSION['CapRateReal'], SQLFLT8);

	mssql_bind($qryInsert, "RETVAL", $intStatusCode, SQLINT2);

	// EXECUTE QUERY
	$rs = mssql_execute($qryInsert);

	// SET RETURN VALUE
	$_SESSION['OrderID'] = $OrderSeed;

	// LOOP THROUGH ITEMS AND ADD TO ORDER
	$blnIsLiterature = 0;

	if (isset($_SESSION['cart']))
	{
		for (reset($_SESSION['cart']); list($key) = each($_SESSION['cart']);)
		{
			// EXECUTE SQL QUERY
			$result = mssql_query("SELECT RecordID, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CategoryID FROM tblProducts WHERE RecordID = " . $key);
			while($row = mssql_fetch_array($result))
			{
				if ($row["CategoryID"] == 2)
					$blnIsLiterature = 1;

				if ($_SESSION['CustomerTypeID'] == 2)
				{

					if ($_SESSION['IsInternational'] == 0)
					{
						$rs2 = mssql_query("SELECT TOP 1 DiscountPrice FROM tblProducts_ResellerTier WHERE ProductID = " . $key . " AND " . $_SESSION['cart'][$key] . " >= QtyRequired ORDER BY QtyRequired DESC");
						while($row2 = mssql_fetch_array($rs2))
						{
							$decUnitPrice = $row2["DiscountPrice"];
							$decExtendedPrice = number_format($row2["DiscountPrice"] * $_SESSION['cart'][$key], 2, '.', '');
						}
					}
					else
					{
						$decUnitPrice = $row["ResellerPrice"];
						$decExtendedPrice = number_format($row["ResellerPrice"] * $_SESSION['cart'][$key], 2, '.', '');
					}

                    // GMC - 05/29/12 - SUMMER2012 Web Reseller Promotion June 2012
                    $rs = mssql_query("SELECT RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, CategoryID FROM tblProducts WHERE RecordID = " . $key);
                    while($row = mssql_fetch_array($rs))
                    {
                        if($_SESSION['PaymentCC_PromoCode'] != '')
                        {
	                        $rs3 = mssql_query("SELECT PromoCode, Discount FROM tblResellersPromoCode WHERE IsActive = 1 AND PromoCode = '" . $_SESSION['PaymentCC_PromoCode'] . "'");
	                        while($row3 = mssql_fetch_array($rs3))
	                        {
                                $strPromoCode = $row3["PromoCode"];
                                $strDiscount = $row3["Discount"];
                            }

                            if($strPromoCode != '' && $key == 620)
                            {
					            $decUnitPrice = ($row["ResellerPrice"]- ($row["ResellerPrice"] * $strDiscount));
					            $decExtendedPrice = number_format($decUnitPrice * $_SESSION['cart'][$key], 2, '.', '');
                            }
                        }
                    }
				}
				elseif ($_SESSION['CustomerTypeID'] == 3)
				{
					$decUnitPrice = $row["DistributorPrice"];
					$decExtendedPrice = number_format($row["DistributorPrice"] * $_SESSION['cart'][$key], 2, '.', '');
				}
				elseif ($_SESSION['CustomerTypeID'] == 1)
				{
                    // GMC - 07/16/09 - Promo Code Discount for Unit Price reflected in tblOrders_Items
                    // GMC - Can be changed depending of the Promo Code Text
                    if($_SESSION['Promo_Code'] == "NEWBEAUTY")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 09/10/09 - Friend Promo Code
                    else if($_SESSION['Promo_Code'] == "FRIEND")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 09/17/09 - Pretty City Promo Code
                    else if($_SESSION['Promo_Code'] == "PRETTYCITY")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 09/24/09 - Meg Promo Code
                    else if($_SESSION['Promo_Code'] == "MEG")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 10/20/09 - OK Weekly Promo Code
                    else if($_SESSION['Promo_Code'] == "OKWEEKLY")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 10/30/09 - Hair Loss Talk Promo Code
                    else if($_SESSION['Promo_Code'] == "HAIRLOSSTALK")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 11/12/09 - Twitter Promo Code
                    else if($_SESSION['Promo_Code'] == "TWITTER")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 11/16/09 - Leads Promo Code
                    else if($_SESSION['Promo_Code'] == "LEADS")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 11/25/09 - Remax Promo Code
                    else if($_SESSION['Promo_Code'] == "REMAX")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 12/03/09 - Rome Promo Code
                    else if($_SESSION['Promo_Code'] == "ROME")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 04/28/10 - April 2010 Promotion "Newsletter" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "NEWSLETTER")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 06/21/10 - June 2010 Promotion "Wahanda" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "WAHANDA")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 07/02/10 - July 2010 Promotion "ISPA" - 20% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "ISPA")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.20);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.20)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 07/26/10 - July 2010 Promotion "YANDR" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "YANDR")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 08/20/10 - August 2010 Promotion "LSE001" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "LSE001")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 09/28/10 - September 2010 Promotion "TWEET" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "TWEET")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 09/28/10 - September 2010 Promotion "TUBEIT" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "TUBEIT")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 10/06/10 - October 2010 Promotion "AMERSPA" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "AMERSPA")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 10/07/10 - Commision Junction Project
                    // GMC - 10/19/10 - Cancel 15% for CJ
                    /*
                    else if($_SESSION['CJ_VAR'] == "Yes")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }
                    */

                    // GMC - 10/18/10 - October 2010 Promotion "LASHES" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "LASHES")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "MASCARA")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 12/20/10 - December 2010 Promotion "JOURNAL" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "JOURNAL")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 01/17/10 - January 2011 Promotion "COUPON" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "COUPON")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 01/18/10 - January 2011 Promotion "ORDER" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "ORDER")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 03/01/11 - March 2011 Promotion "LASHWORLD" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "LASHWORLD")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 04/04/11 - April 2011 Promotion "CLIQUE" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "CLIQUE")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 06/14/11 - June 2011 Promotion "PKW" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "PKW")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 09/22/11 - September 2011 Promotion "GIFT" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "GIFT")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 11/22/11 - November 2011 Promotion "BF2011" - 20% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "BF2011")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.20);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.20)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 04/16/12 - April 2012 Promotion "BUZZ" - 15% Off on Total Purchased Value (any product)
                    else if($_SESSION['Promo_Code'] == "BUZZ")
                    {
					    $decUnitPrice = $row["RetailPrice"] - ($row["RetailPrice"] * 0.15);
					    $decExtendedPrice = number_format(($row["RetailPrice"] - ($row["RetailPrice"] * 0.15)) * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    else
                    {
					    $decUnitPrice = $row["RetailPrice"];
					    $decExtendedPrice = number_format($row["RetailPrice"] * $_SESSION['cart'][$key], 2, '.', '');
                    }

                    // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
                    if ($key == 967)
					{
                        $Add_1022_If_967 = "Yes";
                    }
                }

                // GMC - 05/06/09 - FedEx Netherlands
                // GMC - 10/09/10 - Force Shipping from Las Vegas for EU Orders
                if($_SESSION['CountryCodeFedExEu_Retail'] != '')
                {
            	    // $Location = 'FEDEXNETH';
            	    $Location = 'ATHENA-LV';
                }
                else
                {
                    $Location = 'ATHENA-LV';
                }

				$qryInsertItem = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intItemStatusCode = 0;
				mssql_bind($qryInsertItem, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
				mssql_bind($qryInsertItem, "@prmItemID", $key, SQLINT4);
				mssql_bind($qryInsertItem, "@prmLocation", $Location, SQLVARCHAR);
				mssql_bind($qryInsertItem, "@prmQty", $_SESSION['cart'][$key], SQLINT4);
				mssql_bind($qryInsertItem, "@prmUnitPrice", $decUnitPrice, SQLFLT8);
                mssql_bind($qryInsertItem, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                $DiscProValue = 0;
                $DiscValueEmpty = "";

                mssql_bind($qryInsertItem, "@prmIntDiscProValue", $DiscProValue, SQLFLT8);
			    mssql_bind($qryInsertItem, "@prmIntDiscProCode", $DiscValueEmpty, SQLVARCHAR);
			    mssql_bind($qryInsertItem, "@prmIntDiscProStartDate", $DiscValueEmpty, SQLVARCHAR);
			    mssql_bind($qryInsertItem, "@prmIntDiscProEndDate", $DiscValueEmpty, SQLVARCHAR);

				mssql_bind($qryInsertItem, "RETVAL", $intItemStatusCode, SQLINT2);

				$rs = mssql_execute($qryInsertItem);

				if ((!isset($_SESSION['CustomerTerms']) || $_SESSION['CustomerTerms'] == 0) && ($_SESSION['CustomerTypeID'] == 2) && ($row["ResellerFreeTrigger"] > 0) && (floor($_SESSION['cart'][$key] / $row["ResellerFreeTrigger"]) >= 1))
				{
                    $intItemQty = floor($_SESSION['cart'][$key] / $row["ResellerFreeTrigger"]);

					$decItemPrice = 0;
					$qryInsertFreeItem = mssql_init("spOrders_Items_Create", $connProcess);

					// BIND PARAMETERS
					$intFreeItemStatusCode = 0;
					mssql_bind($qryInsertFreeItem, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                    // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
                    // GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                    // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                    if($key == '124' || $key == '139' || $key == '141' || $key == '142')
                    {
                         $key = '100';
					     mssql_bind($qryInsertFreeItem, "@prmItemID", $key, SQLINT4);
                    }
                    else
                    {
					    mssql_bind($qryInsertFreeItem, "@prmItemID", $key, SQLINT4);
                    }

                    mssql_bind($qryInsertFreeItem, "@prmLocation", $Location, SQLVARCHAR);
					mssql_bind($qryInsertFreeItem, "@prmQty", $intItemQty, SQLINT4);
					mssql_bind($qryInsertFreeItem, "@prmUnitPrice", $decItemPrice, SQLFLT8);
					mssql_bind($qryInsertFreeItem, "@prmExtendedPrice", $decItemPrice, SQLFLT8);

                    // GMC - 01/19/14 - Discount Promo Code International Items
                    $DiscProValue = 0;
                    $DiscValueEmpty = "";

                    mssql_bind($qryInsertFreeItem, "@prmIntDiscProValue", $DiscProValue, SQLFLT8);
                    mssql_bind($qryInsertFreeItem, "@prmIntDiscProCode", $DiscValueEmpty, SQLVARCHAR);
                    mssql_bind($qryInsertFreeItem, "@prmIntDiscProStartDate", $DiscValueEmpty, SQLVARCHAR);
			        mssql_bind($qryInsertFreeItem, "@prmIntDiscProEndDate", $DiscValueEmpty, SQLVARCHAR);

					mssql_bind($qryInsertFreeItem, "RETVAL", $intFreeItemStatusCode, SQLINT2);

					$rs = mssql_execute($qryInsertFreeItem);
				}
             }
         }

        // GMC - 12/17/09 - Insert Item 204 into Order (Brochure with every order NAV 321)
        // GMC - 02/24/10 - Change Item 204 for 247 by JS
        // GMC - 07/01/11 - Change Item 247 for 402 by JS
        // GMC - 03/16/12 - Change Item 402 for 539 and 515 for 540 by JS
        // GMC - 06/25/12 - Change Item 539 for 662 (Domestic and International)
        // GMC - 07/28/12 - Cancel sending Item 662 with all orders
        // GMC - 08/28/12 - Change Item 662 for 700
        // GMC - 12/04/12 - Change Item 700 for 853 - 854 or 855
        // GMC - 03/11/13 - Web Orders to include extra item (Domestic = 853, International = 855)

        $intItemQty = 1;
		$decItemPrice = 0;

        // $intItemId = 147; // Test
		// $intItemId = 204; // Production
		// $intItemId = 247; // Production
		// $intItemId = 402; // Production
		// $intItemId = 662; // Production
		// $intItemId = 700; // Production

        if($_SESSION['IsInternational'] == 1)
        {
		    $intItemId = 1266; // Production
        }
        else
        {
            /*
            if(strtoupper($_SESSION['State_Customer']) == 'CA' || strtoupper($_SESSION['State_Customer']) == 'TN')
            {
		        $intItemId = 853; // Production
            }
            else
            {
		        $intItemId = 854; // Production
            }
            */

            // GMC - 08/22/13 - Web Orders to include extra item (Domestic = 1060)
            // GMC - 10/20/14 - Web Orders to include extra item (Domestic = 1324)
            // $intItemId = 853; // Production
            // $intItemId = 1060; // Production
            $intItemId = 1324; // Production
        }

		$qryInsertBrochure = mssql_init("spOrders_Items_Create", $connProcess);

        // BIND PARAMETERS
        $intFreeItemStatusCode = 0;
		mssql_bind($qryInsertBrochure, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
		mssql_bind($qryInsertBrochure, "@prmItemID", $intItemId, SQLINT4);
        mssql_bind($qryInsertBrochure, "@prmLocation", $Location, SQLVARCHAR);
		mssql_bind($qryInsertBrochure, "@prmQty", $intItemQty, SQLINT4);
		mssql_bind($qryInsertBrochure, "@prmUnitPrice", $decItemPrice, SQLFLT8);
		mssql_bind($qryInsertBrochure, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
		mssql_bind($qryInsertBrochure, "RETVAL", $intFreeItemStatusCode, SQLINT2);

		$rsGC = mssql_execute($qryInsertBrochure);

       // GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
       if($_SESSION['Promo_Code'] == "MASCARA")
       {
           $intItemQty = 1;
           $decItemPrice = 0;
           $intItemId = 217; // Production
           $qryInsertBrochure = mssql_init("spOrders_Items_Create", $connProcess);

           // BIND PARAMETERS
           $intFreeItemStatusCode = 0;
           mssql_bind($qryInsertBrochure, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
           mssql_bind($qryInsertBrochure, "@prmItemID", $intItemId, SQLINT4);
           mssql_bind($qryInsertBrochure, "@prmLocation", $Location, SQLVARCHAR);
           mssql_bind($qryInsertBrochure, "@prmQty", $intItemQty, SQLINT4);
           mssql_bind($qryInsertBrochure, "@prmUnitPrice", $decItemPrice, SQLFLT8);
           mssql_bind($qryInsertBrochure, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
           mssql_bind($qryInsertBrochure, "RETVAL", $intFreeItemStatusCode, SQLINT2);

           $rsGC = mssql_execute($qryInsertBrochure);
       }

       // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
       if ($Add_1022_If_967 == "Yes")
	   {
           $intItemQty = 1;
           $decItemPrice = 0;
           $intItemId = 1022; // Production
           $qryInsertBrochure = mssql_init("spOrders_Items_Create", $connProcess);

           // BIND PARAMETERS
           $intFreeItemStatusCode = 0;
           mssql_bind($qryInsertBrochure, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
           mssql_bind($qryInsertBrochure, "@prmItemID", $intItemId, SQLINT4);
           mssql_bind($qryInsertBrochure, "@prmLocation", $Location, SQLVARCHAR);
           mssql_bind($qryInsertBrochure, "@prmQty", $intItemQty, SQLINT4);
           mssql_bind($qryInsertBrochure, "@prmUnitPrice", $decItemPrice, SQLFLT8);
           mssql_bind($qryInsertBrochure, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
           mssql_bind($qryInsertBrochure, "RETVAL", $intFreeItemStatusCode, SQLINT2);

           $rsGC = mssql_execute($qryInsertBrochure);
       }
	}
	else
	{
        // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
        $RegenesisInt = 0;
        $RegenesisStr = "";

		if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_1'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle1_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                    $qryInsertItem1 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                    // GMC - 09/18/09 - To calculate the Promo Discount Value per Item        620
                    if($_POST['ItemID1'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                    {
                        $_POST['ItemPrice1'] = $_POST['ItemPrice1'] - ($_POST['ItemPrice1'] * $_SESSION['Promo_Code_Discount']);
                        $decExtendedPrice = $_POST['ItemPrice1'] * $_POST['ItemQty1'];
                    }
                    else
                    {
			            $decExtendedPrice = $_POST['ItemPrice1'] * $_POST['ItemQty1'];
                    }

                    // GMC - 01/28/10 - Include PONumber, EnteredBy and Location in Order Header NAV
                    $Location = $_POST['ItemStockLocation1'];

                    // BIND PARAMETERS
                    $intItemStatusCode = 0;
                    mssql_bind($qryInsertItem1, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertItem1, "@prmItemID", $_POST['ItemID1'], SQLINT4);
                    mssql_bind($qryInsertItem1, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertItem1, "@prmQty", $_POST['ItemQty1'], SQLINT4);
                    mssql_bind($qryInsertItem1, "@prmUnitPrice", $_POST['ItemPrice1'], SQLFLT8);
                    mssql_bind($qryInsertItem1, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertItem1, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertItem1, "RETVAL", $intItemStatusCode, SQLINT2);
                    $rs1 = mssql_execute($qryInsertItem1);
            }
            else
            {
                $qryInsertItem1 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item        620
                if($_POST['ItemID1'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice1'] = $_POST['ItemPrice1'] - ($_POST['ItemPrice1'] * $_SESSION['Promo_Code_Discount']);
                    $decExtendedPrice = $_POST['ItemPrice1'] * $_POST['ItemQty1'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice1'] * $_POST['ItemQty1'];
                }

                // GMC - 01/28/10 - Include PONumber, EnteredBy and Location in Order Header NAV
                $Location = $_POST['ItemStockLocation1'];

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem1, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem1, "@prmItemID", $_POST['ItemID1'], SQLINT4);
			    mssql_bind($qryInsertItem1, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			    mssql_bind($qryInsertItem1, "@prmQty", $_POST['ItemQty1'], SQLINT4);
			    mssql_bind($qryInsertItem1, "@prmUnitPrice", $_POST['ItemPrice1'], SQLFLT8);
                mssql_bind($qryInsertItem1, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem1, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_1'], SQLFLT8);
			    mssql_bind($qryInsertItem1, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_1'], SQLVARCHAR);
			    mssql_bind($qryInsertItem1, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_1'], SQLVARCHAR);
			    mssql_bind($qryInsertItem1, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_1'], SQLVARCHAR);

                mssql_bind($qryInsertItem1, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs1 = mssql_execute($qryInsertItem1);
            }

			if (isset($_POST['ItemFree1']) && $_POST['ItemFree1'] != 0)
			{
				$intItemQty = $_POST['ItemFree1'];
				$decItemPrice = 0;
				$qryInsertFreeItem1 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem1, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID1'] == '124' || $_POST['ItemID1'] == '139' || $_POST['ItemID1'] == '141' || $_POST['ItemID1'] == '142')
                {
                    $_POST['ItemID1'] = '100';
				    mssql_bind($qryInsertFreeItem1, "@prmItemID", $_POST['ItemID1'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem1, "@prmItemID", $_POST['ItemID1'], SQLINT4);
                }

                mssql_bind($qryInsertFreeItem1, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem1, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem1, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem1, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem1, "RETVAL", $intFreeItemStatusCode, SQLINT2);
				$rs1a = mssql_execute($qryInsertFreeItem1);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_1'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_1']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle1_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID1'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_1'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_1'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_1 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_1, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_1, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_1, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_1, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_1, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_1, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_1, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_1, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_1, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_1, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_1, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_1);
            }

            if ($_SESSION['Regenesis_2285_1'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_1'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_1 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_1, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_1, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_1, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_1, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_1, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_1, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_1, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_1, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_1, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_1, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_1, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_1);
            }
		}

		if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_2'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle2_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem2 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID2'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice2'] = $_POST['ItemPrice2'] - ($_POST['ItemPrice2'] * $_SESSION['Promo_Code_Discount']);
                    $decExtendedPrice = $_POST['ItemPrice2'] * $_POST['ItemQty2'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice2'] * $_POST['ItemQty2'];
                }

                // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem2, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem2, "@prmItemID", $_POST['ItemID2'], SQLINT4);
			    mssql_bind($qryInsertItem2, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			    mssql_bind($qryInsertItem2, "@prmQty", $_POST['ItemQty2'], SQLINT4);
                mssql_bind($qryInsertItem2, "@prmUnitPrice", $_POST['ItemPrice2'], SQLFLT8);
                mssql_bind($qryInsertItem2, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem2, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                mssql_bind($qryInsertItem2, "RETVAL", $intItemStatusCode, SQLINT2);
                $rs2 = mssql_execute($qryInsertItem2);
            }
            else
            {
                $qryInsertItem2 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID2'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice2'] = $_POST['ItemPrice2'] - ($_POST['ItemPrice2'] * $_SESSION['Promo_Code_Discount']);
                    $decExtendedPrice = $_POST['ItemPrice2'] * $_POST['ItemQty2'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice2'] * $_POST['ItemQty2'];
                }

                // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem2, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem2, "@prmItemID", $_POST['ItemID2'], SQLINT4);
			    mssql_bind($qryInsertItem2, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			    mssql_bind($qryInsertItem2, "@prmQty", $_POST['ItemQty2'], SQLINT4);
                mssql_bind($qryInsertItem2, "@prmUnitPrice", $_POST['ItemPrice2'], SQLFLT8);
                mssql_bind($qryInsertItem2, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem2, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_2'], SQLFLT8);
			    mssql_bind($qryInsertItem2, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_2'], SQLVARCHAR);
			    mssql_bind($qryInsertItem2, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_2'], SQLVARCHAR);
			    mssql_bind($qryInsertItem2, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_2'], SQLVARCHAR);

                mssql_bind($qryInsertItem2, "RETVAL", $intItemStatusCode, SQLINT2);

                $rs2 = mssql_execute($qryInsertItem2);
            }

			if (isset($_POST['ItemFree2']) && $_POST['ItemFree2'] != 0)
			{
				$intItemQty = $_POST['ItemFree2'];
				$decItemPrice = 0;
				$qryInsertFreeItem2 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem2, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID2'] == '124' || $_POST['ItemID2'] == '139' || $_POST['ItemID2'] == '141' || $_POST['ItemID2'] == '142')
                {
                    $_POST['ItemID2'] = '100';
				    mssql_bind($qryInsertFreeItem2, "@prmItemID", $_POST['ItemID2'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem2, "@prmItemID", $_POST['ItemID2'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem2, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem2, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem2, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem2, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem2, "RETVAL", $intFreeItemStatusCode, SQLINT2);

				$rs2a = mssql_execute($qryInsertFreeItem2);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_2'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_2']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle2_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID2'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_2'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_2'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_2 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_2, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_2, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_2, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_2, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_2, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_2, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_2, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_2, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_2, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_2, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_2, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_2);
            }

            if ($_SESSION['Regenesis_2285_2'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_2'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_2 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_2, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_2, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_2, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_2, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_2, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_2, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_2, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_2, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_2, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_2, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_2, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_2);
            }
		}

		if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_3'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle3_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem3 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID3'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice3'] = $_POST['ItemPrice3'] - ($_POST['ItemPrice3'] * $_SESSION['Promo_Code_Discount']);
                    $decExtendedPrice = $_POST['ItemPrice3'] * $_POST['ItemQty3'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice3'] * $_POST['ItemQty3'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem3, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem3, "@prmItemID", $_POST['ItemID3'], SQLINT4);
			    mssql_bind($qryInsertItem3, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			    mssql_bind($qryInsertItem3, "@prmQty", $_POST['ItemQty3'], SQLINT4);
			    mssql_bind($qryInsertItem3, "@prmUnitPrice", $_POST['ItemPrice3'], SQLFLT8);
			    mssql_bind($qryInsertItem3, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem3, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem3, "RETVAL", $intItemStatusCode, SQLINT2);

			   $rs3 = mssql_execute($qryInsertItem3);
            }
            else
            {
			    $qryInsertItem3 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID3'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice3'] = $_POST['ItemPrice3'] - ($_POST['ItemPrice3'] * $_SESSION['Promo_Code_Discount']);
                    $decExtendedPrice = $_POST['ItemPrice3'] * $_POST['ItemQty3'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice3'] * $_POST['ItemQty3'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem3, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem3, "@prmItemID", $_POST['ItemID3'], SQLINT4);
			    mssql_bind($qryInsertItem3, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			    mssql_bind($qryInsertItem3, "@prmQty", $_POST['ItemQty3'], SQLINT4);
			    mssql_bind($qryInsertItem3, "@prmUnitPrice", $_POST['ItemPrice3'], SQLFLT8);
			    mssql_bind($qryInsertItem3, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem3, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_3'], SQLFLT8);
			    mssql_bind($qryInsertItem3, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_3'], SQLVARCHAR);
			    mssql_bind($qryInsertItem3, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_3'], SQLVARCHAR);
			    mssql_bind($qryInsertItem3, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_3'], SQLVARCHAR);

			    mssql_bind($qryInsertItem3, "RETVAL", $intItemStatusCode, SQLINT2);

			   $rs3 = mssql_execute($qryInsertItem3);
            }

			if (isset($_POST['ItemFree3']) && $_POST['ItemFree3'] != 0)
			{
				$intItemQty = $_POST['ItemFree3'];
				$decItemPrice = 0;
				$qryInsertFreeItem3 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem3, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID3'] == '124' || $_POST['ItemID3'] == '139' || $_POST['ItemID3'] == '141' || $_POST['ItemID3'] == '142')
                {
                    $_POST['ItemID3'] = '100';
				    mssql_bind($qryInsertFreeItem3, "@prmItemID", $_POST['ItemID3'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem3, "@prmItemID", $_POST['ItemID3'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem3, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem3, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem3, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem3, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem3, "RETVAL", $intFreeItemStatusCode, SQLINT3);

				$rs3a = mssql_execute($qryInsertFreeItem3);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_3'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_3']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle3_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID3'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_3'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_3'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_3 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_3, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_3, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_3, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_3, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_3, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_3, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_3, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_3, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_3, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_3, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_3, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_3);
            }

            if ($_SESSION['Regenesis_2285_3'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_3'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_3 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_3, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_3, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_3, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_3, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_3, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_3, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_3, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_3, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_3, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_3, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_3, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_3);
            }
		}

		if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_4'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle4_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem4 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID4'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice4'] = $_POST['ItemPrice4'] - ($_POST['ItemPrice4'] * $_SESSION['Promo_Code_Discount']);
                    $decExtendedPrice = $_POST['ItemPrice4'] * $_POST['ItemQty4'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice4'] * $_POST['ItemQty4'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem4, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem4, "@prmItemID", $_POST['ItemID4'], SQLINT4);
			    mssql_bind($qryInsertItem4, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			    mssql_bind($qryInsertItem4, "@prmQty", $_POST['ItemQty4'], SQLINT4);
			    mssql_bind($qryInsertItem4, "@prmUnitPrice", $_POST['ItemPrice4'], SQLFLT8);
			    mssql_bind($qryInsertItem4, "@prmUnitPrice", $_POST['ItemPrice4'], SQLFLT8);
			    mssql_bind($qryInsertItem4, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem4, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem4, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs4 = mssql_execute($qryInsertItem4);
            }
            else
            {
			    $qryInsertItem4 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID4'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice4'] = $_POST['ItemPrice4'] - ($_POST['ItemPrice4'] * $_SESSION['Promo_Code_Discount']);
                    $decExtendedPrice = $_POST['ItemPrice4'] * $_POST['ItemQty4'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice4'] * $_POST['ItemQty4'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem4, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem4, "@prmItemID", $_POST['ItemID4'], SQLINT4);
			    mssql_bind($qryInsertItem4, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			    mssql_bind($qryInsertItem4, "@prmQty", $_POST['ItemQty4'], SQLINT4);
			    mssql_bind($qryInsertItem4, "@prmUnitPrice", $_POST['ItemPrice4'], SQLFLT8);
			    mssql_bind($qryInsertItem4, "@prmUnitPrice", $_POST['ItemPrice4'], SQLFLT8);
			    mssql_bind($qryInsertItem4, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem4, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_4'], SQLFLT8);
			    mssql_bind($qryInsertItem4, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_4'], SQLVARCHAR);
			    mssql_bind($qryInsertItem4, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_4'], SQLVARCHAR);
			    mssql_bind($qryInsertItem4, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_4'], SQLVARCHAR);

			    mssql_bind($qryInsertItem4, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs4 = mssql_execute($qryInsertItem4);
            }

			if (isset($_POST['ItemFree4']) && $_POST['ItemFree4'] != 0)
			{
				$intItemQty = $_POST['ItemFree4'];
				$decItemPrice = 0;
				$qryInsertFreeItem4 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem4, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID4'] == '124' || $_POST['ItemID4'] == '139' || $_POST['ItemID4'] == '141' || $_POST['ItemID4'] == '142')
                {
                    $_POST['ItemID4'] = '100';
				    mssql_bind($qryInsertFreeItem4, "@prmItemID", $_POST['ItemID4'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem4, "@prmItemID", $_POST['ItemID4'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem4, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem4, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem4, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem4, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem4, "RETVAL", $intFreeItemStatusCode, SQLINT4);

				$rs4a = mssql_execute($qryInsertFreeItem4);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_4'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_4']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle4_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID4'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_4'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_4'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_4 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_4, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_4, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_4, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_4, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_4, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_4, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_4, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_4, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_4, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_4, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_4, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_4);
            }

            if ($_SESSION['Regenesis_2285_4'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_4'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_4 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_4, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_4, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_4, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_4, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_4, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_4, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_4, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_4, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_4, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_4, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_4, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_4);
            }
		}

		if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_5'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle5_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem5 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID5'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                   $_POST['ItemPrice5'] = $_POST['ItemPrice5'] - ($_POST['ItemPrice5'] * $_SESSION['Promo_Code_Discount']);
			       $decExtendedPrice = $_POST['ItemPrice5'] * $_POST['ItemQty5'];
                }
                else
                {
			       $decExtendedPrice = $_POST['ItemPrice5'] * $_POST['ItemQty5'];
                }

                // BIND PARAMETERS
                $intItemStatusCode = 0;
                mssql_bind($qryInsertItem5, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertItem5, "@prmItemID", $_POST['ItemID5'], SQLINT4);
                mssql_bind($qryInsertItem5, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
                mssql_bind($qryInsertItem5, "@prmQty", $_POST['ItemQty5'], SQLINT4);
                mssql_bind($qryInsertItem5, "@prmUnitPrice", $_POST['ItemPrice5'], SQLFLT8);
                mssql_bind($qryInsertItem5, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem5, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                mssql_bind($qryInsertItem5, "RETVAL", $intItemStatusCode, SQLINT2);

                $rs5 = mssql_execute($qryInsertItem5);
            }
            else
            {
			    $qryInsertItem5 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID5'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                   $_POST['ItemPrice5'] = $_POST['ItemPrice5'] - ($_POST['ItemPrice5'] * $_SESSION['Promo_Code_Discount']);
			       $decExtendedPrice = $_POST['ItemPrice5'] * $_POST['ItemQty5'];
                }
                else
                {
			       $decExtendedPrice = $_POST['ItemPrice5'] * $_POST['ItemQty5'];
                }

                // BIND PARAMETERS
                $intItemStatusCode = 0;
                mssql_bind($qryInsertItem5, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertItem5, "@prmItemID", $_POST['ItemID5'], SQLINT4);
                mssql_bind($qryInsertItem5, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
                mssql_bind($qryInsertItem5, "@prmQty", $_POST['ItemQty5'], SQLINT4);
                mssql_bind($qryInsertItem5, "@prmUnitPrice", $_POST['ItemPrice5'], SQLFLT8);
                mssql_bind($qryInsertItem5, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem5, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_5'], SQLFLT8);
			    mssql_bind($qryInsertItem5, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_5'], SQLVARCHAR);
			    mssql_bind($qryInsertItem5, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_5'], SQLVARCHAR);
			    mssql_bind($qryInsertItem5, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_5'], SQLVARCHAR);

                mssql_bind($qryInsertItem5, "RETVAL", $intItemStatusCode, SQLINT2);

                $rs5 = mssql_execute($qryInsertItem5);
            }

			if (isset($_POST['ItemFree5']) && $_POST['ItemFree5'] != 0)
			{
				$intItemQty = $_POST['ItemFree5'];
				$decItemPrice = 0;
				$qryInsertFreeItem5 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem5, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID5'] == '124' || $_POST['ItemID5'] == '139' || $_POST['ItemID5'] == '141' || $_POST['ItemID5'] == '142')
                {
                    $_POST['ItemID5'] = '100';
				    mssql_bind($qryInsertFreeItem5, "@prmItemID", $_POST['ItemID5'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem5, "@prmItemID", $_POST['ItemID5'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem5, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem5, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem5, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem5, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem5, "RETVAL", $intFreeItemStatusCode, SQLINT5);

				$rs5a = mssql_execute($qryInsertFreeItem5);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_5'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_5']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle5_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID5'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_5'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_5'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_5 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_5, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_5, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_5, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_5, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_5, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_5, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_5, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_5, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_5, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_5, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_5, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_5);
            }

            if ($_SESSION['Regenesis_2285_5'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_5'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_5 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_5, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_5, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_5, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_5, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_5, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_5, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_5, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_5, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_5, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_5, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_5, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_5);
            }
		}

		if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_6'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle6_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem6 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID6'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice6'] = $_POST['ItemPrice6'] - ($_POST['ItemPrice6'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice6'] * $_POST['ItemQty6'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice6'] * $_POST['ItemQty6'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem6, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem6, "@prmItemID", $_POST['ItemID6'], SQLINT4);
			    mssql_bind($qryInsertItem6, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			    mssql_bind($qryInsertItem6, "@prmQty", $_POST['ItemQty6'], SQLINT4);
                mssql_bind($qryInsertItem6, "@prmUnitPrice", $_POST['ItemPrice6'], SQLFLT8);
			    mssql_bind($qryInsertItem6, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem6, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem6, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs6 = mssql_execute($qryInsertItem6);
            }
            else
            {
			    $qryInsertItem6 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID6'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice6'] = $_POST['ItemPrice6'] - ($_POST['ItemPrice6'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice6'] * $_POST['ItemQty6'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice6'] * $_POST['ItemQty6'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem6, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem6, "@prmItemID", $_POST['ItemID6'], SQLINT4);
			    mssql_bind($qryInsertItem6, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			    mssql_bind($qryInsertItem6, "@prmQty", $_POST['ItemQty6'], SQLINT4);
                mssql_bind($qryInsertItem6, "@prmUnitPrice", $_POST['ItemPrice6'], SQLFLT8);
			    mssql_bind($qryInsertItem6, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem6, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_6'], SQLFLT8);
			    mssql_bind($qryInsertItem6, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_6'], SQLVARCHAR);
			    mssql_bind($qryInsertItem6, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_6'], SQLVARCHAR);
			    mssql_bind($qryInsertItem6, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_6'], SQLVARCHAR);

			    mssql_bind($qryInsertItem6, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs6 = mssql_execute($qryInsertItem6);
            }

			if (isset($_POST['ItemFree6']) && $_POST['ItemFree6'] != 0)
			{
				$intItemQty = $_POST['ItemFree6'];
				$decItemPrice = 0;
				$qryInsertFreeItem6 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem6, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID6'] == '124'  || $_POST['ItemID6'] == '139'|| $_POST['ItemID6'] == '141' || $_POST['ItemID6'] == '142')
                {
                    $_POST['ItemID6'] = '100';
				    mssql_bind($qryInsertFreeItem6, "@prmItemID", $_POST['ItemID6'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem6, "@prmItemID", $_POST['ItemID6'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem6, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem6, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem6, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem6, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem6, "RETVAL", $intFreeItemStatusCode, SQLINT6);

				$rs6a = mssql_execute($qryInsertFreeItem6);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_6'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_6']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle6_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID6'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_6'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_6'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_6 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_6, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_6, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_6, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_6, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_6, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_6, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_6, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_6, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_6, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_6, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_6, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_6);
            }

            if ($_SESSION['Regenesis_2285_6'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_6'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_6 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_6, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_6, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_6, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_6, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_6, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_6, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_6, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_6, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_6, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_6, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_6, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_6);
            }
		}

		if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_7'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle7_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem7 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID7'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice7'] = $_POST['ItemPrice7'] - ($_POST['ItemPrice7'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice7'] * $_POST['ItemQty7'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice7'] * $_POST['ItemQty7'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem7, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem7, "@prmItemID", $_POST['ItemID7'], SQLINT4);
			    mssql_bind($qryInsertItem7, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			    mssql_bind($qryInsertItem7, "@prmQty", $_POST['ItemQty7'], SQLINT4);
			    mssql_bind($qryInsertItem7, "@prmUnitPrice", $_POST['ItemPrice7'], SQLFLT8);
			    mssql_bind($qryInsertItem7, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem7, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem7, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs7 = mssql_execute($qryInsertItem7);
            }
            else
            {
			    $qryInsertItem7 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID7'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice7'] = $_POST['ItemPrice7'] - ($_POST['ItemPrice7'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice7'] * $_POST['ItemQty7'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice7'] * $_POST['ItemQty7'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem7, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem7, "@prmItemID", $_POST['ItemID7'], SQLINT4);
			    mssql_bind($qryInsertItem7, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			    mssql_bind($qryInsertItem7, "@prmQty", $_POST['ItemQty7'], SQLINT4);
			    mssql_bind($qryInsertItem7, "@prmUnitPrice", $_POST['ItemPrice7'], SQLFLT8);
			    mssql_bind($qryInsertItem7, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem7, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_7'], SQLFLT8);
			    mssql_bind($qryInsertItem7, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_7'], SQLVARCHAR);
			    mssql_bind($qryInsertItem7, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_7'], SQLVARCHAR);
			    mssql_bind($qryInsertItem7, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_7'], SQLVARCHAR);

			    mssql_bind($qryInsertItem7, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs7 = mssql_execute($qryInsertItem7);
            }

			if (isset($_POST['ItemFree7']) && $_POST['ItemFree7'] != 0)
			{
				$intItemQty = $_POST['ItemFree7'];
				$decItemPrice = 0;
				$qryInsertFreeItem7 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem7, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID7'] == '124' || $_POST['ItemID7'] == '139' || $_POST['ItemID7'] == '141' || $_POST['ItemID7'] == '142')
                {
                    $_POST['ItemID7'] = '100';
				    mssql_bind($qryInsertFreeItem7, "@prmItemID", $_POST['ItemID7'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem7, "@prmItemID", $_POST['ItemID7'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem7, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem7, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem7, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem7, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem7, "RETVAL", $intFreeItemStatusCode, SQLINT7);

				$rs7a = mssql_execute($qryInsertFreeItem7);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_7'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_7']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle7_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID7'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_7'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_7'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_7 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_7, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_7, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_7, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_7, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_7, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_7, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_7, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_7, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_7, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_7, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_7, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_7);
            }

            if ($_SESSION['Regenesis_2285_7'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_7'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_7 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_7, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_7, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_7, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_7, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_7, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_7, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_7, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_7, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_7, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_7, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_7, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_7);
            }
		}

		if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_8'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle8_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem8 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID8'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice8'] = $_POST['ItemPrice8'] - ($_POST['ItemPrice8'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice8'] * $_POST['ItemQty8'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice8'] * $_POST['ItemQty8'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem8, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem8, "@prmItemID", $_POST['ItemID8'], SQLINT4);
			    mssql_bind($qryInsertItem8, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			    mssql_bind($qryInsertItem8, "@prmQty", $_POST['ItemQty8'], SQLINT4);
			    mssql_bind($qryInsertItem8, "@prmUnitPrice", $_POST['ItemPrice8'], SQLFLT8);
			    mssql_bind($qryInsertItem8, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem8, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem8, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs8 = mssql_execute($qryInsertItem8);
            }
            else
            {
			    $qryInsertItem8 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID8'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice8'] = $_POST['ItemPrice8'] - ($_POST['ItemPrice8'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice8'] * $_POST['ItemQty8'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice8'] * $_POST['ItemQty8'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem8, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem8, "@prmItemID", $_POST['ItemID8'], SQLINT4);
			    mssql_bind($qryInsertItem8, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			    mssql_bind($qryInsertItem8, "@prmQty", $_POST['ItemQty8'], SQLINT4);
			    mssql_bind($qryInsertItem8, "@prmUnitPrice", $_POST['ItemPrice8'], SQLFLT8);
			    mssql_bind($qryInsertItem8, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem8, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_8'], SQLFLT8);
			    mssql_bind($qryInsertItem8, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_8'], SQLVARCHAR);
			    mssql_bind($qryInsertItem8, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_8'], SQLVARCHAR);
			    mssql_bind($qryInsertItem8, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_8'], SQLVARCHAR);

			    mssql_bind($qryInsertItem8, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs8 = mssql_execute($qryInsertItem8);
            }

			if (isset($_POST['ItemFree8']) && $_POST['ItemFree8'] != 0)
			{
				$intItemQty = $_POST['ItemFree8'];
				$decItemPrice = 0;
				$qryInsertFreeItem8 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem8, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID8'] == '124' || $_POST['ItemID8'] == '139' || $_POST['ItemID8'] == '141' || $_POST['ItemID8'] == '142')
                {
                    $_POST['ItemID8'] = '100';
                    mssql_bind($qryInsertFreeItem8, "@prmItemID", $_POST['ItemID8'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem8, "@prmItemID", $_POST['ItemID8'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem8, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem8, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem8, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem8, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem8, "RETVAL", $intFreeItemStatusCode, SQLINT8);

				$rs8a = mssql_execute($qryInsertFreeItem8);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_8'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_8']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle8_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID8'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_8'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_8'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_8 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_8, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_8, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_8, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_8, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_8, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_8, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_8, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_8, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_8, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_8, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_8, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_8);
            }

            if ($_SESSION['Regenesis_2285_8'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_8'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_8 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_8, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_8, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_8, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_8, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_8, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_8, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_8, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_8, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_8, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_8, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_8, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_8);
            }
		}

		if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_9'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle9_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem9 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID9'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice9'] = $_POST['ItemPrice9'] - ($_POST['ItemPrice9'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice9'] * $_POST['ItemQty9'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice9'] * $_POST['ItemQty9'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem9, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem9, "@prmItemID", $_POST['ItemID9'], SQLINT4);
			    mssql_bind($qryInsertItem9, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			    mssql_bind($qryInsertItem9, "@prmQty", $_POST['ItemQty9'], SQLINT4);
			    mssql_bind($qryInsertItem9, "@prmUnitPrice", $_POST['ItemPrice9'], SQLFLT8);
			    mssql_bind($qryInsertItem9, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem9, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem9, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs9 = mssql_execute($qryInsertItem9);
            }
            else
            {
			    $qryInsertItem9 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID9'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice9'] = $_POST['ItemPrice9'] - ($_POST['ItemPrice9'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice9'] * $_POST['ItemQty9'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice9'] * $_POST['ItemQty9'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem9, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem9, "@prmItemID", $_POST['ItemID9'], SQLINT4);
			    mssql_bind($qryInsertItem9, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			    mssql_bind($qryInsertItem9, "@prmQty", $_POST['ItemQty9'], SQLINT4);
			    mssql_bind($qryInsertItem9, "@prmUnitPrice", $_POST['ItemPrice9'], SQLFLT8);
			    mssql_bind($qryInsertItem9, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem9, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_9'], SQLFLT8);
			    mssql_bind($qryInsertItem9, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_9'], SQLVARCHAR);
			    mssql_bind($qryInsertItem9, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_9'], SQLVARCHAR);
			    mssql_bind($qryInsertItem9, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_9'], SQLVARCHAR);

			    mssql_bind($qryInsertItem9, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs9 = mssql_execute($qryInsertItem9);
            }

			if (isset($_POST['ItemFree9']) && $_POST['ItemFree9'] != 0)
			{
				$intItemQty = $_POST['ItemFree9'];
				$decItemPrice = 0;
				$qryInsertFreeItem9 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem9, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID9'] == '124' || $_POST['ItemID9'] == '139' || $_POST['ItemID9'] == '141' || $_POST['ItemID9'] == '142')
                {
                    $_POST['ItemID9'] = '100';
				    mssql_bind($qryInsertFreeItem9, "@prmItemID", $_POST['ItemID9'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem9, "@prmItemID", $_POST['ItemID9'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem9, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem9, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem9, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem9, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem9, "RETVAL", $intFreeItemStatusCode, SQLINT9);

				$rs9a = mssql_execute($qryInsertFreeItem9);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_9'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_9']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle9_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID9'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_9'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_9'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_9 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_9, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_9, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_9, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_9, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_9, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_9, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_9, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_9, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_9, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_9, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_9, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_9);
            }

            if ($_SESSION['Regenesis_2285_9'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_9'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_9 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_9, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_9, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_9, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_9, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_9, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_9, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_9, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_9, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_9, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_9, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_9, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_9);
            }
		}

		if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_10'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle10_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem10 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID10'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice10'] = $_POST['ItemPrice10'] - ($_POST['ItemPrice10'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice10'] * $_POST['ItemQty10'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice10'] * $_POST['ItemQty10'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem10, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem10, "@prmItemID", $_POST['ItemID10'], SQLINT4);
			    mssql_bind($qryInsertItem10, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			    mssql_bind($qryInsertItem10, "@prmQty", $_POST['ItemQty10'], SQLINT4);
			    mssql_bind($qryInsertItem10, "@prmUnitPrice", $_POST['ItemPrice10'], SQLFLT8);
			    mssql_bind($qryInsertItem10, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem10, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem10, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs10 = mssql_execute($qryInsertItem10);
            }
            else
            {
			    $qryInsertItem10 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID10'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice10'] = $_POST['ItemPrice10'] - ($_POST['ItemPrice10'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice10'] * $_POST['ItemQty10'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice10'] * $_POST['ItemQty10'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem10, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem10, "@prmItemID", $_POST['ItemID10'], SQLINT4);
			    mssql_bind($qryInsertItem10, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			    mssql_bind($qryInsertItem10, "@prmQty", $_POST['ItemQty10'], SQLINT4);
			    mssql_bind($qryInsertItem10, "@prmUnitPrice", $_POST['ItemPrice10'], SQLFLT8);
			    mssql_bind($qryInsertItem10, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem10, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_10'], SQLFLT8);
			    mssql_bind($qryInsertItem10, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_10'], SQLVARCHAR);
			    mssql_bind($qryInsertItem10, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_10'], SQLVARCHAR);
			    mssql_bind($qryInsertItem10, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_10'], SQLVARCHAR);

			    mssql_bind($qryInsertItem10, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs10 = mssql_execute($qryInsertItem10);
            }

			if (isset($_POST['ItemFree10']) && $_POST['ItemFree10'] != 0)
			{
				$intItemQty = $_POST['ItemFree10'];
				$decItemPrice = 0;
				$qryInsertFreeItem10 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem10, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID10'] == '124' || $_POST['ItemID10'] == '139' || $_POST['ItemID10'] == '141' || $_POST['ItemID10'] == '142')
                {
                    $_POST['ItemID10'] = '100';
				    mssql_bind($qryInsertFreeItem10, "@prmItemID", $_POST['ItemID10'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem10, "@prmItemID", $_POST['ItemID10'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem10, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem10, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem10, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem10, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem10, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs10a = mssql_execute($qryInsertFreeItem10);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_10'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_10']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle10_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID10'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_10'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_10'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_10 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_10, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_10, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_10, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_10, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_10, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_10, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_10, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_10, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_10, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_10, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_10, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_10);
            }

            if ($_SESSION['Regenesis_2285_10'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_10'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_10 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_10, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_10, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_10, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_10, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_10, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_10, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_10, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_10, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_10, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_10, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_10, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_10);
            }
		}

        // GMC - 03/18/10 - Add 10 Line Items Admin

		if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_11'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle11_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem11 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID11'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice11'] = $_POST['ItemPrice11'] - ($_POST['ItemPrice11'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice11'] * $_POST['ItemQty11'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice11'] * $_POST['ItemQty11'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem11, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem11, "@prmItemID", $_POST['ItemID11'], SQLINT4);
			    mssql_bind($qryInsertItem11, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			    mssql_bind($qryInsertItem11, "@prmQty", $_POST['ItemQty11'], SQLINT4);
			    mssql_bind($qryInsertItem11, "@prmUnitPrice", $_POST['ItemPrice11'], SQLFLT8);
			    mssql_bind($qryInsertItem11, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem11, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem11, "RETVAL", $intItemStatusCode, SQLINT2);

			   $rs11 = mssql_execute($qryInsertItem11);
            }
            else
            {
			    $qryInsertItem11 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID11'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice11'] = $_POST['ItemPrice11'] - ($_POST['ItemPrice11'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice11'] * $_POST['ItemQty11'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice11'] * $_POST['ItemQty11'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem11, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem11, "@prmItemID", $_POST['ItemID11'], SQLINT4);
			    mssql_bind($qryInsertItem11, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			    mssql_bind($qryInsertItem11, "@prmQty", $_POST['ItemQty11'], SQLINT4);
			    mssql_bind($qryInsertItem11, "@prmUnitPrice", $_POST['ItemPrice11'], SQLFLT8);
			    mssql_bind($qryInsertItem11, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem11, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_11'], SQLFLT8);
			    mssql_bind($qryInsertItem11, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_11'], SQLVARCHAR);
			    mssql_bind($qryInsertItem11, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_11'], SQLVARCHAR);
			    mssql_bind($qryInsertItem11, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_11'], SQLVARCHAR);

			    mssql_bind($qryInsertItem11, "RETVAL", $intItemStatusCode, SQLINT2);

			   $rs11 = mssql_execute($qryInsertItem11);
            }

			if (isset($_POST['ItemFree11']) && $_POST['ItemFree11'] != 0)
			{
				$intItemQty = $_POST['ItemFree11'];
				$decItemPrice = 0;
				$qryInsertFreeItem11 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem11, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID11'] == '124' || $_POST['ItemID11'] == '139' || $_POST['ItemID11'] == '141' || $_POST['ItemID11'] == '142')
                {
                    $_POST['ItemID11'] = '100';
				    mssql_bind($qryInsertFreeItem11, "@prmItemID", $_POST['ItemID11'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem11, "@prmItemID", $_POST['ItemID11'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem11, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem11, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem11, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem11, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem11, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs11a = mssql_execute($qryInsertFreeItem11);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_11'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_11']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle11_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID11'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_11'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_11'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_11 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_11, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_11, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_11, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_11, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_11, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_11, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_11, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_11, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_11, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_11, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_11, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_11);
            }

            if ($_SESSION['Regenesis_2285_11'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_11'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_11 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_11, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_11, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_11, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_11, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_11, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_11, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_11, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_11, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_11, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_11, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_11, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_11);
            }
		}

		if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_12'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle12_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem12 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID12'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice12'] = $_POST['ItemPrice12'] - ($_POST['ItemPrice12'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice12'] * $_POST['ItemQty12'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice12'] * $_POST['ItemQty12'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem12, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem12, "@prmItemID", $_POST['ItemID12'], SQLINT4);
			    mssql_bind($qryInsertItem12, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			    mssql_bind($qryInsertItem12, "@prmQty", $_POST['ItemQty12'], SQLINT4);
			    mssql_bind($qryInsertItem12, "@prmUnitPrice", $_POST['ItemPrice12'], SQLFLT8);
			    mssql_bind($qryInsertItem12, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem12, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem12, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs12 = mssql_execute($qryInsertItem12);
            }
            else
            {
			    $qryInsertItem12 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID12'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice12'] = $_POST['ItemPrice12'] - ($_POST['ItemPrice12'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice12'] * $_POST['ItemQty12'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice12'] * $_POST['ItemQty12'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem12, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem12, "@prmItemID", $_POST['ItemID12'], SQLINT4);
			    mssql_bind($qryInsertItem12, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			    mssql_bind($qryInsertItem12, "@prmQty", $_POST['ItemQty12'], SQLINT4);
			    mssql_bind($qryInsertItem12, "@prmUnitPrice", $_POST['ItemPrice12'], SQLFLT8);
			    mssql_bind($qryInsertItem12, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem12, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_12'], SQLFLT8);
			    mssql_bind($qryInsertItem12, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_12'], SQLVARCHAR);
			    mssql_bind($qryInsertItem12, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_12'], SQLVARCHAR);
			    mssql_bind($qryInsertItem12, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_12'], SQLVARCHAR);

			    mssql_bind($qryInsertItem12, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs12 = mssql_execute($qryInsertItem12);
            }

			if (isset($_POST['ItemFree12']) && $_POST['ItemFree12'] != 0)
			{
				$intItemQty = $_POST['ItemFree12'];
				$decItemPrice = 0;
				$qryInsertFreeItem12 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem12, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID12'] == '124' || $_POST['ItemID12'] == '139' || $_POST['ItemID12'] == '141' || $_POST['ItemID12'] == '142')
                {
                    $_POST['ItemID12'] = '100';
				    mssql_bind($qryInsertFreeItem12, "@prmItemID", $_POST['ItemID12'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem12, "@prmItemID", $_POST['ItemID12'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem12, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem12, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem12, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem12, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem12, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs12a = mssql_execute($qryInsertFreeItem12);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_12'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_12']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle12_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID12'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_12'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_12'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_12 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_12, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_12, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_12, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_12, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_12, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_12, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_12, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_12, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_12, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_12, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_12, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_12);
            }

            if ($_SESSION['Regenesis_2285_12'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_12'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_12 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_12, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_12, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_12, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_12, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_12, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_12, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_12, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_12, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_12, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_12, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_12, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_12);
            }
		}

		if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_13'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle13_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem13 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID13'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice13'] = $_POST['ItemPrice13'] - ($_POST['ItemPrice13'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice13'] * $_POST['ItemQty13'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice13'] * $_POST['ItemQty13'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem13, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem13, "@prmItemID", $_POST['ItemID13'], SQLINT4);
			    mssql_bind($qryInsertItem13, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			    mssql_bind($qryInsertItem13, "@prmQty", $_POST['ItemQty13'], SQLINT4);
			    mssql_bind($qryInsertItem13, "@prmUnitPrice", $_POST['ItemPrice13'], SQLFLT8);
			    mssql_bind($qryInsertItem13, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem13, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem13, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs13 = mssql_execute($qryInsertItem13);
            }
            else
            {
			    $qryInsertItem13 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID13'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice13'] = $_POST['ItemPrice13'] - ($_POST['ItemPrice13'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice13'] * $_POST['ItemQty13'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice13'] * $_POST['ItemQty13'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem13, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem13, "@prmItemID", $_POST['ItemID13'], SQLINT4);
			    mssql_bind($qryInsertItem13, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			    mssql_bind($qryInsertItem13, "@prmQty", $_POST['ItemQty13'], SQLINT4);
			    mssql_bind($qryInsertItem13, "@prmUnitPrice", $_POST['ItemPrice13'], SQLFLT8);
			    mssql_bind($qryInsertItem13, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem13, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_13'], SQLFLT8);
			    mssql_bind($qryInsertItem13, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_13'], SQLVARCHAR);
			    mssql_bind($qryInsertItem13, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_13'], SQLVARCHAR);
			    mssql_bind($qryInsertItem13, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_13'], SQLVARCHAR);

			    mssql_bind($qryInsertItem13, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs13 = mssql_execute($qryInsertItem13);
            }

			if (isset($_POST['ItemFree13']) && $_POST['ItemFree13'] != 0)
			{
				$intItemQty = $_POST['ItemFree13'];
				$decItemPrice = 0;
				$qryInsertFreeItem13 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem13, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID13'] == '124' || $_POST['ItemID13'] == '139' || $_POST['ItemID13'] == '141' || $_POST['ItemID13'] == '142')
                {
                    $_POST['ItemID13'] = '100';
				    mssql_bind($qryInsertFreeItem13, "@prmItemID", $_POST['ItemID13'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem13, "@prmItemID", $_POST['ItemID13'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem13, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem13, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem13, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem13, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem13, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs13a = mssql_execute($qryInsertFreeItem13);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_13'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_13']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle13_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID13'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_13'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_13'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_13 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_13, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_13, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_13, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_13, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_13, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_13, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_13, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_13, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_13, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_13, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_13, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_13);
            }

            if ($_SESSION['Regenesis_2285_13'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_13'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_13 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_13, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_13, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_13, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_13, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_13, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_13, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_13, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_13, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_13, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_13, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_13, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_13);
            }
		}

		if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_14'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle14_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem14 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID14'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice14'] = $_POST['ItemPrice14'] - ($_POST['ItemPrice14'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice14'] * $_POST['ItemQty14'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice14'] * $_POST['ItemQty14'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem14, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem14, "@prmItemID", $_POST['ItemID14'], SQLINT4);
			    mssql_bind($qryInsertItem14, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			    mssql_bind($qryInsertItem14, "@prmQty", $_POST['ItemQty14'], SQLINT4);
			    mssql_bind($qryInsertItem14, "@prmUnitPrice", $_POST['ItemPrice14'], SQLFLT8);
			    mssql_bind($qryInsertItem14, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem14, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem14, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs14 = mssql_execute($qryInsertItem14);
            }
            else
            {
			    $qryInsertItem14 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID14'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice14'] = $_POST['ItemPrice14'] - ($_POST['ItemPrice14'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice14'] * $_POST['ItemQty14'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice14'] * $_POST['ItemQty14'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem14, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem14, "@prmItemID", $_POST['ItemID14'], SQLINT4);
			    mssql_bind($qryInsertItem14, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			    mssql_bind($qryInsertItem14, "@prmQty", $_POST['ItemQty14'], SQLINT4);
			    mssql_bind($qryInsertItem14, "@prmUnitPrice", $_POST['ItemPrice14'], SQLFLT8);
			    mssql_bind($qryInsertItem14, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem14, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_14'], SQLFLT8);
			    mssql_bind($qryInsertItem14, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_14'], SQLVARCHAR);
			    mssql_bind($qryInsertItem14, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_14'], SQLVARCHAR);
			    mssql_bind($qryInsertItem14, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_14'], SQLVARCHAR);

			    mssql_bind($qryInsertItem14, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs14 = mssql_execute($qryInsertItem14);
            }

			if (isset($_POST['ItemFree14']) && $_POST['ItemFree14'] != 0)
			{
				$intItemQty = $_POST['ItemFree14'];
				$decItemPrice = 0;
				$qryInsertFreeItem14 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem14, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID14'] == '124' || $_POST['ItemID14'] == '139' || $_POST['ItemID14'] == '141' || $_POST['ItemID14'] == '142')
                {
                    $_POST['ItemID14'] = '100';
				    mssql_bind($qryInsertFreeItem14, "@prmItemID", $_POST['ItemID14'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem14, "@prmItemID", $_POST['ItemID14'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem14, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem14, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem14, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem14, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem14, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs14a = mssql_execute($qryInsertFreeItem14);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_14'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_14']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle14_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID14'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_14'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_14'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_14 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_14, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_14, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_14, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_14, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_14, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_14, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_14, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_14, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_14, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_14, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_14, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_14);
            }

            if ($_SESSION['Regenesis_2285_14'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_14'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_14 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_14, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_14, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_14, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_14, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_14, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_14, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_14, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_14, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_14, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_14, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_14, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_14);
            }
		}

		if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_15'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle15_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem15 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID15'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice15'] = $_POST['ItemPrice15'] - ($_POST['ItemPrice15'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice15'] * $_POST['ItemQty15'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice15'] * $_POST['ItemQty15'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem15, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem15, "@prmItemID", $_POST['ItemID15'], SQLINT4);
			    mssql_bind($qryInsertItem15, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			    mssql_bind($qryInsertItem15, "@prmQty", $_POST['ItemQty15'], SQLINT4);
			    mssql_bind($qryInsertItem15, "@prmUnitPrice", $_POST['ItemPrice15'], SQLFLT8);
			    mssql_bind($qryInsertItem15, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem15, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem15, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs15 = mssql_execute($qryInsertItem15);
            }
            else
            {
			    $qryInsertItem15 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID15'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice15'] = $_POST['ItemPrice15'] - ($_POST['ItemPrice15'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice15'] * $_POST['ItemQty15'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice15'] * $_POST['ItemQty15'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem15, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem15, "@prmItemID", $_POST['ItemID15'], SQLINT4);
			    mssql_bind($qryInsertItem15, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			    mssql_bind($qryInsertItem15, "@prmQty", $_POST['ItemQty15'], SQLINT4);
			    mssql_bind($qryInsertItem15, "@prmUnitPrice", $_POST['ItemPrice15'], SQLFLT8);
			    mssql_bind($qryInsertItem15, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem15, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_15'], SQLFLT8);
			    mssql_bind($qryInsertItem15, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_15'], SQLVARCHAR);
			    mssql_bind($qryInsertItem15, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_15'], SQLVARCHAR);
			    mssql_bind($qryInsertItem15, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_15'], SQLVARCHAR);

			    mssql_bind($qryInsertItem15, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs15 = mssql_execute($qryInsertItem15);
            }

			if (isset($_POST['ItemFree15']) && $_POST['ItemFree15'] != 0)
			{
				$intItemQty = $_POST['ItemFree15'];
				$decItemPrice = 0;
				$qryInsertFreeItem15 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem15, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID15'] == '124' || $_POST['ItemID15'] == '139' || $_POST['ItemID15'] == '141' || $_POST['ItemID15'] == '142')
                {
                    $_POST['ItemID15'] = '100';
				    mssql_bind($qryInsertFreeItem15, "@prmItemID", $_POST['ItemID15'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem15, "@prmItemID", $_POST['ItemID15'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem15, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem15, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem15, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem15, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem15, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs15a = mssql_execute($qryInsertFreeItem15);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_15'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_15']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle15_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID15'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_15'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_15'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_15 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_15, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_15, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_15, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_15, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_15, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_15, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_15, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_15, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_15, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_15, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_15, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_15);
            }

            if ($_SESSION['Regenesis_2285_15'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_15'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_15 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_15, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_15, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_15, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_15, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_15, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_15, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_15, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_15, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_15, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_15, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_15, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_15);
            }
		}

		if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_16'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle16_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem16 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID16'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice16'] = $_POST['ItemPrice16'] - ($_POST['ItemPrice16'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice16'] * $_POST['ItemQty16'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice16'] * $_POST['ItemQty16'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem16, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem16, "@prmItemID", $_POST['ItemID16'], SQLINT4);
			    mssql_bind($qryInsertItem16, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			    mssql_bind($qryInsertItem16, "@prmQty", $_POST['ItemQty16'], SQLINT4);
			    mssql_bind($qryInsertItem16, "@prmUnitPrice", $_POST['ItemPrice16'], SQLFLT8);
			    mssql_bind($qryInsertItem16, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem16, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem16, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs16 = mssql_execute($qryInsertItem16);
            }
            else
            {
			    $qryInsertItem16 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID16'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice16'] = $_POST['ItemPrice16'] - ($_POST['ItemPrice16'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice16'] * $_POST['ItemQty16'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice16'] * $_POST['ItemQty16'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem16, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem16, "@prmItemID", $_POST['ItemID16'], SQLINT4);
			    mssql_bind($qryInsertItem16, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			    mssql_bind($qryInsertItem16, "@prmQty", $_POST['ItemQty16'], SQLINT4);
			    mssql_bind($qryInsertItem16, "@prmUnitPrice", $_POST['ItemPrice16'], SQLFLT8);
			    mssql_bind($qryInsertItem16, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem16, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_16'], SQLFLT8);
			    mssql_bind($qryInsertItem16, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_16'], SQLVARCHAR);
			    mssql_bind($qryInsertItem16, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_16'], SQLVARCHAR);
			    mssql_bind($qryInsertItem16, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_16'], SQLVARCHAR);

			    mssql_bind($qryInsertItem16, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs16 = mssql_execute($qryInsertItem16);
            }

			if (isset($_POST['ItemFree16']) && $_POST['ItemFree16'] != 0)
			{
				$intItemQty = $_POST['ItemFree16'];
				$decItemPrice = 0;
				$qryInsertFreeItem16 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem16, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID16'] == '124' || $_POST['ItemID16'] == '139' || $_POST['ItemID16'] == '141' || $_POST['ItemID16'] == '142')
                {
                    $_POST['ItemID16'] = '100';
				    mssql_bind($qryInsertFreeItem16, "@prmItemID", $_POST['ItemID16'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem16, "@prmItemID", $_POST['ItemID16'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem16, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem16, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem16, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem16, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem16, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs16a = mssql_execute($qryInsertFreeItem16);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_16'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_16']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle16_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID16'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_16'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_16'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_16 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_16, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_16, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_16, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_16, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_16, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_16, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_16, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_16, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_16, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_16, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_16, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_16);
            }

            if ($_SESSION['Regenesis_2285_16'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_16'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_16 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_16, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_16, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_16, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_16, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_16, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_16, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_16, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_16, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_16, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_16, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_16, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_16);
            }
		}

		if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_17'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle17_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem17 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID17'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice17'] = $_POST['ItemPrice17'] - ($_POST['ItemPrice17'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice17'] * $_POST['ItemQty17'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice17'] * $_POST['ItemQty17'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem17, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem17, "@prmItemID", $_POST['ItemID17'], SQLINT4);
			    mssql_bind($qryInsertItem17, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			    mssql_bind($qryInsertItem17, "@prmQty", $_POST['ItemQty17'], SQLINT4);
			    mssql_bind($qryInsertItem17, "@prmUnitPrice", $_POST['ItemPrice17'], SQLFLT8);
			    mssql_bind($qryInsertItem17, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem17, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem17, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs17 = mssql_execute($qryInsertItem17);
            }
            else
            {
			    $qryInsertItem17 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID17'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice17'] = $_POST['ItemPrice17'] - ($_POST['ItemPrice17'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice17'] * $_POST['ItemQty17'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice17'] * $_POST['ItemQty17'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem17, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem17, "@prmItemID", $_POST['ItemID17'], SQLINT4);
			    mssql_bind($qryInsertItem17, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			    mssql_bind($qryInsertItem17, "@prmQty", $_POST['ItemQty17'], SQLINT4);
			    mssql_bind($qryInsertItem17, "@prmUnitPrice", $_POST['ItemPrice17'], SQLFLT8);
			    mssql_bind($qryInsertItem17, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem17, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_17'], SQLFLT8);
			    mssql_bind($qryInsertItem17, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_17'], SQLVARCHAR);
			    mssql_bind($qryInsertItem17, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_17'], SQLVARCHAR);
			    mssql_bind($qryInsertItem17, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_17'], SQLVARCHAR);

			    mssql_bind($qryInsertItem17, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs17 = mssql_execute($qryInsertItem17);
            }

			if (isset($_POST['ItemFree17']) && $_POST['ItemFree17'] != 0)
			{
				$intItemQty = $_POST['ItemFree17'];
				$decItemPrice = 0;
				$qryInsertFreeItem17 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem17, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID17'] == '124' || $_POST['ItemID17'] == '139' || $_POST['ItemID17'] == '141' || $_POST['ItemID17'] == '142')
                {
                    $_POST['ItemID17'] = '100';
				    mssql_bind($qryInsertFreeItem17, "@prmItemID", $_POST['ItemID17'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem17, "@prmItemID", $_POST['ItemID17'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem17, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem17, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem17, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem17, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem17, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs17a = mssql_execute($qryInsertFreeItem17);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_17'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_17']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle17_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID17'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_17'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_17'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_17 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_17, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_17, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_17, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_17, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_17, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_17, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_17, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_17, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_17, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_17, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_17, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_17);
            }

            if ($_SESSION['Regenesis_2285_17'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_17'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_17 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_17, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_17, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_17, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_17, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_17, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_17, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_17, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_17, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_17, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_17, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_17, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_17);
            }
		}

		if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_18'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle18_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem18 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID18'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice18'] = $_POST['ItemPrice18'] - ($_POST['ItemPrice18'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice18'] * $_POST['ItemQty18'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice18'] * $_POST['ItemQty18'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem18, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem18, "@prmItemID", $_POST['ItemID18'], SQLINT4);
			    mssql_bind($qryInsertItem18, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			    mssql_bind($qryInsertItem18, "@prmQty", $_POST['ItemQty18'], SQLINT4);
			    mssql_bind($qryInsertItem18, "@prmUnitPrice", $_POST['ItemPrice18'], SQLFLT8);
			    mssql_bind($qryInsertItem18, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem18, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem18, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs18 = mssql_execute($qryInsertItem18);
            }
            else
            {
			    $qryInsertItem18 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID18'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice18'] = $_POST['ItemPrice18'] - ($_POST['ItemPrice18'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice18'] * $_POST['ItemQty18'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice18'] * $_POST['ItemQty18'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem18, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem18, "@prmItemID", $_POST['ItemID18'], SQLINT4);
			    mssql_bind($qryInsertItem18, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			    mssql_bind($qryInsertItem18, "@prmQty", $_POST['ItemQty18'], SQLINT4);
			    mssql_bind($qryInsertItem18, "@prmUnitPrice", $_POST['ItemPrice18'], SQLFLT8);
			    mssql_bind($qryInsertItem18, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem18, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_18'], SQLFLT8);
			    mssql_bind($qryInsertItem18, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_18'], SQLVARCHAR);
			    mssql_bind($qryInsertItem18, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_18'], SQLVARCHAR);
			    mssql_bind($qryInsertItem18, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_18'], SQLVARCHAR);

			    mssql_bind($qryInsertItem18, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs18 = mssql_execute($qryInsertItem18);
            }

			if (isset($_POST['ItemFree18']) && $_POST['ItemFree18'] != 0)
			{
				$intItemQty = $_POST['ItemFree18'];
				$decItemPrice = 0;
				$qryInsertFreeItem18 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem18, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID18'] == '124' || $_POST['ItemID18'] == '139' || $_POST['ItemID18'] == '141' || $_POST['ItemID18'] == '142')
                {
                    $_POST['ItemID18'] = '100';
				    mssql_bind($qryInsertFreeItem18, "@prmItemID", $_POST['ItemID18'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem18, "@prmItemID", $_POST['ItemID18'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem18, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem18, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem18, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem18, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem18, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs18a = mssql_execute($qryInsertFreeItem18);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_18'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_18']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle18_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID18'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_18'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_18'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_18 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_18, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_18, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_18, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_18, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_18, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_18, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_18, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_18, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_18, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_18, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_18, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_18);
            }

            if ($_SESSION['Regenesis_2285_18'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_18'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_18 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_18, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_18, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_18, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_18, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_18, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_18, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_18, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_18, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_18, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_18, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_18, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_18);
            }
		}

		if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_19'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle19_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem19 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID19'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice19'] = $_POST['ItemPrice19'] - ($_POST['ItemPrice19'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice19'] * $_POST['ItemQty19'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice19'] * $_POST['ItemQty19'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem19, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem19, "@prmItemID", $_POST['ItemID19'], SQLINT4);
			    mssql_bind($qryInsertItem19, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			    mssql_bind($qryInsertItem19, "@prmQty", $_POST['ItemQty19'], SQLINT4);
			    mssql_bind($qryInsertItem19, "@prmUnitPrice", $_POST['ItemPrice19'], SQLFLT8);
			    mssql_bind($qryInsertItem19, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem19, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem19, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs19 = mssql_execute($qryInsertItem19);
            }
            else
            {
			    $qryInsertItem19 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID19'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice19'] = $_POST['ItemPrice19'] - ($_POST['ItemPrice19'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice19'] * $_POST['ItemQty19'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice19'] * $_POST['ItemQty19'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem19, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem19, "@prmItemID", $_POST['ItemID19'], SQLINT4);
			    mssql_bind($qryInsertItem19, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			    mssql_bind($qryInsertItem19, "@prmQty", $_POST['ItemQty19'], SQLINT4);
			    mssql_bind($qryInsertItem19, "@prmUnitPrice", $_POST['ItemPrice19'], SQLFLT8);
			    mssql_bind($qryInsertItem19, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem19, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_19'], SQLFLT8);
			    mssql_bind($qryInsertItem19, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_19'], SQLVARCHAR);
			    mssql_bind($qryInsertItem19, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_19'], SQLVARCHAR);
			    mssql_bind($qryInsertItem19, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_19'], SQLVARCHAR);

			    mssql_bind($qryInsertItem19, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs19 = mssql_execute($qryInsertItem19);
            }

			if (isset($_POST['ItemFree19']) && $_POST['ItemFree19'] != 0)
			{
				$intItemQty = $_POST['ItemFree19'];
				$decItemPrice = 0;
				$qryInsertFreeItem19 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem19, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID19'] == '124' || $_POST['ItemID19'] == '139' || $_POST['ItemID19'] == '141' || $_POST['ItemID19'] == '142')
                {
                    $_POST['ItemID19'] = '100';
				    mssql_bind($qryInsertFreeItem19, "@prmItemID", $_POST['ItemID19'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem19, "@prmItemID", $_POST['ItemID19'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem19, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem19, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem19, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem19, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem19, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs19a = mssql_execute($qryInsertFreeItem19);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_19'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_19']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle19_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID19'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_19'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_19'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_19 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_19, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_19, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_19, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_19, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_19, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_19, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_19, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_19, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_19, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_19, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_19, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_19);
            }

            if ($_SESSION['Regenesis_2285_19'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_19'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_19 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_19, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_19, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_19, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_19, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_19, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_19, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_19, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_19, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_19, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_19, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_19, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_19);
            }
		}

		if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
		{
            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            if($_SESSION['Bundles2010_20'] != '')
            {
                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle20_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }

                $qryInsertItem20 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID20'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice20'] = $_POST['ItemPrice20'] - ($_POST['ItemPrice20'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice20'] * $_POST['ItemQty20'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice20'] * $_POST['ItemQty20'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem20, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem20, "@prmItemID", $_POST['ItemID20'], SQLINT4);
			    mssql_bind($qryInsertItem20, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			    mssql_bind($qryInsertItem20, "@prmQty", $_POST['ItemQty20'], SQLINT4);
			    mssql_bind($qryInsertItem20, "@prmUnitPrice", $_POST['ItemPrice20'], SQLFLT8);
			    mssql_bind($qryInsertItem20, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem20, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem20, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs20 = mssql_execute($qryInsertItem20);
            }
            else
            {
			    $qryInsertItem20 = mssql_init("spOrders_Items_Create", $connProcess);

                // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
                // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
                if($_POST['ItemID20'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice20'] = $_POST['ItemPrice20'] - ($_POST['ItemPrice20'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice20'] * $_POST['ItemQty20'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice20'] * $_POST['ItemQty20'];
                }

			    // BIND PARAMETERS
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem20, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem20, "@prmItemID", $_POST['ItemID20'], SQLINT4);
			    mssql_bind($qryInsertItem20, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			    mssql_bind($qryInsertItem20, "@prmQty", $_POST['ItemQty20'], SQLINT4);
			    mssql_bind($qryInsertItem20, "@prmUnitPrice", $_POST['ItemPrice20'], SQLFLT8);
			    mssql_bind($qryInsertItem20, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem20, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_20'], SQLFLT8);
			    mssql_bind($qryInsertItem20, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_20'], SQLVARCHAR);
			    mssql_bind($qryInsertItem20, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_20'], SQLVARCHAR);
			    mssql_bind($qryInsertItem20, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_20'], SQLVARCHAR);

			    mssql_bind($qryInsertItem20, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs20 = mssql_execute($qryInsertItem20);
            }

			if (isset($_POST['ItemFree20']) && $_POST['ItemFree20'] != 0)
			{
				$intItemQty = $_POST['ItemFree20'];
				$decItemPrice = 0;
				$qryInsertFreeItem20 = mssql_init("spOrders_Items_Create", $connProcess);

				// BIND PARAMETERS
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem20, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);

                // GMC - 11/22/08 - To accomodate ID=139 to FREE=100
				// GMC - 11/05/08 - To accomodate ID=124 to FREE=100
                // GMC - 11/20/08 - To accomodate ID=141 and ID=142 to FREE=100
                if($_POST['ItemID20'] == '124' || $_POST['ItemID20'] == '139' || $_POST['ItemID20'] == '141' || $_POST['ItemID20'] == '142')
                {
                    $_POST['ItemID20'] = '100';
				    mssql_bind($qryInsertFreeItem20, "@prmItemID", $_POST['ItemID20'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem20, "@prmItemID", $_POST['ItemID20'], SQLINT4);
                }

				mssql_bind($qryInsertFreeItem20, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem20, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem20, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem20, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem20, "RETVAL", $intFreeItemStatusCode, SQLINT10);

				$rs20a = mssql_execute($qryInsertFreeItem20);
			}

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_20'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_20']);

                // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                $intItemCatCount = 1;

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle20_1))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;

                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);

                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);

                    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
                    $intItemCatCount++;
                }
            }

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($_POST['ItemID20'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_20'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_20'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_20 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_20, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_20, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_20, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_20, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_20, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_20, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_20, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_20, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_20, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_20, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_20, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_20);
            }

            if ($_SESSION['Regenesis_2285_20'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_20'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_20 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_20, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_20, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_20, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_20, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_20, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_20, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_20, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_20, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_20, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_20, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_20, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_20);
            }
		}

        // GMC - 10/16/13 - Add 20 Line Items Admin

		if (isset($_POST['ItemID21']) && $_POST['ItemID21'] != 0)
		{
            if($_SESSION['Bundles2010_21'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle21_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem21 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID21'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice21'] = $_POST['ItemPrice21'] - ($_POST['ItemPrice21'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice21'] * $_POST['ItemQty21'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice21'] * $_POST['ItemQty21'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem21, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem21, "@prmItemID", $_POST['ItemID21'], SQLINT4);
			    mssql_bind($qryInsertItem21, "@prmLocation", $_POST['ItemStockLocation21'], SQLVARCHAR);
			    mssql_bind($qryInsertItem21, "@prmQty", $_POST['ItemQty21'], SQLINT4);
			    mssql_bind($qryInsertItem21, "@prmUnitPrice", $_POST['ItemPrice21'], SQLFLT8);
			    mssql_bind($qryInsertItem21, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem21, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem21, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs21 = mssql_execute($qryInsertItem21);
            }
            else
            {
			    $qryInsertItem21 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID21'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice21'] = $_POST['ItemPrice21'] - ($_POST['ItemPrice21'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice21'] * $_POST['ItemQty21'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice21'] * $_POST['ItemQty21'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem21, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem21, "@prmItemID", $_POST['ItemID21'], SQLINT4);
			    mssql_bind($qryInsertItem21, "@prmLocation", $_POST['ItemStockLocation21'], SQLVARCHAR);
			    mssql_bind($qryInsertItem21, "@prmQty", $_POST['ItemQty21'], SQLINT4);
			    mssql_bind($qryInsertItem21, "@prmUnitPrice", $_POST['ItemPrice21'], SQLFLT8);
			    mssql_bind($qryInsertItem21, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem21, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_21'], SQLFLT8);
			    mssql_bind($qryInsertItem21, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_21'], SQLVARCHAR);
			    mssql_bind($qryInsertItem21, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_21'], SQLVARCHAR);
			    mssql_bind($qryInsertItem21, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_21'], SQLVARCHAR);

			    mssql_bind($qryInsertItem21, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs21 = mssql_execute($qryInsertItem21);
            }
			if (isset($_POST['ItemFree21']) && $_POST['ItemFree21'] != 0)
			{
				$intItemQty = $_POST['ItemFree21'];
				$decItemPrice = 0;
				$qryInsertFreeItem21 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem21, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID21'] == '124' || $_POST['ItemID21'] == '139' || $_POST['ItemID21'] == '141' || $_POST['ItemID21'] == '142')
                {
                    $_POST['ItemID21'] = '100';
				    mssql_bind($qryInsertFreeItem21, "@prmItemID", $_POST['ItemID21'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem21, "@prmItemID", $_POST['ItemID21'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem21, "@prmLocation", $_POST['ItemStockLocation21'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem21, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem21, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem21, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem21, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs21a = mssql_execute($qryInsertFreeItem21);
			}
            if($_SESSION['Bundles2010_21'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_21']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle21_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID21'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_21'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_21'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_21 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_21, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_21, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_21, "@prmLocation", $_POST['ItemStockLocation21'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_21, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_21, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_21, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_21, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_21, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_21, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_21, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_21, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_21);
            }

            if ($_SESSION['Regenesis_2285_21'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_21'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_21 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_21, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_21, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_21, "@prmLocation", $_POST['ItemStockLocation21'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_21, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_21, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_21, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_21, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_21, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_21, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_21, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_21, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_21);
            }
		}

		if (isset($_POST['ItemID22']) && $_POST['ItemID22'] != 0)
		{
            if($_SESSION['Bundles2010_22'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle22_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem22 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID22'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice22'] = $_POST['ItemPrice22'] - ($_POST['ItemPrice22'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice22'] * $_POST['ItemQty22'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice22'] * $_POST['ItemQty22'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem22, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem22, "@prmItemID", $_POST['ItemID22'], SQLINT4);
			    mssql_bind($qryInsertItem22, "@prmLocation", $_POST['ItemStockLocation22'], SQLVARCHAR);
			    mssql_bind($qryInsertItem22, "@prmQty", $_POST['ItemQty22'], SQLINT4);
			    mssql_bind($qryInsertItem22, "@prmUnitPrice", $_POST['ItemPrice22'], SQLFLT8);
			    mssql_bind($qryInsertItem22, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem22, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem22, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs22 = mssql_execute($qryInsertItem22);
            }
            else
            {
			    $qryInsertItem22 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID22'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice22'] = $_POST['ItemPrice22'] - ($_POST['ItemPrice22'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice22'] * $_POST['ItemQty22'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice22'] * $_POST['ItemQty22'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem22, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem22, "@prmItemID", $_POST['ItemID22'], SQLINT4);
			    mssql_bind($qryInsertItem22, "@prmLocation", $_POST['ItemStockLocation22'], SQLVARCHAR);
			    mssql_bind($qryInsertItem22, "@prmQty", $_POST['ItemQty22'], SQLINT4);
			    mssql_bind($qryInsertItem22, "@prmUnitPrice", $_POST['ItemPrice22'], SQLFLT8);
			    mssql_bind($qryInsertItem22, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem22, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_22'], SQLFLT8);
			    mssql_bind($qryInsertItem22, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_22'], SQLVARCHAR);
			    mssql_bind($qryInsertItem22, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_22'], SQLVARCHAR);
			    mssql_bind($qryInsertItem22, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_22'], SQLVARCHAR);

			    mssql_bind($qryInsertItem22, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs22 = mssql_execute($qryInsertItem22);
            }
			if (isset($_POST['ItemFree22']) && $_POST['ItemFree22'] != 0)
			{
				$intItemQty = $_POST['ItemFree22'];
				$decItemPrice = 0;
				$qryInsertFreeItem22 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem22, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID22'] == '124' || $_POST['ItemID22'] == '139' || $_POST['ItemID22'] == '141' || $_POST['ItemID22'] == '142')
                {
                    $_POST['ItemID22'] = '100';
				    mssql_bind($qryInsertFreeItem22, "@prmItemID", $_POST['ItemID22'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem22, "@prmItemID", $_POST['ItemID22'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem22, "@prmLocation", $_POST['ItemStockLocation22'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem22, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem22, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem22, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem22, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs22a = mssql_execute($qryInsertFreeItem22);
			}
            if($_SESSION['Bundles2010_22'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_22']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle22_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID22'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_22'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_22'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_22 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_22, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_22, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_22, "@prmLocation", $_POST['ItemStockLocation22'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_22, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_22, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_22, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_22, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_22, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_22, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_22, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_22, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_22);
            }

            if ($_SESSION['Regenesis_2285_22'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_22'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_22 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_22, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_22, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_22, "@prmLocation", $_POST['ItemStockLocation22'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_22, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_22, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_22, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_22, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_22, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_22, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_22, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_22, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_22);
            }
		}

		if (isset($_POST['ItemID23']) && $_POST['ItemID23'] != 0)
		{
            if($_SESSION['Bundles2010_23'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle23_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem23 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID23'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice23'] = $_POST['ItemPrice23'] - ($_POST['ItemPrice23'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice23'] * $_POST['ItemQty23'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice23'] * $_POST['ItemQty23'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem23, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem23, "@prmItemID", $_POST['ItemID23'], SQLINT4);
			    mssql_bind($qryInsertItem23, "@prmLocation", $_POST['ItemStockLocation23'], SQLVARCHAR);
			    mssql_bind($qryInsertItem23, "@prmQty", $_POST['ItemQty23'], SQLINT4);
			    mssql_bind($qryInsertItem23, "@prmUnitPrice", $_POST['ItemPrice23'], SQLFLT8);
			    mssql_bind($qryInsertItem23, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem23, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem23, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs23 = mssql_execute($qryInsertItem23);
            }
            else
            {
			    $qryInsertItem23 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID23'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice23'] = $_POST['ItemPrice23'] - ($_POST['ItemPrice23'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice23'] * $_POST['ItemQty23'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice23'] * $_POST['ItemQty23'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem23, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem23, "@prmItemID", $_POST['ItemID23'], SQLINT4);
			    mssql_bind($qryInsertItem23, "@prmLocation", $_POST['ItemStockLocation23'], SQLVARCHAR);
			    mssql_bind($qryInsertItem23, "@prmQty", $_POST['ItemQty23'], SQLINT4);
			    mssql_bind($qryInsertItem23, "@prmUnitPrice", $_POST['ItemPrice23'], SQLFLT8);
			    mssql_bind($qryInsertItem23, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem23, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_23'], SQLFLT8);
			    mssql_bind($qryInsertItem23, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_23'], SQLVARCHAR);
			    mssql_bind($qryInsertItem23, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_23'], SQLVARCHAR);
			    mssql_bind($qryInsertItem23, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_23'], SQLVARCHAR);

			    mssql_bind($qryInsertItem23, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs23 = mssql_execute($qryInsertItem23);
            }
			if (isset($_POST['ItemFree23']) && $_POST['ItemFree23'] != 0)
			{
				$intItemQty = $_POST['ItemFree23'];
				$decItemPrice = 0;
				$qryInsertFreeItem23 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem23, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID23'] == '124' || $_POST['ItemID23'] == '139' || $_POST['ItemID23'] == '141' || $_POST['ItemID23'] == '142')
                {
                    $_POST['ItemID23'] = '100';
				    mssql_bind($qryInsertFreeItem23, "@prmItemID", $_POST['ItemID23'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem23, "@prmItemID", $_POST['ItemID23'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem23, "@prmLocation", $_POST['ItemStockLocation23'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem23, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem23, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem23, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem23, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs23a = mssql_execute($qryInsertFreeItem23);
			}
            if($_SESSION['Bundles2010_23'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_23']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle23_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID23'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_23'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_23'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_23 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_23, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_23, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_23, "@prmLocation", $_POST['ItemStockLocation23'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_23, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_23, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_23, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_23, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_23, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_23, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_23, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_23, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_23);
            }

            if ($_SESSION['Regenesis_2285_23'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_23'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_23 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_23, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_23, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_23, "@prmLocation", $_POST['ItemStockLocation23'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_23, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_23, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_23, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_23, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_23, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_23, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_23, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_23, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_23);
            }
		}

		if (isset($_POST['ItemID24']) && $_POST['ItemID24'] != 0)
		{
            if($_SESSION['Bundles2010_24'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle24_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem24 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID24'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice24'] = $_POST['ItemPrice24'] - ($_POST['ItemPrice24'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice24'] * $_POST['ItemQty24'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice24'] * $_POST['ItemQty24'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem24, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem24, "@prmItemID", $_POST['ItemID24'], SQLINT4);
			    mssql_bind($qryInsertItem24, "@prmLocation", $_POST['ItemStockLocation24'], SQLVARCHAR);
			    mssql_bind($qryInsertItem24, "@prmQty", $_POST['ItemQty24'], SQLINT4);
			    mssql_bind($qryInsertItem24, "@prmUnitPrice", $_POST['ItemPrice24'], SQLFLT8);
			    mssql_bind($qryInsertItem24, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem24, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem24, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs24 = mssql_execute($qryInsertItem24);
            }
            else
            {
			    $qryInsertItem24 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID24'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice24'] = $_POST['ItemPrice24'] - ($_POST['ItemPrice24'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice24'] * $_POST['ItemQty24'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice24'] * $_POST['ItemQty24'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem24, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem24, "@prmItemID", $_POST['ItemID24'], SQLINT4);
			    mssql_bind($qryInsertItem24, "@prmLocation", $_POST['ItemStockLocation24'], SQLVARCHAR);
			    mssql_bind($qryInsertItem24, "@prmQty", $_POST['ItemQty24'], SQLINT4);
			    mssql_bind($qryInsertItem24, "@prmUnitPrice", $_POST['ItemPrice24'], SQLFLT8);
			    mssql_bind($qryInsertItem24, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem24, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_24'], SQLFLT8);
			    mssql_bind($qryInsertItem24, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_24'], SQLVARCHAR);
			    mssql_bind($qryInsertItem24, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_24'], SQLVARCHAR);
			    mssql_bind($qryInsertItem24, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_24'], SQLVARCHAR);

			    mssql_bind($qryInsertItem24, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs24 = mssql_execute($qryInsertItem24);
            }
			if (isset($_POST['ItemFree24']) && $_POST['ItemFree24'] != 0)
			{
				$intItemQty = $_POST['ItemFree24'];
				$decItemPrice = 0;
				$qryInsertFreeItem24 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem24, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID24'] == '124' || $_POST['ItemID24'] == '139' || $_POST['ItemID24'] == '141' || $_POST['ItemID24'] == '142')
                {
                    $_POST['ItemID24'] = '100';
				    mssql_bind($qryInsertFreeItem24, "@prmItemID", $_POST['ItemID24'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem24, "@prmItemID", $_POST['ItemID24'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem24, "@prmLocation", $_POST['ItemStockLocation24'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem24, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem24, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem24, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem24, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs24a = mssql_execute($qryInsertFreeItem24);
			}
            if($_SESSION['Bundles2010_24'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_24']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle24_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID24'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_24'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_24'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_24 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_24, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_24, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_24, "@prmLocation", $_POST['ItemStockLocation24'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_24, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_24, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_24, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_24, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_24, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_24, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_24, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_24, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_24);
            }

            if ($_SESSION['Regenesis_2285_24'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_24'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_24 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_24, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_24, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_24, "@prmLocation", $_POST['ItemStockLocation24'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_24, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_24, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_24, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_24, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_24, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_24, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_24, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_24, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_24);
            }
		}

		if (isset($_POST['ItemID25']) && $_POST['ItemID25'] != 0)
		{
            if($_SESSION['Bundles2010_25'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle25_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem25 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID25'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice25'] = $_POST['ItemPrice25'] - ($_POST['ItemPrice25'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice25'] * $_POST['ItemQty25'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice25'] * $_POST['ItemQty25'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem25, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem25, "@prmItemID", $_POST['ItemID25'], SQLINT4);
			    mssql_bind($qryInsertItem25, "@prmLocation", $_POST['ItemStockLocation25'], SQLVARCHAR);
			    mssql_bind($qryInsertItem25, "@prmQty", $_POST['ItemQty25'], SQLINT4);
			    mssql_bind($qryInsertItem25, "@prmUnitPrice", $_POST['ItemPrice25'], SQLFLT8);
			    mssql_bind($qryInsertItem25, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem25, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem25, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs25 = mssql_execute($qryInsertItem25);
            }
            else
            {
			    $qryInsertItem25 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID25'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice25'] = $_POST['ItemPrice25'] - ($_POST['ItemPrice25'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice25'] * $_POST['ItemQty25'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice25'] * $_POST['ItemQty25'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem25, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem25, "@prmItemID", $_POST['ItemID25'], SQLINT4);
			    mssql_bind($qryInsertItem25, "@prmLocation", $_POST['ItemStockLocation25'], SQLVARCHAR);
			    mssql_bind($qryInsertItem25, "@prmQty", $_POST['ItemQty25'], SQLINT4);
			    mssql_bind($qryInsertItem25, "@prmUnitPrice", $_POST['ItemPrice25'], SQLFLT8);
			    mssql_bind($qryInsertItem25, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem25, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_25'], SQLFLT8);
			    mssql_bind($qryInsertItem25, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_25'], SQLVARCHAR);
			    mssql_bind($qryInsertItem25, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_25'], SQLVARCHAR);
			    mssql_bind($qryInsertItem25, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_25'], SQLVARCHAR);

			    mssql_bind($qryInsertItem25, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs25 = mssql_execute($qryInsertItem25);
            }
			if (isset($_POST['ItemFree25']) && $_POST['ItemFree25'] != 0)
			{
				$intItemQty = $_POST['ItemFree25'];
				$decItemPrice = 0;
				$qryInsertFreeItem25 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem25, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID25'] == '124' || $_POST['ItemID25'] == '139' || $_POST['ItemID25'] == '141' || $_POST['ItemID25'] == '142')
                {
                    $_POST['ItemID25'] = '100';
				    mssql_bind($qryInsertFreeItem25, "@prmItemID", $_POST['ItemID25'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem25, "@prmItemID", $_POST['ItemID25'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem25, "@prmLocation", $_POST['ItemStockLocation25'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem25, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem25, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem25, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem25, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs25a = mssql_execute($qryInsertFreeItem25);
			}
            if($_SESSION['Bundles2010_25'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_25']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle25_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID25'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_25'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_25'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_25 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_25, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_25, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_25, "@prmLocation", $_POST['ItemStockLocation25'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_25, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_25, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_25, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_25, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_25, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_25, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_25, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_25, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_25);
            }

            if ($_SESSION['Regenesis_2285_25'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_25'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_25 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_25, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_25, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_25, "@prmLocation", $_POST['ItemStockLocation25'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_25, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_25, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_25, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_25, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_25, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_25, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_25, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_25, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_25);
            }
		}

		if (isset($_POST['ItemID26']) && $_POST['ItemID26'] != 0)
		{
            if($_SESSION['Bundles2010_26'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle26_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem26 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID26'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice26'] = $_POST['ItemPrice26'] - ($_POST['ItemPrice26'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice26'] * $_POST['ItemQty26'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice26'] * $_POST['ItemQty26'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem26, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem26, "@prmItemID", $_POST['ItemID26'], SQLINT4);
			    mssql_bind($qryInsertItem26, "@prmLocation", $_POST['ItemStockLocation26'], SQLVARCHAR);
			    mssql_bind($qryInsertItem26, "@prmQty", $_POST['ItemQty26'], SQLINT4);
			    mssql_bind($qryInsertItem26, "@prmUnitPrice", $_POST['ItemPrice26'], SQLFLT8);
			    mssql_bind($qryInsertItem26, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem26, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem26, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs26 = mssql_execute($qryInsertItem26);
            }
            else
            {
			    $qryInsertItem26 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID26'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice26'] = $_POST['ItemPrice26'] - ($_POST['ItemPrice26'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice26'] * $_POST['ItemQty26'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice26'] * $_POST['ItemQty26'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem26, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem26, "@prmItemID", $_POST['ItemID26'], SQLINT4);
			    mssql_bind($qryInsertItem26, "@prmLocation", $_POST['ItemStockLocation26'], SQLVARCHAR);
			    mssql_bind($qryInsertItem26, "@prmQty", $_POST['ItemQty26'], SQLINT4);
			    mssql_bind($qryInsertItem26, "@prmUnitPrice", $_POST['ItemPrice26'], SQLFLT8);
			    mssql_bind($qryInsertItem26, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem26, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_26'], SQLFLT8);
			    mssql_bind($qryInsertItem26, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_26'], SQLVARCHAR);
			    mssql_bind($qryInsertItem26, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_26'], SQLVARCHAR);
			    mssql_bind($qryInsertItem26, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_26'], SQLVARCHAR);

			    mssql_bind($qryInsertItem26, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs26 = mssql_execute($qryInsertItem26);
            }
			if (isset($_POST['ItemFree26']) && $_POST['ItemFree26'] != 0)
			{
				$intItemQty = $_POST['ItemFree26'];
				$decItemPrice = 0;
				$qryInsertFreeItem26 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem26, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID26'] == '124' || $_POST['ItemID26'] == '139' || $_POST['ItemID26'] == '141' || $_POST['ItemID26'] == '142')
                {
                    $_POST['ItemID26'] = '100';
				    mssql_bind($qryInsertFreeItem26, "@prmItemID", $_POST['ItemID26'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem26, "@prmItemID", $_POST['ItemID26'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem26, "@prmLocation", $_POST['ItemStockLocation26'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem26, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem26, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem26, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem26, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs26a = mssql_execute($qryInsertFreeItem26);
			}
            if($_SESSION['Bundles2010_26'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_26']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle26_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID26'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_26'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_26'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_26 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_26, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_26, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_26, "@prmLocation", $_POST['ItemStockLocation26'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_26, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_26, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_26, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_26, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_26, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_26, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_26, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_26, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_26);
            }

            if ($_SESSION['Regenesis_2285_26'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_26'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_26 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_26, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_26, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_26, "@prmLocation", $_POST['ItemStockLocation26'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_26, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_26, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_26, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_26, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_26, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_26, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_26, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_26, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_26);
            }
		}

		if (isset($_POST['ItemID27']) && $_POST['ItemID27'] != 0)
		{
            if($_SESSION['Bundles2010_27'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle27_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem27 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID27'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice27'] = $_POST['ItemPrice27'] - ($_POST['ItemPrice27'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice27'] * $_POST['ItemQty27'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice27'] * $_POST['ItemQty27'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem27, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem27, "@prmItemID", $_POST['ItemID27'], SQLINT4);
			    mssql_bind($qryInsertItem27, "@prmLocation", $_POST['ItemStockLocation27'], SQLVARCHAR);
			    mssql_bind($qryInsertItem27, "@prmQty", $_POST['ItemQty27'], SQLINT4);
			    mssql_bind($qryInsertItem27, "@prmUnitPrice", $_POST['ItemPrice27'], SQLFLT8);
			    mssql_bind($qryInsertItem27, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem27, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem27, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs27 = mssql_execute($qryInsertItem27);
            }
            else
            {
			    $qryInsertItem27 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID27'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice27'] = $_POST['ItemPrice27'] - ($_POST['ItemPrice27'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice27'] * $_POST['ItemQty27'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice27'] * $_POST['ItemQty27'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem27, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem27, "@prmItemID", $_POST['ItemID27'], SQLINT4);
			    mssql_bind($qryInsertItem27, "@prmLocation", $_POST['ItemStockLocation27'], SQLVARCHAR);
			    mssql_bind($qryInsertItem27, "@prmQty", $_POST['ItemQty27'], SQLINT4);
			    mssql_bind($qryInsertItem27, "@prmUnitPrice", $_POST['ItemPrice27'], SQLFLT8);
			    mssql_bind($qryInsertItem27, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem27, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_27'], SQLFLT8);
			    mssql_bind($qryInsertItem27, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_27'], SQLVARCHAR);
			    mssql_bind($qryInsertItem27, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_27'], SQLVARCHAR);
			    mssql_bind($qryInsertItem27, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_27'], SQLVARCHAR);

			    mssql_bind($qryInsertItem27, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs27 = mssql_execute($qryInsertItem27);
            }
			if (isset($_POST['ItemFree27']) && $_POST['ItemFree27'] != 0)
			{
				$intItemQty = $_POST['ItemFree27'];
				$decItemPrice = 0;
				$qryInsertFreeItem27 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem27, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID27'] == '124' || $_POST['ItemID27'] == '139' || $_POST['ItemID27'] == '141' || $_POST['ItemID27'] == '142')
                {
                    $_POST['ItemID27'] = '100';
				    mssql_bind($qryInsertFreeItem27, "@prmItemID", $_POST['ItemID27'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem27, "@prmItemID", $_POST['ItemID27'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem27, "@prmLocation", $_POST['ItemStockLocation27'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem27, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem27, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem27, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem27, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs27a = mssql_execute($qryInsertFreeItem27);
			}
            if($_SESSION['Bundles2010_27'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_27']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle27_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID27'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_27'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_27'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_27 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_27, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_27, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_27, "@prmLocation", $_POST['ItemStockLocation27'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_27, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_27, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_27, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_27, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_27, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_27, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_27, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_27, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_27);
            }

            if ($_SESSION['Regenesis_2285_27'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_27'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_27 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_27, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_27, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_27, "@prmLocation", $_POST['ItemStockLocation27'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_27, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_27, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_27, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_27, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_27, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_27, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_27, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_27, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_27);
            }
		}

		if (isset($_POST['ItemID28']) && $_POST['ItemID28'] != 0)
		{
            if($_SESSION['Bundles2010_28'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle28_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem28 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID28'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice28'] = $_POST['ItemPrice28'] - ($_POST['ItemPrice28'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice28'] * $_POST['ItemQty28'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice28'] * $_POST['ItemQty28'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem28, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem28, "@prmItemID", $_POST['ItemID28'], SQLINT4);
			    mssql_bind($qryInsertItem28, "@prmLocation", $_POST['ItemStockLocation28'], SQLVARCHAR);
			    mssql_bind($qryInsertItem28, "@prmQty", $_POST['ItemQty28'], SQLINT4);
			    mssql_bind($qryInsertItem28, "@prmUnitPrice", $_POST['ItemPrice28'], SQLFLT8);
			    mssql_bind($qryInsertItem28, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem28, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem28, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs28 = mssql_execute($qryInsertItem28);
            }
            else
            {
			    $qryInsertItem28 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID28'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice28'] = $_POST['ItemPrice28'] - ($_POST['ItemPrice28'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice28'] * $_POST['ItemQty28'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice28'] * $_POST['ItemQty28'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem28, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem28, "@prmItemID", $_POST['ItemID28'], SQLINT4);
			    mssql_bind($qryInsertItem28, "@prmLocation", $_POST['ItemStockLocation28'], SQLVARCHAR);
			    mssql_bind($qryInsertItem28, "@prmQty", $_POST['ItemQty28'], SQLINT4);
			    mssql_bind($qryInsertItem28, "@prmUnitPrice", $_POST['ItemPrice28'], SQLFLT8);
			    mssql_bind($qryInsertItem28, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem28, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_28'], SQLFLT8);
			    mssql_bind($qryInsertItem28, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_28'], SQLVARCHAR);
			    mssql_bind($qryInsertItem28, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_28'], SQLVARCHAR);
			    mssql_bind($qryInsertItem28, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_28'], SQLVARCHAR);

			    mssql_bind($qryInsertItem28, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs28 = mssql_execute($qryInsertItem28);
            }
			if (isset($_POST['ItemFree28']) && $_POST['ItemFree28'] != 0)
			{
				$intItemQty = $_POST['ItemFree28'];
				$decItemPrice = 0;
				$qryInsertFreeItem28 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem28, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID28'] == '124' || $_POST['ItemID28'] == '139' || $_POST['ItemID28'] == '141' || $_POST['ItemID28'] == '142')
                {
                    $_POST['ItemID28'] = '100';
				    mssql_bind($qryInsertFreeItem28, "@prmItemID", $_POST['ItemID28'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem28, "@prmItemID", $_POST['ItemID28'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem28, "@prmLocation", $_POST['ItemStockLocation28'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem28, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem28, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem28, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem28, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs28a = mssql_execute($qryInsertFreeItem28);
			}
            if($_SESSION['Bundles2010_28'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_28']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle28_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID28'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_28'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_28'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_28 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_28, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_28, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_28, "@prmLocation", $_POST['ItemStockLocation28'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_28, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_28, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_28, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_28, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_28, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_28, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_28, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_28, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_28);
            }

            if ($_SESSION['Regenesis_2285_28'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_28'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_28 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_28, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_28, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_28, "@prmLocation", $_POST['ItemStockLocation28'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_28, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_28, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_28, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_28, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_28, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_28, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_28, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_28, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_28);
            }
		}

		if (isset($_POST['ItemID29']) && $_POST['ItemID29'] != 0)
		{
            if($_SESSION['Bundles2010_29'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle29_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem29 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID29'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice29'] = $_POST['ItemPrice29'] - ($_POST['ItemPrice29'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice29'] * $_POST['ItemQty29'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice29'] * $_POST['ItemQty29'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem29, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem29, "@prmItemID", $_POST['ItemID29'], SQLINT4);
			    mssql_bind($qryInsertItem29, "@prmLocation", $_POST['ItemStockLocation29'], SQLVARCHAR);
			    mssql_bind($qryInsertItem29, "@prmQty", $_POST['ItemQty29'], SQLINT4);
			    mssql_bind($qryInsertItem29, "@prmUnitPrice", $_POST['ItemPrice29'], SQLFLT8);
			    mssql_bind($qryInsertItem29, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem29, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem29, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs29 = mssql_execute($qryInsertItem29);
            }
            else
            {
			    $qryInsertItem29 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID29'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice29'] = $_POST['ItemPrice29'] - ($_POST['ItemPrice29'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice29'] * $_POST['ItemQty29'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice29'] * $_POST['ItemQty29'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem29, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem29, "@prmItemID", $_POST['ItemID29'], SQLINT4);
			    mssql_bind($qryInsertItem29, "@prmLocation", $_POST['ItemStockLocation29'], SQLVARCHAR);
			    mssql_bind($qryInsertItem29, "@prmQty", $_POST['ItemQty29'], SQLINT4);
			    mssql_bind($qryInsertItem29, "@prmUnitPrice", $_POST['ItemPrice29'], SQLFLT8);
			    mssql_bind($qryInsertItem29, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem29, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_29'], SQLFLT8);
			    mssql_bind($qryInsertItem29, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_29'], SQLVARCHAR);
			    mssql_bind($qryInsertItem29, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_29'], SQLVARCHAR);
			    mssql_bind($qryInsertItem29, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_29'], SQLVARCHAR);

			    mssql_bind($qryInsertItem29, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs29 = mssql_execute($qryInsertItem29);
            }
			if (isset($_POST['ItemFree29']) && $_POST['ItemFree29'] != 0)
			{
				$intItemQty = $_POST['ItemFree29'];
				$decItemPrice = 0;
				$qryInsertFreeItem29 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem29, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID29'] == '124' || $_POST['ItemID29'] == '139' || $_POST['ItemID29'] == '141' || $_POST['ItemID29'] == '142')
                {
                    $_POST['ItemID29'] = '100';
				    mssql_bind($qryInsertFreeItem29, "@prmItemID", $_POST['ItemID29'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem29, "@prmItemID", $_POST['ItemID29'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem29, "@prmLocation", $_POST['ItemStockLocation29'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem29, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem29, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem29, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem29, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs29a = mssql_execute($qryInsertFreeItem29);
			}
            if($_SESSION['Bundles2010_29'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_29']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle29_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID29'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_29'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_29'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_29 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_29, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_29, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_29, "@prmLocation", $_POST['ItemStockLocation29'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_29, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_29, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_29, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_29, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_29, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_29, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_29, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_29, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_29);
            }

            if ($_SESSION['Regenesis_2285_29'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_29'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_29 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_29, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_29, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_29, "@prmLocation", $_POST['ItemStockLocation29'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_29, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_29, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_29, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_29, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_29, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_29, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_29, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_29, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_29);
            }
		}

		if (isset($_POST['ItemID30']) && $_POST['ItemID30'] != 0)
		{
            if($_SESSION['Bundles2010_30'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle30_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem30 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID30'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice30'] = $_POST['ItemPrice30'] - ($_POST['ItemPrice30'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice30'] * $_POST['ItemQty30'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice30'] * $_POST['ItemQty30'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem30, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem30, "@prmItemID", $_POST['ItemID30'], SQLINT4);
			    mssql_bind($qryInsertItem30, "@prmLocation", $_POST['ItemStockLocation30'], SQLVARCHAR);
			    mssql_bind($qryInsertItem30, "@prmQty", $_POST['ItemQty30'], SQLINT4);
			    mssql_bind($qryInsertItem30, "@prmUnitPrice", $_POST['ItemPrice30'], SQLFLT8);
			    mssql_bind($qryInsertItem30, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem30, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem30, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs30 = mssql_execute($qryInsertItem30);
            }
            else
            {
			    $qryInsertItem30 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID30'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice30'] = $_POST['ItemPrice30'] - ($_POST['ItemPrice30'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice30'] * $_POST['ItemQty30'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice30'] * $_POST['ItemQty30'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem30, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem30, "@prmItemID", $_POST['ItemID30'], SQLINT4);
			    mssql_bind($qryInsertItem30, "@prmLocation", $_POST['ItemStockLocation30'], SQLVARCHAR);
			    mssql_bind($qryInsertItem30, "@prmQty", $_POST['ItemQty30'], SQLINT4);
			    mssql_bind($qryInsertItem30, "@prmUnitPrice", $_POST['ItemPrice30'], SQLFLT8);
			    mssql_bind($qryInsertItem30, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem30, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_30'], SQLFLT8);
			    mssql_bind($qryInsertItem30, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_30'], SQLVARCHAR);
			    mssql_bind($qryInsertItem30, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_30'], SQLVARCHAR);
			    mssql_bind($qryInsertItem30, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_30'], SQLVARCHAR);

			    mssql_bind($qryInsertItem30, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs30 = mssql_execute($qryInsertItem30);
            }
			if (isset($_POST['ItemFree30']) && $_POST['ItemFree30'] != 0)
			{
				$intItemQty = $_POST['ItemFree30'];
				$decItemPrice = 0;
				$qryInsertFreeItem30 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem30, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID30'] == '124' || $_POST['ItemID30'] == '139' || $_POST['ItemID30'] == '141' || $_POST['ItemID30'] == '142')
                {
                    $_POST['ItemID30'] = '100';
				    mssql_bind($qryInsertFreeItem30, "@prmItemID", $_POST['ItemID30'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem30, "@prmItemID", $_POST['ItemID30'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem30, "@prmLocation", $_POST['ItemStockLocation30'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem30, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem30, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem30, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem30, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs30a = mssql_execute($qryInsertFreeItem30);
			}
            if($_SESSION['Bundles2010_30'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_30']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle30_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID30'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_30'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_30'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_30 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_30, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_30, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_30, "@prmLocation", $_POST['ItemStockLocation30'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_30, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_30, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_30, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_30, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_30, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_30, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_30, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_30, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_30);
            }

            if ($_SESSION['Regenesis_2285_30'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_30'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_30 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_30, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_30, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_30, "@prmLocation", $_POST['ItemStockLocation30'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_30, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_30, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_30, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_30, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_30, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_30, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_30, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_30, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_30);
            }
		}

		if (isset($_POST['ItemID31']) && $_POST['ItemID31'] != 0)
		{
            if($_SESSION['Bundles2010_31'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle31_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem31 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID31'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice31'] = $_POST['ItemPrice31'] - ($_POST['ItemPrice31'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice31'] * $_POST['ItemQty31'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice31'] * $_POST['ItemQty31'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem31, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem31, "@prmItemID", $_POST['ItemID31'], SQLINT4);
			    mssql_bind($qryInsertItem31, "@prmLocation", $_POST['ItemStockLocation31'], SQLVARCHAR);
			    mssql_bind($qryInsertItem31, "@prmQty", $_POST['ItemQty31'], SQLINT4);
			    mssql_bind($qryInsertItem31, "@prmUnitPrice", $_POST['ItemPrice31'], SQLFLT8);
			    mssql_bind($qryInsertItem31, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem31, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem31, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs31 = mssql_execute($qryInsertItem31);
            }
            else
            {
			    $qryInsertItem31 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID31'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice31'] = $_POST['ItemPrice31'] - ($_POST['ItemPrice31'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice31'] * $_POST['ItemQty31'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice31'] * $_POST['ItemQty31'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem31, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem31, "@prmItemID", $_POST['ItemID31'], SQLINT4);
			    mssql_bind($qryInsertItem31, "@prmLocation", $_POST['ItemStockLocation31'], SQLVARCHAR);
			    mssql_bind($qryInsertItem31, "@prmQty", $_POST['ItemQty31'], SQLINT4);
			    mssql_bind($qryInsertItem31, "@prmUnitPrice", $_POST['ItemPrice31'], SQLFLT8);
			    mssql_bind($qryInsertItem31, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem31, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_31'], SQLFLT8);
			    mssql_bind($qryInsertItem31, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_31'], SQLVARCHAR);
			    mssql_bind($qryInsertItem31, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_31'], SQLVARCHAR);
			    mssql_bind($qryInsertItem31, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_31'], SQLVARCHAR);

			    mssql_bind($qryInsertItem31, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs31 = mssql_execute($qryInsertItem31);
            }
			if (isset($_POST['ItemFree31']) && $_POST['ItemFree31'] != 0)
			{
				$intItemQty = $_POST['ItemFree31'];
				$decItemPrice = 0;
				$qryInsertFreeItem31 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem31, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID31'] == '124' || $_POST['ItemID31'] == '139' || $_POST['ItemID31'] == '141' || $_POST['ItemID31'] == '142')
                {
                    $_POST['ItemID31'] = '100';
				    mssql_bind($qryInsertFreeItem31, "@prmItemID", $_POST['ItemID31'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem31, "@prmItemID", $_POST['ItemID31'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem31, "@prmLocation", $_POST['ItemStockLocation31'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem31, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem31, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem31, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem31, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs31a = mssql_execute($qryInsertFreeItem31);
			}
            if($_SESSION['Bundles2010_31'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_31']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle31_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID31'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_31'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_31'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_31 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_31, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_31, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_31, "@prmLocation", $_POST['ItemStockLocation31'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_31, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_31, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_31, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_31, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_31, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_31, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_31, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_31, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_31);
            }

            if ($_SESSION['Regenesis_2285_31'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_31'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_31 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_31, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_31, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_31, "@prmLocation", $_POST['ItemStockLocation31'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_31, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_31, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_31, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_31, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_31, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_31, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_31, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_31, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_31);
            }
		}

		if (isset($_POST['ItemID32']) && $_POST['ItemID32'] != 0)
		{
            if($_SESSION['Bundles2010_32'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle32_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem32 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID32'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice32'] = $_POST['ItemPrice32'] - ($_POST['ItemPrice32'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice32'] * $_POST['ItemQty32'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice32'] * $_POST['ItemQty32'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem32, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem32, "@prmItemID", $_POST['ItemID32'], SQLINT4);
			    mssql_bind($qryInsertItem32, "@prmLocation", $_POST['ItemStockLocation32'], SQLVARCHAR);
			    mssql_bind($qryInsertItem32, "@prmQty", $_POST['ItemQty32'], SQLINT4);
			    mssql_bind($qryInsertItem32, "@prmUnitPrice", $_POST['ItemPrice32'], SQLFLT8);
			    mssql_bind($qryInsertItem32, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem32, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem32, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs32 = mssql_execute($qryInsertItem32);
            }
            else
            {
			    $qryInsertItem32 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID32'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice32'] = $_POST['ItemPrice32'] - ($_POST['ItemPrice32'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice32'] * $_POST['ItemQty32'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice32'] * $_POST['ItemQty32'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem32, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem32, "@prmItemID", $_POST['ItemID32'], SQLINT4);
			    mssql_bind($qryInsertItem32, "@prmLocation", $_POST['ItemStockLocation32'], SQLVARCHAR);
			    mssql_bind($qryInsertItem32, "@prmQty", $_POST['ItemQty32'], SQLINT4);
			    mssql_bind($qryInsertItem32, "@prmUnitPrice", $_POST['ItemPrice32'], SQLFLT8);
			    mssql_bind($qryInsertItem32, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem32, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_32'], SQLFLT8);
			    mssql_bind($qryInsertItem32, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_32'], SQLVARCHAR);
			    mssql_bind($qryInsertItem32, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_32'], SQLVARCHAR);
			    mssql_bind($qryInsertItem32, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_32'], SQLVARCHAR);

			    mssql_bind($qryInsertItem32, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs32 = mssql_execute($qryInsertItem32);
            }
			if (isset($_POST['ItemFree32']) && $_POST['ItemFree32'] != 0)
			{
				$intItemQty = $_POST['ItemFree32'];
				$decItemPrice = 0;
				$qryInsertFreeItem32 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem32, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID32'] == '124' || $_POST['ItemID32'] == '139' || $_POST['ItemID32'] == '141' || $_POST['ItemID32'] == '142')
                {
                    $_POST['ItemID32'] = '100';
				    mssql_bind($qryInsertFreeItem32, "@prmItemID", $_POST['ItemID32'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem32, "@prmItemID", $_POST['ItemID32'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem32, "@prmLocation", $_POST['ItemStockLocation32'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem32, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem32, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem32, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem32, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs32a = mssql_execute($qryInsertFreeItem32);
			}
            if($_SESSION['Bundles2010_32'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_32']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle32_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID32'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_32'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_32'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_32 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_32, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_32, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_32, "@prmLocation", $_POST['ItemStockLocation32'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_32, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_32, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_32, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_32, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_32, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_32, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_32, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_32, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_32);
            }

            if ($_SESSION['Regenesis_2285_32'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_32'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_32 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_32, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_32, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_32, "@prmLocation", $_POST['ItemStockLocation32'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_32, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_32, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_32, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_32, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_32, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_32, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_32, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_32, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_32);
            }
		}

		if (isset($_POST['ItemID33']) && $_POST['ItemID33'] != 0)
		{
            if($_SESSION['Bundles2010_33'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle33_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem33 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID33'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice33'] = $_POST['ItemPrice33'] - ($_POST['ItemPrice33'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice33'] * $_POST['ItemQty33'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice33'] * $_POST['ItemQty33'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem33, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem33, "@prmItemID", $_POST['ItemID33'], SQLINT4);
			    mssql_bind($qryInsertItem33, "@prmLocation", $_POST['ItemStockLocation33'], SQLVARCHAR);
			    mssql_bind($qryInsertItem33, "@prmQty", $_POST['ItemQty33'], SQLINT4);
			    mssql_bind($qryInsertItem33, "@prmUnitPrice", $_POST['ItemPrice33'], SQLFLT8);
			    mssql_bind($qryInsertItem33, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem33, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem33, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs33 = mssql_execute($qryInsertItem33);
            }
            else
            {
			    $qryInsertItem33 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID33'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice33'] = $_POST['ItemPrice33'] - ($_POST['ItemPrice33'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice33'] * $_POST['ItemQty33'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice33'] * $_POST['ItemQty33'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem33, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem33, "@prmItemID", $_POST['ItemID33'], SQLINT4);
			    mssql_bind($qryInsertItem33, "@prmLocation", $_POST['ItemStockLocation33'], SQLVARCHAR);
			    mssql_bind($qryInsertItem33, "@prmQty", $_POST['ItemQty33'], SQLINT4);
			    mssql_bind($qryInsertItem33, "@prmUnitPrice", $_POST['ItemPrice33'], SQLFLT8);
			    mssql_bind($qryInsertItem33, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem33, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_33'], SQLFLT8);
			    mssql_bind($qryInsertItem33, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_33'], SQLVARCHAR);
			    mssql_bind($qryInsertItem33, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_33'], SQLVARCHAR);
			    mssql_bind($qryInsertItem33, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_33'], SQLVARCHAR);

			    mssql_bind($qryInsertItem33, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs33 = mssql_execute($qryInsertItem33);
            }
			if (isset($_POST['ItemFree33']) && $_POST['ItemFree33'] != 0)
			{
				$intItemQty = $_POST['ItemFree33'];
				$decItemPrice = 0;
				$qryInsertFreeItem33 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem33, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID33'] == '124' || $_POST['ItemID33'] == '139' || $_POST['ItemID33'] == '141' || $_POST['ItemID33'] == '142')
                {
                    $_POST['ItemID33'] = '100';
				    mssql_bind($qryInsertFreeItem33, "@prmItemID", $_POST['ItemID33'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem33, "@prmItemID", $_POST['ItemID33'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem33, "@prmLocation", $_POST['ItemStockLocation33'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem33, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem33, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem33, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem33, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs33a = mssql_execute($qryInsertFreeItem33);
			}
            if($_SESSION['Bundles2010_33'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_33']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle33_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID33'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_33'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_33'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_33 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_33, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_33, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_33, "@prmLocation", $_POST['ItemStockLocation33'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_33, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_33, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_33, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_33, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_33, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_33, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_33, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_33, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_33);
            }

            if ($_SESSION['Regenesis_2285_33'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_33'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_33 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_33, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_33, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_33, "@prmLocation", $_POST['ItemStockLocation33'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_33, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_33, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_33, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_33, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_33, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_33, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_33, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_33, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_33);
            }
		}

		if (isset($_POST['ItemID34']) && $_POST['ItemID34'] != 0)
		{
            if($_SESSION['Bundles2010_34'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle34_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem34 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID34'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice34'] = $_POST['ItemPrice34'] - ($_POST['ItemPrice34'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice34'] * $_POST['ItemQty34'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice34'] * $_POST['ItemQty34'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem34, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem34, "@prmItemID", $_POST['ItemID34'], SQLINT4);
			    mssql_bind($qryInsertItem34, "@prmLocation", $_POST['ItemStockLocation34'], SQLVARCHAR);
			    mssql_bind($qryInsertItem34, "@prmQty", $_POST['ItemQty34'], SQLINT4);
			    mssql_bind($qryInsertItem34, "@prmUnitPrice", $_POST['ItemPrice34'], SQLFLT8);
			    mssql_bind($qryInsertItem34, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem34, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem34, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs34 = mssql_execute($qryInsertItem34);
            }
            else
            {
			    $qryInsertItem34 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID34'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice34'] = $_POST['ItemPrice34'] - ($_POST['ItemPrice34'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice34'] * $_POST['ItemQty34'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice34'] * $_POST['ItemQty34'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem34, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem34, "@prmItemID", $_POST['ItemID34'], SQLINT4);
			    mssql_bind($qryInsertItem34, "@prmLocation", $_POST['ItemStockLocation34'], SQLVARCHAR);
			    mssql_bind($qryInsertItem34, "@prmQty", $_POST['ItemQty34'], SQLINT4);
			    mssql_bind($qryInsertItem34, "@prmUnitPrice", $_POST['ItemPrice34'], SQLFLT8);
			    mssql_bind($qryInsertItem34, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem34, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_34'], SQLFLT8);
			    mssql_bind($qryInsertItem34, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_34'], SQLVARCHAR);
			    mssql_bind($qryInsertItem34, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_34'], SQLVARCHAR);
			    mssql_bind($qryInsertItem34, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_34'], SQLVARCHAR);

			    mssql_bind($qryInsertItem34, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs34 = mssql_execute($qryInsertItem34);
            }
			if (isset($_POST['ItemFree34']) && $_POST['ItemFree34'] != 0)
			{
				$intItemQty = $_POST['ItemFree34'];
				$decItemPrice = 0;
				$qryInsertFreeItem34 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem34, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID34'] == '124' || $_POST['ItemID34'] == '139' || $_POST['ItemID34'] == '141' || $_POST['ItemID34'] == '142')
                {
                    $_POST['ItemID34'] = '100';
				    mssql_bind($qryInsertFreeItem34, "@prmItemID", $_POST['ItemID34'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem34, "@prmItemID", $_POST['ItemID34'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem34, "@prmLocation", $_POST['ItemStockLocation34'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem34, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem34, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem34, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem34, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs34a = mssql_execute($qryInsertFreeItem34);
			}
            if($_SESSION['Bundles2010_34'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_34']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle34_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID34'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_34'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_34'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_34 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_34, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_34, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_34, "@prmLocation", $_POST['ItemStockLocation34'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_34, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_34, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_34, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_34, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_34, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_34, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_34, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_34, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_34);
            }

            if ($_SESSION['Regenesis_2285_34'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_34'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_34 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_34, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_34, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_34, "@prmLocation", $_POST['ItemStockLocation34'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_34, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_34, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_34, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_34, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_34, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_34, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_34, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_34, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_34);
            }
		}

		if (isset($_POST['ItemID35']) && $_POST['ItemID35'] != 0)
		{
            if($_SESSION['Bundles2010_35'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle35_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem35 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID35'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice35'] = $_POST['ItemPrice35'] - ($_POST['ItemPrice35'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice35'] * $_POST['ItemQty35'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice35'] * $_POST['ItemQty35'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem35, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem35, "@prmItemID", $_POST['ItemID35'], SQLINT4);
			    mssql_bind($qryInsertItem35, "@prmLocation", $_POST['ItemStockLocation35'], SQLVARCHAR);
			    mssql_bind($qryInsertItem35, "@prmQty", $_POST['ItemQty35'], SQLINT4);
			    mssql_bind($qryInsertItem35, "@prmUnitPrice", $_POST['ItemPrice35'], SQLFLT8);
			    mssql_bind($qryInsertItem35, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem35, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem35, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs35 = mssql_execute($qryInsertItem35);
            }
            else
            {
			    $qryInsertItem35 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID35'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice35'] = $_POST['ItemPrice35'] - ($_POST['ItemPrice35'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice35'] * $_POST['ItemQty35'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice35'] * $_POST['ItemQty35'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem35, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem35, "@prmItemID", $_POST['ItemID35'], SQLINT4);
			    mssql_bind($qryInsertItem35, "@prmLocation", $_POST['ItemStockLocation35'], SQLVARCHAR);
			    mssql_bind($qryInsertItem35, "@prmQty", $_POST['ItemQty35'], SQLINT4);
			    mssql_bind($qryInsertItem35, "@prmUnitPrice", $_POST['ItemPrice35'], SQLFLT8);
			    mssql_bind($qryInsertItem35, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem35, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_35'], SQLFLT8);
			    mssql_bind($qryInsertItem35, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_35'], SQLVARCHAR);
			    mssql_bind($qryInsertItem35, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_35'], SQLVARCHAR);
			    mssql_bind($qryInsertItem35, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_35'], SQLVARCHAR);

			    mssql_bind($qryInsertItem35, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs35 = mssql_execute($qryInsertItem35);
            }
			if (isset($_POST['ItemFree35']) && $_POST['ItemFree35'] != 0)
			{
				$intItemQty = $_POST['ItemFree35'];
				$decItemPrice = 0;
				$qryInsertFreeItem35 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem35, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID35'] == '124' || $_POST['ItemID35'] == '139' || $_POST['ItemID35'] == '141' || $_POST['ItemID35'] == '142')
                {
                    $_POST['ItemID35'] = '100';
				    mssql_bind($qryInsertFreeItem35, "@prmItemID", $_POST['ItemID35'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem35, "@prmItemID", $_POST['ItemID35'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem35, "@prmLocation", $_POST['ItemStockLocation35'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem35, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem35, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem35, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem35, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs35a = mssql_execute($qryInsertFreeItem35);
			}
            if($_SESSION['Bundles2010_35'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_35']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle35_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID35'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_35'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_35'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_35 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_35, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_35, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_35, "@prmLocation", $_POST['ItemStockLocation35'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_35, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_35, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_35, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_35, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_35, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_35, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_35, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_35, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_35);
            }

            if ($_SESSION['Regenesis_2285_35'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_35'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_35 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_35, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_35, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_35, "@prmLocation", $_POST['ItemStockLocation35'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_35, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_35, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_35, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_35, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_35, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_35, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_35, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_35, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_35);
            }
		}

		if (isset($_POST['ItemID36']) && $_POST['ItemID36'] != 0)
		{
            if($_SESSION['Bundles2010_36'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle36_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem36 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID36'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice36'] = $_POST['ItemPrice36'] - ($_POST['ItemPrice36'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice36'] * $_POST['ItemQty36'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice36'] * $_POST['ItemQty36'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem36, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem36, "@prmItemID", $_POST['ItemID36'], SQLINT4);
			    mssql_bind($qryInsertItem36, "@prmLocation", $_POST['ItemStockLocation36'], SQLVARCHAR);
			    mssql_bind($qryInsertItem36, "@prmQty", $_POST['ItemQty36'], SQLINT4);
			    mssql_bind($qryInsertItem36, "@prmUnitPrice", $_POST['ItemPrice36'], SQLFLT8);
			    mssql_bind($qryInsertItem36, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem36, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem36, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs36 = mssql_execute($qryInsertItem36);
            }
            else
            {
			    $qryInsertItem36 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID36'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice36'] = $_POST['ItemPrice36'] - ($_POST['ItemPrice36'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice36'] * $_POST['ItemQty36'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice36'] * $_POST['ItemQty36'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem36, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem36, "@prmItemID", $_POST['ItemID36'], SQLINT4);
			    mssql_bind($qryInsertItem36, "@prmLocation", $_POST['ItemStockLocation36'], SQLVARCHAR);
			    mssql_bind($qryInsertItem36, "@prmQty", $_POST['ItemQty36'], SQLINT4);
			    mssql_bind($qryInsertItem36, "@prmUnitPrice", $_POST['ItemPrice36'], SQLFLT8);
			    mssql_bind($qryInsertItem36, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem36, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_36'], SQLFLT8);
			    mssql_bind($qryInsertItem36, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_36'], SQLVARCHAR);
			    mssql_bind($qryInsertItem36, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_36'], SQLVARCHAR);
			    mssql_bind($qryInsertItem36, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_36'], SQLVARCHAR);

			    mssql_bind($qryInsertItem36, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs36 = mssql_execute($qryInsertItem36);
            }
			if (isset($_POST['ItemFree36']) && $_POST['ItemFree36'] != 0)
			{
				$intItemQty = $_POST['ItemFree36'];
				$decItemPrice = 0;
				$qryInsertFreeItem36 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem36, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID36'] == '124' || $_POST['ItemID36'] == '139' || $_POST['ItemID36'] == '141' || $_POST['ItemID36'] == '142')
                {
                    $_POST['ItemID36'] = '100';
				    mssql_bind($qryInsertFreeItem36, "@prmItemID", $_POST['ItemID36'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem36, "@prmItemID", $_POST['ItemID36'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem36, "@prmLocation", $_POST['ItemStockLocation36'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem36, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem36, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem36, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem36, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs36a = mssql_execute($qryInsertFreeItem36);
			}
            if($_SESSION['Bundles2010_36'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_36']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle36_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID36'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_36'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_36'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_36 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_36, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_36, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_36, "@prmLocation", $_POST['ItemStockLocation36'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_36, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_36, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_36, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_36, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_36, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_36, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_36, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_36, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_36);
            }

            if ($_SESSION['Regenesis_2285_36'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_36'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_36 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_36, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_36, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_36, "@prmLocation", $_POST['ItemStockLocation36'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_36, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_36, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_36, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_36, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_36, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_36, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_36, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_36, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_36);
            }
		}

		if (isset($_POST['ItemID37']) && $_POST['ItemID37'] != 0)
		{
            if($_SESSION['Bundles2010_37'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle37_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem37 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID37'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice37'] = $_POST['ItemPrice37'] - ($_POST['ItemPrice37'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice37'] * $_POST['ItemQty37'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice37'] * $_POST['ItemQty37'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem37, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem37, "@prmItemID", $_POST['ItemID37'], SQLINT4);
			    mssql_bind($qryInsertItem37, "@prmLocation", $_POST['ItemStockLocation37'], SQLVARCHAR);
			    mssql_bind($qryInsertItem37, "@prmQty", $_POST['ItemQty37'], SQLINT4);
			    mssql_bind($qryInsertItem37, "@prmUnitPrice", $_POST['ItemPrice37'], SQLFLT8);
			    mssql_bind($qryInsertItem37, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem37, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem37, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs37 = mssql_execute($qryInsertItem37);
            }
            else
            {
			    $qryInsertItem37 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID37'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice37'] = $_POST['ItemPrice37'] - ($_POST['ItemPrice37'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice37'] * $_POST['ItemQty37'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice37'] * $_POST['ItemQty37'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem37, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem37, "@prmItemID", $_POST['ItemID37'], SQLINT4);
			    mssql_bind($qryInsertItem37, "@prmLocation", $_POST['ItemStockLocation37'], SQLVARCHAR);
			    mssql_bind($qryInsertItem37, "@prmQty", $_POST['ItemQty37'], SQLINT4);
			    mssql_bind($qryInsertItem37, "@prmUnitPrice", $_POST['ItemPrice37'], SQLFLT8);
			    mssql_bind($qryInsertItem37, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem37, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_37'], SQLFLT8);
			    mssql_bind($qryInsertItem37, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_37'], SQLVARCHAR);
			    mssql_bind($qryInsertItem37, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_37'], SQLVARCHAR);
			    mssql_bind($qryInsertItem37, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_37'], SQLVARCHAR);

			    mssql_bind($qryInsertItem37, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs37 = mssql_execute($qryInsertItem37);
            }
			if (isset($_POST['ItemFree37']) && $_POST['ItemFree37'] != 0)
			{
				$intItemQty = $_POST['ItemFree37'];
				$decItemPrice = 0;
				$qryInsertFreeItem37 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem37, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID37'] == '124' || $_POST['ItemID37'] == '139' || $_POST['ItemID37'] == '141' || $_POST['ItemID37'] == '142')
                {
                    $_POST['ItemID37'] = '100';
				    mssql_bind($qryInsertFreeItem37, "@prmItemID", $_POST['ItemID37'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem37, "@prmItemID", $_POST['ItemID37'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem37, "@prmLocation", $_POST['ItemStockLocation37'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem37, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem37, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem37, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem37, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs37a = mssql_execute($qryInsertFreeItem37);
			}
            if($_SESSION['Bundles2010_37'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_37']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle37_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID37'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_37'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_37'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_37 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_37, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_37, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_37, "@prmLocation", $_POST['ItemStockLocation37'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_37, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_37, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_37, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_37, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_37, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_37, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_37, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_37, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_37);
            }

            if ($_SESSION['Regenesis_2285_37'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_37'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_37 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_37, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_37, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_37, "@prmLocation", $_POST['ItemStockLocation37'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_37, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_37, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_37, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_37, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_37, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_37, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_37, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_37, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_37);
            }
		}

		if (isset($_POST['ItemID38']) && $_POST['ItemID38'] != 0)
		{
            if($_SESSION['Bundles2010_38'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle38_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem38 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID38'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice38'] = $_POST['ItemPrice38'] - ($_POST['ItemPrice38'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice38'] * $_POST['ItemQty38'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice38'] * $_POST['ItemQty38'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem38, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem38, "@prmItemID", $_POST['ItemID38'], SQLINT4);
			    mssql_bind($qryInsertItem38, "@prmLocation", $_POST['ItemStockLocation38'], SQLVARCHAR);
			    mssql_bind($qryInsertItem38, "@prmQty", $_POST['ItemQty38'], SQLINT4);
			    mssql_bind($qryInsertItem38, "@prmUnitPrice", $_POST['ItemPrice38'], SQLFLT8);
			    mssql_bind($qryInsertItem38, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem38, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem38, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs38 = mssql_execute($qryInsertItem38);
            }
            else
            {
			    $qryInsertItem38 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID38'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice38'] = $_POST['ItemPrice38'] - ($_POST['ItemPrice38'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice38'] * $_POST['ItemQty38'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice38'] * $_POST['ItemQty38'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem38, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem38, "@prmItemID", $_POST['ItemID38'], SQLINT4);
			    mssql_bind($qryInsertItem38, "@prmLocation", $_POST['ItemStockLocation38'], SQLVARCHAR);
			    mssql_bind($qryInsertItem38, "@prmQty", $_POST['ItemQty38'], SQLINT4);
			    mssql_bind($qryInsertItem38, "@prmUnitPrice", $_POST['ItemPrice38'], SQLFLT8);
			    mssql_bind($qryInsertItem38, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem38, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_38'], SQLFLT8);
			    mssql_bind($qryInsertItem38, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_38'], SQLVARCHAR);
			    mssql_bind($qryInsertItem38, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_38'], SQLVARCHAR);
			    mssql_bind($qryInsertItem38, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_38'], SQLVARCHAR);

			    mssql_bind($qryInsertItem38, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs38 = mssql_execute($qryInsertItem38);
            }
			if (isset($_POST['ItemFree38']) && $_POST['ItemFree38'] != 0)
			{
				$intItemQty = $_POST['ItemFree38'];
				$decItemPrice = 0;
				$qryInsertFreeItem38 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem38, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID38'] == '124' || $_POST['ItemID38'] == '139' || $_POST['ItemID38'] == '141' || $_POST['ItemID38'] == '142')
                {
                    $_POST['ItemID38'] = '100';
				    mssql_bind($qryInsertFreeItem38, "@prmItemID", $_POST['ItemID38'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem38, "@prmItemID", $_POST['ItemID38'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem38, "@prmLocation", $_POST['ItemStockLocation38'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem38, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem38, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem38, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem38, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs38a = mssql_execute($qryInsertFreeItem38);
			}
            if($_SESSION['Bundles2010_38'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_38']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle38_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID38'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_38'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_38'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_38 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_38, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_38, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_38, "@prmLocation", $_POST['ItemStockLocation38'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_38, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_38, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_38, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_38, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_38, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_38, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_38, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_38, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_38);
            }

            if ($_SESSION['Regenesis_2285_38'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_38'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_38 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_38, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_38, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_38, "@prmLocation", $_POST['ItemStockLocation38'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_38, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_38, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_38, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_38, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_38, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_38, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_38, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_38, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_38);
            }
		}

		if (isset($_POST['ItemID39']) && $_POST['ItemID39'] != 0)
		{
            if($_SESSION['Bundles2010_39'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle39_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem39 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID39'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice39'] = $_POST['ItemPrice39'] - ($_POST['ItemPrice39'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice39'] * $_POST['ItemQty39'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice39'] * $_POST['ItemQty39'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem39, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem39, "@prmItemID", $_POST['ItemID39'], SQLINT4);
			    mssql_bind($qryInsertItem39, "@prmLocation", $_POST['ItemStockLocation39'], SQLVARCHAR);
			    mssql_bind($qryInsertItem39, "@prmQty", $_POST['ItemQty39'], SQLINT4);
			    mssql_bind($qryInsertItem39, "@prmUnitPrice", $_POST['ItemPrice39'], SQLFLT8);
			    mssql_bind($qryInsertItem39, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem39, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem39, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs39 = mssql_execute($qryInsertItem39);
            }
            else
            {
			    $qryInsertItem39 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID39'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice39'] = $_POST['ItemPrice39'] - ($_POST['ItemPrice39'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice39'] * $_POST['ItemQty39'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice39'] * $_POST['ItemQty39'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem39, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem39, "@prmItemID", $_POST['ItemID39'], SQLINT4);
			    mssql_bind($qryInsertItem39, "@prmLocation", $_POST['ItemStockLocation39'], SQLVARCHAR);
			    mssql_bind($qryInsertItem39, "@prmQty", $_POST['ItemQty39'], SQLINT4);
			    mssql_bind($qryInsertItem39, "@prmUnitPrice", $_POST['ItemPrice39'], SQLFLT8);
			    mssql_bind($qryInsertItem39, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem39, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_39'], SQLFLT8);
			    mssql_bind($qryInsertItem39, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_39'], SQLVARCHAR);
			    mssql_bind($qryInsertItem39, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_39'], SQLVARCHAR);
			    mssql_bind($qryInsertItem39, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_39'], SQLVARCHAR);

			    mssql_bind($qryInsertItem39, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs39 = mssql_execute($qryInsertItem39);
            }
			if (isset($_POST['ItemFree39']) && $_POST['ItemFree39'] != 0)
			{
				$intItemQty = $_POST['ItemFree39'];
				$decItemPrice = 0;
				$qryInsertFreeItem39 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem39, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID39'] == '124' || $_POST['ItemID39'] == '139' || $_POST['ItemID39'] == '141' || $_POST['ItemID39'] == '142')
                {
                    $_POST['ItemID39'] = '100';
				    mssql_bind($qryInsertFreeItem39, "@prmItemID", $_POST['ItemID39'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem39, "@prmItemID", $_POST['ItemID39'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem39, "@prmLocation", $_POST['ItemStockLocation39'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem39, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem39, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem39, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem39, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs39a = mssql_execute($qryInsertFreeItem39);
			}
            if($_SESSION['Bundles2010_39'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_39']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle39_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID39'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_39'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_39'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_39 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_39, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_39, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_39, "@prmLocation", $_POST['ItemStockLocation39'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_39, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_39, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_39, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_39, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_39, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_39, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_39, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_39, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_39);
            }

            if ($_SESSION['Regenesis_2285_39'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_39'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_39 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_39, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_39, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_39, "@prmLocation", $_POST['ItemStockLocation39'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_39, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_39, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_39, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_39, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_39, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_39, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_39, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_39, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_39);
            }
		}

		if (isset($_POST['ItemID40']) && $_POST['ItemID40'] != 0)
		{
            if($_SESSION['Bundles2010_40'] != '')
            {
                while($rowGetBundle = mssql_fetch_array($qryCartBundle40_0))
                {
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . "0";
                }
                $qryInsertItem40 = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                if($_POST['ItemID40'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice40'] = $_POST['ItemPrice40'] - ($_POST['ItemPrice40'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice40'] * $_POST['ItemQty40'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice40'] * $_POST['ItemQty40'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem40, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem40, "@prmItemID", $_POST['ItemID40'], SQLINT4);
			    mssql_bind($qryInsertItem40, "@prmLocation", $_POST['ItemStockLocation40'], SQLVARCHAR);
			    mssql_bind($qryInsertItem40, "@prmQty", $_POST['ItemQty40'], SQLINT4);
			    mssql_bind($qryInsertItem40, "@prmUnitPrice", $_POST['ItemPrice40'], SQLFLT8);
			    mssql_bind($qryInsertItem40, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                mssql_bind($qryInsertItem40, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
			    mssql_bind($qryInsertItem40, "RETVAL", $intItemStatusCode, SQLINT2);
			    $rs40 = mssql_execute($qryInsertItem40);
            }
            else
            {
			    $qryInsertItem40 = mssql_init("spOrders_Items_Create", $connProcess);
                if($_POST['ItemID40'] != '620' && $_SESSION['Summer2012FineLinePrimer'] == 0 && $_SESSION['Promo_Code'] != '' && $_SESSION['Promo_Code_Discount'] > 0)
                {
                    $_POST['ItemPrice40'] = $_POST['ItemPrice40'] - ($_POST['ItemPrice40'] * $_SESSION['Promo_Code_Discount']);
			        $decExtendedPrice = $_POST['ItemPrice40'] * $_POST['ItemQty40'];
                }
                else
                {
			        $decExtendedPrice = $_POST['ItemPrice40'] * $_POST['ItemQty40'];
                }
			    $intItemStatusCode = 0;
			    mssql_bind($qryInsertItem40, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertItem40, "@prmItemID", $_POST['ItemID40'], SQLINT4);
			    mssql_bind($qryInsertItem40, "@prmLocation", $_POST['ItemStockLocation40'], SQLVARCHAR);
			    mssql_bind($qryInsertItem40, "@prmQty", $_POST['ItemQty40'], SQLINT4);
			    mssql_bind($qryInsertItem40, "@prmUnitPrice", $_POST['ItemPrice40'], SQLFLT8);
			    mssql_bind($qryInsertItem40, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);

                // GMC - 01/19/14 - Discount Promo Code International Items
                mssql_bind($qryInsertItem40, "@prmIntDiscProValue", $_SESSION['IntDiscProValue_40'], SQLFLT8);
			    mssql_bind($qryInsertItem40, "@prmIntDiscProCode", $_SESSION['IntDiscProCode_40'], SQLVARCHAR);
			    mssql_bind($qryInsertItem40, "@prmIntDiscProStartDate", $_SESSION['IntDiscProStartDate_40'], SQLVARCHAR);
			    mssql_bind($qryInsertItem40, "@prmIntDiscProEndDate", $_SESSION['IntDiscProEndDate_40'], SQLVARCHAR);

			    mssql_bind($qryInsertItem40, "RETVAL", $intItemStatusCode, SQLINT2);

			    $rs40 = mssql_execute($qryInsertItem40);
            }
			if (isset($_POST['ItemFree40']) && $_POST['ItemFree40'] != 0)
			{
				$intItemQty = $_POST['ItemFree40'];
				$decItemPrice = 0;
				$qryInsertFreeItem40 = mssql_init("spOrders_Items_Create", $connProcess);
				$intFreeItemStatusCode = 0;
				mssql_bind($qryInsertFreeItem40, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                if($_POST['ItemID40'] == '124' || $_POST['ItemID40'] == '139' || $_POST['ItemID40'] == '141' || $_POST['ItemID40'] == '142')
                {
                    $_POST['ItemID40'] = '100';
				    mssql_bind($qryInsertFreeItem40, "@prmItemID", $_POST['ItemID40'], SQLINT4);
                }
                else
                {
				    mssql_bind($qryInsertFreeItem40, "@prmItemID", $_POST['ItemID40'], SQLINT4);
                }
				mssql_bind($qryInsertFreeItem40, "@prmLocation", $_POST['ItemStockLocation40'], SQLVARCHAR);
				mssql_bind($qryInsertFreeItem40, "@prmQty", $intItemQty, SQLINT4);
				mssql_bind($qryInsertFreeItem40, "@prmUnitPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem40, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
				mssql_bind($qryInsertFreeItem40, "RETVAL", $intFreeItemStatusCode, SQLINT10);
				$rs40a = mssql_execute($qryInsertFreeItem40);
			}
            if($_SESSION['Bundles2010_40'] != '')
            {
                $sess_values = explode("~", $_SESSION['Bundles2010_40']);
                $intItemCatCount = 1;
                while($rowGetBundle = mssql_fetch_array($qryCartBundle40_1))
                {
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $intBundleId = $rowGetBundle['BundleID'];
                    $strItemCategory = "BNDL" . $intBundleId . $intItemCatCount;
                    $qryInsertBundle = mssql_init("spOrders_Items_Create_Bundle", $connProcess);
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmItemCategory", $strItemCategory, SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);
                    $rs = mssql_execute($qryInsertBundle);
                    $intItemCatCount++;
                }
            }
            if ($_POST['ItemID40'] == 967)
            {
                $Add_1022_If_967 = "Yes";
            }

            // GMC - 06/23/15 - Regenesis - Promotion 3 + 1
            if ($_SESSION['Regenesis_2286_40'] > 0)
            {
                // Insert ReGenesis Back Bar Conditioner
                $intItemQty = $_SESSION['Regenesis_2286_40'];
			    $decItemPrice = 0;
			    $intItemId = 1599;
			    $qryInsertRegenesis_2286_40 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2286_40, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_40, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2286_40, "@prmLocation", $_POST['ItemStockLocation40'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_40, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2286_40, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_40, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2286_40, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2286_40, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_40, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_40, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2286_40, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2286_40);
            }

            if ($_SESSION['Regenesis_2285_40'] > 0)
            {
                // Insert ReGenesis Back Bar Shampoo
                $intItemQty = $_SESSION['Regenesis_2285_40'];
			    $decItemPrice = 0;
			    $intItemId = 1598;
			    $qryInsertRegenesis_2285_40 = mssql_init("spOrders_Items_Create", $connProcess);

			    // BIND PARAMETERS
			    $intFreeItemStatusCode = 0;
			    mssql_bind($qryInsertRegenesis_2285_40, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_40, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertRegenesis_2285_40, "@prmLocation", $_POST['ItemStockLocation40'], SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_40, "@prmQty", $intItemQty, SQLINT4);
			    mssql_bind($qryInsertRegenesis_2285_40, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_40, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertRegenesis_2285_40, "@prmIntDiscProValue", $RegenesisInt, SQLFLT8);
			    mssql_bind($qryInsertRegenesis_2285_40, "@prmIntDiscProCode", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_40, "@prmIntDiscProStartDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_40, "@prmIntDiscProEndDate", $RegenesisStr, SQLVARCHAR);
			    mssql_bind($qryInsertRegenesis_2285_40, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			    $rsGC = mssql_execute($qryInsertRegenesis_2285_40);
            }
		}

        // GMC - 12/25/09 Correct NAV 321 Consumer Only
        // GMC - 07/28/12 - Cancel sending Item 662 with all orders
        // GMC - 08/28/12 - Change Item 662 for 700
        // GMC - 12/04/12 - Change Item 700 for 853 - 854 or 855

        if ($_SESSION['CustomerTypeID'] == 1)
        {
            // GMC - 12/17/09 - Insert Item 204 into Order (Brochure with every order NAV 321)
            // GMC - 02/24/10 - Change Item 204 for 247 by JS
            // GMC - 07/01/11 - Change Item 247 for 402 by JS
            // GMC - 03/16/12 - Change Item 402 for 539 and 515 for 540 by JS
            // GMC - 06/25/12 - Change Item 539 for 662 (Domestic and International)
            // GMC - 03/11/13 - Web Orders to include extra item (Domestic = 853, International = 855)

            $intItemQty = 1;
		    $decItemPrice = 0;

            // $intItemId = 147; // Test
		    // $intItemId = 204; // Production
		    // $intItemId = 247; // Production
		    // $intItemId = 402; // Production
		    // $intItemId = 662; // Production
		    // $intItemId = 700; // Production

            if($_SESSION['IsInternational'] == 1)
            {
		        $intItemId = 1266; // Production
            }
            else
            {
                /*
                if(strtoupper($_SESSION['Ship_State']) == 'CA' || strtoupper($_SESSION['Ship_State']) == 'TN')
                {
		            $intItemId = 853; // Production
                }
                else
                {
                    $intItemId = 854; // Production
                }
                */

                // GMC - 08/22/13 - Web Orders to include extra item (Domestic = 1060)
                // GMC - 10/20/14 - Web Orders to include extra item (Domestic = 1324)
                // $intItemId = 853; // Production
                // $intItemId = 1060; // Production
                $intItemId = 1324; // Production
            }

		    $qryInsertBrochure = mssql_init("spOrders_Items_Create", $connProcess);

            // BIND PARAMETERS
            $intFreeItemStatusCode = 0;
		    mssql_bind($qryInsertBrochure, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
		    mssql_bind($qryInsertBrochure, "@prmItemID", $intItemId, SQLINT4);
            mssql_bind($qryInsertBrochure, "@prmLocation",$_POST['ItemStockLocation1'], SQLVARCHAR);
            mssql_bind($qryInsertBrochure, "@prmQty", $intItemQty, SQLINT4);
            mssql_bind($qryInsertBrochure, "@prmUnitPrice", $decItemPrice, SQLFLT8);
		    mssql_bind($qryInsertBrochure, "@prmExtendedPrice", $decItemPrice, SQLFLT8);

            // GMC - 04/25/14 - Fix of Store Procedures passing parameters
            $DiscProValueBrochure = 0;
            $DiscProCodeBrochure = "";
            $DiscProStartDateBrochure = "";
            $DiscProEndDateBrochure = "";
            mssql_bind($qryInsertBrochure, "@prmIntDiscProValue", $DiscProValueBrochure, SQLFLT8);
		    mssql_bind($qryInsertBrochure, "@prmIntDiscProCode", $DiscProCodeBrochure, SQLVARCHAR);
		    mssql_bind($qryInsertBrochure, "@prmIntDiscProStartDate", $DiscProStartDateBrochure, SQLVARCHAR);
		    mssql_bind($qryInsertBrochure, "@prmIntDiscProEndDate", $DiscProEndDateBrochure, SQLVARCHAR);

		    mssql_bind($qryInsertBrochure, "RETVAL", $intFreeItemStatusCode, SQLINT2);

		    $rsGC = mssql_execute($qryInsertBrochure);

            // GMC - 08/12/13 - Insert Item NAV-ID 1573-1022 into Order with every order NAV-ID 1425-967
            if ($Add_1022_If_967 == "Yes")
            {
                $intItemQty = 1;
                $decItemPrice = 0;
                $intItemId = 1022; // Production
                $qryInsertBrochure = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertBrochure, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertBrochure, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertBrochure, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                mssql_bind($qryInsertBrochure, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertBrochure, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertBrochure, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertBrochure, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertBrochure);
            }
        }

        // GMC - 05/29/12 - Shipment Fine Line Sheet Brochure to Resellers
        // GMC - 12/05/12 - Stop Item 619 with all Orders
        // GMC - 09/09/13 - Shipment Nav-ID 1653-1074 with every WEB-CSR entered DOMESTIC RESELLER ORDERS
        // GMC - 10/25/13 - Shipment Nav-ID 1654-1109 with every WEB-CSR entered DOMESTIC RESELLER ORDERS
        // GMC - 01/02/14 - Cancel Shipment Nav-ID 1654-1109 with every WEB-CSR entered DOMESTIC RESELLER ORDERS
        /*
        if ($_SESSION['CustomerTypeID'] == 2 && $_SESSION['IsInternational'] == 0)
        {
            $intItemQty = 1;
		    $decItemPrice = 0;
		    $intItemId = 1109; // Production

		    $qryInsertBrochRes = mssql_init("spOrders_Items_Create", $connProcess);

            // BIND PARAMETERS
            $intFreeItemStatusCode = 0;
		    mssql_bind($qryInsertBrochRes, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
		    mssql_bind($qryInsertBrochRes, "@prmItemID", $intItemId, SQLINT4);
            mssql_bind($qryInsertBrochRes, "@prmLocation",$_POST['ItemStockLocation1'], SQLVARCHAR);
            mssql_bind($qryInsertBrochRes, "@prmQty", $intItemQty, SQLINT4);
            mssql_bind($qryInsertBrochRes, "@prmUnitPrice", $decItemPrice, SQLFLT8);
		    mssql_bind($qryInsertBrochRes, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
		    mssql_bind($qryInsertBrochRes, "RETVAL", $intFreeItemStatusCode, SQLINT2);

		    $rsGC = mssql_execute($qryInsertBrochRes);
        }
        */

        // GMC - 01/28/13 NAV-ID 1394-901 with every Reseller Order entered in Admin
        // GMC - 04/15/13 Cancel NAV-ID 1394-901 with every Reseller Order entered in Admin
        // GMC - 05/06/13 - Nouriche NAV 1487 RecordID 989 Product Sheet to Resellers Only
        // GMC - 06/27/13 - Cancel Nouriche NAV 1487 RecordID 989 Product Sheet to Resellers Only
        /*
        if ($_SESSION['CustomerTypeID'] == 2 && $_SESSION['IsInternational'] == 0)
        {
            $intItemQty = 1;
		    $decItemPrice = 0;
		    $intItemId = 989; // Production

		    $qryInsertBrochRes = mssql_init("spOrders_Items_Create", $connProcess);

            // BIND PARAMETERS
            $intFreeItemStatusCode = 0;
		    mssql_bind($qryInsertBrochRes, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
		    mssql_bind($qryInsertBrochRes, "@prmItemID", $intItemId, SQLINT4);
            mssql_bind($qryInsertBrochRes, "@prmLocation",$_POST['ItemStockLocation1'], SQLVARCHAR);
            mssql_bind($qryInsertBrochRes, "@prmQty", $intItemQty, SQLINT4);
            mssql_bind($qryInsertBrochRes, "@prmUnitPrice", $decItemPrice, SQLFLT8);
		    mssql_bind($qryInsertBrochRes, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
		    mssql_bind($qryInsertBrochRes, "RETVAL", $intFreeItemStatusCode, SQLINT2);

		    $rsGC = mssql_execute($qryInsertBrochRes);
        }
        */
	}

	// GET CUSTOMER NAME
	$qryGetCustomerName = mssql_query("SELECT FirstName, LastName, NavisionCustomerID FROM tblCustomers WHERE RecordID = " . $_SESSION['CustomerID']);

	while($rowGetCustomerName = mssql_fetch_array($qryGetCustomerName))
	{
		$CustomerName = $rowGetCustomerName['FirstName'] . ' ' . $rowGetCustomerName['LastName'];
		$CustomerNavisionID = $rowGetCustomerName['NavisionCustomerID'];
	}

    // GMC - 05/20/10 - Ship To Name in the Web Order Header
    // GET SHIP TO NAME
    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
	// $qryGetShipToName = mssql_query("SELECT Attn FROM tblCustomers_ShipTo WHERE CustomerID = " . $_SESSION['CustomerID'] . " AND IsDefault = 'True'");
	$qryGetShipToName = mssql_query("SELECT CompanyName, Attn FROM tblCustomers_ShipTo WHERE CustomerID = " . $_SESSION['CustomerID'] . " AND IsDefault = 'True'");

	while($rowGetShipToName = mssql_fetch_array($qryGetShipToName))
	{
		$ShipToCompanyName = $rowGetShipToName['CompanyName'];
		$ShipToName = $rowGetShipToName['Attn'];
	}

    if($ShipToName == "")
    {
        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
	    // $qryGetShipToNameAlt = mssql_query("SELECT top 1 Attn FROM tblCustomers_ShipTo WHERE CustomerID = " . $_SESSION['CustomerID'] . " AND Attn is not NULL");
	    $qryGetShipToNameAlt = mssql_query("SELECT top 1 CompanyName, Attn FROM tblCustomers_ShipTo WHERE CustomerID = " . $_SESSION['CustomerID'] . " AND Attn is not NULL");

     	while($rowGetShipToNameAlt = mssql_fetch_array($qryGetShipToNameAlt))
	    {
		   $ShipToCompanyName = $rowGetShipToName['CompanyName'];
		   $ShipToName = $rowGetShipToNameAlt['Attn'];
	    }
    }

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

	// GET SHIPPING METHOD INFORMATION
	$qryGetOrderShipMethod = mssql_query("SELECT NavisionCode FROM conShippingMethods WHERE RecordID = " . $_SESSION['ShippingMethod']);

	while($rowGetShipMethod = mssql_fetch_array($qryGetOrderShipMethod))
	{
		$ShipMethod = $rowGetShipMethod['NavisionCode'];
	}

	// SET SALES REP
	if (isset($_SESSION['IsRevitalashLoggedIn']) && $_SESSION['IsRevitalashLoggedIn'] == 1)
	{
		$SalesRep = '';

		$qryGetSalesRep = mssql_query("SELECT tblRevitalash_Users.RevitalashID FROM tblCustomers LEFT JOIN tblRevitalash_Users ON tblCustomers.SalesRepID = tblRevitalash_Users.RecordID WHERE tblCustomers.RecordID = " . $_SESSION['CustomerID']);

		while($rowGetSalesRep = mssql_fetch_array($qryGetSalesRep))
		{
			$SalesRep = $rowGetSalesRep['RevitalashID'];
		}
	}
	else
	{
		$qryGetSalesRep = mssql_query("SELECT tblRevitalash_Users.RevitalashID FROM tblCustomers LEFT JOIN tblRevitalash_Users ON tblCustomers.SalesRepID = tblRevitalash_Users.RecordID WHERE tblCustomers.RecordID = " . $_SESSION['CustomerID']);

		while($rowGetSalesRep = mssql_fetch_array($qryGetSalesRep))
		{
			$SalesRep = $rowGetSalesRep['RevitalashID'];
		}
	}

    // GMC - 07/19/10 - Get Order Date to populate in WebOrderHeader (NAV)
    $qryGetOrderDate = mssql_query("SELECT OrderDate from tblOrders WHERE RecordID = " . $_SESSION['OrderID']);
    while($rowGetOrderDate = mssql_fetch_array($qryGetOrderDate))
    {
        $OrderDate = $rowGetOrderDate['OrderDate'];
    }

	// GET ORDER ITEMS
    // GMC - 11/26/10 - Send CartDescription instead of ProductName
    // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
    // GMC - 01/19/14 - Discount Promo Code International Items
	// $qryGetOrderItems = mssql_query("SELECT tblOrders_Items.UnitPrice, tblOrders_Items.Qty, tblOrders_Items.ExtendedPrice, tblProducts.ProductName, tblProducts.PartNumber, tblOrders_Items.Location FROM tblOrders_Items INNER JOIN tblProducts ON tblOrders_Items.ItemID = tblProducts.RecordID WHERE tblOrders_Items.OrderID = " . $_SESSION['OrderID']);
    // $qryGetOrderItems = mssql_query("SELECT tblOrders_Items.UnitPrice, tblOrders_Items.Qty, tblOrders_Items.ExtendedPrice, tblProducts.ProductName, tblProducts.CartDescription, tblProducts.PartNumber, tblOrders_Items.Location FROM tblOrders_Items INNER JOIN tblProducts ON tblOrders_Items.ItemID = tblProducts.RecordID WHERE tblOrders_Items.OrderID = " . $_SESSION['OrderID']);
    // $qryGetOrderItems = mssql_query("SELECT tblOrders_Items.UnitPrice, tblOrders_Items.Qty, tblOrders_Items.ExtendedPrice, tblProducts.ProductName, tblProducts.CartDescription, tblProducts.PartNumber, tblOrders_Items.Location, tblOrders_Items.ItemCategory FROM tblOrders_Items INNER JOIN tblProducts ON tblOrders_Items.ItemID = tblProducts.RecordID WHERE tblOrders_Items.OrderID = " . $_SESSION['OrderID']);
    $qryGetOrderItems = mssql_query("SELECT tblOrders_Items.UnitPrice, tblOrders_Items.Qty, tblOrders_Items.ExtendedPrice, tblProducts.ProductName, tblProducts.CartDescription, tblProducts.PartNumber, tblOrders_Items.Location, tblOrders_Items.ItemCategory, tblOrders_Items.DiscountValue, tblOrders_Items.IntDiscProCode, tblOrders_Items.IntDiscProStartDate, tblOrders_Items.IntDiscProEndDate FROM tblOrders_Items INNER JOIN tblProducts ON tblOrders_Items.ItemID = tblProducts.RecordID WHERE tblOrders_Items.OrderID = " . $_SESSION['OrderID']);

	// CLOSE DATABASE CONNECTION
	mssql_close($connProcess);

	if ($intUserID != 75)
	{
		$connNavision = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on 65.46.25.26");
		mssql_select_db('Athena', $connNavision);

		$qryInsertNAVOrder = mssql_init("wsInsertWebOrder", $connNavision);

		// BIND PARAMETERS
		$intNavImportStatus = 0;
		mssql_bind($qryInsertNAVOrder, "@prmNAVCustomerID", $CustomerNavisionID, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmWebOrderID", $_SESSION['OrderID'], SQLVARCHAR);

        // GMC - 05/20/10 - Ship To Name in the Web Order Header
        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
		// mssql_bind($qryInsertNAVOrder, "@prmCustomerName", $CustomerName, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmCompanyName", $ShipToCompanyName, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmCustomerName", $ShipToName, SQLVARCHAR);

		mssql_bind($qryInsertNAVOrder, "@prmAddress1", $Address1, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmAddress2", $Address2, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmAddressCity", $AddressCity, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmAddressState", $AddressState, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmAddressPostalCode", $AddressPostalCode, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmAddressCountry", $AddressCountryCode, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmShipMethod", $ShipMethod, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmSalesRep", $SalesRep, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmPaymentMethod", $_SESSION['PaymentType'], SQLVARCHAR);

        // GMC  - 03/26/09 - Add PromoCode - CampaignNo for Athena - Web Order Header
		mssql_bind($qryInsertNAVOrder, "@prmCampaignNo", $PromoCode, SQLVARCHAR);

		mssql_bind($qryInsertNAVOrder, "@prmSpecialInstructions", $OrderNotes, SQLVARCHAR);
		mssql_bind($qryInsertNAVOrder, "@prmSalesTaxAmount", $OrderTax, SQLFLT8);
		mssql_bind($qryInsertNAVOrder, "@prmShippingAmount", $OrderShipping, SQLFLT8);
		mssql_bind($qryInsertNAVOrder, "@prmCurrencyAdjustment", $OrderAdjustment, SQLFLT8);

        // GMC - 01/28/10 - Include PONumber, EnteredBy and Location in Order Header NAV
        // GMC - 02/02/10 - RevitalashIdInUserId_NAV_WebHeader
        // mssql_bind($qryInsertNAVOrder, "@prmEnteredBy", $intUserID, SQLINT4);
        mssql_bind($qryInsertNAVOrder, "@prmEnteredBy", $_SESSION['RevitalashID'], SQLVARCHAR);

        if ($PONumber != '')
		    mssql_bind($qryInsertNAVOrder, "@prmPONumber", $PONumber, SQLVARCHAR);
	    else
		    mssql_bind($qryInsertNAVOrder, "@prmPONumber", $PONumber, SQLVARCHAR, false, true);

		mssql_bind($qryInsertNAVOrder, "@prmLocationCode", $Location, SQLVARCHAR);

        // GMC - 04/02/10 - Insert Instructions Field in NAV
  		mssql_bind($qryInsertNAVOrder, "@prmInstructions", $OrderNotes, SQLVARCHAR);

        // GMC - 07/19/10 - Get Order Date to populate in WebOrderHeader (NAV)
  		mssql_bind($qryInsertNAVOrder, "@prmOrderDate", $OrderDate, SQLVARCHAR);

        // GMC - 02/01/11 - Order Closed By CSR ADMIN Partner - Rep
  		mssql_bind($qryInsertNAVOrder, "@prmOrderClosedBy", $OrderClosedBy, SQLVARCHAR);

        // GMC - 02/14/11 - Get New Order Date as string to populate in WebOrderHeader (NAV)
  		mssql_bind($qryInsertNAVOrder, "@prmNewOrderDate", $OrderDate, SQLVARCHAR);

        // GMC - 07/14/11 - Distributors Change CSRADMIN
        mssql_bind($qryInsertNAVOrder, "@prmDistributorCode", $DistributorCode, SQLTEXT);
        mssql_bind($qryInsertNAVOrder, "@prmPromisedDate", $PromisedDate, SQLTEXT);
        mssql_bind($qryInsertNAVOrder, "@prmTransportationCharges", $TransportationCharges, SQLTEXT);
        mssql_bind($qryInsertNAVOrder, "@prmTransportationChargesValue", $TransportationChargesValue, SQLTEXT);
        mssql_bind($qryInsertNAVOrder, "@prmDutyTax", $DutyTax, SQLTEXT);
        mssql_bind($qryInsertNAVOrder, "@prmDutyTaxValue", $DutyTaxValue, SQLTEXT);

        // GMC - 02/16/13 - Special Customer Code passed to NAV
        mssql_bind($qryInsertNAVOrder, "@prmSpecialCustomerCode", $intSpecialCustomerCode, SQLINT4);

        // GMC - 11/11/14 - Include Shipping Values for Fullfillment
        mssql_bind($qryInsertNAVOrder, "@prmTotalNetOrderShipping", $_SESSION['OrderShipping'], SQLFLT8);
        mssql_bind($qryInsertNAVOrder, "@prmTotalOrderWeight", $_SESSION['OrderWeight'], SQLFLT8);
        mssql_bind($qryInsertNAVOrder, "@prmTotalBoxCount", $_SESSION['TotalBoxCount'], SQLFLT8);

        // GMC - 05/11/15 - Integrate CAP Products into Admin
        mssql_bind($qryInsertNAVOrder, "@prmActualShippingCharge", $_SESSION['CapRateReal'], SQLFLT8);

		$rsInsertNAVOrder = mssql_execute($qryInsertNAVOrder);
		$intLineNumber = 0;

		while($rowGetOrderItems = mssql_fetch_array($qryGetOrderItems))
		{
            $intLineNumber++;

			$Location = $rowGetOrderItems["Location"];

             // GMC - 11/26/10 - Send CartDescription instead of ProductName
			// $Item = $rowGetOrderItems["ProductName"];
			$Item = $rowGetOrderItems["CartDescription"];

			$ItemNumber = $rowGetOrderItems["PartNumber"];
			$Qty = $rowGetOrderItems["Qty"];
			$UnitPrice = $rowGetOrderItems["UnitPrice"];

            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
            $ItemCategory = $rowGetOrderItems["ItemCategory"];

            // GMC - 01/19/14 - Discount Promo Code International Items
            $DiscountValue = $rowGetOrderItems["DiscountValue"];
            $IntDiscProCode = $rowGetOrderItems["IntDiscProCode"];
            $IntDiscProStartDate = $rowGetOrderItems["IntDiscProStartDate"];
            $IntDiscProEndDate = $rowGetOrderItems["IntDiscProEndDate"];

			$qryInsertNAVOrderItem = mssql_init("wsInsertWebOrderItem", $connNavision);

			// BIND PARAMETERS
			mssql_bind($qryInsertNAVOrderItem, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			mssql_bind($qryInsertNAVOrderItem, "@prmOrderItemID", $intLineNumber, SQLINT4);
			mssql_bind($qryInsertNAVOrderItem, "@prmLocation", $Location, SQLVARCHAR);
			mssql_bind($qryInsertNAVOrderItem, "@prmItem", $Item, SQLVARCHAR);
			mssql_bind($qryInsertNAVOrderItem, "@prmItemNumber", $ItemNumber, SQLVARCHAR);
			mssql_bind($qryInsertNAVOrderItem, "@prmQty", $Qty, SQLFLT8);
			mssql_bind($qryInsertNAVOrderItem, "@prmUnitPrice", $UnitPrice, SQLFLT8);

            // GMC - 10/10/12 - Insert ItemCategory in tblOrders_Items
			mssql_bind($qryInsertNAVOrderItem, "@prmItemCategory", $ItemCategory, SQLVARCHAR);

            // GMC - 01/19/14 - Discount Promo Code International Items
			mssql_bind($qryInsertNAVOrderItem, "@prmDiscountValue", $DiscountValue, SQLFLT8);
			mssql_bind($qryInsertNAVOrderItem, "@prmIntDiscProCode", $IntDiscProCode, SQLVARCHAR);
			mssql_bind($qryInsertNAVOrderItem, "@prmIntDiscProStartDate", $IntDiscProStartDate, SQLVARCHAR);
			mssql_bind($qryInsertNAVOrderItem, "@prmIntDiscProEndDate", $IntDiscProEndDate, SQLVARCHAR);

			$rsInsertNAVOrderItem = mssql_execute($qryInsertNAVOrderItem);
		}

		// CLOSE DATABASE CONNECTION
		mssql_close($connNavision);
	}
}
else
{
	$qryDeclined = mssql_init("spOrders_Declined", $connProcess);
	mssql_bind($qryDeclined, "@prmOrderID", $OrderSeed, SQLINT4);
	$rsDeclined = mssql_execute($qryDeclined);

     // GMC - 05/09/10 - To avoid an extra shipping only charge to the credit card
    if($OrderSubtotal > 0)
    {
        $pageerror = 'There was a problem processing your credit card. Please specify another payment method.';
	}
    else
    {
        $pageerror = '';
    }

	mssql_close($connProcess);
}

?>
