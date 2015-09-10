<?php 

if ($_POST['ShippingAddress'] == 'UseExisting')
{
	$_SESSION['IsShippingSet'] = 1;
	$_SESSION['CustomerShipToID'] = $_POST['ShipToID'];
}
elseif ($_POST['ShippingAddress'] == 'New')
{
	$strAttn = $_POST['FirstName'] . ' ' . $_POST['LastName'];
	$strAddress1 = $_POST['Address1'];
	$strAddress2 = $_POST['Address2'];
	$strCity = $_POST['City'];
	$strState = $_POST['State'];
	$strPostalCode = $_POST['PostalCode'];
	$strCountryCode = $_POST['CountryCode'];


	// CONNECT TO SQL SERVER DATABASE
	$connNewAddress = mssql_connect($dbServer, $dbUser, $dbPass)
		or die("Couldn't connect to SQL Server on $dbServer");
	
	// OPEN REVITALASH DATABASE
	$selected = mssql_select_db($dbName, $connNewAddress);

	//INITIALIZE SPROC
	$qrySetShipping = mssql_init("spOrders_SetShipping", $connNewAddress);
	$intStatusCode = 0;
	
	//BIND INPUT PARAMETERS
	mssql_bind($qrySetShipping, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);
	mssql_bind($qrySetShipping, "@prmAttn", $strAttn, SQLVARCHAR);
	mssql_bind($qrySetShipping, "@prmAddress1", $strAddress1, SQLVARCHAR);
	mssql_bind($qrySetShipping, "@prmAddress2", $strAddress2, SQLVARCHAR);
	mssql_bind($qrySetShipping, "@prmCity", $strCity, SQLVARCHAR);
	mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR);
	mssql_bind($qrySetShipping, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
	mssql_bind($qrySetShipping, "@prmCountryCode", $strCountryCode, SQLVARCHAR);
	
	$rsGetAddress = mssql_execute($qrySetShipping);
	
	if (mssql_num_rows($rsGetAddress) > 0)
	{
		while($row = mssql_fetch_array($rsGetAddress))
		{
			$_SESSION['IsShippingSet'] = 1;
			$_SESSION['CustomerShipToID'] = $row["RecordID"];
		}
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connNewAddress);
}


?>