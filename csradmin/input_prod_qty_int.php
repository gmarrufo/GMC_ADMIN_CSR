<?php
require_once("../modules/db.php");
include("../includes/selCountries.php");

?>

<h1>FedEx International Shipping Charges Process</h1>

<form action="/csradmin/calculate_boxes.php" method="post">

<table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">
<tr>
    <th width="*" style="text-align:left">Enter Address Information</th>
</tr>
</table>

<table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">
<tr>
    <th width="150" style="text-align:left">Street Address</th>
    <th width="150" style="text-align:left">City</th>
    <th width="150" style="text-align:left">Province or Region</th>
    <th width="150" style="text-align:left">Postal Code</th>
    <th width="150" style="text-align:left">Country</th>
</tr>
<tr>
    <td><input type="text" name="Address" size="20" value="" /></td>
    <td><input type="text" name="City" size="20" value="" /></td>
    <td><input type="text" name="State" size="20" value="" /></td>
    <td><input type="text" name="Zip" size="20" value="" /></td>


    <!-- GMC - 05/04/11 -- Add Drop Down for Countries
    <td><input type="text" name="Country" size="20" value="" /></td>
    -->

    <?php echo '<td><select name="Country" size="1">' . $selectCountries . '</select></td>'; ?>

</tr>
</table>

<table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">

