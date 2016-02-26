<form name="cartform" action="https://secure.revitalash.com/retail/cart.php" method="post">
<table width="600" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#6666CC">
<tr>
    <td>
    <table width="600" border="0" align="center" cellpadding="4" cellspacing="0">
    
    <tr class="ContentBG">
        <td colspan="4" valign="top"><div align="right"><img src="/images/toplogo.png" width="247" height="73"></div></td>
    </tr>
    
    
    <tr class="TopHeadder">
        <td width="*">ITEM DESCRIPTION</td>
        <td width="100">QTY</td>
        <td width="100">EACH</td>
        <td width="80">TOTAL</td>
    </tr>
      
    <?php
    // DISPLAY CART CONTENTS
	echo $cart_table;

    // DISPLAY DISCOUNT IF ANY
	if (isset($_SESSION['OrderSubtotalDiscount']) && $_SESSION['OrderSubtotalDiscount'] > 0)
    {
		echo '<tr class="ContentAcc">';
		echo '<td class="textNorm">' . $SESSION['OrderSubtotalDiscountDisplay'] . '</div></td>';
		echo '<td class="textNorm">&nbsp;</td>';
		echo '<td><div align="right" class="textNorm">-$' . number_format($_SESSION['OrderSubtotalDiscount'], 2, '.', '') . '</div></td>';
		echo '</tr>';
	  
	}
	?>
    
    <tr class="ContentBG"><td colspan="4">&nbsp;</td></tr>
    
    <tr class="ContentBG">
        <td rowspan="5" class="textNormBlack" valign="top">
        
            <table width="100%" cellspacing="5" cellpadding="0">
            
            <tr>
                <td width="48%" style="font-weight:bold;">Customer Information</td>
                <td width="*">&nbsp;</td>
                <td width="48%" style="font-weight:bold;">Shipping Information</td>
            </tr>
            
            <tr>
                <td><?php echo $CustomerName; ?></td>
                <td>&nbsp;</td>
                <td><?php echo $ShipToName; ?></td>
            </tr>
            
            <tr>
                <td><?php echo $CustomerAddress; ?></td>
                <td>&nbsp;</td>
                <td><?php echo $ShipToAddress; ?></td>
            </tr>
            
            <tr>
                <td><?php echo $CustomerCityStateZIP; ?></td>
                <td>&nbsp;</td>
                <td><?php echo $ShipToCityStateZIP; ?></td>
            </tr>
            
            <tr>
                <td><?php echo $CustomerCountryCode; ?></td>
                <td>&nbsp;</td>
                <td><?php echo $ShipToCountryCode; ?></td>
            </tr>
            
            <tr>
                <td><?php echo $CustomerTelephone; ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            
            </table>
        
        </td>
        <td colspan="2" class="textNormBlack" align="right">Subtotal:</td>
        <td class="textNormBlack">$<?php echo number_format($_SESSION['OrderSubtotal'], 2, '.', ''); ?></td>
    </tr>
    
    <tr class="ContentBG">
        <td colspan="2" class="textNormBlack" align="right">Ship Via <?php echo $ShipMethod; ?>:</td>
        <td class="textNormBlack">$<?php echo number_format($_SESSION['OrderShipping'], 2, '.', ''); ?></td>
    </tr>
    
    <tr class="ContentBG">
        <td colspan="2" class="textNormBlack" align="right">Sales Tax:</td>
        <td class="textNormBlack">$<?php echo number_format($_SESSION['OrderTax'], 2, '.', ''); ?></td>
    </tr>
    
    <tr class="ContentBG">
        <td colspan="2" class="textNormBlack" align="right">Order Total:</td>
        <td class="textNormBlack">$<?php echo number_format($_SESSION['OrderTotal'], 2, '.', ''); ?></td>
    </tr>
    
    <tr class="ContentBG"><td colspan="3">&nbsp;</td></tr>
      
 	<tr class="ContentBG">
        <td colspan="2">&nbsp;</td>
        <td colspan="2" align="right"><img src="/images/btn_complete.png" width="178" height="26" alt="Complete Transaction"></td>
    </tr>
    
    </table>
    
    </td>
</tr>
</table>
</form>