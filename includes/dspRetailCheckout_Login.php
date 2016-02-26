<!-- GMC - 06/22/09 - Make label for Reseller Bigger -->
<p style="text-align:right; font-weight:bold;"><h1>If you are a reseller or spa, please <a href="/pro/checkout.php">click here</a>.</h1></p>

<p style="font-weight:bold;">If you already have a Revitalash account, please login to continue your order.</p>

<!-- LOGIN TABLE -->
<form action="/retail/checkout.php" method="post" name="consumerform" id="consumerform" onSubmit="return validate_login(this);">
<table width="300" cellpadding="0" cellspacing="5" align="center">

<tr>
    <th width="75">E-Mail: <span class="required">*</span></th>
    <td width="*"><input type="text" name="EMailAddress" size="25" /></td>
</tr>

<tr>
    <th>Password: <span class="required">*</span></th>
    <td><input type="password" name="Password" size="10" />  &nbsp; <a style="color:#FF0000;" href="/forgotpw.php">forgot password?</a></td>
</tr>

<tr>
    <th>&nbsp;</th>
    <td><input type="submit" name="cmdLogin" value="Login" class="formSubmit" /></td>
</tr>

</table>
</form>

<hr width="722" size="4" color="#000000" noshade="noshade" />

<!-- GMC - 10/06/09 - To adjust the Billing/Shipping Entry for Ireland/Outside US -->
<!--<p style="font-weight:bold;">If you have not placed an order with Revitalash, please fill in the following information. <span style="color:#FF0000;">NOTE: We do not deliver to PO Boxes.</span> International users <a href="/retail/checkout.php?IsInternational">click here</a>.</p>-->
<p style="font-weight:bold;">If you have not placed an order with Revitalash, please fill in the following information. <span style="color:#FF0000;">NOTE: We do not deliver to PO Boxes.</span></p>

<form action="/retail/checkout.php" method="post" onSubmit="return validate_new(this);">
<table width="100%" cellpadding="0" cellspacing="0" border="0">

<tr>
	<td width="55%" style="font-weight:bold;">&nbsp; BILLING INFORMATION</th>
    <td width="45%" style="font-weight:bold;">&nbsp; SHIPPING INFORMATION</th>
</tr>

<tr>
<td>
    <table width="100%" cellpadding="0" cellspacing="5">
    
    <tr>
        <th width="120">First Name: <span class="required">*</span></th>
        <td width="*"><input type="text" name="FirstName" size="20" value="" /></td>
    </tr>
    
    <tr>
        <th>Last Name: <span class="required">*</span></th>
        <td><input type="text" name="LastName" size="20" value="" /></td>
    </tr>
    
    <tr>
        <th>Address 1: <span class="required">*</span></th>
        <td><input type="text" name="Address1" size="40" value="" /></td>
    </tr>
    
    <tr>
        <th>Address 2:</th>
        <td><input type="text" name="Address2" size="40" value="" /></td>
    </tr>

    <!-- GMC - 10/06/09 - To adjust the Billing/Shipping Entry for Ireland -->
    <?php

    if($_SESSION['Product_Shipping_Country_Retail'] == "IE")
    {
        echo '<tr>';
        echo '<th>County, City: <span class="required">*</span></th>';
        echo '<td><input type="text" name="City" size="20" value="" /></td>';
        echo '</tr>';
    }
    else
    {
        echo '<tr>';
        echo '<th>City: <span class="required">*</span></th>';
        echo '<td><input type="text" name="City" size="20" value="" /></td>';
        echo '</tr>';
    }

    ?>

    <?php
	
	if (isset($_GET['IsInternational']) || $_SESSION['Product_Shipping_Country_Retail']  != "US")
	{
        if($_SESSION['Product_Shipping_Country_Retail'] == "IE")
        {
            echo '<tr>
            <th>State/Province:</th>
            <td><input type="text" name="State" size="20" value="" /></td>
    	    </tr>';
        }
        else
        {
            echo '<tr>
            <th>State/Province: <span class="required">*</span></th>
            <td><input type="text" name="State" size="20" value="" /></td>
    	    </tr>';
        }
	}
	else
	{
		echo '<tr>
        <th>State/Province:</th>
        <td><select name="State" size="1">
            <option value="0"> -- SELECT -- </option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District Of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="PR">Puerto Rico</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="DC">Washington Dc</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
          </select></td>
    	</tr>';
	}
	
	?>

    <!-- GMC - 10/06/09 - To adjust the Billing/Shipping Entry for Ireland -->
    <?php

    if($_SESSION['Product_Shipping_Country_Retail'] == "IE")
    {
        echo '<tr>';
        echo '<th>Postal Code:<br/><span class="required">Only required if Postal Code Exists</span></th>';
        echo '<td><input type="text" name="PostalCode" size="10" value="" /></td>';
        echo '</tr>';
    }
    else
    {
        echo '<tr>';
        echo '<th>Postal Code: <span class="required">*</span></th>';
        echo '<td><input type="text" name="PostalCode" size="10" value="" /></td>';
        echo '</tr>';
    }
    
    ?>

    <tr>
        <th>Country Code: <span class="required">*</span></th>
        <td><select name="CountryCode" size="1">
        <?php echo $selectCountries; ?>
        </select></td>
    </tr>
    
    <tr>
        <th>Telephone: <span class="required">*</span></th>

        <!-- GMC - 10/28/09 - To eliminate the Extension Field by JS -->
        <!--<td><input type="text" name="Telephone" size="15" value="" /> x <input type="text" name="TelephoneExtension" size="8" value="" /></td>-->
        <td><input type="text" name="Telephone" size="15" value="" /></td>

    </tr>
    
    <tr>
        <th>Email: <span class="required">*</span></th>
        <td><input type="text" name="EMailAddress" size="40" value="" /></td>
    </tr>
    
    <tr>
        <th>Security Question: <span class="required">*</span></th>
        <td><select name="SecurityQuestionID" size="1">
        <?php echo $selectSecurityQuestions; ?>
        </select></td>
    </tr>
    
    <tr>
        <th>Security Answer: <span class="required">*</span></th>
        <td><input type="text" name="SecurityAnswer" size="25" value="" /></td>
    </tr>
    
    <tr>
        <th>Password: <span class="required">*</span></th>
        <td><input type="password" name="Password" size="10" value="" /></td>
    </tr>
    
    <tr>
        <th>Verify Password: <span class="required">*</span></th>
        <td><input type="password" name="PasswordConfirm" size="10" value="" /></td>
    </tr>
    
    </table>
