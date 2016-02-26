<?php
if (isset($confirmation)) echo '<p class="confirmation">' . $confirmation . '</p>';
if (isset($pageerror)) echo '<p class="error">' . $pageerror . '</p>';
?>

<!-- GMC - 04/30/09 - Add Some Text Content Changes -->
<p>Welcome to the RevitaLash&reg; dealer Reseller website.</p>

<p>If this is your first time purchasing RevitaLash&reg;, click <a href="/pro/register.php"><b>HERE</b></a> to register as a <b>RESELLER</b>. You will be prompted through a series of questions and asked to agree to our <b><u>Terms and Conditions</u></b>. Please note your email and password for future
use. Once submitted, your application will be processed and you will be contacted with your approval notice by our Customer Service Department within two business days.</p>

<p>If you've already been approved and/or contacted by one of our reps with assigned login information, please login and proceed with your purchase. Remember to include your marketing material choices in your order.</p>

<p>Thank you and we appreciate your business.</p>

<!-- GMC - 01/25/12 - Cancel 805 Insider button for Security Reasons -->
<!-- GMC - 12/24/11 - 805 Insider button in the reseller login screen -->
<!--<p><input type="image" src="http://www.revitalash.com/wp-content/uploads/Text-icon.jpg" onclick="displayWidget(1693);" title="Click here to join the RevitaLash Insiders Club" /><strong>Attention Resellers! Click here to join Revitalash <sup>&reg;</sup> Insiders Club.</strong></p>-->

<font color="red">
<strong>

<!-- GMC 08/065/09 - August 17 to October 31 Promo: Breast Cancer Awareness -->
<!-- GMC 12/25/09 - Change in text below -->
<!-- GMC 01/19/10 - Remove Breast Cancer and 2009 Holidat Gift Box -->
<!-- <div align="center"> -->
<!-- AUGUST 17 TO OCTOBER 31 PROMOTION -->
<!--
FROM AUGUST 17 ON PROMOTION
</div>
<p>
Breast Cancer Awareness Promotion includes Revitalash, Mascara, and Pink Ribbon Cosmetic bag. Minimum order of 12 or more ~ While Supplies Last.
</p>
-->

<!-- GMC 11/02/09 - 2009 Holiday Gift Box Set -->
<!--
<div align="center">
2009 HOLIDAY GIFT BOX SET
</div>
<p>
Revitalash and Eyelash Curler in a Holiday Box. Minimum order of 12 or more. Buy 12 and get 2 Revitalash for Free ~ Ends 12/31/09 or While Supplies Last.
</p>
-->

<!-- GMC 01/05/10 - Valentine's Day 2010 Promotion -->
<!--
<div align="center">
VALENTINE'S DAY 2010 PROMOTION
</div>
<p>
Valentine's Day 2010 Promotion includes Valentine's Promotion Bag, Revitalash and Candle or Compact Mirror.  Minimum order of 12 or more – While Supplies Last.
</p>
-->

<!-- GMC - 03/16/10 Mother's Day 2010 Promotiom -->
<!-- GMC - 05/17/10 - Cancel Mother's Day 2010 Promotion Text -->
<!--
<div align="center">
MOTHER'S DAY 2010 PROMOTION
</div>
<p>
Mother's Day 2010 Promotion includes Revitalash and Mascara.  Minimum order of 12 or more – While Supplies Last.
</p>
-->

<!-- GMC - 06/10/11 - NAV Item 498 - 593 and 594 - 12 + 2 Free from 062011 to 090111 -->
<!-- GMC - 08/27/11 - Deactivate NAV Item 498 - 593 and 594 - 12 + 2 Free from 062011 to 090111
<div align="center">
ADVANCED FORMULA 2011 PROMOTION
</div>
<p>
Advanced Formula Special Promotion (Purchase 12 and get 2 free) from 06/20/11 to 09/01/11
</p>
-->

<!-- GMC - 08/28/11 - NAV Item 593 and 417 Get 12 + 1 plus multiples of 13 free effective 090111 -->
<!-- GMC - 09/29/11 - NAV Item 593 and 417 Get 12 + 1 plus multiples of 13 free effective 090111 - Cancel Promo
<div align="center">
PINK REVITALASH BAG 2011 PROMOTION
</div>
<p>
Pink Revitalash Bag Special Promotion (Purchase 12 or more of Advanced Revitalash SPA 3.5 ml and get bags free UP to 39 Units) from 09/01/11
</p>
-->

</strong>
</font>

<form action="/pro/index.php" method="post">
<table width="100%" cellspacing="5" cellpadding="0">

<tr>
	<td colspan="2" style="background-color:#9799C3; color:#FFFFFF; font-weight:bold; padding:5px;">Please enter your username and password or <a href="/pro/register.php">click here to register</a></td>
</tr>

<tr>
	<th width="300">E-Mail Address: <span class="required">*</span></th>
    <td width="*"><input type="text" name="EMailAddress" size="40" value="" /></td>
</tr>

<tr>
	<th>Password: <span class="required">*</span></th>
    <td><input type="password" name="Password" size="10" value="" /> &nbsp; <a style="color:#FF0000;" href="/forgotpw.php">forgot password?</a></td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>

<tr>
	<td>&nbsp;</td>
    <td colspan="2"><input type="submit" name="cmdLogin" value="Login" class="formSubmit" /></td>
</tr>

</table>
</form>

<p>&nbsp;</p>
