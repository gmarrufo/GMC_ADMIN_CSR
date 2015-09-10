<?php 

// CONNECT TO SQL SERVER DATABASE
$connUpdate = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connUpdate);
	
$qryUpdate = mssql_init("spCustomers_ProEditPayment", $connUpdate);

$strCardholder = $_POST['Cardholder'];
$strCCExpiration = $_POST['ExpMonth'] . $_POST['ExpYear'];
$strBillingPostalCode = $_POST['BillingPostalCode'];

$intStatusCode = 0;

// Bind the parameters
mssql_bind($qryUpdate, "@prmPaymentID", $_GET['Payment'], SQLINT4);
mssql_bind($qryUpdate, "@prmCardholder", $strCardholder, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmCCExpiration", $strCCExpiration, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmBillingPostalCode", $strBillingPostalCode, SQLVARCHAR);


// Bind the return value

mssql_bind($qryUpdate, "RETVAL", $intStatusCode, SQLINT2);


// EXECUTE QUERY
$rs = mssql_execute($qryUpdate);

$confirmation = 'Your payment method was updated successfully. Click here to <a href="/pro/account.php">return to your account</a>.';
?>