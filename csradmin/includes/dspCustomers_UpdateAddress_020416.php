<!-- GMC - 11/16/08 - Domestic Vs. International 2nd Phase -->

<h1>Select Shipping Address</h1>

<?php

if ($_SESSION['UserTypeID'] == 1)
	echo '<p style="margin:10px; text-align:center; font-style:italic;"><font color="red"><strong>To make changes to this customer record, you must fax changes to 888-371-3171.</strong></font></p>';

if (isset($pageerror))
{
	echo '<p class="error">' . $pageerror . '</p>';
}

echo '<table width="900" cellpadding="2" cellspacing="0" style="margin:10px;">';

// POPULATE CUSTOMER DATA
while($rowGetCustomer = mssql_fetch_array($qryGetCustomer))
{
    $intCustomerType = $rowGetCustomer["CustomerTypeID"];
	$blnIsApprovedTerms = $rowGetCustomer["IsApprovedTerms"];
	$_SESSION['CustomerTerms'] = $blnIsApprovedTerms;

	if ($rowGetCustomer["CountryCode"] == 'US')
		$blnIsInternational = 0;
	else
		$blnIsInternational = 1;

	echo '<tr>
        <th width="140" style="text-align:left;">Company:</th>
        <td width="*">' . $rowGetCustomer["CompanyName"] . '</td>
    </tr>

	<tr>
        <th style="text-align:left;">Contact Name:</th>
        <td>' . $rowGetCustomer["FirstName"] . ' ' . $rowGetCustomer["LastName"] . '</td>
    </tr>

	<tr>
        <th style="text-align:left;">Country:</th>
        <td>' . $rowGetCustomer["CountryCode"] . '</td>
    </tr>';

	if ($intCustomerType == 1)
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Consumer</td></tr>';
	elseif ($intCustomerType == 2)
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Spa/Reseller</td></tr>';
	elseif ($intCustomerType == 3)
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Distributor</td></tr>';
		
    // GMC - 03/08/09 - Customer Type "REP"
	elseif ($intCustomerType == 4)
		echo '<tr><th style="text-align:left;">Customer Type:</th><td>Rep</td></tr>';

}

// INITIALIZE DEFAULT SESSION VARIABLES
/*
if (!isset($_SESSION['FORMItemID1']))
{
	$_SESSION['FORMItemID1'] = 0;
	$_SESSION['FORMItemStockLocation1'] = 'MAIN';
	$_SESSION['FORMItemQty1'] = '';

	$_SESSION['FORMItemID2'] = 0;
	$_SESSION['FORMItemStockLocation2'] = 'MAIN';
	$_SESSION['FORMItemQty2'] = '';

	$_SESSION['FORMItemID3'] = 0;
	$_SESSION['FORMItemStockLocation3'] = 'MAIN';
	$_SESSION['FORMItemQty3'] = '';

	$_SESSION['FORMItemID4'] = 0;
	$_SESSION['FORMItemStockLocation4'] = 'MAIN';
	$_SESSION['FORMItemQty4'] = '';

	$_SESSION['FORMItemID5'] = 0;
	$_SESSION['FORMItemStockLocation5'] = 'MAIN';
	$_SESSION['FORMItemQty5'] = '';

	$_SESSION['FORMItemID6'] = 0;
	$_SESSION['FORMItemStockLocation6'] = 'MAIN';
	$_SESSION['FORMItemQty6'] = '';

	$_SESSION['FORMItemID7'] = 0;
	$_SESSION['FORMItemStockLocation7'] = 'MAIN';
	$_SESSION['FORMItemQty7'] = '';

	$_SESSION['FORMItemID8'] = 0;
	$_SESSION['FORMItemStockLocation8'] = 'MAIN';
	$_SESSION['FORMItemQty8'] = '';

	$_SESSION['FORMItemID9'] = 0;
	$_SESSION['FORMItemStockLocation9'] = 'MAIN';
	$_SESSION['FORMItemQty9'] = '';

	$_SESSION['FORMItemID10'] = 0;
	$_SESSION['FORMItemStockLocation10'] = 'MAIN';
	$_SESSION['FORMItemQty10'] = '';
}
*/

