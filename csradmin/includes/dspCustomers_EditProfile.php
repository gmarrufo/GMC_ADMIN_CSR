<h1>Edit Customer's Shipping Information</h1>

<?php if (isset($confirmation)) echo '<p class="confirmation">' . $confirmation . '</p>'; ?>

<p style="margin-top:10px; font-weight:bold;">Use the following form to edit your customer's shipping information.
<a href="/csradmin/customers.php">Click here to return to your customer list</a></p>
<table width="900" cellpadding="2" cellspacing="0" style="margin:10px;">
<tr>
<td>
<table>
<form action="/csradmin/customers.php?Action=EditProfile&CustomerID=<?php echo $_GET['CustomerID']; ?>" method="post">
<input type="hidden" name="Password" value=""><input type="hidden" name="PasswordConfirm" value="">
</table>
</td>
<td>
<table>

<?php

if (mssql_num_rows($qryGetShippingAddressesToModify) > 0)
{
    while($row = mssql_fetch_array($qryGetShippingAddressesToModify))
    {
    echo '<tr>
        <th width="140">Company:</th>
        <td width="*">
        <input type="text" name="ShipCompany" size="60" value="' . $row["CompanyName"] . '" />
        <input type="hidden" name="ShipToId" value"' . $row["RecordID"] . '"/>
        </td>
    </tr>
	<tr>
        <th>Attn:</th>
        <td><input type="text" name="ShipAttn" size="60" value="' . $row["Attn"] . '" /></td>
    </tr>
	<tr>
        <th>Address1:</th>
        <td><input type="text" name="ShipAddress1" size="60" value="' . $row["Address1"] . '" /></td>
    </tr>
	<tr>
        <th>Address2:</th>
        <td><input type="text" name="ShipAddress2" size="60" value="' . $row["Address2"] . '" /></td>
    </tr>
    <tr>
        <th>Country:</th>
        <td><input type="text" name="ShipCountryCode" size="40" value="' . $row["CountryCode"] . '" /></td>
    </tr>
    <tr>
        <th>City/Province:</th>
        <td><input type="text" name="ShipCity" size="40" value="' . $row["City"] . '" /></td>
    </tr>
    <tr>
        <th>State/Region:</th>
        <td><input type="text" name="ShipState" size="5" value="' . $row["State"] . '" /></td>
    </tr>
    <tr>
        <th>Postal Code:</th>
        <td><input type="text" name="ShipPostalCode" size="20" value="' . $row["PostalCode"] . '" /></td>
    </tr>';
    }
}

?>

<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<th>&nbsp;</th>
<td>
<input type="submit" name="cmdModifyShippingAddress" value="Modify Address" class="formSubmit" />
&nbsp;&nbsp;
</td>
</tr>
</form>
</table>
</td>
</tr>
</table>
<p>&nbsp;</p>