<tr>
    <th width="*" style="text-align:left">Products</th>
    <th width="50" style="text-align:left">Qty</th>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID1" size="1"><option value="0">-- Select Below --</option>';
	  // while($row1 = mssql_fetch_array($cboProducts1))
	  while($row1 = sqlsrv_fetch_array($cboProducts1))
	  {
		 echo '<option value="'. $row1["RecordID"] . '"';
		 echo '>';
		 echo $row1["ProductName"] . ' ~ ' . $row1["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty1" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID2" size="1"><option value="0">-- Select Below --</option>';
	  // while($row2 = mssql_fetch_array($cboProducts2))
	  while($row2 = sqlsrv_fetch_array($cboProducts2))
	  {
		 echo '<option value="'. $row2["RecordID"] . '"';
		 echo '>';
		 echo $row2["ProductName"] . ' ~ ' . $row2["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty2" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID3" size="1"><option value="0">-- Select Below --</option>';
	  // while($row3 = mssql_fetch_array($cboProducts3))
	  while($row3 = sqlsrv_fetch_array($cboProducts3))
	  {
		 echo '<option value="'. $row3["RecordID"] . '"';
		 echo '>';
		 echo $row3["ProductName"] . ' ~ ' . $row3["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty3" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID4" size="1"><option value="0">-- Select Below --</option>';
	  // while($row4 = mssql_fetch_array($cboProducts4))
	  while($row4 = sqlsrv_fetch_array($cboProducts4))
	  {
		 echo '<option value="'. $row4["RecordID"] . '"';
		 echo '>';
		 echo $row4["ProductName"] . ' ~ ' . $row4["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty4" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID5" size="1"><option value="0">-- Select Below --</option>';
	  // while($row5 = mssql_fetch_array($cboProducts5))
	  while($row5 = sqlsrv_fetch_array($cboProducts5))
	  {
		 echo '<option value="'. $row5["RecordID"] . '"';
		 echo '>';
		 echo $row5["ProductName"] . ' ~ ' . $row5["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty5" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID6" size="1"><option value="0">-- Select Below --</option>';
	  // while($row6 = mssql_fetch_array($cboProducts6))
	  while($row6 = sqlsrv_fetch_array($cboProducts6))
	  {
		 echo '<option value="'. $row6["RecordID"] . '"';
		 echo '>';
		 echo $row6["ProductName"] . ' ~ ' . $row6["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty6" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID7" size="1"><option value="0">-- Select Below --</option>';
	  // while($row7 = mssql_fetch_array($cboProducts7))
	  while($row7 = sqlsrv_fetch_array($cboProducts7))
	  {
		 echo '<option value="'. $row7["RecordID"] . '"';
		 echo '>';
		 echo $row7["ProductName"] . ' ~ ' . $row7["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty7" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID8" size="1"><option value="0">-- Select Below --</option>';
	  // while($row8 = mssql_fetch_array($cboProducts8))
	  while($row8 = sqlsrv_fetch_array($cboProducts8))
	  {
		 echo '<option value="'. $row8["RecordID"] . '"';
		 echo '>';
		 echo $row8["ProductName"] . ' ~ ' . $row8["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty8" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID9" size="1"><option value="0">-- Select Below --</option>';
	  // while($row9 = mssql_fetch_array($cboProducts9))
	  while($row9 = sqlsrv_fetch_array($cboProducts9))
	  {
		 echo '<option value="'. $row9["RecordID"] . '"';
		 echo '>';
		 echo $row9["ProductName"] . ' ~ ' . $row9["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty9" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID10" size="1"><option value="0">-- Select Below --</option>';
	  // while($row10 = mssql_fetch_array($cboProducts10))
	  while($row10 = sqlsrv_fetch_array($cboProducts10))
	  {
		 echo '<option value="'. $row10["RecordID"] . '"';
		 echo '>';
		 echo $row10["ProductName"] . ' ~ ' . $row10["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty10" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID11" size="1"><option value="0">-- Select Below --</option>';
	  // while($row11 = mssql_fetch_array($cboProducts11))
	  while($row11 = sqlsrv_fetch_array($cboProducts11))
	  {
		 echo '<option value="'. $row11["RecordID"] . '"';
		 echo '>';
		 echo $row11["ProductName"] . ' ~ ' . $row11["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty11" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID12" size="1"><option value="0">-- Select Below --</option>';
	  // while($row12 = mssql_fetch_array($cboProducts12))
	  while($row12 = sqlsrv_fetch_array($cboProducts12))
	  {
		 echo '<option value="'. $row12["RecordID"] . '"';
		 echo '>';
		 echo $row12["ProductName"] . ' ~ ' . $row12["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty12" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID13" size="1"><option value="0">-- Select Below --</option>';
	  // while($row13 = mssql_fetch_array($cboProducts13))
	  while($row13 = sqlsrv_fetch_array($cboProducts13))
	  {
		 echo '<option value="'. $row13["RecordID"] . '"';
		 echo '>';
		 echo $row13["ProductName"] . ' ~ ' . $row13["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty13" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID14" size="1"><option value="0">-- Select Below --</option>';
	  // while($row14 = mssql_fetch_array($cboProducts14))
	  while($row14 = sqlsrv_fetch_array($cboProducts14))
	  {
		 echo '<option value="'. $row14["RecordID"] . '"';
		 echo '>';
		 echo $row14["ProductName"] . ' ~ ' . $row14["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty14" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID15" size="1"><option value="0">-- Select Below --</option>';
	  // while($row15 = mssql_fetch_array($cboProducts15))
	  while($row15 = sqlsrv_fetch_array($cboProducts15))
	  {
		 echo '<option value="'. $row15["RecordID"] . '"';
		 echo '>';
		 echo $row15["ProductName"] . ' ~ ' . $row15["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty15" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID16" size="1"><option value="0">-- Select Below --</option>';
	  // while($row16 = mssql_fetch_array($cboProducts16))
	  while($row16 = sqlsrv_fetch_array($cboProducts16))
	  {
		 echo '<option value="'. $row16["RecordID"] . '"';
		 echo '>';
		 echo $row16["ProductName"] . ' ~ ' . $row16["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty16" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID17" size="1"><option value="0">-- Select Below --</option>';
	  // while($row17 = mssql_fetch_array($cboProducts17))
	  while($row17 = sqlsrv_fetch_array($cboProducts17))
	  {
		 echo '<option value="'. $row17["RecordID"] . '"';
		 echo '>';
		 echo $row17["ProductName"] . ' ~ ' . $row17["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty17" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID18" size="1"><option value="0">-- Select Below --</option>';
	  // while($row18 = mssql_fetch_array($cboProducts18))
	  while($row18 = sqlsrv_fetch_array($cboProducts18))
	  {
		 echo '<option value="'. $row18["RecordID"] . '"';
		 echo '>';
		 echo $row18["ProductName"] . ' ~ ' . $row18["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty18" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID19" size="1"><option value="0">-- Select Below --</option>';
	  // while($row19 = mssql_fetch_array($cboProducts19))
	  while($row19 = sqlsrv_fetch_array($cboProducts19))
	  {
		 echo '<option value="'. $row19["RecordID"] . '"';
		 echo '>';
		 echo $row19["ProductName"] . ' ~ ' . $row19["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty19" size="2" value="" /></td>
</tr>

<tr>
    <?php
      echo '<td><select name="ItemID20" size="1"><option value="0">-- Select Below --</option>';
	  // while($row20 = mssql_fetch_array($cboProducts20))
	  while($row20 = sqlsrv_fetch_array($cboProducts20))
	  {
		 echo '<option value="'. $row20["RecordID"] . '"';
		 echo '>';
		 echo $row20["ProductName"] . ' ~ ' . $row20["PartNumber"];
		 echo '</option>';
	  }
      echo '</select></td>';
    ?>
    <td><input type="text" name="ItemQty20" size="2" value="" /></td>
</tr>

</table>


<table width="100%" cellpadding="5" cellspacing="0">

<tr><td colspan="3"><input type="submit" name="cmdContinue" value="Submit" class="formSubmit" /></td></tr>

</table>

</form>