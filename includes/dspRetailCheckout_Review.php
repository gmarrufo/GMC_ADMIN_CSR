<p style="font-size:14px; font-weight:bold;">Order Summary - Please Review</p>

<?php

// ATTEMPT CONNECTION TO DATABASE SERVER
$conn = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $conn);

$decSubtotal = 0;

// INITIALIZE CART TABLE AND LOOP THROUGH TO POPULATE
$cart_table = '';

// print_r($key);
// var_dump($key);

for (reset($_SESSION['cart']); list($key) = each($_SESSION['cart']);)
{
   // GMC - 07/07/09 - To determine that no other item will show with no quantity
   // GMC - 06/17/11 - Add Alt for Images
   if($_SESSION['cart'][$key] > 0)
   {
       // EXECUTE SQL QUERY
       $result = mssql_query("SELECT RecordID, ProductName, RetailPrice, CartThumbnail, CartDescription FROM tblProducts WHERE RecordID = " . $key);
       while($row = mssql_fetch_array($result))
       {
	       $decExtendedPrice = number_format($row["RetailPrice"] * $_SESSION['cart'][$key], 2, '.', '');
           $decSubtotal = $decSubtotal + $decExtendedPrice;
           $cart_table .= '<tr>';
           $cart_table .= '<td><img src="/images/products/cart/' . $row["CartThumbnail"] . '" alt="RecordId (' . $key . ')" /></td>';
           $cart_table .= '<td>' . $row["CartDescription"] . '</td>';
           $cart_table .= '<td>' . convert($row["RetailPrice"],$decExchangeRate,$strCurrencyName) . '</td>';
           $cart_table .= '<td>' . $_SESSION['cart'][$key]. '</td>';
           $cart_table .= '<td style="text-align:right;">' . convert($decExtendedPrice,$decExchangeRate,$strCurrencyName) . '</td>';
           $cart_table .= '</tr>';
	   }

       // GMC - 11/02/10 - 2010 Holiday Gift Set (RecordID = 288)
       if($key == '288') // Production
       // if($key == '183') // Test
       {
           $resultBun = mssql_query("SELECT Qty, UnitPrice, CartThumbnail, Description FROM tblBundles WHERE ProductID = " . $key);
           while($row = mssql_fetch_array($resultBun))
           {
               $cart_table .= '<tr>';
               $cart_table .= '<td><img src="/images/products/cart/' . $row["CartThumbnail"] . '" /></td>';
               $cart_table .= '<td>' . $row["Description"] . '</td>';
               $cart_table .= '<td>' . convert($row["UnitPrice"],$decExchangeRate,$strCurrencyName) . '</td>';
               $cart_table .= '<td>' . $row["Qty"] * $_SESSION['cart'][$key] . '</td>';
               $cart_table .= '<td style="text-align:right;">' . convert(0,$decExchangeRate,$strCurrencyName) . '</td>';
               $cart_table .= '</tr>';
	       }
       }

       // GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
       if ($_SESSION['Promo_Code'] == "MASCARA")
       {
           $cart_table .= '<tr>';
           $cart_table .= '<td><img src="/images/products/cart/MascaraEspresso_325.jpg" /></td>';
           $cart_table .= '<td>Mascara by Revitalash - Espresso (Blk/Brwn)</td>';
           $cart_table .= '<td>' . convert(0,$decExchangeRate,$strCurrencyName) . '</td>';
           $cart_table .= '<td>1</td>';
           $cart_table .= '<td style="text-align:right;">' . convert(0,$decExchangeRate,$strCurrencyName) . '</td>';
           $cart_table .= '</tr>';
       }

   }
}

echo '<form action="/retail/checkout.php" method="post">
<table width="100%" cellspacing="10" cellpadding="0">
<tr style="font-weight:bold;">
	<td width="100">&nbsp;</td>
	<td width="*">Item</td>
	<td width="80">Price</td>
	<td width="80">Qty</td>
	<td width="80" style="text-align:right;">Total</td>
</tr>';
			
// DISPLAY CART			
echo $cart_table;

echo '<tr><td colspan="5">&nbsp;</td></tr>';

