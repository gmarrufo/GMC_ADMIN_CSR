<p>Please enter your shipping information below. If you would like to add an additional shipping address or change an existing one, please <a href="mailto:customerservice@revitalash.com">click here to contact us</a>.</p>

<form action="/pro/checkout.php" method="post">
<table width="100%" cellpadding="0" cellspacing="5">

<tr>
	<td width="30"><input type="radio" name="ShippingAddress" value="UseExisting" checked="checked" />
    <td width="100">Existing Address:</th>
    <td width="*"><select name="ShipToID" size="1">
    <?php echo $strCBO; ?>
    </select></td>
</tr>

<tr>
    <td colspan="3">&nbsp;</td>
</tr>

<tr>
    <td colspan="2"><input type="submit" name="cmdSetShipping" value="Continue" class="formSubmit" /></td>
</tr>

</table>
</form>