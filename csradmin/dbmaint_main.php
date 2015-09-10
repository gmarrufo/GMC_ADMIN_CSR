<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

// GMC - 07/28/11  - Monthly Newsletters
$Back = "";
if (isset($_POST['monthly_news_back']))
{
    $Back = $_POST['monthly_news_back'];
}
if($Back == "Back")
{
    include("dbmaint.php");
}

// GMC - 07/13/11 - Navision Search for Products
if (isset($_POST['NavSearch']) && $_POST['NavSearch'] != "")
{
    // Load the DB Object with the table information
	$tblRevitalashProducts = '';
    $NavSearch = $_POST['NavSearch'];
    unset($_POST['NavSearch']);
	$strSQL = "select * from tblProducts where PartNumber = " . $NavSearch . "";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY PRODUCT RECORDID
	$qryGetRecId = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetRecId))
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
    // GMC - 11/03/14 - Take out IsPro
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
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";
    $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";
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

        // GMC - 11/03/14 - Take out IsPro
        // $tblRevitalashProductsFile .= $row["IsPro"] . ',';

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

    // Now Present the Information
    include("revitalash_products.php");
}

// GMC - 07/19/12 - RecordID Search for Products
if (isset($_POST['RecSearch']) && $_POST['RecSearch'] != "")
{
    // Load the DB Object with the table information
	$tblRevitalashProducts = '';
    $RecSearch = $_POST['RecSearch'];
    unset($_POST['RecSearch']);
	$strSQL = "select * from tblProducts where RecordID = " . $RecSearch . "";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY PRODUCT RECORDID
	$qryGetRecId = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetRecId))
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
    // GMC - 11/03/14 - Take out IsPro
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
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";
    $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";

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

        // GMC - 11/03/14 - Take out IsPro
        // $tblRevitalashProductsFile .= $row["IsPro"] . ',';

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

    // Now Present the Information
    include("revitalash_products.php");
}

