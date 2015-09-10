<h1>Recent Orders</h1>

<?php

// GMC - 06/08/09 - Search Criteria for Orders
// CONNECT TO SQL SERVER DATABASE
$connOrders = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connOrders);

$_SESSION['Seller'] = '';
$strOutput = "";

if ($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 39) // Jesse Stancarone Or Amber Tobler
{
    // GMC - 06/08/09 - Search Criteria for Orders -->
    $strOutput .= '<p>Below is a list of the most recent orders. To view a "specific" order not on the list, please enter the specific order number and click the "View" button</p>';

    // GMC - 06/08/09 - Search Criteria for Orders -->
    $strOutput .= '<form action="/csradmin/search_report.php" method="get">';
    $strOutput .= '<div align="left">';
    $strOutput .= '<table border="0">';
    $strOutput .= '<tr>';
    $strOutput .= '<td>';
    $strOutput .= '&nbsp;From Date:';
    $strOutput .= '</td>';
    $strOutput .= '<td>';
    $strOutput .= '<input type="text" name="OrderDateFrom" size="6" />';
    $strOutput .= '</td>';
    $strOutput .= '<td>';
    $strOutput .= 'To Date:';
    $strOutput .= '</td>';
    $strOutput .= '<td>';
    $strOutput .= '<input type="text" name="OrderDateTo" size="6" />';
    $strOutput .= '</td>';
    
    // GMC - 07/15/09 - Add Country to Search
    $strOutput .= '<td>';
    $strOutput .= '(US &nbsp;<input type="radio" name="radSelect" value="USSelect" checked/>';
    $strOutput .= '<font color="red"><b> OR </b></font>';
    $strOutput .= '&nbsp;INTL &nbsp;<input type="radio" name="radSelect" value="INTLSelect" />)';
    $strOutput .= '</td>';

    $strOutput .= '<td>';
    $strOutput .= '(Entered by:';
    $strOutput .= '</td>';
    $strOutput .= '<td>';
    $strOutput .= '<select name="OrderBy" size="1">';
    $strOutput .= '<option value="">Select option below</option>';
    $strOutput .= '<option value="All">Select All</option>';

    $strSQL = "SELECT LEFT(tblRevitalash_Users.FirstName, 1) + '. ' + tblRevitalash_Users.LastName AS Seller FROM tblRevitalash_Users WHERE tblRevitalash_Users.IsActive = 1 ORDER BY tblRevitalash_Users.LastName ASC";
    $result = mssql_query($strSQL);

    while($rowSellers = mssql_fetch_array($result))
    {
         if (isset($_SESSION['Seller']) && $_SESSION['Seller'] == $rowSellers["Seller"])
         {
	          $strOutput .= '<option selected="selected" value="' . $rowSellers["Seller"] . '">' . $rowSellers["Seller"] . '</option>';
         }
         else
	     {
    	      $strOutput .= '<option value="' . $rowSellers["Seller"] . '">' . $rowSellers["Seller"] . '</option>';
         }
    }

    $strOutput .= '</select>';
    
    // GMC - 06/15/09 - Add CCNumber to Search
    $strOutput .= '&nbsp;<font color="red"><b> OR </b></font>Last 4 Digits of CC #:';
    $strOutput .= '</td>';
    $strOutput .= '<td>';
    $strOutput .= '<input type="text" name="CCNumber" size="6" />)';
    $strOutput .= '</td>';
    $strOutput .= '<td>';
    $strOutput .= '<input type="submit" name="cmdSearchOrder" value="Search" class="formSubmit_small" />';
    $strOutput .= '</td>';
    $strOutput .= '</tr>';
    $strOutput .= '</table>';
    $strOutput .= '</div>';
    $strOutput .= '<div align="left">';
    $strOutput .= '<table border="0">';
    $strOutput .= '<tr>';
    $strOutput .= '<td>';
    $strOutput .= '&nbsp;Enter a filename and click on "Report" to create a report of your current query below';
    $strOutput .= '</td>';
    $strOutput .= '<td>';
    $strOutput .= '<input type="text" name="ReportFilename" size="24" />';
    $strOutput .= '</td>';
    $strOutput .= '<td>';
    $strOutput .= '<input type="submit" name="cmdCreateReport" value="Report" class="formSubmit_small" />';
    $strOutput .= '</td>';
    $strOutput .= '</tr>';
    $strOutput .= '</table>';
    $strOutput .= '</div>';
    $strOutput .= '</form>';
    $strOutput .= '<form action="/csradmin/orders.php" method="get">';
    $strOutput .= '<input type="hidden" name="Action" value="Detail">';
    $strOutput .= '<div class="bluediv_header"><input type="text" name="OrderID" size="6" /> <input type="submit" name="cmdViewOrder" value="View" class="formSubmit_small" /></div>';
    $strOutput .= '</form>';

    echo $strOutput;
}
else
{
    $strOutput .= '<p>Below is a list of recent orders. To view a specific order, please use the view button below.</p>';
    $strOutput .= '<form action="/csradmin/orders.php" method="get">';
    $strOutput .= '<input type="hidden" name="Action" value="Detail">';
    $strOutput .= '<div class="bluediv_header"><input type="text" name="OrderID" size="6" /> <input type="submit" name="cmdViewOrder" value="View" class="formSubmit_small" /></div>';
    $strOutput .= '</form>';

    echo $strOutput;
}

?>

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
        
        <!-- GMC - 04/10/12 - SubTotal OrderRecent -->
        <td width="80">SubTotal</td>

        <td width="80">Total</td>

        <!-- GMC - 01/26/10 - Email in Order List and Order Excel -->
        <td width="80">Email</td>

    </tr>

    <?php echo $tblOrderList; ?>
   
    </table>

</div>

<p>&nbsp;</p>