echo '</table>';

?>

<form action="/csradmin/customers.php?Action=UpdateAddress&CustomerID=<?php echo $intCustomerID; ?>" method="post">

<table width="100%" cellpadding="5" cellspacing="0">

<tr>
    <td width="30">&nbsp;</td>
    <td width="150">&nbsp;</th>
    <td width="*"><font color="red"><strong>YOU MUST HIGHLIGHT A SHIPPING ADDRESS OR ADD A NEW SHIPPING ADDRESS AND CLICK "UPDATE ADDRESS" TO CONTINUE.</strong></font></td>
</tr>

<tr>
    <td width="30"><input type="radio" name="ShippingAddress" value="UseExisting" checked="checked" />
    <td width="150">Shipping Addresses:</th>
    <td width="*"><select name="ShipToID" size="1">
    <?php

    // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
    while($row = mssql_fetch_array($qryGetCustomerShipTo))
    {
		if (isset($_SESSION['CustomerShipToID']) && $_SESSION['CustomerShipToID'] == $row["RecordID"])
			echo '<option value="' . $row["RecordID"] . '">' . $row["CompanyName"] . ' - ' . $row["Attn"] . ' - ' . $row["Address1"] . ' - ' . $row["Address2"] . ' - ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . ' ' . $row["CountryCode"] . '</option>';
		else
			echo '<option selected="selected" value="' . $row["RecordID"] . '">' . $row["CompanyName"] . ' - ' . $row["Attn"] . ' - ' . $row["Address1"] . ' - ' . $row["Address2"] . ' - ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . ' ' . $row["CountryCode"] . '</option>';
    }
    ?>
    </select>
    </td>
</tr>

<?php

