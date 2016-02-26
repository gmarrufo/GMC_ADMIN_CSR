<?php

// CONNECT TO SQL SERVER DATABASE
$connCountryCodes = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connCountryCodes);


// QUERY DATABASE FOR COUNTRY CODES DEPENDING ON AVAILABLE VARIABLES
if (isset($_GET['Profile']))
{
	$qryGetCountryCodes = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 ORDER BY SortOrder ASC, CountryName ASC");
	$qryGetCurrent = mssql_query("SELECT CountryCode FROM tblCustomers WHERE RecordID = " . $_SESSION['CustomerID']);
	
	while($row = mssql_fetch_array($qryGetCurrent))
	{
		$CountryCode = $row["CountryCode"];
	}
}
elseif (isset($_GET['ShipTo']))
{
	$qryGetCountryCodes = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 ORDER BY SortOrder ASC, CountryName ASC");
	$qryGetCurrent = mssql_query("SELECT CountryCode FROM tblCustomers_ShipTo WHERE RecordID = " . mssql_escape_string($_GET['ShipTo']));
	while($row = mssql_fetch_array($qryGetCurrent))
	{
		$CountryCode = $row["CountryCode"];
	}
}
elseif (isset($_SESSION['CustomerID']))
{
	$qryGetCountryCodes = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 ORDER BY SortOrder ASC, CountryName ASC");
	$qryGetCurrent = mssql_query("SELECT CountryCode FROM tblCustomers WHERE RecordID = " . $_SESSION['CustomerID']);
	while($row = mssql_fetch_array($qryGetCurrent))
	{
		$CountryCode = $row["CountryCode"];
	}
}
elseif (isset($_GET['CustomerID']))
{
	$qryGetCountryCodes = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 ORDER BY SortOrder ASC, CountryName ASC");
	$qryGetCurrent = mssql_query("SELECT CountryCode FROM tblCustomers WHERE RecordID = " . mssql_escape_string($_GET['CustomerID']));
	while($row = mssql_fetch_array($qryGetCurrent))
	{
		$CountryCode = $row["CountryCode"];
	}
}
elseif (isset($SelectedCountryCode))
{
	$qryGetCountryCodes = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 ORDER BY SortOrder ASC, CountryName ASC");
	$CountryCode = $SelectedCountryCode;
}
// CLOSE DATABASE CONNECTION
mssql_close($connCountryCodes);



$selectCountries = '<option value="US">Select Country</option>';

while($row = mssql_fetch_array($qryGetCountryCodes))
{
  $selectCountries .= '<option value="' . $row["CountryCode"] . '"';
  if ($CountryCode == $row["CountryCode"])
		$selectCountries .= ' selected="selected"';
  $selectCountries .= '>' . $row["CountryName"] . '</option>';
}

?>