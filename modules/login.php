<?php 

// CONNECT TO SQL SERVER DATABASE
$connLogin = mssql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
$selected = mssql_select_db($dbName, $connLogin);
	
$qryLogin = mssql_init("spCustomers_Login", $connLogin);

$strUsername = $_POST['EMailAddress'];
$hshPassword = md5($_POST['Password']);
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
		$_SESSION['CustomerIsLoggedIn'] = 1;
		$_SESSION['CustomerTypeID'] = 1;
		$_SESSION['CustomerID'] = $row["RecordID"];
		$_SESSION['FirstName'] = $row["FirstName"];
		$_SESSION['LastName'] = $row["LastName"];
		$_SESSION['EMailAddress'] = $row["EMailAddress"];

        // GMC - 02/21/10 - Add New Shopping Cart Flow
        $_SESSION['Country_Customer'] = $row["CountryCode"];

        // GMC - 01/17/13 - Fix Bug on State Exclusion when NewCustomer or OneTimeRegistration and Returning
        $_SESSION['StateExclusion'] =  $row["State"];

        // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
        $_SESSION['CountryExclusion'] = $row["CountryCode"];
	}
	
	mssql_next_result($rs);

}
/*
do {
   		while ($row = mssql_fetch_row($rs))
		{
       		echo "$row[0] -- $row[1]<BR>";
    	}
    } while (mssql_next_result($rs));
*/

// GMC - 10/12/11 - Error Help for Customers when Login
$_SESSION['Error_Help'] = $intStatusCode;

if ($intStatusCode == 97)
	$pageerror = 'The username you specified was not found in our database. Please check for typos and try again.';
elseif ($intStatusCode == 98)
	$pageerror = 'The username you specified was found in our database but has been deactivated. Please call for assistance.';
elseif ($intStatusCode == 99)
	$pageerror = 'The password you entered did not match the password on file for this account. Please check for typos and try again.';
elseif ($intStatusCode != 100)
	$pageerror = 'An unknown error occured during your login attempt. Please call for assistance. Error Code ' . $intStatusCode;
	
	if (isset($pageerror)) echo $pageerror;

// CLOSE DATABASE CONNECTION
mssql_close($connLogin);
?>
