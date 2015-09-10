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

// GMC - 10/03/09 - Shipping and Handling = 0 for EU Orders
// GMC - 11/06/10 - Cancel Shipping and Handling = 0 for EU Orders
/*
if($_SESSION['CountryCodeFedExEu_Retail'] != '')
{
	$_SESSION['OrderHandling'] = 0;
}
*/

// GMC - 10/24/10 - Bundles Project Oct 2010
// EXECUTE SQL QUERY
if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
{
	$qryCartBundle = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID1']);
}

if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
{
	$qryCartBundle2 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID2']);
}

if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
{
	$qryCartBundle3 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID3']);
}

if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
{
	$qryCartBundle4 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID4']);
}

if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
{
	$qryCartBundle5 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID5']);
}

if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
{
	$qryCartBundle6 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID6']);
}

if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
{
	$qryCartBundle7 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID7']);
}

if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
{
	$qryCartBundle8 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID8']);
}

if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
{
	$qryCartBundle9 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID9']);
}

if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
{
	$qryCartBundle10 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID10']);
}

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
{
	$qryCartBundle11 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID11']);
}

if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
{
	$qryCartBundle12 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID12']);
}

if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
{
	$qryCartBundle13 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID13']);
}

if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
{
	$qryCartBundle14 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID14']);
}

if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
{
	$qryCartBundle15 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID15']);
}

if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
{
	$qryCartBundle16 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID16']);
}

if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
{
	$qryCartBundle17 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID17']);
}

if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
{
	$qryCartBundle18 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID18']);
}

if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
{
	$qryCartBundle19 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID19']);
}

if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
{
	$qryCartBundle20 = mssql_query("SELECT NavID, Description, UnitPrice, Qty, Weight FROM tblBundles WHERE ProductID = " . $_POST['ItemID20']);
}

// GMC - 05/28/09 - June Promotion 2009 - Ends on June 30th - Hair Buy 2 Get Free Ground Shipping
// GMC 06/29/09 - End June Promotions
/*
if ($_SESSION['Hair_June_2009_Free_Ground_Promo'] == 'True' && $_SESSION['ShipMethodID'] == '300')
{
	$_SESSION['OrderHandling'] = 0;
}
*/


// echo $_SESSION['OrderSubtotal'];
// echo $_SESSION['ShippingMethod'];
// echo $_SESSION['ShipToZip'];
// echo $_SESSION['PaymentCC_BillingPostalCode'];


// GMC - 02/06/12 - Consumer Web Anti-Fraud Flags
// GMC - 02/14/12 - Only one flag in Consumer Web Anti-Fraud
if($_SESSION['CustomerTypeID'] == 1)
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

// GMC - 04/12/10 - LTL Shipments
// GMC - 05/12/10 - LTL Shipment Not a Payment Type -->
/*
elseif ($_SESSION['PaymentType'] == 'LTL')
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
*/

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

// GMC - 10/21/09 - No Shipping - Handling for Order greater than $50.00 (Customers Only)
// GMC - 02/03/10 - Cancel No Shipping for Order Greater than $50.00 (Customers Only)
/*
else if($_SESSION['OrderSubtotal'] > 50 && $_SESSION['CustomerTypeID'] == "1")
{
    $OrderShipping = 0;
    $_SESSION['OrderShipping'] = 0;
    $_SESSION['OrderHandling'] = 0;
}
*/

// GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
else if($_SESSION['Promo_Code'] == "MASCARA")
{
    $_SESSION['OrderShipping'] = 0;
    $_SESSION['OrderHandling'] = 0;
}

