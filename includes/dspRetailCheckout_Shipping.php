<p style="font-weight:bold;">Please use this form to update your information as well as selecting a shipping address.</p>

<form action="/retail/checkout.php" method="post" onSubmit="return validate_ship(this);">
<table width="100%" cellpadding="0" cellspacing="0" border="0">

<?php

while($row = mssql_fetch_array($rsGetCustomer))
{
    echo '<tr>
	<td width="55%" style="font-weight:bold;">&nbsp; BILLING INFORMATION</th>
    <td width="45%" style="font-weight:bold;">&nbsp; SHIPPING INFORMATION</th>
</tr>

<tr>
<td>
    <table width="100%" cellpadding="0" cellspacing="5">
    
    <tr>
        <th width="120">First Name:</th>
        <td width="*"><input type="text" name="FirstName" size="20" value="' . $row["FirstName"] . '" /></td>
    </tr>
    
    <tr>
        <th>Last Name:</th>
        <td><input type="text" name="LastName" size="20" value="' . $row["LastName"] . '" /></td>
    </tr>
    
    <tr>
        <th>Address 1:</th>
        <td><input type="text" name="Address1" size="40" value="' . $row["Address1"] . '" /></td>
    </tr>
    
    <tr>
        <th>Address 2:</th>
        <td><input type="text" name="Address2" size="40" value="' . $row["Address2"] . '" /></td>
    </tr>

    <!-- GMC - 04/22/10 - US and CA Drop Downs for State and Province -->
    <tr>
        <th>Country Code:</th>
        <td><select name="CountryCode" size="1" onChange="getStateProvince(this.value)">' . $selectCountries . '</select></td>
    </tr>';

    // GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller
    if($_SESSION['StateExclusionProductSelected'] == "Yes")
    {
        echo '<tr><th>State/Province:</th><td><div id="state_province"><input type="text" name="State" size="5" value="" /></div></td></tr>';
    }
    else
    {
        echo '<tr><th>State/Province:</th><td><div id="state_province"><input type="text" name="State" size="5" value="' . $row["State"] . '" /></div></td></tr>';
    }

    echo'<!-- GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller -->
    <script>
    function getStateProvince(sType)
    {
        var sCountry = sType;

        if(sCountry == "US")
        {
            document.getElementById("state_province").innerHTML = "";
            var sOutput = "<select name=State size=1>"
            + "<option value=0> -- SELECT -- </option>"
            + "<option value=AL>Alabama</option>"
            + "<option value=AK>Alaska</option>"
            + "<option value=AZ>Arizona</option>"
            + "<option value=AR>Arkansas</option>"';

            // GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller
            if($_SESSION['StateExclusionProductSelected'] == "Yes")
            {
                echo '<!-- + "<option value=CA>California</option>" -->';
            }
            else
            {
                echo '+ "<option value=CA>California</option>"';
            }

            echo
            '+ "<option value=CO>Colorado</option>"
            + "<option value=CT>Connecticut</option>"
            + "<option value=DE>Delaware</option>"
            + "<option value=DC>District Of Columbia</option>"
            + "<option value=FL>Florida</option>"
            + "<option value=GA>Georgia</option>"
            + "<option value=HI>Hawaii</option>"
            + "<option value=ID>Idaho</option>"
            + "<option value=IL>Illinois</option>"
            + "<option value=IN>Indiana</option>"
            + "<option value=IA>Iowa</option>"
            + "<option value=KS>Kansas</option>"
            + "<option value=KY>Kentucky</option>"
            + "<option value=LA>Louisiana</option>"
            + "<option value=ME>Maine</option>"
            + "<option value=MD>Maryland</option>"
            + "<option value=MA>Massachusetts</option>"
            + "<option value=MI>Michigan</option>"
            + "<option value=MN>Minnesota</option>"
            + "<option value=MS>Mississippi</option>"
            + "<option value=MO>Missouri</option>"
            + "<option value=MT>Montana</option>"
            + "<option value=NE>Nebraska</option>"
            + "<option value=NV>Nevada</option>"
            + "<option value=NH>New Hampshire</option>"
            + "<option value=NJ>New Jersey</option>"
            + "<option value=NM>New Mexico</option>"
            + "<option value=NY>New York</option>"
            + "<option value=NC>North Carolina</option>"
            + "<option value=ND>North Dakota</option>"
            + "<option value=OH>Ohio</option>"
            + "<option value=OK>Oklahoma</option>"
            + "<option value=OR>Oregon</option>"
            + "<option value=PA>Pennsylvania</option>"
            + "<option value=PR>Puerto Rico</option>"
            + "<option value=RI>Rhode Island</option>"
            + "<option value=SC>South Carolina</option>"
            + "<option value=SD>South Dakota</option>"';

            // GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller
            if($_SESSION['StateExclusionProductSelected'] == "Yes")
            {
                echo '<!-- + "<option value=TN>Tennessee</option>" -->';
            }
            else
            {
                echo '+ "<option value=TN>Tennessee</option>"';
            }

            echo '+ "<option value=TX>Texas</option>"
            + "<option value=UT>Utah</option>"
            + "<option value=VT>Vermont</option>"
            + "<option value=VA>Virginia</option>"
            + "<option value=WA>Washington</option>"
            + "<option value=DC>Washington Dc</option>"
            + "<option value=WV>West Virginia</option>"
            + "<option value=WI>Wisconsin</option>"
            + "<option value=WY>Wyoming</option>"
            + "</select>";
            document.getElementById("state_province").innerHTML = sOutput;
        }
        else if(sCountry == "CA")
        {
            document.getElementById("state_province").innerHTML = "";
            var sOutput = "<select name=State size=1>"
            + "<option value=0> -- SELECT -- </option>"
            + "<option value=ON>Ontario</option>"
            + "<option value=QC>Quebec</option>"
            + "<option value=BC>British Columbia</option>"
            + "<option value=AB>Alberta</option>"
            + "<option value=MB>Manitoba</option>"
            + "<option value=SK>Saskatchewan</option>"
            + "<option value=NS>Nova Scotia</option>"
            + "<option value=NB>New Brunswick</option>"
            + "<option value=NL>Newfoundland and Labrador</option>"
            + "<option value=PE>Prince Edward Island</option>"
            + "<option value=NT>Northwest Territories</option>"
            + "<option value=YT>Yukon</option>"
            + "<option value=NU>Nunavut</option>"
            + "</select>";
            document.getElementById("state_province").innerHTML = sOutput;
        }
        else
        {
            document.getElementById("state_province").innerHTML = "";
            var sOutput = "<input type=text name=State size=5 />";
            document.getElementById("state_province").innerHTML = sOutput;
        }
    }
    </script>

    <tr>
        <th>City:</th>
        <td><input type="text" name="City" size="20" value="' . $row["City"] . '" /></td>
    </tr>';

    // GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller
    if($_SESSION['StateExclusionProductSelected'] == "Yes")
    {
        echo '<tr><th>Postal Code:</th><td><input type="text" name="PostalCode" size="10" value="" /></td></tr>';
    }
    else
    {
        echo '<tr><th>Postal Code:</th><td><input type="text" name="PostalCode" size="10" value="' . $row["PostalCode"] . '" /></td></tr>';
    }

    echo '<tr>
        <th>Telephone:</th>
        <td><input type="text" name="Telephone" size="15" value="' . $row["Telephone"] . '" /> x <input type="text" name="TelephoneExtension" size="8" value="' . $row["TelephoneExtension"] . '" /></td>
    </tr>
    
    <tr>
        <th>Email:</th>
        <td><input type="text" name="EMailAddress" size="40" value="' . $row["EMailAddress"] . '" /></td>
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
        <th>Address Type:</th>
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

    <!-- GMC - 04/22/10 - US and CA Drop Downs for State and Province -->
    <tr>
        <th>Country Code:</th>
        <td><select name="ShipToCountryCode" size="1" onChange="getStateProvinceShip(this.value)">' . $selectCountries . '</select></td>
    </tr>

    <tr>
        <th>State/Province:</th>
        <td><div id="state_province_ship"><input type="text" name="ShipToState" size="5" value="" /></div></td>
    </tr>

    <!-- GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller -->
    <script>
    function getStateProvinceShip(sType)
    {
        var sCountry = sType;

        if(sCountry == "US")
        {
            document.getElementById("state_province_ship").innerHTML = "";
            var sOutput = "<select name=ShipToState size=1>"
            + "<option value=0> -- SELECT -- </option>"
            + "<option value=AL>Alabama</option>"
            + "<option value=AK>Alaska</option>"
            + "<option value=AZ>Arizona</option>"
            + "<option value=AR>Arkansas</option>"';

            // GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller
            if($_SESSION['StateExclusionProductSelected'] == "Yes")
            {
                echo '<!-- + "<option value=CA>California</option>" -->';
            }
            else
            {
                echo '+ "<option value=CA>California</option>"';
            }

            echo
            '+ "<option value=CO>Colorado</option>"
            + "<option value=CT>Connecticut</option>"
            + "<option value=DE>Delaware</option>"
            + "<option value=DC>District Of Columbia</option>"
            + "<option value=FL>Florida</option>"
            + "<option value=GA>Georgia</option>"
            + "<option value=HI>Hawaii</option>"
            + "<option value=ID>Idaho</option>"
            + "<option value=IL>Illinois</option>"
            + "<option value=IN>Indiana</option>"
            + "<option value=IA>Iowa</option>"
            + "<option value=KS>Kansas</option>"
            + "<option value=KY>Kentucky</option>"
            + "<option value=LA>Louisiana</option>"
            + "<option value=ME>Maine</option>"
            + "<option value=MD>Maryland</option>"
            + "<option value=MA>Massachusetts</option>"
            + "<option value=MI>Michigan</option>"
            + "<option value=MN>Minnesota</option>"
            + "<option value=MS>Mississippi</option>"
            + "<option value=MO>Missouri</option>"
            + "<option value=MT>Montana</option>"
            + "<option value=NE>Nebraska</option>"
            + "<option value=NV>Nevada</option>"
            + "<option value=NH>New Hampshire</option>"
            + "<option value=NJ>New Jersey</option>"
            + "<option value=NM>New Mexico</option>"
            + "<option value=NY>New York</option>"
            + "<option value=NC>North Carolina</option>"
            + "<option value=ND>North Dakota</option>"
            + "<option value=OH>Ohio</option>"
            + "<option value=OK>Oklahoma</option>"
            + "<option value=OR>Oregon</option>"
            + "<option value=PA>Pennsylvania</option>"
            + "<option value=PR>Puerto Rico</option>"
            + "<option value=RI>Rhode Island</option>"
            + "<option value=SC>South Carolina</option>"
            + "<option value=SD>South Dakota</option>"';

            // GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller
            if($_SESSION['StateExclusionProductSelected'] == "Yes")
            {
                echo '<!-- + "<option value=TN>Tennessee</option>" -->';
            }
            else
            {
                echo '+ "<option value=TN>Tennessee</option>"';
            }

            echo
            '+ "<option value=TX>Texas</option>"
            + "<option value=UT>Utah</option>"
            + "<option value=VT>Vermont</option>"
            + "<option value=VA>Virginia</option>"
            + "<option value=WA>Washington</option>"
            + "<option value=DC>Washington Dc</option>"
            + "<option value=WV>West Virginia</option>"
            + "<option value=WI>Wisconsin</option>"
            + "<option value=WY>Wyoming</option>"
            + "</select>";
            document.getElementById("state_province_ship").innerHTML = sOutput;
        }
        else if(sCountry == "CA")
        {
            document.getElementById("state_province_ship").innerHTML = "";
            var sOutput = "<select name=ShipToState size=1>"
            + "<option value=0> -- SELECT -- </option>"
            + "<option value=ON>Ontario</option>"
            + "<option value=QC>Quebec</option>"
            + "<option value=BC>British Columbia</option>"
            + "<option value=AB>Alberta</option>"
            + "<option value=MB>Manitoba</option>"
            + "<option value=SK>Saskatchewan</option>"
            + "<option value=NS>Nova Scotia</option>"
            + "<option value=NB>New Brunswick</option>"
            + "<option value=NL>Newfoundland and Labrador</option>"
            + "<option value=PE>Prince Edward Island</option>"
            + "<option value=NT>Northwest Territories</option>"
            + "<option value=YT>Yukon</option>"
            + "<option value=NU>Nunavut</option>"
            + "</select>";
            document.getElementById("state_province_ship").innerHTML = sOutput;
        }
        else
        {
            document.getElementById("state_province_ship").innerHTML = "";
            var sOutput = "<input type=text name=ShipToState size=5 />";
            document.getElementById("state_province_ship").innerHTML = sOutput;
        }
    }
    </script>

    <tr>
        <th>City:</th>
        <td><input type="text" name="ShipToCity" size="20" value="" /></td>
    </tr>

    <tr>	
        <th>Postal Code:</th>
        <td><input type="text" name="ShipToPostalCode" size="10" value="" /></td>
    </tr>
    
    </table>
</td>
</tr>

<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>

<!-- GMC - 02/18/09 - We Ship The World Text-->
<tr>
	<td colspan="2" align="center"><font face="arial" color="red" size="5"><b>We Ship the World!</b></font></td>
</tr>

<tr>
	<td colspan="2" align="center">&nbsp;</td>
</tr>

<tr>
	<td colspan="2" align="center"><input type="submit" name="cmdSetShipping" value="Continue" class="formSubmit" /></td>
</tr>';
}
?>
</table>
</form>
