<?php

// KILL SESSION VARIABLES IF USER WANTS TO LOGOUT
if (isset($_GET['logout']))
	session_unset();

// CONNECT TO SQL SERVER DATABASE
$connExistingShip = mssql_connect($dbServer, $dbUser, $dbPass)
  or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE, EXECUTE QUERY AND RETURN RESULTS
$selected = mssql_select_db($dbName, $connExistingShip);

// GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
// $rsShipTo = mssql_query("SELECT RecordID, Attn, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND CustomerID = " . $_SESSION['CustomerID'] . " ORDER BY Attn ASC");
$rsShipTo = mssql_query("SELECT RecordID, CompanyName, Attn, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND CustomerID = " . $_SESSION['CustomerID'] . " ORDER BY Attn ASC");

$strCBO = '';

while($row = mssql_fetch_array($rsShipTo))
{
  // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
  // $strCBO .= '<option value="' . $row["RecordID"] . '">' . $row["Attn"] . ' - ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . ' ' . $row["CountryCode"] . '</option>';
  $strCBO .= '<option value="' . $row["RecordID"] . '">' .  $row["CompanyName"] . ' - ' . $row["Attn"] . ' - ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . ' ' . $row["CountryCode"] . '</option>';
}

//$numRows = mssql_num_rows($result);

// CLOSE DATABASE CONNECTION
mssql_close($connExistingShip);
?> 
