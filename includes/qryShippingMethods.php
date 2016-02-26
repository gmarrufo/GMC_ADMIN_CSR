<?php

// CONNECT TO SQL SERVER DATABASE
$connshipmethod = mssql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
mssql_select_db($dbName, $connshipmethod);

// EXECUTE QUERY
$qryGetShippingMethods = mssql_init("spConstants_ActiveShippingMethods", $connshipmethod);
mssql_bind($qryGetShippingMethods, "@prmCustomerShipToID", $_SESSION['CustomerShipToID'], SQLINT4);
$rs = mssql_execute($qryGetShippingMethods);

// CLOSE DATABASE CONNECTION
mssql_close($connshipmethod);

?>