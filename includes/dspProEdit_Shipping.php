<?php
echo '<p style="margin-top:10px;"><span style="font-weight:bold;">Use the following form to update this shipping address.</span> Please note that making changes to this address may result in an order delay of up to 48 hours pending review by our customer service department.</p>';
            
echo '<form action="/pro/edit.php?ShipTo=' . $_GET['ShipTo'] . '" method="post">
<table width="100%" cellpadding="0" cellspacing="5">';

while($rowGetShipTo = mssql_fetch_array($rsGetShipTo))
{
    echo '<tr>';
    echo '<th width="140">Address Type:</th>';
    echo '<td width="*">';
	if ($rowGetShipTo["AddressType"] == 'Business')
		echo '<input type="radio" name="AddressType" value="Business" checked="checked"> Business &nbsp; &nbsp; <input type="radio" name="AddressType" value="Residential"> Residential';
	else
		echo '<input type="radio" name="AddressType" value="Business"> Business &nbsp; &nbsp; <input type="radio" name="AddressType" value="Residential" checked="checked"> Residential';
	
	echo '</td>';
    echo '</tr>';
	
    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
	echo '<tr>
        <th width="140">Company:</th>
        <td width="*"><input type="text" name="Company" size="20" value="' . $rowGetShipTo["CompanyName"] . '" /></td>
    </tr>

    <tr>
        <th>Attn:</th>
        <td width="*"><input type="text" name="Attn" size="20" value="' . $rowGetShipTo["Attn"] . '" /></td>
    </tr>

    <tr>
        <th>Address 1:</th>
        <td><input type="text" name="Address1" size="40" value="' . $rowGetShipTo["Address1"] . '" /></td>
    </tr>
    
    <tr>
        <th>Address 2:</th>
        <td><input type="text" name="Address2" size="40" value="' . $rowGetShipTo["Address2"] . '" /></td>
    </tr>
    
    <tr>
        <th>City:</th>
        <td><input type="text" name="City" size="20" value="' . $rowGetShipTo["City"] . '" /></td>
    </tr>
    
    <tr>
        <th>State/Province:</th>
        <td><input type="text" name="State" size="5" value="' . $rowGetShipTo["State"] . '" /></td>
    </tr>
    
    <tr>	
        <th>Postal Code:</th>
        <td><input type="text" name="PostalCode" size="10" value="' . $rowGetShipTo["PostalCode"] . '" /></td>
    </tr>
	
    
    <tr>
        <th>Country Code:</th>
        <td><select name="CountryCode">' . $selectCountries . '</select></td>
    </tr>
    
    <tr><td colspan="2">&nbsp;</td></tr>
    
    <tr>
        <th>&nbsp;</th>
        <td><input type="submit" name="cmdUpdate" value="Update Shipping" class="formSubmit" /></td>
    </tr>
    
    </table>
    </form>
    <p>&nbsp;</p>';
}
?>
