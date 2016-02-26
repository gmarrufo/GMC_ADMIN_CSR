<p style="font-size:14px; font-weight:bold;">Order Summary - Please Review</p>

<?php

// ATTEMPT CONNECTION TO DATABASE SERVER
$conn = mssql_connect($dbServer, $dbUser, $dbPass)
  or die("Couldn't connect to SQL Server on $dbServer");

// SET CONNECTION TO REVITALASH DB
$selected = mssql_select_db($dbName, $conn)
  or die("Couldn't open database $dbName");

$decSubtotal = 0;

// INITIALIZE CART TABLE
$cart_table = '';

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
		
		$cart_table .= '<tr>';
		$cart_table .= '<td><img src="/images/products/cart/' . $row["CartThumbnail"] . '" /></td>';
		$cart_table .= '<td>' . $row["CartDescription"] . '</td>';
		$cart_table .= '<td>' . convert($decUnitPrice,$decExchangeRate,$strCurrencyName) . '</td>';
		$cart_table .= '<td>' . $_SESSION['cart'][$key]. '</td>';
		$cart_table .= '<td style="text-align:right;">' . convert($decExtendedPrice,$decExchangeRate,$strCurrencyName) . '</td>';
		$cart_table .= '</tr>';

		// GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness
		if($_SESSION['BreastCancerAwarenessPromo_Pro'] >= 12)
		{
            // Show Revitalash
            $cart_table .= '<tr>';
			$cart_table .= '<td><img src="/images/products/cart/revitalash.gif" /></td>';
			$cart_table .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
			$cart_table .= '<td>0.00</td>';
			$cart_table .= '<td>' . $_SESSION['cart'][$key]. '</td>';
			$cart_table .= '<td>0.00</td>';
			$cart_table .= '<td>&nbsp;</td>';
			$cart_table .= '</tr>';

            // Show Mascara
            $cart_table .= '<tr>';
			$cart_table .= '<td><img src="/images/products/cart/mascara.gif" /></td>';
			$cart_table .= '<td>Mascara by Revitalash&reg;</td>';
			$cart_table .= '<td>0.00</td>';
			$cart_table .= '<td>' . $_SESSION['cart'][$key]. '</td>';
			$cart_table .= '<td>0.00</td>';
			$cart_table .= '<td>&nbsp;</td>';
			$cart_table .= '</tr>';
		}

		// GMC - 11/02/09 - 2009 Holiday Gift Box Set
		if($_SESSION['HolidayGiftBoxSet2009Promo_Pro'] > 0)
		{
            // Show Revitalash
            $cart_table .= '<tr>';
			$cart_table .= '<td><img src="/images/products/cart/revitalash.gif" /></td>';
			$cart_table .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
			$cart_table .= '<td>0.00</td>';
			$cart_table .= '<td>' . $_SESSION['HolidayGiftBoxSet2009Promo_Pro'] . '</td>';
			$cart_table .= '<td>0.00</td>';
			$cart_table .= '<td>&nbsp;</td>';
			$cart_table .= '</tr>';
		}

        // GMC 01/05/10 - Valentine's Day 2010 Promotion
        if($_SESSION['ValentinesDay2010Promo_Pro'] > 0)
        {
            if ($blnIsInternational == 0)
            {
                // Show Revitalash
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/revitalash.gif" /></td>';
                $cart_table .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';

                // Show Candle Gift
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/candle.png" /></td>';
                $cart_table .= '<td>Candle Gift</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';

                // Show Valentine Day Promotion Bag
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/holidaygiftbag2008.jpg" /></td>';
                $cart_table .= '<td>Valentine Day Promotion Bag</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';
            }
            else
            {
                // Show Revitalash
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/revitalash.gif" /></td>';
                $cart_table .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';

                // Show Compact Mirror Gift
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/mirror.gif" /></td>';
                $cart_table .= '<td>Compact Mirror Gift</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';

                // Show Valentine Day Promotion Bag
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/holidaygiftbag2008.jpg" /></td>';
                $cart_table .= '<td>Valentine Day Promotion Bag</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['ValentinesDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';
            }
        }

        // GMC - 03/16/10 Mother's Day 2010 Promotiom
        if($_SESSION['MothersDay2010Promo_Pro'] > 0)
        {
            if ($blnIsInternational == 0)
            {
                // Show Revitalash
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/revitalash.gif" /></td>';
                $cart_table .= '<td>Revitalash&reg; Eyelash Conditioner</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['MothersDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';

                // Show Mascara
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/mascara.gif" /></td>';
                $cart_table .= '<td>Mascara</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['MothersDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';
            }
            else
            {
                // Show Revitalash
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/revitalash.gif" /></td>';
                $cart_table .= '<td>Revitalash&reg; FR-Canadian</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['MothersDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';

                // Show Mascara
                $cart_table .= '<tr>';
			    $cart_table .= '<td><img src="/images/products/cart/mascara.gif" /></td>';
                $cart_table .= '<td>Mascara FR-Canadian</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>' . $_SESSION['MothersDay2010Promo_Pro'] . '</td>';
                $cart_table .= '<td>0.00</td>';
                $cart_table .= '<td>&nbsp;</td>';
                $cart_table .= '</tr>';
            }
        }
	}
}

