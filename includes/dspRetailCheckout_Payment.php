<p>
We accept most major Credit Cards.
</p>

<form action="/retail/checkout.php" method="post">
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
    </select>

    <select name="CC_ExpYear" size="1">
    <!--
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

</table>

<table width="100%" cellpadding="0" cellspacing="5">

<!-- GMC - 06/02/09 - June 2009 Promotion 06/05 to 06/19 - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 06/29/09 - July 2009 Promotion "New Beauty" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 09/17/09 - September 2009 Promotion "Pretty City" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 09/24/09 - September 2009 Promotion "Meg" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 10/20/09 - October 2009 Promotion "OK Weekly" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 10/30/09 - October 2009 Promotion "Hair Loss Talk" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 11/12/09 - November 2009 Promotion "Twitter" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 11/16/09 - November 2009 Promotion "Leads" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 11/25/09 - November 2009 Promotion "Leads" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 12/03/09 - December 2009 Promotion "Rome" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 04/28/10 - April 2010 Promotion "Newsletter" - 15% Off on Total Purchased Value (any product)-->
<!-- GMC - 06/21/10 - June 2010 Promotion "Wahanda" - 15% Off on Total Purchased Value (any product)-->
<!-- GMC - 07/02/10 - July 2010 Promotion "ISPA" - 20% Off on Total Purchased Value (any product)-->
<!-- GMC - 07/26/10 - July 2010 Promotion "YANDR" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 08/20/10 - August 2010 Promotion "LSE001" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 09/28/10 - September 2010 Promotion "TWEET" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 09/28/10 - September 2010 Promotion "TUBEIT" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 10/06/10 - October 2010 Promotion "AMERSPA" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 10/18/10 - October 2010 Promotion "LASHES" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 10/30/10 - October 2010 Promotion "MASCARA" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 12/20/10 - December 2010 Promotion "JOURNAL" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 01/17/10 - January 2011 Promotion "COUPON" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 01/18/10 - January 2011 Promotion "ORDER" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 03/01/11 - March 2011 Promotion "LASHWORLD" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 04/04/11 - April 2011 Promotion "CLIQUE" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 06/14/11 - June 2011 Promotion "PKW" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 09/22/11 - September 2011 Promotion "GIFT" - 15% Off on Total Purchased Value (any product) -->
<!-- GMC - 11/22/11 - November 2011 Promotion "BF2011" - 20% Off on Total Purchased Value (any product) -->
<!-- GMC - 04/16/12 - April 2012 Promotion "BUZZ" - 15% Off on Total Purchased Value (any product) -->

<tr>
    <!--<th colspan="3"><font color="red"><b>People's Magazine Promo Code 15% Off<br>(Please enter Promo Code you got from People's Magazine Web Site)</b></font></th>-->
    <!-- GMC - 09/10/09 - Friend Promo Code -->
    <th colspan="18"><font color="red"><b>Promo Code</b></font></th>
    <td><input type="text" name="Promo_Code" size="10" value="" /></td>
</tr>

</table>

<table width="100%" cellpadding="0" cellspacing="5">

<tr>
    <td colspan="3"><input type="submit" name="cmdSetPayment" value="Continue" class="formSubmit" /></td>
</tr>

</table>
</form>
