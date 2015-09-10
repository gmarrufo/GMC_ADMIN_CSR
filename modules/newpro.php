<?php 

// CONNECT TO SQL SERVER DATABASE
$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connCustomer);

$intCustomerTypeID = 2;

// GMC - 05/06/10 - Proper RecordIDs for Consumer-Reseller (Domestic and International)
// $SalesRepID = $_POST['SalesRepID'];

$strFirstName = $_POST['FirstName'];
$strLastName = $_POST['LastName'];
$strCompanyName = $_POST['CompanyName'];
$strTitle = $_POST['Title'];
$strEMailAddress = $_POST['EMailAddress'];
$strPassword = md5($_POST['Password']);
$strPasswordConfirm = md5($_POST['PasswordConfirm']);
$intSecurityQuestionID = $_POST['SecurityQuestionID'];
$strSecurityAnswer = $_POST['SecurityAnswer'];
$strAddress1 = $_POST['Address1'];
$strAddress2 = $_POST['Address2'];
$strCity = $_POST['City'];
$strState = $_POST['State'];
$strPostalCode = $_POST['PostalCode'];
$strCountryCode = $_POST['CountryCode'];

// GMC - 05/06/10 - Proper RecordIDs for Consumer-Reseller (Domestic and International)
if($strCountryCode == "US")
{
    $SalesRepID = 84;
}
else
{
    $SalesRepID = 128;
}

$strTelephone = preg_replace('/[^0-9]/', '', $_POST['Telephone']);
$strTelephoneExtension = preg_replace('/[^0-9]/', '', $_POST['TelephoneExtension']);
$strFax = preg_replace('/[^0-9]/', '', $_POST['FaxNumber']);
$strReferralSource = 'Undefined';
$intPreferredCurrencyID = 1;
$CompanyType = $_POST['CompanyType'];
$TaxIDNumber = $_POST['TaxIDNumber'];
$ResellerNumber = $_POST['TaxExemptID'];
if ($_POST['CoreBusiness'] == 'Other')
	$CoreBusiness = 'Other: ' . $_POST['CoreBusinessOther'];
else
	$CoreBusiness = 'Other: ' . $_POST['CoreBusinessOther'];
$OtherProductsCarried = $_POST['OtherProductsCarried'];
$Website = $_POST['WebsiteURL'];
$EstheticianLicense = $_POST['EstheticianLicense'];

$intStatusCode = 0;

// SPECIFY QUERY
$qryInsert = mssql_init("spCustomers_ProAddNew", $connCustomer);

