<h1>Recent Tradeshow Orders</h1>
            
<p>Below is a list of recent orders. To view a specific order, please use the view button below.</p>
            
<form action="/csradmin/orders.php" method="get">
<input type="hidden" name="Action" value="Detail">
<div class="bluediv_header"><input type="text" name="OrderID" size="6" /> <input type="submit" name="cmdViewOrder" value="View" class="formSubmit_small" /></div>
</form>
            
<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="75">Order</td>
        <td width="100">Entered By</td>
        <td width="*">Customer</td>
        <td width="*">Type</td>
        <td width="*">State</td>
        <td width="*">Country</td>
        <td width="*">CC #</td>
        <td width="150">Date</td>
        <td width="125">Status</td>
        <td width="125">Ship From</td>
        <td width="150">Shipping</td>
        <td width="80">Total</td>
    </tr>
    
    <?php echo $tblOrderList; ?>
   
    </table>

</div>


<p>&nbsp;</p>