echo '<tr>';
echo '<td style="font-weight:bold;">Shipping Notes</td>';

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
// if ($_SESSION['Promo_Code'] == "FRIEND" || $_SESSION['Promo_Code'] == "PRETTYCITY" || $_SESSION['Promo_Code'] == "MEG" || $_SESSION['Promo_Code'] == "OKWEEKLY" || $_SESSION['Promo_Code'] == "HAIRLOSSTALK" || $_SESSION['Promo_Code'] == "TWITTER" || $_SESSION['Promo_Code'] == "LEADS" || $_SESSION['Promo_Code'] == "REMAX" || $_SESSION['Promo_Code'] == "NEWSLETTER" || $_SESSION['Promo_Code'] == "WAHANDA" || $_SESSION['Promo_Code'] == "ISPA" || $_SESSION['Promo_Code'] == "YANDR" || $_SESSION['Promo_Code'] == "LSE001" || $_SESSION['Promo_Code'] == "TWEET" || $_SESSION['Promo_Code'] == "TUBEIT" || $_SESSION['Promo_Code'] == "AMERSPA" || $_SESSION['Promo_Code'] == "LASHES" || $_SESSION['Promo_Code'] == "MASCARA" || $_SESSION['Promo_Code'] == "JOURNAL" || $_SESSION['Promo_Code'] == "COUPON" || $_SESSION['Promo_Code'] == "ORDER" || $_SESSION['Promo_Code'] == "LASHWORLD" || $_SESSION['Promo_Code'] == "CLIQUE" || $_SESSION['Promo_Code'] == "PKW" || $_SESSION['Promo_Code'] == "GIFT" || $_SESSION['Promo_Code'] == "BF2011")
if ($_SESSION['Promo_Code'] == "FRIEND" || $_SESSION['Promo_Code'] == "BUZZ" || $_SESSION['Promo_Code'] == "MEG" || $_SESSION['Promo_Code'] == "OKWEEKLY" || $_SESSION['Promo_Code'] == "HAIRLOSSTALK" || $_SESSION['Promo_Code'] == "TWITTER" || $_SESSION['Promo_Code'] == "LEADS" || $_SESSION['Promo_Code'] == "REMAX" || $_SESSION['Promo_Code'] == "NEWSLETTER" || $_SESSION['Promo_Code'] == "WAHANDA" || $_SESSION['Promo_Code'] == "ISPA" || $_SESSION['Promo_Code'] == "YANDR" || $_SESSION['Promo_Code'] == "LSE001" || $_SESSION['Promo_Code'] == "TWEET" || $_SESSION['Promo_Code'] == "TUBEIT" || $_SESSION['Promo_Code'] == "AMERSPA" || $_SESSION['Promo_Code'] == "LASHES" || $_SESSION['Promo_Code'] == "MASCARA" || $_SESSION['Promo_Code'] == "JOURNAL" || $_SESSION['Promo_Code'] == "COUPON" || $_SESSION['Promo_Code'] == "ORDER" || $_SESSION['Promo_Code'] == "LASHWORLD" || $_SESSION['Promo_Code'] == "CLIQUE" || $_SESSION['Promo_Code'] == "PKW" || $_SESSION['Promo_Code'] == "GIFT" || $_SESSION['Promo_Code'] == "BF2011")
{
    echo '<td colspan="3" style="text-align:right; font-weight:bold; color:red;">Less 15% Discount ' . $_SESSION['Promo_Code'] . ' Promo Code</td>';
    echo '<td style="text-align:right; color:red;">' . convert($_SESSION['OrderDiscountValue'],$decExchangeRate,$strCurrencyName) . '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td colspan="4" style="text-align:right; font-weight:bold;">Subtotal:</td>';
    echo '<td style="text-align:right;" font-weight:bold;>' . convert($_SESSION['OrderSubtotal'],$decExchangeRate,$strCurrencyName) . '</td>';
}
else
{
    echo '<td colspan="3" style="text-align:right; font-weight:bold;">Subtotal:</td>';
    echo '<td style="text-align:right;">' . convert($_SESSION['OrderSubtotal'],$decExchangeRate,$strCurrencyName) . '</td>';
}

echo '</tr>';

echo '<tr>';
echo '<td colspan="2" rowspan="4"><textarea name="ShippingNotes" rows="3" cols="30"></textarea></td>';

