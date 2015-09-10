<h1>CRM LEAD Customers Summary</h1>

<form method="post" action="/csradmin/customers.php?Action=SearchCRMList">
<table width="100%" cellpadding="0" cellspacing="10">

<tr>
	<th>Customer Number:</th>
    <td><input type="text" size="15" name="CustomerNumber" /></td>
</tr>

<tr>
	<th>Company:</th>
    <td><input type="text" size="15" name="CompanyName" /></td>
</tr>

<tr>
	<th width="125">Last Contact Date:</th>
    <td width="*"><input type="text" size="15" name="LastContactDate" />&nbsp;&nbsp;&nbsp;&nbsp; <font color="red">(mm/dd/yyyy)</font></td>
</tr>

<tr>
	<th>Disposition:</th>
    <td>
    <select name="Disposition" size="1">
    <?php

     // CONNECT TO SQL SERVER DATABASE
	$connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomers);

    $qryGetDispositions = mssql_query("SELECT Disposition FROM tblCRM_Dispositions ORDER BY DisplayOrder ASC");
    while($row = mssql_fetch_array($qryGetDispositions))
    {
		echo '<option value="' . $row["Disposition"] . '">' . $row["Disposition"] . '</option>';
    }

     // CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    ?>
    </select>
    </td>
</tr>

<tr>
	<th width="125">Follow Up Date:</th>
    <td width="*"><input type="text" size="15" name="FollowUpDate" />&nbsp;&nbsp;&nbsp;&nbsp; <font color="red">(mm/dd/yyyy)</font></td>
</tr>

<!-- GMC - 06/27/13 - Add CustomerStatus to CRM-Lead Process -->
<tr>
	<th width="125">Customer Status:</th>
    <td width="*">
    <select name="CustomerStatus" size="1">
		<option value="Select">Select</option>
		<option value="Approved">Approved</option>
		<option value="Pending">Pending</option>
		<option value="Inactive">Inactive</option>
		<option value="Denied">Denied</option>
    </select>
    </td>
</tr>

<!-- GMC - 08/12/13 - Add State Code to CRM-Lead Process -->
<tr>
	<th width="125">State Code:</th>
    <td width="*">
    <select name=State size=1><option value=Select>Select</option><option value=AL>Alabama</option>
            <option value=AK>Alaska</option><option value=AZ>Arizona</option><option value=AR>Arkansas</option>
            <option value=CA>California</option><option value=CO>Colorado</option>
            <option value=CT>Connecticut</option><option value=DE>Delaware</option>
            <option value=DC>District Of Columbia</option><option value=FL>Florida</option>
            <option value=GA>Georgia</option><option value=HI>Hawaii</option>
            <option value=ID>Idaho</option><option value=IL>Illinois</option>
            <option value=IN>Indiana</option><option value=IA>Iowa</option>
            <option value=KS>Kansas</option><option value=KY>Kentucky</option>
            <option value=LA>Louisiana</option><option value=ME>Maine</option>
            <option value=MD>Maryland</option><option value=MA>Massachusetts</option>
            <option value=MI>Michigan</option><option value=MN>Minnesota</option>
            <option value=MS>Mississippi</option><option value=MO>Missouri</option>
            <option value=MT>Montana</option><option value=NE>Nebraska</option>
            <option value=NV>Nevada</option><option value=NH>New Hampshire</option>
            <option value=NJ>New Jersey</option><option value=NM>New Mexico</option>
            <option value=NY>New York</option><option value=NC>North Carolina</option>
            <option value=ND>North Dakota</option><option value=OH>Ohio</option>
            <option value=OK>Oklahoma</option><option value=OR>Oregon</option>
            <option value=PA>Pennsylvania</option><option value=PR>Puerto Rico</option>
            <option value=RI>Rhode Island</option><option value=SC>South Carolina</option>
            <option value=SD>South Dakota</option><option value=TN>Tennessee</option>
            <option value=TX>Texas</option><option value=UT>Utah</option>
            <option value=VT>Vermont</option><option value=VA>Virginia</option>
            <option value=WA>Washington</option><option value=WV>West Virginia</option>
            <option value=WI>Wisconsin</option><option value=WY>Wyoming</option>
            </select>
    </td>
</tr>

<!-- GMC - 06/30/14 - Add City and Phone to CRM-Lead Process -->
<tr>
	<th>City:</th>
    <td><input type="text" size="35" name="City" /></td>
</tr>

<tr>
	<th>Phone:</th>
    <td><input type="text" size="15" name="Phone" />&nbsp;&nbsp;&nbsp;&nbsp; <font color="red">(###-###-####)</font></td>
</tr>

<tr>
	<th>&nbsp;</th>
    <td><input type="submit" name="cmdSearch" value="Search" class="formSubmit" /></td>
</tr>

</table>
</form>

<p>&nbsp;</p>

<p>Please click on a Customer Name to go to the Customer Detail or in CRM Detail to see CRM activities for the Customer.</p>

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="*">CRM Detail</td>
        <td width="*">Customer ID</td>
        <td width="150">Name</td>
        <td width="*">State</td>
        <!-- GMC - 06/30/14 - Add City and Phone to CRM-Lead Process -->
        <td width="150">City</td>
        <td width="150">Phone</td>
        <td width="*">Last Contact Date</td>
        <td width="*">Disposition</td>
        <td width="*">Follow Up Date</td>

        <!-- GMC - 06/27/13 - Add CustomerStatus to CRM-Lead Process -->
        <td width="*">Customer Status</td>

    </tr>
    
    <?php echo $tblCustomerList; ?>
   
    </table>

</div>

<p>&nbsp;</p>
