<?php 

// CONNECT TO SQL SERVER DATABASE
$connpreorder = mssql_connect($dbServer, $dbUser, $dbPass)
	or die("Couldn't connect to SQL Server on $dbServer");

// GMC - 05/06/10 - Proper RecordIDs for Consumer-Reseller (Domestic and International)
$_SESSION["CustomerTypeID_SalesRepId"] = "";

// GMC - 01/22/11 - Replace UPS Shipping Rates For FedEx ShoppingCart
// $path_to_wsdl = "http://localhost/wsdl/RateService_v8.wsdl"; // Test
$path_to_wsdl = "https://secure.revitalash.com/wsdl/RateService_v8.wsdl"; // Production

// OPEN REVITALASH DATABASE
mssql_select_db($dbName, $connpreorder);

//INITIALIZE CUSTOMER DETAILS SPROC
$qryGetCustomer = mssql_init("spCustomers_GetDetails", $connpreorder);
mssql_bind($qryGetCustomer, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);

// EXECUTE QUERY
$rs = mssql_execute($qryGetCustomer);

if (mssql_num_rows($rs) > 0)
{
	while($row = mssql_fetch_array($rs))
	{
		$CustomerName = $row["FirstName"] . ' ' . $row["LastName"];
		$CustomerAddress = $row["Address1"];

        // GMC - 05/06/10 - Proper RecordIDs for Consumer-Reseller (Domestic and International)
        $_SESSION["CustomerTypeID_SalesRepId"] = $row["CustomerTypeID"];

        if ($row["Address2"] != '')
			$CustomerAddress .= ', ' . $row["Address2"];
		$CustomerCityStateZIP = $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"];
		$CustomerCountryCode = $row["CountryCode"];
		$CustomerTelephone = $row["Telephone"];
		if ($row["TelephoneExtension"] != '')
			$CustomerTelephone .= ' x ' . $row["TelephoneExtension"];
   
        // GMC - 02/06/12 - Consumer Web Anti-Fraud Flags
        $_SESSION["CustomerName"] = $CustomerName;
        $_SESSION["CustomerAddress"] = $CustomerAddress;
        $_SESSION["CustomerCityStateZIP"] = $CustomerCityStateZIP;
        $_SESSION["CustomerCountryCode"] = $CustomerCountryCode;
        $_SESSION["CustomerTelephone"] = $CustomerTelephone;
        $_SESSION["CustomerEmail"] =  $row["EMailAddress"];
	}
}

//INITIALIZE SHIP TO DETAILS SPROC
$qryGetCustomerShipTo = mssql_init("spCustomers_GetShipToDetails", $connpreorder);

// GMC - 02/08/14 - Fix Sales Exclusion Issues
// mssql_bind($qryGetCustomerShipTo, "@prmShipToID", $_SESSION['CustomerShipToID'], SQLINT4);
mssql_bind($qryGetCustomerShipTo, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);

// EXECUTE QUERY
$rs = mssql_execute($qryGetCustomerShipTo);

if (mssql_num_rows($rs) > 0)
{
	while($row = mssql_fetch_array($rs))
	{
        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
        $ShipToCompany = $row["CompanyName"];

        $ShipToName = $row["Attn"];
		$ShipToAddress = $row["Address1"];
		if ($row["Address2"] != '')
			$ShipToAddress .= ', ' . $row["Address2"];
		$ShipToCityStateZIP = $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"];
		$ShipToCountryCode = $row["CountryCode"];

		// GMC - 01/24/09 - CA - NV (For Customer Web)
		// GMC - 05/29/09 - UT (For Customer Web)
        $_SESSION['State_Customer'] = $row["State"];
        
        // GMC - 06/24/09 - To Fix New Web Customer Sales Tax (CA - NV- UT - For Now)
        $_SESSION['Zip_Customer'] = $row["PostalCode"];
        
        // GMC - 06/28/09 - To Fix International $41.00 instead of $31.00
        if($ShipToCountryCode == "US")
        {
           $_SESSION['IsInternational'] = 0;
        }
        else
        {
           $_SESSION['IsInternational'] = 1;
        }
	}
}

