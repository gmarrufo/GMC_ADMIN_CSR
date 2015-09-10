<?php 

// CREATE CART IF NOT DEFINED
if (!isset($_SESSION['cart']))
	$_SESSION['cart'] = array();

// GMC - 09/09/09 - Define the Country for Shipping from First Entry
if($Shipping_Country_Retail != 'US')
{
    $connGetShippingRetailCountry = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
    mssql_select_db($dbName, $connGetShippingRetailCountry);

    $result = mssql_query("SELECT IsEU FROM conCountryCodes WHERE CountryCode = '" . $Shipping_Country_Retail . "'");

    while($row = mssql_fetch_array($result))
    {
         if($row["IsEU"] == 1)
         {
             // GMC - 10/06/09 - To adjust the Billing/Shipping Entry for Ireland
             if($Shipping_Country_Retail == "IE")
             {
                 $_SESSION['Product_Shipping_Country_Retail'] = 'IE';
             }
             else
             {
                 $_SESSION['Product_Shipping_Country_Retail'] = 'EU';
             }
         }
         else
         {
             $_SESSION['Product_Shipping_Country_Retail'] = 'NOT_EU';
         }
     }
}
else
{
   $_SESSION['Product_Shipping_Country_Retail'] = 'US';
}

// ADD TO CART FROM LINK
if (isset($_GET['AddToCart']))
{
    // Now declare the proper product ids depending of the shipping country
    if($_SESSION['Product_Shipping_Country_Retail'] == 'US')
    {
         // GMC - 01/24/12 - Control Products thru tblProducts - Consumer Web
		 $connProductsUS = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		 mssql_select_db($dbName, $connProductsUS);

         // GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller
		 // EXECUTE SQL QUERY
		 // $resultUS = mssql_query("SELECT RecordID FROM tblProducts WHERE IsActive = 'True' AND IsDomestic = 'True' AND IsConsumer = 'True' AND CategoryID = 1");
         if($_SESSION['StateExclusion'] == "")
         {
		     $resultUS = mssql_query("SELECT RecordID FROM tblProducts WHERE IsActive = 'True' AND IsDomestic = 'True' AND IsConsumer = 'True' AND CategoryID = 1");
         }
         else
         {
		     $resultUS = mssql_query("SELECT RecordID FROM tblProducts WHERE IsActive = 'True' AND IsDomestic = 'True' AND IsConsumer = 'True' AND CategoryID = 1 AND (StateExclusion NOT LIKE '%" . $_SESSION['StateExclusion'] . "%' or StateExclusion is null)");
         }
         
		 while($rowUS = mssql_fetch_array($resultUS))
		 {
             $intItemID = $rowUS["RecordID"];

             // INCREMENT IF EXISTS IN CART ALREADY
             if (array_key_exists($intItemID, $_SESSION['cart']))
             {
		         $connSalesLimitUS = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		         mssql_select_db($dbName, $connSalesLimitUS);

		         // EXECUTE SQL QUERY
		         $resultUS = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		         while($rowUS = mssql_fetch_array($resultUS))
		         {
			         if($rowUS["SalesLimit"] == '' || $rowUS["SalesLimit"] < $_SESSION['cart'][$intItemID])
			         {
			             // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				         // $_SESSION['cart'][$intItemID] += 1;
			         }
			         else
			         {
				         $carterror = 'You have reached the maximum amount of items for this product.';
			         }
		         }
		         mssql_close($connSalesLimitUS);
	         }
	         else
	         {
		         $_SESSION['cart'][$intItemID] = 0;
             }
		 }
		 mssql_close($connProductsUS);
    }
    else
    {
         // GMC - 01/24/12 - Control Products thru tblProducts - Consumer Web
		 $connProductsNonUS = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		 mssql_select_db($dbName, $connProductsNonUS);

         // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
		 // EXECUTE SQL QUERY
		 // $resultNonUS = mssql_query("SELECT RecordID FROM tblProducts WHERE IsActive = 'True' AND IsDomestic = 'False' AND IsConsumer = 'True' AND CategoryID = 1");
         if($_SESSION['CountryExclusion'] == "")
         {
		     $resultNonUS = mssql_query("SELECT RecordID FROM tblProducts WHERE IsActive = 'True' AND IsDomestic = 'False' AND IsConsumer = 'True' AND CategoryID = 1");
         }
         else
         {
		     $resultNonUS = mssql_query("SELECT RecordID FROM tblProducts WHERE IsActive = 'True' AND IsDomestic = 'False' AND IsConsumer = 'True' AND CategoryID = 1 AND (CountryExclusion NOT LIKE '%" . $_SESSION['CountryExclusion'] . "%' or CountryExclusion is null)");
         }

		 while($rowNonUS = mssql_fetch_array($resultNonUS))
		 {
             $intItemID = $rowNonUS["RecordID"];

             // INCREMENT IF EXISTS IN CART ALREADY
             if (array_key_exists($intItemID, $_SESSION['cart']))
             {
		         $connSalesLimitNonUS = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		         mssql_select_db($dbName, $connSalesLimitNonUS);

		         // EXECUTE SQL QUERY
		         $resultNonUS = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		         while($rowNonUS = mssql_fetch_array($resultNonUS))
		         {
			         if($rowNonUS["SalesLimit"] == '' || $rowNonUS["SalesLimit"] < $_SESSION['cart'][$intItemID])
			         {
			             // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				         // $_SESSION['cart'][$intItemID] += 1;
			         }
			         else
			         {
				         $carterror = 'You have reached the maximum amount of items for this product.';
			         }
		         }
		         mssql_close($connSalesLimitNonUS);
	         }
	         else
	         {
		         $_SESSION['cart'][$intItemID] = 0;
             }
		 }
		 mssql_close($connProductsNonUS);
    }
}

