<?php 

// CONNECT TO SQL SERVER DATABASE
$conn = mssql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
$selected = mssql_select_db($dbName, $conn)
  	or die("Couldn't open database $dbName");
	
$qryLogin = mssql_init("spCustomers_ProLogin", $conn);

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
		$_SESSION['IsProLoggedIn'] = 1;
		$_SESSION['CustomerTypeID'] = $row["CustomerTypeID"];
		$_SESSION['CustomerID'] = $row["RecordID"];
		$_SESSION['FirstName'] = $row["FirstName"];
		$_SESSION['LastName'] = $row["LastName"];
		$_SESSION['CompanyName'] = $row["CompanyName"];
		$_SESSION['EMailAddress'] = $row["EMailAddress"];
		$_SESSION['CustomerTerms'] = $row["IsApprovedTerms"];

        // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
        $_SESSION['PRO_COUNTRY_EXCLUDED'] =  $row["CountryCode"];

		if ($row["CountryCode"] == 'US')
        {
			$_SESSION['IsInternational'] = 0;
   
            // GMC - 11/30/11 - Block Australia from Ordering
             $_SESSION['IsFromAU'] = 'False';
        }
        else
        {
			$_SESSION['IsInternational'] = 1;
   
            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if ($row["CountryCode"] == 'CA')
            {
			    $_SESSION['IsFromCA'] = 'True';
            }
            else
            {
			    $_SESSION['IsFromCA'] = 'False';
            }
            
            // GMC - 11/30/11 - Block Australia from Ordering
            if ($row["CountryCode"] == 'AU')
            {
			    $_SESSION['IsFromAU'] = 'True';
            }
            else
            {
			    $_SESSION['IsFromAU'] = 'False';
            }

            // GMC - 05/07/12 - Stop Shippings to Spain - Reseller - Web
            if ($row["CountryCode"] == 'ES')
            {
			    $_SESSION['IsFromAU'] = 'ES';
            }
        }
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

?>