if ($_SESSION['UserTypeID'] != 1)
{
	echo '<tr>
		<td width="30"><input type="radio" name="ShippingAddress" value="New" />
		<td colspan="2">New Shipping Address </td>
	</tr>

	<tr>
		<!--<td width="30">&nbsp;</td>
		<th width="100">First Name:</th>-->
		
		<!-- GMC - 07/09/09 - Changes by JS -->
		<!--<th colspan="2">First Name:</th>-->

		<th colspan="2">Company:</th>

        <!--GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
		<td width="*"><input type="text" name="FirstName" size="20" value="" /></td>
        -->
		<td width="*"><input type="text" name="CompanyName" size="20" value="" /></td>

	</tr>

	<tr>

		<!-- GMC - 07/09/09 - Changes by JS -->
		<!--<th colspan="2">Last Name:</th>-->

		<th colspan="2">Contact Name:</th>

        <!--GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
		<td><input type="text" name="LastName" size="20" value="" /></td>
        -->
		<td><input type="text" name="ContactName" size="20" value="" /></td>

	</tr>

	<tr>
		<th colspan="2">Street Address 1:</th>
		<td><input type="text" name="Address1" size="40" value="" /></td>
	</tr>

	<tr>
		<th colspan="2">Street Address 2:</th>
		<td><input type="text" name="Address2" size="40" value="" /></td>
	</tr>

    <!-- GMC - 04/22/10 - US and CA Drop Downs for State and Province -->
	<tr>
		<th colspan="2">Country Code:</th>
		<td><select name="CountryCode" onChange="getStateProvince(this.value)">

        <!-- GMC - Add Select to Country Code Drop Down -->
		<option value="0">-- SELECT --</option>';

        while($row = mssql_fetch_array($cboCountryCodes))
		{
		  echo '<option value="' . $row["CountryCode"] . '">' . $row["CountryName"] . '</option>';
		}

		echo '</select></td>
	</tr>

    <!-- GMC - 03/13/12 - Flip City State in Update Address -->
    <!-- GMC - 03/18/12 - Hong Kong Change for City and Zip Code -->
    <tr>
		<th colspan="2">City/Province:</th>
		<!--<td><input type="text" name="City" size="20" value="" /></td>-->
		<td><div id="city_dynamic"><input type="text" name="City" size="20" value="" /></div></td>
	</tr>

	<tr>
		<th colspan="2">State/Region:</th>
		<td><div id="state_province"><input type="text" name="State" size="5" value="" /></div></td>
    </tr>

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
        
        else if(sCountry == "HK")
        {
            document.getElementById("city_dynamic").innerHTML = "";
            var sOutput = "<select name=City size=1 onChange=getHKProvince(this.value)>"
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
            var sOutput = "<input type=text name=State size=5 value= HK />";
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
		<th colspan="2">Zip or Postal Code:</th>
		<td><div id="zip_dynamic"><input type="text" name="PostalCode" size="10" value="" /></div></td>
	</tr>

    <script>
    function getHKProvince(sType)
    {
         document.getElementById("zip_dynamic").innerHTML = "";
         var sCity = sType;
         var sOutput = "";

         if(sCity == "CH01")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH02")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH03")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH04")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH05")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=504 />";
         }
         else if(sCity == "CH06")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH07")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH08")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH09")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH10")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH11")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH12")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH13")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH14")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH15")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         else if(sCity == "CH16")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=221 />";
         }
         else if(sCity == "CH17")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH18")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH19")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=221 />";
         }
         else if(sCity == "CH20")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH21")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH22")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH23")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH24")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=504 />";
         }
         else if(sCity == "CH25")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH26")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH27")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH28")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         else if(sCity == "CH29")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH30")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH31")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH32")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH33")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH34")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH35")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH36")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=221 />";
         }
         else if(sCity == "CH37")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         else if(sCity == "CH38")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=504 />";
         }
         else if(sCity == "CH39")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH40")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH41")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH42")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=504 />";
         }
         else if(sCity == "CH43")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH44")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH44")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=504 />";
         }
         else if(sCity == "CH45")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH46")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH47")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH48")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH49")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH50")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=504 />";
         }
         else if(sCity == "CH51")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH52")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH53")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         else if(sCity == "CH54")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=221 />";
         }
         else if(sCity == "CH55")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH56")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH57")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH58")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         else if(sCity == "CH59")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=504 />";
         }
         else if(sCity == "CH60")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH61")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=221 />";
         }
         else if(sCity == "CH62")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH63")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH64")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH65")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH66")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH67")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH68")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=221 />";
         }
         else if(sCity == "CH69")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=221 />";
         }
         else if(sCity == "CH70")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         else if(sCity == "CH71")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH72")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH73")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH74")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH75")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         else if(sCity == "CH76")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         else if(sCity == "CH77")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH78")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         else if(sCity == "CH79")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=898 />";
         }
         else if(sCity == "CH80")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH81")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=312 />";
         }
         else if(sCity == "CH82")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=504 />";
         }
         else if(sCity == "CH83")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=425 />";
         }
         else if(sCity == "CH84")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH85")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=511 />";
         }
         else if(sCity == "CH86")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=164 />";
         }
         else if(sCity == "CH87")
         {
            sOutput = "<input type=text name=PostalCode size=5 value=582 />";
         }
         document.getElementById("zip_dynamic").innerHTML = sOutput;
    }
    </script>

    <!-- GMC - 02/01/11 - Order Closed By CSR ADMIN Partner - Rep -->
	<tr>
		<th colspan="2">Order Closed By:</th>
		<td><input type="text" name="OrderClosedBy" size="30" value="" /></td>
	</tr>';
 
}

?>

<tr><td colspan="3"><input type="submit" name="cmdContinue" value="Update Address" class="formSubmit" /></td></tr>

</table>

</table>

</form>

<p>&nbsp;</p>
