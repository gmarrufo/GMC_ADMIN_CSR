<?php 

// CONNECT TO SQL SERVER DATABASE
$connUpdate = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connUpdate);
	
$qryUpdate = mssql_init("spCustomers_ProEditShipping", $connUpdate);

// GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
$strCompany = $_POST['Company'];

$strAddressType = $_POST['AddressType'];
$strAttn = $_POST['Attn'];
$strAddress1 = $_POST['Address1'];
$strAddress2 = $_POST['Address2'];
$strCity = $_POST['City'];
$strState = $_POST['State'];
$strPostalCode = $_POST['PostalCode'];
$strCountryCode = $_POST['CountryCode'];

$intStatusCode = 0;

// Bind the parameters
mssql_bind($qryUpdate, "@prmShipToID", $_GET['ShipTo'], SQLINT4);
mssql_bind($qryUpdate, "@prmAddressType", $strAddressType, SQLVARCHAR);

// GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
mssql_bind($qryUpdate, "@prmCompany", $strCompany, SQLVARCHAR);

mssql_bind($qryUpdate, "@prmAttn", $strAttn, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmAddress1", $strAddress1, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmAddress2", $strAddress2, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmCity", $strCity, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmState", $strState, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmCountryCode", $strCountryCode, SQLVARCHAR);


// Bind the return value

mssql_bind($qryUpdate, "RETVAL", $intStatusCode, SQLINT2);


// EXECUTE QUERY
$rs = mssql_execute($qryUpdate);

$confirmation = 'Your shipping address was updated successfully. Click here to <a href="/pro/account.php">return to your account</a>.';
?>