echo '<form action="/pro/checkout.php" method="post">
<table width="100%" cellspacing="5" cellpadding="0">
<tr style="font-weight:bold;">
	<td width="100">&nbsp;</td>
	<td width="*">Item</td>
	<td width="70">Price</td>
	<td width="70">Qty</td>
	<td width="80" style="text-align:right;">Total</td>
</tr>';
			
// DISPLAY CART			
echo $cart_table;

echo '<tr><td colspan="5">&nbsp;</td></tr>';

echo '<tr>';
echo '<td style="font-weight:bold;">Shipping Notes</td>';
echo '<td colspan="3" style="text-align:right; font-weight:bold;">Subtotal:</td>';
echo '<td style="text-align:right;">$' . convert($_SESSION['OrderSubtotal'],$decExchangeRate,$strCurrencyName) . '</td>';
echo '</tr>';

// GMC - 01/03/12 - Promo Code for Resellers
// GMC - 09/12/13 - Cancel Promo Code for Resellers
// GMC - 02/02/14 - Put Back Promo Code for Resellers
echo '<tr>';
echo '<td colspan="4" style="text-align:right; font-weight:bold;">Promo Code:</td>';
echo '<td style="text-align:right;">' . $_SESSION['Promo_Code'] . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="4" style="text-align:right; font-weight:bold;">Promo Code Discount:</td>';
echo '<td style="text-align:right;">$' . convert($_SESSION['OrderDiscountValue'],$decExchangeRate,$strCurrencyName) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="2" rowspan="3"><textarea name="ShippingNotes" rows="3" cols="30"></textarea></td>';
echo '<td colspan="2" style="text-align:right; font-weight:bold;">Shipping and Handling:</td>';
echo '<td style="text-align:right;">$' . convert($_SESSION['OrderShipping'] + $_SESSION['OrderHandling'],$decExchangeRate,$strCurrencyName) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="2" style="text-align:right; font-weight:bold;">Sales Tax:</td>';
echo '<td style="text-align:right;">$' . convert($_SESSION['OrderTax'],$decExchangeRate,$strCurrencyName) . '</td>';
echo '</tr>';

echo '<tr>';
echo '<td colspan="2" style="text-align:right; font-weight:bold;">Order Total:</td>';
echo '<td style="text-align:right;">$' . convert($_SESSION['OrderTotal'],$decExchangeRate,$strCurrencyName) . '</td>';
echo '</tr>';

echo '</table>';

echo '<hr width="100%" size="1" color="#000000" noshade="noshade" />';

echo '<table width="100%" cellspacing="5" cellpadding="0">';

echo '<tr>
	<td width="48%" style="font-weight:bold;">Customer Information</td>
	<td width="*">&nbsp;</td>
	<td width="48%" style="font-weight:bold;">Shipping Information (<a href="/pro/checkout.php?EditShipping">Edit</a>)</td>
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
	<td colspan="3" style="font-weight:bold;">Payment Method: Credit Card (<a href="/pro/checkout.php?EditPayment">Edit</a>)</td>
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
	echo '<tr>
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
	
	echo '<tr>
	<td colspan="2">Purchase Order #' . $_SESSION['PaymentPO_Number'] . '</td>
	<td>&nbsp;</td>
	</tr>';
}

echo '<tr>
	<td colspan="3" align="right"><input type="submit" name="cmdProcess" value="Process Order" class="formSubmit" style="margin-right:60px;" /></td>
	</tr>';

echo '</table></form>';
?>
