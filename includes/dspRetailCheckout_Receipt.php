<?php

// ATTEMPT CONNECTION TO DATABASE SERVER
$conn = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $conn);

$qryGetCustomer = mssql_query("SELECT * FROM tblCustomers WHERE IsActive = 1 AND RecordID = " . $_SESSION['CustomerID']);
$qryShippingMethod = mssql_query("SELECT ShippingMethodDisplay FROM conShippingMethods WHERE RecordID = " . $_SESSION['ShippingMethod']);

// GMC - 05/25/10 - Proper Shipping Information at Confirmation
$qryGetOrderShipTo = mssql_query("SELECT * FROM tblCustomers_ShipTo WHERE RecordID = " . $_SESSION['CustomerShipToID'] . " AND IsDefault = 'True'");

$qryGetShippingMethods = mssql_init("spConstants_CSRShippingMethods", $conn);
mssql_bind($qryGetShippingMethods, "@prmCustomerShipToID", $_SESSION['CustomerShipToID'], SQLINT4);
$rsGetShippingMethods = mssql_execute($qryGetShippingMethods);

while($rowGetCustomer = mssql_fetch_array($qryGetCustomer))
{
    $intCustomerType = $rowGetCustomer["CustomerTypeID"];
	$strCustomerFirstName = $rowGetCustomer["FirstName"];
	$strCustomerLastName = $rowGetCustomer["LastName"];
	$strCustomerName = $strCustomerFirstName . ' ' . $strCustomerLastName;
	$strCompanyName = $rowGetCustomer["CompanyName"];
	$strCustomerEMail = $rowGetCustomer["EMailAddress"];

    // GMC - 03/12/12 - Add Billing Information to Order Confirmations
    $BillAddress1 = $rowGetCustomer["Address1"];
    $BillAddress2 = $rowGetCustomer["Address2"];
    $BillCity = $rowGetCustomer["City"];
    $BillState = $rowGetCustomer["State"];
    $BillPostalCode = $rowGetCustomer["PostalCode"];
    $BillCountryCode = $rowGetCustomer["CountryCode"];

	if ($rowGetCustomer["CountryCode"] == 'US')
		$blnIsInternational = 0;
	else
		$blnIsInternational = 1;
}

// GMC - 05/25/10 - Proper Shipping Information at Confirmation
while($rowGetShipTo = mssql_fetch_array($qryGetOrderShipTo))
{
    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
    $CompanyName =  $rowGetShipTo['CompanyName'];
    $Attn =  $rowGetShipTo['Attn'];

    if($Attn == "")
    {
        // GMC - 02/06/14 - Fix the picking of wrong shipping information for Confirmation
        /*
        $qryGetShipToNameAlt = mssql_query("SELECT top 1 * FROM tblCustomers_ShipTo WHERE CustomerID = " . $_SESSION['CustomerID'] . " AND Attn is not NULL");

     	while($rowGetShipToNameAlt = mssql_fetch_array($qryGetShipToNameAlt))
	    {
           // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
		   $CompanyName = $rowGetShipToNameAlt['CompanyName'];

		   $Attn = $rowGetShipToNameAlt['Attn'];
	       $Address1 = $rowGetShipToNameAlt['Address1'];
	       $Address2 = $rowGetShipToNameAlt['Address2'];
	       $AddressCity = $rowGetShipToNameAlt['City'];
	       $AddressState = $rowGetShipToNameAlt['State'];
	       $AddressPostalCode = $rowGetShipToNameAlt['PostalCode'];
	       $AddressCountryCode = $rowGetShipToNameAlt['CountryCode'];
	    }
        */
        
        $Attn = $strCustomerFirstName + " " + $strCustomerLastName;
	    $Address1 = $rowGetShipTo['Address1'];
	    $Address2 = $rowGetShipTo['Address2'];
	    $AddressCity = $rowGetShipTo['City'];
	    $AddressState = $rowGetShipTo['State'];
	    $AddressPostalCode = $rowGetShipTo['PostalCode'];
	    $AddressCountryCode = $rowGetShipTo['CountryCode'];
    }
    else
    {
	    $Address1 = $rowGetShipTo['Address1'];
	    $Address2 = $rowGetShipTo['Address2'];
	    $AddressCity = $rowGetShipTo['City'];
	    $AddressState = $rowGetShipTo['State'];
	    $AddressPostalCode = $rowGetShipTo['PostalCode'];
	    $AddressCountryCode = $rowGetShipTo['CountryCode'];
    }
}

