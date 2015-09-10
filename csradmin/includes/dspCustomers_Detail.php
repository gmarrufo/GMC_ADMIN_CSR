<h1>Customer Details</h1>

<table width="900" cellpadding="2" cellspacing="0" style="margin:10px;">

<?php

while($rowGetCustomer = mssql_fetch_array($qryGetCustomer))
{
    echo '<tr>
        <th width="140" style="text-align:left;">Customer ID:</th>
        <td width="*">' . $rowGetCustomer["NavisionCustomerID"] . '</td>
    </tr>';
	
	if ($rowGetCustomer["CustomerTypeID"] == 1)
	{
		echo '<tr>
        <th style="text-align:left;">Customer Type:</th>
        <td>Consumer</td>
    	</tr>';
	}
	elseif ($rowGetCustomer["CustomerTypeID"] == 2)
	{
		echo '<tr>
        <th style="text-align:left;">Customer Type:</th>
        <td>Reseller</td>
    	</tr>';
	}
	elseif ($rowGetCustomer["CustomerTypeID"] == 3)
	{
		echo '<tr>
        <th style="text-align:left;">Customer Type:</th>
        <td>Distributor</td>
    	</tr>';
	}

	// GMC - 03/08/09 - Customer Type "REP"
	elseif ($rowGetCustomer["CustomerTypeID"] == 4)
	{
		echo '<tr>
        <th style="text-align:left;">Customer Type:</th>
        <td>Rep</td>
    	</tr>';
	}

    // GMC - 03/27/09 - Defensive Code - Warning when Customer Type None of the Above
    else
    {
		echo '<tr>
        <th style="text-align:left;">Customer Type:</th>
        <td><font color="red"><b>WARNING! --INVALID CUSTOMER TYPE. PLEASE CORRECT IN NAV BEFORE PROCEEDING WITH ORDER-- WARNING!</b></font></td>
    	</tr>';
    }

    // GMC - 07/02/15 - Credit Hold for Customers
    if ($rowGetCustomer["CustomerStatus"] == "Credit Hold")
	{
		echo '<tr>
        <th style="text-align:left;">Customer Status:</th>
        <td><font color="red"><b>WARNING! -- CUSTOMER ON CREDIT HOLD. PLEASE CONTACT ACCOUNTING DO NOT PROCEED WITH ORDER-- WARNING!</b></font></td>
    	</tr>';
	}

	echo '<tr>
        <th style="text-align:left;">Company:</th>
        <td>' . $rowGetCustomer["CompanyName"] . '</td>
    </tr>
	
	<tr>
        <th style="text-align:left;">Contact:</th>
        <td>' . $rowGetCustomer["FirstName"] . ' ' . $rowGetCustomer["LastName"] . '</td>
    </tr>
    
    <tr>
        <th style="text-align:left;">Street Address:</th>
        <td>' . $rowGetCustomer["Address1"] . '</td>
    </tr>
    
    <tr>
        <th style="text-align:left;">Unit/Apt/Suite:</th>
        <td>' . $rowGetCustomer["Address2"] . '</td>
    </tr>
    
    <tr>
        <th style="text-align:left;">City:</th>
        <td>' . $rowGetCustomer["City"] . ', ' . $rowGetCustomer["State"] . ' ' . $rowGetCustomer["PostalCode"] . '</td>
    </tr>
    
    <tr>
        <th style="text-align:left;">Country:</th>
        <td>' . $rowGetCustomer["CountryCode"] . '</td>
    </tr>

    <tr>
        <th style="text-align:left;">Telephone:</th>
        <td>' . $rowGetCustomer["Telephone"] . ' x ' . $rowGetCustomer["TelephoneExtension"] . '</td>
    </tr>
    
    <tr>
        <th style="text-align:left;">Email:</th>
        <td>' . $rowGetCustomer["EMailAddress"] . '</td>
    </tr>';
    
    // GMC - 03/27/09 - Defensive Code - Warning when SalesRepId is NULL
    if ($rowGetCustomer["SalesRepID"] == NULL)
	{
		echo '<tr>
        <th style="text-align:left;">&nbsp;</th>
        <td><font color="red"><b>WARNING! --INVALID SALES PERSON CODE. PLEASE CORRECT IN NAV BEFORE YOU PROCEED WITH THE ORDER-- WARNING!</b></font></td>
    	</tr>';
	}
 
    // GMC - 11/23/10 - Show SalesRepID
    else
    {
	     $qryGetSalesRepIDNav = mssql_query("SELECT NavisionUserID FROM tblRevitalash_Users WHERE RecordID = " . $rowGetCustomer["SalesRepID"]);

         while($rowGetSalesRepID = mssql_fetch_array($qryGetSalesRepIDNav))
         {
            $SalesRepIDNav = $rowGetSalesRepID["NavisionUserID"];
         }

        echo '<tr>
        <th style="text-align:left;">Sales Rep ID</th>
        <td>' . $SalesRepIDNav . '</td>
    	</tr>';
    }
}
?>
</table>


<table width="900" cellpadding="2" cellspacing="0" style="margin:10px;">

<tr>
    <th style="text-align:left;">Shipping Information</th>
</tr>

<?php
//DISPLAY SHIPPING ADDRESSES
$strShipping = '';

while($rowGetShipping = mssql_fetch_array($qryGetShipping))
{
    $strShipping .= '<tr><td>';
    $strShipping .= '<table width="100%" cellspacing="2" cellpadding="0" border="0">';
    $strShipping .= '<tr style="font-size:11px;">';
    $strShipping .= '<td width="*">' . $rowGetShipping["Attn"] . '</td>';
    $strShipping .= '<td width="175">' . $rowGetShipping["Address1"] . '</td>';
    $strShipping .= '<td width="175">' . $rowGetShipping["City"] . ', ' . $rowGetShipping["State"] . ' ' . $rowGetShipping["PostalCode"] . ' ' . $rowGetShipping["CountryCode"];
    $strShipping .= '</td>';
    $strShipping .= '</tr>';
    $strShipping .= '</table>';
    $strShipping .= '</td></tr>';		
}

echo $strShipping;
?>

</table>

<h1>Order History</h1>

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="100">Order</td>
        <td width="*">Customer</td>
        <td width="150">Date</td>
        <td width="125">Status</td>
        <td width="150">Shipping</td>
        <td width="80">Total</td>
    </tr>
    
    <?php
	if (isset($tblOrderList))
		echo $tblOrderList;
	else
		echo '<tr class="tdgray"><td colspan="6"><span style="font-weight:bold; font-style:italic;">There are no orders for this customer.</span></td></tr>';
	?>
	
    </table>

</div>

<p>&nbsp;</p>