$rs = mssql_query("SELECT ShippingMethodDisplay FROM conShippingMethods WHERE RecordID = " . $_SESSION['ShippingMethod']);
while($row = mssql_fetch_array($rs))
{
	$ShipMethod = $row["ShippingMethodDisplay"];
}

// CALCULATE ORDER SUBTOTAL
$decSubtotal = 0;

// GMC - 09/09/2009 - Fix for Empty Key Value in Order Detail
// $input = $_SESSION['cart'];
// $result = array_unique($input);
// print_r($result);
// print_r($_SESSION['cart']);
// echo "";
// var_dump($_SESSION['cart']);

foreach($_SESSION['cart'] as $key => $value)
{
    if($value == 0)
    {
         unset($_SESSION['cart'][$key]);
    }
}

if (isset($_SESSION['IsProLoggedIn']))
{
	for (reset($_SESSION['cart']); list($key) = each($_SESSION['cart']);)
	{
		// EXECUTE SQL QUERY
		$rs = mssql_query("SELECT RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, CategoryID FROM tblProducts WHERE RecordID = " . $key);
		while($row = mssql_fetch_array($rs))
		{
			if ($_SESSION['CustomerTypeID'] == 2)
			{
				if ($_SESSION['IsInternational'] == 0)
				{
					$rs2 = mssql_query("SELECT TOP 1 DiscountPrice FROM tblProducts_ResellerTier WHERE ProductID = " . $key . " AND " . $_SESSION['cart'][$key] . " >= QtyRequired ORDER BY QtyRequired DESC");
					while($row2 = mssql_fetch_array($rs2))
					{
						$decUnitPrice = $row2["DiscountPrice"];
						$decExtendedPrice = number_format($row2["DiscountPrice"] * $_SESSION['cart'][$key], 2, '.', '');
					}
				}
				else
				{
					$decUnitPrice = $row["ResellerPrice"];
					$decExtendedPrice = number_format($row["ResellerPrice"] * $_SESSION['cart'][$key], 2, '.', '');
				}
			}
			elseif ($_SESSION['CustomerTypeID'] == 3)
			{
				$decUnitPrice = $row["DistributorPrice"];
				$decExtendedPrice = number_format($row["DistributorPrice"] * $_SESSION['cart'][$key], 2, '.', '');
			}
				
			if (($_SESSION['IsInternational'] == 1) && ($row["CategoryID"] != 2))
			{
				$decUnitPrice = number_format($decUnitPrice + $row["InternationalSurcharge"], 2, '.', '');
				$decExtendedPrice = number_format($decExtendedPrice + ($row["InternationalSurcharge"] * $_SESSION['cart'][$key]), 2, '.', '');
			}

            // GMC - 05/29/12 - SUMMER2012 Web Reseller Promotion June 2012
            // GMC - 01/03/12 - Promo Code for Resellers
            // GMC - 09/12/13 - Cancel Promo Code for Resellers
            // GMC - 02/02/14 - Put Back Promo Code for Resellers
            if($_SESSION['PaymentCC_PromoCode'] != '')
            {
                $rs3 = mssql_query("SELECT PromoCode, Discount FROM tblResellersPromoCode WHERE IsActive = 1 AND PromoCode = '" . $_SESSION['PaymentCC_PromoCode'] . "'");

                while($row3 = mssql_fetch_array($rs3))
	            {
                    $strPromoCode = $row3["PromoCode"];
                    $strDiscount = $row3["Discount"];
                }

                if($strPromoCode != '' && $key == 620)
                {
					$decUnitPrice = ($row["ResellerPrice"]- ($row["ResellerPrice"] * $strDiscount));
					$decExtendedPrice = number_format($decUnitPrice * $_SESSION['cart'][$key], 2, '.', '');
                }
                else if($strPromoCode != '' && $key != 620)
                {
                    $_SESSION['Promo_Code'] = $strPromoCode;

                    // GMC - 09/05/12 Fix Bug with Promo Code Calculation
                    // $discountValue = $decSubtotal * $strDiscount;
                    // $decSubtotal = ($decSubtotal - ($decSubtotal * $strDiscount));

					$decUnitPrice = ($row["ResellerPrice"]- ($row["ResellerPrice"] * $strDiscount));
                    $discountValue = $discountValue + ($row["ResellerPrice"] * $strDiscount * $_SESSION['cart'][$key]);
					$decExtendedPrice = number_format($decUnitPrice * $_SESSION['cart'][$key], 2, '.', '');
                }
            }

			$decSubtotal = $decSubtotal + $decExtendedPrice;
		}
	}
}
else
{
	for (reset($_SESSION['cart']); list($key) = each($_SESSION['cart']);)
	{
		// EXECUTE SQL QUERY
		$rs = mssql_query("SELECT RetailPrice FROM tblProducts WHERE RecordID = " . $key);
		while($row = mssql_fetch_array($rs))
		{
                 $decExtendedPrice = number_format($row["RetailPrice"] * $_SESSION['cart'][$key], 2, '.', '');
			     $decSubtotal = $decSubtotal + $decExtendedPrice;
		}
	}
	
    // GMC - 06/02/09 - June 2009 Promotion 06/05 to 06/19 - 15% Off on Total Purchased Value (any product)
    // GMC - 06/29/09 - July 2009 Promotion "New Beauty" - 15% Off on Total Purchased Value (any product)
    // GMC - 09/17/09 - September 2009 Promotion "Pretty City" - 15% Off on Total Purchased Value (any product)
    /*
    $Promo_Code = strtoupper($_POST['Promo_Code']);
    if($Promo_Code == "PEOPLE")
    {
        $_SESSION['Promo_Code'] = "PEOPLE";
        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }
    */
    
    $Promo_Code = strtoupper($_POST['Promo_Code']);
    if($Promo_Code == "NEWBEAUTY" || $Promo_Code == "NEW BEAUTY")
    {
        $_SESSION['Promo_Code'] = "NEWBEAUTY";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }
    
    // GMC - 09/10/09 - Friend Promo Code
    if($Promo_Code == "FRIEND")
    {
        $_SESSION['Promo_Code'] = "FRIEND";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }
    
    // GMC - 09/17/09 - Pretty City Promo Code
    if($Promo_Code == "PRETTYCITY")
    {
        $_SESSION['Promo_Code'] = "PRETTYCITY";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }
    
    // GMC - 09/24/09 - Meg Promo Code
    if($Promo_Code == "MEG")
    {
        $_SESSION['Promo_Code'] = "MEG";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 10/20/09 - OK Weekly Promo Code
    if($Promo_Code == "OKWEEKLY" || $Promo_Code == "OK WEEKLY")
    {
        $_SESSION['Promo_Code'] = "OKWEEKLY";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 10/30/09 - Hair Loss Talk Promo Code
    if($Promo_Code == "HAIR LOSS TALK" || $Promo_Code == "HAIRLOSSTALK")
    {
        $_SESSION['Promo_Code'] = "HAIRLOSSTALK";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 11/12/09 - Twitter Promo Code
    if($Promo_Code == "TWITTER")
    {
        $_SESSION['Promo_Code'] = "TWITTER";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 11/16/09 - Leads Promo Code
    if($Promo_Code == "LEADS")
    {
        $_SESSION['Promo_Code'] = "LEADS";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 11/25/09 - Remax Promo Code
    if($Promo_Code == "REMAX")
    {
        $_SESSION['Promo_Code'] = "REMAX";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 12/03/09 - Rome Promo Code
    if($Promo_Code == "ROME")
    {
        $_SESSION['Promo_Code'] = "ROME";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 04/28/10 - April 2010 Promotion "Newsletter" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "NEWSLETTER")
    {
        $_SESSION['Promo_Code'] = "NEWSLETTER";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 06/21/10 - June 2010 Promotion "Wahanda" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "WAHANDA")
    {
        $_SESSION['Promo_Code'] = "WAHANDA";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 07/02/10 - July 2010 Promotion "ISPA" - 20% Off on Total Purchased Value (any product)
    if($Promo_Code == "ISPA")
    {
        $_SESSION['Promo_Code'] = "ISPA";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.20;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.20));
    }

    // GMC - 07/26/10 - July 2010 Promotion "YANDR" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "YANDR")
    {
        $_SESSION['Promo_Code'] = "YANDR";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 08/20/10 - August 2010 Promotion "LSE001" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "LES001")
    {
        $_SESSION['Promo_Code'] = "LSE001";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 09/28/10 - September 2010 Promotion "TWEET" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "TWEET")
    {
        $_SESSION['Promo_Code'] = "TWEET";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 09/28/10 - September 2010 Promotion "TUBEIT" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "TUBEIT")
    {
        $_SESSION['Promo_Code'] = "TUBEIT";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 10/06/10 - October 2010 Promotion "AMERSPA" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "AMERSPA")
    {
        $_SESSION['Promo_Code'] = "AMERSPA";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 10/07/10 - Commision Junction Project
    // GMC - 10/19/10 - Cancel 15% for CJ
    /*
    if($_SESSION['CJ_VAR'] == "Yes")
    {
        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }
    */
    
    // GMC - 10/18/10 - October 2010 Promotion "LASHES" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "LASHES")
    {
        $_SESSION['Promo_Code'] = "LASHES";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "MASCARA")
    {
        $_SESSION['Promo_Code'] = "MASCARA";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 12/20/10 - December 2010 Promotion "JOURNAL" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "JOURNAL")
    {
        $_SESSION['Promo_Code'] = "JOURNAL";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 01/17/10 - January 2011 Promotion "COUPON" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "COUPON")
    {
        $_SESSION['Promo_Code'] = "COUPON";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 01/18/10 - January 2011 Promotion "ORDER" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "ORDER")
    {
        $_SESSION['Promo_Code'] = "ORDER";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }
    
    // GMC - 03/01/11 - March 2011 Promotion "LASHWORLD" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "LASHWORLD")
    {
        $_SESSION['Promo_Code'] = "LASHWORLD";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 04/04/11 - April 2011 Promotion "CLIQUE" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "CLIQUE")
    {
        $_SESSION['Promo_Code'] = "CLIQUE";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 06/14/11 - June 2011 Promotion "PKW" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "PKW")
    {
        $_SESSION['Promo_Code'] = "PKW";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

    // GMC - 09/22/11 - September 2011 Promotion "GIFT" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "GIFT")
    {
        $_SESSION['Promo_Code'] = "GIFT";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }
    
    // GMC - 11/22/11 - November 2011 Promotion "BF2011" - 20% Off on Total Purchased Value (any product)
    if($Promo_Code == "BF2011")
    {
        $_SESSION['Promo_Code'] = "BF2011";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.20;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.20));
    }

    // GMC - 04/16/12 - April 2012 Promotion "BUZZ" - 15% Off on Total Purchased Value (any product)
    if($Promo_Code == "BUZZ")
    {
        $_SESSION['Promo_Code'] = "BUZZ";

        // GMC - 09/19/09 - To present a more detailed discount information
        $discountValue = $decSubtotal * 0.15;

        $decSubtotal = ($decSubtotal - ($decSubtotal * 0.15));
    }

}

$_SESSION['OrderSubtotal'] = $decSubtotal;

// GMC - 09/19/09 - To present a more detailed discount information
$_SESSION['OrderDiscountValue'] = $discountValue;

//CALCULATE SHIPPING

// GMC - 06/28/09 - To Fix International $41.00 instead of $31.00
if ($_SESSION['IsInternational'] == 1)
{
    $rs = mssql_query("SELECT Handling FROM tblSite_Options WHERE RecordID = 2");
}
else
{
    $rs = mssql_query("SELECT Handling FROM tblSite_Options WHERE RecordID = 1");
}

while($row = mssql_fetch_array($rs))
{
	$_SESSION['OrderHandling'] = $row["Handling"];
}

if (!isset($_SESSION['OrderWeight']))
{
	$_SESSION['OrderWeight'] = 1;
}

// GMC - 01/22/11 - Replace UPS Shipping Rates For FedEx ShoppingCart
ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array('UserCredential' =>

// array('Key' => 'XXX', 'Password' => 'YYY')); // Replace 'XXX' and 'YYY' with FedEx provided credentials
array('Key' => 'hRnybIZX3PKne28q', 'Password' => 'yDjVeSkK252f1kFblX1AXy31b')); // Replace 'XXX' and 'YYY' with FedEx provided credentials

// $request['ClientDetail'] = array('AccountNumber' => 'XXX', 'MeterNumber' => 'XXX');// Replace 'XXX' with your account and meter number
$request['ClientDetail'] = array('AccountNumber' => '462227000', 'MeterNumber' => '100677102');// Replace 'XXX' with your account and meter number

$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Available Services Request v8 using PHP ***');
$request['Version'] = array('ServiceId' => 'crs', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
$request['ReturnTransitAndCommit'] = true;

$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
$request['RequestedShipment']['ShipTimestamp'] = date('c');

// Service Type and Packaging Type are not passed in the request
$request['RequestedShipment']['Shipper'] = array('Address' => array(
                          'StreetLines' => array('701 N. Green Valley Parkway St # 200'), // Origin details
                          'City' => 'Henderson',
                          'StateOrProvinceCode' => 'NV',
                          'PostalCode' => '89074',
                          'CountryCode' => 'US'));

$request['RequestedShipment']['Recipient'] = array('Address' => array (
                          'StreetLines' => array('123 Main St'), // Destination details
                          'City' => $_SESSION['ShipToCity'],
                          'StateOrProvinceCode' => $_SESSION['ShipToState'],
                          'PostalCode' => $_SESSION['ShipToZip'],
                          'CountryCode' => $_SESSION['ShipToCountry']));
$request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
                          'Payor' => array('AccountNumber' => '462227000', // Replace "XXX" with payor's account number
                          'CountryCode' => 'US'));
$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT';
$request['RequestedShipment']['PackageCount'] = '1';
$request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
$request['RequestedShipment']['RequestedPackageLineItems'] = array('0' => array('Weight' => array('Value' => $_SESSION['OrderWeight'] + 1.2,
                                                                    'Units' => 'LB'),
                                                                    'Dimensions' => array('Length' => 12,
                                                                    'Width' => 12,
                                                                    'Height' => 12,
                                                                    'Units' => 'IN')));

try
{
 $response = $client -> getRates($request);
 if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
 {
      foreach ($response -> RateReplyDetails as $rateReply)
      {
          $serviceType = $rateReply -> ServiceType;

          if($serviceType == "FEDEX_GROUND")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $ResultCodeOut = $rateReply -> RatedShipmentDetails -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
              }
              if($_SESSION['ShippingMethod'] == 199)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }
          elseif($serviceType == "FEDEX_2_DAY")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 201)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }
          elseif($serviceType == "FEDEX_EXPRESS_SAVER")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 200)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }
          elseif($serviceType == "FEDEX_1_DAY_FREIGHT")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 211)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }

          elseif($serviceType == "FEDEX_2_DAY_FREIGHT")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 212)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }
          elseif($serviceType == "FEDEX_3_DAY_FREIGHT")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 213)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }

          // GMC - 03/11/11 - Add Standard Overnight to Shipping Methods
          elseif($serviceType == "STANDARD_OVERNIGHT")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 202)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }

          // International Override
          /*
          elseif($serviceType == "INTERNATIONAL_PRIORITY")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge->Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 205)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }
          elseif($serviceType == "INTERNATIONAL_ECONOMY")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 206)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }
          elseif($serviceType == "INTERNATIONAL_PRIORITY_FREIGHT")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge->Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 214)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }
          elseif($serviceType == "INTERNATIONAL_ECONOMY_FREIGHT")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 215)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }
          elseif($serviceType == "INTERNATIONAL_FIRST")
          {
              if(is_array($response -> RateReplyDetails))
              {
                  $tnfec = $rateReply -> RatedShipmentDetails;
                  foreach ($tnfec as $wha)
                  {
                      $ResultCodeOut = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                  }
              }
              if($_SESSION['ShippingMethod'] == 207)
              {
                  $ResultCode = $ResultCodeOut;
              }
          }
          */
      }
 }
 else
 {
      foreach ($response -> Notifications as $notification)
      {
          if(is_array($response -> Notifications))
          {
              $blnIsError = 1;
          }
          else
          {
              $blnIsError = 1;
          }
      }
 }
}
catch (SoapFault $exception)
{
 $blnIsError = 1;
 echo $exception;
}