while($rowSM = mssql_fetch_array($qryShippingMethod))
{ $strShippingMethod = $rowSM["ShippingMethodDisplay"]; }

$decSubtotal = 0;

// GMC - 12/12/08 - Change Text on Order Confirmation
// $strConfirmation = '<h2>Order Confirmation:</h2>';

// GMC - 09/24/09 - Present Invoice to Ernst & Young
if($_SESSION['CountryCodeFedExEu_Retail'] != '')
{
    $strConfirmation = '<h2><div align="center"><font color="red">*** INVOICE ***</font></div></h2>';
    $strConfirmation .= '<h2>WEB Order#: ' . $_SESSION['OrderID'] . ' Amount ' . convert($_SESSION['OrderTotal'],$decExchangeRate,$strCurrencyName) . '</h2>';
}
else
{
    // GMC - 05/11/10 - Order Completed Information to prevent click on refresh
    // GMC - 03/07/11 - Changes to Order Confirmation
    // $strConfirmation = '<h2><div align="center"><font color="red">*** ORDER COMPLETED ***</font></div></h2>';
    $strConfirmation = '<h2><div align="center"><font color="red">*** ORDER CONFIRMATION ***</font></div></h2>';
    $strConfirmation .= '<h2><div align="center">WEB Order#: ' . $_SESSION['OrderID'] . ' Amount ' . convert($_SESSION['OrderTotal'],$decExchangeRate,$strCurrencyName) . '</div></h2>';
}

// GMC - 05/11/10 - Order Completed Information to prevent click on refresh
// $strConfirmation .= '<p>Thank you for your recent order at revitalash.com.</p>';
// GMC - 03/07/11 - Changes to Order Confirmation
// $strConfirmation .= '<div align="center"><b>Thank you for your recent order at revitalash.com.<br>Your order is complete.<br>Your order confirmation has been sent to your email address.<br><a href="http://www.revitalash.com">Click here to exit</a></b></div>';
$strConfirmation .= '<div align="center"><b>Thank you for your recent order at revitalash.com.<br>Your order confirmation has been sent to your email address.</div>';

$strConfirmation .= '<table width="100%" cellspacing="2" cellpadding="0">';
$strConfirmation .= '<tr>';
$strConfirmation .= '<th width="140" style="text-align:left;">Name: <br>' . $strCustomerFirstName . ' ' . $strCustomerLastName . '</th>';
$strConfirmation .= '</tr>';

if ($strCompanyName != $strCustomerName)
{
	echo '<tr>
        <th style="text-align:left;">Company:</th>
        <td>' . $strCompanyName . '</td>
    </tr>';
}
	
$strConfirmation .= '</table>';

// GMC - 03/12/12 - Add Billing Information to Order Confirmations
$strConfirmation .= '<p>&nbsp;</p>';
$strConfirmation .= '<p><span style="font-weight:bold;">Billing Information</span><br />';
$strConfirmation .= $BillAddress1 . '<br />';
if ($BillAddress2 != '') $strConfirmation .= $BillAddress2 . '<br />';
$strConfirmation .= $BillCity . ', ' . $BillState . ' ' . $BillPostalCode . '<br />';
$strConfirmation .= $BillCountryCode . '</p>';

$strConfirmation .= '<p>&nbsp;</p>';
$strConfirmation .= '<p><span style="font-weight:bold;">Shipping Information</span><br />';

// GMC - 05/25/10 - Proper Shipping Information at Confirmation
// GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
// GMC - 03/07/11 - Changes to Order Confirmation
// $strConfirmation .= $CompanyName . '<br />';
$strConfirmation .= $Attn . '<br />';

$strConfirmation .= $Address1 . '<br />';
if ($Address2 != '') $strConfirmation .= $Address2 . '<br />';
$strConfirmation .= $AddressCity . ', ' . $AddressState . ' ' . $AddressPostalCode . '<br />';
$strConfirmation .= $AddressCountryCode . '</p>';

$strConfirmation .= '<p><span style="font-weight:bold;">Payment Information</span><br />';

