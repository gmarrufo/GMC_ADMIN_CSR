<form name="cartform" action="https://secure.revitalash.com/retail/cart.php" method="post">
<table width="600" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#6666CC">
<tr>
    <td>
    <table width="600" border="0" align="center" cellpadding="4" cellspacing="0">
    
    <tr class="ContentBG">
        <td width="9" height="30" class="ContentBG">&nbsp;</td>
        <td colspan="3" valign="bottom"><div align="right"><table width="204" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
                <td height="50" valign="top" class="textNormBlack"><div align="right"><span class="textBoldblackTotal">retail customer cart only!</span><br />if you are a spa or reseller please use the reseller cart by clicking below.</div></td>
            </tr>
            <tr>
                <td valign="top"><div align="right"><img src="/images/btn_spa.png" width="178" height="26" alt="login"></div></td>
            </tr>
            </table></div>
        </td>
        <td height="30" colspan="3" valign="top"><div align="right"><img src="/images/toplogo.png" width="247" height="73"></div></td>
    </tr>
    
    
    <tr class="TopHeadder">
        <td width="9">&nbsp;</td>
        <td colspan="3"><div align="center">ITEM DESCRIPTION</div></td>
        
        <td width="40"><div align="center">QTY</div></td>
        <td width="71"><div align="right">EACH</div></td>
        <td width="121"><div align="right">TOTAL</div></td>
    </tr>
      
    <?php
    // DISPLAY CART CONTENTS
	echo $cart_table;

    // DISPLAY DISCOUNT IF ANY
	if (isset($_SESSION['OrderSubtotalDiscount']) && $_SESSION['OrderSubtotalDiscount'] > 0)
    {
		echo '<tr class="ContentAcc">';
		echo '<td>&nbsp;</td>';
		echo '<td colspan="3" class="textNorm"><div align="right">' . $SESSION['OrderSubtotalDiscountDisplay'] . '</div></td>';
		echo '<td class="textNorm">&nbsp;</td>';
		echo '<td class="textNorm">&nbsp;</td>';
		echo '<td><div align="right" class="textNorm">-$' . $SESSION['OrderSubtotalDiscount'] . '</div></td>';
		echo '</tr>';
	  
	}
	else
	{
		echo '<tr class="ContentAcc">';
		echo '<td colspan="7"><span class="textNormBlack">Coupon code? Enter it here:</span> <span class="textNormBlack"><input name="couponCode" type="text" class="textNormBlack" id="code" size="5">';
		if (isset($_POST['couponCode']) && $_POST['couponCode'] != '') echo ' &nbsp; <span style="font-weight:bold;">The code you entered was invalid.</span>';
		echo '</td></tr>';
	}
	?> 

      
    <tr>
        <td rowspan="3" class="ContentBG">&nbsp;</td>
        <td colspan="3" rowspan="3" valign="bottom" class="ContentBG">
        
        
        <?php
        if (!(isset($_SESSION['CustomerIsLoggedIn'])))
		{
			echo '<table width="100%" border="0" align="right" cellpadding="0" cellspacing="0">
			  <tr>
	
				<td height="40" valign="top"><span class="textBoldblackTotal">returning customers</span><br>
				  sign in for faster checkout.</td>
			  </tr>
			  <tr>
				<td height="20" class="textBoldblack">Email</td>
			  </tr>
			  <tr>
	
				<td height="20"><label>
				  <input name="LoginEMailAddress" type="text" class="textBoldblack" id="email2" size="25">
				</label></td>
			  </tr>
			  <tr>
				<td height="20" class="textBoldblack">Password</td>
			  </tr>
			  <tr>
	
				<td height="20"><label>
				  <input name="LoginPassword" type="text" class="textBoldblack" id="password2" size="25">
				</label></td>
			  </tr>
			  <tr>
				<td height="20" valign="top" class="textNormBlack">Forgot your password?</td>
			  </tr>
			  <tr>
	
				<td valign="top"><input type="image" name="cmdLogin" src="/images/btn_login.png" width="178" height="26" alt="login"></td>
			  </tr>
			</table>';
		}
		else
			echo '&nbsp;';
		?>
        
        
        </td>
        <td colspan="3" valign="top" class="ContentBG"><div align="right">
            
            <!--- SHIPPING CALC TABLE --->
            <table width="100%" border="0" cellpadding="0" cellspacing="2">
        
            <tr>
                <td class="textNormBlack"><div align="center" class="textBoldblackTotal"><div align="right">calculate shipping rates:</div></div></td>
            </tr>
            
            <tr>
                <td class="textNormBlack"><div align="right">Country: <select name="shipCalc_Country" class="textNormBlack" id="shipCalc_Country"><?php echo $sel_SelectCountry1; ?></select></div></td>
            </tr>
            
            <tr>
                <td class="textNormBlack"><div align="right">State/Province: <input name="shipCalc_State" type="text" class="textBoldblack" id="shipCalc_State" value="<?php if(isset($_POST['shipCalc_State'])) echo $_POST['shipCalc_State']; ?>" size="19"></div></td>
            </tr>
            
            <tr>
                <td class="textNormBlack"><div align="right">Zip / PostalCode: <input name="shipCalc_PostalCode" type="text" class="textBoldblack" id="shipCalc_PostalCode" value="<?php if(isset($_POST['shipCalc_PostalCode'])) echo $_POST['shipCalc_PostalCode']; ?>" size="7" maxlength="15"></div></td>
            </tr>
            
            <tr>
                <td class="textNormBlack"><div align="right">Address Type:
                <input name="shipCalc_AddressType" type="radio" class="textNormBlack" id="shipCalc_AddressType" value="residential" <?php if(isset($_POST['shipCalc_AddressType']) && $_POST['shipCalc_AddressType'] == 'residential') echo 'checked="checked"'; ?> /> Home
                <input name="shipCalc_AddressType" type="radio" class="textNormBlack" id="shipCalc_AddressType" value="business" <?php if(isset($_POST['shipCalc_AddressType']) && $_POST['shipCalc_AddressType'] == 'business') echo 'checked="checked"'; ?> /> Business
                </div></td>
            </tr>
            
            <tr>
                <td class="textNormBlack"><div align="right">Ship Via:
                <select name="shipCalc_ShipVia" class="textNormBlack" id="shipCalc_ShipVia" onchange="cartform.submit();"><?php echo $sel_ShippingMethods; ?></select>
                </div></td>
            </tr>
        
            </table>
        
        </div></td>
    </tr>
      
    <tr>
        <td colspan="2" bgcolor="#B6B5E7" class="textBold"><div align="right" class="textBoldblack">Shipping:</div></td>
        <td bgcolor="#B6B5E7" class="textBold"><div align="right"><span class="textBoldblack">US $<?php if (isset($_SESSION['OrderShipping'])) echo number_format($_SESSION['OrderShipping'], 2, '.', ''); else echo '0.00'; ?></span></div></td>
    </tr>
      
    <tr>
        <td colspan="2" bgcolor="#B6B5E7" class="textBold"><div align="right" class="textBoldblack">Tax:</div></td>
        <td bgcolor="#B6B5E7" class="textBold"><div align="right" class="textBoldblack">US $<?php if (isset($_SESSION['OrderTax'])) echo number_format($_SESSION['OrderTax'], 2, '.', ''); else echo '0.00'; ?></div></td>
    </tr>
    
    <tr>
        <td class="ContentBG">&nbsp;</td>
        <td colspan="3" valign="bottom" class="ContentBG">&nbsp;</td>
        <td colspan="3" bgcolor="#B6B5E7" class="textBold"><hr align="center" width="100%" size="2" noshade class="textBoldblack" id="LINE"></td>
    </tr>
    
    <tr>
        <td bgcolor="#B6B5E7">&nbsp;</td>
        <td colspan="3" bgcolor="#B6B5E7">&nbsp;</td>
        <td colspan="2" bgcolor="#B6B5E7"><div align="right" class="textBoldblackTotal">Total:</div></td>
        <td bgcolor="#B6B5E7" class="TextWhite16"><div align="right" class="textBoldblackTotal">US $<?php echo number_format($_SESSION['OrderTotal'], 2, '.', ''); ?></div></td>
    </tr>
    
    <tr class="TopHeadder">
        <td>&nbsp;</td>
        <td colspan="3"><div align="center">BILLING&nbsp; INFORMATION</div></td>
        <td colspan="3"><div align="left">
          <div align="center">SHIPPING&nbsp;  INFORMATION</div>
        </div></td>
    </tr>
 
	<tr>
        <td rowspan="3" class="ContentBG">&nbsp;</td>
        <td colspan="3" rowspan="3" valign="top" class="ContentBG">
            <!--- MAIN DATA ENTRY TABLE --->
            <table width="260" border="0" align="right" cellpadding="4" cellspacing="0">
            
            <tr>
              <td width="73" class="textBoldblack"><div align="right">First Name</div></td>
              <td width="165"><label><input name="FirstName" type="text" class="textBoldblack" id="nameFirst" size="25"></label></td>
            </tr>
            
            <tr>
              <td class="textBoldblack"><div align="right">Last Name</div></td>
              <td><input name="LastName" type="text" class="textBoldblack" id="nameLast" size="25"></td>
            </tr>
            
            <tr>
              <td class="textBoldblack"><div align="right">Address</div></td>
              <td><input name="Address1" type="text" class="textBoldblack" id="address1" size="25"></td>
            </tr>
            
            <tr>
              <td class="textNormBlack"><div align="right">Address 2</div></td>
              <td><input name="Address2" type="text" class="textBoldblack" id="address2" size="25"></td>
            </tr>
            
            <tr>
              <td class="textBoldblack"><div align="right">Country</div></td>
              <td><select name="CountryCode" class="textNormBlack" id="country">
                <?php echo $sel_SelectCountry2; ?>
            </select></td>
            </tr>
            
            <tr>
              <td class="textBoldblack"><div align="right">City</div></td>
              <td><input name="City" type="text" class="textBoldblack" id="city" size="25"></td>
            </tr>
            
            <tr>
              <td class="textBoldblack"><div align="right">State</div></td>
              <td><input name="State" type="text" class="textBoldblack" id="city" size="25"></td>
            </tr>
            
            <tr>
              <td class="textBoldblack"><div align="right">Postal Code</div></td>
              <td><input name="PostalCode" type="text" class="textBoldblack" id="zip" size="25"></td>
            </tr>
            
            <tr>
              <td class="textNormBlack"><div align="right">Phone</div></td>
        
              <td><input name="Telephone" type="text" class="textBoldblack" id="phone" size="25"></td>
            </tr>
            
            <tr>
              <td class="textBoldblack"><div align="right">Card Type</div></td>
              <td><select name="CC_Type" class="textNormBlack" id="CC_Type">
                <option value="VISA" selected label="VISA">VISA</option>
                <option label="MC" value="MC">MC</option>
                <option label="DISCOVER" value="DISCOVER">DISCOVER</option>
                <option label="AMEX" value="AMEX">AMEX</option>
                <option label="OTHER" value="OTHER">OTHER</option>
                </select></td>
            </tr>
            
            <tr>
              <td class="textBoldblack"><div align="right">Number</div></td>
              <td><input name="CC_Number" type="text" class="textBoldblack" id="CC_Number" size="25"></td>
            </tr>
            
            <tr>
              <td class="textBoldblack"><div align="right">CVV code</div></td>
              <td><input name="CC_CVV" type="text" class="textBoldblack" id="CC_CVV" size="5" maxlength="4"> <a href="link"><span class="textNormBlack"> what is this?</span></a></td>
            </tr>
        
            <tr>
              <td class="textBoldblack"><div align="right">Expirtion Date</div></td>
              <td><select name="CC_ExpMonth" class="textNormBlack" id="CC_ExpMonth">
                  <option value="01">January</option>
                  <option value="02">February</option>
                  <option value="03">March</option>
                  <option value="04">April</option>
                  <option value="05">May</option>
                  <option value="06">June</option>
                  <option value="07">July</option>
                  <option value="08">August</option>
                  <option value="09">September</option>
                  <option value="10">October</option>
                  <option value="11">November</option>
                  <option value="12">December</option>
                </select>
              <label>
              <select name="CC_ExpYear" class="textNormBlack" id="CC_ExpYear">
                <option value="2008">2008</option>
                <option value="2009">2009</option>
                <option value="2010">2010</option>
                <option value="2011">2011</option>
                <option value="2012">2012</option>
                <option value="2013">2013</option>
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
              </select>
              </label></td>
            </tr>
            <tr>
              <td><div align="right" class="textNormBlack">How did you <br>
                find us?</div></td>
        
                <td><select name="ReferralSource" class="textNormBlack" id="ReferralSource">
                <option value="Undefined">-Select One-</option>
                <option value="Magazine Ad">Magazine Ad</option>
                <option value="Latin Expo">Latin Expo</option>
                <option value="Face and Body">Face and Body</option>
                <option value="Intl. Beauty Expo">Intl. Beauty Expo</option>
                <option value="Emmys">Emmys</option>
                <option value="Comesur Beauty World">Comesur Beauty World</option>
                <option value="Intl. Congress">Intl. Congress</option>
                <option value="Spa and Resort">Spa and Resort</option>
                <option value="Spatec">Spatec</option>
                <option value="IECSC">IECSC</option>
                <option value="Med.Spa Ethetics Conf.">Med.Spa Ethetics Conf.</option>
                <option value="Beyond Beauty">Beyond Beauty</option>
                <option value="Beauty World">Beauty World</option>
                <option value="LA Fashion Week">LA Fashion Week</option>
                <option value="Professional Beauty">Professional Beauty</option>
                <option value="Hotec">Hotec</option>
                <option value="Intl. Congress">Intl. Congress</option>
                <option value="Emmys">Emmys</option>
                <option value="AMA's">AMA's</option>
                <option value="Plastic Sx">Plastic Sx</option>
                <option value="Walk For Hope">Walk For Hope</option>
                <option value="ISPA">ISPA</option>
                <option value="Cosmoprof">Cosmoprof</option>
                <option value="A4M">A4M</option>
              </select></td>
            </tr>
            </table>
        </td>
        
        <td colspan="3" valign="top" class="ContentBG">
            <!--- SHIPPING TABLE --->
            <table width="98%" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td width="164">&nbsp;</td>
                <td class="textNormBlack"><label><input name="shipTo" type="radio" class="textNormBlack" id="shipTo" value="Billing" checked="checked" /></label> Use Billing Address</td>
            </tr>
            
            <tr>
                <td>&nbsp;</td>
                <td class="textNormBlack"><label><input name="shipTo" type="radio" class="textNormBlack" id="shipTo" value="NewAddress"></label> Create New (enter below)</td>
            </tr>
            
            <tr>
                <td class="textBoldblack"><div align="right">First Name</div></td>
                <td><input name="shipTo_FirstName" type="text" class="textBoldblack" id="shipTo_FirstName" size="25"></td>
            </tr>
            
            <tr>
                <td class="textBoldblack"><div align="right">Last Name</div></td>
                <td><input name="shipTo_LastName" type="text" class="textBoldblack" id="shipTo_LastName" size="25"></td>
            </tr>
            
            <tr>
                <td class="textBoldblack"><div align="right">Address</div></td>
                <td><input name="shipTo_Address1" type="text" class="textBoldblack" id="shipTo_Address1" size="25"></td>
            </tr>
            
            <tr>
                <td class="textNormBlack"><div align="right">Address 2</div></td>
                <td><input name="shipTo_Address2" type="text" class="textBoldblack" id="shipTo_Address2" size="25"></td>
            </tr>
            
            <tr>
                <td class="textBoldblack"><div align="right">City</div></td>
                <td><input name="shipTo_City" type="text" class="textBoldblack" id="shipTo_City" size="25"></td>
            </tr>
            
            <tr>
                <td class="textBoldblack"><div align="right">State</div></td>
                <td><input name="shipTo_State" type="text" class="textBoldblack" id="shipTo_State" size="25"></td>
            </tr>
            
            <tr>
                <td class="textBoldblack"><div align="right">Postal Code</div></td>
                <td><input name="shipTo_PostalCode" type="text" class="textBoldblack" id="shipTo_PostalCode" size="25"></td>
            </tr>
            
            <tr>
                <td class="textBoldblack"><div align="right">Country</div></td>
                <td class="textNormBlack"><select name="shipTo_Country" class="textNormBlack" id="shipTo_Country"><?php echo $sel_SelectCountry3; ?></select></td>
            </tr>
            
            </table>
        </td>
	</tr>
        
    <tr>
    	<td colspan="3" valign="top"><div align="center" class="TextWhite16 style5">LOGIN&nbsp;  INFORMATION</div></td>
    </tr>
      
    <tr>
        <td colspan="3" valign="top" class="ContentBG">
            <!--- NEW ACCOUNT ENTRY FIELD --->
            <table width="98%" border="0" cellspacing="0" cellpadding="4">
            <tr>
                <td valign="top"><div align="right"><span class="textBoldblack">Email</span></div></td>
                <td width="164"><input name="EMailAddress" type="text" class="textBoldblack" id="EMailAddress" size="25"><br><span class="textNormBlack">This will be your returning customer login.</span></td>
            </tr>
            
            <tr>
                <td valign="top"><div align="right"><span class="textBoldblack">Password</span></div></td>
                <td><input name="Password" type="text" class="textBoldblack" id="Password" size="25"><br><span class="textNormBlack">Must contain at least six (6) characters and at least </span><span class="textNormBlack">one (1) number.</span></td>
            </tr>
            
            <tr>
                <td class="textBoldblack"><div align="right">Confirm</div></td>
                <td><input name="PasswordConfirm" type="text" class="textBoldblack" id="PasswordConfirm" size="25"></td>
            </tr>
            </table>
        </td>
    </tr>
      
 	<tr class="TopHeadder">
        <td>&nbsp;</td>
        <td valign="top">&nbsp;</td>
        <td width="85" valign="top">&nbsp;</td>
        <td width="106" valign="top">&nbsp;</td>
        <td colspan="3"><div align="center"><input type="image" name="cmdCheckout" src="/images/btn_checkout.png" width="178" height="26" alt="Check Out"></div></td>
    </tr>
    </table>
    
    </td>
</tr>
</table>
</form>