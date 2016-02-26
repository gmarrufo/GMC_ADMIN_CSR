<?php

// CONNECT TO SQL SERVER DATABASE
$conn = mssql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
$selected = mssql_select_db($dbName, $conn)
  	or die("Couldn't open database $dbName");

// GMC - 08/16/12 - Split US Other Countries at Shipping Selection Shopping Cart Consumer
if($_SESSION['Country_Customer'] == "US")
{
    $selectCountries = '<option value="">Select Country</option>';

    $result = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 AND CountryCode = 'US' ORDER BY SortOrder ASC, CountryName ASC");

    while($row = mssql_fetch_array($result))
    {
        $selectCountries .= '<option value="' . $row["CountryCode"] . '">' . $row["CountryName"] . '</option>';
    }
}
else
{
    $selectCountries = '<option value="">Select Country</option>';

    $result = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 AND CountryCode <> 'US' ORDER BY SortOrder ASC, CountryName ASC");

    while($row = mssql_fetch_array($result))
    {
        $selectCountries .= '<option value="' . $row["CountryCode"] . '">' . $row["CountryName"] . '</option>';
    }
}

// CLOSE DATABASE CONNECTION
mssql_close($conn);

?>