// International Override
if ($_SESSION['ShipToCountry'] != 'US')
{
    $ResultCode = 26;
}

// GMC - 10/27/08 - To accomodate $10 Extra Surcharge
// GMC - 06/28/09 - To Fix International $41.00 instead of $31.00
/*
if ($_SESSION['IsInternational'] == 1)
{
   $ResultCode += 10;
}
*/

// GMC - 10/21/09 - No Shipping - Handling for Order greater than $50.00 (Customers Only)
// GMC - 02/03/10 - Cancel No Shipping for Order Greater than $50.00 (Customers Only)
/*
if($_SESSION['OrderSubtotal'] > 50 && $_SESSION['CustomerTypeID'] == "1")
{
    $ResultCode = 0;
	$_SESSION['OrderHandling'] = 0;
}
*/

// GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
if ($_SESSION['Promo_Code'] == "MASCARA")
{
    $ResultCode = 0;
	$_SESSION['OrderHandling'] = 0;
}

$_SESSION['OrderShipping'] = $ResultCode;

// GMC - 01/24/09 - CA - NV (For Customer Web)
// GMC - 05/29/09 - UT (For Customer Web)

if (!isset($_SESSION['IsProLoggedIn']))
{
	//INITIALIZE SALES TAX SPROC
	$qryGetTaxRate = mssql_init("spOrders_GetSalesTax", $connpreorder);

	// BIND INPUT PARAMETERS
	mssql_bind($qryGetTaxRate, "@prmShipToID", $_SESSION['CustomerShipToID'], SQLINT4);

	// EXECUTE QUERY
	$rs = mssql_execute($qryGetTaxRate);

	if (mssql_num_rows($rs) > 0)
	{
		while($row = mssql_fetch_array($rs))
		{
            // GMC - 01/24/09 - CA - NV (For Customer Web)
            if(strtoupper($_SESSION['State_Customer']) == 'CA')
            {
                $_SESSION['OrderTaxRate'] = $row["TaxRate"];
			    $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal']);
            }
            else if(strtoupper($_SESSION['State_Customer']) == 'NV')
            {
		        $_SESSION['OrderTaxRate'] = $row["TaxRate"];
			    $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal'] + $_SESSION['OrderShipping'] + $_SESSION['OrderHandling']);
            }
            
            // GMC - 05/29/09 - UT (For Customer Web)
            else if(strtoupper($_SESSION['State_Customer']) == 'UT')
            {
		        $_SESSION['OrderTaxRate'] = $row["TaxRate"];
			    $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal'] + $_SESSION['OrderShipping'] + $_SESSION['OrderHandling']);
            }

            else
            {
                // GMC - 05/06/09 - FedEx Netherlands
                if($_SESSION['CountryCodeFedExEu_Retail'] != '')
                {
                    // GMC - 10/25/10 - Disconnect Earnst&Young and VAT Charges for EU Orders - Consumers
                    // GMC - 09/28/09 - Adjust based on Ernst & Young from 17% to 19%
                    // $_SESSION['OrderTaxRate'] = 0.17;
                    // $_SESSION['OrderTaxRate'] = 0.19;
                    $_SESSION['OrderTaxRate'] = 0;

                    // GMC - 09/29/09 - Calculate Taxes on Shipping and Handling Too by Ernst & Young
                    // $_SESSION['OrderTax'] = ($_SESSION['OrderSubtotal'] * $_SESSION['OrderTaxRate']);
                    $_SESSION['OrderTax'] = (($_SESSION['OrderSubtotal'] + $_SESSION['OrderShipping'] + $_SESSION['OrderHandling'])* $_SESSION['OrderTaxRate']);
                }
                else
                {
                    $_SESSION['OrderTaxRate'] = $row["TaxRate"];
			        $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal'] + $_SESSION['OrderHandling']);
			    }
            }
		}
	}
	
	// GMC - 06/24/09 - To Fix New Web Customer Sales Tax (CA - NV- UT - For Now)
    if(strtoupper($_SESSION['State_Customer']) == 'CA' ||  strtoupper($_SESSION['State_Customer']) == 'NV' || strtoupper($_SESSION['State_Customer']) == 'UT')
    {
       $rsSalesTax = mssql_query("SELECT TaxRate FROM conSalesTaxRates WHERE PostalCode =  " . $_SESSION['Zip_Customer']);

       // GMC - 07/16/09 - Force a Sales Tax Flat Rate by Jesse Stancarone
	   if (mssql_num_rows($rsSalesTax) > 0)
	   {
           while($row = mssql_fetch_array($rsSalesTax))
           {
               // GMC - 01/24/09 - CA - NV (For Customer Web)
               if(strtoupper($_SESSION['State_Customer']) == 'CA')
               {
                    if($row["TaxRate"] == "")
                    {
                        $row["TaxRate"] = 0.0825;
                        $_SESSION['OrderTaxRate'] = 0.0825;
                    }
                    else
                    {
                        $_SESSION['OrderTaxRate'] = $row["TaxRate"];
                    }

			        $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal']);
               }
               else if(strtoupper($_SESSION['State_Customer']) == 'NV')
               {
                    if($row["TaxRate"] == "")
                    {
                        $row["TaxRate"] = 0.0810;
                        $_SESSION['OrderTaxRate'] = 0.0810;
                    }
                    else
                    {
                        $_SESSION['OrderTaxRate'] = $row["TaxRate"];
                    }

			        $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal'] + $_SESSION['OrderShipping'] + $_SESSION['OrderHandling']);
               }

               // GMC - 05/29/09 - UT (For Customer Web)
               else if(strtoupper($_SESSION['State_Customer']) == 'UT')
               {
                    if($row["TaxRate"] == "")
                    {
                        $row["TaxRate"] = 0.0685;
                        $_SESSION['OrderTaxRate'] = 0.0685;
                    }
                    else
                    {
                        $_SESSION['OrderTaxRate'] = $row["TaxRate"];
                    }

			        $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal'] + $_SESSION['OrderShipping'] + $_SESSION['OrderHandling']);
               }
           }
       }
       else
       {
           // GMC - 01/24/09 - CA - NV (For Customer Web)
           if(strtoupper($_SESSION['State_Customer']) == 'CA')
           {
               $row["TaxRate"] = 0.0825;
               $_SESSION['OrderTaxRate'] = 0.0825;
			   $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal']);
           }
           else if(strtoupper($_SESSION['State_Customer']) == 'NV')
           {
               $row["TaxRate"] = 0.0810;
	           $_SESSION['OrderTaxRate'] = 0.0810;
			   $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal'] + $_SESSION['OrderShipping'] + $_SESSION['OrderHandling']);
           }

           // GMC - 05/29/09 - UT (For Customer Web)
           else if(strtoupper($_SESSION['State_Customer']) == 'UT')
           {
               $row["TaxRate"] = 0.0685;
		       $_SESSION['OrderTaxRate'] = 0.0685;
			   $_SESSION['OrderTax'] = $row["TaxRate"] * ($_SESSION['OrderSubtotal'] + $_SESSION['OrderShipping'] + $_SESSION['OrderHandling']);
           }
       }
	}
}
else
{
	$_SESSION['OrderTaxRate'] = 0;
	$_SESSION['OrderTax'] = 0;
}

// TOTAL ORDER
$_SESSION['OrderTotal'] = $_SESSION['OrderSubtotal'] + $_SESSION['OrderHandling'] + $_SESSION['OrderShipping'] + $_SESSION['OrderTax'];

// CLOSE DATABASE CONNECTION
mssql_close($connpreorder);
?>