</td>
<td>
	<table width="100%" cellpadding="0" cellspacing="5">
    
    <tr>
        <th width="100">&nbsp;</th>
        <td width="*" style="font-size:10px; font-weight:bold;"><input name="AddressAction" type="radio" value="SameAsBilling" checked="checked" /> Same As Billing <input name="AddressAction" type="radio" value="CreateNew" /> New Address</td>
    </tr>
    
    <tr>
        <th>AddressType:</th>
        <td><select name="ShipToAddressType" size="1">
        <option value="Residential" selected="selected">Residential</option>
        <option value="Business">Business</option>
        </select></td>
    </tr>
    
    <tr>
        <th>Attn:</th>
        <td><input type="text" name="ShipToAttn" size="20" value="" /></td>
    </tr>
    
    <tr>
        <th>Address 1:</th>
        <td><input type="text" name="ShipToAddress1" size="30" value="" /></td>
    </tr>
    
    <tr>
        <th>Address 2:</th>
        <td><input type="text" name="ShipToAddress2" size="30" value="" /></td>
    </tr>
    
    <tr>
        <th>City:</th>
        <td><input type="text" name="ShipToCity" size="20" value="" /></td>
    </tr>
    
    <?php
	
	if (isset($_GET['IsInternational']) || $_SESSION['Product_Shipping_Country_Retail']  != "US")
	{
    	echo '<tr>
        <th>State/Province:</th>
        <td><input type="text" name="ShipToState" size="20" value="" /></td>
    	</tr>';	
	}
	else
	{
		echo '<tr>
        <th>State/Province:</th>
        <td><select name="ShipToState" size="1">
            <option value="0"> -- SELECT -- </option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District Of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="PR">Puerto Rico</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="DC">Washington Dc</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
          </select></td>
    	</tr>';
	}
	
	?>
    
    <tr>	
        <th>Postal Code:</th>
        <td><input type="text" name="ShipToPostalCode" size="10" value="" /></td>
    </tr>
    
    <tr>
        <th>Country Code:</th>
        <td><select name="ShipToCountryCode" size="1">
        <?php echo $selectCountries; ?>
        </select></td>
    </tr>

    </table>
    
</td>
</tr>

<tr>
	<td colspan="2" align="center"><input type="submit" name="cmdNewAccount" value="Create Account" class="formSubmit" /></td>
</tr>

</table>
</form>
