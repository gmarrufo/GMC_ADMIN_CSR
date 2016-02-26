<p>Please select a payment method.</p>

<form action="/pro/checkout.php" method="post">
<table width="100%" cellpadding="0" cellspacing="5">

<tr>
	<td><input type="radio" name="PaymentType" value="CreditCard" checked="checked" />
    <td colspan="2" style="font-weight:bold;">Pay By Credit Card</td>
</tr>

<tr>
    <td width="50">&nbsp;</td>
    <th width="140">Credit Card Number:</th>
    <td width="*"><input type="text" name="CC_Number" size="16" value="" /></td>
</tr>

<tr>
    <th colspan="2">Cardholder Name:</th>
    <td><input type="text" name="CC_Cardholder" size="25" value="" /></td>
</tr>

<!-- GMC - Fix the year 2010 CC -->
<!-- GMC - Fix the year 2011 CC -->
<!-- GMC - Fix the year 2012 CC -->
<!-- GMC - Fix the year 2013 CC -->
<tr>
    <th colspan="2">Expiration:</th>
    <td><select name="CC_ExpMonth" size="1">
    <option value="01">01 - JAN</option>
    <option value="02">02 - FEB</option>
    <option value="03">03 - MAR</option>
    <option value="04">04 - APR</option>
    <option value="05">05 - MAY</option>
    <option value="06">06 - JUN</option>
    <option value="07">07 - JUL</option>
    <option value="08">08 - AUG</option>
    <option value="09">09 - SEP</option>
    <option value="10">10 - OCT</option>
    <option value="11">11 - NOV</option>
    <option value="12">12 - DEC</option>
    </select> <select name="CC_ExpYear" size="1">
    <!--
    <option value="08">2008</option>
    <option value="09">2009</option>
    <option value="10">2010</option>
    <option value="11">2011</option>
    <option value="12">2012</option>
    <option value="13">2013</option>
    -->
    <option value="14">2014</option>
    <option value="15">2015</option>
    <option value="16">2016</option>
    <option value="17">2017</option>
    <option value="18">2018</option>
    <option value="19">2019</option>
    <option value="20">2020</option>
    <option value="20">2021</option>
    <option value="20">2022</option>
    <option value="20">2023</option>
    </select></td>
</tr>

<tr>
    <th colspan="2">Security Code:</th>
    <td><input type="text" name="CC_CVV" size="3" value="" /></td>
</tr>

<tr>
    <th colspan="2">Billing Postal Code:</th>
    <td><input type="text" name="CC_BillingPostalCode" size="10" value="" /></td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>

<!-- GMC - 01/03/12 - Promo Code for Resellers -->
<!-- GMC - 09/12/13 - Cancel Promo Code for Resellers -->
<!-- GMC - 02/02/14 - Put Back Promo Code for Resellers -->
<tr>
    <th colspan="2">Promo Code:</th>
    <td><input type="text" name="CC_PromoCode" size="20" value="" /></td>
</tr>

<?php

if (isset($_SESSION['CustomerTerms']) && $_SESSION['CustomerTerms'] == 1)
{
	echo '<tr>
		<td><input type="radio" name="PaymentType" value="Terms" />
		<td colspan="2" style="font-weight:bold;">Authorized Terms</td>
	</tr>
	
	<tr>
		<th colspan="2">PO Number:</th>
		<td><input type="text" name="PO_Number" size="30" value="" /></td>
	</tr>
	
	<tr><td colspan="3">&nbsp;</td></tr>';
}
?>

<tr>
    <td colspan="3"><input type="submit" name="cmdSetPayment" value="Continue" class="formSubmit" /></td>
</tr>

</table>
</form>