// GMC - 10/03/09 - Shipping and Handling = 0 for EU Orders
// GMC - 11/06/10 - Cancel Shipping and Handling = 0 for EU Orders
if($_SESSION['CountryCodeFedExEu_Retail'] != '')
{
    // echo '<td colspan="2" style="text-align:right; font-weight:bold;"><font color="red">Shipping and Handling:<br/>(Included)</font></td>';
    echo '<td colspan="2" style="text-align:right; font-weight:bold;">Shipping and Handling:</td>';
}

// GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product)
else if($_SESSION['Promo_Code'] == "MASCARA")
{
    echo '<td colspan="2" style="text-align:right; font-weight:bold;"><font color="red">Shipping and Handling:<br/>(Included)</font></td>';
}

else
{
    // GMC - 10/21/09 - No Shipping - Handling for Order greater than $50.00 (Customers Only)
    // GMC - 02/03/10 - Cancel No Shipping for Order Greater than $50.00 (Customers Only)
    /*
    if($_SESSION['OrderSubtotal'] > 50)
    {
         echo '<td colspan="2" style="text-align:right; font-weight:bold;"><font color="red">Shipping and Handling:<br/>(Included)</font></td>';
    }
    else
    {
         echo '<td colspan="2" style="text-align:right; font-weight:bold;">Shipping and Handling:</td>';
    }
    */

    echo '<td colspan="2" style="text-align:right; font-weight:bold;">Shipping and Handling:</td>';
}

echo '<td style="text-align:right;">' . convert($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'],$decExchangeRate,$strCurrencyName) . '</td>';
echo '</tr>';

echo '<tr>';

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
        echo '<td colspan="2" style="text-align:right; font-weight:bold;">VAT (GBP):</td>';
        echo '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']) , 2, '.', '') . '</td>';
    }
    else
    {
        echo '<td colspan="2" style="text-align:right; font-weight:bold;">VAT (EUR):</td>';
        echo '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']) , 2, '.', '') . '</td>';
    }
    */

    echo '<td colspan="2" style="text-align:right; font-weight:bold;">VAT (EUR):</td>';
    echo '<td style="text-align:right;">' . number_format(($_SESSION['OrderTax']/$_SESSION['CountryCodeFedExEuExchangeRate']) , 2, '.', '') . '</td>';

}
else
{
    echo '<td colspan="2" style="text-align:right; font-weight:bold;">Sales Tax:</td>';
    echo '<td style="text-align:right;">' . convert($_SESSION['OrderTax'],$decExchangeRate,$strCurrencyName) . '</td>';
}

echo '</tr>';

echo '<tr>';
echo '<td colspan="2" style="text-align:right; font-weight:bold;">Order Total:</td>';
echo '<td style="text-align:right;">' . convert($_SESSION['OrderTotal'],$decExchangeRate,$strCurrencyName) . '</td>';
echo '</tr>';

echo '</table>';

echo '<hr width="100%" size="1" color="#000000" noshade="noshade" />';

echo '<table width="100%" cellspacing="10" cellpadding="0">';

echo '<tr>
	<td width="48%" style="font-weight:bold;">Customer Information</td>
	<td width="*">&nbsp;</td>
 
    <!-- GMC - 01/13/14 - Block the option to edit the Shipping Address -->
	<!--<td width="48%" style="font-weight:bold;">Shipping Information (<a href="/retail/checkout.php?EditShipping">Edit</a>)</td>-->
	<td width="48%" style="font-weight:bold;">Shipping Information</td>

     </tr>';
	
echo '<tr>
	<td>' . $CustomerName . '</td>
	<td>&nbsp;</td>
	<td>' . $ShipToName . '</td>
	</tr>';
	
echo '<tr>
	<td>' . $CustomerAddress . '</td>
	<td>&nbsp;</td>
	<td>' . $ShipToAddress . '</td>
	</tr>';
	
echo '<tr>
	<td>' . $CustomerCityStateZIP . '</td>
	<td>&nbsp;</td>
	<td>' . $ShipToCityStateZIP . '</td>
	</tr>';
	
echo '<tr>
	<td>' . $CustomerCountryCode . '</td>
	<td>&nbsp;</td>
	<td>' . $ShipToCountryCode . '</td>
	</tr>';
	
echo '<tr>
	<td>' . $CustomerTelephone . '</td>
	<td>&nbsp;</td>
	<td>Ship Via ' . $ShipMethod . '</td>
	</tr>';
	
