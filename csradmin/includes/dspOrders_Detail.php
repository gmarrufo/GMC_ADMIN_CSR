<!-- GMC - Word Invoice in the middle -->
<h1>Order <?php echo $OrderID; ?> Details&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
*** INVOICE ***</h1>

<!-- GMC - 07/29/09 - Date and Time show by JS
<p>Order Date: <?//php echo date("F d, Y", strtotime($OrderDate)); ?></p>
-->

<p>Order Date: <?php echo $OrderDate; ?></p>

<p>Order Status: <?php echo $OrderStatus; ?></p>

<!-- GMC - 12/03/08 - Domestic vs International 3rd Phase -->
<p>Entered By: <?php echo $EnteredBy; ?></p>

<!-- GMC - 04/13/09 - Payment Type -->
<p>Payment Type: <?php echo $PaymentType; ?></p>

<!-- GMC - 02/01/11 - Order Closed By CSR ADMIN Partner - Rep -->
<p>Order Closed By: <?php echo $OrderClosedBy; ?></p>

<table width="100%" cellpadding="0" cellspacing="10">

<tr style="font-weight:bold;">
	<td width="50%">Customer Information</td>
    <td width="50%">Shipping Information</td>
</tr>

<tr>
	<td style="vertical-align:top;">
    <?php
    
    // GMC - 01/20/09 - Present CustomerID in Order Detail
    echo $CustomerID . '<br />';
    
    // GMC - 02/18/09 - CustomerTypeID to Show in Order Detail
    if($CustomerTypeID == 1)
    {
       echo 'Consumer<br />';
    }
    else if($CustomerTypeID == 2)
    {
       echo 'Reseller<br />';
    }
    else if($CustomerTypeID == 3)
    {
       echo 'Distributor<br />';
    }

    // GMC - 02/18/09 - RevitalashID to Show in Order Detail
    echo $RevitalashID . '<br />';

    echo $FirstName . ' ' . $LastName . '<br />';
	if (strLen($CompanyName) > 1 && $CompanyName != 'Individual')
		echo $CompanyName . '<br />';	
	echo $Address1 . '<br />';
	if (strLen($Address2) > 1)
		echo $Address2 . '<br />';
	echo $City . ' ' . $State . ' ' . $PostalCode . ' ' . $CountryCode . '<br />';
	echo $Telephone;
    ?>
    </td>
    <td style="vertical-align:top;">
    <?php
    echo $sAttn . '<br />';	
	echo $sAddress1 . '<br />';
	if (strLen($sAddress2) > 1)
		echo $sAddress2 . '<br />';
	echo $sCity . ' ' . $sState . ' ' . $sPostalCode . ' ' . $sCountryCode . '<br />&nbsp;<br />';
	echo 'Ship Via: ' . $ShippingMethod;
	if ($OrderTracking != '')
	{
		if (substr($ShippingMethod, 0, 3) == 'UPS')
			echo '<br />UPS Tracking: <a target="_blank" href="http://wwwapps.ups.com/tracking/tracking.cgi?tracknum=' . $OrderTracking . '">' . $OrderTracking . '</a>';
		elseif (substr($ShippingMethod, 0, 5) == 'FedEx')
			echo '<br />FedEx Tracking: <a target="_blank" href="http://www.fedex.com/Tracking/Detail?ftc_start_url=&totalPieceNum=&backTo=&template_type=print&cntry_code=us&language=english&trackNum=' . $OrderTracking . '&pieceNum=">' . $OrderTracking . '</a>';
		else
			echo '<br />Tracking: ' . $OrderTracking;
    }
	?>
    </td>
</tr>
            
</table>

<?php

	if ($PaymentType == 'CreditCard')
		echo '<p>Payment: Credit Card ending in ' . substr($CCNumber, -4, 4) . '</p>';
	elseif ($PaymentType == 'ECheck')
		echo '<p>Payment: Checking Account ending in ' . substr($CKBankAccount, -4, 4) . '</p>';
	if ($PaymentType == 'Terms')
		echo '<p>Payment: Purchase Order Number ' . $PONumber . '</p>';

?>

<hr width="100%" align="center" size="1" color="#000000" noshade="noshade" />

<table width="100%" cellpadding="0" cellspacing="10">

<tr style="font-weight:bold;">
	<td width="*">Item</td>
    <td width="100">Location</td>
    <td width="300">Tracking</td>
    <td width="50">Qty</td>
    <td width="75">Price</td>
    <td width="75">Extended</td>
</tr>

<?php echo $tblOrderItems; ?>

<tr><td colspan="6">&nbsp;</td>

<tr>
    <!-- GMC - 12/22/08 - Order Notes to Show in Order Detail-->
    <td colspan="2">Notes: <?php echo $OrderNotes ?></td>
    
<!-- GMC - 09/20/09 - To show discount details at the Order Detail Level -->
<!-- Eventually needs to be put into a modular code -->
<!-- GMC - 09/24/09 - September 2009 Promotion "Meg" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 10/20/09 - October 2009 Promotion "OK Weekly" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 10/30/09 - October 2009 Promotion "Hair Loss Talk" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 11/12/09 - November 2009 Promotion "Twitter" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 11/16/09 - November 2009 Promotion "Leads" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 11/25/09 - November 2009 Promotion "Remax" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 12/03/09 - December 2009 Promotion "Rome" - 15% Off on Total Purchased Value (any product) -->
<?php

