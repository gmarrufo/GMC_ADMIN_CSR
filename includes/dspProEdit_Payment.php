<?php
echo '<p style="margin-top:10px;"><span style="font-weight:bold;">Use the following form to update this stored payment method.</span></p>';
            
echo '<form action="/pro/edit.php?Payment=' . $_GET['Payment'] . '" method="post">
<table width="100%" cellpadding="0" cellspacing="5">';

while($rowGetPayment = mssql_fetch_array($rsGetPayment))
{
    
	if ($rowGetPayment['PaymentType'] == 'Credit Card')
	{
		echo '<tr>
			<th width="140">Credit Card Number:</th>
			<td width="*">Ends in ' . substr($rowGetPayment["CCNumber"], -4, 4) . '</td>
		</tr>
		
		<tr>
			<th>Cardholder Name:</th>
			<td><input type="text" name="Cardholder" size="25" value="' . $rowGetPayment['Cardholder'] . '" /></td>
		</tr>
		
		<tr>
			<th>Expiration:</th>
			<td><select name="ExpMonth" size="1">';
			echo '<option value="01"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '01') echo ' selected="selected"'; echo '>01 - JAN</option>';
			echo '<option value="02"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '02') echo ' selected="selected"'; echo '>02 - FEB</option>';
			echo '<option value="03"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '03') echo ' selected="selected"'; echo '>03 - MAR</option>';
			echo '<option value="04"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '04') echo ' selected="selected"'; echo '>04 - APR</option>';
			echo '<option value="05"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '05') echo ' selected="selected"'; echo '>05 - MAY</option>';
			echo '<option value="06"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '06') echo ' selected="selected"'; echo '>06 - JUN</option>';
			echo '<option value="07"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '07') echo ' selected="selected"'; echo '>07 - JUL</option>';
			echo '<option value="08"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '08') echo ' selected="selected"'; echo '>08 - AUG</option>';
			echo '<option value="09"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '09') echo ' selected="selected"'; echo '>09 - SEP</option>';
			echo '<option value="10"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '10') echo ' selected="selected"'; echo '>10 - OCT</option>';
			echo '<option value="11"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '11') echo ' selected="selected"'; echo '>11 - NOV</option>';
			echo '<option value="12"'; if (substr($rowGetPayment['CCExpiration'], 0, 2) == '12') echo ' selected="selected"'; echo '>12 - DEC</option>';
			echo '</select> <select name="ExpYear" size="1">';
			echo '<option value="08"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '08') echo ' selected="selected"'; echo '>2008</option>';
			echo '<option value="09"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '09') echo ' selected="selected"'; echo '>2009</option>';
			echo '<option value="10"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '10') echo ' selected="selected"'; echo '>2010</option>';
			echo '<option value="11"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '11') echo ' selected="selected"'; echo '>2011</option>';
			echo '<option value="12"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '12') echo ' selected="selected"'; echo '>2012</option>';
			echo '<option value="13"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '13') echo ' selected="selected"'; echo '>2013</option>';
			echo '<option value="14"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '14') echo ' selected="selected"'; echo '>2014</option>';
			echo '<option value="15"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '15') echo ' selected="selected"'; echo '>2015</option>';
			echo '<option value="16"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '16') echo ' selected="selected"'; echo '>2016</option>';
			echo '<option value="17"'; if (substr($rowGetPayment['CCExpiration'], -2, 2) == '17') echo ' selected="selected"'; echo '>2017</option>';
		echo '</select></td>
		</tr>
		
		<tr>
			<th>Billing Postal Code:</th>
			<td><input type="text" name="BillingPostalCode" size="10" value="' . $rowGetPayment['BillingPostalCode'] . '" /></td>
		</tr>
		
		<tr><td colspan="2">&nbsp;</td></tr>
		
		<tr>
			<th>&nbsp;</th>
			<td><input type="submit" name="cmdUpdate" value="Update Card Info" class="formSubmit" /></td>
		</tr>
		
		</table>
		</form>
		<p>&nbsp;</p>';
	}
	elseif ($rowGetPayment['PaymentType'] == 'Electronic Check')
	{
	
	}
	elseif ($rowGetPayment['PaymentType'] == 'Terms')
	{
	
	}
	
}
?>