if ($_SESSION['PaymentType'] == 'CreditCard')
{
	$strConfirmation .= 'Credit Card Ending in ' . substr($_SESSION['PaymentCC_Number'],-5,5) . '<br />';
	$strConfirmation .= 'Expires ' . $_SESSION['PaymentCC_ExpMonth'] . ' / ' . $_SESSION['PaymentCC_ExpYear'] . '</p>';
}
elseif ($_SESSION['PaymentType'] == 'CreditCardSwiped')
{
	$strConfirmation .= 'Preswiped Authorization ' . $_SESSION['PaymentCC_SwipedAuth'] . '</p>';
}
elseif ($_SESSION['PaymentType'] == 'ECheck')
{
	$strConfirmation .= 'Bank Routing Number: ' . $_SESSION['PaymentCK_BankRouting'] . '<br />';
	$strConfirmation .= 'Bank Account Number ' . $_SESSION['PaymentCK_BankAccount'] . '</p>';
}
elseif ($_SESSION['PaymentType'] == 'Terms')
{
	$strConfirmation .= 'Purchase Order Number: ' . $_SESSION['PaymentPO_Number'] . '</p>';
}

$strConfirmation .= '<p>&nbsp;</p>';

$strConfirmation .= '<table width="100%" cellspacing="2" cellpadding="0">
<tr style="font-weight:bold;">
	<td width="*">Item</td>
	<td width="80">Price</td>
	<td width="50">Qty</td>
	<td width="90" style="text-align:right;">Total</td>
</tr>';

// GMC - 10/07/10 - Commision Junction Project
$intCJ = 1;
$sCJBEGURL = '<img src="https://www.emjcd.com/u?CID=1518466&TYPE=338667&METHOD=IMG&CURRENCY=USD&OID=' . $_SESSION['OrderID'] . '';
$sCJENDURL = '" height="1" width="20" />';
$sCJOutput = '';

// INITIALIZE CART TABLE
for (reset($_SESSION['cart']); list($key) = each($_SESSION['cart']);)
{
   // GMC - 07/07/09 - To determine that no other item will show with no quantity
   if($_SESSION['cart'][$key] > 0)
   {
	   // EXECUTE SQL QUERY
       $rsCart = mssql_query("SELECT RecordID, ProductName, RetailPrice, CartThumbnail, CartDescription FROM tblProducts WHERE RecordID = " . $key);
       while($rowCart = mssql_fetch_array($rsCart))
	   {
           $decExtendedPrice = number_format($rowCart["RetailPrice"] * $_SESSION['cart'][$key], 2, '.', '');
           $decSubtotal = $decSubtotal + $decExtendedPrice;
           $strConfirmation .= '<tr>';
           $strConfirmation .= '<td>' . $rowCart["CartDescription"] . '</td>';
           $strConfirmation .= '<td>' . convert($rowCart["RetailPrice"],$decExchangeRate,$strCurrencyName) . '</td>';
           $strConfirmation .= '<td>' . $_SESSION['cart'][$key]. '</td>';
           $strConfirmation .= '<td style="text-align:right;">' . convert($decExtendedPrice,$decExchangeRate,$strCurrencyName) . '</td>';
           $strConfirmation .= '</tr>';

           $ItemDesc = str_replace(" ", "-", $rowCart["CartDescription"]); // Replace spaces
           $ItemDesc = str_replace("®", "", $ItemDesc); // Replace "Registered Symbol"
           $ItemDesc = str_replace(".", "-", $ItemDesc); // Replace periods
           $ItemDesc = str_replace("(", "-", $ItemDesc); // Replace (
           $ItemDesc = str_replace(")", "-", $ItemDesc); // Replace )
           $ItemDesc = str_replace("/", "-", $ItemDesc); // Replace /

           // GMC - 10/07/10 - Commision Junction Project
           $sCJOutput .= '&ITEM' . $intCJ . '=' . $ItemDesc . '&AMT' . $intCJ . '=' . $rowCart["RetailPrice"] . '&QTY' . $intCJ . '=' . $_SESSION['cart'][$key] . '';
           $intCJ = $intCJ + 1;
	    }

       // GMC - 11/02/10 - 2010 Holiday Gift Set (RecordID = 288)
       if($key == '288') // Production
       // if($key == '183') // Test
       {
           $resultBun = mssql_query("SELECT Qty, UnitPrice, CartThumbnail, Description FROM tblBundles WHERE ProductID = " . $key);
           while($rowCart = mssql_fetch_array($resultBun))
           {
               $strConfirmation .= '<tr>';
               $strConfirmation .= '<td>' . $rowCart["Description"] . '</td>';
               $strConfirmation .= '<td>' . convert($rowCart["UnitPrice"],$decExchangeRate,$strCurrencyName) . '</td>';
               $strConfirmation .= '<td>' . $rowCart["Qty"] * $_SESSION['cart'][$key] . '</td>';
               $strConfirmation .= '<td style="text-align:right;">' . convert(0,$decExchangeRate,$strCurrencyName) . '</td>';
               $strConfirmation .= '</tr>';
	       }
       }

       // GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
       if ($_SESSION['Promo_Code'] == "MASCARA")
       {
           $strConfirmation .= '<tr>';
           $strConfirmation .= '<td>Mascara by Revitalash - Espresso (Blk/Brwn)</td>';
           $strConfirmation .= '<td>' . convert(0,$decExchangeRate,$strCurrencyName) . '</td>';
           $strConfirmation .= '<td>1</td>';
           $strConfirmation .= '<td style="text-align:right;">' . convert(0,$decExchangeRate,$strCurrencyName) . '</td>';
           $strConfirmation .= '</tr>';
       }

	}
}

