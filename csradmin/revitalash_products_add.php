<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

$Submit = "";
$bolSwitch = "True";

if (isset($_POST['add_submit']))
{
    $Submit = $_POST['add_submit'];
}

if($Submit == "Submit")
{
    if ($_POST['product_name'] == '')
    {
       $bolSwitch = "False";
    }
    if ($_POST['part_number'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['cart_description'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['retail_price'] == '')
    {
        $bolSwitch = "False";
    }
    if ($_POST['reseller_price'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['distributor_price'] == '')
    {
        $bolSwitch = "False";
    }
    if ($_POST['weight'] == '')
    {
         $bolSwitch = "False";
    }
    
    // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
    /*
    if ($_POST['international_surcharge'] == '')
    {
        $bolSwitch = "False";
     }
    if ($_POST['reseller_free_trigger'] == '')
    {
        $bolSwitch = "False";
     }
    if ($_POST['distributor_free_trigger'] == '')
    {
        $bolSwitch = "False";
    }
    if ($_POST['cart_thumbnail'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['gallery_thumbnail'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['product_image'] == '')
    {
        $bolSwitch = "False";
    }
    */
    
    if ($_POST['category_id'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['is_show_csr_price'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['is_consumer'] == '')
    {
         $bolSwitch = "False";
    }
    
    // GMC - 11/03/14 - Take out IsPro
    /*
    if ($_POST['is_pro'] == '')
    {
         $bolSwitch = "False";
    }
    */

    if ($_POST['is_active'] == '')
    {
        $bolSwitch = "False";
    }
    if ($_POST['is_domestic'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['is_rep'] == '')
    {
         $bolSwitch = "False";
    }

    // GMC - 07/14/10 - Include Box Values in tblProducts-->
    if ($_POST['box_count'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['box_weight'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['box_length'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['box_height'] == '')
    {
         $bolSwitch = "False";
    }
    if ($_POST['box_width'] == '')
    {
         $bolSwitch = "False";
    }

    if($bolSwitch == "True")
    {
    // Define the SQL Statement
    // GMC - 07/14/10 - Include Box Values in tblProducts
    // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
    // GMC - 11/03/14 - Take out IsPro
    // GMC - 11/19/14 - Take out Listing Description and arrange some fields
	$strSQL = "INSERT INTO tblProducts
    (
        ProductName,
		PartNumber,
		CartDescription,
        LongDescription,
		RetailPrice,
		ResellerPrice,
		DistributorPrice,
		Weight,
		CategoryID,
		SalesLimit,
		IsShowCSRPrice,
		IsConsumer,
		IsActive,
		IsDomestic,
		IsRep,
        BoxCount,
        BoxWeight,
        BoxLength,
        BoxHeight,
        BoxWidth,
        MinimumQty,
        StateExclusion,
        UserIDExclusion,
        CountryExclusion,
        ItemDiscount,
        DiscountValue,
        IntDiscProCode,
        IntDiscProStartDate,
        IntDiscProEndDate
     )
     VALUES
     (
        '" . $_POST["product_name"] . "',
		'" . $_POST["part_number"] . "',
		'" . $_POST["cart_description"] . "',
        '" . $_POST["long_description"] . "',
		'" . $_POST["retail_price"] . "',
		'" . $_POST["reseller_price"] . "',
		'" . $_POST["distributor_price"] . "',
		'" . $_POST["weight"] . "',
        '" . $_POST["category_id"] . "',
		'" . $_POST["sales_limit"] . "',
		'" . $_POST["is_show_csr_price"] . "',
		'" . $_POST["is_consumer"] . "',
		'" . $_POST["is_active"] . "',
		'" . $_POST["is_domestic"] . "',
		'" . $_POST["is_rep"] . "',
		'" . $_POST["box_count"] . "',
		'" . $_POST["box_weight"] . "',
		'" . $_POST["box_length"] . "',
		'" . $_POST["box_height"] . "',
		'" . $_POST["box_width"] . "',
		'" . $_POST["minimum_qty"] . "',
		'" . $_POST["state_exc"] . "',
		'" . $_POST["userid_exc"] . "',
		'" . $_POST["country_exc"] . "',
		'" . $_POST["item_disc"] . "',
		'" . $_POST["disc_value"] . "',
		'" . $_POST["disc_pro"] . "',
		'" . $_POST["start_date"] . "',
		'" . $_POST["end_value"] . "'
     )";

    // echo $strSQL;

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProducts = mssql_query($strSQL);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    echo '<script language="javascript">alert("Product Added Sucessfully.")</script>;';

     include("dbmaint.php");

    }
    else
    {
        echo '<script language="javascript">alert("You have not entered one or more of the required fields, please try again.")</script>;';
    }
}

$Back = "";

if (isset($_POST['add_back']))
{
    $Back = $_POST['add_back'];
}

if($Back == "Back")
{
    include("dbmaint.php");
}
else
{
    if($Submit == "")
    {
    $ProductName = "";
	$PartNumber = "";
	$CartDescription = "";
	$RetailPrice = "";
	$ResellerPrice = "";
	$DistributorPrice = "";
	$Weight = "";
 
    // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
    /*
	$InternationalSurcharge = "";
	$ResellerFreeTrigger = "";
	$DistributorFreeTrigger = "";
	$CartThumbnail = "";
	$GalleryThumbnail = "";
	$ProductImage = "";
    */

    $CategoryID = "";
	$IsShowCSRPrice = "";
	$IsConsumer = "";
 
    // GMC - 11/03/14 - Take out IsPro
	// $IsPro = "";

	$IsActive = "";
	$IsDomestic = "";
	$IsRep = "";
 
    // GMC - 07/14/10 - Include Box Values in tblProducts-->
    $BoxCount = "";
    $BoxWeight = "";
    $BoxLength = "";
    $BoxHeight = "";
    $BoxWidth = "";

    // GMC - 11/08/11 - Include MinimumQty in tblProducts
    $MinimumQty = "";

    // GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process
    $StateExclusion = "";

    // GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process
    $UserIDExclusion = "";

    // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
    $CountryExclusion = "";

    // GMC - 06/11/13 - Special Discount Process System
    $ItemDiscount = "";
    $DiscountValue = "";

    // GMC - 01/19/14 - Discount Promo Code International Items
    $IntDiscProCode = "";
    $IntDiscProStartDate = "";
    $IntDiscProEndDate = "";

    if (isset($_POST['product_name']))
    {
        $ProductName = $_POST['product_name'];
    }
    if (isset($_POST['part_number']))
    {
        $PartNumber = $_POST['part_number'];
    }
    if (isset($_POST['cart_description']))
    {
        $CartDescription = $_POST['cart_description'];
    }
    if (isset($_POST['retail_price']))
    {
        $RetailPrice = $_POST['retail_price'];
    }
    if (isset($_POST['reseller_price']))
    {
        $ResellerPrice = $_POST['reseller_price'];
    }
    if (isset($_POST['distributor_price']))
    {
        $DistributorPrice = $_POST['distributor_price'];
    }
    if (isset($_POST['weight']))
    {
        $Weight = $_POST['weight'];
    }
    
    // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
    /*
    if (isset($_POST['international_surcharge']))
    {
        $InternationalSurcharge = $_POST['international_surcharge'];
    }
    if (isset($_POST['reseller_free_trigger']))
    {
        $ResellerFreeTrigger = $_POST['reseller_free_trigger'];
    }
    if (isset($_POST['distributor_free_trigger']))
    {
        $DistributorFreeTrigger = $_POST['distributor_free_trigger'];
    }
    if (isset($_POST['cart_thumbnail']))
    {
        $CartThumbnail = $_POST['cart_thumbnail'];
    }
    if (isset($_POST['gallery_thumbnail']))
    {
        $GalleryThumbnail = $_POST['gallery_thumbnail'];
    }
    if (isset($_POST['product_image']))
    {
        $ProductImage = $_POST['product_image'];
    }
    */

    if (isset($_POST['category_id']))
    {
        $CategoryID = $_POST['category_id'];
    }
    if (isset($_POST['is_show_csr_price']))
    {
        $IsShowCSRPrice = $_POST['is_show_csr_price'];
    }
    if (isset($_POST['is_consumer']))
    {
        $IsConsumer = $_POST['is_consumer'];
    }

    // GMC - 11/03/14 - Take out IsPro
    /*
    if (isset($_POST['is_pro']))
    {
        $IsPro = $_POST['is_pro'];
    }
    */
    
    if (isset($_POST['is_active']))
    {
        $IsActive = $_POST['is_active'];
    }
    if (isset($_POST['is_domestic']))
    {
        $IsDomestic = $_POST['is_domestic'];
    }
    if (isset($_POST['is_rep']))
    {
        $IsRep = $_POST['is_rep'];
    }

    // GMC - 07/14/10 - Include Box Values in tblProducts-->
    if (isset($_POST['box_count']))
    {
        $BoxCount = $_POST['box_count'];
    }
    if (isset($_POST['box_weight']))
    {
        $BoxWeight = $_POST['box_weight'];
    }
    if (isset($_POST['box_lenght']))
    {
        $BoxLength = $_POST['box_length'];
    }
    if (isset($_POST['box_height']))
    {
        $BoxHeight = $_POST['box_height'];
    }
    if (isset($_POST['box_widht']))
    {
        $BoxWidth = $_POST['box_widht'];
    }

    // GMC - 11/08/11 - Include MinimumQty in tblProducts
    if (isset($_POST['minimum_qty']))
    {
        $MinimumQty = $_POST['minimum_qty'];
    }

    // GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process
    if (isset($_POST['state_exc']))
    {
        $StateExclusion = $_POST['state_exc'];
    }

    // GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process
    if (isset($_POST['userid_exc']))
    {
        $UserIDExclusion = $_POST['userid_exc'];
    }

    // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
    if (isset($_POST['country_exc']))
    {
        $CountryExclusion = $_POST['country_exc'];
    }

    // GMC - 06/11/13 - Special Discount Process System
    if (isset($_POST['item_disc']))
    {
        $ItemDiscount = $_POST['item_disc'];
    }

    if (isset($_POST['disc_value']))
    {
        $DiscountValue = $_POST['disc_value'];
    }

    // GMC - 01/19/14 - Discount Promo Code International Items
    if (isset($_POST['disc_pro']))
    {
        $IntDiscProCode = $_POST['disc_pro'];
    }

    if (isset($_POST['start_date']))
    {
        $IntDiscProStartDate = $_POST['start_date'];
    }

    if (isset($_POST['end_value']))
    {
        $IntDiscProEndDate = $_POST['end_value'];
    }

    $strBody = "";
    $strBody .= '<h1>Add Revitalash Product</h1>';
    $strBody .= '<body>';
    $strBody .= '<p><font color="red">* Required</font></p>';
    $strBody .= '<form method="post" action="revitalash_products_add.php">';
    $strBody .= '<div class="bluediv_content">';
    $strBody .= '<table width="80%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3"> ';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Product Name:</td><td><input type="text" size="100" name="product_name" value="' . $ProductName . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Part Number:</td><td><input type="text" name="part_number" value="' . $PartNumber . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    
    // GMC - 11/19/14 - Take out Listing Description and arrange some fields
    /*
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Listing Description:</td><td><input type="text" size="100" name="listing_description" value="" /></td>';
    $strBody .= '</tr>';
    */

    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Cart Description:</td><td><input type="text" size="100" name="cart_description" value="' . $CartDescription . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Long Description:</td><td><input type="text" size="100" name="long_description" value="" /></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Retail Price:</td><td><input type="text" name="retail_price" value="' . $RetailPrice . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Reseller Price:</td><td><input type="text" name="reseller_price" value="' . $ResellerPrice . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Distributor Price:</td><td><input type="text" name="distributor_price" value="' . $DistributorPrice . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    
    // GMC - 11/19/14 - Take out Listing Description and arrange some fields
    /*
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Size:</td><td><input type="text" name="size" value="" /></td>';
    $strBody .= '</tr>';
    */

    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Weight:</td><td><input type="text" name="weight" value="' . $Weight . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    
    // GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img
    /*
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>International Surcharge:</td><td><input type="text" name="international_surcharge" value="' . $InternationalSurcharge . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Reseller Free Trigger:</td><td><input type="text" name="reseller_free_trigger" value="' . $ResellerFreeTrigger . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Distributor Free Trigger:</td><td><input type="text" name="distributor_free_trigger" value="' . $DistributorFreeTrigger . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Cart Thumbnail:</td><td><input type="text" name="cart_thumbnail" value="' . $CartThumbnail . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Gallery Thumbnail:</td><td><input type="text" name="gallery_thumbnail" value="' . $GalleryThumbnail . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Product Image:</td><td><input type="text" name="product_image" value="' . $ProductImage . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    */

    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>CategoryID:</td><td><input type="text" name="category_id" value="' . $CategoryID . '" /><font color="red">*</font> 1 = Product, 2 = Literature</td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Sales Limit:</td><td><input type="text" name="sales_limit" value="" /></td>';
    $strBody .= '</tr>';

    // GMC - 11/19/14 - Take out Listing Description and arrange some fields
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Is Active?:</td><td><input type="text" name="is_active" value="' . $IsActive . '" /><font color="red">*</font> 1 = True, 0 = False</td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Is Domestic?:</td><td><input type="text" name="is_domestic" value="' . $IsDomestic . '" /><font color="red">*</font> 1 = True, 0 = False</td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Is CSR:</td><td><input type="text" name="is_show_csr_price" value="' . $IsShowCSRPrice . '" /><font color="red">*</font> 1 = True, 0 = False</td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Is Rep?:</td><td><input type="text" name="is_rep" value="' . $IsRep . '" /><font color="red">*</font> 1 = True, 0 = False</td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Is Consumer?:</td><td><input type="text" name="is_consumer" value="' . $IsConsumer . '" /><font color="red">*</font> 1 = True, 0 = False</td>';
    $strBody .= '</tr>';
    
    // GMC - 11/03/14 - Take out IsPro
    /*
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Is Pro?:</td><td><input type="text" name="is_pro" value="' . $IsPro . '" /><font color="red">*</font> 1 = True, 0 = False</td>';
    $strBody .= '</tr>';
    */

    // GMC - 07/14/10 - Include Box Values in tblProducts-->
    // GMC - 07/26/10 - Change order of box fields
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Box Count:</td><td><input type="text" name="box_count" value="' . $BoxCount . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Box Weight:</td><td><input type="text" name="box_weight" value="' . $BoxWeight . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Box Length:</td><td><input type="text" name="box_length" value="' . $BoxLength . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Box Width:</td><td><input type="text" name="box_width" value="' . $BoxWidth . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Box Height:</td><td><input type="text" name="box_height" value="' . $BoxHeight . '" /><font color="red">*</font></td>';
    $strBody .= '</tr>';

    // GMC - 11/08/11 - Include MinimumQty in tblProducts
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Minimum Qty:</td><td><input type="text" name="minimum_qty" value="' . $MinimumQty . '" /></td>';
    $strBody .= '</tr>';

    // GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>State Exclusion:</td><td><input type="text" name="state_exc" value="' . $StateExclusion . '" /></td>';
    $strBody .= '</tr>';

    // GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>UserID Exclusion:</td><td><input type="text" name="userid_exc" value="' . $UserIDExclusion . '" /></td>';
    $strBody .= '</tr>';

    // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Country Exclusion:</td><td><input type="text" name="country_exc" value="' . $CountryExclusion . '" /></td>';
    $strBody .= '</tr>';

    // GMC - 06/11/13 - Special Discount Process System
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Item Discount:</td><td><input type="text" name="item_disc" value="' . $ItemDiscount . '" /> 1 = True, 0 = False</td>';
    $strBody .= '</tr>';

    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Discount Value:</td><td><input type="text" name="disc_value" value="' . $DiscountValue . '" /></td>';
    $strBody .= '</tr>';

    // GMC - 01/19/14 - Discount Promo Code International Items
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Discount Promo Code:</td><td><input type="text" name="disc_pro" value="' . $IntDiscProCode . '" /></td>';
    $strBody .= '</tr>';

    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>Start Date:</td><td><input type="text" name="start_date" value="' . $IntDiscProStartDate . '" /></td>';
    $strBody .= '</tr>';

    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>End Date:</td><td><input type="text" name="end_date" value="' . $IntDiscProEndDate . '" /></td>';
    $strBody .= '</tr>';

    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td colspan="3">';
    $strBody .= '"SUBMIT" = Save New Record Or "BACK"';
    $strBody .= '</td>';
    $strBody .= '</tr>';
    $strBody .= '<tr class="tdwhite" style="font-weight:bold;">';
    $strBody .= '<td>';
    $strBody .= '<input type="submit" name="add_submit" value="Submit" />';
    $strBody .= '<input type="submit" name="add_back" value="Back" />';
    $strBody .= '</td>';
    $strBody .= '</tr>';
    $strBody .= '</table>';
    $strBody .= '</div>';
    $strBody .= '</form>';
    $strBody .= '</body>';

    echo $strBody;
    }
}

?>

</html>
