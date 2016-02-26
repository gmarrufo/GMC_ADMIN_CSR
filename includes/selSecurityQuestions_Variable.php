<?php

// CONNECT TO SQL SERVER DATABASE
$connSecurityQuestions = mssql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
$selected = mssql_select_db($dbName, $connSecurityQuestions);

// QUERY DATABASE FOR COUNTRY CODES
$qryGetSecurityQuestions = mssql_query("SELECT RecordID, QuestionDisplay FROM conSecurityQuestions");
if (isset($_SESSION['CustomerID']))
	$qryGetCurrent = mssql_query("SELECT SecurityQuestionID FROM tblCustomers WHERE RecordID = " . $_SESSION['CustomerID']);
if (isset($_GET['CustomerID']))
	$qryGetCurrent = mssql_query("SELECT SecurityQuestionID FROM tblCustomers WHERE RecordID = " . mssql_escape_string($_GET['CustomerID']));

// CLOSE DATABASE CONNECTION
mssql_close($connSecurityQuestions);


while($row = mssql_fetch_array($qryGetCurrent))
{
 	$SecurityQuestionID = $row["SecurityQuestionID"];
}

$selectSecurityQuestions = '<option value="0">-SELECT BELOW-</option>';

while($row = mssql_fetch_array($qryGetSecurityQuestions))
{
	$selectSecurityQuestions .= '<option value="' . $row["RecordID"] . '"';
	if ($SecurityQuestionID == $row["RecordID"])
		$selectSecurityQuestions .= ' selected="selected"';
	$selectSecurityQuestions .= '>' . $row["QuestionDisplay"] . '</option>';
}
?>