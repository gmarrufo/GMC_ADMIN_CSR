<?php 

// CONNECT TO SQL SERVER DATABASE
$connShipping = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connShipping);
	
$qryInsert = mssql_init("spCustomers_AddNew", $connShipping);

$intCustomerTypeID = 1;

// GMC - 05/06/10 - Proper RecordIDs for Consumer-Reseller (Domestic and International)
// $SalesRepID = 1;

$strFirstName = $_POST['FirstName'];
$strLastName = $_POST['LastName'];

// GMC - 06/16/10 - No Individual Word for Consumers by JS
// GMC - 06/17/10 - Now they want First Name + Last Name for Consumers by JS
// $strCompanyName = 'Individual';
$strCompanyName = $strFirstName . ' ' . $strLastName;

$strEMailAddress = $_POST['EMailAddress'];
$strPassword = md5($_POST['Password']);
$strPasswordConfirm = md5($_POST['PasswordConfirm']);
$intSecurityQuestionID = $_POST['SecurityQuestionID'];
$strSecurityAnswer = $_POST['SecurityAnswer'];
$strAddress1 = $_POST['Address1'];
$strAddress2 = $_POST['Address2'];
$strCity = $_POST['City'];
$strState = $_POST['State'];

// GMC - 01/17/13 - Fix Bug on State Exclusion when NewCustomer or OneTimeRegistration and Returning
$_SESSION['StateExclusion'] = $strState;

$strPostalCode = $_POST['PostalCode'];
$strCountryCode = $_POST['CountryCode'];

// GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
$_SESSION['CountryExclusion'] = $strCountryCode;

// GMC - 07/09/09 - Formatting US Phones
if($strCountryCode == 'US')
{
    $sPhone = preg_replace("[^0-9]",'',$_POST['Telephone']);
    if(strlen($sPhone) != 10)
    {
        $strTelephone = preg_replace('/[^0-9]/', '', $_POST['Telephone']);
    }
    else
    {
        $sArea = substr($sPhone,0,3);
        $sPrefix = substr($sPhone,3,3);
        $sNumber = substr($sPhone,6,4);
        $strTelephone = "".$sArea."-".$sPrefix."-".$sNumber;
    }

    // GMC - 05/06/10 - Proper RecordIDs for Consumer-Reseller (Domestic and International)
    $SalesRepID = 1;
}
else
{
    $strTelephone = preg_replace('/[^0-9]/', '', $_POST['Telephone']);

    // GMC - 05/06/10 - Proper RecordIDs for Consumer-Reseller (Domestic and International)
    $SalesRepID = 125;
}

$strTelephoneExtension = preg_replace('/[^0-9]/', '', $_POST['TelephoneExtension']);
$strFax = '';
$strReferralSource = 'Undefined';
$intPreferredCurrencyID = 1;
$CompanyType = 1;
$TaxIDNumber = 'N/A';
$ResellerNumber = 'N/A';
$CoreBusiness = 'N/A';
$OtherProductsCarried = 'N/A';
$Website = 'N/A';
$EstheticianLicense = 'N/A';

$intStatusCode = 0;

