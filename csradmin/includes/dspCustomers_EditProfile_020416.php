<h1>Edit Customer's Shipping Information</h1>

<?php if (isset($confirmation)) echo '<p class="confirmation">' . $confirmation . '</p>'; ?>

<!-- GMC - 05/27/10 - Edit Shipping Information in CSRADMIN
<p style="margin-top:10px; font-weight:bold;">Use the following form to edit your customer's information.
<a href="/csradmin/customers.php">Click here to return to your customer list</a> &nbsp; OR &nbsp;
<a href="/csradmin/delete_customer.php?id=<?php// echo $_GET['CustomerID']; ?>">Click here to delete this customer</a>.</p>
-->

<!-- GMC - 06/29/10 - Put back Delete Customer Option by JS -->
<!-- GMC - 03/18/12 - Hong Kong Change for City and Zip Code -->
<!-- GMC - 06/27/12 - Put back Delete Customer Option but only for JS -->
<!--
<p style="margin-top:10px; font-weight:bold;"><a href="/csradmin/delete_customer.php?id=<?// php echo $_GET['CustomerID']; ?> ">Click here to delete this customer</a>.</p>
-->

<?php
if ($_SESSION['UserID'] == 35)
{
?>
 <p style="margin-top:10px; font-weight:bold;"><a href="/csradmin/delete_customer.php?id=<?php echo $_GET['CustomerID']; ?> ">Click here to delete this customer</a>.</p>
<?php
}
?>

<p style="margin-top:10px; font-weight:bold;">Use the following form to edit your customer's shipping information.
<a href="/csradmin/customers.php">Click here to return to your customer list</a></p>

<table width="900" cellpadding="2" cellspacing="0" style="margin:10px;">
<tr>
<td>
<table>
<form action="/csradmin/customers.php?Action=EditProfile&CustomerID=<?php echo $_GET['CustomerID']; ?>" method="post">
<input type="hidden" name="Password" value=""><input type="hidden" name="PasswordConfirm" value="">

<!-- GMC - 11/12/09 - Need to Delete Extra Shipping Addresses -->

<?php

while($row = mssql_fetch_array($qryGetCustomer))
{
    echo '<tr>
        <th width="140">Customer Number:</th>
        <td width="*"><input type="text" readonly="readonly" name="CustomerNumber" size="40" value="' . $row["NavisionCustomerID"] . '" /></td>
    </tr>

    <tr>
        <th width="140">Company Name:</th>
        <td width="*"><input type="text" readonly="readonly" name="CompanyName" size="40" value="' . $row["CompanyName"] . '" /></td>
    </tr>
	
	<tr>
        <th>First Name:</th>
        <td><input type="text" readonly="readonly" name="FirstName" size="20" value="' . $row["FirstName"] . '" /></td>
    </tr>
	
	<tr>
        <th>Last Name:</th>
        <td><input type="text" readonly="readonly" name="LastName" size="20" value="' . $row["LastName"] . '" /></td>
    </tr>
    
    <tr>
        <th>Address 1:</th>
        <td><input type="text" readonly="readonly" name="Address1" size="40" value="' . $row["Address1"] . '" /></td>
    </tr>
    
    <tr>
        <th>Address 2:</th>
        <td><input type="text" readonly="readonly" name="Address2" size="40" value="' . $row["Address2"] . '" /></td>
    </tr>
    
    <tr>
        <th>City/Province:</th>
        <td><input type="text" readonly="readonly" name="City" size="20" value="' . $row["City"] . '" /></td>
    </tr>
    
    <tr>
        <th>State/Region:</th>
        <td><input type="text" readonly="readonly" name="State" size="5" value="' . $row["State"] . '" /></td>
    </tr>
    
    <tr>	
        <th>Postal Code:</th>
        <td><input type="text" readonly="readonly" name="PostalCode" size="10" value="' . $row["PostalCode"] . '" /></td>
    </tr>
    
    <tr>
        <th>Country Code:</th>
        <td><input type="text" readonly="readonly" name="CountryCode" size="2" value="' . $row["CountryCode"] . '" /></td>
    </tr>

    <tr>
        <th>Telephone:</th>
        <td><input type="text" readonly="readonly" name="Telephone" size="15" value="' . $row["Telephone"] . '" /> x <input type="text" readonly="readonly" name="TelephoneExtension" size="8" value="' . $row["TelephoneExtension"] . '" /></td>
    </tr>
    
    <tr>
        <th>Email:</th>
        <td><input type="text" readonly="readonly" name="EMailAddress" size="40" value="' . $row["EMailAddress"] . '" /></td>
    </tr>
	
	
	<!-- GMC - 08/19/09 - Add Sales Rep Id for Editing -->
    <!-- GMC - 12/15/10 - Add Nav Rep Id and ability to delete shipping address -->
	
    <tr>
        <th>Sales Rep Rec ID:</th>
        <td><input type="text" readonly="readonly" name="SalesRepID" size="2" value="' . $row["SalesRepID"] . '" /></td>
    </tr>';

    // GMC - 12/15/10 - Add Nav Rep Id and ability to delete shipping address
   $qryGetNavId = mssql_query("SELECT NavisionUserID FROM tblRevitalash_Users WHERE RecordID = " . $row["SalesRepID"]);

   while($row = mssql_fetch_array($qryGetNavId))
   {
      echo '<tr>
        <th>NAV Rep ID:</th>
        <td><input type="text" readonly="readonly" name="NavRepID" size="40" value="' . $row["NavisionUserID"] . '" /></td>
        </tr>';
   }

	echo '<tr>
        <th>Security Question:</th>
        <td><select name="SecurityQuestionID" size="1">' . $selectSecurityQuestions . '</select></td>
    </tr>
    
    <tr>
        <th>Security Answer:</th>
        <td><input type="text" readonly="readonly" name="SecurityAnswer" size="25" value="' . $row["SecurityAnswer"] . '" /></td>
    </tr>
    
    <tr><td colspan="2">&nbsp;</td></tr>

    <!-- GMC - 05/27/10 - Edit Shipping Information in CSRADMIN
    <tr>
        <th>&nbsp;</th>
        <td><input type="submit" name="cmdUpdate" value="Update Profile" class="formSubmit" /></td>
    </tr>
    -->
    
    ';
}
?>

