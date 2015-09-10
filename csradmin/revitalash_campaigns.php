<h1>Revitalash Campaigns</h1>

<form method="post" action="">

<!-- GMC - 12/18/08 - Add Telephone and join FirstName + LastName = Contact Search
<p>Below is a list of the most recent customers. Please click on a customer to place a new order or edit the customer information.</p>
-->

<p>
Below is a list of current Campaigns.<br>
If you want to "ADD" a Campaign to the Revitalash Campaigns Table, click <a href="revitalash_campaigns_add.php">here</a>.<br>
If you want to "EDIT" or "DELETE" a User from the Revitalash Campaigns Table (One Record at a time), select the record with the proper link and click on it.
</p>

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">

    <tr class="tdwhite" style="font-weight:bold;">
        <td width="2">Edit</td>
        <td width="2">Delete</td>
        <td width="2">Record ID</td>
		<td width="2">NavisionCode</td>
		<td width="2">CampaignName</td>
		<td width="2">StartDate</td>
		<td width="2">EndDate</td>
		<td width="2">IsActive</td>
		<td width="2">Location</td>

        <!-- GMC - 02/03/14 - Add Discount to tblCampaigns -->
		<td width="2">Discount</td>

    </tr>

    <?php echo $tblCampaigns; ?>

    </table>

</div>

</form>
