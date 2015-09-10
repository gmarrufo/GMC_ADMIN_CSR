<?php

// GMC - 11/04/11 - MinimumQty Calculation
$MinQty = 0;

// CREATE CART IF NOT DEFINED
if (!isset($_SESSION['cart']))
	$_SESSION['cart'] = array();

// ADD TO CART FROM LINK
if (isset($_GET['AddToCart']))
{
	$intItemID = $_GET['AddToCart'];

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
			     $_SESSION['cart'][$intItemID] += 1;
            }
            else
            {
			     $carterror = 'You have reached the maximum amount of items for this product.';
		    }
         }

		mssql_close($connSalesLimit);
	}
	else
	{
		$_SESSION['cart'][$intItemID] = 0;
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

                // GMC - 11/04/11 - MinimumQty Calculation
                $qryMinQty = mssql_query("SELECT MinimumQty FROM tblProducts WHERE RecordId = " . $item);
                while($rowMQ = mssql_fetch_array($qryMinQty))
                {
                    $MinQty = $rowMQ['MinimumQty'];
                }

				// EXECUTE SQL QUERY
				$result = mssql_query("SELECT SalesLimit FROM tblProducts WHERE RecordID = " . $item);

				while($row = mssql_fetch_array($result))
				{
				    // if($row["SalesLimit"] == '' || $row["SalesLimit"] < $_SESSION['cart'][$item])
				    if($row["SalesLimit"] == '' || $row["SalesLimit"] < $intQty)
                    {
                        if($MinQty > 0 && $intQty < $MinQty)
                        {
                            $carterror = 'You have not reached the minimum amount of items for this product.';
                        }
                        else
                        {
						    $_SESSION['cart'][$item] = $intQty;
                        }
                    }
					else
                    {
						$carterror = 'You have reached the maximum amount of items for this product.';
                    }
				}

				mssql_close($connSalesLimit);
			}
		}

	}
}

?>
