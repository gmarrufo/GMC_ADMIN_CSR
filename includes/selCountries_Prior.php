<?php

// CONNECT TO SQL SERVER DATABASE
$conn = mssql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
$selected = mssql_select_db($dbName, $conn)
  	or die("Couldn't open database $dbName");

// QUERY DATABASE FOR COUNTRY CODES
$result = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 ORDER BY SortOrder ASC, CountryName ASC");

// CLOSE DATABASE CONNECTION
mssql_close($conn);

$selectCountries = '<option value="US">Select Country</option>';

while($row = mssql_fetch_array($result))
{
  $selectCountries .= '<option value="' . $row["CountryCode"] . '">' . $row["CountryName"] . '</option>';
}

?>