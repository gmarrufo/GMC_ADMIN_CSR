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
	$strCompanyName = $rowGetCustomer["CompanyName"];
	$strCustomerEMail = $rowGetCustomer["EMailAddress"];
	
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

$strConfirmation = '<h1>Order Confirmation: Web Order ' . $_SESSION['OrderID'] . '</h1>';

$strConfirmation .= '<p>Thank you for your recent order at revitalash.com.</p>';

$strConfirmation .= '<table width="100%" cellspacing="2" cellpadding="0">';

$strConfirmation .= '<tr>
        <th width="140" style="text-align:left;">Name:</th>
        <td width="*">' . $strCustomerFirstName . ' ' . $strCustomerLastName . '</td>
    </tr>
    
    <tr>
        <th style="text-align:left;">Company:</th>
        <td>' . $strCompanyName . '</td>
    </tr>';
	
$strConfirmation .= '</table>

<p>&nbsp;</p>

<p><span style="font-weight:bold;">Shipping Information</span><br />';

// GMC - 05/25/10 - Proper Shipping Information at Confirmation
// GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
$strConfirmation .= $CompanyName . '<br />';
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
	<td width="70">Price</td>
	<td width="60">Qty</td>
	<td width="90" style="text-align:right;">Total</td>
</tr>';

// INITIALIZE CART TABLE

