<?php

// CONNECT TO SQL SERVER DATABASE
$connSalesRep = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected3 = mssql_select_db($dbName, $connSalesRep);

// QUERY DATABASE FOR COUNTRY CODES
$result = mssql_query("SELECT RecordID, FirstName, LastName FROM tblRevitalash_Users WHERE IsActive = 1 AND UserTypeID = 1 ORDER BY FirstName ASC, LastName ASC");

// CLOSE DATABASE CONNECTION
mssql_close($connSalesRep);

$selectSalesRep = '<option value="1">-SELECT BELOW-</option><option value="1">No</option>';

while($row = mssql_fetch_array($result))
{
  $selectSalesRep .= '<option value="' . $row["RecordID"] . '">' . $row["FirstName"] . ' ' . $row["LastName"] . '</option>';
}

?>