// Bind the parameters
mssql_bind($qryInsert, "@prmCustomerTypeID", $intCustomerTypeID, SQLINT4);
mssql_bind($qryInsert, "@prmSalesRepID", $SalesRepID, SQLINT4);
mssql_bind($qryInsert, "@prmFirstName", $strFirstName, SQLVARCHAR);
mssql_bind($qryInsert, "@prmLastName", $strLastName, SQLVARCHAR);
mssql_bind($qryInsert, "@prmCompanyName", $strCompanyName, SQLVARCHAR);
mssql_bind($qryInsert, "@prmEMailAddress", $strEMailAddress, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPassword", $strPassword, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPasswordConfirm", $strPasswordConfirm, SQLVARCHAR);
mssql_bind($qryInsert, "@prmSecurityQuestionID", $intSecurityQuestionID, SQLINT4);
mssql_bind($qryInsert, "@prmSecurityAnswer", $strSecurityAnswer, SQLVARCHAR);
mssql_bind($qryInsert, "@prmAddress1", $strAddress1, SQLVARCHAR);
mssql_bind($qryInsert, "@prmAddress2", $strAddress2, SQLVARCHAR);
mssql_bind($qryInsert, "@prmCity", $strCity, SQLVARCHAR);
mssql_bind($qryInsert, "@prmState", $strState, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
mssql_bind($qryInsert, "@prmCountryCode", $strCountryCode, SQLVARCHAR);
mssql_bind($qryInsert, "@prmTelephone", $strTelephone, SQLVARCHAR);
mssql_bind($qryInsert, "@prmTelephoneExtension", $strTelephoneExtension, SQLVARCHAR);
mssql_bind($qryInsert, "@prmReferralSource", $strReferralSource, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPreferredCurrencyID", $intPreferredCurrencyID, SQLINT4);
mssql_bind($qryInsert, "@prmState", $strState, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
mssql_bind($qryInsert, "@prmCountryCode", $strCountryCode, SQLVARCHAR);
mssql_bind($qryInsert, "@prmTelephone", $strTelephone, SQLVARCHAR);
mssql_bind($qryInsert, "@prmTelephoneExtension", $strTelephoneExtension, SQLVARCHAR);
mssql_bind($qryInsert, "@prmReferralSource", $strReferralSource, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPreferredCurrencyID", $intPreferredCurrencyID, SQLINT4);
mssql_bind($qryInsert, "@prmCompanyType", $CompanyType, SQLVARCHAR);
mssql_bind($qryInsert, "@prmTaxIDNumber", $TaxIDNumber, SQLVARCHAR);
mssql_bind($qryInsert, "@prmResellerNumber", $ResellerNumber, SQLVARCHAR);
mssql_bind($qryInsert, "@prmCoreBusiness", $CoreBusiness, SQLVARCHAR);
mssql_bind($qryInsert, "@prmOtherProductsCarried", $OrderProductsCarried, SQLVARCHAR);
mssql_bind($qryInsert, "@prmWebsite", $Website, SQLVARCHAR);
mssql_bind($qryInsert, "@prmEstheticianLicense", $EstheticianLicense, SQLVARCHAR);
mssql_bind($qryInsert, "RETVAL", $intStatusCode, SQLINT4);


// EXECUTE QUERY
$rs = mssql_execute($qryInsert);

if ($intStatusCode == 98)
	$pageerror = 'The passwords you entered did not match. Please check for typos and try again.';
elseif ($intStatusCode == 99)
	$pageerror = 'There is already an account for this user. Please login using your existing password';
elseif ($intStatusCode != 100)
	$intCustomerID = $intStatusCode;

// CLOSE DATABASE CONNECTION
mssql_close($connCustomer);


if ($intStatusCode > 100)
{
	// CONNECT TO SQL SERVER DATABASE
	$connNavision = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db('Athena', $connNavision);
	
	// SPECIFY QUERY
	$qryNAVInsert = mssql_init("wsInsertWebCustomer", $connCustomer);
	
	// Bind the parameters
	mssql_bind($qryNAVInsert, "@prmCustomerID", $intCustomerID, SQLINT4);
	mssql_bind($qryNAVInsert, "@prmFirstName", $strFirstName, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmLastName", $strLastName, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmAddress1", $strAddress1, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmAddress2", $strAddress2, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmCity", $strCity, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmState", $strState, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmCountryCode", $strCountryCode, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmTelephone", $strTelephone, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmFax", $strFax, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmSalesRep", $SalesRepID, SQLINT4);
	mssql_bind($qryNAVInsert, "@prmEMailAddress", $strEMailAddress, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmWebsite", $Website, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmTitle", $strTitle, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmCompanyName", $strCompanyName, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmCompanyType", $intCustomerTypeID, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmCoreBusiness", $CoreBusiness, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmReferral", $strReferralSource, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmOtherProductsCarried", $OrderProductsCarried, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmEstheticianLicense", $EstheticianLicense, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmResellerPermit", $ResellerNumber, SQLVARCHAR);
	mssql_bind($qryNAVInsert, "@prmTaxIDNumber", $TaxIDNumber, SQLVARCHAR);
	
	$rsNAVInsert = mssql_execute($qryNAVInsert);
	
	$mailmessage = 'A new reseller application has been received through the website.<br>';
	$mailmessage .= 'Customer Number: ' . $intCustomerID . '<br>';
	$mailmessage .= 'Company Name: ' . $strCompanyName . '<br>';
	$mailmessage .= 'Contact: ' . $strFirstName . ' ' . $strLastName . '<br>';
	$mailmessage .= 'Telephone: ' . $strTelephone . '<br>';
	$mailmessage .= 'E-Mail: ' . $strEMailAddress . '<br>';
	$mailmessage .= 'Billing Address: ' . $strAddress1 . ' ' . $strCity . ' ' . $strState . ' ' . $strPostalCode . ' ' . $strCountryCode . '<br>';
	$mailmessage .= 'Company Type: ' . $CompanyType . '<br>';
	$mailmessage .= 'Core Business: ' . $CoreBusiness . '<br>';
	$mailmessage .= 'Lead Source: ' . $strReferralSource . '<br>';
	$mailmessage .= 'Other Products Carried: ' . $OrderProductsCarried . '<br>';
	
	$mailrecepient = 'customerservice@revitalash.com';
	$mailsubject = 'Revitalash Reseller Application';
	$mailheader = 'MIME-Version: 1.0' . "\r\n";
	$mailheader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$mailheader .= "From:" . 'sales@revitalash.com' . "\r\n";

    // GMC - 03/11/11 - Add CFelix and PBaldi at Reseller Registration
    // GMC - 12/05/11 - Change Gayle email address
    // GMC - 12/15/11 - Take Gayle email address all except sales confirmations
    // GMC - 03/13/12 - Take AOL-COM from email addresses
	// $mailheader .= 'Bcc: mcbrink@aol.com,gayleb@revitalash.com' . "\r\n";
	// $mailheader .= 'Bcc: mcbrink@aol.com, gayleb@revitalash.com, cfelix@revitalash.com, pbaldi@revitalash.com' . "\r\n";
	// $mailheader .= 'Bcc: mcbrink@aol.com, gaylebrinkenhoff@revitalash.com, cfelix@revitalash.com, pbaldi@revitalash.com' . "\r\n";
	// $mailheader .= 'Bcc: mcbrink@aol.com, cfelix@revitalash.com, pbaldi@revitalash.com' . "\r\n";
	$mailheader .= 'Bcc: cfelix@revitalash.com, pbaldi@revitalash.com' . "\r\n";

	mail($mailrecepient, $mailsubject, $mailmessage, $mailheader);

	// CLOSE DATABASE CONNECTION
	mssql_close($connNavision);
}

?>