$_SESSION['CJ_VALUES'] = $sCJBEGURL . '' . $sCJOutput . '' . $sCJENDURL;

$strConfirmation .= '<tr><td colspan="4">&nbsp;</td></tr>';

$strConfirmation .= '<tr>';
$strConfirmation .= '<td style="font-weight:bold;">Shipping Notes</td>';

// GMC - 06/02/09 - June 2009 Promotion 06/05 to 06/19 - 15% Off on Total Purchased Value (any product)
// GMC - 06/29/09 - July 2009 Promotion "New Beauty" - 15% Off on Total Purchased Value (any product)
// GMC - 09/17/09 - September 2009 Promotion "Pretty City" - 15% Off on Total Purchased Value (any product)
// GMC - 09/24/09 - September 2009 Promotion "Meg" - 15% Off on Total Purchased Value (any product)
// GMC - 10/20/09 - October 2009 Promotion "OK Weekly" - 15% Off on Total Purchased Value (any product)
// GMC - 10/30/09 - October 2009 Promotion "Hair Loss Talk" - 15% Off on Total Purchased Value (any product)
// GMC - 11/12/09 - November 2009 Promotion "Twitter" - 15% Off on Total Purchased Value (any product)
// GMC - 11/16/09 - November 2009 Promotion "Leads" - 15% Off on Total Purchased Value (any product)
// GMC - 11/25/09 - November 2009 Promotion "Remax" - 15% Off on Total Purchased Value (any product)
// GMC - 12/03/09 - December 2009 Promotion "Rome" - 15% Off on Total Purchased Value (any product)
// GMC - 04/28/10 - April 2010 Promotion "Newsletter" - 15% Off on Total Purchased Value (any product)
// GMC - 06/21/10 - June 2010 Promotion "Wahanda" - 15% Off on Total Purchased Value (any product)
// GMC - 07/02/10 - July 2010 Promotion "ISPA" - 20% Off on Total Purchased Value (any product)
// GMC - 07/26/10 - July 2010 Promotion "YANDR" - 15% Off on Total Purchased Value (any product)
// GMC - 08/20/10 - August 2010 Promotion "LSE001" - 15% Off on Total Purchased Value (any product)
// GMC - 09/28/10 - September 2010 Promotion "TWEET" - 15% Off on Total Purchased Value (any product)
// GMC - 09/28/10 - September 2010 Promotion "TUBEIT" - 15% Off on Total Purchased Value (any product)
// GMC - 10/06/10 - October 2010 Promotion "AMERSPA" - 15% Off on Total Purchased Value (any product)
// GMC - 10/18/10 - October 2010 Promotion "LASHES" - 15% Off on Total Purchased Value (any product)
// GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
// GMC - 12/20/10 - December 2010 Promotion "JOURNAL" - 15% Off on Total Purchased Value (any product)
// GMC - 01/17/10 - January 2011 Promotion "COUPON" - 15% Off on Total Purchased Value (any product)
// GMC - 01/18/10 - January 2011 Promotion "ORDER" - 15% Off on Total Purchased Value (any product)
// GMC - 03/01/11 - March 2011 Promotion "LASHWORLD" - 15% Off on Total Purchased Value (any product)
// GMC - 04/04/11 - April 2011 Promotion "CLIQUE" - 15% Off on Total Purchased Value (any product)
// GMC - 06/14/11 - June 2011 Promotion "PKW" - 15% Off on Total Purchased Value (any product)
// GMC - 09/22/11 - September 2011 Promotion "GIFT" - 15% Off on Total Purchased Value (any product)
// GMC - 11/22/11 - November 2011 Promotion "BF2011" - 20% Off on Total Purchased Value (any product)
// GMC - 04/16/12 - April 2012 Promotion "BUZZ" - 15% Off on Total Purchased Value (any product)