// Bind the parameters
mssql_bind($qryInsert, "@prmCustomerTypeID", $intCustomerTypeID, SQLINT4);
mssql_bind($qryInsert, "@prmFirstName", $strFirstName, SQLVARCHAR);
mssql_bind($qryInsert, "@prmLastName", $strLastName, SQLVARCHAR);
mssql_bind($qryInsert, "@prmCompanyName", $strCompanyName, SQLVARCHAR);
mssql_bind($qryInsert, "@prmEMailAddress", $strEMailAddress, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPassword", $strPassword, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPasswordConfirm", $strPasswordConfirm, SQLVARCHAR);
mssql_bind($qryInsert, "@prmSecurityQuestionID", $intSecurityQuestionID, SQLINT4);
mssql_bind($qryInsert, "@prmSecurityAnswer", $strSecurityAnswer, SQLVARCHAR);
mssql_bind($qryInsert, "@prmAddress1", $strAddress1, SQLVARCHAR);
mssql_bind($qryInsert, "@prmAddress2", $strAddress2, SQLVARCHAR);
mssql_bind($qryInsert, "@prmCity", $strCity, SQLVARCHAR);
mssql_bind($qryInsert, "@prmState", $strState, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
mssql_bind($qryInsert, "@prmCountryCode", $strCountryCode, SQLVARCHAR);
mssql_bind($qryInsert, "@prmTelephone", $strTelephone, SQLVARCHAR);
mssql_bind($qryInsert, "@prmTelephoneExtension", $strTelephoneExtension, SQLVARCHAR);
mssql_bind($qryInsert, "@prmReferralSource", $strReferralSource, SQLVARCHAR);
mssql_bind($qryInsert, "@prmPreferredCurrencyID", $intPreferredCurrencyID, SQLINT4);

// GMC - 05/06/10 - Proper RecordIDs for Consumer-Reseller (Domestic and International)
mssql_bind($qryInsert, "@prmSalesRepId", $SalesRepID, SQLINT4);

// Bind the return value
mssql_bind($qryInsert, "RETVAL", $intStatusCode, SQLINT2);

// EXECUTE QUERY
$rs = mssql_execute($qryInsert);

if (mssql_num_rows($rs) > 0)
{
	while($row = mssql_fetch_array($rs))
	{
		$_SESSION['CustomerIsLoggedIn'] = 1;
		$_SESSION['CustomerTypeID'] = 1;
		$_SESSION['CustomerID'] = $row["RecordID"];
		$intCustomerID = $row["RecordID"];
		$_SESSION['FirstName'] = $row["FirstName"];
		$_SESSION['LastName'] = $row["LastName"];
		$_SESSION['EMailAddress'] = $row["EMailAddress"];
	}
	
	mssql_next_result($rs);
}

if ($intStatusCode == 98)
	$pageerror = 'The passwords you entered did not match. Please check for typos and try again.';
elseif ($intStatusCode == 99)
	$pageerror = 'There is already an account for this user. Please login using your existing password';
elseif ($intStatusCode != 100)
	$pageerror = 'An unknown error occured during your registration attempt. Please call for assistance. Error Code ' . $intStatusCode;
else
{
    // GMC - 05/07/10 - To prevent duplicate address in tblCustomers_ShipTo
    if ($_POST['AddressAction'] == 'SameAsBilling')
	{
		//INITIALIZE SPROC
		$qryGetAddress = mssql_init("spCustomers_GetAddress", $connShipping);
		$intStatusCode = 0;
	
		// BIND INPUT PARAMETERS
		mssql_bind($qryGetAddress, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);
	
		// EXECUTE QUERY
		$rs = mssql_execute($qryGetAddress);
	
		if (mssql_num_rows($rs) > 0)
		{
			while($row = mssql_fetch_array($rs))
			{
                // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
				$strCompanyName = $row["CompanyName"];

				$strAttn = $row["FirstName"] . ' ' . $row["LastName"];
				$strAddress1 = $row["Address1"];
				$strAddress2 = $row["Address2"];
				$strCity = $row["City"];
				$strState = $row["State"];
				$strPostalCode = $row["PostalCode"];
				$strCountryCode = $row["CountryCode"];
			}
			
		    // GMC - 05/06/09 - FedEx Netherlands
		    $_SESSION['CountryCodeFedExEu_Retail'] =  $strCountryCode;

            // GMC - 01/17/13 - Fix Bug on State Exclusion when NewCustomer or OneTimeRegistration and Returning
            $_SESSION['StateExclusion'] = $strState;

            // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
            $_SESSION['CountryExclusion'] = $strCountryCode;
		}

	    //INITIALIZE SPROC
     	$qrySetShipping = mssql_init("spOrders_SetShipping", $connShipping);
     	$intStatusCode = 0;

     	//BIND INPUT PARAMETERS
     	mssql_bind($qrySetShipping, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);

        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
     	mssql_bind($qrySetShipping, "@prmCompanyName", $strCompanyName, SQLVARCHAR);

     	mssql_bind($qrySetShipping, "@prmAttn", $strAttn, SQLVARCHAR);
     	mssql_bind($qrySetShipping, "@prmAddress1", $strAddress1, SQLVARCHAR);
     	mssql_bind($qrySetShipping, "@prmAddress2", $strAddress2, SQLVARCHAR);
     	mssql_bind($qrySetShipping, "@prmCity", $strCity, SQLVARCHAR);
     	if ($strState != '')
     	{
     		mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR);
     	}
     	else
     	{
     		mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR, false, true);
     	}
     	mssql_bind($qrySetShipping, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
     	mssql_bind($qrySetShipping, "@prmCountryCode", $strCountryCode, SQLVARCHAR);

     	$rs2 = mssql_execute($qrySetShipping);

     	if (mssql_num_rows($rs2) > 0)
     	{
     		while($row2 = mssql_fetch_array($rs2))
     		{
     			$_SESSION['IsShippingSet'] = 1;
     			$_SESSION['CustomerShipToID'] = $row2["RecordID"];
     		}
     	}
	}
	elseif ($_POST['AddressAction'] == 'CreateNew')
	{
        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
		$strCompanyName = $_POST['ShipToAttn'];

		$strAttn = $_POST['ShipToAttn'];
		$strAddress1 = $_POST['ShipToAddress1'];
		$strAddress2 = $_POST['ShipToAddress2'];
		$strCity = $_POST['ShipToCity'];
		$strState = $_POST['ShipToState'];

        // GMC - 01/17/13 - Fix Bug on State Exclusion when NewCustomer or OneTimeRegistration and Returning
        $_SESSION['StateExclusion'] = $strState;

		$strPostalCode = $_POST['ShipToPostalCode'];
		$strCountryCode = $_POST['ShipToCountryCode'];

        // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
        $_SESSION['CountryExclusion'] = $strCountryCode;

		// GMC - 05/06/09 - FedEx Netherlands
		$_SESSION['CountryCodeFedExEu_Retail'] =  $strCountryCode;

        // GMC - 02/13/12 - Check for CA - AU in ShiptoCountryCode
        // GMC - 05/07/12 - Stop Shippings to Spain - Consumer - Web
        if($strCountryCode == 'CA' || $strCountryCode == 'AU' || $strCountryCode == 'ES'){
            $_SESSION['ShipToCountryCode'] = $strCountryCode;
        }

        //INITIALIZE SPROC
        $qrySetShipping = mssql_init("spOrders_SetShipping", $connShipping);
        $intStatusCode = 0;

        //BIND INPUT PARAMETERS
	    mssql_bind($qrySetShipping, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);

        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
	    mssql_bind($qrySetShipping, "@prmCompanyName", $strCompanyName, SQLVARCHAR);

	    mssql_bind($qrySetShipping, "@prmAttn", $strAttn, SQLVARCHAR);
	    mssql_bind($qrySetShipping, "@prmAddress1", $strAddress1, SQLVARCHAR);
	    mssql_bind($qrySetShipping, "@prmAddress2", $strAddress2, SQLVARCHAR);
	    mssql_bind($qrySetShipping, "@prmCity", $strCity, SQLVARCHAR);
	    if ($strState != '')
	    {
		   mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR);
	    }
	    else
	    {
		   mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR, false, true);
	    }
	    mssql_bind($qrySetShipping, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
	    mssql_bind($qrySetShipping, "@prmCountryCode", $strCountryCode, SQLVARCHAR);

	    $rs2 = mssql_execute($qrySetShipping);

	    if (mssql_num_rows($rs2) > 0)
	    {
		   while($row2 = mssql_fetch_array($rs2))
		   {
			  $_SESSION['IsShippingSet'] = 1;
			  $_SESSION['CustomerShipToID'] = $row2["RecordID"];
		   }
	    }
	}
    else
    {
		//INITIALIZE SPROC
		$qryGetAddress = mssql_init("spCustomers_GetAddress", $connShipping);
		$intStatusCode = 0;

		// BIND INPUT PARAMETERS
		mssql_bind($qryGetAddress, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);

		// EXECUTE QUERY
		$rs = mssql_execute($qryGetAddress);

		if (mssql_num_rows($rs) > 0)
		{
			while($row = mssql_fetch_array($rs))
			{
                // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
				$strCompanyName = $row["CompanyName"];

				$strAttn = $row["FirstName"] . ' ' . $row["LastName"];
				$strAddress1 = $row["Address1"];
				$strAddress2 = $row["Address2"];
				$strCity = $row["City"];
				$strState = $row["State"];
				$strPostalCode = $row["PostalCode"];
				$strCountryCode = $row["CountryCode"];
			}

		    // GMC - 05/06/09 - FedEx Netherlands
		    $_SESSION['CountryCodeFedExEu_Retail'] =  $strCountryCode;

            // GMC - 01/17/13 - Fix Bug on State Exclusion when NewCustomer or OneTimeRegistration and Returning
            $_SESSION['StateExclusion'] = $strState;

            // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
            $_SESSION['CountryExclusion'] = $strCountryCode;
		}

	    //INITIALIZE SPROC
     	$qrySetShipping = mssql_init("spOrders_SetShipping", $connShipping);
     	$intStatusCode = 0;

     	//BIND INPUT PARAMETERS
     	mssql_bind($qrySetShipping, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);

        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
     	mssql_bind($qrySetShipping, "@prmCompanyName", $strCompanyName, SQLVARCHAR);

     	mssql_bind($qrySetShipping, "@prmAttn", $strAttn, SQLVARCHAR);
     	mssql_bind($qrySetShipping, "@prmAddress1", $strAddress1, SQLVARCHAR);
     	mssql_bind($qrySetShipping, "@prmAddress2", $strAddress2, SQLVARCHAR);
     	mssql_bind($qrySetShipping, "@prmCity", $strCity, SQLVARCHAR);
     	if ($strState != '')
     	{
     		mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR);
     	}
     	else
     	{
     		mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR, false, true);
     	}
     	mssql_bind($qrySetShipping, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
     	mssql_bind($qrySetShipping, "@prmCountryCode", $strCountryCode, SQLVARCHAR);

     	$rs2 = mssql_execute($qrySetShipping);

     	if (mssql_num_rows($rs2) > 0)
     	{
     		while($row2 = mssql_fetch_array($rs2))
     		{
     			$_SESSION['IsShippingSet'] = 1;
     			$_SESSION['CustomerShipToID'] = $row2["RecordID"];
     		}
     	}
    }

	// CLOSE DATABASE CONNECTION
	mssql_close($connShipping);
}

