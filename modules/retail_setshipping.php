<?php 

// CONNECT TO SQL SERVER DATABASE
$connShipping = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connShipping);
	
$qryUpdate = mssql_init("spCustomers_RetailEdit", $connShipping);

$strFirstName = $_POST['FirstName'];
$strLastName = $_POST['LastName'];
$strEMailAddress = $_POST['EMailAddress'];
$strAddress1 = $_POST['Address1'];
$strAddress2 = $_POST['Address2'];
$strCity = $_POST['City'];
$strState = $_POST['State'];

// GMC - 01/17/13 - Fix Bug on State Exclusion when NewCustomer or OneTimeRegistration and Returning
$_SESSION['StateExclusion'] = $strState;

$strPostalCode = $_POST['PostalCode'];
$strCountryCode = $_POST['CountryCode'];
$strTelephone = preg_replace('/[^0-9]/', '', $_POST['Telephone']);
$strTelephoneExtension = preg_replace('/[^0-9]/', '', $_POST['TelephoneExtension']);

$intStatusCode = 0;

// Bind the parameters
mssql_bind($qryUpdate, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);
mssql_bind($qryUpdate, "@prmFirstName", $strFirstName, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmLastName", $strLastName, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmEMailAddress", $strEMailAddress, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmAddress1", $strAddress1, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmAddress2", $strAddress2, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmCity", $strCity, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmState", $strState, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmCountryCode", $strCountryCode, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmTelephone", $strTelephone, SQLVARCHAR);
mssql_bind($qryUpdate, "@prmTelephoneExtension", $strTelephoneExtension, SQLVARCHAR);

// Bind the return value
mssql_bind($qryUpdate, "RETVAL", $intStatusCode, SQLINT2);

// EXECUTE QUERY
$rs = mssql_execute($qryUpdate);

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
	}

    //INITIALIZE SPROC
    $qrySetShipping = mssql_init("spOrders_SetShipping", $connShipping);

    //BIND INPUT PARAMETERS
    mssql_bind($qrySetShipping, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);

    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
    mssql_bind($qrySetShipping, "@prmCompanyName", $strCompanyName, SQLVARCHAR);

    mssql_bind($qrySetShipping, "@prmAttn", $strAttn, SQLVARCHAR);
    mssql_bind($qrySetShipping, "@prmAddress1", $strAddress1, SQLVARCHAR);
    mssql_bind($qrySetShipping, "@prmAddress2", $strAddress2, SQLVARCHAR);
    mssql_bind($qrySetShipping, "@prmCity", $strCity, SQLVARCHAR);
    mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR);
    mssql_bind($qrySetShipping, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
    mssql_bind($qrySetShipping, "@prmCountryCode", $strCountryCode, SQLVARCHAR);

    $rs2 = mssql_execute($qrySetShipping);

    if (mssql_num_rows($rs2) > 0)
    {
	    while($row2 = mssql_fetch_array($rs2))
	    {
		    $_SESSION['IsShippingSet'] = 1;
		    $_SESSION['CustomerShipToID'] = $row2["RecordID"];
		    if ($strCountryCode == 'US')
			   $_SESSION['IsInternational'] = 0;
		    else
			   $_SESSION['IsInternational'] = 1;
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
	$strPostalCode = $_POST['ShipToPostalCode'];
	$strCountryCode = $_POST['ShipToCountryCode'];
	
	// GMC - 05/06/09 - FedEx Netherlands
	$_SESSION['CountryCodeFedExEu_Retail'] =  $strCountryCode;

    // GMC - 01/17/13 - Fix Bug on State Exclusion when NewCustomer or OneTimeRegistration and Returning
    $_SESSION['StateExclusion'] = $strState;

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
    mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR);
    mssql_bind($qrySetShipping, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
    mssql_bind($qrySetShipping, "@prmCountryCode", $strCountryCode, SQLVARCHAR);

    $rs2 = mssql_execute($qrySetShipping);

    if (mssql_num_rows($rs2) > 0)
    {
	   while($row2 = mssql_fetch_array($rs2))
	   {
           $_SESSION['IsShippingSet'] = 1;
           $_SESSION['CustomerShipToID'] = $row2["RecordID"];
           if ($strCountryCode == 'US')
		       $_SESSION['IsInternational'] = 0;
           else
               $_SESSION['IsInternational'] = 1;
       }
    }
}

// GMC - 02/08/14 - Fix Sales Exclusion Issues
else
{
    //INITIALIZE SPROC
    $qrySetShipping = mssql_query("SELECT * FROM tblCustomers_ShipTo WHERE CustomerID = " . $_SESSION['CustomerID'] . " AND IsDefault = 'True' AND IsActive = 'True'");

    while($row2 = mssql_fetch_array($qrySetShipping))
    {
        $_SESSION['IsShippingSet'] = 1;
		$_SESSION['CustomerShipToID'] = $row2["RecordID"];
		if ($strCountryCode == 'US')
			$_SESSION['IsInternational'] = 0;
		else
			$_SESSION['IsInternational'] = 1;
    }
}

// CLOSE DATABASE CONNECTION
mssql_close($connShipping);

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

?>
