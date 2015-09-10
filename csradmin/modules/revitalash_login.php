<?php

// CONNECT TO SQL SERVER DATABASE
$connLogin = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
$selected = mssql_select_db($dbName, $connLogin);
	
$qryLogin = mssql_init("spRevitalash_Login", $connLogin);

$strUsername = $_POST['username'];
$hshPassword = md5($_POST['password']);
$intStatusCode = 0;

// Bind the parameters
mssql_bind($qryLogin, "@prmUsername", $strUsername, SQLVARCHAR);
mssql_bind($qryLogin, "@prmPassword", $hshPassword, SQLVARCHAR);

// Bind the return value

mssql_bind($qryLogin, "RETVAL", $intStatusCode, SQLINT2);

// EXECUTE QUERY
$rs = mssql_execute($qryLogin);

if (mssql_num_rows($rs) > 0)
{
	while($row = mssql_fetch_array($rs))
	{
		$_SESSION['IsRevitalashLoggedIn'] = 1;
		$_SESSION['UserID'] = $row["RecordID"];
		$_SESSION['FirstName'] = $row["FirstName"];
		$_SESSION['LastName'] = $row["LastName"];
		$_SESSION['EMailAddress'] = $row["EMailAddress"];
		$_SESSION['UserTypeID'] = $row["UserTypeID"];
	}
	
	mssql_next_result($rs);
}

if ($intStatusCode == 97)
	$pageerror = 'The username you specified was not found in our database. Please check for typos and try again.';
elseif ($intStatusCode == 98)
	$pageerror = 'The username you specified was found in our database but has been deactivated. Please call for assistance.';
elseif ($intStatusCode == 99)
	$pageerror = 'The password you entered did not match the password on file for this account. Please check for typos and try again.';
elseif ($intStatusCode != 100)
	$pageerror = 'An unknown error occured during your login attempt. Please call for assistance. Error Code ' . $intStatusCode;

// CLOSE DATABASE CONNECTION
mssql_close($connLogin);
?>