// GMC - 05/06/09 - FedEx Netherlands
// CONNECT TO SQL SERVER DATABASE
$connFedExEURetail = mssql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
mssql_select_db($dbName, $connFedExEURetail);

$qryGetCountryCodeEU = mssql_query("SELECT IsEU FROM conCountryCodes WHERE CountryCode  = '" . $_SESSION['CountryCodeFedExEu_Retail'] . "'");

while($row = mssql_fetch_array($qryGetCountryCodeEU))
{
    if($row["IsEU"] == 0)
    {
        $_SESSION['CountryCodeFedExEu_Retail'] = '';
    }
}

// CLOSE DATABASE CONNECTION
mssql_close($connFedExEURetail);

// CONNECT TO SQL SERVER DATABASE
$connNavision = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db('Athena', $connNavision);

// SPECIFY QUERY
$qryNAVInsert = mssql_init("wsInsertWebCustomer", $connNavision);

// Bind the parameters
mssql_bind($qryNAVInsert, "@prmCustomerID", $intCustomerID, SQLINT4);
mssql_bind($qryNAVInsert, "@prmFirstName", $strFirstName, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmLastName", $strLastName, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmAddress1", $strAddress1, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmAddress2", $strAddress2, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmCity", $strCity, SQLVARCHAR);
if ($strState != '')
	{
		mssql_bind($qryNAVInsert, "@prmState", $strState, SQLVARCHAR);
	}
	else
	{
		mssql_bind($qryNAVInsert, "@prmState", $strState, SQLVARCHAR, false, true);
	}
