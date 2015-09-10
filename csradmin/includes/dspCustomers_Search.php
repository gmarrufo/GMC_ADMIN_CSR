<h1>Search Results</h1>

<form method="post" action="/csradmin/customers.php?Action=Search">
<table width="100%" cellpadding="0" cellspacing="10">

<!-- GMC - 12/17/08 - Add CustomerId Search-->
<tr>
	<th width="125">CustomerID:</th>
    <td width="*"><input type="text" size="15" name="CustomerID" /></td>
</tr>

<tr>
	<th>Company:</th>
    <td><input type="text" size="15" name="CompanyName" /></td>
</tr>

<!-- GMC - 12/18/08 - Add Telephone and join FirstName + LastName = Contact Search
<tr>
	<th width="125">First Name:</th>
    <td width="*"><input type="text" size="15" name="FirstName" /></td>
</tr>

<tr>
	<th>Last Name:</th>
    <td><input type="text" size="15" name="LastName" /></td>
</tr>
-->

<!-- GMC - 12/18/08 - Add Telephone and join FirstName + LastName = Contact Search-->
<tr>
	<th width="125">Contact Name:</th>
    <td width="*"><input type="text" size="15" name="ContactName" /></td>
</tr>

<!-- GMC - 12/03/08 - Domestic vs International 3rd Phase -->
<tr>
	<th>Address:</th>
    <td><input type="text" size="30" name="Address" /></td>
</tr>

<tr>
	<th>Telephone:</th>
    <td><input type="text" size="15" name="ContactNumber" /></td>
</tr>

<tr>
	<th>E-Mail Address:</th>
    <td><input type="text" size="15" name="EMailAddress" /></td>
</tr>

<tr>
	<th>&nbsp;</th>
    <td><input type="submit" name="cmdSearch" value="Search" class="formSubmit" /></td>
</tr>

</table>
</form>

<p>&nbsp;</p>

            
<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">

    <!-- GMC - 07/22/09 - Change the order of the columns by JS -->
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="100">Customer ID</td>
        <td width="250">Company Name</td>
        <td width="*">Contact Name</td>
        <td width="250">Address</td>
        <td width="125">Telephone</td>
    </tr>
    
    <?php
    if ($tblCustomerList == '')
	{
		echo '<tr><td colspan="4">There are no customers matching your criteria. Please try again.</td></tr>';
	}
	else
	{
		echo $tblCustomerList; 
    }
   	?>
    </table>

</div>


<p>&nbsp;</p>
