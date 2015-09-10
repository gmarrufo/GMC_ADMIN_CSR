<?php

// GMC - 07/23/11 - Reinsert the I would like to place my order without creating an account option plus Email Monthly Updates
if($_SESSION['Monthly_News'] == 'Yes')
{
    // ATTEMPT CONNECTION TO DATABASE SERVER
    $conn = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");

    // SET CONNECTION TO REVITALASH DB
    mssql_select_db($dbName, $conn);

    // EXECUTE SQL QUERY
    $result = mssql_query("SELECT * FROM tblCustomers WHERE RecordID = " . $_SESSION['CustomerID']);
    while($row = mssql_fetch_array($result))
    {
        $strFirstName = $row['FirstName'];
        $strLastName = $row['LastName'];
        $strEMailAddress = $row['EMailAddress'];
    }

    // CLOSE DATABASE CONNECTION
    mssql_close($conn);

    // CONNECT TO SQL SERVER DATABASE
    $connMonthlyNewsLetter = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
    $selected = mssql_select_db($dbName, $connMonthlyNewsLetter);

    // SPECIFY QUERY
    $qryNewsInsert = mssql_init("spCustomers_AddMonthlyNews", $connMonthlyNewsLetter);

    // Bind the parameters
    mssql_bind($qryNewsInsert, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);
    mssql_bind($qryNewsInsert, "@prmFirstName", $strFirstName, SQLVARCHAR);
    mssql_bind($qryNewsInsert, "@prmLastName", $strLastName, SQLVARCHAR);
    mssql_bind($qryNewsInsert, "@prmEMailAddress", $strEMailAddress, SQLVARCHAR);

    $rsMonthlyNews = mssql_execute($qryNewsInsert);

    // CLOSE DATABASE CONNECTION
    mssql_close($connMonthlyNewsLetter);
}

?>
