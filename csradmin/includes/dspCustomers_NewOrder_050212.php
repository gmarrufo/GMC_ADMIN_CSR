<?php

// GMC - 03/26/12 - MediaKit Process
if($_SESSION['OrderType'] == 'MediaKit')
{
    echo '<h1>New Order (Media Kit or Media Kit Thank You)</h1>';
}
else
{
    echo '<h1>New Order (Standard)</h1>';
}

// GMC - 05/06/09 - FedEx Netherlands
$_SESSION['CountryCodeFedExEu'] = '';
$_SESSION['CustomerTypeIDFedExEu'] = '';
$_SESSION['CountryCodeFedExEuExchangeRate'] = '';
$_SESSION['ShipMethodIDFedExEu'] = '';

// GMC - 07/27/09 - To avoid PromoCode Error in process_order.php
$_SESSION['Promo_Code'] = "";
$_SESSION['BreastCancerAwarenessPromo_Pro'] = 0;

// GMC - 09/05/09 - Promotion Section - Drop Down for CSR's Only
$_SESSION['Promo_Code_Discount'] = 0;
$_SESSION['Promo_Code_Description'] = '';

// GMC - 10/21/09 - To avoid Error in process_order.php
$_SESSION['CountryCodeFedExEu_Retail'] = '';
$_SESSION['CustomerTypeID'] = '';

// GMC - 11/02/09 - To avoid PromoCode Error in process_order.php
$_SESSION['HolidayGiftBoxSet2009Promo_Pro'] = 0;

// GMC - 11/18/09 - 2009 Holiday Gift Box Set - Extend to Distributor
$_SESSION['CountryCode_2009_Distributor_HolidayGiftBox'] = '';

// GMC 01/05/10 - Valentine's Day 2010 Promotion
// GMC - 01/05/10 - To avoid PromoCode Error in process_order.php
$_SESSION['ValentinesDay2010Promo_Pro'] = 0;

// GMC - 03/16/10 Mother's Day 2010 Promotiom
// GMC - 03/16/10 - To avoid PromoCode Error in process_order.php
$_SESSION['MothersDay2010Promo_Pro'] = 0;

// GMC - 07/20/10 - Reseller EU has reseller number no VAT charge
$_SESSION['ResellerEUHasNumber'] = '';

// GMC - 10/24/10 - To avoid PromoCode Error in process_order.php
$_SESSION['CJ_VAR'] = '';

// GMC - 11/02/10 - 2010 Holiday Gift Set (RecordID = 288) - To avoid PromoCode Error in process_order.php
$_SESSION['2010_Holiday_Gift_Set'] = '';

// GMC - 06/10/11 - NAV Item 498 - 593 and 594 - 12 + 2 Free from 062011 to 090111
// GMC - 06/10/11 - To avoid PromoCode Error in process_order.php
// GMC - 08/27/11 - Deactivate NAV Item 498 - 593 and 594 - 12 + 2 Free from 062011 to 090111
// $_SESSION['AdvancedFormula2011Promo_Pro'] = 0;
// $_SESSION['AdvancedFormula2011Promo_Item'] = 0;

// GMC - 08/28/11 - NAV Item 593 and 417 Get 12 + 1 plus multiples of 13 free effective 090111
// GMC - 08/28/11 - To avoid PromoCode Error in process_order.php
// GMC - 09/29/11 - NAV Item 593 and 417 Get 12 + 1 plus multiples of 13 free effective 090111 - Cancel Promo
// $_SESSION['PinkBag13FreePromo_Pro'] = 0;

// GMC - 03/26/12 - MediaKit Process
if($_SESSION['OrderType'] == 'MediaKit')
{
    $_SESSION['PaymentType'] = "NOCHARGE";
}

if ($_SESSION['UserTypeID'] == 1)
	echo '<p style="margin:10px; text-align:center; font-style:italic;"><font color="red"><strong>To make changes to this customer record, you must fax changes to 888-371-3171.</strong></font></p>';

if (isset($pageerror))
{
	echo '<p class="error">' . $pageerror . '</p>';
}

echo '<table width="900" cellpadding="2" cellspacing="0" style="margin:10px;">';

// POPULATE CUSTOMER DATA           
while($rowGetCustomer = mssql_fetch_array($qryGetCustomer))
{
    $intCustomerType = $rowGetCustomer["CustomerTypeID"];
	$blnIsApprovedTerms = $rowGetCustomer["IsApprovedTerms"];
	$_SESSION['CustomerTerms'] = $blnIsApprovedTerms;

	if ($rowGetCustomer["CountryCode"] == 'US')
		$blnIsInternational = 0;
	else
		$blnIsInternational = 1;
	
	echo '<tr>
        <th width="140" style="text-align:left;">Company:</th>
        <td width="*">' . $rowGetCustomer["CompanyName"] . '</td>
    </tr>
	
	<tr>
        <th style="text-align:left;">Contact Name:</th>
        <td>' . $rowGetCustomer["FirstName"] . ' ' . $rowGetCustomer["LastName"] . '</td>
    </tr>
    
	<tr>
        <th style="text-align:left;">Country:</th>
        <td>' . $rowGetCustomer["CountryCode"] . '</td>
    </tr>';
	
	if ($intCustomerType == 1)
	{
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Consumer</td></tr>';
		
        // GMC - 05/06/09 - FedEx Netherlands
        $_SESSION['CustomerTypeIDFedExEu'] = 1;
	}
	elseif ($intCustomerType == 2)
	{
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Spa/Reseller</td></tr>';

        // GMC - 07/14/11 - Distributors Change CSRADMIN
		echo '<tr><th style="text-align:left;">Reseller/VAT ID:</th><td>' . $rowGetCustomer["ResellerNumber"] . '</td></tr>';

        // GMC - 05/06/09 - FedEx Netherlands
        $_SESSION['CustomerTypeIDFedExEu'] = 2;
	}
	elseif ($intCustomerType == 3)
	{
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Distributor</td></tr>';
		
        // GMC - 05/06/09 - FedEx Netherlands
        $_SESSION['CustomerTypeIDFedExEu'] = 3;
    }

	// GMC - 03/08/09 - Customer Type "REP"
	elseif ($intCustomerType == 4)
	{
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Rep</td></tr>';
		
        // GMC - 05/06/09 - FedEx Netherlands
        $_SESSION['CustomerTypeIDFedExEu'] = 4;
	}
}

