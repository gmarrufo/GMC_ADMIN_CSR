<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

    ini_set('display_errors', false);
    require_once("../modules/session.php");
    require_once("../modules/db.php");
    include("../includes/selCountries.php");
    include("../includes/selSecurityQuestions.php");

    if (isset($_POST['cmdOneTimeRegister']))
    {
         // GMC - 03/29/10 - Force to Shipping Methods
         // Check if user is already in tblCustomers based on email
         $rsEmailExists = mssql_query("SELECT * FROM tblCustomers WHERE EmailAddress = '" . $_POST['EMailAddress'] . "'");
         $intTotalRows = mssql_num_rows($rsEmailExists);

         if($intTotalRows > 0)
         {
             while($row = mssql_fetch_array($rsEmailExists))
             {
                 $_SESSION['CustomerIsLoggedIn'] = 1;
                 $_SESSION['CustomerTypeID'] = 1;
                 $_SESSION['CustomerID'] = $row["RecordID"];
                 $_SESSION['FirstName'] = $row["FirstName"];
                 $_SESSION['LastName'] = $row["LastName"];
                 $_SESSION['EMailAddress'] = $row["EMailAddress"];

                 // GMC - 02/21/10 - Add New Shopping Cart Flow
                 $_SESSION['Country_Customer'] = $row["CountryCode"];
             }

	         include("../modules/retail_setshipping.php");

             // CHANGE WHEN GOING TO PRODUCTION
             header("Location: https://secure.revitalash.com/retail/cart.php?AddToCart=100&Country=" . $_SESSION['Country_Customer'] . "&CustomerID=" . $_SESSION['CustomerID']);
             // header("Location: http://localhost/retail/cart.php?AddToCart=100&Country=" . $_SESSION['Country_Customer'] . "&CustomerID=" . $_SESSION['CustomerID']);
         }
         else
         {
             //ATTEMPT TO CREATE NEW CUSTOMER
             include("../modules/newconsumer.php");

             // CHANGE WHEN GOING TO PRODUCTION
             header("Location: https://secure.revitalash.com/retail/cart.php?AddToCart=100&Country=" . $_SESSION['Country_Customer'] . "&CustomerID=" . $_SESSION['CustomerID']);
             // header("Location: http://localhost/retail/cart.php?AddToCart=100&Country=" . $_SESSION['Country_Customer'] . "&CustomerID=" . $_SESSION['CustomerID']);
         }
    }

    // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
    // ADDING THE COMMENT BUT IT IS REALLY THE FIX FOR THE COUNTRY CODE NOT BEING FORCED - JUST ON THIS FILE
?>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Shopping Cart | Revitalash.com</title>
<link rel="stylesheet" type="text/css" href="/styles/revitalash.css" />

<script type="text/javascript">

	function validate_required(field,alerttxt){with (field){if (value==null||value==""||value=="Select Country"){alert(alerttxt);return false;}else{return true}}}

	function validate_login(thisform)
	{
		with (thisform)
		{
			if (validate_required(EMailAddress,"You must supply your login email address")==false)
		  	{return false;}
			else if (validate_required(Password,"You must supply your login password")==false)
		  	{return false;}
		}
	}

	function validate_new(thisform)
	{
		with (thisform)
		{
			if (validate_required(FirstName,"You must enter your first name")==false)
		  	{return false;}
			else if (validate_required(LastName,"You must enter your last name")==false)
		  	{return false;}
			else if (validate_required(Address1,"You must enter your street address")==false)
		  	{return false;}
			else if (validate_required(City,"You must enter your city")==false)
		  	{return false;}
			else if (validate_required(PostalCode,"You must enter your postal code")==false)
		  	{return false;}
			else if (validate_required(CountryCode,"You must enter your country")==false)
		  	{return false;}
			else if (validate_required(Telephone,"You must enter your telephone number")==false)
		  	{return false;}
			else if (validate_required(EMailAddress,"You must enter your e-mail address")==false)
		  	{return false;}
			else if (validate_required(SecurityAnswer,"You must enter your security answer")==false)
		  	{return false;}
			else if (validate_required(Password,"You must enter your password")==false)
		  	{return false;}
			else if (validate_required(PasswordConfirm,"You must enter your password confirmation")==false)
		  	{return false;}
		}
	}