// ADD TO CART FROM FORM
if (isset($_POST['cmdAddToCart']) && isset($_POST['Qty']) && isset($_POST['RecordID']))
{
	$intItemID = $_POST['RecordID'];
	
	if ((eregi('^[0-9]{1,3}$', $_POST['Qty'])) && ($_POST['Qty'] >= 0))
	{
		// INCREMENT IF EXISTS IN CART ALREADY
		if (array_key_exists($intItemID, $_SESSION['cart']))
			$_SESSION['cart'][$intItemID] += $_POST['Qty'];
		else
			$_SESSION['cart'][$intItemID] = $_POST['Qty'];
	}
}

//REMOVE FROM CART
if (isset($_GET['Delete']))
{
	//$intItemToRemove = $_GET['Delete'];
	//$_SESSION['cart'] = array_filter($_SESSION['cart'], 'ToRemove');
	
	unset($_SESSION['cart'][$_GET['Delete']]);
}
	// ADD TO CART FROM FORM
	/*
	if ($_POST['cmdAddItemToCart'] == 'Add To Cart')
	{
		$intQty = $_POST['Qty'];
		
		if ((eregi('^[0-9]{1,3}$', $intQty)) && ($intQty >= 0))
		{
			// INCREMENT IF EXISTS IN CART ALREADY
			if (array_key_exists($item, $_SESSION['cart']))
				$_SESSION['cart'][$item] += $intQty;
			else
			{	
				// DELETE IF QTY IS ZERO
				if ($intQty == 0)
					unset($_SESSION['cart'][$item]);
				// ADD TO CART
				else
					$_SESSION['cart'][$item] = $intQty;
			}
		}
		else
			$_SESSION['error_message'] = 'That was not a valid number and was not entered into your cart';
	}
	*/

// UPDATE CART
if (isset($_POST['cmdUpdate']))
{
     // GMC - 02/11/09 - Empty Cart Issue
     $_SESSION['cart_empty'] = '';

	//Iterates through each item in cart adjusting values from quantity fields
	for (reset($_SESSION['cart']); list($key) = each($_SESSION['cart']);)
	{
		$strFormField = 'q' . $key;
		$intQty = $_POST[$strFormField];
		$item = $key;
		
		if ((eregi('^[0-9]{1,3}$', $intQty)) && ($intQty >= 0))
		{	
			// DELETE IF ZERO; OTHERWISE UPDATE QUANTITY
			if ($_POST[$strFormField] == 0)
				unset($_SESSION['cart'][$item]);
			else
			{
				$connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
				mssql_select_db($dbName, $connSalesLimit);
		
				// EXECUTE SQL QUERY
				$result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $item);
				
				while($row = mssql_fetch_array($result))
				{
					if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$item])
                    {
                        $_SESSION['cart'][$item] = $intQty;

                        // GMC - 01/22/10 - Present Check Out Only when Add Cart has been pressed
                        $_SESSION['cart_empty'] = 'False';
                    }
					else
                    {
						$carterror = 'You mave reached the maximum amount of items for this product.';
                    }
				}

                // GMC - 11/03/12 - State Exclusion for Shopping Cart Applications - Consumer and Reseller
				$resultA = mssql_query("SELECT StateExclusion FROM tblProducts WHERE RecordID = " . $item);

                while($row = mssql_fetch_array($resultA))
				{
                    $StateExclusionSelected = $row["StateExclusion"];
				}

                if (strlen(strstr($StateExclusionSelected,'CA'))>0 || strlen(strstr($StateExclusionSelected,'TN'))>0)
                {
                    $_SESSION['StateExclusionProductSelected'] = 'Yes';
                }

				mssql_close($connSalesLimit);
			}
		}
	}
}
	
?>