if($PromoCode != "")
{
    if($PromoCode == "NEWBEAUTY" || $PromoCode == "FRIEND" || $PromoCode == "PRETTYCITY" || $PromoCode == "MEG" || $PromoCode == "OKWEEKLY" || $PromoCode == "HAIRLOSSTALK" || $PromoCode == "TWITTER" || $PromoCode == "LEADS" || $PromoCode == "REMAX" || $PromoCode == "ROME") // Shopping Cart 15%
    {
        // GMC - 09/21/09 - Fix Bug in Detail Discount
        echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.85), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 15% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.85)* .15), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }
    else if($PromoCode == "FTBD") // CSRADmin 20%
    {
        // GMC - 09/21/09 - Fix Bug in Detail Discount
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.80), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 20% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.80)* .20), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    // GMC - 10/17/11 - FTB10 Promotion Code 10%
    else if($PromoCode == "FTB10")
    {
        // GMC - 09/21/09 - Fix Bug in Detail Discount
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.90), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 10% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.90)* .10), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    // GMC - 07/12/11 - PKW Promotion Code 15%
    else if($PromoCode == "PKW")
    {
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.85), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 15% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.85)* .15), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    // GMC - 07/21/11 - AMERSPA Promotion Code 15%
    else if($PromoCode == "AMERSPA")
    {
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.85), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 15% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.85)* .15), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    // GMC - 02/14/12 - LASH Promotion Code 15%
    else if($PromoCode == "LASH")
    {
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.85), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 15% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.85)* .15), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    // GMC - 02/03/14 - LASHLOVE Promotion Code 15%
    else if($PromoCode == "LASHLOVE")
    {
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.85), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 15% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.85)* .15), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    // GMC - 07/31/12 - NAT10 - DIST10 - DIST15 Promo Codes
    else if($PromoCode == "NAT10")
    {
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.90), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 10% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.90)* .10), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    else if($PromoCode == "DIST10")
    {
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.90), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 10% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.90)* .10), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    else if($PromoCode == "DIST15")
    {
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.85), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 15% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.85)* .15), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    // GMC - 04/17/14 - OBAGI Promotion Code 25%
    else if($PromoCode == "OBAGI")
    {
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Original Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format(($OrderSubtotal/.75), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold; color:red;">Less 25% Discount ' . $PromoCode . ' Promo Code</td>';
        echo '<td style="text-align:right; font-weight:bold; color:red;">$' . number_format((($OrderSubtotal/.75)* .25), 2, '.', '') . '</td>';
        echo '</tr>';
        echo '<tr>';
 	    echo '<td colspan="7" style="text-align:right; font-weight:bold;">New Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }

    else
    {
 	    echo '<td colspan="5" style="text-align:right; font-weight:bold;">Subtotal:</td>';
        echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
    }
}
else
{
 	echo '<td colspan="5" style="text-align:right; font-weight:bold;">Subtotal:</td>';
    echo '<td style="text-align:right;">$' . number_format($OrderSubtotal, 2, '.', '') . '</td>';
}
    
?>

</tr>

<tr>
    <!-- GMC - 12/22/08 - Order Notes to Show in Order Detail-->
    <td colspan="2">&nbsp;</td>
	<td colspan="5" style="text-align:right; font-weight:bold;">Shipping &amp; Handling:</td>

    <!-- GMC - 09/27/11 - To control NO CHARGE in Order Details -->
    <?php
    if ($PaymentType == 'NOCHARGE')
    {
    ?>
    <td style="text-align:right;">$<?php echo number_format(0.00, 2, '.', '') ?></td>
    <?php
    }
    else
    {
    ?>
    <td style="text-align:right;">$<?php echo number_format($OrderShipping, 2, '.', '') ?></td>
    <?php
    }
    ?>

</tr>

<?php

// GMC - 09/23/09 - Show FedEx Netherlands
if($ShowFedExEU == "Yes")
{
    echo '<tr>';
    // GMC - 12/22/08 - Order Notes to Show in Order Detail
    echo '<td colspan="2">&nbsp;</td>';
	echo '<td colspan="5" style="text-align:right; font-weight:bold;">VAT:</td>';
    echo '<td style="text-align:right;">$' . number_format($OrderTax, 2, '.', '') . '</td>';
    echo '</tr>';
}
else
{
    echo '<tr>';
    // GMC - 12/22/08 - Order Notes to Show in Order Detail
    echo '<td colspan="2">&nbsp;</td>';
	echo '<td colspan="5" style="text-align:right; font-weight:bold;">Sales Tax:</td>';
    echo '<td style="text-align:right;">$' . number_format($OrderTax, 2, '.', '') . '</td>';
    echo '</tr>';
}

?>

<tr>
    <!-- GMC - 12/22/08 - Order Notes to Show in Order Detail-->
    <td colspan="2">&nbsp;</td>
	<td colspan="5" style="text-align:right; font-weight:bold;">Total:</td>
    <td style="text-align:right;">$<?php echo number_format($OrderTotal, 2, '.', '') ?></td>
</tr>

</table>

<?php

// GMC - 09/23/09 - Show FedEx Netherlands
// GMC - 09/28/09 - Adjust based on Ernst & Young from 17% to 19%
if($ShowFedExEU == "Yes")
{
    echo '<br />';
    echo '<table>';
    echo '<tr>';
    echo '<td><font color="red"><b>Ernst & Young VAT Rep BV, A. Vivalditratt 150, 1083 HP, Amsterdam, acts as our general fiscal representative under VAT ID number 0030.25.263.B.01<br/>Athena Cosmetics, Inc. 701 N. Green Valley Parkway, Suite 200 Henderson, NV 89074 USA VAT ID number NL 8201.38.113.B.01</b></font></td>';
    echo '</tr>';
    echo '</table>';
}

?>

<p>&nbsp;</p>