</script>

</head>

<body>

<div id="masthead">
<!--<img src="/images/interface/masthead_logo.jpg" alt="Masthead Logo" width="730" height="100" />-->
<img src="/images/interface/revitalash-logo.png" alt="Masthead Logo" />
</div>

<div id="wrapper">
<!--<div class="consumer_header">Shipping Information<br>Only<span style="margin-left:540px;">Revitalash&reg;</span></div>-->
<div class="consumer_header">Shipping Information Only</div>

<form action="../includes/dspRetail_OneTimeRegistration.php" method="post" onSubmit="return validate_new(this);">

<p class="why">
<img src="/images/question_mark.png" alt="Why register" hspace="5" vspace="8" border="0" align="left">
<strong>Why should i register?</strong> Our one-time registration means you won't have to re-enter your information every time you<br>
visit the site. As a returning customer you can benefit from future promotions and a faster checkout process.<br>
<br>
<strong>Your information will not be shared, sold, or exchanged with any person or company in any manner.</strong>
</p>

<table width="100%" cellpadding="0" cellspacing="0" border="0">

<tr>
    <td width="5%">&nbsp;</th>
    <td width="55%" style="font-weight:bold;">&nbsp;</th>
    <td width="45%" style="font-weight:bold;color:red;text-align:center;">&nbsp; NOTE: We do not deliver to PO Boxes</th>
</tr>

<tr>
    <td width="5%">&nbsp;</th>

    <!-- GMC - 03/12/10 - Take out Security Question, Password by JS -->
	<!--<td width="55%" style="font-weight:bold;">&nbsp; SHIPPING INFORMATION</th>-->
	<td width="55%" style="font-weight:bold;">&nbsp; SHIPPING INFORMATION ONLY</th>

    <td width="45%" style="font-weight:bold;">&nbsp;</th>
</tr>

<tr>
<td width="5%">&nbsp;</th>
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

    <!-- GMC - 04/22/10 - US and CA Drop Downs for State and Province -->
    <tr>
        <th>Country Code: <span class="required">*</span></th>
        <td><select name="CountryCode" size="1" onChange="getStateProvince(this.value)">
        <?php echo $selectCountries; ?>
        </select></td>
    </tr>

    <tr>
        <th>State/Province:</th>
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
            + "<option value=TX>Texas</option>"
            + "<option value=TN>Tennesse</option>"
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
        <th>City: <span class="required">*</span></th>
        <td><input type="text" name="City" size="20" value="" /></td>
    </tr>

    <tr>
        <th>Postal Code: <span class="required">*</span></th>
        <td><input type="text" name="PostalCode" size="10" value="" /></td>
    </tr>

    <tr>
        <th>Telephone: <span class="required">*</span></th>
        <td><input type="text" name="Telephone" size="15" value="" /></td>
    </tr>

    <tr>
        <th>Email: <span class="required">*</span></th>
        <td><input type="text" name="EMailAddress" size="40" value="" /><br><font color="red"><b>Your Email is used to send you Shipping Information</b></font>

        <!-- GMC - 03/12/10 - Take out Security Question, Password by JS -->
        <input type="hidden" name="SecurityAnswer" value="blahblah" />
        <input type="hidden" name="Password" value="blahblah" />
        <input type="hidden" name="PasswordConfirm" value="blahblah" />

        </td>
    </tr>

    <!-- GMC - 03/12/10 - Take out Security Question, Password by JS -->
    <!--
    <tr>
        <th>Security Question: <span class="required">*</span></th>
        <td><select name="SecurityQuestionID" size="1">
        <?php// echo $selectSecurityQuestions; ?>
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
    -->
    
    </table>
    
</td>
<td>
&nbsp;
</td>

</tr>

<tr>
	<td colspan="2" align="center"><input type="submit" name="cmdOneTimeRegister" value="Continue" class="formSubmit" /></td>
</tr>

</table>

</form>

</div>

</body>

</html>