// GMC - 07/14/11 - Distributors Change CSRADMIN
if($_SESSION['Transportation_Charges'] == 'Collect' || $_SESSION['Transportation_Charges'] == 'Third Party')
{
    $_SESSION['OrderHandling'] = 0;
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
 // if($OrderSubtotal > 0)
 if($OrderTotal > 0)
 {
    if (isset($_SESSION['CurrencyCountryCode']) && $_SESSION['CurrencyCountryCode'] != 'US')
	{
		$qryGetCustomerName = mssql_query("SELECT FirstName, LastName, NavisionCustomerID FROM tblCustomers WHERE RecordID = " . $_SESSION['CustomerID']);
		
		while($rowGetCustomerName = mssql_fetch_array($qryGetCustomerName))
		{
			$CustomerFirstName = $rowGetCustomerName['FirstName'];
			$CustomerLastName = $rowGetCustomerName['LastName'];
			$CustomerName = $rowGetCustomerName['FirstName'] . ' ' . $rowGetCustomerName['LastName'];
			$CustomerNavisionID = $rowGetCustomerName['NavisionCustomerID'];
		}
		
		require_once('usaepay.php');
		
		// Instantiate USAePay client object
		$tran=new umTransaction;
		
		// Merchants Source key must be generated within the console
		$tran->key="IIfWEsr1cYbL26orp11uQhTFU7rLmo0X";
		$tran->pin="4th3na";
		
		// GMC - Change from Test to Production
		// $tran->testmode=1;
		
		$tran->card = $_SESSION['PaymentCC_Number'];
		$tran->exp = $_SESSION['PaymentCC_ExpMonth'] . $_SESSION['PaymentCC_ExpYear'];
		$tran->currency = $_SESSION['CurrencyCode'];		

        // GMC - 07/31/09 - Japan and South Korea No Decimals
        if($_SESSION['CurrencyCountryCode'] == "JP" || $_SESSION['CurrencyCountryCode'] == "KR")
        {
            $tran->amount = number_format(convert($OrderTotal,$decExchangeRate,$strCurrencyName), 0, '.', '');
        }
        else
        {
            $tran->amount = number_format(convert($OrderTotal,$decExchangeRate,$strCurrencyName), 2, '.', '');
        }

        $tran->invoice = $OrderSeed;   		
		$tran->cardholder = $CustomerFirstName . " " . $CustomerLastName; 			
		$tran->description = "Online Order";	
		$tran->cvv2 = $_SESSION['PaymentCC_CVV'];
		$tran->cabundle = 'c:\windows\curl-ca-bundle.crt';				
		
		if($tran->Process())
		{
			$CCAuthorization = $tran->authcode;
			$CCTransactionID = 'USAEPAY';
			$OrderAdjustment = $tran->convertedamount;
		}
		else
		{
			$blnPaymentError = 1;	
		}
	}
	else
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
		$CCAuthorization = $_SESSION['PaymentCC_SwipedAuth'];
		$CCTransactionID = 'TRADESHOW';
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

//  GMC - 04/12/10 - LTL Shipments
// GMC - 05/12/10 - LTL Shipment Not a Payment Type -->
/*
elseif ($_SESSION['PaymentType'] == 'LTL')
// NO CHARGE TRANSACTION
{
    $CCAuthorization = 'LTL';
    $CCTransactionID = 'LTL';
}
*/

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

	if (isset($_SESSION['PaymentPO_Number']))
		$PONumber = $_SESSION['PaymentPO_Number'];
	else
		$PONumber = '';
		
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
                }
					
                // GMC - 08/07/09 - Bug - Why add 10 extra to international just at the item level??
                /*
                if (($_SESSION['IsInternational'] == 1) && ($blnIsLiterature != 1))
				{
					$decUnitPrice = number_format($decUnitPrice + $row["InternationalSurcharge"], 2, '.', '');
					$decExtendedPrice = number_format($decExtendedPrice + ($row["InternationalSurcharge"] * $_SESSION['cart'][$key]), 2, '.', '');
				}
                */
                
                // GMC - 05/06/09 - FedEx Netherlands
                // GMC - 10/09/10 - Force Shipping from Las Vegas for EU Orders
                if($_SESSION['CountryCodeFedExEu_Retail'] != '')
                {
            	    // $Location = 'FEDEXNETH';
            	    $Location = 'ATHENA-LV';
                }
                else
                {
                    // GMC - 12/04/09 - Las Vegas Facility
                    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
                    /*if ($_SESSION['IsInternational'] == 0)
					{
                        $Location = 'ATHENA-LV';
                    }
                    else
                    {
                        $Location = 'MAIN';
                    }
                    */

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
				mssql_bind($qryInsertItem, "RETVAL", $intItemStatusCode, SQLINT2);
				
				$rs = mssql_execute($qryInsertItem);
		
				if ((!isset($_SESSION['CustomerTerms']) || $_SESSION['CustomerTerms'] == 0) && ($_SESSION['CustomerTypeID'] == 2) && ($row["ResellerFreeTrigger"] > 0) && (floor($_SESSION['cart'][$key] / $row["ResellerFreeTrigger"]) >= 1))
				{
                    // GMC - 06/10/11 - NAV Item 498 - 593 and 594 - 12 + 2 Free from 062011 to 090111
                    // GMC - 08/27/11 - Deactivate NAV Item 498 - 593 and 594 - 12 + 2 Free from 062011 to 090111
                    /*
                    if($_SESSION['AdvancedFormula2011Promo_Pro'] > 0)
		            {
					    $intItemQty = $_SESSION['AdvancedFormula2011Promo_Pro'];
                    }
                    else
                    {
                        $intItemQty = floor($_SESSION['cart'][$key] / $row["ResellerFreeTrigger"]);
                    }
                    */
                    
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
					mssql_bind($qryInsertFreeItem, "RETVAL", $intFreeItemStatusCode, SQLINT2);
					
					$rs = mssql_execute($qryInsertFreeItem);
				}
				/*elseif (($_SESSION['CustomerTypeID'] == 3) && ($row["DistributorFreeTrigger"] > 0) && (floor($_SESSION['cart'][$key] / $row["DistributorFreeTrigger"]) >= 1))
				{
					$intItemQty = floor($_SESSION['cart'][$key] / $row["DistributorFreeTrigger"]);
					$decItemPrice = 0;
					$qryInsertFreeItem = mssql_init("spOrders_Items_Create", $connProcess);
				
					// BIND PARAMETERS
					$intFreeItemStatusCode = 0;
					mssql_bind($qryInsertFreeItem, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
					mssql_bind($qryInsertFreeItem, "@prmItemID", $key, SQLINT4);
					mssql_bind($qryInsertFreeItem, "@prmLocation", $Location, SQLVARCHAR);
					mssql_bind($qryInsertFreeItem, "@prmQty", $intItemQty, SQLINT4);
					mssql_bind($qryInsertFreeItem, "@prmUnitPrice", $decItemPrice, SQLFLT8);
					mssql_bind($qryInsertFreeItem, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
					mssql_bind($qryInsertFreeItem, "RETVAL", $intFreeItemStatusCode, SQLINT2);
					
					$rs = mssql_execute($qryInsertFreeItem);
				}*/
				
				// GMC - 03/10/09 - Disable Promotion 123
				/*
				if (($_SESSION['CustomerTypeID'] == 2) && $key == 100 && $_SESSION['cart'][$key] >= 12)
                {
					$intItemQty = $_SESSION['cart'][$key];
					$decItemPrice = 0;
					$qryInsertPromoItem = mssql_init("spOrders_Items_Create", $connProcess);
				
					$intFreeItem = 123;
				
					// BIND PARAMETERS
					$intPromoItemStatusCode = 0;
					mssql_bind($qryInsertPromoItem, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
					mssql_bind($qryInsertPromoItem, "@prmItemID", $intFreeItem, SQLINT4);
					mssql_bind($qryInsertPromoItem, "@prmLocation", $Location, SQLVARCHAR);
					mssql_bind($qryInsertPromoItem, "@prmQty", $intItemQty, SQLINT4);
					mssql_bind($qryInsertPromoItem, "@prmUnitPrice", $decItemPrice, SQLFLT8);
					mssql_bind($qryInsertPromoItem, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
					mssql_bind($qryInsertPromoItem, "RETVAL", $intPromoItemStatusCode, SQLINT2);
					
					$rs = mssql_execute($qryInsertPromoItem);
              	}
              	*/
              	
			}
		}

		// GMC - 04/15/09 - Mother's Day Promo 2009
        // GMC - 05/18/09 - Cancel Mother's Day Promo 2009
        /*
        if ($_SESSION['Revitalash_Hair_Pro_Quantity'] >= 12)
        {
			$intItemQty = $_SESSION['Revitalash_Hair_Pro_Quantity'];
			$decItemPrice = 0;
			$intItemId = 160;
			$qryInsertFreeGiftCandle = mssql_init("spOrders_Items_Create", $connProcess);

			// BIND PARAMETERS
			$intFreeItemStatusCode = 0;
			mssql_bind($qryInsertFreeGiftCandle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			mssql_bind($qryInsertFreeGiftCandle, "@prmItemID", $intItemId, SQLINT4);
            mssql_bind($qryInsertFreeGiftCandle, "@prmLocation", $Location, SQLVARCHAR);
			mssql_bind($qryInsertFreeGiftCandle, "@prmQty", $intItemQty, SQLINT4);
			mssql_bind($qryInsertFreeGiftCandle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			mssql_bind($qryInsertFreeGiftCandle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			mssql_bind($qryInsertFreeGiftCandle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			$rsGC = mssql_execute($qryInsertFreeGiftCandle);
        }
        */

        /*
		// GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
		if($_SESSION['BreastCancerAwarenessPromo_Pro'] >= 12)
		{
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAwarenessPromo_Pro'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAwarenessPromo_Pro'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAwarenessPromo_Pro'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAwarenessPromo_Pro'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_Mascara);
                }
        }

        // GMC - 11/02/09 - 2009 Holiday Gift Box Set
		if($_SESSION['HolidayGiftBoxSet2009Promo_Pro'] > 0)
		{
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009Promo_Pro'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_Revitalash);

                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009Promo_Pro'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_Revitalash);

                }
        }

        // GMC 01/05/10 - Valentine's Day 2010 Promotion
        if($_SESSION['ValentinesDay2010Promo_Pro'] > 0)
		{
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010Promo_Pro'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010Promo_Pro'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_Candle, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_Candle);

                     // Insert Valetines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010Promo_Pro'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_Bag, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                         // Insert Revitalash
                         $intItemQty = $_SESSION['ValentinesDay2010Promo_Pro'];
                         $decItemPrice = 0;
                         // $intItemId = 126; // Test
                         $intItemId = 194; // Production
                         $qryInsertValentinesDay2010_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                         // BIND PARAMETERS
                         $intFreeItemStatusCode = 0;
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmLocation", $Location, SQLVARCHAR);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                         $rsGC = mssql_execute($qryInsertValentinesDay2010_Revitalash);

                         // Insert Compact Mirror
                         $intItemQty = $_SESSION['ValentinesDay2010Promo_Pro'];
                         $decItemPrice = 0;
                         // $intItemId = 151; //Test
                         $intItemId = 137; //Production
                         $qryInsertValentinesDay2010_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

                         // BIND PARAMETERS
                         $intFreeItemStatusCode = 0;
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmItemID", $intItemId, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmLocation", $Location, SQLVARCHAR);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmQty", $intItemQty, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                         $rsGC = mssql_execute($qryInsertValentinesDay2010_Mirror);

                         // Insert Valetines Day Promotion Bag
                         $intItemQty = $_SESSION['ValentinesDay2010Promo_Pro'];
                         $decItemPrice = 0;
                         // $intItemId = 151; //Test
                         $intItemId = 230; //Production
                         $qryInsertValentinesDay2010_Bag = mssql_init("spOrders_Items_Create", $connProcess);

                         // BIND PARAMETERS
                         $intFreeItemStatusCode = 0;
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmItemID", $intItemId, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmLocation", $Location, SQLVARCHAR);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmQty", $intItemQty, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                         $rsGC = mssql_execute($qryInsertValentinesDay2010_Bag);
                     }
                     else
                     {
                         // Insert Revitalash
                         $intItemQty = $_SESSION['ValentinesDay2010Promo_Pro'];
                         $decItemPrice = 0;
                         // $intItemId = 126; // Test

                         // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
                         // $intItemId = 125; // Production
                         $intItemId = 107; // Production

                         $qryInsertValentinesDay2010_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                         // BIND PARAMETERS
                         $intFreeItemStatusCode = 0;
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmLocation", $Location, SQLVARCHAR);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                         $rsGC = mssql_execute($qryInsertValentinesDay2010_Revitalash);

                         // Insert Compact Mirror
                         $intItemQty = $_SESSION['ValentinesDay2010Promo_Pro'];
                         $decItemPrice = 0;
                         // $intItemId = 151; //Test
                         $intItemId = 109; //Production
                         $qryInsertValentinesDay2010_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

                         // BIND PARAMETERS
                         $intFreeItemStatusCode = 0;
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmItemID", $intItemId, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmLocation", $Location, SQLVARCHAR);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmQty", $intItemQty, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                         $rsGC = mssql_execute($qryInsertValentinesDay2010_Mirror);

                         // Insert Valetines Day Promotion Bag
                         $intItemQty = $_SESSION['ValentinesDay2010Promo_Pro'];
                         $decItemPrice = 0;
                         // $intItemId = 151; //Test
                         $intItemId = 229; //Production
                         $qryInsertValentinesDay2010_Bag = mssql_init("spOrders_Items_Create", $connProcess);

                         // BIND PARAMETERS
                         $intFreeItemStatusCode = 0;
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmItemID", $intItemId, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmLocation", $Location, SQLVARCHAR);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmQty", $intItemQty, SQLINT4);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                         mssql_bind($qryInsertValentinesDay2010_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                         $rsGC = mssql_execute($qryInsertValentinesDay2010_Bag);
                     }
                }
        }

        // GMC - 03/16/10 Mother's Day 2010 Promotiom
        if($_SESSION['MothersDay2010Promo_Pro'] > 0)
		{
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['MothersDay2010Promo_Pro'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['MothersDay2010Promo_Pro'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_Mascara, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['MothersDay2010Promo_Pro'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['MothersDay2010Promo_Pro'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_Mascara, "@prmLocation", $Location, SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_Mascara);
                }
        }
        */

        // GMC - 12/17/09 - Insert Item 204 into Order (Brochure with every order NAV 321)
        // GMC - 02/24/10 - Change Item 204 for 247 by JS
        // GMC - 07/01/11 - Change Item 247 for 402 by JS
        // GMC - 03/16/12 - Change Item 402 for 539 and 515 for 540 by JS
        $intItemQty = 1;
		$decItemPrice = 0;
		// $intItemId = 147; // Test
		// $intItemId = 204; // Production
		// $intItemId = 247; // Production
		// $intItemId = 402; // Production
        if($_SESSION['IsInternational'] == 0)
        {
		    $intItemId = 539; // Production
        }
        else
        {
		    $intItemId = 540; // Production
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

        // GMC - 11/02/10 - 2010 Holiday Gift Set (RecordID = 288)
        // GMC - 11/15/10 - Cancel Set Insert for 2010 Holiday Gift Set (RecordID = 288)
        /*
       if($_SESSION['2010_Holiday_Gift_Set'] != '')
       {
           // Separate values from Session
           $sess_values = explode("~", $_SESSION['2010_Holiday_Gift_Set']);

           $resultBun = mssql_query("SELECT NavID, Qty, UnitPrice FROM tblBundles WHERE ProductID = " . $sess_values[0]);
           while($row = mssql_fetch_array($resultBun))
           {
               $intItemQty = $row["Qty"] * $sess_values[1];
		       $decItemPrice = $row["UnitPrice"];
		       $intItemId = $row["NavID"];
		       $qryInsertBun = mssql_init("spOrders_Items_Create", $connProcess);

               // BIND PARAMETERS
               $intFreeItemStatusCode = 0;
               mssql_bind($qryInsertBun, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
               mssql_bind($qryInsertBun, "@prmItemID", $intItemId, SQLINT4);
               mssql_bind($qryInsertBun, "@prmLocation", $Location, SQLVARCHAR);
               mssql_bind($qryInsertBun, "@prmQty", $intItemQty, SQLINT4);
               mssql_bind($qryInsertBun, "@prmUnitPrice", $decItemPrice, SQLFLT8);
               mssql_bind($qryInsertBun, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
               mssql_bind($qryInsertBun, "RETVAL", $intFreeItemStatusCode, SQLINT2);

               $rs = mssql_execute($qryInsertBun);
	       }
       }
       */
       
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

	}
	else
	{
		if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
		{
            $qryInsertItem1 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
            {
                $_POST['ItemPrice1'] = $_POST['ItemPrice1'] - ($_POST['ItemPrice1'] * $_SESSION['Promo_Code_Discount']);
			    $decExtendedPrice = $_POST['ItemPrice1'] * $_POST['ItemQty1'];
            }





            // GMC - 05/01/12 - New Special Revitabrow Promotion for MAY 2012
            else if ($_POST['ItemID1'] == '392' && $_SESSION['RevitaBrowPromoMay2012'] == 1)
            {
			    $decExtendedPrice = ($_POST['ItemPrice1'] * $_POST['ItemQty1'])*.25;
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
			mssql_bind($qryInsertItem1, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs1 = mssql_execute($qryInsertItem1);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_1'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_1'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_1_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_1_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_1_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_1'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_1_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_1_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_1_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_1_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_1'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_1'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_1_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_1'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_1_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_1'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_1'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_1_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_1_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_1_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_1_Candle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_1_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_1_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_1_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_1_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_1_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_1_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_1_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_1_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_1_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_1_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_1_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_1_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_1_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_1_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_1_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_1'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_1_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_1_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Hair, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_1_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_1_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitabrow, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_1_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_1_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_1_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_1_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_1_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_1']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_1_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Espresso, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_1_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_1_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_1'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_1'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_1_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_1_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_1_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_1'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_1_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_1_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_1_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_1'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_1_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_1_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_1_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_1'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_1']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_1'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_1'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_1 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_1, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_1, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_1, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_1, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_1, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_1, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_1, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_1);
            }
            */
            
		}
		
		if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
		{
			$qryInsertItem2 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem2, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs2 = mssql_execute($qryInsertItem2);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_2'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_2'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_2_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_2_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_2_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_2'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_2_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_2_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_2_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_2_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_2'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_2'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_1_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_2_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_2'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_2_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_2'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_2'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_2_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_2_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_2_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_2_Candle, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_2_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_2_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_2_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_2_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_2_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_2_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_2_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_2_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_2_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_2_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_2_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_2_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_2_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_2_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_2_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_2'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_2_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_2_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Hair, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_2_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_2_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitabrow, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_2_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_2_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_2_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_2_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_2_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_2']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_2_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Espresso, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_2_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_2_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_2'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_2'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_2_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_2_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_2_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_2'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_2_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_2_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_2_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_2'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_2_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_2_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_2_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_2'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_2']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle2))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_2'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_2'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_2 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_2, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_2, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_2, "@prmLocation", $_POST['ItemStockLocation2'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_2, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_2, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_2, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_2, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_2);
            }
            */

		}
		
		if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
		{
			$qryInsertItem3 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem3, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs3 = mssql_execute($qryInsertItem3);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_3'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_3'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_3_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_3_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_3_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_3'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_3_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_3_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_3_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_3_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_3'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_3'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_3_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_3'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_3_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_3'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_3'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_3_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_3_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_3_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_3_Candle, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_3_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_3_Candle);
            
                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_3_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_3_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_3_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_3_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_3_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_3_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_3_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_3_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_3_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_3_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_3_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_3_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_3_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_3'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_3_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_3_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Hair, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_3_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_3_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitabrow, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_3_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_3_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_3_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_3_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_3_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_3']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_3_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Espresso, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_3_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_3_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_3'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_3'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_3_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_3_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_3_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_3'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_3_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_3_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_3_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_3'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_3_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_3_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_3_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_3'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_3']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle3))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_3'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_3'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_3 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_3, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_3, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_3, "@prmLocation", $_POST['ItemStockLocation3'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_3, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_3, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_3, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_3, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_3);
            }
            */

		}
		
		if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
		{
			$qryInsertItem4 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem4, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs4 = mssql_execute($qryInsertItem4);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_4'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_4'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_4_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_4_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_4_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_4'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_4_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_4_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_4_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_4_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_4'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_4'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_4_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_4'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_4_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_4'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_4'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_4_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_4_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_4_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_4_Candle, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_4_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_4_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_4_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_4_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_4_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_4_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_4_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_4_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_4_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_4_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_4_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_4_Mirror);
            
                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_4_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_4_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_4_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_4'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_4_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_4_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Hair, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_4_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_4_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitabrow, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_4_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_4_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_4_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_4_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_4_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                //$intItemQty = $_SESSION['TradeShowFamilyBundle2010_4'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_4']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_4_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Espresso, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_4_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_4_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_4'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_4'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_4_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_4_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_4_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_4'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_4_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_4_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_4_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_4'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_4_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_4_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_4_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_4'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_4']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle4))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_4'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_4'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_4 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_4, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_4, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_4, "@prmLocation", $_POST['ItemStockLocation4'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_4, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_4, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_4, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_4, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_4);
            }
            */

		}
		
		if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
		{
			$qryInsertItem5 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem5, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs5 = mssql_execute($qryInsertItem5);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_5'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_5'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_5_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_5_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_5_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_5'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_5_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_5_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_5_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_5_Mascara);
                }
            }
            
            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_5'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_5'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_5_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_5'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_5_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_5'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_5'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_5_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_5_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_5_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_5_Candle, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_5_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_5_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_5_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_5_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_5_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_5_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_5_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_5_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_5_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_5_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_5_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_5_Mirror);
            
                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_5_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_5_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_5_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_5'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_5_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_5_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Hair, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_5_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_5_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitabrow, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_5_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_5_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_5_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_5_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_5_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_5']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_5_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Espresso, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_5_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_5_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_5'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_5'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_5_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_5_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_5_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_5'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_5_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_5_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_5_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_5'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_5_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_5_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_5_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_5'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_5']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle5))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_5'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_5'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_5 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_5, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_5, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_5, "@prmLocation", $_POST['ItemStockLocation5'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_5, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_5, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_5, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_5, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_5);
            }
            */

		}
		
		if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
		{
			$qryInsertItem6 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem6, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs6 = mssql_execute($qryInsertItem6);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_6'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_6'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_6_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_6_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_6_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_6'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_6_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_6_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_6_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_6_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_6'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_6'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_6_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_6'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_6_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_6'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_6'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_6_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_6_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_6_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_6_Candle, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_6_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_6_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_6_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_6_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_6_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_6_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_6_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_6_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_6_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_6_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_6_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_6_Mirror);
            
                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_6_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_6_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_6_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_6'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_6_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_6_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Hair, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_6_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_6_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitabrow, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_6_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_6_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_6_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_6_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_6_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_6']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_6_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Espresso, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_6_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_6_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_6'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_6'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_6_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_6_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_6_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_6'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_6_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_6_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_6_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_6'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_6_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_6_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_6_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_6'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_6']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle6))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_6'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_6'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_6 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_6, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_6, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_6, "@prmLocation", $_POST['ItemStockLocation6'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_6, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_6, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_6, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_6, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_6);
            }
            */

		}
		
		if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
		{
			$qryInsertItem7 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem7, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs7 = mssql_execute($qryInsertItem7);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_7'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_7'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_7_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_7_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_7_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_7'];
			         $decItemPrice = 0;
			         //$intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_7_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_7_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_7_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_7_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_7'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_7'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_7_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_7'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_7_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_7'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_7'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_7_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_7_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_7_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_7_Candle, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_7_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_7_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_7_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_7_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_7_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_7_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_7_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_7_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_7_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_7_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_7_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_7_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_7_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_7_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_7_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_7'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_7_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_7_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Hair, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_7_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_7_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitabrow, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_7_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_7_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_7_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_7_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_7_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_7']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_7_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Espresso, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_7_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_7_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_7'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_7'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_7_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_7_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_7_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_7'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_7_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_7_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_7_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_7'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_7_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_7_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_7_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_7'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_7']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle7))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_7'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_7'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_7 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_7, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_7, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_7, "@prmLocation", $_POST['ItemStockLocation7'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_7, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_7, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_7, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_7, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_7);
            }
            */

		}
		
		if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
		{
			$qryInsertItem8 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem8, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs8 = mssql_execute($qryInsertItem8);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_8'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_8'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_8_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_8_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_8_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_8'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_8_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_8_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_8_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_8_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_8'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_8'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_8_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_8'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_8_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_8'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_8'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_8_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_8_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_8_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_8_Candle, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_8_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_8_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_8_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_8_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_8_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_8_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_8_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_8_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_8_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_8_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_8_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_8_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_8_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_8_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_8_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_8'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_8_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_8_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Hair, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_8_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_8_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitabrow, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_8_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_8_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_8_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_8_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_8_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_8']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_8_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Espresso, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_8_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_8_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_8'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_8'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_8_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_8_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_8_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_8'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_8_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_8_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_8_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_8'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_8_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_8_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_8_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_8'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_8']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle8))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_8'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_8'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_8 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_8, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_8, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_8, "@prmLocation", $_POST['ItemStockLocation8'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_8, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_8, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_8, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_8, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_8);
            }
            */

		}
		
		if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
		{
			$qryInsertItem9 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem9, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs9 = mssql_execute($qryInsertItem9);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_9'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_9'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_9_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_9_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_9_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_9'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_9_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_9_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_9_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_9_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_9'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_9'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_9_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_9'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_9_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_9'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_9'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_9_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_9_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_9_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_9_Candle, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_9_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_9_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_9_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_9_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_9_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_9_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_9_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_9_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_9_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_9_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_9_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_9_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_9_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_9_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_9_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_9'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_9_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_9_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Hair, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_9_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_9_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitabrow, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_9_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_9_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_9_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_9_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_9_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_9']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_9_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Espresso, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_9_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_9_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_9'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_9'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_9_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_9_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_9_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_9'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_9_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_9_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_9_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_9'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_9_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_9_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_9_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_9'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_9']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle9))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_9'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_9'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_9 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_9, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_9, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_9, "@prmLocation", $_POST['ItemStockLocation9'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_9, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_9, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_9, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_9, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_9);
            }
            */

		}
		
		if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
		{
			$qryInsertItem10 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem10, "RETVAL", $intItemStatusCode, SQLINT2);
			
			$rs10 = mssql_execute($qryInsertItem10);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_10'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_10'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_10_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_10_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_10_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_10'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_10_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_10_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_10_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_10_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_10'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_10'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_10_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_10'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_10_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_10'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_10'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_10_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_10_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_10_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_10_Candle, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_10_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_10_Candle);
            
                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_10_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_10_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_10_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_10_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_10_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_10_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_10_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_10_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_10_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_10_Mirror);
            
                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_10_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_10_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_10_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_10'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_10_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_10_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Hair, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_10_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_10_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitabrow, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_10_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_10_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_10_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_10_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_10_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_10']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_10_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Espresso, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_10_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_10_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_10'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_10'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_10_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_10_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_10_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_10'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_10_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_10_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_10_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_10'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_10_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_10_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_10_Mascara);
                }
            }
            */
            
            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_10'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_10']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle10))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_10'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_10'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_10 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_10, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_10, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_10, "@prmLocation", $_POST['ItemStockLocation10'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_10, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_10, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_10, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_10, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_10);
            }
            */

		}

        // GMC - 03/18/10 - Add 10 Line Items Admin

		if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
		{
			$qryInsertItem11 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem11, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs11 = mssql_execute($qryInsertItem11);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_11'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_11'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_11_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_11_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_11_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_11'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_11_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_11_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_11_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_11_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_11'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_11'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_11_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_11'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_11_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_11'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_11'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_11_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_11_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_11_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_11_Candle, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_11_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_11_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_11_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_11_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_11_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_11_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_11_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_11_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_11_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_11_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_11_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_11_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_11_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_11_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_11_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_11'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_11_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_11_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Hair, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_11_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_11_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitabrow, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_11_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_11_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_11_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_11_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_11_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_11']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_11_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Espresso, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_11_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_11_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_11'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_11'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_11_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_11_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_11_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_11'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_11_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_11_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_11_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_11'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_11_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_11_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_11_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_11'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_11']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle11))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_11'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_11'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_11 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_11, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_11, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_11, "@prmLocation", $_POST['ItemStockLocation11'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_11, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_11, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_11, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_11, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_11);
            }
            */

		}

		if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
		{
			$qryInsertItem12 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem12, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs12 = mssql_execute($qryInsertItem12);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_12'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_12'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_12_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_12_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_12_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_12'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_12_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_12_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_12_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_12_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_12'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_12'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_12_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_12'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_12_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_12'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_12'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_12_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_12_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_12_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_12_Candle, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_12_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_12_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_12_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_12_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_12_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_12_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_12_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_12_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_12_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_12_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_12_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_12_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_12_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_12_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_12_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_12'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_12_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_12_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Hair, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_12_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_12_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitabrow, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_12_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_12_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_12_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_12_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_12_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_12']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_12_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Espresso, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_12_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_12_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_12'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_12'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_12_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_12_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_12_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_12'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_12_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_12_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_12_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_12'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_12_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_12_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_12_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_12'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_12']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle12))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_12'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_12'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_12 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_12, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_12, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_12, "@prmLocation", $_POST['ItemStockLocation12'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_12, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_12, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_12, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_12, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_12);
            }
            */

		}

		if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
		{
			$qryInsertItem13 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem13, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs13 = mssql_execute($qryInsertItem13);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_13'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_13'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_13_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_13_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_13_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_13'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_13_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_13_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_13_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_13_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_13'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_13'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_13_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_13'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_13_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_13'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_13'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_13_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_13_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_13_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_13_Candle, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_13_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_13_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_13_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_13_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_13_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_13_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_13_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_13_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_13_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_13_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_13_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_13_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_13_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_13_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_13_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_13'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_13_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_13_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Hair, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_13_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_13_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitabrow, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_13_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_13_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_13_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_13_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_13_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_13']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_13_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Espresso, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_13_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_13_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_13'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_13'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_13_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_13_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_13_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_13'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_13_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_13_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_13_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_13'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_13_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_13_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_13_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_13'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_13']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle13))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_13'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_13'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_13 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_13, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_13, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_13, "@prmLocation", $_POST['ItemStockLocation13'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_13, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_13, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_13, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_13, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_13);
            }
            */

		}

		if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
		{
			$qryInsertItem14 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem14, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs14 = mssql_execute($qryInsertItem14);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_14'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_14'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_14_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_14_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_14_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_14'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_14_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_14_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_14_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_14_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_14'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_14'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_14_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_14'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_14_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_14'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_14'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_14_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_14_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_14_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_14_Candle, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_14_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_14_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_14_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_14_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_14_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_14_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_14_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_14_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_14_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_14_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_14_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_14_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_14_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_14_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_14_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_14'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_14_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_14_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Hair, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_14_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_14_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitabrow, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_14_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_14_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_14_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_14_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_14_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_14']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_14_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Espresso, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_14_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_14_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_14'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_14'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_14_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_14_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_14_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_14'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_14_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_14_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_14_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_14'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_14_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_14_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_14_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_14'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_14']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle14))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_14'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_14'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_14 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_14, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_14, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_14, "@prmLocation", $_POST['ItemStockLocation14'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_14, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_14, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_14, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_14, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_14);
            }
            */

		}

		if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
		{
			$qryInsertItem15 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem15, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs15 = mssql_execute($qryInsertItem15);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_15'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_15'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_15_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_15_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_15_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_15'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_15_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_15_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_15_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_15_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_15'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_15'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_15_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_15'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_15_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_15'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_15'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_15_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_15_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_15_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_15_Candle, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_15_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_15_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_15_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_15_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_15_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_15_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_15_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_15_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_15_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_15_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_15_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_15_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_15_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_15_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_15_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_15'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_15_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_15_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Hair, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_15_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_15_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitabrow, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_15_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_15_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_15_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_15_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_15_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_15']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_15_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Espresso, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_15_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_15_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_15'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_15'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_15_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_15_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_15_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_15'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_15_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_15_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_15_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_15'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_15_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_15_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_15_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_15'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_15']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle15))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_15'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_15'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_15 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_15, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_15, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_15, "@prmLocation", $_POST['ItemStockLocation15'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_15, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_15, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_15, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_15, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_15);
            }
            */

		}

		if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
		{
			$qryInsertItem16 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem16, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs16 = mssql_execute($qryInsertItem16);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_16'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_16'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_16_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_16'];
			         $decItemPrice = 0;
			         // $intItemId = 161; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_16_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_16_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_16'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_16_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_16'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_16_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_16_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_16_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_16'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_16'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_16_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_16'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_16_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_16'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_16'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_16_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_16_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_16_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_16_Candle, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_16_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_16_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_16_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_16_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_16_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_16_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_16_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_16_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_16_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_16_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_16_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_16_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_16_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_16_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_16_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_16'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_16_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_16_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Hair, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_16_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_16_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitabrow, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_16_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_16_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_16_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_16_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_16_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_16']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_16_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Espresso, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_16_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_16_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_16'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_16'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_16_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_16_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_16_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_16'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_16_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_16_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_16_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_16'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_16_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_16_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_16_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_16'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_16']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle16))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_16'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_16'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_16 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_16, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_16, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_16, "@prmLocation", $_POST['ItemStockLocation16'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_16, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_16, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_16, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_16, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_16);
            }
            */

		}

		if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
		{
			$qryInsertItem17 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem17, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs17 = mssql_execute($qryInsertItem17);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_17'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_17'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_17_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_17'];
			         $decItemPrice = 0;
			         // $intItemId = 161; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_17_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_17_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_17'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_17_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_17'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_17_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_17_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_17_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_17'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_17'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_17_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_17'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_17_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_17'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_17'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_17_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_17_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_17_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_17_Candle, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_17_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_17_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_17_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_17_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_17_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_17_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_17_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_17_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_17_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_17_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_17_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_17_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_17_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_17_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_17_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_17'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_17_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_17_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Hair, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_17_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_17_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitabrow, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_17_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_17_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_17_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_17_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_17_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_17']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_17_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Espresso, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_17_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_17_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_17'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_17'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_17_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_17_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_17_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_17'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_17_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_17_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_17_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_17'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_17_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_17_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_17_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_17'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_17']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle17))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_17'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_17'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_17 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_17, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_17, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_17, "@prmLocation", $_POST['ItemStockLocation17'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_17, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_17, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_17, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_17, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_17);
            }
            */
            
		}

		if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
		{
			$qryInsertItem18 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/18/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem18, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs18 = mssql_execute($qryInsertItem18);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_18'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_18'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_18_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_18'];
			         $decItemPrice = 0;
			         // $intItemId = 161; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_18_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_18_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_18'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_18_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_18'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_18_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_18_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_18_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_18'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_18'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_18_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_18'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_18_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_18'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_18'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_18_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_18_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_18_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_18_Candle, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_18_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_18_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_18_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_18_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_18_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_18_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_18_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_18_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_18_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 125; // Production

			         $qryInsertValentinesDay2010_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_18_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_18_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_18_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_18_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_18_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_18_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_18'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_18_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_18_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Hair, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_18_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_18_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitabrow, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_18_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_18_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_18_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_18_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_18_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_18']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_18_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Espresso, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_18_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_18_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_18'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_18'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_18_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_18_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_18_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_18'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_18_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_18_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_18_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_18'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_18_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_18_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_18_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_18'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_18']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle18))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_18'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_18'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_18 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_18, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_18, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_18, "@prmLocation", $_POST['ItemStockLocation18'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_18, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_18, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_18, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_18, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_18);
            }
            */
            
		}

		if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
		{
			$qryInsertItem19 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/19/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem19, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs19 = mssql_execute($qryInsertItem19);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_19'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_19'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_19_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_19'];
			         $decItemPrice = 0;
			         // $intItemId = 161; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_19_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_19_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_19'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_19_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_19'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_19_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_19_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_19_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_19'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_19'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_19_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_19'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_19_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_19'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_19'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_19_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_19_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_19_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_19_Candle, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_19_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_19_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_19_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_19_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_19_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_19_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_19_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_19_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_19_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_19_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_19_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_19_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_19_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_19_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_19_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_19'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_19_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_19_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Hair, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_19_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_19_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitabrow, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_19_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_19_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_19_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_19_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_19_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_19']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_19_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Espresso, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_19_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_19_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_19'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_19'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_19_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_19_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_19_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_19'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_19_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_19_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_19_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_19'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_19_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_19_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_19_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_19'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_19']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle19))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_19'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_19'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_19 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_19, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_19, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_19, "@prmLocation", $_POST['ItemStockLocation19'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_19, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_19, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_19, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_19, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_19);
            }
            */
            
		}

		if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
		{
			$qryInsertItem20 = mssql_init("spOrders_Items_Create", $connProcess);

            // GMC - 09/19/09 - To calculate the Promo Discount Value per Item
            if(($_SESSION['Promo_Code'] != '') && ($_SESSION['Promo_Code_Discount'] > 0))
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
			mssql_bind($qryInsertItem20, "RETVAL", $intItemStatusCode, SQLINT2);

			$rs20 = mssql_execute($qryInsertItem20);

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

            /*
            // GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
            if ($_SESSION['BreastCancerAugOct2009_20'] >= 12)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_20'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertBreastCancerAugOct2009_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_20_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_20'];
			         $decItemPrice = 0;
			         // $intItemId = 161; //Test
			         $intItemId = 158; //Production
			         $qryInsertBreastCancerAugOct2009_20_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_20_Mascara);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_20'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertBreastCancerAugOct2009_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_20_Revitalash);

                     // Insert Mascara
                     $intItemQty = $_SESSION['BreastCancerAugOct2009_20'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertBreastCancerAugOct2009_20_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertBreastCancerAugOct2009_20_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertBreastCancerAugOct2009_20_Mascara);
                }
            }

            // GMC - 11/02/09 - 2009 Holiday Gift Box Set
            if ($_SESSION['HolidayGiftBoxSet2009_20'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_20'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertHolidayGiftBoxSet2009_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_20_Revitalash);
                }
                else
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['HolidayGiftBoxSet2009_20'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertHolidayGiftBoxSet2009_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertHolidayGiftBoxSet2009_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertHolidayGiftBoxSet2009_20_Revitalash);
                }
            }

            // GMC 01/05/10 - Valentine's Day 2010 Promotion
            if($_SESSION['ValentinesDay2010_20'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_20'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertValentinesDay2010_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_20_Revitalash);

                     // Insert Candle
                     $intItemQty = $_SESSION['ValentinesDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 160; //Production
			         $qryInsertValentinesDay2010_20_Candle = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_20_Candle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Candle, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_20_Candle, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_20_Candle, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Candle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Candle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Candle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_20_Candle);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_20_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_20_Bag);
                }
                else
                {
                     // GMC - 01/10/10 - Valentine's Day 2010 French - Canada
                     if($key == '231')
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test
			         $intItemId = 194; // Production
			         $qryInsertValentinesDay2010_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_20_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 137; //Production
			         $qryInsertValentinesDay2010_20_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_20_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 230; //Production
			         $qryInsertValentinesDay2010_20_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_20_Bag);
                     }
                     else
                     {
                     // Insert Revitalash
                     $intItemQty = $_SESSION['ValentinesDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 126; // Test

                     // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
			         // $intItemId = 125; // Production
			         $intItemId = 107; // Production

			         $qryInsertValentinesDay2010_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_20_Revitalash);

                     // Insert Compact Mirror
                     $intItemQty = $_SESSION['ValentinesDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 109; //Production
			         $qryInsertValentinesDay2010_20_Mirror = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Mirror, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_20_Mirror);

                     // Insert Valentines Day Promotion Bag
                     $intItemQty = $_SESSION['ValentinesDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 229; //Production
			         $qryInsertValentinesDay2010_20_Bag = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertValentinesDay2010_20_Bag, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertValentinesDay2010_20_Bag);
                     }
                }
            }

            // GMC 01/29/10 - TradeShow Family Bundle 01-2010
            // GMC - 02/04/10 - TradeShow Bundle Nav Code 404 - CSRADMIN 241
            if($_SESSION['TradeShowFamilyBundle2010_20'] > 0)
            {
                // Insert Revitalash

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20']*5;

                $decItemPrice = 0;
                $intItemId = 100;
                $qryInsertTradeShowFamilyBundle2010_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_20_Revitalash);

                // Insert Hair

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 150; //Production
                $qryInsertTradeShowFamilyBundle2010_20_Hair = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Hair, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Hair, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Hair, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Hair, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Hair, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Hair, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Hair, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_20_Hair);

                // Insert Revitabrow

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 232; //Production
                $qryInsertTradeShowFamilyBundle2010_20_Revitabrow = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitabrow, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitabrow, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitabrow, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitabrow, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitabrow, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitabrow, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Revitabrow, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_20_Revitabrow);

                if($_SESSION['TradeShowFamilyBundleCode241'] == 1)
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20']*10;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_20_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_20_Raven);
                }
                else
                {
                // Insert Mascara Raven

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 159; //Production
                $qryInsertTradeShowFamilyBundle2010_20_Raven = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Raven, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_20_Raven);

                // Insert Mascara Espresso

                // GMC - 02/03/10 - Bug multiple by 5 the $intItemQty
                // $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20'];
                $intItemQty = $_SESSION['TradeShowFamilyBundle2010_20']*5;

                $decItemPrice = 0;
                // $intItemId = 151; //Test
                $intItemId = 217; //Production
                $qryInsertTradeShowFamilyBundle2010_20_Espresso = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Espresso, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Espresso, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Espresso, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Espresso, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Espresso, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Espresso, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryInsertTradeShowFamilyBundle2010_20_Espresso, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryInsertTradeShowFamilyBundle2010_20_Espresso);
                }
            }

            // GMC 03/09/10 - Mother's Day 2010 Promotion
            if($_SESSION['MothersDay2010_20'] > 0)
            {
                if ($_SESSION['IsInternational'] == 0)
                {
                     // Insert Revitalash Domestic
                     $intItemQty = $_SESSION['MothersDay2010_20'];
			         $decItemPrice = 0;
			         $intItemId = 100;
			         $qryInsertMothersDay2010_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_20_Revitalash);

                     // Insert Mascara Domestic
                     $intItemQty = $_SESSION['MothersDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 159; //Production
			         $qryInsertMothersDay2010_20_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_20_Mascara);
                }
                else
                {
                     // Insert Revitalash FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_20'];
			         $decItemPrice = 0;
			         $intItemId = 194;
			         $qryInsertMothersDay2010_20_Revitalash = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_20_Revitalash, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_20_Revitalash);

                     // Insert Mascara FR-Canadian
                     $intItemQty = $_SESSION['MothersDay2010_20'];
			         $decItemPrice = 0;
			         // $intItemId = 151; //Test
			         $intItemId = 190; //Production
			         $qryInsertMothersDay2010_20_Mascara = mssql_init("spOrders_Items_Create", $connProcess);

			         // BIND PARAMETERS
			         $intFreeItemStatusCode = 0;
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmItemID", $intItemId, SQLINT4);
                     mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmQty", $intItemQty, SQLINT4);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			         mssql_bind($qryInsertMothersDay2010_20_Mascara, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			         $rsGC = mssql_execute($qryInsertMothersDay2010_20_Mascara);
                }
            }
            */

            // GMC - 10/24/10 - Bundles Project Oct 2010
            if($_SESSION['Bundles2010_20'] != '')
            {
                // Separate values from Session
                $sess_values = explode("~", $_SESSION['Bundles2010_20']);

                // Iterate thru the other items in the tblBundles
                while($rowGetBundle = mssql_fetch_array($qryCartBundle20))
                {
                    // Insert Items from tblBundles
                    $intItemQty = $rowGetBundle['Qty'] * $sess_values[1];
                    $decItemPrice = $rowGetBundle['UnitPrice'];
                    $decExtendedPrice = $rowGetBundle['UnitPrice'] * $rowGetBundle['Qty'] * $sess_values[1];
                    $intItemId = $rowGetBundle['NavID'];
                    $qryInsertBundle = mssql_init("spOrders_Items_Create", $connProcess);

                    // BIND PARAMETERS
                    $intFreeItemStatusCode = 0;
                    mssql_bind($qryInsertBundle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmItemID", $intItemId, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
                    mssql_bind($qryInsertBundle, "@prmQty", $intItemQty, SQLINT4);
                    mssql_bind($qryInsertBundle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "@prmExtendedPrice", $decExtendedPrice, SQLFLT8);
                    mssql_bind($qryInsertBundle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                    $rs = mssql_execute($qryInsertBundle);
                }
            }

            // GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            // GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples
            /*
            if($_SESSION['MascaraEspressoBlowOut2010_20'] > 0)
            {
                // Insert Mascara Espresso
                $intItemQty = $_SESSION['MascaraEspressoBlowOut2010_20'];
                $decItemPrice = 0;
                $intItemId = 217;
                $qryMascaraEspressoBlowOut2010_20 = mssql_init("spOrders_Items_Create", $connProcess);

                // BIND PARAMETERS
                $intFreeItemStatusCode = 0;
                mssql_bind($qryMascaraEspressoBlowOut2010_20, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_20, "@prmItemID", $intItemId, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_20, "@prmLocation", $_POST['ItemStockLocation20'], SQLVARCHAR);
                mssql_bind($qryMascaraEspressoBlowOut2010_20, "@prmQty", $intItemQty, SQLINT4);
                mssql_bind($qryMascaraEspressoBlowOut2010_20, "@prmUnitPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_20, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
                mssql_bind($qryMascaraEspressoBlowOut2010_20, "RETVAL", $intFreeItemStatusCode, SQLINT2);

                $rsGC = mssql_execute($qryMascaraEspressoBlowOut2010_20);
            }
            */

		}

		// GMC - 04/15/09 - Mother's Day Promo 2009
        // GMC - 05/18/09 - Cancel Mother's Day Promo 2009
        /*
        if ($_SESSION['Revitalash_Hair_Total_Qty'] >= 12)
        {
			$intItemQty = $_SESSION['Revitalash_Hair_Total_Qty'];
			$decItemPrice = 0;
			$intItemId = 160;
			$qryInsertFreeGiftCandle = mssql_init("spOrders_Items_Create", $connProcess);

			// BIND PARAMETERS
			$intFreeItemStatusCode = 0;
			mssql_bind($qryInsertFreeGiftCandle, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			mssql_bind($qryInsertFreeGiftCandle, "@prmItemID", $intItemId, SQLINT4);
            mssql_bind($qryInsertFreeGiftCandle, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			mssql_bind($qryInsertFreeGiftCandle, "@prmQty", $intItemQty, SQLINT4);
			mssql_bind($qryInsertFreeGiftCandle, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			mssql_bind($qryInsertFreeGiftCandle, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			mssql_bind($qryInsertFreeGiftCandle, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			$rsGC = mssql_execute($qryInsertFreeGiftCandle);
        }
        */

        // GMC - 12/25/09 Correct NAV 321 Consumer Only
        if ($_SESSION['CustomerTypeID'] == 1)
        {
            // GMC - 12/17/09 - Insert Item 204 into Order (Brochure with every order NAV 321)
            // GMC - 02/24/10 - Change Item 204 for 247 by JS
            // GMC - 07/01/11 - Change Item 247 for 402 by JS
            // GMC - 03/16/12 - Change Item 402 for 539 and 515 for 540 by JS
            $intItemQty = 1;
		    $decItemPrice = 0;
		    // $intItemId = 147; // Test
		    // $intItemId = 204; // Production
		    // $intItemId = 247; // Production
		    // $intItemId = 402; // Production
            if($_SESSION['IsInternational'] == 0)
            {
		        $intItemId = 539; // Production
            }
            else
            {
		        $intItemId = 540; // Production
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
		    mssql_bind($qryInsertBrochure, "RETVAL", $intFreeItemStatusCode, SQLINT2);

		    $rsGC = mssql_execute($qryInsertBrochure);
        }

        // GMC - 08/28/11 - NAV Item 593 and 417 Get 12 + 1 plus multiples of 13 free effective 090111
        // GMC - 09/29/11 - NAV Item 593 and 417 Get 12 + 1 plus multiples of 13 free effective 090111 - Cancel Promo
        /*
        if ($_SESSION['PinkBag13Free2011'] > 0)
        {
			$intItemQty = $_SESSION['PinkBag13Free2011'];
			$decItemPrice = 0;
			$intItemId = 252;
			$qryInsertPinkBagFree = mssql_init("spOrders_Items_Create", $connProcess);

			// BIND PARAMETERS
			$intFreeItemStatusCode = 0;
			mssql_bind($qryInsertPinkBagFree, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			mssql_bind($qryInsertPinkBagFree, "@prmItemID", $intItemId, SQLINT4);
            mssql_bind($qryInsertPinkBagFree, "@prmLocation", $_POST['ItemStockLocation1'], SQLVARCHAR);
			mssql_bind($qryInsertPinkBagFree, "@prmQty", $intItemQty, SQLINT4);
			mssql_bind($qryInsertPinkBagFree, "@prmUnitPrice", $decItemPrice, SQLFLT8);
			mssql_bind($qryInsertPinkBagFree, "@prmExtendedPrice", $decItemPrice, SQLFLT8);
			mssql_bind($qryInsertPinkBagFree, "RETVAL", $intFreeItemStatusCode, SQLINT2);

			$rsGC = mssql_execute($qryInsertPinkBagFree);
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
	// $qryGetOrderItems = mssql_query("SELECT tblOrders_Items.UnitPrice, tblOrders_Items.Qty, tblOrders_Items.ExtendedPrice, tblProducts.ProductName, tblProducts.PartNumber, tblOrders_Items.Location FROM tblOrders_Items INNER JOIN tblProducts ON tblOrders_Items.ItemID = tblProducts.RecordID WHERE tblOrders_Items.OrderID = " . $_SESSION['OrderID']);
	$qryGetOrderItems = mssql_query("SELECT tblOrders_Items.UnitPrice, tblOrders_Items.Qty, tblOrders_Items.ExtendedPrice, tblProducts.ProductName, tblProducts.CartDescription, tblProducts.PartNumber, tblOrders_Items.Location FROM tblOrders_Items INNER JOIN tblProducts ON tblOrders_Items.ItemID = tblProducts.RecordID WHERE tblOrders_Items.OrderID = " . $_SESSION['OrderID']);

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
			
			$qryInsertNAVOrderItem = mssql_init("wsInsertWebOrderItem", $connNavision);
					
			// BIND PARAMETERS
			mssql_bind($qryInsertNAVOrderItem, "@prmOrderID", $_SESSION['OrderID'], SQLINT4);
			mssql_bind($qryInsertNAVOrderItem, "@prmOrderItemID", $intLineNumber, SQLINT4);
			mssql_bind($qryInsertNAVOrderItem, "@prmLocation", $Location, SQLVARCHAR);
			mssql_bind($qryInsertNAVOrderItem, "@prmItem", $Item, SQLVARCHAR);
			mssql_bind($qryInsertNAVOrderItem, "@prmItemNumber", $ItemNumber, SQLVARCHAR);
			mssql_bind($qryInsertNAVOrderItem, "@prmQty", $Qty, SQLFLT8);
			mssql_bind($qryInsertNAVOrderItem, "@prmUnitPrice", $UnitPrice, SQLFLT8);
			
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