<!-- GMC - 05/27/10 - Edit Shipping Information in CSRADMIN
</form>
-->

</table>
</td>
<td>
<table>

<!-- GMC - 05/27/10 - Edit Shipping Information in CSRADMIN
<form action="/csradmin/deleteShippingAddress.php" method="post">
<tr><th width="140"></th><td width="*"><font color="red"><b>Select the Shipping Address To Delete</b></font></td></tr>
-->
<!-- GMC - 12/15/10 - Add Nav Rep Id and ability to delete shipping address -->
<tr><th width="140"></th><td width="*"><font color="red"><b>Select the Shipping Address To Modify Or Delete<br/>Address will be deleted only if NO ORDER is assigned to it.</b></font></td></tr>

<?php

if (mssql_num_rows($qryGetShippingAddresses) > 0)
{
    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
    while($row = mssql_fetch_array($qryGetShippingAddresses))
    {
	  echo '<tr>
             <th><input type="radio" name="shipSelect" onclick="form.submit()" value="' . $row["RecordID"] . '" /></th>
             <td>' . $row["CompanyName"] . ' ' . $row["Attn"] . ' ' . $row["Address1"] . ' ' . $row["Address2"] . ' ' . $row["City"] . ' ' . $row["State"] . ' ' . $row["PostalCode"] . ' ' . $row["CountryCode"] . '</td>
             </tr>';
    }
}
else
{
    // GMC - 05/27/10 - Edit Shipping Information in CSRADMIN
	// echo '<tr><th>&nbsp;</th><td>No Extra Shipping Address to Delete</td></tr>';
	echo '<tr><th>&nbsp;</th><td>No Extra Shipping Address to Modify</td></tr>';
}

