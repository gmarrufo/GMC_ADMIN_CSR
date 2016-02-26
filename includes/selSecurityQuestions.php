<?php

// CONNECT TO SQL SERVER DATABASE
$connSecurity = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected2 = mssql_select_db($dbName, $connSecurity);

// QUERY DATABASE FOR COUNTRY CODES
$result = mssql_query("SELECT RecordID, QuestionDisplay FROM conSecurityQuestions");

// CLOSE DATABASE CONNECTION
mssql_close($connSecurity);

$selectSecurityQuestions = '<option value="0">-SELECT BELOW-</option>';

while($row = mssql_fetch_array($result))
{
  $selectSecurityQuestions .= '<option value="' . $row["RecordID"] . '">' . $row["QuestionDisplay"] . '</option>';
}

?>