// GMC - 09/19/09 - To present a more detailed discount information
// if ($_SESSION['Promo_Code'] == "PEOPLE")
// GMC - 09/10/09 - Friend Promo Code
// if ($_SESSION['Promo_Code'] == "NEWBEAUTY")
// GMC - 10/19/10 - Cancel 15% for CJ
// if ($_SESSION['Promo_Code'] == "NEWBEAUTY" || $_SESSION['Promo_Code'] == "FRIEND" || $_SESSION['Promo_Code'] == "PRETTYCITY" || $_SESSION['Promo_Code'] == "MEG" || $_SESSION['Promo_Code'] == "OKWEEKLY" || $_SESSION['Promo_Code'] == "HAIRLOSSTALK" || $_SESSION['Promo_Code'] == "TWITTER" || $_SESSION['Promo_Code'] == "LEADS" || $_SESSION['Promo_Code'] == "REMAX" || $_SESSION['Promo_Code'] == "ROME" || $_SESSION['Promo_Code'] == "NEWSLETTER" || $_SESSION['Promo_Code'] == "WAHANDA" || $_SESSION['Promo_Code'] == "ISPA" || $_SESSION['Promo_Code'] == "YANDR" || $_SESSION['Promo_Code'] == "LSE001" || $_SESSION['Promo_Code'] == "TWEET" || $_SESSION['Promo_Code'] == "TUBEIT" || $_SESSION['Promo_Code'] == "AMERSPA" || $_SESSION['CJ_VAR'] == "Yes" || $_SESSION['Promo_Code'] == "LASHES")
// if ($_SESSION['Promo_Code'] == "NEWBEAUTY" || $_SESSION['Promo_Code'] == "FRIEND" || $_SESSION['Promo_Code'] == "PRETTYCITY" || $_SESSION['Promo_Code'] == "MEG" || $_SESSION['Promo_Code'] == "OKWEEKLY" || $_SESSION['Promo_Code'] == "HAIRLOSSTALK" || $_SESSION['Promo_Code'] == "TWITTER" || $_SESSION['Promo_Code'] == "LEADS" || $_SESSION['Promo_Code'] == "REMAX" || $_SESSION['Promo_Code'] == "ROME" || $_SESSION['Promo_Code'] == "NEWSLETTER" || $_SESSION['Promo_Code'] == "WAHANDA" || $_SESSION['Promo_Code'] == "ISPA" || $_SESSION['Promo_Code'] == "YANDR" || $_SESSION['Promo_Code'] == "LSE001" || $_SESSION['Promo_Code'] == "TWEET" || $_SESSION['Promo_Code'] == "TUBEIT" || $_SESSION['Promo_Code'] == "AMERSPA" || $_SESSION['Promo_Code'] == "LASHES" || $_SESSION['Promo_Code'] == "MASCARA" || $_SESSION['Promo_Code'] == "JOURNAL" || $_SESSION['Promo_Code'] == "COUPON" || $_SESSION['Promo_Code'] == "ORDER" || $_SESSION['Promo_Code'] == "LASHWORLD" || $_SESSION['Promo_Code'] == "CLIQUE" || $_SESSION['Promo_Code'] == "kwwLIQUE")
// if ($_SESSION['Promo_Code'] == "FRIEND" || $_SESSION['Promo_Code'] == "PRETTYCITY" || $_SESSION['Promo_Code'] == "MEG" || $_SESSION['Promo_Code'] == "OKWEEKLY" || $_SESSION['Promo_Code'] == "HAIRLOSSTALK" || $_SESSION['Promo_Code'] == "TWITTER" || $_SESSION['Promo_Code'] == "LEADS" || $_SESSION['Promo_Code'] == "REMAX" || $_SESSION['Promo_Code'] == "NEWSLETTER" || $_SESSION['Promo_Code'] == "WAHANDA" || $_SESSION['Promo_Code'] == "ISPA" || $_SESSION['Promo_Code'] == "YANDR" || $_SESSION['Promo_Code'] == "LSE001" || $_SESSION['Promo_Code'] == "TWEET" || $_SESSION['Promo_Code'] == "TUBEIT" || $_SESSION['Promo_Code'] == "AMERSPA" || $_SESSION['Promo_Code'] == "LASHES" || $_SESSION['Promo_Code'] == "MASCARA" || $_SESSION['Promo_Code'] == "JOURNAL" || $_SESSION['Promo_Code'] == "COUPON" || $_SESSION['Promo_Code'] == "ORDER" || $_SESSION['Promo_Code'] == "LASHWORLD" || $_SESSION['Promo_Code'] == "CLIQUE" || $_SESSION['Promo_Code'] == "PKW" || $_SESSION['Promo_Code'] == "GIFT" || $_SESSION['Promo_Code'] == "BF2011")
if ($_SESSION['Promo_Code'] == "FRIEND" || $_SESSION['Promo_Code'] == "BUZZ" || $_SESSION['Promo_Code'] == "MEG" || $_SESSION['Promo_Code'] == "OKWEEKLY" || $_SESSION['Promo_Code'] == "HAIRLOSSTALK" || $_SESSION['Promo_Code'] == "TWITTER" || $_SESSION['Promo_Code'] == "LEADS" || $_SESSION['Promo_Code'] == "REMAX" || $_SESSION['Promo_Code'] == "NEWSLETTER" || $_SESSION['Promo_Code'] == "WAHANDA" || $_SESSION['Promo_Code'] == "ISPA" || $_SESSION['Promo_Code'] == "YANDR" || $_SESSION['Promo_Code'] == "LSE001" || $_SESSION['Promo_Code'] == "TWEET" || $_SESSION['Promo_Code'] == "TUBEIT" || $_SESSION['Promo_Code'] == "AMERSPA" || $_SESSION['Promo_Code'] == "LASHES" || $_SESSION['Promo_Code'] == "MASCARA" || $_SESSION['Promo_Code'] == "JOURNAL" || $_SESSION['Promo_Code'] == "COUPON" || $_SESSION['Promo_Code'] == "ORDER" || $_SESSION['Promo_Code'] == "LASHWORLD" || $_SESSION['Promo_Code'] == "CLIQUE" || $_SESSION['Promo_Code'] == "PKW" || $_SESSION['Promo_Code'] == "GIFT" || $_SESSION['Promo_Code'] == "BF2011")
{
    $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold; color:red;">Less 15% Discount ' . $_SESSION['Promo_Code'] . ' Promo Code</td>';
    $strConfirmation .= '<td style="text-align:right; color:red;">' . convert($_SESSION['OrderDiscountValue'],$decExchangeRate,$strCurrencyName) . '</td>';
    $strConfirmation .= '</tr>';
    $strConfirmation .= '<tr>';
    $strConfirmation .= '<td colspan="3" style="text-align:right; font-weight:bold;">Subtotal:</td>';
    $strConfirmation .= '<td style="text-align:right;">' . convert($_SESSION['OrderSubtotal'],$decExchangeRate,$strCurrencyName) . '</td>';
}
else
{
    $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Subtotal:</td>';
    $strConfirmation .= '<td style="text-align:right;">' . convert($_SESSION['OrderSubtotal'],$decExchangeRate,$strCurrencyName) . '</td>';
}

