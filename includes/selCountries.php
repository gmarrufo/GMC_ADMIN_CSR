<?php

// CONNECT TO SQL SERVER DATABASE
// $conn = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
// $selected = mssql_select_db($dbName, $conn) or die("Couldn't open database $dbName");

$connectionInfo1 = array( "Database"=>$dbName, "UID"=>$dbUser, "PWD"=>$dbPass);
$conn1 = sqlsrv_connect( $serverName, $connectionInfo1);

if( $conn1 ) {
	 // echo "Connection established.<br />";
}else{
	 echo "Connection could not be established.<br />";
	 die( print_r( sqlsrv_errors(), true));
}	

// GMC - 08/16/12 - Split US Other Countries at Shipping Selection Shopping Cart Consumer
if($_SESSION['Country_Customer'] == "US")
{
    $selectCountries = '<option value="">Select Country</option>';

    // $result = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 AND CountryCode = 'US' ORDER BY SortOrder ASC, CountryName ASC");
    $result = sqlsrv_query($conn1, "SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 AND CountryCode = 'US' ORDER BY SortOrder ASC, CountryName ASC");

    // while($row = mssql_fetch_array($result))
    while($row = sqlsrv_fetch_array($result))
    {
        $selectCountries .= '<option value="' . $row["CountryCode"] . '">' . $row["CountryName"] . '</option>';
    }
}
else
{
    $selectCountries = '<option value="">Select Country</option>';

    // $result = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 AND CountryCode <> 'US' ORDER BY SortOrder ASC, CountryName ASC");
    $result = sqlsrv_query($conn1, "SELECT CountryCode, CountryName FROM conCountryCodes WHERE IsActive = 1 AND CountryCode <> 'US' ORDER BY SortOrder ASC, CountryName ASC");

    // while($row = mssql_fetch_array($result))
    while($row = sqlsrv_fetch_array($result))
    {
        $selectCountries .= '<option value="' . $row["CountryCode"] . '">' . $row["CountryName"] . '</option>';
    }
}

// CLOSE DATABASE CONNECTION
// mssql_close($conn);
sqlsrv_close($conn1);

?>
