<h1>Revitalash Products</h1>

<form method="post" action="/csradmin/dbmaint_main.php">

<!-- GMC - 12/18/08 - Add Telephone and join FirstName + LastName = Contact Search
<p>Below is a list of the most recent customers. Please click on a customer to place a new order or edit the customer information.</p>
-->

<!-- GMC - 07/13/11 - Navision Search for Products -->
<p>
Below is a list of current Products.<br>
If you want to "ADD" a Record to the Products Table, click <a href="revitalash_products_add.php">here</a>.<br>
If you want to "EDIT" or "DELETE" a Record from the Products Table (One Record at a time), select the record with the proper link and click on it.<br>
If you want to "OPEN A CSV FILE" reflecting the Products table right click and save target locally from <a href="tblProducts.csv">here</a><br>
If you want to "SEARCH" for a particular product, enter value and click on search link <input type="text" name="NavSearch" size="10" />&nbsp;&nbsp;<input type="submit" name="nav_search" value="Search" /><br>
If you want to "SEARCH" for a particular record id, enter value and click on search link <input type="text" name="RecSearch" size="10" />&nbsp;&nbsp;<input type="submit" name="rec_search" value="Search" />
</p>

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">

    <tr class="tdwhite" style="font-weight:bold;">
        <td width="2">Edit</td>
        <td width="2">Delete</td>
        
        <!-- GMC - 07/09/09 - Changes by JS -->
        <!-- GMC - 11/11/10 - Put back RecordID by JS -->
        <td width="2">Record ID</td>

        <!-- GMC - 07/22/11 - Take certain fields from tblProducts Display -->
        <!--<td width="2">ProductName</td>-->
        
        <td width="2">PartNumber</td>

        <!--<td width="2">ListingDescription</td>-->

        <td width="2">CartDescription</td>

        <!--<td width="2">LongDescription</td>-->

        <td width="2">RetailPrice</td>
        <td width="2">ResellerPrice</td>
        <td width="2">DistributorPrice</td>
        <td width="2">Size</td>
        <td width="2">Weight</td>

        <!-- GMC - 11/03/14 - Take out Int Sur - Res Fre - Dst Fre - Crt Thm - Gal Thm - Pro Img -->
        <!--<td width="2">InternationalSurcharge</td>-->
        <!--<td width="2">ResellerFreeTriger</td>-->
        <!--<td width="2">DistributorFreeTrigger</td>-->
        <!--<td width="2">CartThumbnail</td>-->
        <!--<td width="2">GalleryThumbnail</td>-->
        <!--<td width="2">ProductImage</td>-->

        <td width="2">CategoryID</td>
        <td width="2">SalesLimit</td>
        <td width="2">IsShowCSRPrice</td>
        <td width="2">IsConsumer</td>

        <!-- GMC - 11/03/14 - Take out IsPro -->
        <!--<td width="2">IsPro</td>-->

        <td width="2">IsActive</td>
        <td width="2">IsDomestic</td>
        <td width="2">IsRep</td>

        <!-- GMC - 07/14/10 - Include Box Values in tblProducts-->
        <!-- GMC - 07/26/10 - Change order of box fields -->
        <td width="2">BoxCount</td>
        <td width="2">BoxWeigth</td>
        <td width="2">BoxLength</td>
        <td width="2">BoxWidth</td>
        <td width="2">BoxHeight</td>
        
        <!-- GMC - 11/08/11 - Include MinimumQty in tblProducts -->
        <td width="2">MinimumQty</td>

        <!-- GMC - 11/02/12 - Include StateExclusion in tblProducts Maintenance process -->
        <td width="2">StateExclusion</td>

        <!-- GMC - 12/27/12 - Include UserIDExclusion in tblProducts Maintenance process -->
        <td width="2">UserIDExclusion</td>

        <!-- GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) -->
        <td width="2">CountryExclusion</td>

        <!-- GMC - 06/11/13 - Special Discount Process System -->
        <td width="2">ItemDiscount</td>
        <td width="2">DiscountValue</td>

        <!-- GMC - 01/19/14 - Discount Promo Code International Items -->
        <td width="2">DiscountPromoCode</td>
        <td width="2">StartDate</td>
        <td width="2">EndDate</td>

    </tr>

    <?php echo $tblRevitalashProducts; ?>

    </table>

</div>

</form>