$strConfirmation .= '</tr>';

$strConfirmation .= '<tr>';
$strConfirmation .= '<td rowspan="3">' . $_POST['ShippingNotes'] . '</td>';

// GMC - 10/03/09 - Shipping and Handling = 0 for EU Orders
// GMC - 11/06/10 - Cancel Shipping and Handling = 0 for EU Orders
if($_SESSION['CountryCodeFedExEu_Retail'] != '')
{
    // $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;"><font color="red">Shipping and Handling:<br/>(Included)</font></td>';
    $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Shipping and Handling:</td>';
}

// GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
else if($_SESSION['Promo_Code'] == "MASCARA")
{
    $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;"><font color="red">Shipping and Handling:<br/>(Included)</font></td>';
}

else
{
    // GMC - 10/21/09 - No Shipping - Handling for Order greater than $50.00 (Customers Only)
    // GMC - 02/03/10 - Cancel No Shipping for Order Greater than $50.00 (Customers Only)
    /*
    if($_SESSION['OrderSubtotal'] > 50)
    {
        $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;"><font color="red">Shipping and Handling:<br/>(Included)</font></td>';
    }
    else
    {
        $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Shipping and Handling:</td>';
    }
    */
    
    $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Shipping and Handling:</td>';
}

$strConfirmation .= '<td style="text-align:right;">' . convert($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'],$decExchangeRate,$strCurrencyName) . '</td>';
$strConfirmation .= '</tr>';

$strConfirmation .= '<tr>';

// GMC - 05/06/09 - FedEx Netherlands
if($_SESSION['CountryCodeFedExEu_Retail'] != '')
{
    // GMC - 10/06/09 - Convert Sales Tax Calculation to EU (For all EU including GB by Ernst & Young)
	// $rsFedExEu = mssql_query("SELECT ExchangeRate FROM conCountryCodes WHERE CountryCode = '" . $_SESSION['CountryCodeFedExEu_Retail'] . "'");
	$rsFedExEu = mssql_query("SELECT ExchangeRate FROM conCountryCodes WHERE CountryCode = 'FR'");

    while($rowFedExEu = mssql_fetch_array($rsFedExEu))
	{
        $_SESSION['CountryCodeFedExEuExchangeRate'] = $rowFedExEu["ExchangeRate"];
	}

    // GMC - 10/06/09 - Convert Sales Tax Calculation to EU (For all EU including GB by Ernst & Young)
    /*
    if($_SESSION['CountryCodeFedExEu_Retail'] == 'GB')
    {
        $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">VAT (GBP):</td>';
        $strConfirmation .= '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']) , 2, '.', '') . '</td>';
    }
    else
    {
        $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">VAT (EUR):</td>';
        $strConfirmation .= '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']) , 2, '.', '') . '</td>';
    }
    */
    
    $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">VAT (EUR):</td>';
    $strConfirmation .= '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']) , 2, '.', '') . '</td>';

}
else
{
    $strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Sales Tax:</td>';
    $strConfirmation .= '<td style="text-align:right;">' . convert($_SESSION['OrderTax'],$decExchangeRate,$strCurrencyName) . '</td>';
}

