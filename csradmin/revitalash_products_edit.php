<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

/*
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);
*/

require_once("../modules/session.php");
require_once("../modules/db.php");

if (isset($_GET['id']))
{
    $Id = $_GET['id'];
    $_SESSION['edit_id'] = $Id;
    
	$strSQL = "SELECT * FROM tblProducts WHERE RecordID = " . $Id . "";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetProducts))
	{
		$RecordID = $row["RecordID"];
        $ProductName = $row["ProductName"];
		$PartNumber = $row["PartNumber"];
		$ListingDescription = $row["ListingDescription"];
		$CartDescription = $row["CartDescription"];
        $LongDescription = $row["LongDescription"];
		$RetailPrice = $row["RetailPrice"];
		$ResellerPrice = $row["ResellerPrice"];
		$DistributorPrice = $row["DistributorPrice"];
		$Size = $row["Size"];
		$Weight = $row["Weight"];

        // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
        /*
		$InternationalSurcharge = $row["InternationalSurcharge"];
		$ResellerFreeTrigger = $row["ResellerFreeTrigger"];
		$DistributorFreeTrigger = $row["DistributorFreeTrigger"];
		$CartThumbnail = $row["CartThumbnail"];
		$GalleryThumbnail = $row["GalleryThumbnail"];
		$ProductImage = $row["ProductImage"];
        */
        
        $CategoryID = $row["CategoryID"];
		$SalesLimit = $row["SalesLimit"];
		$IsShowCSRPrice = $row["IsShowCSRPrice"];
		$IsConsumer = $row["IsConsumer"];

        // GMC - 11/03/14 - Take out IsPro
		// $IsPro = $row["IsPro"];

		$IsActive = $row["IsActive"];
		$IsDomestic = $row["IsDomestic"];
		$IsRep = $row["IsRep"];

        // GMC - 07/14/10 - Include Box Values in tblProducts
		$BoxCount = $row["BoxCount"];
		$BoxWeight = $row["BoxWeight"];
		$BoxLength = $row["BoxLength"];
		$BoxHeight = $row["BoxHeight"];
		$BoxWidth = $row["BoxWidth"];

        // GMC - 11/08/11 - Include MinimumQty in tblProducts
		$MinimumQty = $row["MinimumQty"];

        // GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process
		$StateExclusion = $row["StateExclusion"];

        // GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process
		$UserIDExclusion = $row["UserIDExclusion"];

        // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
		$CountryExclusion = $row["CountryExclusion"];
  
        // GMC - 06/11/13 - Special Discount Process System
        $ItemDiscount = $row["ItemDiscount"];
        $DiscountValue = $row["DiscountValue"];

        // GMC - 01/19/14 - Discount Promo Code International Items
        $IntDiscProCode = $row["IntDiscProCode"];
        $IntDiscProStartDate = $row["IntDiscProStartDate"];
        $IntDiscProEndDate = $row["IntDiscProEndDate"];
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

?>

<h1>Edit Revitalash Product</h1>
<body>
<form method="post" action="revitalash_products_edit.php">
<div class="bluediv_content">
    <table width="80%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">
    <tr class="tdwhite" style="font-weight:bold;">
        <td width="20%">RecordID:</td><td><?php echo $RecordID ?></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Product Name:</td><td><input type="text" size="100" name="product_name" value="<?php echo $ProductName ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Part Number:</td><td><input type="text" name="part_number" value="<?php echo $PartNumber ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Listing Description:</td><td><input type="text" size="100" name="listing_description" value="<?php echo $ListingDescription ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Cart Description:</td><td><input type="text" size="100" name="cart_description" value="<?php echo $CartDescription ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Long Description:</td><td><input type="text" size="100" name="long_description" value="<?php echo $LongDescription ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Retail Price:</td><td><input type="text" name="retail_price" value="<?php echo $RetailPrice ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Reseller Price:</td><td><input type="text" name="reseller_price" value="<?php echo $ResellerPrice ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Distributor Price:</td><td><input type="text" name="distributor_price" value="<?php echo $DistributorPrice ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Size:</td><td><input type="text" name="size" value="<?php echo $Size ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Weight:</td><td><input type="text" name="weight" value="<?php echo $Weight ?>" /></td>
    </tr>
    
    <!--
    <tr class="tdwhite" style="font-weight:bold;">
        <td>International Surcharge:</td><td><input type="text" name="international_surcharge" value="<?php // echo $InternationalSurcharge ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Reseller Free Trigger:</td><td><input type="text" name="reseller_free_trigger" value="<?php // echo $ResellerFreeTrigger ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Distributor Free Trigger:</td><td><input type="text" name="distributor_free_trigger" value="<?php // echo $DistributorFreeTrigger ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Cart Thumbnail:</td><td><input type="text" name="cart_thumbnail" value="<?php // echo $CartThumbnail ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Gallery Thumbnail:</td><td><input type="text" name="gallery_thumbnail" value="<?php // echo $GalleryThumbnail ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Product Image:</td><td><input type="text" name="product_image" value="<?php // echo $ProductImage ?>" /></td>
    </tr>
    -->

    <tr class="tdwhite" style="font-weight:bold;">
        <td>CategoryID:</td><td><input type="text" name="category_id" value="<?php echo $CategoryID ?>" /> 1 = Product, 2 = Literature</td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Sales Limit:</td><td><input type="text" name="sales_limit" value="<?php echo $SalesLimit ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Show CSR Price:</td><td><input type="text" name="is_show_csr_price" value="<?php echo $IsShowCSRPrice ?>" /> 1 = True, 0 = False</td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Is Consumer?:</td><td><input type="text" name="is_consumer" value="<?php echo $IsConsumer ?>" /> 1 = True, 0 = False</td>
    </tr>
    
    <!--
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Is Pro?:</td><td><input type="text" name="is_pro" value="<?php // echo $IsPro ?>" /> 1 = True, 0 = False</td>
    </tr>
    -->

    <tr class="tdwhite" style="font-weight:bold;">
        <td>Is Active?:</td><td><input type="text" name="is_active" value="<?php echo $IsActive ?>" /> 1 = True, 0 = False</td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Is Domestic?:</td><td><input type="text" name="is_domestic" value="<?php echo $IsDomestic ?>" /> 1 = True, 0 = False</td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Is Rep?:</td><td><input type="text" name="is_rep" value="<?php echo $IsRep ?>" /> 1 = True, 0 = False</td>
    </tr>
    
    <!-- GMC - 07/14/10 - Include Box Values in tblProducts-->
    <!-- GMC - 07/26/10 - Change order of box fields -->
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Box Count:</td><td><input type="text" name="box_count" value="<?php echo $BoxCount ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Box Weight:</td><td><input type="text" name="box_weight" value="<?php echo $BoxWeight ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Box Length:</td><td><input type="text" name="box_length" value="<?php echo $BoxLength ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Box Width:</td><td><input type="text" name="box_width" value="<?php echo $BoxWidth ?>" /></td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Box Height:</td><td><input type="text" name="box_height" value="<?php echo $BoxHeight ?>" /></td>
    </tr>

    <!-- GMC - 11/08/11 - Include MinimumQty in tblProducts-->
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Minimum Qty:</td><td><input type="text" name="minimum_qty" value="<?php echo $MinimumQty ?>" /></td>
    </tr>

    <!-- GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process -->
    <tr class="tdwhite" style="font-weight:bold;">
        <td>State Exclusion:</td><td><input type="text" name="state_exc" value="<?php echo $StateExclusion ?>" /></td>
    </tr>

     <!-- GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process -->
    <tr class="tdwhite" style="font-weight:bold;">
        <td>UserID Exclusion:</td><td><input type="text" name="userid_exc" value="<?php echo $UserIDExclusion ?>" /></td>
    </tr>

    <!-- GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)-->
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Country Exclusion:</td><td><input type="text" name="country_exc" value="<?php echo $CountryExclusion ?>" /></td>
    </tr>

    <!-- GMC - 06/11/13 - Special Discount Process System -->
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Item Discount:</td><td><input type="text" name="item_disc" value="<?php echo $ItemDiscount ?>" /> 1 = True, 0 = False</td>
    </tr>

    <tr class="tdwhite" style="font-weight:bold;">
        <td>Discount Value:</td><td><input type="text" name="disc_value" value="<?php echo $DiscountValue ?>" /></td>
    </tr>

    <!-- GMC - 01/19/14 - Discount Promo Code International Items -->
    <tr class="tdwhite" style="font-weight:bold;">
        <td>Discount Promo Code:</td><td><input type="text" name="disc_pro" value="<?php echo $IntDiscProCode ?>" /></td>
    </tr>

    <tr class="tdwhite" style="font-weight:bold;">
        <td>Start Date:</td><td><input type="text" name="start_date" value="<?php echo $IntDiscProStartDate ?>" /></td>
    </tr>

    <tr class="tdwhite" style="font-weight:bold;">
        <td>End Date:</td><td><input type="text" name="end_date" value="<?php echo $IntDiscProEndDate ?>" /></td>
    </tr>

    <tr class="tdwhite" style="font-weight:bold;">
        <td colspan="3">
        "SUBMIT" = Save Changes Or "BACK"
        </td>
    </tr>
    <tr class="tdwhite" style="font-weight:bold;">
        <td>
        <input type="submit" name="edit_submit" value="Submit" />
        <input type="submit" name="edit_back" value="Back" />
        </td>
    </tr>
    </table>