if (mssql_num_rows($qryGetShippingAddressesToModify) > 0)
{
    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
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
        <td><select name="ShipCountryCode" size="1" onChange="getStateProvinceShip(this.value)">' . $selectCountries . '</select></td>
    </tr>

    <script>
    function getStateProvinceShip(sType)
    {
        var sCountry = sType;

        if(sCountry == "US")
        {
            document.getElementById("state_province").innerHTML = "";
            var sOutput = "<select name=ShipState size=1>"
            + "<option value=0> -- SELECT -- </option>"
            + "<option value=AL>Alabama</option>"
            + "<option value=AK>Alaska</option>"
            + "<option value=AZ>Arizona</option>"
            + "<option value=AR>Arkansas</option>"
            + "<option value=CA>California</option>"
            + "<option value=CO>Colorado</option>"
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
            + "<option value=SD>South Dakota</option>"
            + "<option value=TN>Tennessee</option>"
            + "<option value=TX>Texas</option>"
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
            var sOutput = "<select name=ShipState size=1>"
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

        else if(sCountry == "HK")
        {
            document.getElementById("city_dynamic").innerHTML = "";
            var sOutput = "<select name=ShipCity size=1 onChange=getHKProvince(this.value)>"
            + "<option value=0> -- SELECT -- </option>"
            + "<option value=CH01>Aberdeen</option>"
            + "<option value=CH02>Admiralty</option>"
            + "<option value=CH03>Ap lei Chau</option>"
            + "<option value=CH04>Causeway Bay</option>"
            + "<option value=CH05>Central</option>"
            + "<option value=CH06>Chaiwan</option>"
            + "<option value=CH07>Chek Lap Kok</option>"
            + "<option value=CH08>Cheung Chau</option>"
            + "<option value=CH09>Cheung Sha Wan</option>"
            + "<option value=CH10>Choi Hung</option>"
            + "<option value=CH11>Chok Ko Wan</option>"
            + "<option value=CH12>Chung Hom Kok</option>"
            + "<option value=CH13>Clear Water Bay</option>"
            + "<option value=CH14>Discovery Bay</option>"
            + "<option value=CH15>Fanling</option>"
            + "<option value=CH16>Fo Tan</option>"
            + "<option value=CH17>Happy Valley</option>"
            + "<option value=CH18>Ho Man Tin</option>"
            + "<option value=CH19>Hong Lok Yuen</option>"
            + "<option value=CH20>Hung Hom</option>"
            + "<option value=CH21>Jardine Lookout</option>"
            + "<option value=CH22>Jordan</option>"
            + "<option value=CH23>Junk Bay</option>"
            + "<option value=CH24>Kennedy Town</option>"
            + "<option value=CH25>Kowloon Bay</option>"
            + "<option value=CH26>Kowloon City</option>"
            + "<option value=CH27>Kowloon Tong</option>"
            + "<option value=CH28>Kwai Chung</option>"
            + "<option value=CH29>Kwai Fong</option>"
            + "<option value=CH30>Kwun Tong</option>"
            + "<option value=CH31>Lai Chi Kok</option>"
            + "<option value=CH32>Lam Tin</option>"
            + "<option value=CH33>Lamma Island</option>"
            + "<option value=CH34>Lantau Island</option>"
            + "<option value=CH35>Lei Yue Mun</option>"
            + "<option value=CH36>Ma On Shan</option>"
            + "<option value=CH37>Ma Wan</option>"
            + "<option value=CH38>Mid-Level</option>"
            + "<option value=CH39>Mongkok</option>"
            + "<option value=CH40>Ngau Tau Kok</option>"
            + "<option value=CH41>North Point</option>"
            + "<option value=CH42>Peak</option>"
            + "<option value=CH43>Peng Chau</option>"
            + "<option value=CH44>Pennys Bay</option>"
            + "<option value=CH45>Pokfulam</option>"
            + "<option value=CH46>Quarry Bay</option>"
            + "<option value=CH47>Queensway</option>"
            + "<option value=CH48>Repulse Bay</option>"
            + "<option value=CH49>Sai Kung</option>"
            + "<option value=CH50>Sai Wan Ho</option>"
            + "<option value=CH51>Sai Ying Pun</option>"
            + "<option value=CH52>San Po Kong</option>"
            + "<option value=CH53>Sham Shui Po</option>"
            + "<option value=CH54>Sham Tseng</option>"
            + "<option value=CH55>Shatin</option>"
            + "<option value=CH56>Shaukiwan</option>"
            + "<option value=CH57>Shek Kip Mei</option>"
            + "<option value=CH58>Shek O</option>"
            + "<option value=CH59>Sheung Shui</option>"
            + "<option value=CH60>Sheung Wan</option>"
            + "<option value=CH61>Siu Ho Wan</option>"
            + "<option value=CH62>Siu Lik Yuen</option>"
            + "<option value=CH63>Siu Sai Wan</option>"
            + "<option value=CH64>South Bay</option>"
            + "<option value=CH65>Stanley</option>"
            + "<option value=CH66>Tai Hang</option>"
            + "<option value=CH67>Tai Kok Tsui</option>"
            + "<option value=CH68>Tai O</option>"
            + "<option value=CH69>Tai Wai</option>"
            + "<option value=CH70>Taipo</option>"
            + "<option value=CH71>Tin Shui Wai</option>"
            + "<option value=CH72>Tokwawan</option>"
            + "<option value=CH73>Tseung Kwan O</option>"
            + "<option value=CH74>Tsimshatsui</option>"
            + "<option value=CH75>Tsimshatsui East</option>"
            + "<option value=CH76>Tsing Yi</option>"
            + "<option value=CH77>Tsuen Wan</option>"
            + "<option value=CH78>Tsz Wan Shan</option>"
            + "<option value=CH79>Tuen Mun</option>"
            + "<option value=CH80>Tung Chung</option>"
            + "<option value=CH81>Wanchai</option>"
            + "<option value=CH82>Wang Tau Hom</option>"
            + "<option value=CH83>Western</option>"
            + "<option value=CH84>Wong Chuk Hang</option>"
            + "<option value=CH85>Yau Ma Tei</option>"
            + "<option value=CH86>Yau Tong</option>"
            + "<option value=CH87>Yau Yat Chuen</option>"
            + "<option value=CH88>Yuen Long</option>"
            + "</select>";
            document.getElementById("city_dynamic").innerHTML = sOutput;

            document.getElementById("state_province").innerHTML = "";
            var sOutput = "<input type=text name=ShipState size=5 value= HK />";
            document.getElementById("state_province").innerHTML = sOutput;
        }

        else
        {
            document.getElementById("state_province").innerHTML = "";
            var sOutput = "<input type=text name=ShipState size=5 />";
            document.getElementById("state_province").innerHTML = sOutput;
        }
    }
    </script>

    <!-- GMC - 03/18/12 - Hong Kong Change for City and Zip Code -->
    <tr>
        <th>City/Province:</th>
        <!--<td><input type="text" name="ShipCity" size="40" value="' . $row["City"] . '" /></td>-->
		<td><div id="city_dynamic"><input type="text" name="ShipCity" size="40" value="' . $row["City"] . '" /></div></td>
    </tr>

    <tr>
        <th>State/Region:</th>
        <td><div id="state_province"><input type="text" name="ShipState" size="5" value="' . $row["State"] . '" /></div></td>
    </tr>

    <tr>
        <th>Postal Code:</th>
        <!--<td><input type="text" name="ShipPostalCode" size="20" value="' . $row["PostalCode"] . '" /></td>-->
		<td><div id="zip_dynamic"><input type="text" name="ShipPostalCode" size="20" value="' . $row["PostalCode"] . '" /></div></td>
    </tr>
    
    <script>
    function getHKProvince(sType)
    {
         document.getElementById("zip_dynamic").innerHTML = "";
         var sCity = sType;
         var sOutput = "";

         if(sCity == "CH01")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH02")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH03")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH04")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH05")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=504 />";
         }
         else if(sCity == "CH06")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH07")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH08")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH09")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH10")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH11")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH12")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH13")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH14")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH15")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         else if(sCity == "CH16")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=221 />";
         }
         else if(sCity == "CH17")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH18")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH19")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=221 />";
         }
         else if(sCity == "CH20")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH21")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH22")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH23")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH24")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=504 />";
         }
         else if(sCity == "CH25")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH26")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH27")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH28")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         else if(sCity == "CH29")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH30")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH31")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH32")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH33")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH34")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH35")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH36")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=221 />";
         }
         else if(sCity == "CH37")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         else if(sCity == "CH38")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=504 />";
         }
         else if(sCity == "CH39")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH40")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH41")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH42")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=504 />";
         }
         else if(sCity == "CH43")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH44")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH44")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=504 />";
         }
         else if(sCity == "CH45")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH46")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH47")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH48")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH49")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH50")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=504 />";
         }
         else if(sCity == "CH51")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH52")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH53")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         else if(sCity == "CH54")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=221 />";
         }
         else if(sCity == "CH55")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH56")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH57")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH58")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         else if(sCity == "CH59")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=504 />";
         }
         else if(sCity == "CH60")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH61")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=221 />";
         }
         else if(sCity == "CH62")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH63")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH64")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH65")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH66")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH67")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH68")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=221 />";
         }
         else if(sCity == "CH69")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=221 />";
         }
         else if(sCity == "CH70")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         else if(sCity == "CH71")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH72")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH73")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH74")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH75")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         else if(sCity == "CH76")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         else if(sCity == "CH77")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH78")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         else if(sCity == "CH79")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=898 />";
         }
         else if(sCity == "CH80")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH81")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=312 />";
         }
         else if(sCity == "CH82")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=504 />";
         }
         else if(sCity == "CH83")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=425 />";
         }
         else if(sCity == "CH84")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH85")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=511 />";
         }
         else if(sCity == "CH86")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=164 />";
         }
         else if(sCity == "CH87")
         {
            sOutput = "<input type=text name=ShipPostalCode size=5 value=582 />";
         }
         document.getElementById("zip_dynamic").innerHTML = sOutput;
    }
    </script>';

    }
}
?>

<tr><td colspan="2">&nbsp;</td></tr>

<!-- GMC - 05/27/10 - Edit Shipping Information in CSRADMIN
<tr><th>&nbsp;</th><td><input type="submit" name="cmdDeleteShippingAddress" value="Delete Address" class="formSubmit" /></td></tr>
-->
<!-- GMC - 12/15/10 - Add Nav Rep Id and ability to delete shipping address -->
<tr>
<th>&nbsp;</th>
<td>
<input type="submit" name="cmdModifyShippingAddress" value="Modify Address" class="formSubmit" />
&nbsp;&nbsp;

<?php
// GMC - 03/22/12 - Make Edit Shipping Information available to all CSR and delete only for JStancarone
// GMC - 04/18/12 - Delete Option Open for CFELIX
if ($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 41)
{
    echo '<input type=submit name=cmdDeleteShippingAddress value=Delete Address class=formSubmit />';
}
?>

</td>
</tr>

</form>
</table>

</td>

</tr>
</table>
<p>&nbsp;</p>