$strConfirmation .= '</tr>';

$strConfirmation .= '<tr>';
$strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Order Total:</td>';
$strConfirmation .= '<td style="text-align:right;">' . convert($_SESSION['OrderTotal'],$decExchangeRate,$strCurrencyName) . '</td>';
$strConfirmation .= '</tr>';
$strConfirmation .= '</table>';

// GMC - 10/25/10 - Disconnect Earnst&Young and VAT Charges for EU Orders - Consumers
// GMC - 05/06/09 - FedEx Netherlands
// GMC - 09/28/09 - Adjust based on Ernst & Young from 17% to 19%
/*
if($_SESSION['CountryCodeFedExEu_Retail'] != '')
{
    $strConfirmation .= '<table>';
    $strConfirmation .= '<tr>';
    $strConfirmation .= '<td>';
    $strConfirmation .= '<div align="center">Ernst & Young VAT Rep BV, A. Vivalditratt 150, 1083 HP, Amsterdam, acts as our general fiscal representative under VAT ID number 0030.25.263.B.01</div>';
    $strConfirmation .= '<br/>';
    $strConfirmation .= '<div align="center">Athena Cosmetics, Inc. 701 N. Green Valley Parkway, Suite 200 Henderson, NV 89074 USA VAT ID number NL 8201.38.113.B.01</div>';
    $strConfirmation .= '</td>';
    $strConfirmation .= '</tr>';
    $strConfirmation .= '</table>';
}
*/

// DISPLAY CART			
echo $strConfirmation;

// GMC - Add Google Tracking by SK (SEO)
$strGoogleTrack = '<script type="text/javascript">';
$strGoogleTrack .= 'try {';
$strGoogleTrack .= 'var pageTracker = _gat._getTracker("UA-5807276-1");';
$strGoogleTrack .= 'pageTracker._trackPageview();';
$strGoogleTrack .= 'pageTracker._addTrans("' . $_SESSION['OrderID'] . '","' . $Address1 . '","' . $_SESSION['OrderTotal'] . '","' . $_SESSION['OrderTax'] . '","' . $_SESSION['OrderShipping'] . '","' . $AddressCity . '","' . $AddressState . '","' . $AddressCountryCode . '");';
$strGoogleTrack .= 'pageTracker._addItem("' . $_SESSION['OrderID'] . '","SKU","Revitalash","Revitalash","' . $_SESSION['OrderTotal'] . '","' . $_SESSION['cart'][$key] . '");';
$strGoogleTrack .= 'pageTracker._trackTrans();';
$strGoogleTrack .= '} catch(err) {}</script>';

