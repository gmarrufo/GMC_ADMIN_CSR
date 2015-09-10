<?php

if ((!isset($_SESSION['IsRevitalashLoggedIn'])) || ($_SESSION['IsRevitalashLoggedIn'] == 0))
{
    // GMC - 12/03/08 - Domestic vs International 3rd Phase
    // GMC - 09/20/12 - Change Admin Banner plus content
    // echo '<div><img src="/csradmin/images/bg_masthead.gif" alt="Revitalash Administration" width="950" height="91" /></div>';
    echo '<div><img src="/csradmin/images/admin_banner.jpg" alt="Revitalash Administration" width="950" height="91" /></div>';

    echo '<div id="topmenu">
	
            <!-- GMC - 12/03/08 - Domestic vs International 3rd Phase
			<a href="index.php">Not Logged In</a>
            -->

    	</div>';
}
else
{
    // GMC - 12/03/08 - Domestic vs International 3rd Phase
    if ($_SESSION['UserTypeID'] == 1)
    {
         // GMC - 09/20/12 - Change Admin Banner plus content
         // echo '<div><img src="/csradmin/images/bg_masthead_reps.gif" alt="Revitalash Administration" width="950" height="91" /></div>';
         echo '<div><img src="/csradmin/images/admin_banner.jpg" alt="Revitalash Administration" width="950" height="91" /></div>';
    }
    else if ($_SESSION['UserTypeID'] == 2)
    {
         // GMC - 09/20/12 - Change Admin Banner plus content
         // echo '<div><img src="/csradmin/images/bg_masthead_csrs.gif" alt="Revitalash Administration" width="950" height="91" /></div>';
         echo '<div><img src="/csradmin/images/admin_banner.jpg" alt="Revitalash Administration" width="950" height="91" /></div>';
    }

    // GMC - 03/17/14 - New User ID 3 Sales Specialist
    else if ($_SESSION['UserTypeID'] == 3)
    {
         echo '<div><img src="/csradmin/images/admin_banner.jpg" alt="Revitalash Administration" width="950" height="91" /></div>';
    }

    // GMC - 09/29/14 - UnWash in Admin
    else if ($_SESSION['UserTypeID'] == 4)
    {
         echo '<div><img src="/csradmin/images/admin_banner.jpg" alt="Revitalash Administration" width="950" height="91" /></div>';
    }

	if ($_SERVER["SCRIPT_NAME"] == '/csradmin/orders.php')
	{
	   echo '<div id="topmenu">

        <!-- GMC - 11/18/08 - To Take Out Admin Home Page from Options
        <a href="/csradmin/index.php">Admin Home Page</a>
        -->

		<a href="/csradmin/customers.php">Customer Management</a>
        <a href="/csradmin/orders.php">Order Management</a>
        <a href="/csradmin/index.php?logout">Log Out</a>
		</div>';

		echo '<div id="submenu">
			<a href="orders.php?Action=Overview">Recent Orders</a>
			<a href="orders.php?Action=Tradeshow">Tradeshow Orders</a>

            <!--GMC - 04/24/14 - Activate Orders Summary for Selected Users-->
            <!--GMC - 03/31/14 - Create Order Summary for CSRADMIN -->
			<!--<a href="orders.php?Action=Summary">Orders Summary (Entered by)</a>-->
            <!--GMC - 04/09/14 - Create Order Summary - SalesRepID for CSRADMIN -->
			<!--<a href="orders.php?Action=SummarySalesRepId">Orders Summary (SalesRepID)</a>-->';

            if($_SESSION['UserID'] == "41" || $_SESSION['UserID'] == "44" || $_SESSION['UserID'] == "133" || $_SESSION['UserID'] == "89" || $_SESSION['UserID'] == "55" || $_SESSION['UserID'] == "164" || $_SESSION['UserID'] == "93")
            {
                echo '<a href="orders.php?Action=Summary">Orders Summary (Entered by)</a><a href="orders.php?Action=SummarySalesRepId">Orders Summary (SalesRepID)</a>';
            }

            // GMC - 06/17/13 - Use NavisionUserID instead of UserTypeID to trigger CRM Lead Management
            // GMC - 05/23/13 - CRM LM System
            // if ($_SESSION['UserTypeID'] == 1)
            // CONNECT TO SQL SERVER DATABASE
            $connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
            $selected = mssql_select_db($dbName, $connCustomers);
            $getUserNAVId = mssql_query("SELECT NavisionUserID FROM tblRevitalash_Users WHERE RecordID = " . $_SESSION['UserID'] . "");
            while($row = mssql_fetch_array($getUserNAVId))
            {
                $NavisionUserID = $row["NavisionUserID"];
            }
            if ((isset($NavisionUserID)) || ($NavisionUserID != ""))
            {
                echo '<div id="submenu"><a href="customers.php?Action=CRMSummary">CRM - LEAD Management</a></div>';
            }
            // CLOSE DATABASE CONNECTION
            mssql_close($connCustomers);

		echo '</div>';
	}
	else if ($_SERVER["SCRIPT_NAME"] == '/csradmin/customers.php')
	{
		if (isset($_GET['CustomerID']))
		{
  	       echo '<div id="topmenu">

           <!-- GMC - 11/18/08 - To Take Out Admin Home Page from Options
           <a href="/csradmin/index.php">Admin Home Page</a>
           -->

		   <a href="/csradmin/customers.php">Customer Management</a>
           <a href="/csradmin/orders.php">Order Management</a>
           <a href="/csradmin/index.php?logout">Log Out</a>
		   </div>';

            // GMC - 12/03/08 - Domestic vs International 3rd Phase
            /*
			echo '<div id="submenu">

                <!--  GMC - 11/16/08 - Domestic Vs. International 2nd Phase
				<a href="customers.php?Action=Index">Customer Overview</a>
                -->

                <!-- GMC - 12/03/08 - Domestic vs International 3rd Phase
            	<a href="customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '">Create New Order</a>
                -->
                
                <!--  GMC - 11/16/08 - Domestic Vs. International 2nd Phase -->
				<a href="customers.php?Action=SelectAddress&CustomerID=' . $_GET['CustomerID'] . '">Select Shipping Address</a>';

				if ($_SESSION['UserTypeID'] != 1)
                {
                    // GMC - 11/18/08 - Eliminate Miscellaneous (Edit Customer + Select Shipping Address)
					// echo '<a href="customers.php?Action=EditProfile&CustomerID=' . $_GET['CustomerID'] . '">Edit Customer Information</a>';
				}
				
			echo '</div>';
			*/

            // GMC - 07/10/09 - Reactivate Edit Customer Option for JS Only
            // GMC - 07/21/09 - Reactivate Edit Customer Option for Tina Felix Only (by JS)
            // GMC - 08/25/09 - Reactivate Edit Customer Option for Herlinda Cordova (by JS)
            if ($_GET['Action'] == "Detail")
            {
			   echo '<div id="submenu">';
			   
               // GMC - 02/18/10 - Add WPowers to Edit Customer Information
               // GMC - 03/21/12 - Make Edit Shipping Information available to all CSR-Reps
               // GMC - 03/22/12 - Make Edit Shipping Information available to all CSR and delete only for JStancarone
               // if ($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 41 || $_SESSION['UserID'] == 49)
               // if ($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 41 || $_SESSION['UserID'] == 49 || $_SESSION['UserID'] == 92)
               // {

               // GMC - 03/17/14 - New User ID 3 Sales Specialist
               // if ($_SESSION['UserTypeID'] == 2)
               if ($_SESSION['UserTypeID'] == 2 || $_SESSION['UserTypeID'] == 3)
               {
                      // GMC - 05/27/10 - Edit Shipping Information in CSRADMIN
			          // echo '<a href="customers.php?Action=EditProfile&CustomerID=' . $_GET['CustomerID'] . '">Edit Customer Information</a>';
			          echo '<a href="customers.php?Action=EditProfile&CustomerID=' . $_GET['CustomerID'] . '">Edit Customers Shipping Information</a>';
               }

               // }

               // GMC - 05/15/10 - To avoid continue purchase process if customer type is invalid
               while($rowGetCustomer = mssql_fetch_array($qryGetCustomer_Masthead))
               {
                  // GMC - 07/02/15 - Credit Hold for Customers
                  if ($rowGetCustomer["CustomerStatus"] == "Credit Hold")
                  {
                      echo '<font color="red"><b>WARNING! -- CUSTOMER ON CREDIT HOLD -- WARNING!</b></font>';
                  }
                  else
                  {
	                 if ($rowGetCustomer["CustomerTypeID"] == 1)
	                 {
	    	            echo '<a href="customers.php?Action=SelectAddress&CustomerID=' . $_GET['CustomerID'] . '">Select Shipping Address</a>';
                     }
                     elseif ($rowGetCustomer["CustomerTypeID"] == 2)
                     {
                        echo '<a href="customers.php?Action=SelectAddress&CustomerID=' . $_GET['CustomerID'] . '">Select Shipping Address</a>';
                     }
                     elseif ($rowGetCustomer["CustomerTypeID"] == 3)
                     {
	    	            echo '<a href="customers.php?Action=SelectAddress&CustomerID=' . $_GET['CustomerID'] . '">Select Shipping Address</a>';
                     }
                     elseif ($rowGetCustomer["CustomerTypeID"] == 4)
                     {
    	                echo '<a href="customers.php?Action=SelectAddress&CustomerID=' . $_GET['CustomerID'] . '">Select Shipping Address</a>';
                     }
                     else
                     {

                     }
                  }
               }

               echo '</div>';
            }
            else if($_GET['Action'] == "SelectAddress")
            {
            }
            else
            {
                 // GMC - 02/18/10 - Add WPowers to Edit Customer Information
                 // GMC - 03/21/12 - Make Edit Shipping Information available to all CSR-Reps
                 // GMC - 03/22/12 - Make Edit Shipping Information available to all CSR and delete only for JStancarone
			     // if ($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 41)
			     // if ($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 41 || $_SESSION['UserID'] == 92)
                 // {

                 // GMC - 03/17/14 - New User ID 3 Sales Specialist
                 // if ($_SESSION['UserTypeID'] == 2)
                 if ($_SESSION['UserTypeID'] == 2 || $_SESSION['UserTypeID'] == 3)
                 {
			          echo '<div id="submenu">';

                      // GMC - 05/27/10 - Edit Shipping Information in CSRADMIN
			          // echo '<a href="customers.php?Action=EditProfile&CustomerID=' . $_GET['CustomerID'] . '">Edit Customer Information</a>';
			          echo '<a href="customers.php?Action=EditProfile&CustomerID=' . $_GET['CustomerID'] . '">Edit Customers Shipping Information</a>';

			          echo '</div>';
                 }
                 
                // }

                // GMC - 06/17/13 - Use NavisionUserID instead of UserTypeID to trigger CRM Lead Management
                // GMC - 05/23/13 - CRM LM System
                // if ($_SESSION['UserTypeID'] == 1)
                // CONNECT TO SQL SERVER DATABASE
                $connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
                $selected = mssql_select_db($dbName, $connCustomers);
                $getUserNAVId = mssql_query("SELECT NavisionUserID FROM tblRevitalash_Users WHERE RecordID = " . $_SESSION['UserID'] . "");
                while($row = mssql_fetch_array($getUserNAVId))
                {
                    $NavisionUserID = $row["NavisionUserID"];
                }
                if ((isset($NavisionUserID)) || ($NavisionUserID != ""))
                {
                    echo '<div id="submenu"><a href="customers.php?Action=CRMSummary">CRM - LEAD Management</a></div>';
                }
                // CLOSE DATABASE CONNECTION
                mssql_close($connCustomers);
            }
		}
		else
		{
	        echo '<div id="topmenu">

            <!-- GMC - 11/18/08 - To Take Out Admin Home Page from Options
            <a href="/csradmin/index.php">Admin Home Page</a>
            -->

		    <a href="/csradmin/customers.php">Customer Management</a>
            <a href="/csradmin/orders.php">Order Management</a>
            <a href="/csradmin/index.php?logout">Log Out</a>
		    </div>';

            // GMC - 12/03/08 - Domestic vs International 3rd Phase
            /*
			echo '<div id="submenu">

                <!--  GMC - 11/16/08 - Domestic Vs. International 2nd Phase
				<a href="customers.php?Action=Index">Customer Overview</a>
                -->

                <!-- GMC - 12/03/08 - Domestic vs International 3rd Phase
            	<a href="customers.php?Action=NewOrder" onclick="alert(\'You must have a customer selected to choose this option\'); return false;">Create New Order</a>
                -->
                
                <!-- GMC - 11/18/08 - Eliminate Miscellaneous (Edit Customer + Select Shipping Address)
				<a href="customers.php?Action=EditProfile" onclick="alert(\'You must have a customer selected to choose this option\'); return false;">Edit Customer Information</a>
                -->
                
			</div>';
			*/
			
            // GMC - 07/10/09 - Reactivate Edit Customer Option for JS Only
            // GMC - 03/21/12 - Make Edit Shipping Information available to all CSR-Reps
            // GMC - 03/22/12 - Make Edit Shipping Information available to all CSR and delete only for JStancarone
			// if ($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 41)
            // {
                // GMC - 05/27/10 - Edit Shipping Information in CSRADMIN
                /*
                echo '<div id="submenu">
				<a href="customers.php?Action=EditProfile" onclick="alert(\'You must have a customer selected to choose this option\'); return false;">Edit Customer Information</a>
			    </div>';
                */
                
                 // GMC - 03/17/14 - New User ID 3 Sales Specialist
                 // if ($_SESSION['UserTypeID'] == 2)
                 if ($_SESSION['UserTypeID'] == 2 || $_SESSION['UserTypeID'] == 3)
                 {
			         echo '<div id="submenu">
				     <a href="customers.php?Action=EditProfile" onclick="alert(\'You must have a customer selected to choose this option\'); return false;">Edit Customers Shipping Information</a>
			         </div>';
                 }

                // GMC - 06/17/13 - Use NavisionUserID instead of UserTypeID to trigger CRM Lead Management
                // GMC - 05/23/13 - CRM LM System
                // if ($_SESSION['UserTypeID'] == 1)
                // CONNECT TO SQL SERVER DATABASE
                $connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
                $selected = mssql_select_db($dbName, $connCustomers);
                $getUserNAVId = mssql_query("SELECT NavisionUserID FROM tblRevitalash_Users WHERE RecordID = " . $_SESSION['UserID'] . "");
                while($row = mssql_fetch_array($getUserNAVId))
                {
                    $NavisionUserID = $row["NavisionUserID"];
                }
                if ((isset($NavisionUserID)) || ($NavisionUserID != ""))
                {
                    echo '<div id="submenu"><a href="customers.php?Action=CRMSummary">CRM - LEAD Management</a></div>';
                }
                // CLOSE DATABASE CONNECTION
                mssql_close($connCustomers);

            // }
		}
	}
    else
    {
	   echo '<div id="topmenu">

        <!-- GMC - 11/18/08 - To Take Out Admin Home Page from Options
        <a href="/csradmin/index.php">Admin Home Page</a>
        -->

		<a href="/csradmin/customers.php">Customer Management</a>
        <a href="/csradmin/orders.php">Order Management</a>
		<a href="/csradmin/updatepw.php">Update Password</a>
        <a href="/csradmin/index.php?logout">Log Out</a>
		</div>';

        // GMC - 06/17/13 - Use NavisionUserID instead of UserTypeID to trigger CRM Lead Management
        // GMC - 05/23/13 - CRM LM System
        // if ($_SESSION['UserTypeID'] == 1)
        // CONNECT TO SQL SERVER DATABASE
        $connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
        $selected = mssql_select_db($dbName, $connCustomers);
        $getUserNAVId = mssql_query("SELECT NavisionUserID FROM tblRevitalash_Users WHERE RecordID = " . $_SESSION['UserID'] . "");
        while($row = mssql_fetch_array($getUserNAVId))
        {
            $NavisionUserID = $row["NavisionUserID"];
        }
        if ((isset($NavisionUserID)) || ($NavisionUserID != ""))
        {
            echo '<div id="submenu"><a href="customers.php?Action=CRMSummary">CRM - LEAD Management</a></div>';
        }
        // CLOSE DATABASE CONNECTION
        mssql_close($connCustomers);
    }
}
