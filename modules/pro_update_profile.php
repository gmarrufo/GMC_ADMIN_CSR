<?php 

// CONNECT TO SQL SERVER DATABASE

$connUpdate = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connUpdate);

$qryUpdate = mssql_init("spCustomers_ProEdit", $connUpdate);

$strFirstName = $_POST['FirstName'];
$strLastName = $_POST['LastName'];
$strCompanyName = $_POST['CompanyName'];
$strEMailAddress = $_POST['EMailAddress'];

if (($_POST['Password'] != '') && ($_POST['PasswordConfirm'] != ''))
{
	$strPassword = md5($_POST['Password']);
	$strPasswordConfirm = md5($_POST['PasswordConfirm']);
}
else
{
	$strPassword = '';
	$strPasswordConfirm = '';
}

$intSecurityQuestionID = $_POST['SecurityQuestionID'];
$strSecurityAnswer = $_POST['SecurityAnswer'];
$strAddress1 = $_POST['Address1'];
$strAddress2 = $_POST['Address2'];
$strCity = $_POST['City'];
$strState = $_POST['State'];
$strPostalCode = $_POST['PostalCode'];
$strCountryCode = $_POST['CountryCode'];
$strTelephone = preg_replace('/[^0-9]/', '', $_POST['Telephone']);
$strTelephoneExtension = preg_replace('/[^0-9]/', '', $_POST['TelephoneExtension']);

$intStatusCode = 0;

// Bind the parameters

// GMC - 02/20/09 - WhiteStar Bug Found $mssql_bind (What a .......)
if (isset($_SESSION['CustomerID']))
	mssql_bind($qryUpdate, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);
elseif (isset($_GET['CustomerID']))
	mssql_bind($qryUpdate, "@prmCustomerID", $_GET['CustomerID'], SQLINT4);

mssql_bind($qryUpdate, "@prmFirstName", $strFirstName, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmLastName", $strLastName, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmCompanyName", $strCompanyName, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmEMailAddress", $strEMailAddress, SQLVARCHAR);

if (($_POST['Password'] != '') && ($_POST['PasswordConfirm'] != ''))
{
	mssql_bind($qryUpdate, "@prmPassword", $strPassword, SQLVARCHAR);
	mssql_bind($qryUpdate, "@prmPasswordConfirm", $strPasswordConfirm, SQLVARCHAR);
}
else
{
	mssql_bind($qryUpdate, "@prmPassword", $strPassword, SQLVARCHAR, false, true);
	mssql_bind($qryUpdate, "@prmPasswordConfirm", $strPasswordConfirm, SQLVARCHAR, false, true);
}

mssql_bind($qryUpdate, "@prmSecurityQuestionID", $intSecurityQuestionID, SQLINT4);
mssql_bind($qryUpdate, "@prmSecurityAnswer", $strSecurityAnswer, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmAddress1", $strAddress1, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmAddress2", $strAddress2, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmCity", $strCity, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmState", $strState, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmCountryCode", $strCountryCode, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmTelephone", $strTelephone, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmTelephoneExtension", $strTelephoneExtension, SQLVARCHAR);

// Bind the return value

mssql_bind($qryUpdate, "RETVAL", $intStatusCode, SQLINT2);

// EXECUTE QUERY
$rs = mssql_execute($qryUpdate);

if ($intStatusCode == 98)
	$pageerror = 'The passwords you entered did not match. Please check for typos and try again.';
elseif ($intStatusCode == 99)
	$pageerror = 'There is already an account for this user. Please login using your existing password';
elseif ($intStatusCode != 100)
	$pageerror = 'An unknown error occured during your login attempt. Please call for assistance. Error Code ' . $intStatusCode;
else
	$confirmation = 'Your profile was updated successfully. Click here to <a href="/pro/account.php">return to your account</a>.';

?>
