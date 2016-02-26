<?php

if ((!isset($_SESSION['IsRevitalashLoggedIn'])) || ($_SESSION['IsRevitalashLoggedIn'] == 0))
{
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
         echo '<div><img src="/csradmin/images/admin_banner.jpg" alt="Revitalash Administration" width="950" height="91" /></div>';
    }
    else if ($_SESSION['UserTypeID'] == 2)
    {
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

		<a href="/csradmin/customers.php">Customer Management</a>
        <a href="/csradmin/orders.php">Order Management</a>
        <a href="/csradmin/index.php?logout">Log Out</a>
		</div>';

		echo '<div id="submenu">
			<a href="orders.php?Action=Overview">Recent Orders</a>
			<a href="orders.php?Action=Tradeshow">Tradeshow Orders</a>';

            if($_SESSION['UserID'] == "41" || $_SESSION['UserID'] == "44" || $_SESSION['UserID'] == "133" || $_SESSION['UserID'] == "89" || $_SESSION['UserID'] == "55" || $_SESSION['UserID'] == "164" || $_SESSION['UserID'] == "93")
            {
                echo '<a href="orders.php?Action=Summary">Orders Summary (Entered by)</a><a href="orders.php?Action=SummarySalesRepId">Orders Summary (SalesRepID)</a>';
            }

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

		   <a href="/csradmin/customers.php">Customer Management</a>
           <a href="/csradmin/orders.php">Order Management</a>
           <a href="/csradmin/index.php?logout">Log Out</a>
		   </div>';

            if ($_GET['Action'] == "Detail")
            {
			   echo '<div id="submenu">';

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

		    <a href="/csradmin/customers.php">Customer Management</a>
            <a href="/csradmin/orders.php">Order Management</a>
            <a href="/csradmin/index.php?logout">Log Out</a>
		    </div>';

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

		<a href="/csradmin/customers.php">Customer Management</a>
        <a href="/csradmin/orders.php">Order Management</a>
		<a href="/csradmin/updatepw.php">Update Password</a>
        <a href="/csradmin/index.php?logout">Log Out</a>
		</div>';

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