</div>
</form>
</body>

<?php
}
$Submit = "";
$Back = "";

if (isset($_POST['edit_back']))
{
    $Back = $_POST['edit_back'];
}

if($Back == "Back")
{
    // Now Present the Information
    // include("dbmaint.php");

    // Load the DB Object with the table information
	$tblRevitalashProducts = '';

    // GMC - 10/03/11 - Order tblProducts by Active and Cart Description by JS
	// $strSQL = "select * from tblProducts order by convert(int, PartNumber)";
	$strSQL = "select * from tblProducts order by isactive desc, cartdescription asc";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetProducts))
	{
        // GMC - 07/22/11 - Take certain fields from tblProducts Display
		$tblRevitalashProducts .= '<tr class="tdwhite">';
		$tblRevitalashProducts .= '<td>|<a href="revitalash_products_edit.php?id=' . $row["RecordID"] . '">edit</a></td>';
		$tblRevitalashProducts .= '<td>|<a href="revitalash_products_delete.php?id=' . $row["RecordID"] . '">delete</a></td>';

        // GMC - 11/11/10 - Put RecordID back by JS
        $tblRevitalashProducts .= '<td>|' . $row["RecordID"] . '</td>';

        // $tblRevitalashProducts .= '<td>|' . $row["ProductName"] . '</td>';

		$tblRevitalashProducts .= '<td>|' . $row["PartNumber"] . '</td>';

		// $tblRevitalashProducts .= '<td>|' . $row["ListingDescription"] . '</td>';

		$tblRevitalashProducts .= '<td>|' . $row["CartDescription"] . '</td>';

		// $tblRevitalashProducts .= '<td>|' . $row["LongDescription"] . '</td>';

		$tblRevitalashProducts .= '<td>|' . $row["RetailPrice"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["ResellerPrice"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["DistributorPrice"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["Size"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["Weight"] . '</td>';

        // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
		// $tblRevitalashProducts .= '<td>|' . $row["InternationalSurcharge"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["ResellerFreeTrigger"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["DistributorFreeTrigger"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["CartThumbnail"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["GalleryThumbnail"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["ProductImage"] . '</td>';

		$tblRevitalashProducts .= '<td>|' . $row["CategoryID"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["SalesLimit"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsShowCSRPrice"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsConsumer"] . '</td>';

        // GMC - 11/03/14 - Take out IsPro
		// $tblRevitalashProducts .= '<td>|' . $row["IsPro"] . '</td>';

		$tblRevitalashProducts .= '<td>|' . $row["IsActive"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsDomestic"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsRep"] . '</td>';

        // GMC - 07/14/10 - Include Box Values in tblProducts
        // GMC - 07/26/10 - Change order of box fields
		$tblRevitalashProducts .= '<td>|' . $row["BoxCount"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["BoxWeight"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["BoxLength"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["BoxWidth"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["BoxHeight"] . '</td>';

        // GMC - 11/08/11 - Include MinimumQty in tblProducts
		$tblRevitalashProducts .= '<td>|' . $row["MinimumQty"] . '</td>';

        // GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process
		$tblRevitalashProducts .= '<td>|' . $row["StateExclusion"] . '</td>';

        // GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process
		$tblRevitalashProducts .= '<td>|' . $row["UserIDExclusion"] . '</td>';

        // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
		$tblRevitalashProducts .= '<td>|' . $row["CountryExclusion"] . '</td>';

        // GMC - 06/11/13 - Special Discount Process System
		$tblRevitalashProducts .= '<td>|' . $row["ItemDiscount"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["DiscountValue"] . '</td>';

        // GMC - 01/19/14 - Discount Promo Code International Items
		$tblRevitalashProducts .= '<td>|' . $row["IntDiscProCode"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IntDiscProStartDate"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IntDiscProEndDate"] . '</td>';

		$tblRevitalashProducts .= '</tr>';
	}

    // GMC - 07/26/10 - Output to file tblProducts
    // GMC - 06/19/11 - Add RecordId to Output
    // GMC - 10/03/11 - Order tblProducts by Active and Cart Description by JS
    // GMC - 11/08/11 - Include MinimumQty in tblProducts
    // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
    $tblRevitalashProductsFile = '';
	// $strSQL = "select * from tblProducts order by convert(int, PartNumber)";
	$strSQL = "select * from tblProducts order by isactive desc, cartdescription asc";
	$qryGetProductsFile = mssql_query($strSQL);

    $myFile = "c:\\inetpub\\wwwroot\csradmin\\tblProducts.csv";
    $fh = fopen($myFile, 'w') or die("can't open file");
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";
    $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";

    fwrite($fh, $tblRevitalashProductsHead);

	while($row = mssql_fetch_array($qryGetProductsFile))
	{
        $tblRevitalashProductsFile = $row["RecordID"] . ',';
        $tblRevitalashProductsFile .= $row["ProductName"] . ',';
		$tblRevitalashProductsFile .= $row["PartNumber"] . ',';
		$tblRevitalashProductsFile .= $row["ListingDescription"] . ',';
		$tblRevitalashProductsFile .= $row["CartDescription"] . ',';
		$tblRevitalashProductsFile .= $row["LongDescription"] . ',';
		$tblRevitalashProductsFile .= $row["RetailPrice"] . ',';
		$tblRevitalashProductsFile .= $row["ResellerPrice"] . ',';
		$tblRevitalashProductsFile .= $row["DistributorPrice"] . ',';
		$tblRevitalashProductsFile .= $row["Size"] . ',';
		$tblRevitalashProductsFile .= $row["Weight"] . ',';

        // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
        /*
		$tblRevitalashProductsFile .= $row["InternationalSurcharge"] . ',';
		$tblRevitalashProductsFile .= $row["ResellerFreeTrigger"] . ',';
		$tblRevitalashProductsFile .= $row["DistributorFreeTrigger"] . ',';
		$tblRevitalashProductsFile .= $row["CartThumbnail"] . ',';
		$tblRevitalashProductsFile .= $row["GalleryThumbnail"] . ',';
		$tblRevitalashProductsFile .= $row["ProductImage"] . ',';
        */
        
        $tblRevitalashProductsFile .= $row["CategoryID"] . ',';
		$tblRevitalashProductsFile .= $row["SalesLimit"] . ',';
		$tblRevitalashProductsFile .= $row["IsShowCSRPrice"] . ',';
		$tblRevitalashProductsFile .= $row["IsConsumer"] . ',';
		$tblRevitalashProductsFile .= $row["IsPro"] . ',';
		$tblRevitalashProductsFile .= $row["IsActive"] . ',';
		$tblRevitalashProductsFile .= $row["IsDomestic"] . ',';
		$tblRevitalashProductsFile .= $row["IsRep"] . ',';
		$tblRevitalashProductsFile .= $row["BoxCount"] . ',';
		$tblRevitalashProductsFile .= $row["BoxWeight"] . ',';
		$tblRevitalashProductsFile .= $row["BoxLength"] . ',';
		$tblRevitalashProductsFile .= $row["BoxWidth"] . ',';
		$tblRevitalashProductsFile .= $row["BoxHeight"] . ',';
  
        // GMC - 11/08/11 - Include MinimumQty in tblProducts
		$tblRevitalashProductsFile .= $row["MinimumQty"] . ',';

        // GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process
		$tblRevitalashProductsFile .= $row["StateExclusion"] . ',';

        // GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process
		$tblRevitalashProductsFile .= $row["UserIDExclusion"] . ',';

        // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
		$tblRevitalashProductsFile .= $row["CountryExclusion"] . '';

         // GMC - 06/11/13 - Special Discount Process System
		$tblRevitalashProductsFile .= $row["ItemDiscount"] . '';
		$tblRevitalashProductsFile .= $row["DiscountValue"] . '';

        // GMC - 01/19/14 - Discount Promo Code International Items
		$tblRevitalashProductsFile .= $row["IntDiscProCode"] . '';
		$tblRevitalashProductsFile .= $row["IntDiscProStartDate"] . '';
		$tblRevitalashProductsFile .= $row["IntDiscProEndDate"] . '';

        $tblRevitalashProductsFile .= "\n";
        fwrite($fh, $tblRevitalashProductsFile);
        $tblRevitalashProductsFile = "";
	}

    fclose($fh);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    include("revitalash_products.php");
}

if (isset($_POST['edit_submit']))
{
    $Submit = $_POST['edit_submit'];
}

if($Submit == "Submit")
{
    // NULL if blank
    if($_POST["part_number"] == '')
    {
        $_POST["part_number"] = NULL;
    }
    if($_POST["listing_description"] == '')
    {
        $_POST["listing_description"] = NULL;
    }
    if($_POST["cart_description"] == '')
    {
        $_POST["cart_description"] = NULL;
    }
    if($_POST["long_description"] == '')
    {
        $_POST["long_description"] = NULL;
    }
    if($_POST["size"] == '')
    {
        $_POST["size"] = NULL;
    }
    if($_POST["weight"] == '')
    {
        $_POST["weight"] = NULL;
    }
    
    // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
    /*
    if($_POST["cart_thumbnail"] == '')
    {
        $_POST["cart_thumbnail"] = NULL;
    }
    if($_POST["gallery_thumbnail"] == '')
    {
        $_POST["gallery_thumbnail"] = NULL;
    }
    if($_POST["product_image"] == '')
    {
        $_POST["product_image"] = NULL;
    }
    */

    if($_POST["sales_limit"] == '')
    {
        $_POST["sales_limit"] = NULL;
    }

    // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
    // Define the SQL Statement
	$strSQL = "UPDATE tblProducts SET
        ProductName = '" . $_POST["product_name"] . "',
		PartNumber = '" .$_POST["part_number"] . "',
		ListingDescription = '" .$_POST["listing_description"] . "',
		CartDescription = '" . $_POST["cart_description"] . "',
        LongDescription = '" . $_POST["long_description"] . "',
		RetailPrice = '" . $_POST["retail_price"] . "',
		ResellerPrice = '" . $_POST["reseller_price"] . "',
		DistributorPrice = '" . $_POST["distributor_price"] . "',
		Size = '" . $_POST["size"] . "',
		Weight = '" .$_POST["weight"] . "',
		CategoryID = '" . $_POST["category_id"] . "',
		SalesLimit = '" . $_POST["sales_limit"] . "',
		IsShowCSRPrice = '" . $_POST["is_show_csr_price"] . "',
		IsConsumer = '" . $_POST["is_consumer"] . "',
		IsPro = '" . $_POST["is_pro"] . "',
		IsActive = '" . $_POST["is_active"] . "',
		IsDomestic = '" . $_POST["is_domestic"] . "',
		IsRep = '" . $_POST["is_rep"] . "',
		BoxCount = '" . $_POST["box_count"] . "',
		BoxWeight = '" . $_POST["box_weight"] . "',
		BoxLength = '" . $_POST["box_length"] . "',
		BoxHeight = '" . $_POST["box_height"] . "',
		BoxWidth = '" . $_POST["box_width"] . "',
		MinimumQty = '" . $_POST["minimum_qty"] . "',
		StateExclusion = '" . $_POST["state_exc"] . "',
		UserIDExclusion = '" . $_POST["userid_exc"] . "',
		CountryExclusion = '" . $_POST["country_exc"] . "',
		ItemDiscount = '" . $_POST["item_disc"] . "',
		DiscountValue = '" . $_POST["disc_value"] . "',
        IntDiscProCode = '" . $_POST["disc_pro"] . "',
        IntDiscProStartDate = '" . $_POST["start_date"] . "',
        IntDiscProEndDate = '" . $_POST["end_date"] . "'
        where RecordID = " . $_SESSION['edit_id'] . "";

        // echo $strSQL;
        
	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    // Now Present the Information
    // include("dbmaint.php");

    // Load the DB Object with the table information
	$tblRevitalashProducts = '';

    // GMC - 10/03/11 - Order tblProducts by Active and Cart Description by JS
	// $strSQL = "select * from tblProducts order by convert(int, PartNumber)";
	$strSQL = "select * from tblProducts order by isactive desc, cartdescription asc";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetProducts))
	{
        // GMC - 07/22/11 - Take certain fields from tblProducts Display
		$tblRevitalashProducts .= '<tr class="tdwhite">';
		$tblRevitalashProducts .= '<td>|<a href="revitalash_products_edit.php?id=' . $row["RecordID"] . '">edit</a></td>';
		$tblRevitalashProducts .= '<td>|<a href="revitalash_products_delete.php?id=' . $row["RecordID"] . '">delete</a></td>';

        // GMC - 11/11/10 - Put RecordID back by JS
        $tblRevitalashProducts .= '<td>|' . $row["RecordID"] . '</td>';

        // $tblRevitalashProducts .= '<td>|' . $row["ProductName"] . '</td>';

		$tblRevitalashProducts .= '<td>|' . $row["PartNumber"] . '</td>';

		// $tblRevitalashProducts .= '<td>|' . $row["ListingDescription"] . '</td>';

		$tblRevitalashProducts .= '<td>|' . $row["CartDescription"] . '</td>';

		// $tblRevitalashProducts .= '<td>|' . $row["LongDescription"] . '</td>';

		$tblRevitalashProducts .= '<td>|' . $row["RetailPrice"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["ResellerPrice"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["DistributorPrice"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["Size"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["Weight"] . '</td>';

        // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
		// $tblRevitalashProducts .= '<td>|' . $row["InternationalSurcharge"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["ResellerFreeTrigger"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["DistributorFreeTrigger"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["CartThumbnail"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["GalleryThumbnail"] . '</td>';
		// $tblRevitalashProducts .= '<td>|' . $row["ProductImage"] . '</td>';

		$tblRevitalashProducts .= '<td>|' . $row["CategoryID"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["SalesLimit"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsShowCSRPrice"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsConsumer"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsPro"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsActive"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsDomestic"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IsRep"] . '</td>';

        // GMC - 07/14/10 - Include Box Values in tblProducts
        // GMC - 07/26/10 - Change order of box fields
		$tblRevitalashProducts .= '<td>|' . $row["BoxCount"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["BoxWeight"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["BoxLength"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["BoxWidth"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["BoxHeight"] . '</td>';

        // GMC - 11/08/11 - Include MinimumQty in tblProducts
		$tblRevitalashProducts .= '<td>|' . $row["MinimumQty"] . '</td>';

        // GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process
		$tblRevitalashProducts .= '<td>|' . $row["StateExclusion"] . '</td>';

        // GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process
		$tblRevitalashProducts .= '<td>|' . $row["UserIDExclusion"] . '</td>';

        // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
		$tblRevitalashProducts .= '<td>|' . $row["CountryExclusion"] . '</td>';

         // GMC - 06/11/13 - Special Discount Process System
		$tblRevitalashProducts .= '<td>|' . $row["ItemDiscount"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["DiscountValue"] . '</td>';

        // GMC - 01/19/14 - Discount Promo Code International Items
		$tblRevitalashProducts .= '<td>|' . $row["IntDiscProCode"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IntDiscProStartDate"] . '</td>';
		$tblRevitalashProducts .= '<td>|' . $row["IntDiscProEndDate"] . '</td>';

		$tblRevitalashProducts .= '</tr>';
	}

    // GMC - 07/26/10 - Output to file tblProducts
    // GMC - 06/19/11 - Add RecordId to Output
    // GMC - 10/03/11 - Order tblProducts by Active and Cart Description by JS
    // GMC - 11/08/11 - Include MinimumQty in tblProducts
    // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
    $tblRevitalashProductsFile = '';
	// $strSQL = "select * from tblProducts order by convert(int, PartNumber)";
	$strSQL = "select * from tblProducts order by isactive desc, cartdescription asc";
	$qryGetProductsFile = mssql_query($strSQL);

    $myFile = "c:\\inetpub\\wwwroot\csradmin\\tblProducts.csv";
    $fh = fopen($myFile, 'w') or die("can't open file");
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue\n";
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,InternationalSurcharge,ResellerFreeTriger,DistributorFreeTrigger,CartThumbnail,GalleryThumbnail,ProductImage,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";
    $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";

    fwrite($fh, $tblRevitalashProductsHead);

	while($row = mssql_fetch_array($qryGetProductsFile))
	{
        $tblRevitalashProductsFile = $row["RecordID"] . ',';
        $tblRevitalashProductsFile .= $row["ProductName"] . ',';
		$tblRevitalashProductsFile .= $row["PartNumber"] . ',';
		$tblRevitalashProductsFile .= $row["ListingDescription"] . ',';
		$tblRevitalashProductsFile .= $row["CartDescription"] . ',';
		$tblRevitalashProductsFile .= $row["LongDescription"] . ',';
		$tblRevitalashProductsFile .= $row["RetailPrice"] . ',';
		$tblRevitalashProductsFile .= $row["ResellerPrice"] . ',';
		$tblRevitalashProductsFile .= $row["DistributorPrice"] . ',';
		$tblRevitalashProductsFile .= $row["Size"] . ',';
		$tblRevitalashProductsFile .= $row["Weight"] . ',';
  
        // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
        /*
		$tblRevitalashProductsFile .= $row["InternationalSurcharge"] . ',';
		$tblRevitalashProductsFile .= $row["ResellerFreeTrigger"] . ',';
		$tblRevitalashProductsFile .= $row["DistributorFreeTrigger"] . ',';
		$tblRevitalashProductsFile .= $row["CartThumbnail"] . ',';
		$tblRevitalashProductsFile .= $row["GalleryThumbnail"] . ',';
		$tblRevitalashProductsFile .= $row["ProductImage"] . ',';
        */
        
        $tblRevitalashProductsFile .= $row["CategoryID"] . ',';
		$tblRevitalashProductsFile .= $row["SalesLimit"] . ',';
		$tblRevitalashProductsFile .= $row["IsShowCSRPrice"] . ',';
		$tblRevitalashProductsFile .= $row["IsConsumer"] . ',';
		$tblRevitalashProductsFile .= $row["IsPro"] . ',';
		$tblRevitalashProductsFile .= $row["IsActive"] . ',';
		$tblRevitalashProductsFile .= $row["IsDomestic"] . ',';
		$tblRevitalashProductsFile .= $row["IsRep"] . ',';
		$tblRevitalashProductsFile .= $row["BoxCount"] . ',';
		$tblRevitalashProductsFile .= $row["BoxWeight"] . ',';
		$tblRevitalashProductsFile .= $row["BoxLength"] . ',';
		$tblRevitalashProductsFile .= $row["BoxWidth"] . ',';
		$tblRevitalashProductsFile .= $row["BoxHeight"] . ',';

        // GMC - 11/08/11 - Include MinimumQty in tblProducts
		$tblRevitalashProductsFile .= $row["MinimumQty"] . ',';

        // GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process
		$tblRevitalashProductsFile .= $row["StateExclusion"] . ',';

        // GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process
		$tblRevitalashProductsFile .= $row["UserIDExclusion"] . ',';

        // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
		$tblRevitalashProductsFile .= $row["CountryExclusion"] . '';

         // GMC - 06/11/13 - Special Discount Process System
		$tblRevitalashProductsFile .= $row["ItemDiscount"] . '';
		$tblRevitalashProductsFile .= $row["DiscountValue"] . '';

        // GMC - 01/19/14 - Discount Promo Code International Items
		$tblRevitalashProductsFile .= $row["IntDiscProCode"] . '';
		$tblRevitalashProductsFile .= $row["IntDiscProStartDate"] . '';
		$tblRevitalashProductsFile .= $row["IntDiscProEndDate"] . '';

        $tblRevitalashProductsFile .= "\n";
        fwrite($fh, $tblRevitalashProductsFile);
        $tblRevitalashProductsFile = "";
	}

    fclose($fh);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    include("revitalash_products.php");
}

?>

</html>
