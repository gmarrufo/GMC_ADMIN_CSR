<h1>Orders Summary (by SalesRepID)</h1>

<?php

$strOutput = "";
$strOutput .= '<p>Below is a summary of orders. To view a specific set of orders per SalesRepID, please click on the link by the Name below.</p>';
$strOutput .= '<p>Or by querying by Date Range:</p>';
$strOutput .= '<form action="/csradmin/orders.php" method="get">';
$strOutput .= '<input type="hidden" name="Action" value="SummarySalesRepIdSearch">';
$strOutput .= '<div align="left">';
$strOutput .= '<table border="0">';
$strOutput .= '<tr>';
$strOutput .= '<td>';
$strOutput .= '&nbsp;From Date:';
$strOutput .= '</td>';
$strOutput .= '<td>';
$strOutput .= '<input type="text" name="SummaryOrderDateFrom" size="6" />';
$strOutput .= '</td>';
$strOutput .= '<td>';
$strOutput .= 'To Date:';
$strOutput .= '</td>';
$strOutput .= '<td>';
$strOutput .= '<input type="text" name="SummaryOrderDateTo" size="6" />';
$strOutput .= '</td>';
$strOutput .= '<td>';
$strOutput .= '<input type="submit" name="cmdSearchOrder" value="Search" class="formSubmit_small" />';
$strOutput .= '</td>';
$strOutput .= '</tr>';
$strOutput .= '</table>';
$strOutput .= '</div>';
$strOutput .= '</form>';
echo $strOutput;

?>

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="100">SalesRepID</td>
        <td width="*">Number of Orders</td>
        <td width="80">Total</td>
    </tr>
    
    <?php echo $tblSummaryList; ?>
   
    </table>

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">

    <tr class="tdwhite" style="font-weight:bold;">
        <td width="*">Total Number of Orders</td>
        <td width="80">Total Value of Orders</td>
    </tr>

    <?php echo $tblSummaryTotal; ?>

    </table>

</div>

<p>&nbsp;</p>