// INITIALIZE DEFAULT SESSION VARIABLES
if (!isset($_SESSION['FORMItemID1']))
{
	$_SESSION['FORMItemID1'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID1'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT1'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation1'] = 'MAIN';
	$_SESSION['FORMItemStockLocation1'] = 'ATHENA-LV';

    $_SESSION['FORMItemQty1'] = '';
	
	$_SESSION['FORMItemID2'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID2'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT2'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation2'] = 'MAIN';
	$_SESSION['FORMItemStockLocation2'] = 'ATHENA-LV';

    $_SESSION['FORMItemQty2'] = '';
	
	$_SESSION['FORMItemID3'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID3'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT3'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation3'] = 'MAIN';
	$_SESSION['FORMItemStockLocation3'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty3'] = '';
	
	$_SESSION['FORMItemID4'] = 0;
 
    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID4'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT4'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation4'] = 'MAIN';
	$_SESSION['FORMItemStockLocation4'] = 'ATHENA-LV';
 
	$_SESSION['FORMItemQty4'] = '';
	
	$_SESSION['FORMItemID5'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID5'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT5'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation5'] = 'MAIN';
	$_SESSION['FORMItemStockLocation5'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty5'] = '';
	
	$_SESSION['FORMItemID6'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID6'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT6'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation6'] = 'MAIN';
	$_SESSION['FORMItemStockLocation6'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty6'] = '';
	
	$_SESSION['FORMItemID7'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID7'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT7'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation7'] = 'MAIN';
	$_SESSION['FORMItemStockLocation7'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty7'] = '';
	
	$_SESSION['FORMItemID8'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID8'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT8'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	//$_SESSION['FORMItemStockLocation8'] = 'MAIN';
	$_SESSION['FORMItemStockLocation8'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty8'] = '';
	
	$_SESSION['FORMItemID9'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID9'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT9'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation9'] = 'MAIN';
	$_SESSION['FORMItemStockLocation9'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty9'] = '';
	
	$_SESSION['FORMItemID10'] = 0;
 
    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID10'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT10'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
    // $_SESSION['FORMItemStockLocation10'] = 'MAIN';
    $_SESSION['FORMItemStockLocation10'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty10'] = '';

    // GMC - 03/18/10 - Add 10 Line Items Admin

    $_SESSION['FORMItemID11'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID11'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT11'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation11'] = 'MAIN';
	$_SESSION['FORMItemStockLocation11'] = 'ATHENA-LV';

    $_SESSION['FORMItemQty11'] = '';

	$_SESSION['FORMItemID12'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID12'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT12'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation12'] = 'MAIN';
	$_SESSION['FORMItemStockLocation12'] = 'ATHENA-LV';

    $_SESSION['FORMItemQty12'] = '';

	$_SESSION['FORMItemID13'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID13'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT13'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation13'] = 'MAIN';
	$_SESSION['FORMItemStockLocation13'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty13'] = '';

	$_SESSION['FORMItemID14'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID14'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT14'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation14'] = 'MAIN';
	$_SESSION['FORMItemStockLocation14'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty14'] = '';

	$_SESSION['FORMItemID15'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID15'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT15'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation15'] = 'MAIN';
	$_SESSION['FORMItemStockLocation15'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty15'] = '';

	$_SESSION['FORMItemID16'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID16'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT16'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation16'] = 'MAIN';
	$_SESSION['FORMItemStockLocation16'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty16'] = '';

	$_SESSION['FORMItemID17'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID17'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT17'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation7'] = 'MAIN';
	$_SESSION['FORMItemStockLocation17'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty17'] = '';

	$_SESSION['FORMItemID18'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID18'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT18'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	//$_SESSION['FORMItemStockLocation18'] = 'MAIN';
	$_SESSION['FORMItemStockLocation18'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty18'] = '';

	$_SESSION['FORMItemID19'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID19'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT19'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
	// $_SESSION['FORMItemStockLocation19'] = 'MAIN';
	$_SESSION['FORMItemStockLocation19'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty19'] = '';

	$_SESSION['FORMItemID20'] = 0;

    // GMC - 08/16/11 - To divide Products and Marketing Materials
	$_SESSION['FORMItemMID20'] = 0;

    // GMC - 03/26/12 - MediaKit Process
	$_SESSION['FORMItemMKT20'] = 0;

    // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
    // $_SESSION['FORMItemStockLocation20'] = 'MAIN';
    $_SESSION['FORMItemStockLocation20'] = 'ATHENA-LV';

	$_SESSION['FORMItemQty20'] = '';

}

echo '</table>';

?>

<form action="/csradmin/customers.php?Action=NewOrder&CustomerID=<?php echo $intCustomerID; ?>" method="post">

<table width="100%" cellpadding="5" cellspacing="0">

<tr>

    <!-- GMC - 11/16/08 - Domestic Vs. International 2nd Phase -->
    <!--<td width="30">&nbsp;</td>-->
    <!--<input type="radio" name="ShippingAddress" value="UseExisting" checked="checked" />-->

    <!-- GMC - 11/18/08 - Eliminate Miscellaneous (Edit Customer + Select Shipping Address) -->
    <th width="180">Shipping Address:</th>

    <td width="*">

    <!-- GMC - 11/16/08 - Domestic Vs. International 2nd Phase
    <select name="ShipToID" size="1">
    <?
    /*
    php
    while($row = mssql_fetch_array($qryGetCustomerShipTo))
    {
		if (isset($_SESSION['CustomerShipToID']) && $_SESSION['CustomerShipToID'] == $row["RecordID"])
		{
			echo '<option value="' . $row["RecordID"] . '">' . $row["Attn"] . ' - ' . $row["Address1"] . ' - ' . $row["Address2"] . ' - ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . ' ' . $row["CountryCode"] . '</option>';
        }
        else
        {
        	echo '<option selected="selected" value="' . $row["RecordID"] . '">' . $row["Attn"] . ' - ' . $row["Address1"] . ' - ' . $row["Address2"] . ' - ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . ' ' . $row["CountryCode"] . '</option>';
         }
    }
    */
    ?>
    </select>
    -->

    <?php
    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
    while($row = mssql_fetch_array($qryGetCustomerShipTo))
    {
		if (isset($_SESSION['CustomerShipToID']) && $_SESSION['CustomerShipToID'] == $row["RecordID"])
		{
			echo '' . $row["RecordID"] . ' - ' .  $row["CompanyName"] . ' - ' . $row["Attn"] . ' - ' . $row["Address1"] . ' - ' . $row["Address2"] . ' - ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . ' ' . $row["CountryCode"] . '';
			
            // GMC - 05/06/09 - FedEx Netherlands
            $_SESSION['CountryCodeFedExEu'] = $row["CountryCode"];
        }
        else
        {
			echo '' . $row["RecordID"] . ' - ' . $row["CompanyName"] . ' - ' . $row["Attn"] . ' - ' . $row["Address1"] . ' - ' . $row["Address2"] . ' - ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . ' ' . $row["CountryCode"] . '';
			
            // GMC - 05/06/09 - FedEx Netherlands
            $_SESSION['CountryCodeFedExEu'] = $row["CountryCode"];
        }
    }
    ?>

    </td>
    
</tr>

<?php

// GMC - 05/06/09 - FedEx Netherlands
$qryGetCountryCodeEU = mssql_query("SELECT IsEU FROM conCountryCodes WHERE CountryCode  = '" . $_SESSION['CountryCodeFedExEu'] . "'");

while($row = mssql_fetch_array($qryGetCountryCodeEU))
{
    if($row["IsEU"] == 0)
    {
        $_SESSION['CountryCodeFedExEu'] = '';
    }
}

?>

<hr width="900" size="2" color="#000000" noshade="noshade" align="left" style="margin-left:10px;" />

<table width="100%" cellpadding="5" cellspacing="0">

<tr>
    <th colspan="2">PO Number:</th>
    <td><input type="text" name="PO_Number" size="10" value="" /></td>
</tr>

<!-- GMC - 07/14/11 - Distributors Change CSRADMIN -->
<?php
   echo '<tr>
         <td width="30">&nbsp;</td>
         <th width="140">Distributor Code:</th>
         <td width="*">
         <select name="Distributor_Code" size="1">
         <option value="0">SELECT BELOW</option>
        ';

        $cboDistributorCodes = mssql_query("SELECT DistributorName FROM tblDistributors order by DisplayOrder ASC");

        while($row = mssql_fetch_array($cboDistributorCodes))
        {
		 if (isset($_SESSION['Distributor_Code']) && $_SESSION['Distributor_Code'] == $row["DistributorName"])
			echo '<option selected="selected" value="' . $row["DistributorName"] . '">' . $row["DistributorName"] . '</option>';
		 else
			echo '<option value="' . $row["DistributorName"] . '">' . $row["DistributorName"] . '</option>';
        }

   echo '</select>
        </td>
        </tr>
        ';
?>

<tr>
    <td width="30">&nbsp;</td>
    <th width="140">Expected Ship Date:</th>
    <td width="*"><input type="text" name="Promised_Date" size="16" value="<?php if (isset($_SESSION['Promised_Date'])) echo $_SESSION['Promised_Date']; ?>" />&nbsp;&nbsp;<font color="red"><strong>MM/DD/YYYY</strong></font></td>
</tr>

<tr>
    <td><input type="radio" name="PaymentType" value="CreditCard" checked="checked" />
    <td colspan="2">Pay By Credit Card</td>
</tr>

<tr>
    <td width="30">&nbsp;</td>
    <th width="140">Credit Card Number:</th>
    <td width="*"><input type="text" name="CC_Number" size="16" value="<?php if (isset($_SESSION['PaymentCC_Number'])) echo $_SESSION['PaymentCC_Number']; ?>" /></td>
</tr>

<tr>
    <th colspan="2">Cardholder Name:</th>
    <td><input type="text" name="CC_Cardholder" size="25" value="<?php if (isset($_SESSION['PaymentCC_Cardholder'])) echo $_SESSION['PaymentCC_Cardholder']; ?>" /></td>
</tr>

<tr>
    <th colspan="2">Expiration:</th>
    <td><select name="CC_ExpMonth" size="1">
    <option value="01"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 01) echo ' selected="selected"'; ?>>01 - JAN</option>
    <option value="02"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 02) echo ' selected="selected"'; ?>>02 - FEB</option>
    <option value="03"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 03) echo ' selected="selected"'; ?>>03 - MAR</option>
    <option value="04"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 04) echo ' selected="selected"'; ?>>04 - APR</option>
    <option value="05"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 05) echo ' selected="selected"'; ?>>05 - MAY</option>
    <option value="06"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 06) echo ' selected="selected"'; ?>>06 - JUN</option>
    <option value="07"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 07) echo ' selected="selected"'; ?>>07 - JUL</option>
    <option value="08"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 08) echo ' selected="selected"'; ?>>08 - AUG</option>
    <option value="09"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 09) echo ' selected="selected"'; ?>>09 - SEP</option>
    <option value="10"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 10) echo ' selected="selected"'; ?>>10 - OCT</option>
    <option value="11"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 11) echo ' selected="selected"'; ?>>11 - NOV</option>
    <option value="12"<?php if (isset($_SESSION['PaymentCC_ExpMonth']) && $_SESSION['PaymentCC_ExpMonth'] == 12) echo ' selected="selected"'; ?>>12 - DEC</option>
    </select> <select name="CC_ExpYear" size="1">

    <!-- GMC - 09/18/09 - To calculate the Promo Discount Value per Item -->
    <!-- GMC - 02/11/10 - Fix Expiration Year 2009 -->
    <!-- GMC - 01/03/11 - Fix Expiration Year 2010 -->
    <!-- GMC - 02/01/12 - Fix Expiration Year 2011 -->
    <!--<option value="08"<?php// if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 08) echo ' selected="selected"'; ?>>2008</option>-->
    <!--<option value="09"<?php// if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 09) echo ' selected="selected"'; ?>>2009</option>-->
    <!--<option value="10"<?php// if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 10) echo ' selected="selected"'; ?>>2010</option>-->
    <!--<option value="11"<?php// if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 11) echo ' selected="selected"'; ?>>2011</option>-->
    <option value="12"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 12) echo ' selected="selected"'; ?>>2012</option>
    <option value="13"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 13) echo ' selected="selected"'; ?>>2013</option>
    <option value="14"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 14) echo ' selected="selected"'; ?>>2014</option>
    <option value="15"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 15) echo ' selected="selected"'; ?>>2015</option>
    <option value="16"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 16) echo ' selected="selected"'; ?>>2016</option>
    <option value="17"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 17) echo ' selected="selected"'; ?>>2017</option>
    <option value="18"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 18) echo ' selected="selected"'; ?>>2018</option>
    <option value="19"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 19) echo ' selected="selected"'; ?>>2019</option>
    <option value="20"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 20) echo ' selected="selected"'; ?>>2020</option>
    <option value="21"<?php if (isset($_SESSION['PaymentCC_ExpYear']) && $_SESSION['PaymentCC_ExpYear'] == 21) echo ' selected="selected"'; ?>>2021</option>
    </select></td>
</tr>

<tr>
    <th colspan="2">Security Code:</th>
    <td><input type="text" name="CC_CVV" size="3" value="<?php if (isset($_SESSION['PaymentCC_CVV'])) echo $_SESSION['PaymentCC_CVV']; ?>" /></td>
</tr>

<tr>
    <th colspan="2">Billing Postal Code:</th>
    <td><input type="text" name="CC_BillingPostalCode" size="10" value="<?php if (isset($_SESSION['PaymentCC_BillingPostalCode'])) echo $_SESSION['PaymentCC_BillingPostalCode']; ?>" /></td>
</tr>

<!-- GMC - 01/03/09 - To Hide Swiped Authorization Code from Reps -->
<?php
if ($_SESSION['UserTypeID'] == 1)
{
   echo '<tr><th colspan="2">&nbsp;</th><td>&nbsp;</td></tr>';
   echo '<input type="hidden" name="CC_SwipedAuthorization" value="">';
}
else
{
   echo '<tr><th colspan="2">Swiped Authorization Code:</th><td>';

   if (isset($_SESSION['PaymentCC_SwipedAuth']))
   {
      echo '<input type="text" name="CC_SwipedAuthorization" size="10" value="';
      echo $_SESSION['PaymentCC_SwipedAuth'];
      echo '" />';
   }
   else
   {
      echo '<input type="text" name="CC_SwipedAuthorization" size="10" value="" />';
   }

   echo '</td></tr>';
}
?>

<!-- GMC - 01/19/09 - To accomodate MMARTINEZ to use ECheck -->
<!-- GMC - 11/11/08 - To accomodate HCORDOVA - ATOBLER - JCHAVELLA to use ECheck-->

<?php

// GMC - 05/05/09 - No more ECheck
/*
if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 60)
{
 echo '<tr>';
 echo '<td>';

 if ((isset($_SESSION['PaymentType'])) && ($_SESSION['PaymentType'] == 'ECheck'))
 {
  echo '<input type="radio" name="PaymentType" value="ECheck" checked="checked"/>';
 }
 else
 {
  echo '<input type="radio" name="PaymentType" value="ECheck"/>';
 }

 echo '<td colspan="2">Pay By Electronic Check</td>';
 echo '</tr>';
 echo '<tr>';
 echo '<th colspan="2">Account Type:</th>';
 echo '<td>';

 if (isset($_SESSION['PaymentCK_AccountType']) && $_SESSION['PaymentCK_AccountType'] == 1)
 {
 echo '<select name="CK_AccountType" size="1">';
 echo '<option value="1" selected="selected">Personal Check</option>';
 echo '<option value="2">Personal Savings</option>';
 echo '</select>';
 }
 else if (isset($_SESSION['PaymentCK_AccountType']) && $_SESSION['PaymentCK_AccountType'] == 2)
 {
 echo '<select name="CK_AccountType" size="1">';
 echo '<option value="1">Personal Check</option>';
 echo '<option value="2" selected="selected">Personal Savings</option>';
 echo '</select>';
 }
 else
 {
 echo '<select name="CK_AccountType" size="1">';
 echo '<option value="1" >Personal Check</option>';
 echo '<option value="2" >Personal Savings</option>';
 echo '</select>';
 }

 echo '</td>';
 echo '</tr>';
 echo '<tr>';
 echo '<th colspan="2">Bank Name:</th>';
 echo '<td>';

 if (isset($_SESSION['PaymentCK_BankName']))
 {
  echo '<input type="text" name="CK_BankName" size="30" value="';
  echo $_SESSION['PaymentCK_BankName'];
  echo '" />';
 }
 else
 {
  echo '<input type="text" name="CK_BankName" size="30" value="" />';
 }

 echo '</td>';
 echo '</tr>';
 echo '<tr>';
 echo '<th colspan="2">Name As On Check:</th>';
 echo '<td>';

 if (isset($_SESSION['PaymentCK_AccountName']))
 {
 echo '<input type="text" name="CK_AccountName" size="30" value="';
 echo $_SESSION['PaymentCK_AccountName'];
 echo '" />';
 }
 else
 {
 echo '<input type="text" name="CK_AccountName" size="30" value="" />';
 }
 echo '</td>';
 echo '</tr>';
 echo '<tr>';
 echo '<th colspan="2">ABA Routing Number:</th>';
 echo '<td>';

 if (isset($_SESSION['PaymentCK_BankRouting']))
 {
 echo '<input type="text" name="CK_BankRouting" size="9" value="';
 echo $_SESSION['PaymentCK_BankRouting'];
 echo '" />';
 }
 else
 {
 echo '<input type="text" name="CK_BankRouting" size="9" value="" />';
 }
 echo '</td>';
 echo '</tr>';
 echo '<tr>';
 echo '<th colspan="2">Account Number:</th>';
 echo '<td>';

 if (isset($_SESSION['PaymentCK_BankAccount']))
 {
 echo '<input type="text" name="CK_BankAccount" size="10" value="';
 echo $_SESSION['PaymentCK_BankAccount'];
 echo '" />';
 }
 else
 {
 echo '<input type="text" name="CK_BankAccount" size="10" value="" />';
 }
 echo '</td>';
 echo '</tr>';
}
*/

?>

<?php

if ($blnIsApprovedTerms == 1 && $_SESSION['UserTypeID'] != 1)
{
    if ((isset($_SESSION['PaymentType'])) && ($_SESSION['PaymentType'] == 'Terms'))
	{
		echo '<tr>
		<td><input type="radio" name="PaymentType" value="Terms" checked="checked" />
		<td colspan="2">Authorized Terms</td>
		</tr>';
	}
	else
	{
		echo '<tr>
		<td><input type="radio" name="PaymentType" value="Terms" />
		<td colspan="2">Authorized Terms</td>
		</tr>';
	}
}

?>

<!-- GMC - 02/20/09 - New Payment Types visible for CRSAdmins Only -->
<!-- GMC - 02/23/09 - To accomodate the Terms in New Types -->

<?php

if (($_SESSION['UserTypeID'] == 2 ) && ($_SESSION['CustomerTerms'] == 1))
{

echo '
<tr>
    <td><input type="radio" name="PaymentType" value="Check" />

    <!-- GMC - 06/16/09 - Change requested by JS
    <td colspan="2">Pay By Check - Other then ECheck</td>
    -->

    <td colspan="2">Pay By Check</td>

</tr>

<tr>
    <td width="30">&nbsp;</td>
    <th width="140">Check Number:</th>
    <td width="*">
    ';

    if (isset($_SESSION['PaymentCheck_Number']))
    {
        echo '<input type="text" name="Check_Number" size="16" value="';
        echo $_SESSION['PaymentCheck_Number'];
        echo '" />';
    }
    else
    {
        echo '<input type="text" name="Check_Number" size="16" value="" />';
    }

echo '
</tr>

<tr>
    <td><input type="radio" name="PaymentType" value="Wire" />
    <td colspan="2">Pay By Wire Transfer</td>
</tr>

<tr>
    <td width="30">&nbsp;</td>
    <th width="140">Wire Number:</th>
    <td width="*">
    ';
    if (isset($_SESSION['PaymentWire_Number']))
    {
        echo '<input type="text" name="Wire_Number" size="16" value="';
        echo $_SESSION['PaymentWire_Number'];
        echo '" /></td>';
    }
    else
    {
     echo '<input type="text" name="Wire_Number" size="16" value="" />';
    }

echo '
</tr>

<tr>
    <td><input type="radio" name="PaymentType" value="Cash" />
    <td colspan="2">Pay By Cash - Trade Show</td>
</tr>';

}

?>

<!-- GMC - 12/31/08 - Rep Changes 123108 (To hide the NOCHARGE from Reps)-->
<?php
if ($_SESSION['UserTypeID'] == 1)
{
   echo '<tr><td>&nbsp;</td><td colspan="2">&nbsp;</td></tr>';
}
else
{
   echo '<!-- GMC - 10/31/08 - To accomodate the NOCHARGE Process --><tr><td>';

   if ((isset($_SESSION['PaymentType'])) && ($_SESSION['PaymentType'] == 'NOCHARGE'))
   {
      echo '<input type="radio" name="PaymentType" value="NOCHARGE" checked="checked"/>';
   }
   else
   {
      echo '<input type="radio" name="PaymentType" value="NOCHARGE"/>';
   }

   echo '<td colspan="2">No Charge</td></tr>';

}
?>

<!-- GMC - 04/12/10 - LTL Shipments -->
<!-- GMC - 05/12/10 - LTL Shipment Not a Payment Type -->
<?php

// GMC - 06/14/10 - Add CFelix to LTL by JS

// if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 35)
if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 41)
{
   echo '<tr><td>';

   /*
   if ((isset($_SESSION['PaymentType'])) && ($_SESSION['PaymentType'] == 'LTL'))
   {
      echo '<input type="radio" name="PaymentType" value="LTL" checked="checked"/>';
   }
   else
   {
      echo '<input type="radio" name="PaymentType" value="LTL"/>';
   }
   */
   
   if ((isset($_SESSION['LTLShipmentType'])) && ($_SESSION['LTLShipmentType'] == 'LTL'))
   {
      echo '<input type="checkbox" name="LTLShipmentType" value="LTL" checked="checked"/>';
   }
   else
   {
      echo '<input type="checkbox" name="LTLShipmentType" value="LTL"/>';
   }

   echo '</td>';

   echo '<td colspan="2">LTL Shipment</td></tr>';
}
?>

</table>

<!-- GMC - 12/31/08 - Rep Changes 123108 (To hide the Trade Show from Reps)-->
<!-- GMC - 09/05/09 - Promotion Section - Drop Down for CSR's Only -->
<?php
if ($_SESSION['UserTypeID'] != 1)
{
   echo '<hr width="900" size="2" color="#000000" noshade="noshade" align="left" style="margin-left:10px;" />
        <table width="100%" cellpadding="5" cellspacing="0">
        <tr>
        <th width="180">Promo Codes & Trade Shows (if any):</th>
        <td width="*">
        <select name="NavisionCampaign" size="1">
        <option value="0">SELECT BELOW</option>
        ';

        while($rowCampaign = mssql_fetch_array($cboCampaigns))
        {
		 if (isset($_SESSION['FORMNavisionCampaign']) && $_SESSION['FORMNavisionCampaign'] == $rowCampaign["NavisionCode"])
			echo '<option selected="selected" value="' . $rowCampaign["NavisionCode"] . '">' . $rowCampaign["CampaignDisplay"] . '</option>';
		 else
			echo '<option value="' . $rowCampaign["NavisionCode"] . '">' . $rowCampaign["CampaignDisplay"] . '</option>';
        }

   echo '</select>
        </td>
        </tr>
        </table>
        ';
}
else
{
   // GMC - 01/06/09 - Bug Reps won't see Free if value is blank
   echo '<input type="hidden" name="NavisionCampaign" value="0">';
}
?>

<hr width="900" size="2" color="#000000" noshade="noshade" align="left" style="margin-left:10px;" />

<!-- <p><a href="/csradmin/marketing.php">Product Information Link</a></p> -->

<table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">

<!-- GMC - 04/03/09 - Add Mascara Product in CSRAdmin -->
<!-- GMC - 05/29/09 - Section to Expose the Trade Shows and Promotions Information -->
<tr>
<td width="10%">
&nbsp;
</td>
<td width="80%">
<div align="center">
<font color="red">
<strong>

<!-- GMC - 07/10/09 - Delete by JS
NOTE: Mascara Product is 1 unit or more purchase for Reseller, otherwise you'll be send back to this screen to correct.
-->

<!-- GMC 06/29/09 - End June Promotions
<br><br>
JUNE 2009 PROMOTIONS
<br>
<p>
Revitalash: Buy 18 or more and get 2 Free (All Customers Except Consumers)
<br>
Hair by Revitalash: Buy 2 or more and get Free Shipping and Handling (If UPS Ground is selected)
</p>
-->

<!-- GMC 07/01/09 - July-August Promo: Hair 6 + 1 Free -->
<!--<br><br>
JULY - AUGUST 2009 PROMOTION
<br>
<p>
Hair by Revitalash: Buy 6 get 1 Free (All Customers Except Consumers)
</p>
-->

<!-- GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness -->
<!-- GMC 12/25/09 - Change in text below -->
<!-- GMC 01/06/10 - Breast Cancer Awareness - Take Text Out -->
<!-- AUGUST 17 TO OCTOBER 31 PROMOTION -->
<!--
FROM AUGUST 17 ON PROMOTION
<br>
<p>
Breast Cancer Awareness Promotion includes Revitalash, Mascara, and Pink Ribbon Cosmetic bag.  Minimum order of 12 or more – While Supplies Last.
</p>
-->

<!-- GMC 11/02/09 - 2009 Holiday Gift Box Set -->
<!-- GMC 11/18/09 - 2009 Holiday Gift Box Set - Extend to Distributor-->
<!-- GMC - 12/02/09 - 2009 Holiday Gift Box Set - Change from 12-2 to 6-1-->
<!-- GMC - 01/19/10 - Remove 2009 Holiday Gift Box Text
2009 HOLIDAY GIFT BOX SET (RESELLERS AND DISTRIBUTORS ONLY)
<br>
<p>
Revitalash and Eyelash Curler in a Holiday Box. Minimum order of 6 or more. Buy 6 and get 1 Revitalash for Free – Ends 12/31/09 or While Supplies Last.
</p>
-->

<!-- GMC 01/05/10 - Valentine's Day 2010 Promotion -->
<!-- GMC 03/08/10 - Cancel Valentine's Day 2010 Text
VALENTINE'S DAY 2010 PROMOTION
<br>
<p>
Valentine's Day 2010 Promotion includes Valentine's Promotion Bag, Revitalash and Candle or Compact Mirror.  Minimum order of 12 or more – While Supplies Last.
</p>
-->

<!-- GMC 03/09/10 - Mother's Day 2010 Promotion -->
<!-- GMC - 05/17/10 - Cancel Mother's Day 2010 Promotion Text -->
<!-- GMC - 07/28/10 - Cancel Hair Promo 2010 Promotion Text -->

<!--
MOTHER'S DAY 2010 PROMOTION
<br>
<p>
Mother's Day 2010 Promotion includes Revitalash and Mascara.  Minimum order of 12 or more – While Supplies Last.
</p>
<br>
<p>
Hair promo - Buy 6 get 1 free until June 30th (Resellers Only)
</p>
-->

<!-- GMC - 06/07/10 - Cancel Revitabrow Promo May 30th 2010
<br>
<p>
Revitabrow promo - Buy 6 get 1 free until May 30th (Resellers Only)
</p>
-->

<!-- GMC - 10/24/10 - Bundles Project Oct 2010 -->
<!-- GMC - 12/03/10 - Blow out Mascara Espresso - Buy 12 Give 6 or Multiples -->
<!-- GMC - 01/03/11 - Cancel Blow out Mascara Espresso - Buy 12 Give 6 or Multiples -->
<!-- GMC - 06/10/11 - NAV Item 498 - 593 and 594 - 12 + 2 Free from 062011 to 090111 -->
<!-- GMC - 08/27/11 - Deactivate NAV Item 498 - 593 and 594 - 12 + 2 Free from 062011 to 090111
<br>
<p>
Advanced Formula Special Promotion (Purchase 12 and get 2 free) from 06/20/11 to 09/01/11
</p>
-->

<!-- GMC - 08/28/11 - NAV Item 593 and 417 Get 12 + 1 plus multiples of 13 free effective 090111 -->
<!-- GMC - 09/29/11 - NAV Item 593 and 417 Get 12 + 1 plus multiples of 13 free effective 090111 - Cancel Promo
<br>
<p>
Pink Revitalash Bag Special Promotion (Purchase 12 or more of Advanced Revitalash SPA 3.5 ml and get bags free UP to 39 units) from 09/01/11
</p>
-->

<br>
<p>
Tier Bundles US and Canada (Distributors Only)
</p>

<br>
<p>
YOU CAN ONLY ENTER PRODUCT OR MARKETING IN THE DROP DOWN BELOW OTHERWISE YOU WILL BE SENT TO THE CUSTOMER LIST TO START OVER
</p>

<!-- GMC - 11/30/11 - Block Australia from Ordering -->

<br>
<p>
REMINDER: NO ORDERS TO AUSTRALIA ARE ALLOWED :REMINDER
</p>

<!-- GMC - 11/04/11 - MinimumQty Calculation -->
<!-- GMC - 11/12/11 - MinimuQty Calculation is not needed for CSR Admins -->
<!--
<br>
<p>
Holiday Gift Box 2011 Minimum Quantity of 6 (Exception does not apply when CSR ADMIN orders)
</p>
-->

<!--
<br>
<p>
MASCARA SPECIAL PROMOTION: Buy 12 RAVEN Mascara get 6 Espresso FREE (or Multiples) Offer valid for Resellers - Domestic Only
</p>
-->

<!-- GMC - 03/06/12 - Take Out NAV Item 516 -358 and 443 - 12 + 2 Free from 12/11 to 02/12 -->
<!-- GMC - 12/02/11 - NAV Item 516 -358 and 443 - 12 + 2 Free from 12/11 to 02/12
<br>
<p>
Revitalash Advanced Formula 3.0 ml Special Promotion (Purchase 12 and get 2 free) End Date 02/29/12
</p>
-->

<!-- GMC - 05/01/12 - New Special Revitabrow Promotion for MAY 2012 -->
<br>
<p>
Buy 6 or more Units of XXXXXXXXXXXXXXXXXXXXXXXXXX and any quantity of Revitabrow will get 25% off - New Special Revitabrow Promotion for MAY 2012 - Only for Resellers!
</p>

</strong>
</font>
</div>
</td>
<td width="10%">
&nbsp;
</td>
</tr>
</table>

<hr width="900" size="2" color="#000000" noshade="noshade" align="left" style="margin-left:10px;" />

<table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

      if($blnIsInternational == 1)
      {
          // GMC - 08/16/11 - To divide Products and Marketing Materials
          echo '<th width="*" style="text-align:left">International Products</th>';
          echo '<th width="*" style="text-align:left">International Marketing</th>';
      }
      else
      {
          // GMC - 08/16/11 - To divide Products and Marketing Materials
          echo '<th width="*" style="text-align:left">Domestic Products</th>';
          echo '<th width="*" style="text-align:left">Domestic Marketing</th>';
      }

      // GMC - 03/26/12 - MediaKit Process
      if($_SESSION['OrderType'] == 'MediaKit')
      {
          echo '<th width="*" style="text-align:left">Media Kits</th>';
      }

    ?>

    <th width="50" style="text-align:left">Qty</th>
    <!-- GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now -->
    <th width="150" style="text-align:left"><?php if ($_SESSION['UserTypeID'] != 1) echo 'Ship from Stock Location'; else echo '&nbsp;'; ?></th>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
        echo '<td><select name="ItemID1" size="1"><option value="0">-- Select Below --</option>';
        while($row1 = mssql_fetch_array($cboProducts1_Int))
	    {
            echo '<option value="'. $row1["RecordID"] . '"';
		    if ($_SESSION['FORMItemID1'] == $row1["RecordID"]) echo ' selected="selected"';
		    echo '>';

            // GMC - 08/03/09 - Add Part Number by JS
            echo $row1["ProductName"] . ' ~ ' . $row1["PartNumber"];

            if ($row1['IsShowCSRPrice'] == 1 && $row1['RetailPrice'] != 0) echo ' (' . number_format($row1['RetailPrice'], 2, '.', '') . ')';
		    echo '</option>';
	    }
        echo '</select></td>';

        echo '<td><select name="ItemMID1" size="1"><option value="0">-- Select Below --</option>';
	    while($row1 = mssql_fetch_array($cboMarkets1_Int))
	    {
            echo '<option value="'. $row1["RecordID"] . '"';
            if ($_SESSION['FORMItemMID1'] == $row1["RecordID"]) echo ' selected="selected"';
            echo '>';

            // GMC - 08/03/09 - Add Part Number by JS
            echo $row1["ProductName"] . ' ~ ' . $row1["PartNumber"];

            if ($row1['IsShowCSRPrice'] == 1 && $row1['RetailPrice'] != 0) echo ' (' . number_format($row1['RetailPrice'], 2, '.', '') . ')';
            echo '</option>';
	    }
        echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT1" size="1"><option value="0">-- Select Below --</option>';
            while($row1 = mssql_fetch_array($cboMediaKit1_Int))
            {
                echo '<option value="'. $row1["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT1'] == $row1["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row1["ProductName"] . ' ~ ' . $row1["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
    }
    else
    {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
        echo '<td><select name="ItemID1" size="1"><option value="0">-- Select Below --</option>';
        while($row1 = mssql_fetch_array($cboProducts1))
        {
		     echo '<option value="'. $row1["RecordID"] . '"';
		     if ($_SESSION['FORMItemID1'] == $row1["RecordID"]) echo ' selected="selected"';
		     echo '>';

            // GMC - 08/03/09 - Add Part Number by JS
            echo $row1["ProductName"] . ' ~ ' . $row1["PartNumber"];

            if ($row1['IsShowCSRPrice'] == 1 && $row1['RetailPrice'] != 0) echo ' (' . number_format($row1['RetailPrice'], 2, '.', '') . ')';
            echo '</option>';
	    }
        echo '</select></td>';

        echo '<td><select name="ItemMID1" size="1"><option value="0">-- Select Below --</option>';
	    while($row1 = mssql_fetch_array($cboMarkets1))
	    {
		    echo '<option value="'. $row1["RecordID"] . '"';
		    if ($_SESSION['FORMItemMID1'] == $row1["RecordID"]) echo ' selected="selected"';
		    echo '>';

            // GMC - 08/03/09 - Add Part Number by JS
            echo $row1["ProductName"] . ' ~ ' . $row1["PartNumber"];

		    if ($row1['IsShowCSRPrice'] == 1 && $row1['RetailPrice'] != 0) echo ' (' . number_format($row1['RetailPrice'], 2, '.', '') . ')';
		    echo '</option>';
	    }
        echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT1" size="1"><option value="0">-- Select Below --</option>';
            while($row1 = mssql_fetch_array($cboMediaKit1))
            {
                echo '<option value="'. $row1["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT1'] == $row1["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row1["ProductName"] . ' ~ ' . $row1["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
    }
    ?>

    <td><input type="text" name="ItemQty1" size="2" value="<?php echo $_SESSION['FORMItemQty1']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{
        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation1" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation1'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation1" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation1'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation1" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation1'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';
		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation1" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation1'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation1'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation1" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation1'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
            // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation1'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }
        
    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation1" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation1" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
        echo '<td><select name="ItemID2" size="1"><option value="0">-- Select Below --</option>';
	    while($row2 = mssql_fetch_array($cboProducts2_Int))
	    {
		 echo '<option value="'. $row2["RecordID"] . '"';
		 if ($_SESSION['FORMItemID2'] == $row2["RecordID"]) echo ' selected="selected"';
		 echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		 echo $row2["ProductName"] . ' ~ ' . $row2["PartNumber"];;

		 if ($row2['IsShowCSRPrice'] == 1 && $row2['RetailPrice'] != 0) echo ' (' . number_format($row2['RetailPrice'], 2, '.', '') . ')';
		 echo '</option>';
        }
        echo '</select></td>';
        
        echo '<td><select name="ItemMID2" size="1"><option value="0">-- Select Below --</option>';
	    while($row2 = mssql_fetch_array($cboMarkets2_Int))
	    {
		 echo '<option value="'. $row2["RecordID"] . '"';
		 if ($_SESSION['FORMItemMID2'] == $row2["RecordID"]) echo ' selected="selected"';
		 echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		 echo $row2["ProductName"] . ' ~ ' . $row2["PartNumber"];;

		 if ($row2['IsShowCSRPrice'] == 1 && $row2['RetailPrice'] != 0) echo ' (' . number_format($row2['RetailPrice'], 2, '.', '') . ')';
		 echo '</option>';
        }
        echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT2" size="1"><option value="0">-- Select Below --</option>';
            while($row2 = mssql_fetch_array($cboMediaKit2_Int))
            {
                echo '<option value="'. $row2["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT2'] == $row2["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row2["ProductName"] . ' ~ ' . $row2["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
      echo '<td><select name="ItemID2" size="1"><option value="0">-- Select Below --</option>';
	  while($row2 = mssql_fetch_array($cboProducts2))
	  {
		echo '<option value="'. $row2["RecordID"] . '"';
		if ($_SESSION['FORMItemID2'] == $row2["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
        echo $row2["ProductName"] . ' ~ ' . $row2["PartNumber"];;

		if ($row2['IsShowCSRPrice'] == 1 && $row2['RetailPrice'] != 0) echo ' (' . number_format($row2['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

      echo '<td><select name="ItemMID2" size="1"><option value="0">-- Select Below --</option>';
	  while($row2 = mssql_fetch_array($cboMarkets2))
	  {
		echo '<option value="'. $row2["RecordID"] . '"';
		if ($_SESSION['FORMItemMID2'] == $row2["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
        echo $row2["ProductName"] . ' ~ ' . $row2["PartNumber"];;

		if ($row2['IsShowCSRPrice'] == 1 && $row2['RetailPrice'] != 0) echo ' (' . number_format($row2['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

       // GMC - 03/26/12 - MediaKit Process
       if($_SESSION['OrderType'] == 'MediaKit')
       {
            echo '<td><select name="ItemMKT2" size="1"><option value="0">-- Select Below --</option>';
            while($row2 = mssql_fetch_array($cboMediaKit2))
            {
                echo '<option value="'. $row2["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT2'] == $row2["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row2["ProductName"] . ' ~ ' . $row2["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
       }
     }

   ?>

    <td><input type="text" name="ItemQty2" size="2" value="<?php echo $_SESSION['FORMItemQty2']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{
        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation2" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation2'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';
		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation2" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation2'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation2" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation2'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation2" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation2'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation2'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
            echo '<select name="ItemStockLocation2" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation2'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
            // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation2'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }
        
    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation2" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation2" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
       echo '<td><select name="ItemID3" size="1"><option value="0">-- Select Below --</option>';
	   while($row3 = mssql_fetch_array($cboProducts3_Int))
	   {
		echo '<option value="'. $row3["RecordID"] . '"';
		if ($_SESSION['FORMItemID3'] == $row3["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row3["ProductName"] . ' ~ ' . $row3["PartNumber"];;

		if ($row3['IsShowCSRPrice'] == 1 && $row3['RetailPrice'] != 0) echo ' (' . number_format($row3['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

       echo '<td><select name="ItemMID3" size="1"><option value="0">-- Select Below --</option>';
	   while($row3 = mssql_fetch_array($cboMarkets3_Int))
	   {
		echo '<option value="'. $row3["RecordID"] . '"';
		if ($_SESSION['FORMItemMID3'] == $row3["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row3["ProductName"] . ' ~ ' . $row3["PartNumber"];;

		if ($row3['IsShowCSRPrice'] == 1 && $row3['RetailPrice'] != 0) echo ' (' . number_format($row3['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT3" size="1"><option value="0">-- Select Below --</option>';
            while($row3 = mssql_fetch_array($cboMediaKit3_Int))
            {
                echo '<option value="'. $row3["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT3'] == $row3["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row3["ProductName"] . ' ~ ' . $row3["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
      echo '<td><select name="ItemID3" size="1"><option value="0">-- Select Below --</option>';
	  while($row3 = mssql_fetch_array($cboProducts3))
	  {
		echo '<option value="'. $row3["RecordID"] . '"';
		if ($_SESSION['FORMItemID3'] == $row3["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row3["ProductName"] . ' ~ ' . $row3["PartNumber"];;

		if ($row3['IsShowCSRPrice'] == 1 && $row3['RetailPrice'] != 0) echo ' (' . number_format($row3['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

      echo '<td><select name="ItemMID3" size="1"><option value="0">-- Select Below --</option>';
	  while($row3 = mssql_fetch_array($cboMarkets3))
	  {
		echo '<option value="'. $row3["RecordID"] . '"';
		if ($_SESSION['FORMItemMID3'] == $row3["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row3["ProductName"] . ' ~ ' . $row3["PartNumber"];;

		if ($row3['IsShowCSRPrice'] == 1 && $row3['RetailPrice'] != 0) echo ' (' . number_format($row3['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

       // GMC - 03/26/12 - MediaKit Process
       if($_SESSION['OrderType'] == 'MediaKit')
       {
            echo '<td><select name="ItemMKT3" size="1"><option value="0">-- Select Below --</option>';
            while($row3 = mssql_fetch_array($cboMediaKit3))
            {
                echo '<option value="'. $row3["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT3'] == $row3["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row3["ProductName"] . ' ~ ' . $row3["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
       }
     }

   ?>

    <td><input type="text" name="ItemQty3" size="2" value="<?php echo $_SESSION['FORMItemQty3']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation3" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation3'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation3" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation3'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation3" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation3'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation3" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation3'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation3'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation3" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation3'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation3'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation3" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation3" value="ATHENA-LV" />&nbsp;';
    }
    
    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID4" size="1"><option value="0">-- Select Below --</option>';
	  while($row4 = mssql_fetch_array($cboProducts4_Int))
	  {
		echo '<option value="'. $row4["RecordID"] . '"';
		if ($_SESSION['FORMItemID4'] == $row4["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row4["ProductName"] . ' ~ ' . $row4["PartNumber"];;

		if ($row4['IsShowCSRPrice'] == 1 && $row4['RetailPrice'] != 0) echo ' (' . number_format($row4['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

	  echo '<td><select name="ItemMID4" size="1"><option value="0">-- Select Below --</option>';
	  while($row4 = mssql_fetch_array($cboMarkets4_Int))
	  {
		echo '<option value="'. $row4["RecordID"] . '"';
		if ($_SESSION['FORMItemMID4'] == $row4["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row4["ProductName"] . ' ~ ' . $row4["PartNumber"];;

		if ($row4['IsShowCSRPrice'] == 1 && $row4['RetailPrice'] != 0) echo ' (' . number_format($row4['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT4" size="1"><option value="0">-- Select Below --</option>';
            while($row4 = mssql_fetch_array($cboMediaKit4_Int))
            {
                echo '<option value="'. $row4["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT4'] == $row4["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row4["ProductName"] . ' ~ ' . $row4["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	     echo '<td><select name="ItemID4" size="1"><option value="0">-- Select Below --</option>';
	     while($row4 = mssql_fetch_array($cboProducts4))
	     {
		  echo '<option value="'. $row4["RecordID"] . '"';
          if ($_SESSION['FORMItemID4'] == $row4["RecordID"]) echo ' selected="selected"';
		  echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		  echo $row4["ProductName"] . ' ~ ' . $row4["PartNumber"];;

		  if ($row4['IsShowCSRPrice'] == 1 && $row4['RetailPrice'] != 0) echo ' (' . number_format($row4['RetailPrice'], 2, '.', '') . ')';
		  echo '</option>';
         }
         echo '</select></td>';

	     echo '<td><select name="ItemMID4" size="1"><option value="0">-- Select Below --</option>';
	     while($row4 = mssql_fetch_array($cboMarkets4))
	     {
		  echo '<option value="'. $row4["RecordID"] . '"';
          if ($_SESSION['FORMItemMID4'] == $row4["RecordID"]) echo ' selected="selected"';
		  echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		  echo $row4["ProductName"] . ' ~ ' . $row4["PartNumber"];;

		  if ($row4['IsShowCSRPrice'] == 1 && $row4['RetailPrice'] != 0) echo ' (' . number_format($row4['RetailPrice'], 2, '.', '') . ')';
		  echo '</option>';
         }
         echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT4" size="1"><option value="0">-- Select Below --</option>';
            while($row4 = mssql_fetch_array($cboMediaKit4))
            {
                echo '<option value="'. $row4["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT4'] == $row4["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row4["ProductName"] . ' ~ ' . $row4["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

    ?>

    <td><input type="text" name="ItemQty4" size="2" value="<?php echo $_SESSION['FORMItemQty4']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation4" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation4'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation4" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation4'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation4" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation4'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation4" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation4'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation4'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation4" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation4'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation4'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation4" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation4" value="ATHENA-LV" />&nbsp;';
    }
    
    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID5" size="1"><option value="0">-- Select Below --</option>';
	  while($row5 = mssql_fetch_array($cboProducts5_Int))
	  {
		echo '<option value="'. $row5["RecordID"] . '"';
		if ($_SESSION['FORMItemID5'] == $row5["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row5["ProductName"] . ' ~ ' . $row5["PartNumber"];;

		if ($row5['IsShowCSRPrice'] == 1 && $row5['RetailPrice'] != 0) echo ' (' . number_format($row5['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	  echo '<td><select name="ItemMID5" size="1"><option value="0">-- Select Below --</option>';
	  while($row5 = mssql_fetch_array($cboMarkets5_Int))
	  {
		echo '<option value="'. $row5["RecordID"] . '"';
		if ($_SESSION['FORMItemMID5'] == $row5["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row5["ProductName"] . ' ~ ' . $row5["PartNumber"];;

		if ($row5['IsShowCSRPrice'] == 1 && $row5['RetailPrice'] != 0) echo ' (' . number_format($row5['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT5" size="1"><option value="0">-- Select Below --</option>';
            while($row5 = mssql_fetch_array($cboMediaKit5_Int))
            {
                echo '<option value="'. $row5["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT5'] == $row5["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row5["ProductName"] . ' ~ ' . $row5["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID5" size="1"><option value="0">-- Select Below --</option>';
	  while($row5 = mssql_fetch_array($cboProducts5))
	  {
		echo '<option value="'. $row5["RecordID"] . '"';
		if ($_SESSION['FORMItemID5'] == $row5["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row5["ProductName"] . ' ~ ' . $row5["PartNumber"];;

		if ($row5['IsShowCSRPrice'] == 1 && $row5['RetailPrice'] != 0) echo ' (' . number_format($row5['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

	  echo '<td><select name="ItemMID5" size="1"><option value="0">-- Select Below --</option>';
	  while($row5 = mssql_fetch_array($cboMarkets5))
	  {
		echo '<option value="'. $row5["RecordID"] . '"';
		if ($_SESSION['FORMItemMID5'] == $row5["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row5["ProductName"] . ' ~ ' . $row5["PartNumber"];;

		if ($row5['IsShowCSRPrice'] == 1 && $row5['RetailPrice'] != 0) echo ' (' . number_format($row5['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT5" size="1"><option value="0">-- Select Below --</option>';
            while($row5 = mssql_fetch_array($cboMediaKit5))
            {
                echo '<option value="'. $row5["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT5'] == $row5["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row5["ProductName"] . ' ~ ' . $row5["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty5" size="2" value="<?php echo $_SESSION['FORMItemQty5']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation5" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation5'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation5" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation5'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation5" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation5'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation5" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation5'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation5'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation5" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation5'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation5'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation5" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation5" value="ATHENA-LV" />&nbsp;';
    }
    
    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID6" size="1"><option value="0">-- Select Below --</option>';
	  while($row6 = mssql_fetch_array($cboProducts6_Int))
	  {
		echo '<option value="'. $row6["RecordID"] . '"';
		if ($_SESSION['FORMItemID6'] == $row6["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row6["ProductName"] . ' ~ ' . $row6["PartNumber"];;

		if ($row6['IsShowCSRPrice'] == 1 && $row6['RetailPrice'] != 0) echo ' (' . number_format($row6['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	  echo '<td><select name="ItemMID6" size="1"><option value="0">-- Select Below --</option>';
	  while($row6 = mssql_fetch_array($cboMarkets6_Int))
	  {
		echo '<option value="'. $row6["RecordID"] . '"';
		if ($_SESSION['FORMItemMID6'] == $row6["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row6["ProductName"] . ' ~ ' . $row6["PartNumber"];;

		if ($row6['IsShowCSRPrice'] == 1 && $row6['RetailPrice'] != 0) echo ' (' . number_format($row6['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT6" size="1"><option value="0">-- Select Below --</option>';
            while($row6 = mssql_fetch_array($cboMediaKit6_Int))
            {
                echo '<option value="'. $row6["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT6'] == $row6["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row6["ProductName"] . ' ~ ' . $row6["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID6" size="1"><option value="0">-- Select Below --</option>';
	  while($row6 = mssql_fetch_array($cboProducts6))
	  {
		echo '<option value="'. $row6["RecordID"] . '"';
		if ($_SESSION['FORMItemID6'] == $row6["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row6["ProductName"] . ' ~ ' . $row6["PartNumber"];;

		if ($row6['IsShowCSRPrice'] == 1 && $row6['RetailPrice'] != 0) echo ' (' . number_format($row6['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';
     
	  echo '<td><select name="ItemMID6" size="1"><option value="0">-- Select Below --</option>';
	  while($row6 = mssql_fetch_array($cboMarkets6))
	  {
		echo '<option value="'. $row6["RecordID"] . '"';
		if ($_SESSION['FORMItemMID6'] == $row6["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row6["ProductName"] . ' ~ ' . $row6["PartNumber"];;

		if ($row6['IsShowCSRPrice'] == 1 && $row6['RetailPrice'] != 0) echo ' (' . number_format($row6['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT6" size="1"><option value="0">-- Select Below --</option>';
            while($row6 = mssql_fetch_array($cboMediaKit6))
            {
                echo '<option value="'. $row6["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT6'] == $row6["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row6["ProductName"] . ' ~ ' . $row6["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty6" size="2" value="<?php echo $_SESSION['FORMItemQty6']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation6" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation6'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation6" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation6'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation6" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation6'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation6" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation6'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation6'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation6" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    //echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation6'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation6'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }
        
    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation6" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation6" value="ATHENA-LV" />&nbsp;';
    }
    
    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID7" size="1"><option value="0">-- Select Below --</option>';
	  while($row7 = mssql_fetch_array($cboProducts7_Int))
	  {
		echo '<option value="'. $row7["RecordID"] . '"';
		if ($_SESSION['FORMItemID7'] == $row7["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row7["ProductName"] . ' ~ ' . $row7["PartNumber"];;

		if ($row7['IsShowCSRPrice'] == 1 && $row7['RetailPrice'] != 0) echo ' (' . number_format($row7['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

	  echo '<td><select name="ItemMID7" size="1"><option value="0">-- Select Below --</option>';
	  while($row7 = mssql_fetch_array($cboMarkets7_Int))
	  {
		echo '<option value="'. $row7["RecordID"] . '"';
		if ($_SESSION['FORMItemMID7'] == $row7["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row7["ProductName"] . ' ~ ' . $row7["PartNumber"];;

		if ($row7['IsShowCSRPrice'] == 1 && $row7['RetailPrice'] != 0) echo ' (' . number_format($row7['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT7" size="1"><option value="0">-- Select Below --</option>';
            while($row7 = mssql_fetch_array($cboMediaKit7_Int))
            {
                echo '<option value="'. $row7["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT7'] == $row7["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row7["ProductName"] . ' ~ ' . $row7["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID7" size="1"><option value="0">-- Select Below --</option>';
	  while($row7 = mssql_fetch_array($cboProducts7))
	  {
		echo '<option value="'. $row7["RecordID"] . '"';
		if ($_SESSION['FORMItemID7'] == $row7["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row7["ProductName"] . ' ~ ' . $row7["PartNumber"];;

		if ($row7['IsShowCSRPrice'] == 1 && $row7['RetailPrice'] != 0) echo ' (' . number_format($row7['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	  echo '<td><select name="ItemMID7" size="1"><option value="0">-- Select Below --</option>';
	  while($row7 = mssql_fetch_array($cboMarkets7))
	  {
		echo '<option value="'. $row7["RecordID"] . '"';
		if ($_SESSION['FORMItemMID7'] == $row7["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row7["ProductName"] . ' ~ ' . $row7["PartNumber"];;

		if ($row7['IsShowCSRPrice'] == 1 && $row7['RetailPrice'] != 0) echo ' (' . number_format($row7['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT7" size="1"><option value="0">-- Select Below --</option>';
            while($row7 = mssql_fetch_array($cboMediaKit7))
            {
                echo '<option value="'. $row7["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT7'] == $row7["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row7["ProductName"] . ' ~ ' . $row7["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty7" size="2" value="<?php echo $_SESSION['FORMItemQty7']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation7" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation7'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation7" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation7'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation7" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation7'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation7" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation7'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation7'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation7" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation7'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation7'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation7" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation7" value="ATHENA-LV" />&nbsp;';
    }
    
    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID8" size="1"><option value="0">-- Select Below --</option>';
	  while($row8 = mssql_fetch_array($cboProducts8_Int))
	  {
		echo '<option value="'. $row8["RecordID"] . '"';
		if ($_SESSION['FORMItemID8'] == $row8["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row8["ProductName"] . ' ~ ' . $row8["PartNumber"];;

		if ($row8['IsShowCSRPrice'] == 1 && $row8['RetailPrice'] != 0) echo ' (' . number_format($row8['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

	  echo '<td><select name="ItemMID8" size="1"><option value="0">-- Select Below --</option>';
	  while($row8 = mssql_fetch_array($cboMarkets8_Int))
	  {
		echo '<option value="'. $row8["RecordID"] . '"';
		if ($_SESSION['FORMItemMID8'] == $row8["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row8["ProductName"] . ' ~ ' . $row8["PartNumber"];;

		if ($row8['IsShowCSRPrice'] == 1 && $row8['RetailPrice'] != 0) echo ' (' . number_format($row8['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT8" size="1"><option value="0">-- Select Below --</option>';
            while($row8 = mssql_fetch_array($cboMediaKit8_Int))
            {
                echo '<option value="'. $row8["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT8'] == $row8["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row8["ProductName"] . ' ~ ' . $row8["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID8" size="1"><option value="0">-- Select Below --</option>';
	  while($row8 = mssql_fetch_array($cboProducts8))
	  {
		echo '<option value="'. $row8["RecordID"] . '"';
		if ($_SESSION['FORMItemID8'] == $row8["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row8["ProductName"] . ' ~ ' . $row8["PartNumber"];;

		if ($row8['IsShowCSRPrice'] == 1 && $row8['RetailPrice'] != 0) echo ' (' . number_format($row8['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	  echo '<td><select name="ItemMID8" size="1"><option value="0">-- Select Below --</option>';
	  while($row8 = mssql_fetch_array($cboMarkets8))
	  {
		echo '<option value="'. $row8["RecordID"] . '"';
		if ($_SESSION['FORMItemMID8'] == $row8["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row8["ProductName"] . ' ~ ' . $row8["PartNumber"];;

		if ($row8['IsShowCSRPrice'] == 1 && $row8['RetailPrice'] != 0) echo ' (' . number_format($row8['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT8" size="1"><option value="0">-- Select Below --</option>';
            while($row8 = mssql_fetch_array($cboMediaKit8))
            {
                echo '<option value="'. $row8["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT8'] == $row8["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row8["ProductName"] . ' ~ ' . $row8["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty8" size="2" value="<?php echo $_SESSION['FORMItemQty8']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation8" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation8'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation8" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation8'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation8" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation8'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation8" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation8'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation8'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation8" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation8'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation8'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation8" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation8" value="ATHENA-LV" />&nbsp;';
    }
    
    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	 echo '<td><select name="ItemID9" size="1"><option value="0">-- Select Below --</option>';
	 while($row9 = mssql_fetch_array($cboProducts9_Int))
	 {
		echo '<option value="'. $row9["RecordID"] . '"';
		if ($_SESSION['FORMItemID9'] == $row9["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row9["ProductName"] . ' ~ ' . $row9["PartNumber"];;

		if ($row9['IsShowCSRPrice'] == 1 && $row9['RetailPrice'] != 0) echo ' (' . number_format($row9['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	 echo '<td><select name="ItemMID9" size="1"><option value="0">-- Select Below --</option>';
	 while($row9 = mssql_fetch_array($cboMarkets9_Int))
	 {
		echo '<option value="'. $row9["RecordID"] . '"';
		if ($_SESSION['FORMItemMID9'] == $row9["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row9["ProductName"] . ' ~ ' . $row9["PartNumber"];;

		if ($row9['IsShowCSRPrice'] == 1 && $row9['RetailPrice'] != 0) echo ' (' . number_format($row9['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT9" size="1"><option value="0">-- Select Below --</option>';
            while($row9 = mssql_fetch_array($cboMediaKit9_Int))
            {
                echo '<option value="'. $row9["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT9'] == $row9["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row9["ProductName"] . ' ~ ' . $row9["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	 echo '<td><select name="ItemID9" size="1"><option value="0">-- Select Below --</option>';
	 while($row9 = mssql_fetch_array($cboProducts9))
	 {
		echo '<option value="'. $row9["RecordID"] . '"';
		if ($_SESSION['FORMItemID9'] == $row9["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row9["ProductName"] . ' ~ ' . $row9["PartNumber"];;

		if ($row9['IsShowCSRPrice'] == 1 && $row9['RetailPrice'] != 0) echo ' (' . number_format($row9['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	 echo '<td><select name="ItemMID9" size="1"><option value="0">-- Select Below --</option>';
	 while($row9 = mssql_fetch_array($cboMarkets9))
	 {
		echo '<option value="'. $row9["RecordID"] . '"';
		if ($_SESSION['FORMItemMID9'] == $row9["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row9["ProductName"] . ' ~ ' . $row9["PartNumber"];;

		if ($row9['IsShowCSRPrice'] == 1 && $row9['RetailPrice'] != 0) echo ' (' . number_format($row9['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT9" size="1"><option value="0">-- Select Below --</option>';
            while($row9 = mssql_fetch_array($cboMediaKit9))
            {
                echo '<option value="'. $row9["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT9'] == $row9["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row9["ProductName"] . ' ~ ' . $row9["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
   ?>

    <td><input type="text" name="ItemQty9" size="2" value="<?php echo $_SESSION['FORMItemQty9']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation9" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation9'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation9" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation9'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation9" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation9'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation9" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation9'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation9'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation9" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    //echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation9'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation9'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation9" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation9" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	 echo '<td><select name="ItemID10" size="1"><option value="0">-- Select Below --</option>';
	 while($row10 = mssql_fetch_array($cboProducts10_Int))
	 {
		echo '<option value="'. $row10["RecordID"] . '"';
		if ($_SESSION['FORMItemID10'] == $row10["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row10["ProductName"] . ' ~ ' . $row10["PartNumber"];;

		if ($row10['IsShowCSRPrice'] == 1 && $row10['RetailPrice'] != 0) echo ' (' . number_format($row10['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

	 echo '<td><select name="ItemMID10" size="1"><option value="0">-- Select Below --</option>';
	 while($row10 = mssql_fetch_array($cboMarkets10_Int))
	 {
		echo '<option value="'. $row10["RecordID"] . '"';
		if ($_SESSION['FORMItemMID10'] == $row10["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row10["ProductName"] . ' ~ ' . $row10["PartNumber"];;

		if ($row10['IsShowCSRPrice'] == 1 && $row10['RetailPrice'] != 0) echo ' (' . number_format($row10['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT10" size="1"><option value="0">-- Select Below --</option>';
            while($row10 = mssql_fetch_array($cboMediaKit10_Int))
            {
                echo '<option value="'. $row10["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT10'] == $row10["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row10["ProductName"] . ' ~ ' . $row10["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
    }
    else
    {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	 echo '<td><select name="ItemID10" size="1"><option value="0">-- Select Below --</option>';
	 while($row10 = mssql_fetch_array($cboProducts10))
	 {
		echo '<option value="'. $row10["RecordID"] . '"';
		if ($_SESSION['FORMItemID10'] == $row10["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row10["ProductName"] . ' ~ ' . $row10["PartNumber"];;

		if ($row10['IsShowCSRPrice'] == 1 && $row10['RetailPrice'] != 0) echo ' (' . number_format($row10['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';
     
	 echo '<td><select name="ItemMID10" size="1"><option value="0">-- Select Below --</option>';
	 while($row10 = mssql_fetch_array($cboMarkets10))
	 {
		echo '<option value="'. $row10["RecordID"] . '"';
		if ($_SESSION['FORMItemMID10'] == $row10["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row10["ProductName"] . ' ~ ' . $row10["PartNumber"];;

		if ($row10['IsShowCSRPrice'] == 1 && $row10['RetailPrice'] != 0) echo ' (' . number_format($row10['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

    // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT10" size="1"><option value="0">-- Select Below --</option>';
            while($row10 = mssql_fetch_array($cboMediaKit10))
            {
                echo '<option value="'. $row10["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT10'] == $row10["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row10["ProductName"] . ' ~ ' . $row10["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
    }
   ?>

    <td><input type="text" name="ItemQty10" size="2" value="<?php echo $_SESSION['FORMItemQty10']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation10" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation10'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation10" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation10'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation10" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation10'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation10" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */
            
		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation10'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation10'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation10" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation10'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
           		echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */
            
  		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation10'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation10" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation10" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<!-- GMC - 03/18/10 - Add 10 Line Items Admin -->

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
      echo '<td><select name="ItemID11" size="1"><option value="0">-- Select Below --</option>';
	  while($row11 = mssql_fetch_array($cboProducts11_Int))
	  {
         echo '<option value="'. $row11["RecordID"] . '"';
		 if ($_SESSION['FORMItemID11'] == $row11["RecordID"]) echo ' selected="selected"';
		 echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
         echo $row11["ProductName"] . ' ~ ' . $row11["PartNumber"];

         if ($row11['IsShowCSRPrice'] == 1 && $row11['RetailPrice'] != 0) echo ' (' . number_format($row11['RetailPrice'], 2, '.', '') . ')';
		 echo '</option>';
	  }
      echo '</select></td>';

      echo '<td><select name="ItemMID11" size="1"><option value="0">-- Select Below --</option>';
	  while($row11 = mssql_fetch_array($cboMarkets11_Int))
	  {
         echo '<option value="'. $row11["RecordID"] . '"';
		 if ($_SESSION['FORMItemMID11'] == $row11["RecordID"]) echo ' selected="selected"';
		 echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
         echo $row11["ProductName"] . ' ~ ' . $row11["PartNumber"];

         if ($row11['IsShowCSRPrice'] == 1 && $row11['RetailPrice'] != 0) echo ' (' . number_format($row11['RetailPrice'], 2, '.', '') . ')';
		 echo '</option>';
	  }
      echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT11" size="1"><option value="0">-- Select Below --</option>';
            while($row11 = mssql_fetch_array($cboMediaKit11_Int))
            {
                echo '<option value="'. $row11["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT11'] == $row11["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row11["ProductName"] . ' ~ ' . $row11["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
    }
    else
    {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
      echo '<td><select name="ItemID11" size="1"><option value="0">-- Select Below --</option>';
	  while($row11 = mssql_fetch_array($cboProducts11))
	  {
		 echo '<option value="'. $row11["RecordID"] . '"';
		 if ($_SESSION['FORMItemID11'] == $row11["RecordID"]) echo ' selected="selected"';
		 echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		 echo $row11["ProductName"] . ' ~ ' . $row11["PartNumber"];

		 if ($row11['IsShowCSRPrice'] == 1 && $row11['RetailPrice'] != 0) echo ' (' . number_format($row11['RetailPrice'], 2, '.', '') . ')';
		 echo '</option>';
	  }
      echo '</select></td>';

      echo '<td><select name="ItemMID11" size="1"><option value="0">-- Select Below --</option>';
	  while($row11 = mssql_fetch_array($cboMarkets11))
	  {
		 echo '<option value="'. $row11["RecordID"] . '"';
		 if ($_SESSION['FORMItemMID11'] == $row11["RecordID"]) echo ' selected="selected"';
		 echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		 echo $row11["ProductName"] . ' ~ ' . $row11["PartNumber"];

		 if ($row11['IsShowCSRPrice'] == 1 && $row11['RetailPrice'] != 0) echo ' (' . number_format($row11['RetailPrice'], 2, '.', '') . ')';
		 echo '</option>';
	  }
      echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT11" size="1"><option value="0">-- Select Below --</option>';
            while($row11 = mssql_fetch_array($cboMediaKit11))
            {
                echo '<option value="'. $row11["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT11'] == $row11["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row11["ProductName"] . ' ~ ' . $row11["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
    }
    ?>

    <td><input type="text" name="ItemQty11" size="2" value="<?php echo $_SESSION['FORMItemQty11']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation11" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation11'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation11'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation11'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation11'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation11" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation11'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation11'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation11'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation11'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation11" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation11'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation11'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation11'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation11'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';
		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation11" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation11'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation11'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation11'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation11'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation11'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation11" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation1'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation1'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation1'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
                echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation1'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
            // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation1'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation11'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation11'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation11'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation11'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation11'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation1" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation11" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
        echo '<td><select name="ItemID12" size="1"><option value="0">-- Select Below --</option>';
	    while($row12 = mssql_fetch_array($cboProducts12_Int))
	    {
		 echo '<option value="'. $row12["RecordID"] . '"';
		 if ($_SESSION['FORMItemID12'] == $row12["RecordID"]) echo ' selected="selected"';
		 echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		 echo $row12["ProductName"] . ' ~ ' . $row12["PartNumber"];;

		 if ($row12['IsShowCSRPrice'] == 1 && $row12['RetailPrice'] != 0) echo ' (' . number_format($row12['RetailPrice'], 2, '.', '') . ')';
		 echo '</option>';
        }
        echo '</select></td>';

        echo '<td><select name="ItemMID12" size="1"><option value="0">-- Select Below --</option>';
	    while($row12 = mssql_fetch_array($cboMarkets12_Int))
	    {
		 echo '<option value="'. $row12["RecordID"] . '"';
		 if ($_SESSION['FORMItemMID12'] == $row12["RecordID"]) echo ' selected="selected"';
		 echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		 echo $row12["ProductName"] . ' ~ ' . $row12["PartNumber"];;

		 if ($row12['IsShowCSRPrice'] == 1 && $row12['RetailPrice'] != 0) echo ' (' . number_format($row12['RetailPrice'], 2, '.', '') . ')';
		 echo '</option>';
        }
        echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT12" size="1"><option value="0">-- Select Below --</option>';
            while($row12 = mssql_fetch_array($cboMediaKit12_Int))
            {
                echo '<option value="'. $row12["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT12'] == $row12["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row12["ProductName"] . ' ~ ' . $row12["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
      echo '<td><select name="ItemID12" size="1"><option value="0">-- Select Below --</option>';
	  while($row12 = mssql_fetch_array($cboProducts12))
	  {
		echo '<option value="'. $row12["RecordID"] . '"';
		if ($_SESSION['FORMItemID12'] == $row12["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
        echo $row12["ProductName"] . ' ~ ' . $row12["PartNumber"];;

		if ($row12['IsShowCSRPrice'] == 1 && $row12['RetailPrice'] != 0) echo ' (' . number_format($row12['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
      echo '<td><select name="ItemMID12" size="1"><option value="0">-- Select Below --</option>';
	  while($row12 = mssql_fetch_array($cboMarkets12))
	  {
		echo '<option value="'. $row12["RecordID"] . '"';
		if ($_SESSION['FORMItemMID12'] == $row12["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
        echo $row12["ProductName"] . ' ~ ' . $row12["PartNumber"];;

		if ($row12['IsShowCSRPrice'] == 1 && $row12['RetailPrice'] != 0) echo ' (' . number_format($row12['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT12" size="1"><option value="0">-- Select Below --</option>';
            while($row12 = mssql_fetch_array($cboMediaKit12))
            {
                echo '<option value="'. $row12["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT12'] == $row12["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row12["ProductName"] . ' ~ ' . $row12["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty12" size="2" value="<?php echo $_SESSION['FORMItemQty12']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation12" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation12'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation12'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation12'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation12'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';
		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation12" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation12'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation12'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation12'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation12'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation12" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation12'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation12'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation12'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation12'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation12" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation12'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation12'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation12'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation12'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation12'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
            echo '<select name="ItemStockLocation12" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation2'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation2'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation2'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation2'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
            // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation2'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation12'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation12'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation12'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation12'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation12'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation2" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation12" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
       echo '<td><select name="ItemID13" size="1"><option value="0">-- Select Below --</option>';
	   while($row13 = mssql_fetch_array($cboProducts13_Int))
	   {
		echo '<option value="'. $row13["RecordID"] . '"';
		if ($_SESSION['FORMItemID13'] == $row13["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row13["ProductName"] . ' ~ ' . $row13["PartNumber"];;

		if ($row13['IsShowCSRPrice'] == 1 && $row13['RetailPrice'] != 0) echo ' (' . number_format($row13['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
       echo '<td><select name="ItemMID13" size="1"><option value="0">-- Select Below --</option>';
	   while($row13 = mssql_fetch_array($cboMarkets13_Int))
	   {
		echo '<option value="'. $row13["RecordID"] . '"';
		if ($_SESSION['FORMItemMID13'] == $row13["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row13["ProductName"] . ' ~ ' . $row13["PartNumber"];;

		if ($row13['IsShowCSRPrice'] == 1 && $row13['RetailPrice'] != 0) echo ' (' . number_format($row13['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT13" size="1"><option value="0">-- Select Below --</option>';
            while($row13 = mssql_fetch_array($cboMediaKit13_Int))
            {
                echo '<option value="'. $row13["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT13'] == $row13["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row13["ProductName"] . ' ~ ' . $row13["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID13" size="1"><option value="0">-- Select Below --</option>';
	  while($row13 = mssql_fetch_array($cboProducts13))
	  {
		echo '<option value="'. $row13["RecordID"] . '"';
		if ($_SESSION['FORMItemID13'] == $row13["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row13["ProductName"] . ' ~ ' . $row13["PartNumber"];;

		if ($row13['IsShowCSRPrice'] == 1 && $row13['RetailPrice'] != 0) echo ' (' . number_format($row13['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	  echo '<td><select name="ItemMID13" size="1"><option value="0">-- Select Below --</option>';
	  while($row13 = mssql_fetch_array($cboMarkets13))
	  {
		echo '<option value="'. $row13["RecordID"] . '"';
		if ($_SESSION['FORMItemMID13'] == $row13["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row13["ProductName"] . ' ~ ' . $row13["PartNumber"];;

		if ($row13['IsShowCSRPrice'] == 1 && $row13['RetailPrice'] != 0) echo ' (' . number_format($row13['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT13" size="1"><option value="0">-- Select Below --</option>';
            while($row13 = mssql_fetch_array($cboMediaKit13))
            {
                echo '<option value="'. $row13["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT13'] == $row13["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row13["ProductName"] . ' ~ ' . $row13["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty13" size="2" value="<?php echo $_SESSION['FORMItemQty13']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation13" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation13'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation13'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation13'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation13'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation13" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation13'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation13'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation13'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation13'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation13" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation13'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation13'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation13'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation13'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation13" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation13'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation13'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation13'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation13'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation13'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation13" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation3'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation3'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation3'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation3'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation3'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation13'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation13'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation13'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation13'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation13'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation3" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation13" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID14" size="1"><option value="0">-- Select Below --</option>';
	  while($row14 = mssql_fetch_array($cboProducts14_Int))
	  {
		echo '<option value="'. $row14["RecordID"] . '"';
		if ($_SESSION['FORMItemID14'] == $row14["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row14["ProductName"] . ' ~ ' . $row14["PartNumber"];;

		if ($row14['IsShowCSRPrice'] == 1 && $row14['RetailPrice'] != 0) echo ' (' . number_format($row14['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	  echo '<td><select name="ItemMID14" size="1"><option value="0">-- Select Below --</option>';
	  while($row14 = mssql_fetch_array($cboMarkets14_Int))
	  {
		echo '<option value="'. $row14["RecordID"] . '"';
		if ($_SESSION['FORMItemMID14'] == $row14["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row14["ProductName"] . ' ~ ' . $row14["PartNumber"];;

		if ($row14['IsShowCSRPrice'] == 1 && $row14['RetailPrice'] != 0) echo ' (' . number_format($row14['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT14" size="1"><option value="0">-- Select Below --</option>';
            while($row14 = mssql_fetch_array($cboMediaKit14_Int))
            {
                echo '<option value="'. $row14["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT14'] == $row14["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row14["ProductName"] . ' ~ ' . $row14["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	     echo '<td><select name="ItemID14" size="1"><option value="0">-- Select Below --</option>';
	     while($row14 = mssql_fetch_array($cboProducts14))
	     {
		  echo '<option value="'. $row14["RecordID"] . '"';
          if ($_SESSION['FORMItemID14'] == $row14["RecordID"]) echo ' selected="selected"';
		  echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		  echo $row14["ProductName"] . ' ~ ' . $row14["PartNumber"];;

		  if ($row14['IsShowCSRPrice'] == 1 && $row14['RetailPrice'] != 0) echo ' (' . number_format($row14['RetailPrice'], 2, '.', '') . ')';
		  echo '</option>';
         }
         echo '</select></td>';

	     echo '<td><select name="ItemMID14" size="1"><option value="0">-- Select Below --</option>';
	     while($row14 = mssql_fetch_array($cboMarkets14))
	     {
		  echo '<option value="'. $row14["RecordID"] . '"';
          if ($_SESSION['FORMItemMID14'] == $row14["RecordID"]) echo ' selected="selected"';
		  echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		  echo $row14["ProductName"] . ' ~ ' . $row14["PartNumber"];;

		  if ($row14['IsShowCSRPrice'] == 1 && $row14['RetailPrice'] != 0) echo ' (' . number_format($row14['RetailPrice'], 2, '.', '') . ')';
		  echo '</option>';
         }
         echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT14" size="1"><option value="0">-- Select Below --</option>';
            while($row14 = mssql_fetch_array($cboMediaKit14))
            {
                echo '<option value="'. $row14["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT14'] == $row14["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row14["ProductName"] . ' ~ ' . $row14["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

    ?>

    <td><input type="text" name="ItemQty14" size="2" value="<?php echo $_SESSION['FORMItemQty14']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation14" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation14'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation14'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation14'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation14'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation14" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation14'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation14'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation14'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation14'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation14" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation14'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation14'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation14'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation14'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation14" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation14'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation14'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation14'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation14'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation14'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation14" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation4'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation4'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation4'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation4'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation4'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation14'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation14'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation14'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation14'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation14'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation4" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation14" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID15" size="1"><option value="0">-- Select Below --</option>';
	  while($row15 = mssql_fetch_array($cboProducts15_Int))
	  {
		echo '<option value="'. $row15["RecordID"] . '"';
		if ($_SESSION['FORMItemID15'] == $row15["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row15["ProductName"] . ' ~ ' . $row15["PartNumber"];;

		if ($row15['IsShowCSRPrice'] == 1 && $row15['RetailPrice'] != 0) echo ' (' . number_format($row15['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

	  echo '<td><select name="ItemMID15" size="1"><option value="0">-- Select Below --</option>';
	  while($row15 = mssql_fetch_array($cboMarkets15_Int))
	  {
		echo '<option value="'. $row15["RecordID"] . '"';
		if ($_SESSION['FORMItemMID15'] == $row15["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row15["ProductName"] . ' ~ ' . $row15["PartNumber"];;

		if ($row15['IsShowCSRPrice'] == 1 && $row15['RetailPrice'] != 0) echo ' (' . number_format($row15['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT15" size="1"><option value="0">-- Select Below --</option>';
            while($row15 = mssql_fetch_array($cboMediaKit15_Int))
            {
                echo '<option value="'. $row15["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT15'] == $row15["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row15["ProductName"] . ' ~ ' . $row15["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID15" size="1"><option value="0">-- Select Below --</option>';
	  while($row15 = mssql_fetch_array($cboProducts15))
	  {
		echo '<option value="'. $row15["RecordID"] . '"';
		if ($_SESSION['FORMItemID15'] == $row15["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row15["ProductName"] . ' ~ ' . $row15["PartNumber"];;

		if ($row15['IsShowCSRPrice'] == 1 && $row15['RetailPrice'] != 0) echo ' (' . number_format($row15['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

	  echo '<td><select name="ItemMID15" size="1"><option value="0">-- Select Below --</option>';
	  while($row15 = mssql_fetch_array($cboMarkets15))
	  {
		echo '<option value="'. $row15["RecordID"] . '"';
		if ($_SESSION['FORMItemMID15'] == $row15["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row15["ProductName"] . ' ~ ' . $row15["PartNumber"];;

		if ($row15['IsShowCSRPrice'] == 1 && $row15['RetailPrice'] != 0) echo ' (' . number_format($row15['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT15" size="1"><option value="0">-- Select Below --</option>';
            while($row15 = mssql_fetch_array($cboMediaKit15))
            {
                echo '<option value="'. $row15["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT15'] == $row15["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row15["ProductName"] . ' ~ ' . $row15["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty15" size="2" value="<?php echo $_SESSION['FORMItemQty15']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation15" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation15'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation15'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation15'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation15'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation15" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation15'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation15'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation15'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation15'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation15" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation15'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation15'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation15'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation15'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation15" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation15'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation15'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation15'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation15'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation15'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation15" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation5'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation5'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation5'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation5'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation5'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation15'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation15'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation15'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation15'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation15'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation5" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation15" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID16" size="1"><option value="0">-- Select Below --</option>';
	  while($row16 = mssql_fetch_array($cboProducts16_Int))
	  {
		echo '<option value="'. $row16["RecordID"] . '"';
		if ($_SESSION['FORMItemID16'] == $row16["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row16["ProductName"] . ' ~ ' . $row16["PartNumber"];;

		if ($row16['IsShowCSRPrice'] == 1 && $row16['RetailPrice'] != 0) echo ' (' . number_format($row16['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

	  echo '<td><select name="ItemMID16" size="1"><option value="0">-- Select Below --</option>';
	  while($row16 = mssql_fetch_array($cboMarkets16_Int))
	  {
		echo '<option value="'. $row16["RecordID"] . '"';
		if ($_SESSION['FORMItemMID16'] == $row16["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row16["ProductName"] . ' ~ ' . $row16["PartNumber"];;

		if ($row16['IsShowCSRPrice'] == 1 && $row16['RetailPrice'] != 0) echo ' (' . number_format($row16['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT16" size="1"><option value="0">-- Select Below --</option>';
            while($row16 = mssql_fetch_array($cboMediaKit16_Int))
            {
                echo '<option value="'. $row16["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT16'] == $row16["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row16["ProductName"] . ' ~ ' . $row16["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID16" size="1"><option value="0">-- Select Below --</option>';
	  while($row16 = mssql_fetch_array($cboProducts16))
	  {
		echo '<option value="'. $row16["RecordID"] . '"';
		if ($_SESSION['FORMItemID16'] == $row16["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row16["ProductName"] . ' ~ ' . $row16["PartNumber"];;

		if ($row16['IsShowCSRPrice'] == 1 && $row16['RetailPrice'] != 0) echo ' (' . number_format($row16['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	  echo '<td><select name="ItemMID16" size="1"><option value="0">-- Select Below --</option>';
	  while($row16 = mssql_fetch_array($cboMarkets16))
	  {
		echo '<option value="'. $row16["RecordID"] . '"';
		if ($_SESSION['FORMItemMID16'] == $row16["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row16["ProductName"] . ' ~ ' . $row16["PartNumber"];;

		if ($row16['IsShowCSRPrice'] == 1 && $row16['RetailPrice'] != 0) echo ' (' . number_format($row16['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT16" size="1"><option value="0">-- Select Below --</option>';
            while($row16 = mssql_fetch_array($cboMediaKit16))
            {
                echo '<option value="'. $row16["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT16'] == $row16["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row16["ProductName"] . ' ~ ' . $row16["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty16" size="2" value="<?php echo $_SESSION['FORMItemQty16']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{
        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation16" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation16'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation16'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation16'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation16'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation16" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation16'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation16'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation16'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation16'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation16" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation16'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation16'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation16'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation16'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation16" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation16'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation16'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation16'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation16'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation16'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation16" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation6'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    //echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation6'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation6'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation6'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation6'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation16'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation16'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation16'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation16'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation16'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation6" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation16" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID17" size="1"><option value="0">-- Select Below --</option>';
	  while($row17 = mssql_fetch_array($cboProducts17_Int))
	  {
		echo '<option value="'. $row17["RecordID"] . '"';
		if ($_SESSION['FORMItemID17'] == $row17["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row17["ProductName"] . ' ~ ' . $row17["PartNumber"];;

		if ($row17['IsShowCSRPrice'] == 1 && $row17['RetailPrice'] != 0) echo ' (' . number_format($row17['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	  echo '<td><select name="ItemMID17" size="1"><option value="0">-- Select Below --</option>';
	  while($row17 = mssql_fetch_array($cboMarkets17_Int))
	  {
		echo '<option value="'. $row17["RecordID"] . '"';
		if ($_SESSION['FORMItemMID17'] == $row17["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row17["ProductName"] . ' ~ ' . $row17["PartNumber"];;

		if ($row17['IsShowCSRPrice'] == 1 && $row17['RetailPrice'] != 0) echo ' (' . number_format($row17['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT17" size="1"><option value="0">-- Select Below --</option>';
            while($row17 = mssql_fetch_array($cboMediaKit17_Int))
            {
                echo '<option value="'. $row17["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT17'] == $row17["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row17["ProductName"] . ' ~ ' . $row17["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID17" size="1"><option value="0">-- Select Below --</option>';
	  while($row17 = mssql_fetch_array($cboProducts17))
	  {
		echo '<option value="'. $row17["RecordID"] . '"';
		if ($_SESSION['FORMItemID17'] == $row17["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row17["ProductName"] . ' ~ ' . $row17["PartNumber"];;

		if ($row17['IsShowCSRPrice'] == 1 && $row17['RetailPrice'] != 0) echo ' (' . number_format($row17['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';
     
	  echo '<td><select name="ItemMID17" size="1"><option value="0">-- Select Below --</option>';
	  while($row17 = mssql_fetch_array($cboMarkets17))
	  {
		echo '<option value="'. $row17["RecordID"] . '"';
		if ($_SESSION['FORMItemMID17'] == $row17["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row17["ProductName"] . ' ~ ' . $row17["PartNumber"];;

		if ($row17['IsShowCSRPrice'] == 1 && $row17['RetailPrice'] != 0) echo ' (' . number_format($row17['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT17" size="1"><option value="0">-- Select Below --</option>';
            while($row17 = mssql_fetch_array($cboMediaKit17))
            {
                echo '<option value="'. $row17["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT17'] == $row17["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row17["ProductName"] . ' ~ ' . $row17["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty17" size="2" value="<?php echo $_SESSION['FORMItemQty17']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation17" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation17'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation17'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation17'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation17'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation17" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation17'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation17'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation17'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation17'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation17" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation17'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation17'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation17'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation17'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation17" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation17'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation17'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation17'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation17'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation17'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation17" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation7'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation7'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation7'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		        echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation7'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation7'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation17'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation17'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation17'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation17'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation17'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation7" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation17" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID18" size="1"><option value="0">-- Select Below --</option>';
	  while($row18 = mssql_fetch_array($cboProducts18_Int))
	  {
		echo '<option value="'. $row18["RecordID"] . '"';
		if ($_SESSION['FORMItemID18'] == $row18["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row18["ProductName"] . ' ~ ' . $row18["PartNumber"];;

		if ($row18['IsShowCSRPrice'] == 1 && $row18['RetailPrice'] != 0) echo ' (' . number_format($row18['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

	  echo '<td><select name="ItemMID18" size="1"><option value="0">-- Select Below --</option>';
	  while($row18 = mssql_fetch_array($cboMarkets18_Int))
	  {
		echo '<option value="'. $row18["RecordID"] . '"';
		if ($_SESSION['FORMItemMID18'] == $row18["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row18["ProductName"] . ' ~ ' . $row18["PartNumber"];;

		if ($row18['IsShowCSRPrice'] == 1 && $row18['RetailPrice'] != 0) echo ' (' . number_format($row18['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT18" size="1"><option value="0">-- Select Below --</option>';
            while($row18 = mssql_fetch_array($cboMediaKit18_Int))
            {
                echo '<option value="'. $row18["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT18'] == $row18["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row18["ProductName"] . ' ~ ' . $row18["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	  echo '<td><select name="ItemID18" size="1"><option value="0">-- Select Below --</option>';
	  while($row18 = mssql_fetch_array($cboProducts18))
	  {
		echo '<option value="'. $row18["RecordID"] . '"';
		if ($_SESSION['FORMItemID18'] == $row18["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row18["ProductName"] . ' ~ ' . $row18["PartNumber"];;

		if ($row18['IsShowCSRPrice'] == 1 && $row18['RetailPrice'] != 0) echo ' (' . number_format($row18['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

	  echo '<td><select name="ItemMID18" size="1"><option value="0">-- Select Below --</option>';
	  while($row18 = mssql_fetch_array($cboMarkets18))
	  {
		echo '<option value="'. $row18["RecordID"] . '"';
		if ($_SESSION['FORMItemMID18'] == $row18["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row18["ProductName"] . ' ~ ' . $row18["PartNumber"];;

		if ($row18['IsShowCSRPrice'] == 1 && $row18['RetailPrice'] != 0) echo ' (' . number_format($row18['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
       }
       echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT18" size="1"><option value="0">-- Select Below --</option>';
            while($row18 = mssql_fetch_array($cboMediaKit18))
            {
                echo '<option value="'. $row18["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT18'] == $row18["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row18["ProductName"] . ' ~ ' . $row18["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }

   ?>

    <td><input type="text" name="ItemQty18" size="2" value="<?php echo $_SESSION['FORMItemQty18']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation18" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation18'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation18'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation18'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation18'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation18" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation18'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation18'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation18'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation18'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation18" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation18'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation18'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation18'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation18'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation18" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation18'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation18'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation18'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation18'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation18'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation18" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation8'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation8'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation8'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation8'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation8'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation18'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation18'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation18'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation18'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation18'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation8" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation18" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	 echo '<td><select name="ItemID19" size="1"><option value="0">-- Select Below --</option>';
	 while($row19 = mssql_fetch_array($cboProducts19_Int))
	 {
		echo '<option value="'. $row19["RecordID"] . '"';
		if ($_SESSION['FORMItemID19'] == $row19["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row19["ProductName"] . ' ~ ' . $row19["PartNumber"];;

		if ($row19['IsShowCSRPrice'] == 1 && $row19['RetailPrice'] != 0) echo ' (' . number_format($row19['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

	 echo '<td><select name="ItemMID19" size="1"><option value="0">-- Select Below --</option>';
	 while($row19 = mssql_fetch_array($cboMarkets19_Int))
	 {
		echo '<option value="'. $row19["RecordID"] . '"';
		if ($_SESSION['FORMItemMID19'] == $row19["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row19["ProductName"] . ' ~ ' . $row19["PartNumber"];;

		if ($row19['IsShowCSRPrice'] == 1 && $row19['RetailPrice'] != 0) echo ' (' . number_format($row19['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT19" size="1"><option value="0">-- Select Below --</option>';
            while($row19 = mssql_fetch_array($cboMediaKit19_Int))
            {
                echo '<option value="'. $row19["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT19'] == $row19["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row19["ProductName"] . ' ~ ' . $row19["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
     else
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	 echo '<td><select name="ItemID19" size="1"><option value="0">-- Select Below --</option>';
	 while($row19 = mssql_fetch_array($cboProducts19))
	 {
		echo '<option value="'. $row19["RecordID"] . '"';
		if ($_SESSION['FORMItemID19'] == $row19["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row19["ProductName"] . ' ~ ' . $row19["PartNumber"];;

		if ($row19['IsShowCSRPrice'] == 1 && $row19['RetailPrice'] != 0) echo ' (' . number_format($row19['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

	 echo '<td><select name="ItemMID19" size="1"><option value="0">-- Select Below --</option>';
	 while($row19 = mssql_fetch_array($cboMarkets19))
	 {
		echo '<option value="'. $row19["RecordID"] . '"';
		if ($_SESSION['FORMItemMID19'] == $row19["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row19["ProductName"] . ' ~ ' . $row19["PartNumber"];;

		if ($row19['IsShowCSRPrice'] == 1 && $row19['RetailPrice'] != 0) echo ' (' . number_format($row19['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT19" size="1"><option value="0">-- Select Below --</option>';
            while($row19 = mssql_fetch_array($cboMediaKit19))
            {
                echo '<option value="'. $row19["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT19'] == $row19["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row19["ProductName"] . ' ~ ' . $row19["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
     }
   ?>

    <td><input type="text" name="ItemQty19" size="2" value="<?php echo $_SESSION['FORMItemQty19']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation19" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation19'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation19'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation19'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation19" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation19'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation19'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation19'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation19" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation19'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation19'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation19'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation19" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation19'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation19'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation19'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation19'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation19" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation9'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    //echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation9'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation9'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation9'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation9'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation19'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation19'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation19'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation19'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation9" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation19" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr>

    <!-- GMC - 11/07/08 - To divide Domestic and International Products -->
    <?php

     if($blnIsInternational == 1)
     {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	 echo '<td><select name="ItemID20" size="1"><option value="0">-- Select Below --</option>';
	 while($row20 = mssql_fetch_array($cboProducts20_Int))
	 {
		echo '<option value="'. $row20["RecordID"] . '"';
		if ($_SESSION['FORMItemID20'] == $row20["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row20["ProductName"] . ' ~ ' . $row20["PartNumber"];;

		if ($row20['IsShowCSRPrice'] == 1 && $row20['RetailPrice'] != 0) echo ' (' . number_format($row20['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
    echo '</select></td>';

	 echo '<td><select name="ItemMID20" size="1"><option value="0">-- Select Below --</option>';
	 while($row20 = mssql_fetch_array($cboMarkets20_Int))
	 {
		echo '<option value="'. $row20["RecordID"] . '"';
		if ($_SESSION['FORMItemMID20'] == $row20["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row20["ProductName"] . ' ~ ' . $row20["PartNumber"];;

		if ($row20['IsShowCSRPrice'] == 1 && $row20['RetailPrice'] != 0) echo ' (' . number_format($row20['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
    echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT20" size="1"><option value="0">-- Select Below --</option>';
            while($row20 = mssql_fetch_array($cboMediaKit20_Int))
            {
                echo '<option value="'. $row20["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT20'] == $row20["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row20["ProductName"] . ' ~ ' . $row20["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
    }
    else
    {
        // GMC - 08/16/11 - To divide Products and Marketing Materials
	 echo '<td><select name="ItemID20" size="1"><option value="0">-- Select Below --</option>';
	 while($row20 = mssql_fetch_array($cboProducts20))
	 {
		echo '<option value="'. $row20["RecordID"] . '"';
		if ($_SESSION['FORMItemID20'] == $row20["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row20["ProductName"] . ' ~ ' . $row20["PartNumber"];;

		if ($row20['IsShowCSRPrice'] == 1 && $row20['RetailPrice'] != 0) echo ' (' . number_format($row20['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

	 echo '<td><select name="ItemMID20" size="1"><option value="0">-- Select Below --</option>';
	 while($row20 = mssql_fetch_array($cboMarkets20))
	 {
		echo '<option value="'. $row20["RecordID"] . '"';
		if ($_SESSION['FORMItemMID20'] == $row20["RecordID"]) echo ' selected="selected"';
		echo '>';

         // GMC - 08/03/09 - Add Part Number by JS
		echo $row20["ProductName"] . ' ~ ' . $row20["PartNumber"];;

		if ($row20['IsShowCSRPrice'] == 1 && $row20['RetailPrice'] != 0) echo ' (' . number_format($row20['RetailPrice'], 2, '.', '') . ')';
		echo '</option>';
	 }
     echo '</select></td>';

        // GMC - 03/26/12 - MediaKit Process
        if($_SESSION['OrderType'] == 'MediaKit')
        {
            echo '<td><select name="ItemMKT20" size="1"><option value="0">-- Select Below --</option>';
            while($row20 = mssql_fetch_array($cboMediaKit20))
            {
                echo '<option value="'. $row20["RecordID"] . '"';
                if ($_SESSION['FORMItemMKT20'] == $row20["RecordID"]) echo ' selected="selected"';
                echo '>';
                echo $row20["ProductName"] . ' ~ ' . $row20["PartNumber"];
                echo '</option>';
            }
            echo '</select></td>';
        }
    }
   ?>

    <td><input type="text" name="ItemQty20" size="2" value="<?php echo $_SESSION['FORMItemQty20']; ?>" /></td>
    <td><?php
    if ($_SESSION['UserTypeID'] != 1)
	{

        // GMC - 05/06/09 - FedEx Netherlands
        if($_SESSION['CustomerTypeIDFedExEu'] == 1 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation20" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation20'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation20'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation20'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 2 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation20" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

            echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation20'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation20'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation20'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 3 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation20" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation20'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation20'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation20'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else if($_SESSION['CustomerTypeIDFedExEu'] == 4 && $_SESSION['CountryCodeFedExEu'] != '')
        {
            echo '<select name="ItemStockLocation20" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
            // GMC - 08/03/09 - Add the standard distribution sites by JS
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
		         echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }
            */

		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation20'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation20'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

		    echo '<option value="FEDEXNETH"'; if ($_SESSION['FORMItemStockLocation20'] == 'FEDEXNETH') echo ' selected="selected"'; echo '>FedEx EU</option>';
		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation20'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

		    echo '</select>';
        }
        else
        {
		    echo '<select name="ItemStockLocation20" size="1">';

            // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
            /*
		    // GMC - 11/04/08 - Ship from Stock Location + FCA + International Commented out for Now
		    echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation10'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
		    // echo '<option value="INTL"'; if ($_SESSION['FORMItemStockLocation10'] == 'INTL') echo ' selected="selected"'; echo '>International</option>';

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation10'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 09/03/09 - To Replace Henderson Corp for Athena Las Vegas
		    // echo '<option value="HEND CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'HEND CORP') echo ' selected="selected"'; echo '>Henderson</option>';
            // GMC - 12/04/09 - Las Vegas Facility for JStancarone and Atobler for Now
            // GMC - 01/04/10 - Las Vegas and Netherlands for: WPowers (92), DSidney (133), JChavella (53), ATobler (39), JStancarone (35)
            // GMC - 01/19/10 - Las Vegas for MMartinez (60)
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 53 || $_SESSION['UserID'] == 39 || $_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 60)
            {
           		echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation10'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';
            }

            // GMC - 04/30/09 - Take Ventura from the Drop Down
		    // echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation10'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            */

  		    echo '<option value="ATHENA-LV"'; if ($_SESSION['FORMItemStockLocation20'] == 'ATHENA-LV') echo ' selected="selected"'; echo '>Las Vegas</option>';

            // GMC - 02/23/10 - FCA for WPowers (92), DSidney (133) by JS
            if($_SESSION['UserID'] == 92 || $_SESSION['UserID'] == 133)
            {
		        echo '<option value="MAIN"'; if ($_SESSION['FORMItemStockLocation20'] == 'MAIN') echo ' selected="selected"'; echo '>FCA</option>';
            }

		    echo '<option value="TRADESHOW"'; if ($_SESSION['FORMItemStockLocation20'] == 'TRADESHOW') echo ' selected="selected"'; echo '>Tradeshow</option>';

            // GMC - 02/02/10 Make Ventura Corp available for HCordova (49) and CFelix (41)
            if($_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 41)
            {
                echo '<option value="VEN CORP"'; if ($_SESSION['FORMItemStockLocation20'] == 'VEN CORP') echo ' selected="selected"'; echo '>Ventura</option>';
            }

            // GMC - 03/07/12 - WESTWAY Selection in Shipping From Location (Sidney, Oronoz, Stancarone)
            if($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 133 || $_SESSION['UserID'] == 171 || $_SESSION['UserID'] == 44)
            {
		        echo '<option value="WESTWAY"'; if ($_SESSION['FORMItemStockLocation19'] == 'WESTWAY') echo ' selected="selected"'; echo '>WESTWAY</option>';
            }

            echo '</select>';
        }

    }
	else
    {
        // GMC - 02/01/10 - Changes to Fulfillment Centers - No FCA - Las Vegas First by JS
		// echo '<input type="hidden" name="ItemStockLocation10" value="MAIN" />&nbsp;';
		echo '<input type="hidden" name="ItemStockLocation20" value="ATHENA-LV" />&nbsp;';
    }

    ?>	</td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>

<tr><td colspan="3"><input type="submit" name="cmdContinue" value="Continue" class="formSubmit" /></td></tr>

</table>

</table>

</form>

<p>&nbsp;</p>