mssql_bind($qryNAVInsert, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmCountryCode", $strCountryCode, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmTelephone", $strTelephone, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmFax", $strFax, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmSalesRep", $SalesRepID, SQLINT4);
mssql_bind($qryNAVInsert, "@prmEMailAddress", $strEMailAddress, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmWebsite", $Website, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmTitle", $strTitle, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmCompanyName", $strCompanyName, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmCompanyType", $CompanyType, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmCoreBusiness", $CoreBusiness, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmReferral", $strReferralSource, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmOtherProductsCarried", $OrderProductsCarried, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmEstheticianLicense", $EstheticianLicense, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmResellerPermit", $ResellerNumber, SQLVARCHAR);
mssql_bind($qryNAVInsert, "@prmTaxIDNumber", $TaxIDNumber, SQLVARCHAR);

$rsNAVInsert = mssql_execute($qryNAVInsert);

// GMC - 02/21/10 - Add New Shopping Cart Flow
$_SESSION['Country_Customer'] = $strCountryCode;

// CLOSE DATABASE CONNECTION
mssql_close($connNavision);

// GMC - 07/23/11 - Reinsert the I would like to place my order without creating an account option plus Email Monthly Updates
if($_SESSION['Monthly_News'] == 'Yes')
{
    // CONNECT TO SQL SERVER DATABASE
    $connMonthlyNewsLetter = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
    $selected = mssql_select_db($dbName, $connMonthlyNewsLetter);

    // SPECIFY QUERY
    $qryNewsInsert = mssql_init("spCustomers_AddMonthlyNews", $connMonthlyNewsLetter);

    // Bind the parameters
    mssql_bind($qryNewsInsert, "@prmCustomerID", $intCustomerID, SQLINT4);
    mssql_bind($qryNewsInsert, "@prmFirstName", $strFirstName, SQLVARCHAR);
    mssql_bind($qryNewsInsert, "@prmLastName", $strLastName, SQLVARCHAR);
    mssql_bind($qryNewsInsert, "@prmEMailAddress", $strEMailAddress, SQLVARCHAR);

    $rsMonthlyNews = mssql_execute($qryNewsInsert);

    // CLOSE DATABASE CONNECTION
    mssql_close($connMonthlyNewsLetter);
}

?>