for (reset($_SESSION['cart']); list($key) = each($_SESSION['cart']);)
{
	// EXECUTE SQL QUERY
	$result = mssql_query("SELECT RecordID, CategoryID, ProductName, RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, ResellerFreeTrigger, DistributorFreeTrigger, CartThumbnail, CartDescription FROM tblProducts WHERE RecordID = " . $key);
	while($row = mssql_fetch_array($result))
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

            // GMC - 05/29/12 - SUMMER2012 Web Reseller Promotion June 2012
            $rs = mssql_query("SELECT RetailPrice, ResellerPrice, DistributorPrice, InternationalSurcharge, CategoryID FROM tblProducts WHERE RecordID = " . $key);
            while($row = mssql_fetch_array($rs))
            {
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
                }
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
		
		$decSubtotal = $decSubtotal + $decExtendedPrice;
		
		$strConfirmation .= '<tr>';
		$strConfirmation .= '<td>' . $row["CartDescription"] . '</td>';
		$strConfirmation .= '<td>' . convert($decUnitPrice,$decExchangeRate,$strCurrencyName) . '</td>';
		$strConfirmation .= '<td>' . $_SESSION['cart'][$key]. '</td>';
		$strConfirmation .= '<td style="text-align:right;">' . convert($decExtendedPrice,$decExchangeRate,$strCurrencyName) . '</td>';
		$strConfirmation .= '</tr>';
		
		// GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
		if($_SESSION['BreastCancerAwarenessPromo_Pro'] >= 12)
		{
            // Show Revitalash
		    $strConfirmation .= '<tr>';
		    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
		    $strConfirmation .= '<td>0.00</td>';
		    $strConfirmation .= '<td>' . $_SESSION['cart'][$key]. '</td>';
		    $strConfirmation .= '<td style="text-align:right;">0.00</td>';
		    $strConfirmation .= '</tr>';

            // Show Mascara
		    $strConfirmation .= '<tr>';
		    $strConfirmation .= '<td>Mascara by Revitalash&reg;</td>';
		    $strConfirmation .= '<td>0.00</td>';
		    $strConfirmation .= '<td>' . $_SESSION['cart'][$key]. '</td>';
		    $strConfirmation .= '<td style="text-align:right;">0.00</td>';
		    $strConfirmation .= '</tr>';
		}

		// GMC - 11/02/09 - 2009 Holiday Gift Box Set
		if($_SESSION['HolidayGiftBoxSet2009Promo_Pro'] > 0)
		{
            // Show Revitalash
		    $strConfirmation .= '<tr>';
		    $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
		    $strConfirmation .= '<td>0.00</td>';
		    $strConfirmation .= '<td>' . $_SESSION['HolidayGiftBoxSet2009Promo_Pro'] . '</td>';
		    $strConfirmation .= '<td style="text-align:right;">0.00</td>';
		    $strConfirmation .= '</tr>';
		}

        // GMC 01/05/10 - Valentine's Day 2010 Promotion
        if($_SESSION['ValentinesDay2010Promo_Pro'] > 0)
        {
            if ($blnIsInternational == 0)
            {
                // Show Revitalash
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';

                // Show Candle Gift
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Candle Gift</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';

                // Show Valentine Day Promotion Bag
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Valentine Day Promotion Bag</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';
            }
            else
            {
                // Show Revitalash
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';

                // Show Compact Mirror Gift
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Compact Mirror Gift</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';
                
                // Show Valentine Day Promotion Bag
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Valentine Day Promotion Bag</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';
            }
        }

        // GMC - 03/16/10 Mother's Day 2010 Promotiom
        if($_SESSION['MothersDay2010Promo_Pro'] > 0)
        {
            if ($blnIsInternational == 0)
            {
                // Show Revitalash
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['MothersDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';

                // Show Mascara
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Mascara</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['MothersDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';
            }
            else
            {
                // Show Revitalash
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Revitalash&reg; FR-Canadian</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['MothersDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';

                // Show Mascara
                $strConfirmation .= '<tr>';
                $strConfirmation .= '<td>Mascara FR-Canadian</td>';
                $strConfirmation .= '<td>0.00</td>';
                $strConfirmation .= '<td>' . $_SESSION['MothersDay2010Promo_Pro'] . '</td>';
                $strConfirmation .= '<td style="text-align:right;">0.00</td>';
                $strConfirmation .= '</tr>';
            }
        }
	}
}

$strConfirmation .= '<tr><td colspan="4">&nbsp;</td></tr>';

$strConfirmation .= '<tr>';
$strConfirmation .= '<td style="font-weight:bold;">Shipping Notes</td>';
$strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Subtotal:</td>';
$strConfirmation .= '<td style="text-align:right;">$' . convert($_SESSION['OrderSubtotal'],$decExchangeRate,$strCurrencyName) . '</td>';
$strConfirmation .= '</tr>';

// GMC - 01/03/12 - Promo Code for Resellers
// GMC - 09/12/13 - Cancel Promo Code for Resellers
// GMC - 02/02/14 - Put Back Promo Code for Resellers
$strConfirmation .= '<tr>';
$strConfirmation .= '<td colspan="3" style="text-align:right; font-weight:bold;">Promo Code:</td>';
$strConfirmation .= '<td style="text-align:right;">' . $_SESSION['Promo_Code'] . '</td>';
$strConfirmation .= '</tr>';

$strConfirmation .= '<tr>';
$strConfirmation .= '<td colspan="3" style="text-align:right; font-weight:bold;">Promo Code Discount:</td>';
$strConfirmation .= '<td style="text-align:right;">$' . convert($_SESSION['OrderDiscountValue'],$decExchangeRate,$strCurrencyName) . '</td>';
$strConfirmation .= '</tr>';

$strConfirmation .= '<tr>';
$strConfirmation .= '<td rowspan="3">' . $_POST['ShippingNotes'] . '</td>';
$strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Shipping and Handling:</td>';
$strConfirmation .= '<td style="text-align:right;">$' . convert($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'],$decExchangeRate,$strCurrencyName) . '</td>';
$strConfirmation .= '</tr>';

$strConfirmation .= '<tr>';
$strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Sales Tax:</td>';
$strConfirmation .= '<td style="text-align:right;">$' . convert($_SESSION['OrderTax'],$decExchangeRate,$strCurrencyName) . '</td>';
$strConfirmation .= '</tr>';

$strConfirmation .= '<tr>';
$strConfirmation .= '<td colspan="2" style="text-align:right; font-weight:bold;">Order Total:</td>';
$strConfirmation .= '<td style="text-align:right;">$' . convert($_SESSION['OrderTotal'],$decExchangeRate,$strCurrencyName) . '</td>';
$strConfirmation .= '</tr>';

$strConfirmation .= '</table>';

// DISPLAY CART			
echo $strConfirmation;
echo '<p style="text-align:center; margin:20px;"><a href="/index.php">Click here to return to the home page</a>.</p>';

/* MAIL SENDER */
if ($strCustomerEMail != '')
{
	$mailrecepient = $strCustomerEMail;
	$mailsubject = 'Revtalash Order Confirmation';
	$mailheader = 'MIME-Version: 1.0' . "\r\n";
	$mailheader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$mailheader .= "From:" . 'sales@revitalash.com' . "\r\n";
	//$mailheader .= 'Bcc: jstancarone@revitalash.com' . "\r\n";
    // GMC - 07/29/09 - Add Linda Peterson to list by JS
    // GMC - 11/23/09 - Take LashGro from email add revitalash1@gmail.com
    // GMC - 07/22/11 - Take lpeterson out
    // GMC - 12/05/11 - Change Gayle email address
    // GMC - 12/15/11 - Change Gayle email address back to what it was
    // GMC - 12/22/11 - Change Gayle email for the third to time to what they wanted on 120511
    // GMC - 04/18/13 - Gayle B New Email
    // GMC - 07/18/13 - Take out Gayle B from email confirmations
	// $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,gayleb@revitalash.com,lashgro@aol.com' . "\r\n";
	// $mailheader .= 'Bcc: lpetersonsawyer@revitalash.com,gayleb@revitalash.com,revitalash1@gmail.com' . "\r\n";
	// $mailheader .= 'Bcc: gayleb@revitalash.com,revitalash1@gmail.com' . "\r\n";
	// $mailheader .= 'Bcc: gaylebrinkenhoff@revitalash.com,revitalash1@gmail.com' . "\r\n";
	// $mailheader .= 'Bcc: Gayleyy@revitalash.com,revitalash1@gmail.com' . "\r\n";
	$mailheader .= 'Bcc: revitalash1@gmail.com' . "\r\n";
	mail($mailrecepient, $mailsubject, $strConfirmation, $mailheader);
}

// RESET SESSION VARIABLES
foreach ($_SESSION as $key => $value)
{
	if ($key != 'IsProLoggedIn' && $key != 'CustomerTypeID' && $key != 'CustomerID' && $key != 'FirstName' && $key != 'LastName' && $key != 'CompanyName' && $key != 'EMailAddress' && $key != 'CustomerTerms' && $key != 'IsInternational')
		unset($_SESSION[$key]);
}
?>
