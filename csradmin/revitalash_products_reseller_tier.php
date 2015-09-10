<h1>Revitalash Products Reseller Tier</h1>

<form method="post" action="">

<!-- GMC - 12/18/08 - Add Telephone and join FirstName + LastName = Contact Search
<p>Below is a list of the most recent customers. Please click on a customer to place a new order or edit the customer information.</p>
-->

<p>Below is a list of current Products Reseller Tier Discounts.<br>
If you want to "ADD" a Record to the Products Reseller Tier Table, click <a href="revitalash_products_reseller_add.php">here</a>.<br>
If you want to "EDIT" or "DELETE" a Record from the Products Reseller Tier Table (One Record at a time), select the record with the proper link and click on it.
</p>

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">

    <tr class="tdwhite" style="font-weight:bold;">
        <td width="2">Edit</td>
        <td width="2">Delete</td>
        <td width="2">Record ID</td>
        <td width="2">Product ID</td>
        <td width="2">Qty Required</td>
        <td width="2">Discount Price</td>
    </tr>

    <?php echo $tblProductsResellerTier; ?>

    </table>

</div>

</form>

<p>&nbsp;</p>