if (isset($_POST['radDBMaint']))
{

// Check what selection was made
if ($_POST['radDBMaint'] == 'revitalash_products')
{
    // Load the DB Object with the table information
	$tblRevitalashProducts = '';

    // GMC - 10/03/11 - Order tblProducts by Active and Cart Description by JS
    // GMC - 11/03/14 - Order tblPRoducts by IsActive - IsDomestic - IsShowCSRPrice
	// $strSQL = "select * from tblProducts order by convert(int, PartNumber)";
	// $strSQL = "select * from tblProducts order by isactive desc, cartdescription asc";
	$strSQL = "select * from tblProducts order by isactive desc, isdomestic desc, isshowcsrprice desc";

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
    // GMC - 11/03/14 - Take out IsPro
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
    // $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsPro,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";
    $tblRevitalashProductsHead = "RecordId,ProductName,PartNumber,ListingDescription,CartDescription,LongDescription,RetailPrice,ResellerPrice,DistributorPrice,Size,Weight,CategoryID,SalesLimit,IsShowCSRPrice,IsConsumer,IsActive,IsDomestic,IsRep,BoxCount,BoxWeigth,BoxLength,BoxWidth,BoxHeight,MinimumQty,StateExclusion,UserIDExclusion,CountryExclusion,ItemDiscount,DiscountValue,IntDiscProCode,IntDiscProStartDate,IntDiscProEndDate\n";

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

        // GMC - 11/03/14 - Take out IsPro
		// $tblRevitalashProductsFile .= $row["IsPro"] . ',';

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

    // Now Present the Information
    include("revitalash_products.php");
}
else if ($_POST['radDBMaint'] == 'revitalash_users')
{
    // Load the DB Object with the table information
	$tblRevitalashUsers = '';

    // GMC - 07/06/10 - Order Users Asc by LastName
	$strSQL = "SELECT * FROM tblRevitalash_Users order by lastname asc";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetUsers = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetUsers))
	{
		$tblRevitalashUsers .= '<tr class="tdwhite">';
		$tblRevitalashUsers .= '<td>|<a href="revitalash_users_edit.php?id=' . $row["RecordID"] . '">edit</a></td>';
		$tblRevitalashUsers .= '<td>|<a href="revitalash_users_delete.php?id=' . $row["RecordID"] . '">delete</a></td>';
		$tblRevitalashUsers .= '<td>|' . $row["RecordID"] . '</td>';
		$tblRevitalashUsers .= '<td>|' . $row["RevitalashID"] . '</td>';
		$tblRevitalashUsers .= '<td>|' . $row["FirstName"] . '</td>';
		$tblRevitalashUsers .= '<td>|' . $row["LastName"] . '</td>';
		$tblRevitalashUsers .= '<td>|' . $row["UserTypeID"] . '</td>';
		$tblRevitalashUsers .= '<td>|' . $row["NavisionUserID"] . '</td>';
		$tblRevitalashUsers .= '<td>|' . $row["EMailAddress"] . '</td>';
		$tblRevitalashUsers .= '<td>|' . $row["Password"] . '</td>';
		$tblRevitalashUsers .= '<td>|' . $row["IsActive"] . '</td>';
		$tblRevitalashUsers .= '</tr>';
	}

    // GMC - GMC - 01/15/13 - tblUsers to Excel
    $tblRevitalashUsersFile = '';
	$strSQL = "select * from tblRevitalash_Users order by isactive desc";
	$qryGetUsersFile = mssql_query($strSQL);

    $myFile = "c:\\inetpub\\wwwroot\csradmin\\tblUsers.csv";
    $fh = fopen($myFile, 'w') or die("can't open file");
    $tblRevitalashUsersHead = "RecordID,RevitalashID,FirstName,LastName,UserTypeID,NavisionUserID,EMailAddress,Password,IsActive\n";
    fwrite($fh, $tblRevitalashUsersHead);

	while($row = mssql_fetch_array($qryGetUsersFile))
	{
        $tblRevitalashUsersFile = $row["RecordID"] . ',';
        $tblRevitalashUsersFile .= $row["RevitalashID"] . ',';
		$tblRevitalashUsersFile .= $row["FirstName"] . ',';
		$tblRevitalashUsersFile .= $row["LastName"] . ',';
		$tblRevitalashUsersFile .= $row["UserTypeID"] . ',';
		$tblRevitalashUsersFile .= $row["NavisionUserID"] . ',';
		$tblRevitalashUsersFile .= $row["EMailAddress"] . ',';
		$tblRevitalashUsersFile .= $row["Password"] . ',';
		$tblRevitalashUsersFile .= $row["IsActive"] . ',';

        $tblRevitalashUsersFile .= "\n";
        fwrite($fh, $tblRevitalashUsersFile);
        $tblRevitalashUsersFile = "";
	}

    fclose($fh);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    // Now Present the Information
    include("revitalash_users.php");

}
else if ($_POST['radDBMaint'] == 'revitalash_products_reseller')
{
    // Load the DB Object with the table information
	$tblProductsResellerTier = '';

    // GMC - 06/10/12 - Order ResellerTier Asc by ProductID
	$strSQL = "SELECT * from tblProducts_ResellerTier order by ProductID asc";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetProductsResellerTier = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetProductsResellerTier))
	{
		$tblProductsResellerTier .= '<tr class="tdwhite">';
		$tblProductsResellerTier .= '<td>|<a href="revitalash_products_reseller_edit.php?id=' . $row["RecordID"] . '">edit</a></td>';
		$tblProductsResellerTier .= '<td>|<a href="revitalash_products_reseller_delete.php?id=' . $row["RecordID"] . '">delete</a></td>';
		$tblProductsResellerTier .= '<td>|' . $row["RecordID"] . '</td>';
		$tblProductsResellerTier .= '<td>|' . $row["ProductID"] . '</td>';
		$tblProductsResellerTier .= '<td>|' . $row["QtyRequired"] . '</td>';
		$tblProductsResellerTier .= '<td>|' . $row["DiscountPrice"] . '</td>';
		$tblProductsResellerTier .= '</tr>';
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    // Now Present the Information
    include("revitalash_products_reseller_tier.php");

}
else if ($_POST['radDBMaint'] == 'revitalash_campaigns')
{
    // Load the DB Object with the table information
	$tblCampaigns = '';

    // GMC - 04/19/12 - Sort tblCampaigns by Navision Code and IsActive
	// $strSQL = "SELECT * FROM tblCampaigns";
	$strSQL = "SELECT * FROM tblCampaigns order by IsActive DESC, NavisionCode ASC";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetUsers = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetUsers))
	{
		$tblCampaigns .= '<tr class="tdwhite">';
		$tblCampaigns .= '<td>|<a href="revitalash_campaigns_edit.php?id=' . $row["RecordID"] . '">edit</a></td>';
		$tblCampaigns .= '<td>|<a href="revitalash_campaigns_delete.php?id=' . $row["RecordID"] . '">delete</a></td>';
		$tblCampaigns .= '<td>|' . $row["RecordID"] . '</td>';
		$tblCampaigns .= '<td>|' . $row["NavisionCode"] . '</td>';
		$tblCampaigns .= '<td>|' . $row["CampaignName"] . '</td>';
		$tblCampaigns .= '<td>|' . $row["StartDate"] . '</td>';
		$tblCampaigns .= '<td>|' . $row["EndDate"] . '</td>';
		$tblCampaigns .= '<td>|' . $row["IsActive"] . '</td>';
		$tblCampaigns .= '<td>|' . $row["Location"] . '</td>';
  
        // GMC - 02/03/14 - Add Discount to tblCampaigns
		$tblCampaigns .= '<td>|' . $row["Discount"] . '</td>';

		$tblCampaigns .= '</tr>';
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    // Now Present the Information
    include("revitalash_campaigns.php");
}

