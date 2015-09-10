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
        /*
        // GMC - 01/29/09 - Activate Hair in Shopping Cart
	    // $intItemID = $_GET['AddToCart'];
        // GMC - 09/12/10 - Replace NAV 045 (100) for NAV 432 (285) and NAV   (239) for NAV   (277)
        // GMC - 09/14/10 - Replace NAV 432 (285) for NAV 351 (286) and NAV 180 (150) for NAV 363 (281)
        // GMC - 10/01/10 - Replace NAV 351 (286) for NAV 443 (269)
        // GMC - 06/19/11 - Replace New Products Advanced Formula
        // $intItemID = 100;
        // $intItemID = 285;
        // $intItemID = 286;
        // $intItemID = 269;
        $intItemID = 343;

	    // INCREMENT IF EXISTS IN CART ALREADY
	    if (array_key_exists($intItemID, $_SESSION['cart']))
	    {
		    $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		    mssql_select_db($dbName, $connSalesLimit);

		    // EXECUTE SQL QUERY
		    $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		    while($row = mssql_fetch_array($result))
		    {
			    if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
                {
                    // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
                    // $_SESSION['cart'][$intItemID] += 1;
                }
                else
                {
				    $carterror = 'You mave reached the maximum amount of items for this product.';
			    }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 03/29/10 - New Revitalash 2.4ml
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // GMC - 09/12/10 - Replace NAV 045 (100) for NAV 432 (285) and NAV   (239) for NAV   (277)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // $intItemID = 239; // Production
         // $intItemID = 151; // Test
         // $intItemID = 277; // Production
         $intItemID = 344; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 01/26/10 - New RevitaBrow
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - 08/26/11 - Replace Nav 615 - 364 - 348 for Nav 602 - 392 - 395
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 232; // Production
         // $intItemID = 364; // Production
         $intItemID = 392; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 04/03/09 - Add Mascara Product to Shopping Cart - Consumer
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 158; // Production
         $intItemID = 339; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }
         
         // GMC - 12/08/09 - New Mascara Espresso
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // $intItemID = 151; // Test
         // $intItemID = 217; // Production
         $intItemID = 367; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // GMC - 09/14/10 - Replace NAV 432 (285) for NAV 351 (286) and NAV 180 (150) for NAV 363 (281)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - 09/22/11 - Replace NAV 616 (381 (D) - 382 (I)) for Nav 628 (459 (D) - NO INT))
         // $intItemID = 150; // Production
         // $intItemID = 145; // Test
         // $intItemID = 281; // Production
         // $intItemID = 381; // Production
         $intItemID = 459; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		      }

		      mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 11/02/10 - 2010 Holiday Gift Set (RecordID = 288)
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 288; // Production
         // $intItemID = 183; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		      }

		      mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 04/11/11 - Perfect Primer (Rec ID 321 - 319 - NAV 480) - Mothers Day 2011 Gift Box (Rec ID 320 - 315 - NAV 505)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 321; // Production
         $intItemID = 341; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 320; // Production
         // $intItemID = 151; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 10/29/11 - 2011 Holiday Gift Set
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 484; // Production
         // $intItemID = 183; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		      }

		      mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }
         */
         
         // GMC - 01/24/12 - Control Products thru tblProducts - Consumer Web
		 $connProductsUS = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		 mssql_select_db($dbName, $connProductsUS);

		 // EXECUTE SQL QUERY
		 $resultUS = mssql_query("SELECT RecordID FROM tblProducts WHERE IsActive = 'True' AND IsDomestic = 'True' AND IsConsumer = 'True' AND CategoryID = 1");

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

		 // EXECUTE SQL QUERY
		 $resultNonUS = mssql_query("SELECT RecordID FROM tblProducts WHERE IsActive = 'True' AND IsDomestic = 'False' AND IsConsumer = 'True' AND CategoryID = 1");

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

    // GMC - 01/24/12 - Control Products thru tblProducts - Consumer Web
    /*
    else if($_SESSION['Product_Shipping_Country_Retail'] == 'EU')
    {
        // GMC - 01/29/09 - Activate Hair in Shopping Cart
	    // $intItemID = $_GET['AddToCart'];
        // GMC - 05/11/10 - Replace NAV 165 (143) for NAV 056 (125)
        // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
        // GMC - 11/22/10 - Replace NAV 45(100-107) for NAV 443(269-285) and NAV 373(239-240) for NAV 432(277-278)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
        // $intItemID = 143; // Production
        // $intItemID = 125; // Production
        // $intItemID = 107; // Production
        // $intItemID = 100; // Test
        // $intItemID = 285; // Production
        $intItemID = 345; // Production

	    // INCREMENT IF EXISTS IN CART ALREADY
	    if (array_key_exists($intItemID, $_SESSION['cart']))
	    {
		    $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		    mssql_select_db($dbName, $connSalesLimit);

		    // EXECUTE SQL QUERY
		    $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		    while($row = mssql_fetch_array($result))
		    {
			    if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
                {
                    // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
                    // $_SESSION['cart'][$intItemID] += 1;
                }
                else
                {
				    $carterror = 'You mave reached the maximum amount of items for this product.';
			    }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 03/29/10 - New Revitalash 2.4ml
         // GMC - 11/22/10 - Replace NAV 45(100-107) for NAV 443(269-285) and NAV 373(239-240) for NAV 432(277-278)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 240; // Production
         // $intItemID = 151; // Test
         // $intItemID = 278; // Production
         $intItemID = 346; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 01/26/10 - New RevitaBrow
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - 08/26/11 - Replace Nav 615 - 364 - 348 for Nav 602 - 392 - 395
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 234; // Production
         // $intItemID = 348; // Production
         $intItemID = 395; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 04/03/09 - Add Mascara Product to Shopping Cart - Consumer
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 158; // Production
         $intItemID = 340; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }
         
         // GMC - 12/08/09 - New Mascara Espresso
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 219; // Production
         $intItemID = 380; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // GMC - 08/12/10 - Replace NAV 210 (153) for NAV 180 (150)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // $intItemID = 153; // Production
         // $intItemID = 145; // Test
         // $intItemID = 150; // Production
         // GMC - 09/22/11 - Replace NAV 616 (381 (D) - 382 (I)) for Nav 628 (459 (D) - NO INT))
         /*
         $intItemID = 382; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		      }

		      mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }
         */

         // GMC - 01/24/12 - Control Products thru tblProducts - Consumer Web
         /*
         // GMC - 04/11/11 - Perfect Primer (Rec ID 321 - 319 - NAV 480) - Mothers Day 2011 Gift Box (Rec ID 320 - 315 - NAV 505)
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 319; // Production
         // $intItemID = 151; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 315; // Production
         // $intItemID = 151; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 10/29/11 - 2011 Holiday Gift Set
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 485; // Production
         // $intItemID = 183; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		      }

		      mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }
    }

    // GMC - 10/06/09 - To adjust the Billing/Shipping Entry for Ireland
    else if($_SESSION['Product_Shipping_Country_Retail'] == 'IE')
    {
        // GMC - 01/29/09 - Activate Hair in Shopping Cart
	    // $intItemID = $_GET['AddToCart'];

        // GMC - 05/11/10 - Replace NAV 165 (143) for NAV 056 (125)
        // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
        // GMC - 11/22/10 - Replace NAV 45(100-107) for NAV 443(269-285) and NAV 373(239-240) for NAV 432(277-278)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
        // $intItemID = 143; // Production
        // $intItemID = 125; // Production
        // $intItemID = 107; // Production
        // $intItemID = 100; // Test
        // $intItemID = 285; // Production
        $intItemID = 345; // Production

	    // INCREMENT IF EXISTS IN CART ALREADY
	    if (array_key_exists($intItemID, $_SESSION['cart']))
	    {
		    $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		    mssql_select_db($dbName, $connSalesLimit);

		    // EXECUTE SQL QUERY
		    $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		    while($row = mssql_fetch_array($result))
		    {
			    if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
                {
                    // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
                    // $_SESSION['cart'][$intItemID] += 1;
                }
                else
                {
				    $carterror = 'You mave reached the maximum amount of items for this product.';
			    }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 03/29/10 - New Revitalash 2.4ml
         // GMC - 11/22/10 - Replace NAV 45(100-107) for NAV 443(269-285) and NAV 373(239-240) for NAV 432(277-278)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 240; // Production
         // $intItemID = 151; // Test
         // $intItemID = 278; // Production
         $intItemID = 346; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 01/26/10 - New RevitaBrow
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - 08/26/11 - Replace Nav 615 - 364 - 348 for Nav 602 - 392 - 395
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 234; // Production
         // $intItemID = 348; // Production
         $intItemID = 395; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 04/03/09 - Add Mascara Product to Shopping Cart - Consumer
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 158; // Production
         $intItemID = 340; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 12/08/09 - New Mascara Espresso
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 219; // Production
         $intItemID = 380; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // GMC - 08/12/10 - Replace NAV 210 (153) for NAV 180 (150)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // $intItemID = 153; // Production
         // $intItemID = 145; // Test
         // $intItemID = 150; // Production
         $intItemID = 382; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		      }

		      mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 04/11/11 - Perfect Primer (Rec ID 321 - 319 - NAV 480) - Mothers Day 2011 Gift Box (Rec ID 320 - 315 - NAV 505)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 319; // Production
         $intItemID = 342; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 315; // Production
         // $intItemID = 151; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 10/29/11 - 2011 Holiday Gift Set
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 485; // Production
         // $intItemID = 183; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		      }

		      mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }
    }

    else if($_SESSION['Product_Shipping_Country_Retail'] == 'NOT_EU')
    {
        // GMC - 01/29/09 - Activate Hair in Shopping Cart
	    // $intItemID = $_GET['AddToCart'];

        // GMC - 08/12/10 - Replace NAV 056 (125) for NAV 045 (107)
        // GMC - 11/22/10 - Replace NAV 45(100-107) for NAV 443(269-285) and NAV 373(239-240) for NAV 432(277-278)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
        // $intItemID = 125; // Production
        // $intItemID = 107; // Production
        // $intItemID = 100; // Test
        // $intItemID = 285; // Production
        $intItemID = 345; // Production

	    // INCREMENT IF EXISTS IN CART ALREADY
	    if (array_key_exists($intItemID, $_SESSION['cart']))
	    {
		    $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		    mssql_select_db($dbName, $connSalesLimit);

		    // EXECUTE SQL QUERY
		    $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		    while($row = mssql_fetch_array($result))
		    {
			    if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
                {
                    // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
                    // $_SESSION['cart'][$intItemID] += 1;
                }
                else
                {
				    $carterror = 'You mave reached the maximum amount of items for this product.';
			    }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 03/29/10 - New Revitalash 2.4ml
         // GMC - 11/22/10 - Replace NAV 45(100-107) for NAV 443(269-285) and NAV 373(239-240) for NAV 432(277-278)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 240; // Production
         // $intItemID = 151; // Test
         // $intItemID = 278; // Production
         $intItemID = 346; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 01/26/10 - New RevitaBrow
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - 08/26/11 - Replace Nav 615 - 364 - 348 for Nav 602 - 392 - 395
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 234; // Production
         // $intItemID = 348; // Production
         $intItemID = 395; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 04/03/09 - Add Mascara Product to Shopping Cart - Consumer
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 158; // Production
         $intItemID = 340; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }
         
         // GMC - 12/08/09 - New Mascara Espresso
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 219; // Production
         $intItemID = 380; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // GMC - 08/12/10 - Replace NAV 210 (153) for NAV 180 (150)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // $intItemID = 153; // Production
         // $intItemID = 145; // Test
         // $intItemID = 150; // Production
         $intItemID = 382; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		      }

		      mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 04/11/11 - Perfect Primer (Rec ID 321 - 319 - NAV 480) - Mothers Day 2011 Gift Box (Rec ID 320 - 315 - NAV 505)
         // GMC - 06/19/11 - Replace New Products Advanced Formula
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         // $intItemID = 151; // Test
         // $intItemID = 319; // Production
         $intItemID = 342; // Production

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 315; // Production
         // $intItemID = 151; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		     }

		     mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }

         // GMC - 10/29/11 - 2011 Holiday Gift Set
         // GMC - WARNING - CHANGE WHEN IN PRODUCTION
         $intItemID = 485; // Production
         // $intItemID = 183; // Test

	     // INCREMENT IF EXISTS IN CART ALREADY
	     if (array_key_exists($intItemID, $_SESSION['cart']))
	     {
		     $connSalesLimit = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		     mssql_select_db($dbName, $connSalesLimit);

		     // EXECUTE SQL QUERY
		     $result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $intItemID);

		     while($row = mssql_fetch_array($result))
		     {
			     if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$intItemID])
			     {
			         // GMC - 04/28/09 - Avoid Increase of Item in Drop Down
				     // $_SESSION['cart'][$intItemID] += 1;
			     }
			     else
			     {
				     $carterror = 'You mave reached the maximum amount of items for this product.';
			     }
		      }

		      mssql_close($connSalesLimit);
	     }
	     else
	     {
		     $_SESSION['cart'][$intItemID] = 0;
         }
    }
    */
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
				
				mssql_close($connSalesLimit);
			}
		}

	}
}
	
?>
