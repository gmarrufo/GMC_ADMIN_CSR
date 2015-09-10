<h1>CRM LEAD Customer Detail</h1>

<table width="100%" cellpadding="0" cellspacing="10">
<tr>
	<th width="125">Customer Number:</th>
    <td width="*"><?php echo $_SESSION['CRM_NavCustomerID']; ?></td>
</tr>
<tr>
	<th width="125">Company Name:</th>
    <td width="*"><?php echo $_SESSION['CRM_CompanyName']; ?></td>
</tr>
</table>

<p>&nbsp;</p>

<h2>&nbsp;&nbsp;&nbsp;&nbsp;New CRM entry <font color="red">(All Fields Required)</font></h2>

<form method="post" action="/csradmin/customers.php?Action=CRMEntry">

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">

    <tr class="tdwhite" style="font-weight:bold;">
        <td width="125">Disposition</td>
        <td width="225">Follow Up Date <font color="red">(mm/dd/yyyy)</font></td>
        <td width="425">Comments <font color="red">(up to 250 characters)</font></td>
    </tr>

    <tr class="tdwhite">
        <td>
        <select name="EntryDisposition" size="1">
        <?php

             // CONNECT TO SQL SERVER DATABASE
             $connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
             $selected = mssql_select_db($dbName, $connCustomers);

             $qryGetDispositions = mssql_query("SELECT Disposition FROM tblCRM_Dispositions ORDER BY DisplayOrder ASC");
             while($row = mssql_fetch_array($qryGetDispositions))
             {
                 if($row["Disposition"] != 'Start' && $row["Disposition"] != 'Select'){
                     echo '<option value="' . $row["Disposition"] . '">' . $row["Disposition"] . '</option>';
                 }
             }

             // CLOSE DATABASE CONNECTION
	         mssql_close($connCustomer);

        ?>
        </select>
        </td>
        <td><input type="text" size="25" name="EntryFollowUpDate" /></td>
        <td>
        <!-- GMC - 06/23/14 - Fix text size -->
        <!--<input type="text" size="113" name="EntryComments" />-->
        <textarea maxlength="250" rows="3" cols="50" name="EntryComments"></textarea>
        </td>
    </tr>

    </table>

    <table width="100%" cellpadding="0">
    <tr>
        <td width="825"></td>
        <td width="*"><input type="submit" name="cmdCRMEntry" value="Submit" class="formSubmit" /></td>
    </tr>
    </table>

</div>

</form>

<p>&nbsp;</p>

<h2>&nbsp;&nbsp;&nbsp;&nbsp;CRM Log History</h2>

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="*">Last Contact Date</td>
        <td width="*">Disposition</td>
        <td width="*">Follow Up Date</td>
        <td width="*">Comments</td>
    </tr>
    
    <?php echo $tblCustomerDetail; ?>
   
    </table>

    <!-- GMC - 06/20/13 - Back buttons for some pages -->
    <table width="100%" cellpadding="0">
    <tr>
        <td width="625"></td>

        <!-- GMC - 08/12/13 - Add State Code to CRM-Lead Process -->
        <!--<td width="*"><a href="customers.php?Action=CRMSummary"><font color="red">Return to CRM - LEAD Management</font></a></td>-->
        <td width="*"><a href="customers.php?Action=SearchCRMList"><font color="red">Return to CRM - LEAD Management</font></a></td>

    </tr>
    </table>

</div>

<p>&nbsp;</p>