// GMC - 07/28/11  - Monthly Newsletters
else if ($_POST['radDBMaint'] == 'monthly_newsletters')
{
    // Load the DB Object with the table information
	$tblMonthlyNewsletters = '';
	$strSQL = "SELECT * FROM tblMonthlyNews";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY CUSTOMER RECORDS
	$qryGetMonthlyNews = mssql_query($strSQL);

	while($row = mssql_fetch_array($qryGetMonthlyNews))
	{
		$tblMonthlyNewsletters .= '<tr class="tdwhite">';
		$tblMonthlyNewsletters .= '<td>|' . $row["RecordID"] . '</td>';
		$tblMonthlyNewsletters .= '<td>|' . $row["CustomerID"] . '</td>';
		$tblMonthlyNewsletters .= '<td>|' . $row["FirstName"] . '</td>';
		$tblMonthlyNewsletters .= '<td>|' . $row["LastName"] . '</td>';
		$tblMonthlyNewsletters .= '<td>|' . $row["EMailAddress"] . '</td>';
		$tblMonthlyNewsletters .= '<td>|' . $row["IsExtracted"] . '</td>';
		$tblMonthlyNewsletters .= '</tr>';
	}

    $tblMonthlyNewslettersFile = '';
	$strSQL = "SELECT * FROM tblMonthlyNews where IsExtracted = 0";
	$qryGetMonthlyNewslettersFile = mssql_query($strSQL);

    $myFile = "c:\\inetpub\\wwwroot\csradmin\\tblMonthlyNewsletters.csv";
    $fh = fopen($myFile, 'w') or die("can't open file");
    $tblMonthlyNewslettersHead = "RecordId,CustomerID,FirstName,LastName,EMailAddress\n";
    fwrite($fh, $tblMonthlyNewslettersHead);

	while($row = mssql_fetch_array($qryGetMonthlyNewslettersFile))
	{
        $tblMonthlyNewslettersFile = $row["RecordID"] . ',';
        $tblMonthlyNewslettersFile .= $row["CustomerID"] . ',';
		$tblMonthlyNewslettersFile .= $row["FirstName"] . ',';
		$tblMonthlyNewslettersFile .= $row["LastName"] . ',';
		$tblMonthlyNewslettersFile .= $row["EMailAddress"] . ',';
        $tblMonthlyNewslettersFile .= "\n";
        fwrite($fh, $tblMonthlyNewslettersFile);
        $tblMonthlyNewslettersFile = "";
	}

    fclose($fh);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    // Define the SQL Statement
	$strSQL = "UPDATE tblMonthlyNews SET IsExtracted = 'True' where IsExtracted = 'False'";

	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);

	// QUERY RECORDS
	$qryUpdateMonthlyNews = mssql_query($strSQL);

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);

    // Now Present the Information
    include("monthly_news.php");
}
}

?>

</body>

</html>