echo '<tr>
	<td colspan="3">&nbsp;</td>
	</tr>';
	
if ($_SESSION['PaymentType'] == 'CreditCard')
{
	echo '<tr>
	<td colspan="3" style="font-weight:bold;">Payment Method: Credit Card (<a href="/retail/checkout.php?EditPayment">Edit</a>)</td>
	</tr>';
	
	if (substr($_SESSION['PaymentCC_Number'], 0, 1) == 3)
	{
		echo '<tr>
		<td colspan="2">American Express ending in ' . substr($_SESSION['PaymentCC_Number'], -4, 4) . '</td>
		<td>&nbsp;</td>
		</tr>';
	}
	elseif (substr($_SESSION['PaymentCC_Number'], 0, 1) == 4)
	{
		echo '<tr>
		<td colspan="2">Visa ending in ' . substr($_SESSION['PaymentCC_Number'], -4, 4) . '</td>
		<td>&nbsp;</td>
		</tr>';
	}
	elseif (substr($_SESSION['PaymentCC_Number'], 0, 1) == 5)
	{
		echo '<tr>
		<td colspan="2">Master Card ending in ' . substr($_SESSION['PaymentCC_Number'], -4, 4) . '</td>
		<td>&nbsp;</td>
		</tr>';
	}
	elseif (substr($_SESSION['PaymentCC_Number'], 0, 1) == 6)
	{
		echo '<tr>
		<td colspan="2">Discover ending in ' . substr($_SESSION['PaymentCC_Number'], -4, 4) . '</td>
		<td>&nbsp;</td>
		</tr>';
	}
	
	echo '<tr>
	<td colspan="2">Expiration ' . $_SESSION['PaymentCC_ExpMonth'] . '/' . $_SESSION['PaymentCC_ExpYear'] . '</td>
	<td>&nbsp;</td>
	</tr>';

}
elseif ($_SESSION['PaymentType'] == 'ECheck')
{	
	echo '<tr>                                                                                                                          g
	<td colspan="3" style="font-weight:bold;">Payment Method: Electronic Check (<a href="/pro/checkout.php?EditPayment">Edit</a>)</td>
	</tr>';

	echo '<tr>
	<td colspan="2">' . $_SESSION['PaymentCK_BankName'] . ' account ending in ' . substr($_SESSION['PaymentCK_BankAccount'], -4, 4) . '</td>
	<td>&nbsp;</td>
	</tr>';
	
	echo '<tr>
	<td colspan="2">Routing Number: ' . $_SESSION['PaymentCK_BankRouting'] . '</td>
	<td>&nbsp;</td>
	</tr>';
	
	echo '<tr>
	<td colspan="2">Account Holder Name: ' . $_SESSION['PaymentCK_AccountName'] . '</td>
	<td>&nbsp;</td>
	</tr>';
}

elseif ($_SESSION['PaymentType'] == 'Terms')
{	
	echo '<tr>
	<td colspan="3" style="font-weight:bold;">Payment Method: Terms (<a href="/pro/checkout.php?EditPayment">Edit</a>)</td>
	</tr>';
}

echo '<tr>
	<td colspan="3" align="center">&nbsp;</td>
	</tr>';

// GMC - 12/06/10 - To Warn the users not to click refresh, submit or back button
echo '<tr>
	<td colspan="3" align="center"><font color="red"><strong>PLEASE DO NOT CLICK "PROCESS ORDER" MORE THAN ONCE, THE BACK BUTTON OR REFRESH PAGE</strong></font></td>
	</tr>';

// GMC - 11/19/08 - To Discontinue use of Agree Terms
// echo '<tr><td colspan="3" align="center">I have read the <a href="/legal-notices.php" target="_blank">complete terms and conditions</a> and agree to them. You must select "Yes" to continue.</td></tr>';
// echo '<tr><td colspan="3" align="center"><input name="AgreeToTerms" type="radio" value="No" checked="checked" /> NO, I do not agree to the Terms and Conditions. &nbsp; <input name="AgreeToTerms" type="radio" value="Yes" /> YES, I agree to the Terms and Conditions</td></tr>';
echo '<tr>
	<td colspan="3" align="right"><input type="submit" name="cmdProcess" value="Process Order" class="formSubmit" style="margin-right:60px;" /></td>
	</tr>';

echo '</table></form>';
?>