echo $strGoogleTrack;

// GMC - 11/26/08 - To Deploy Google Analytics + Thank You Page
// echo '<p style="text-align:center; margin:20px;"><a href="/index.php">Click here to return to the home page.</a></p>';

// GMC - 05/11/10 - Order Completed Information to prevent click on refresh
// echo '<p style="text-align:center; margin:20px;"><a href="/thankyou.php">Click here to finish your purchase.</a></p>';
echo '<p style="text-align:center; margin:20px;"><a href="/thankyou.php">Click here to exit.</a></p>';

/* MAIL SENDER */
if ($strCustomerEMail != '')
{
    $mailrecepient = $strCustomerEMail;
    $mailsubject = 'Revitalash Order Confirmation';
    $mailheader = 'MIME-Version: 1.0' . "\r\n";
    $mailheader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $mailheader .= "From:" . 'sales@revitalash.com' . "\r\n";
    //$mailheader .= 'Bcc: jstancarone@revitalash.com' . "\r\n";

    // GMC - 07/29/09 - Add Linda Peterson to list by JS
    // GMC - 09/24/09 - Present Invoice to Ernst & Young
    // GMC - 10/01/09 - Add Dave Hooper to list by JS
    // GMC - 10/07/09 - Add email address to EU Orders (Holland@revitalash.com)
    // GMC - 07/20/10 - Add ar@revitalash.com at EU orders

    // GMC - 10/25/10 - Disconnect Earnst&Young and VAT Charges for EU Orders - Consumers
    if($_SESSION['CountryCodeFedExEu_Retail'] != '')
    {
        // GMC - 11/23/09 - Take LashGro from email add revitalash1@gmail.com
        // GMC - 07/22/11 - Take lpeterson out
        // GMC - 12/05/11 - Change Gayle email address
        // GMC - 12/15/11 - Change Gayle email address back to what it was
        // GMC - 12/22/11 - Change Gayle email for the third to time to what they wanted on 120511
        // GMC - 02/04/13 - Exchange Group Order List Email Addresses to Replace hard coded values
        // GMC - 02/05/13 - Undo Exchange Group Order List Email Addresses to Replace hard coded values
        // GMC - 04/18/13 - Gayle B New Email
        // GMC - 07/18/13 - Take out Gayle B from email confirmations
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,Holland@revitalash.com,marcel.schellekens@nl.ey.com,lashgro@aol.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,ar@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,Holland@revitalash.com,marcel.schellekens@nl.ey.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,ar@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: ar@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: ar@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: ar@revitalash.com,Orders-D@athanacosmetics.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: ar@revitalash.com,gaylebrinkenhoff@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: ar@revitalash.com,Gayleyy@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        $mailheader .= 'Bcc: ar@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
    }
    else
    {
        // GMC - 11/23/09 - Take LashGro from email add revitalash1@gmail.com
        // GMC - 07/22/11 - Take lpeterson out
        // GMC - 12/05/11 - Change Gayle email address
        // GMC - 12/15/11 - Change Gayle email address back to what it was
        // GMC - 12/22/11 - Change Gayle email for the third to time to what they wanted on 120511
        // GMC - 02/04/13 - Exchange Group Order List Email Addresses to Replace hard coded values
        // GMC - 02/05/13 - Undo Exchange Group Order List Email Addresses to Replace hard coded values
        // GMC - 04/18/13 - Gayle B New Email
        // GMC - 07/18/13 - Take out Gayle B from email confirmations
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,lashgro@aol.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: gayleb@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: gaylebrinkenhoff@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: Orders-D@athanacosmetics.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: gaylebrinkenhoff@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        // $mailheader .= 'Bcc: Gayleyy@revitalash.com,dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
        $mailheader .= 'Bcc: dhooper@revitalash.com,revitalash1@gmail.com,jstancarone@revitalash.com,gmarrufo@unimerch.com' . "\r\n";
    }

    mail($mailrecepient, $mailsubject, $strConfirmation, $mailheader);
}

// GMC - 10/07/10 - Commision Junction Project
// RESET SESSION VARIABLES
/*
foreach ($_SESSION as $key => $value)
{
	if ($key != 'CustomerIsLoggedIn' && $key != 'CustomerTypeID' && $key != 'CustomerID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress')
		unset($_SESSION[$key]);
}
*/

?>
