<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

include("errors.php");

require_once("../modules/session.php");
require_once("../modules/db.php");

// GMC - 07/17/10 - Clean up PO Number
require_once("../modules/replace_spec.php");

// GMC - 12/03/08 - Domestic vs International 3rd Phase
require_once("../modules/escape_string.php");

/*
function mssql_escape_string($string_to_escape){
	$replaced_string = str_replace("'","''",$string_to_escape);
	return $replaced_string;
}
*/

// GMC - 11/13/08 - To accomodate that Trade Shows must not give free goods
$TradeShow = 'False';

// GMC - 03/24/09 - Add *REQUIRED on New Address
$_SESSION['NoNewAddress'] = "";

// GMC - 02/04/16 Modifications for the Edit - Delete - Address Shipping Addresses
$_SESSION['UseDeleteEditAddress'] = "";

// GMC - 07/25/12 - Detect the type of browser so PopUps can be disable
$_SESSION['IEBrowser'] = "";

// GMC - 05/11/15 - Fix LTLShipmentType declaration
$_SESSION['LTLShipmentType'] = "";

$u_agent = $_SERVER['HTTP_USER_AGENT'];
$ub = '';

if(preg_match('/MSIE/i',$u_agent))
{
    $ub = "ie";
}
elseif(preg_match('/Firefox/i',$u_agent))
{
    $ub = "firefox";
}
elseif(preg_match('/Safari/i',$u_agent))
{
    $ub = "safari";
}
elseif(preg_match('/Chrome/i',$u_agent))
{
    $ub = "chrome";
}
elseif(preg_match('/Flock/i',$u_agent))
{
    $ub = "flock";
}
elseif(preg_match('/Opera/i',$u_agent))
{
    $ub = "opera";
}

// GMC - 11/01/13 - Cancel the PopUp for Products
/*
if($ub == "ie")
{
    $_SESSION['IEBrowser'] = "True";
}
else
{
    $_SESSION['IEBrowser'] = "False";
}
*/
$_SESSION['IEBrowser'] = "False";

if (isset($_GET['NavSalesRepID']))
// LOG IN CSR AUTOMATICALLY IF COMING FROM NAVISION
{
	if ((!isset($_SESSION['IsRevitalashLoggedIn'])) || ($_SESSION['IsRevitalashLoggedIn'] == 0))
	{
		// CONNECT TO SQL SERVER DATABASE
		$connLogin = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		$selected = mssql_select_db($dbName, $connLogin);
			
		$qryLogin = mssql_init("spRevitalash_Authenticate", $connLogin);
		
		$intStatusCode = 0;
		
		// Bind the parameters
		mssql_bind($qryLogin, "@prmUsername", $_GET['NavSalesRepID'], SQLVARCHAR);
		
		// Bind the return value
		
		mssql_bind($qryLogin, "RETVAL", $intStatusCode, SQLINT2);

		// EXECUTE QUERY
		$rs = mssql_execute($qryLogin);
		
		if (mssql_num_rows($rs) > 0)
		{
			while($row = mssql_fetch_array($rs))
			{
				$_SESSION['IsRevitalashLoggedIn'] = 1;
				$_SESSION['UserID'] = $row["RecordID"];
				$_SESSION['FirstName'] = $row["FirstName"];
				$_SESSION['LastName'] = $row["LastName"];
				$_SESSION['EMailAddress'] = $row["EMailAddress"];
				$_SESSION['UserTypeID'] = $row["UserTypeID"];
			}
			mssql_next_result($rs);
		}
		
		if ($intStatusCode != 100)
		{
			print('ERROR');//header("Location: login.php");
			exit;
		}
		
		// CLOSE DATABASE CONNECTION
		mssql_close($connLogin);
	}
}
elseif ((!isset($_SESSION['IsRevitalashLoggedIn'])) || ($_SESSION['IsRevitalashLoggedIn'] == 0))
// OTHERWISE REDIRECT TO LOGIN PAGE IF NOT LOGGED IN
{
	header("Location: login.php");
	exit;
}

if ((!isset($_GET['Action'])) || ($_GET['Action'] == 'Index') || ($_GET['Action'] == 'Overview'))
// ********** CUSTOMER LIST **********
{
	// RESET SESSION VARIABLES
	foreach ($_SESSION as $key => $value) {
		if ($key != 'IsRevitalashLoggedIn' && $key != 'UserID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress' && $key != 'UserTypeID')	
		{
			unset($_SESSION[$key]);
		}
	}
	
	// CONNECT TO SQL SERVER DATABASE
	$connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomers);
		
	$qryLogin = mssql_init("spRevitalash_CustomerList", $connCustomers);
	
	$intUserID = $_SESSION['UserID'];
	$intStatusCode = 0;
	$tblCustomerList = '';
	
	// BIND PARAMETERS AND RETURN VALUE
	mssql_bind($qryLogin, "@prmUserID", $intUserID, SQLINT2);
	mssql_bind($qryLogin, "RETVAL", $intStatusCode, SQLINT2);

	// EXECUTE QUERY
	$rs = mssql_execute($qryLogin);
	
	if (mssql_num_rows($rs) > 0)
	{
		while($row = mssql_fetch_array($rs))
		{
			$tblCustomerList .= '<tr class="tdwhite">';
			$tblCustomerList .= '<td>' . $row["NavisionCustomerID"] . '</td>';
			$tblCustomerList .= '<td><a href="/csradmin/customers.php?Action=Detail&CustomerID=' . $row["RecordID"] . '">' . $row["CompanyName"] . '</a></td>';
			$tblCustomerList .= '<td><a href="/csradmin/customers.php?Action=Detail&CustomerID=' . $row["RecordID"] . '">' . $row["FirstName"] . ' ' . $row["LastName"] . '</a></td>';
			$tblCustomerList .= '<td>' . $row["Address1"] . ' ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . '</td>';
			$tblCustomerList .= '<td>' . $row["Telephone"] . '</td>';
			$tblCustomerList .= '</tr>';
		}
		mssql_next_result($rs);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomers);
}
elseif ($_GET['Action'] == 'Detail')
// ********** CUSTOMER DETAIL **********
{
	// RESET SESSION VARIABLES
	foreach ($_SESSION as $key => $value) {
		if ($key != 'IsRevitalashLoggedIn' && $key != 'UserID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress' && $key != 'UserTypeID')	
		{
			unset($_SESSION[$key]);
		}
	}
	
	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);
	
	if (isset($_GET['NavCustomerID']))
	{
		$qryGetNavID = mssql_query("SELECT RecordID FROM tblCustomers WHERE NavisionCustomerID = '" . $_GET['NavCustomerID'] . "'");
		
		while($rowGetNavID = mssql_fetch_array($qryGetNavID))
		{ $intCustomerID = $rowGetNavID["RecordID"]; }
	}
	else
		$intCustomerID = $_GET['CustomerID'];

	// QUERY CUSTOMER RECORDS
	$qryGetCustomer = mssql_query("SELECT * FROM tblCustomers WHERE RecordID = " . $intCustomerID);

    // GMC - 05/15/10 - To avoid continue purchase process if customer type is invalid
	$qryGetCustomer_Masthead = mssql_query("SELECT * FROM tblCustomers WHERE RecordID = " . $intCustomerID);

	$qryGetShipping = mssql_query("SELECT * FROM tblCustomers_ShipTo WHERE CustomerID = " . $intCustomerID . " ORDER BY IsDefault DESC, Attn ASC");
	$qryGetPayment = mssql_query("SELECT * FROM tblCustomers_PayMethods WHERE CustomerID = " . $intCustomerID . " ORDER BY IsDefault DESC");
	$qryOrderList = mssql_init("spCustomers_OrderList", $connCustomer);
	mssql_bind($qryOrderList, "@prmCustomerID", $intCustomerID, SQLINT4);

	// EXECUTE QUERY
	$rs = mssql_execute($qryOrderList);
	
	if (mssql_num_rows($rs) > 0)
	{
		$tblOrderList = '';
		
		while($row = mssql_fetch_array($rs))
		{
			$tblOrderList .= '<tr class="tdwhite">';
			$tblOrderList .= '<td width="*"><a href="/csradmin/orders.php?Action=Detail&OrderID=' . $row["RecordID"] . '">' . $row["RecordID"] . '</a></td>';
			if (strlen($row["CompanyName"]) <= 1 || $row["CompanyName"] == 'Individual')
				$tblOrderList .= '<td width="250">' . $row["FirstName"] . ' ' . $row["LastName"] . '</td>';
			else
				$tblOrderList .= '<td width="250">' . $row["CompanyName"] . '</td>';
			$tblOrderList .= '<td>' . date("F d, Y", strtotime($row["OrderDate"])) . '</td>';
			$tblOrderList .= '<td>' . $row["StatusDisplay"] . '</td>';
			$tblOrderList .= '<td>' . $row["ShippingMethodDisplay"] . '</td>';
			$tblOrderList .= '<td>$' . number_format($row["OrderTotal"], 2, '.', '') . '</td>';
			$tblOrderList .= '</tr>';
		}
		
		mssql_next_result($rs);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);
}

// GMC - 05/23/13 - CRM LM System
elseif ($_GET['Action'] == 'CRMSummary')
// ********** CRM LEAD MANAGEMENT **********
{
    // RESET SESSION VARIABLES
	foreach ($_SESSION as $key => $value) {
		if ($key != 'IsRevitalashLoggedIn' && $key != 'UserID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress' && $key != 'UserTypeID')
		{
			unset($_SESSION[$key]);
		}
	}

	// CONNECT TO SQL SERVER DATABASE
	$connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomers);

	$qryLogin = mssql_init("spRevitalash_CRM_CustomerList", $connCustomers);

    $intUserID = $_SESSION['UserID'];
	$intStatusCode = 0;
	$tblCustomerList = '';

	// BIND PARAMETERS AND RETURN VALUE
    mssql_bind($qryLogin, "@prmUserID", $intUserID, SQLINT2);
	mssql_bind($qryLogin, "RETVAL", $intStatusCode, SQLINT2);

	// EXECUTE QUERY
	$rs = mssql_execute($qryLogin);

	if (mssql_num_rows($rs) > 0)
	{
		while($row = mssql_fetch_array($rs))
		{
			$tblCustomerList .= '<tr class="tdwhite">';
			$tblCustomerList .= '<td><a href="/csradmin/customers.php?Action=CRMDetail&CustomerID=' . $row["RecordID"] . '">Go to Detail</a></td>';
			$tblCustomerList .= '<td>' . $row["NavisionCustomerID"] . '</td>';

            // GMC - 06/14/13 - To prevent for Reps to Place and Order on Non-Active Customers
            // GMC - 07/08/13 - To allow for Reps to Place an Order if Customer is Approved
            if($row["CustomerStatus"] == "Approved")
            {
			    $tblCustomerList .= '<td><a href="/csradmin/customers.php?Action=Detail&CustomerID=' . $row["RecordID"] . '">' . $row["CompanyName"] . '</a></td>';
            }
            else
            {
			    $tblCustomerList .= '<td>' . $row["CompanyName"] . '</td>';
            }

   			$tblCustomerList .= '<td>' . $row["State"] . '</td>';
      
            // GMC - 06/30/14 - Add City and Phone to CRM-Lead Process
   			$tblCustomerList .= '<td>' . $row["City"] . '</td>';

			$tblCustomerList .= '<td>' . $row["Telephone"] . '</td>';

            if($row["LastContactDate"] != '' || $row["LastContactDate"] != NULL)
            {
                $LastContactDate = split(" ", $row["LastContactDate"]);
                $tblCustomerList .= '<td>' . $LastContactDate[0] . ' ' . $LastContactDate[1] . ' ' . $LastContactDate[2] . '</td>';
            }
            else
            {
                $tblCustomerList .= '<td></td>';
            }

			$tblCustomerList .= '<td>' . $row["Disposition"] . '</td>';

            if($row["FollowUpDate"] != '' || $row["FollowUpDate"] != NULL)
            {
                $FollowUpDate = split(" ", $row["FollowUpDate"]);
                $tblCustomerList .= '<td>' . $FollowUpDate[0] . ' ' . $FollowUpDate[1] . ' ' . $FollowUpDate[2] . '</td>';
            }
            else
            {
                $tblCustomerList .= '<td></td>';
            }

            // GMC - 06/27/13 - Add CustomerStatus to CRM-Lead Process
			$tblCustomerList .= '<td>' . $row["CustomerStatus"] . '</td>';

			$tblCustomerList .= '</tr>';
		}
		mssql_next_result($rs);
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomers);
}

elseif ($_GET['Action'] == 'CRMDetail')
// ********** CRM LEAD MANAGEMENT **********
{
    // RESET SESSION VARIABLES
    /*
	foreach ($_SESSION as $key => $value) {
		if ($key != 'IsRevitalashLoggedIn' && $key != 'UserID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress' && $key != 'UserTypeID')
		{
			unset($_SESSION[$key]);
		}
	}
    */
    
	// CONNECT TO SQL SERVER DATABASE
	$connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomers);

	$intCustomerID = $_GET['CustomerID'];
    $_SESSION['CRMCustomerID'] = $intCustomerID;
	$tblCustomerDetail = '';

    $getCustomerNAVId = mssql_query("SELECT NavisionCustomerID, CompanyName FROM tblCustomers WHERE RecordID = " . $intCustomerID . "");
 
    while($row = mssql_fetch_array($getCustomerNAVId))
    {
        $_SESSION['CRM_NavCustomerID'] = $row["NavisionCustomerID"];
        $_SESSION['CRM_CompanyName'] = $row["CompanyName"];
    }

    $getCRMDetail = mssql_query("SELECT LastContactDate, Disposition, FollowUpDate, Comments FROM tblCRM_LOG WHERE CustomerID = " . $intCustomerID . " ORDER BY LastContactDate ASC");

    while($row = mssql_fetch_array($getCRMDetail))
    {
        $tblCustomerDetail .= '<tr class="tdwhite">';

        if($row["LastContactDate"] != '' || $row["LastContactDate"] != NULL)
        {
            $LastContactDate = split(" ", $row["LastContactDate"]);
            $tblCustomerDetail .= '<td width="125">' . $LastContactDate[0] . ' ' . $LastContactDate[1] . ' ' . $LastContactDate[2] . '</td>';
        }
        else
        {
            $tblCustomerDetail .= '<td width="125"></td>';
        }

        $tblCustomerDetail .= '<td width="125">' . $row["Disposition"] . '</td>';

        if($row["FollowUpDate"] != '' || $row["FollowUpDate"] != NULL)
        {
            $FollowUpDate = split(" ", $row["FollowUpDate"]);
            $tblCustomerDetail .= '<td width="125">' . $FollowUpDate[0] . ' ' . $FollowUpDate[1] . ' ' . $FollowUpDate[2] . '</td>';
        }
        else
        {
            $tblCustomerDetail .= '<td width="125"></td>';
        }

        $tblCustomerDetail .= '<td>' . $row["Comments"] . '</td>';
        $tblCustomerDetail .= '</tr>';
    }

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomers);
}

elseif ($_GET['Action'] == 'CRMEntry')
// ********** CRM LEAD MANAGEMENT **********
{
    $bolSwitch = "True";

    $EntryLastContactDate = date('Y/m/d H:i:s');

    if ($_POST['EntryDisposition'] != '')
    {
       $EntryDisposition = $_POST['EntryDisposition'];
    }
    else
    {
        $bolSwitch = "False";
    }
   
    if ($_POST['EntryFollowUpDate'] != '')
    {
       $EntryFollowUpDate = $_POST['EntryFollowUpDate'];
    }
    else
    {
        $bolSwitch = "False";
    }

    if ($_POST['EntryComments'] != '')
    {
       $EntryComments = $_POST['EntryComments'];
    }
    else
    {
        $bolSwitch = "False";
    }

    if($bolSwitch == "True")
    {
        // GMC - 06/09/14 - Divide the process of inserting and updating just in case they are conflicting
        // First connect to determine if we have already records of the same values
        
        // CONNECT TO SQL SERVER DATABASE
        $connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
        $selected = mssql_select_db($dbName, $connCustomer);

        $date = $EntryFollowUpDate . " 12:00:00 AM";

        // GMC - 05/01/14 - Defensive Code for Double Insertion in CRM LOG table
        $str = just_clean($EntryComments);
        $result = mssql_query("SELECT * FROM tblCRM_LOG WHERE CustomerID = " . $_SESSION['CRMCustomerID'] . " AND Disposition = '" . $EntryDisposition . "' AND Comments = '" . $str . "' AND FollowUpDate = '" . $date . "'");
        $numRows = mssql_num_rows($result);

        $_SESSION['CRM_Num_Rows'] = $numRows;

        // CLOSE DATABASE CONNECTION
        mssql_close($connCustomer);

        // Second Connect to do the update first
        if ($_SESSION['CRM_Num_Rows'] > 0)
        {
            // do nothing
        }
        else
        {
            // CONNECT TO SQL SERVER DATABASE
            $connUpdate = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
            $selectedUpdate = mssql_select_db($dbName, $connUpdate);

            $strSQL2 = "UPDATE tblCRM_LOG SET LastContactFlag = 0 WHERE CustomerID = " . $_SESSION['CRMCustomerID'] . "";
            // echo $strSQL2;
            $qryUpdateCRMEntries = mssql_query($strSQL2);
            
            // CLOSE DATABASE CONNECTION
            mssql_close($connUpdate);
        }

        // Third Connect to do the insert next
        if ($_SESSION['CRM_Num_Rows'] > 0)
        {
            // do nothing
        }
        else
        {
            // CONNECT TO SQL SERVER DATABASE
            $connInsert = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
            $selectedInsert = mssql_select_db($dbName, $connInsert);

            $str = just_clean($EntryComments);
	        $strSQL1 = "INSERT INTO tblCRM_LOG (CustomerID,LastContactDate,Disposition,FollowUpDate,Comments,LastContactFlag) VALUES ('" . $_SESSION['CRMCustomerID'] . "','" . $EntryLastContactDate . "','" . $EntryDisposition . "','" . $EntryFollowUpDate . "','" . $str . "','1')";
            // echo $strSQL1;
            $qryInsertCRMEntry = mssql_query($strSQL1);
            
            // CLOSE DATABASE CONNECTION
            mssql_close($connInsert);
        }

        // Final Connect to insert into Navision
        if ($_SESSION['CRM_Num_Rows'] > 0)
        {
            // do nothing
        }
        else
        {
            $connNavision = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on 65.46.25.26");
            mssql_select_db('Athena', $connNavision);

            $qryInsertNAVCRM = mssql_init("wsInsertWebCRM", $connNavision);
            $str = just_clean($EntryComments);

            // BIND PARAMETERS
            mssql_bind($qryInsertNAVCRM, "@prmNAVCustomerID", $_SESSION['CRM_NavCustomerID'], SQLVARCHAR);
            mssql_bind($qryInsertNAVCRM, "@prmLastContactDate", $EntryLastContactDate, SQLVARCHAR);
            mssql_bind($qryInsertNAVCRM, "@prmDisposition", $EntryDisposition, SQLVARCHAR);
            mssql_bind($qryInsertNAVCRM, "@prmFollowUpDate", $EntryFollowUpDate, SQLVARCHAR);
            mssql_bind($qryInsertNAVCRM, "@prmComments", $str, SQLTEXT);

            $rsInsertNAVCRM = mssql_execute($qryInsertNAVCRM);

            // CLOSE DATABASE CONNECTION
            mssql_close($connNavision);

            // echo '<script language="javascript">alert("CRM Entry Added Sucessfully.")</script>;';
            $_SESSION['CRM_Message'] = "CRM Entry Added Sucessfully!! You can safely go to Customer CRM-Lead or Customer Management";
        }
    }
    else
    {
        // echo '<script language="javascript">alert("You have not entered one or more of the required fields, please try again.")</script>;';
        $_SESSION['CRM_Message'] = "You have not entered one or more of the required fields, please try again.!!, You must use the Browser Go Back!";
    }
}

elseif ($_GET['Action'] == 'SearchCRMList')
// ********** CRM LEAD MANAGEMENT **********
{
    // RESET SESSION VARIABLES
    /*
    foreach ($_SESSION as $key => $value) {
		if ($key != 'IsRevitalashLoggedIn' && $key != 'UserID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress' && $key != 'UserTypeID')
		{
			unset($_SESSION[$key]);
		}
	}
    */
    
	// CONNECT TO SQL SERVER DATABASE
	$connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomers);

    $intUserID = $_SESSION['UserID'];

    // GMC - 06/27/13 - Add CustomerStatus to CRM-Lead Process
    // $strSQL = "SELECT tblCustomers.RecordID, tblCustomers.FirstName, tblCustomers.LastName, tblCustomers.NavisionCustomerID, tblCustomers.CompanyName, tblCustomers.EMailAddress, tblCustomers.CustomerTypeID, tblCustomers.Telephone, tblCustomers.Address1, tblCustomers.City, tblCustomers.State, tblCustomers.PostalCode, tblCustomers.NavisionCustomerID, tblCRM_LOG.LastContactDate, tblCRM_LOG.Disposition, tblCRM_LOG.FollowUpDate, tblCRM_LOG.Comments FROM tblCustomers INNER JOIN tblCRM_LOG ON tblCRM_LOG.CustomerID = tblCustomers.RecordID WHERE tblCustomers.IsActive = 1 AND tblCustomers.SalesRepID = " . $intUserID . "";
    $strSQL = "SELECT tblCustomers.RecordID, tblCustomers.FirstName, tblCustomers.LastName, tblCustomers.NavisionCustomerID, tblCustomers.CompanyName, tblCustomers.EMailAddress, tblCustomers.CustomerTypeID, tblCustomers.Telephone, tblCustomers.Address1, tblCustomers.City, tblCustomers.State, tblCustomers.PostalCode, tblCustomers.NavisionCustomerID, tblCustomers.CustomerStatus,tblCRM_LOG.LastContactDate, tblCRM_LOG.Disposition, tblCRM_LOG.FollowUpDate, tblCRM_LOG.Comments FROM tblCustomers INNER JOIN tblCRM_LOG ON tblCRM_LOG.CustomerID = tblCustomers.RecordID WHERE tblCRM_LOG.LastContactFlag = 1 AND tblCustomers.SalesRepID = " . $intUserID . "";

    // GMC - 08/12/13 - Add State Code to CRM-Lead Process
    if (isset($_POST['CustomerNumber']) && $_POST['CustomerNumber'] != '')
    {
       $strSQL .= " AND tblCustomers.NavisionCustomerID LIKE '%" . $_POST['CustomerNumber'] . "%'";
       $_SESSION['CRM_CustomerNumber'] = $_POST['CustomerNumber'];
    }
    else
    {
       if(isset($_SESSION['CRM_CustomerNumber']) && $_SESSION['CRM_CustomerNumber'] != "")
       {
           $strSQL .= " AND tblCustomers.NavisionCustomerID LIKE '%" . $_SESSION['CRM_CustomerNumber'] . "%'";
       }
    }

    if (isset($_POST['CompanyName']) && $_POST['CompanyName'] != '')
    {
       $strSQL .= " AND tblCustomers.CompanyName LIKE '%" . $_POST['CompanyName'] . "%'";
       $_SESSION['CRM_CompName'] = $_POST['CompanyName'];
    }
    else
    {
        if(isset($_SESSION['CRM_CompName']) && $_SESSION['CRM_CompName'] != "")
        {
            $strSQL .= " AND tblCustomers.CompanyName LIKE '%" . $_SESSION['CRM_CompName'] . "%'";
        }
    }

    if (isset($_POST['LastContactDate']) && $_POST['LastContactDate'] != '')
    {
       $strSQL .= " AND tblCRM_LOG.LastContactDate = '" . $_POST['LastContactDate'] . "'";
       $_SESSION['CRM_LastContactDate'] = $_POST['LastContactDate'];
    }
    else
    {
        if(isset($_SESSION['CRM_LastContactDate']) && $_SESSION['CRM_LastContactDate'] != "")
        {
            $strSQL .= " AND tblCRM_LOG.LastContactDate = '" . $_SESSION['CRM_LastContactDate'] . "'";
        }
    }

    if (isset($_POST['Disposition']) && $_POST['Disposition'] != '' && $_POST['Disposition'] != 'Select')
    {
       $strSQL .= " AND tblCRM_LOG.Disposition = '" . $_POST['Disposition'] . "'";
       $_SESSION['CRM_Disposition'] = $_POST['Disposition'];
    }
    else
    {
        if(isset($_SESSION['CRM_Disposition']) && $_SESSION['CRM_Disposition'] != "")
        {
            $strSQL .= " AND tblCRM_LOG.Disposition = '" . $_SESSION['CRM_Disposition'] . "'";
        }
    }

    if (isset($_POST['FollowUpDate']) && $_POST['FollowUpDate'] != '')
    {
       $strSQL .= " AND tblCRM_LOG.FollowUpDate = '" . $_POST['FollowUpDate'] . "'";
       $_SESSION['CRM_FollowUpDate'] = $_POST['FollowUpDate'];
    }
    else
    {
        if(isset($_SESSION['CRM_FollowUpDate']) && $_SESSION['CRM_FollowUpDate'] != "")
        {
            $strSQL .= " AND tblCRM_LOG.FollowUpDate = '" . $_SESSION['CRM_FollowUpDate'] . "'";
        }
    }

    // GMC - 06/27/13 - Add CustomerStatus to CRM-Lead Process
    if (isset($_POST['CustomerStatus']) && $_POST['CustomerStatus'] != '' && $_POST['CustomerStatus'] != 'Select')
    {
       $strSQL .= " AND tblCustomers.CustomerStatus = '" . $_POST['CustomerStatus'] . "'";
       $_SESSION['CRM_CustomerStatus'] = $_POST['CustomerStatus'];
    }
    else
    {
        if(isset($_SESSION['CRM_CustomerStatus']) && $_SESSION['CRM_CustomerStatus'] != "")
        {
            $strSQL .= " AND tblCustomers.CustomerStatus = '" . $_SESSION['CRM_CustomerStatus'] . "'";
        }
    }

    // GMC - 08/12/13 - Add State Code to CRM-Lead Process
    if (isset($_POST['State']) && $_POST['State'] != '' && $_POST['State'] != 'Select')
    {
       $strSQL .= " AND tblCustomers.State = '" . $_POST['State'] . "'";
       $_SESSION['CRM_State'] = $_POST['State'];
    }
    else
    {
        if(isset($_SESSION['CRM_State']) && $_SESSION['CRM_State'] != "")
        {
            $strSQL .= " AND tblCustomers.State = '" . $_SESSION['CRM_State'] . "'";
        }
    }

    // GMC - 06/30/14 - Add City and Phone to CRM-Lead Process
    if (isset($_POST['City']) && $_POST['City'] != '')
    {
       $strSQL .= " AND tblCustomers.City LIKE '%" . $_POST['City'] . "%'";
       $_SESSION['CRM_City'] = $_POST['City'];
    }
    else
    {
        if(isset($_SESSION['CRM_City']) && $_SESSION['CRM_City'] != "")
        {
            $strSQL .= " AND tblCustomers.City LIKE '%" . $_SESSION['CRM_City'] . "%'";
        }
    }

    if (isset($_POST['Phone']) && $_POST['Phone'] != '')
    {
       $strSQL .= " AND tblCustomers.Telephone LIKE '%" . $_POST['Phone'] . "%'";
       $_SESSION['CRM_Phone'] = $_POST['Phone'];
    }
    else
    {
        if(isset($_SESSION['CRM_Phone']) && $_SESSION['CRM_Phone'] != "")
        {
            $strSQL .= " AND tblCustomers.Telephone LIKE '%" . $_SESSION['CRM_Phone'] . "%'";
        }
    }

    $strSQL .= " ORDER BY tblCRM_LOG.FollowUpDate DESC, tblCustomers.CompanyName ASC";

    // echo $strSQL;

    $getCRMList = mssql_query($strSQL);

	$tblCustomerList = '';

    while($row = mssql_fetch_array($getCRMList))
    {
        $tblCustomerList .= '<tr class="tdwhite">';
        $tblCustomerList .= '<td><a href="/csradmin/customers.php?Action=CRMDetail&CustomerID=' . $row["RecordID"] . '">Go to Detail</a></td>';
        $tblCustomerList .= '<td>' . $row["NavisionCustomerID"] . '</td>';

        // GMC - 07/08/13 - To allow for Reps to Place an Order if Customer is Approved
        if($row["CustomerStatus"] == "Approved")
        {
			$tblCustomerList .= '<td><a href="/csradmin/customers.php?Action=Detail&CustomerID=' . $row["RecordID"] . '">' . $row["CompanyName"] . '</a></td>';
        }
        else
        {
            $tblCustomerList .= '<td>' . $row["CompanyName"] . '</td>';
        }

        $tblCustomerList .= '<td>' . $row["State"] . '</td>';
        
        // GMC - 06/30/14 - Add City and Phone to CRM-Lead Process
        $tblCustomerList .= '<td>' . $row["City"] . '</td>';

        $tblCustomerList .= '<td>' . $row["Telephone"] . '</td>';
        $tblCustomerList .= '<td>' . $row["LastContactDate"] . '</td>';
        $tblCustomerList .= '<td>' . $row["Disposition"] . '</td>';
        $tblCustomerList .= '<td>' . $row["FollowUpDate"] . '</td>';

        // GMC - 06/27/13 - Add CustomerStatus to CRM-Lead Process
        $tblCustomerList .= '<td>' . $row["CustomerStatus"] . '</td>';

        $tblCustomerList .= '</tr>';
    }

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomers);
}

elseif ($_GET['Action'] == 'EditProfile')
// ********** EDIT CUSTOMER **********
{
    // GMC - 02/04/16 Modifications for the Edit - Delete - Address Shipping Addresses
	// CONNECT TO SQL SERVER DATABASE
   	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
   	mssql_select_db($dbName, $connCustomer);

	if (isset($_POST['cmdModifyShippingAddress']))
	{
        // GMC - 08/16/12 - Split US Other Countries at Shipping Selection Shopping Cart Consumer
		// include("../includes/selCountries.php");
		include("../includes/selCountries_Prior.php");

        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
		$ShipCompany = $_POST['ShipCompany'];

		$ShipAttn = $_POST['ShipAttn'];
		$ShipCountryCode = $_POST['ShipCountryCode'];

        // GMC - 11/14/11 - Replace String process for Shipping Address
        $TempAddress1 = $_POST['ShipAddress1'];
		$TempAddress2 = $_POST['ShipAddress2'];
        $TempCity = $_POST['ShipCity'];

              // GMC - 03/18/12 - Hong Kong Change for City and Zip Code
              if($TempCity == "CH01")
              {
                  $TempCity = "Aberdeen";
              }
              else if($TempCity == "CH02")
              {
                  $TempCity = "Admiralty";
              }
              else if($TempCity == "CH03")
              {
                  $TempCity = "Ap lei Chau";
              }
              else if($TempCity == "CH04")
              {
                  $TempCity = "Causeway Bay";
              }
              else if($TempCity == "CH05")
              {
                  $TempCity = "Central";
              }
              else if($TempCity == "CH06")
              {
                  $TempCity = "Chaiwan";
              }
              else if($TempCity == "CH07")
              {
                  $TempCity = "Chek Lap Kok";
              }
              else if($TempCity == "CH08")
              {
                  $TempCity = "Cheung Chau";
              }
              else if($TempCity == "CH09")
              {
                  $TempCity = "Cheung Sha Wan";
              }
              else if($TempCity == "CH10")
              {
                  $TempCity = "Choi Hung";
              }
              else if($TempCity == "CH11")
              {
                  $TempCity = "Chok Ko Wan";
              }
              else if($TempCity == "CH12")
              {
                  $TempCity = "Chung Hom Kok";
              }
              else if($TempCity == "CH13")
              {
                  $TempCity = "Clear Water Bay";
              }
              else if($TempCity == "CH14")
              {
                  $TempCity = "Discovery Bay";
              }
              else if($TempCity == "CH15")
              {
                  $TempCity = "Fanling";
              }
              else if($TempCity == "CH16")
              {
                  $TempCity = "Fo Tan";
              }
              else if($TempCity == "CH17")
              {
                  $TempCity = "Happy Valley";
              }
              else if($TempCity == "CH18")
              {
                  $TempCity = "Ho Man Tin";
              }
              else if($TempCity == "CH19")
              {
                  $TempCity = "Hong Lok Yuen";
              }
              else if($TempCity == "CH20")
              {
                  $TempCity = "Hung Hom";
              }
              else if($TempCity == "CH21")
              {
                  $TempCity = "Jardine Lookout";
              }
              else if($TempCity == "CH22")
              {
                  $TempCity = "Jordan";
              }
              else if($TempCity == "CH23")
              {
                  $TempCity = "Junk Bay";
              }
              else if($TempCity == "CH24")
              {
                  $TempCity = "Kennedy Town";
              }
              else if($TempCity == "CH25")
              {
                  $TempCity = "Kowloon Bay";
              }
              else if($TempCity == "CH26")
              {
                  $TempCity = "Kowloon City";
              }
              else if($TempCity == "CH27")
              {
                  $TempCity = "Kowloon Tong";
              }
              else if($TempCity == "CH28")
              {
                  $TempCity = "Kwai Chung";
              }
              else if($TempCity == "CH29")
              {
                  $TempCity = "Kwai Fong";
              }
              else if($TempCity == "CH30")
              {
                  $TempCity = "Kwun Tong";
              }
              else if($TempCity == "CH31")
              {
                  $TempCity = "Lai Chi Kok";
              }
              else if($TempCity == "CH32")
              {
                  $TempCity = "Lam Tin";
              }
              else if($TempCity == "CH33")
              {
                  $TempCity = "Lamma Island";
              }
              else if($TempCity == "CH34")
              {
                  $TempCity = "Lantau Island";
              }
              else if($TempCity == "CH35")
              {
                  $TempCity = "Lei Yue Mun";
              }
              else if($TempCity == "CH36")
              {
                  $TempCity = "Ma On Shan";
              }
              else if($TempCity == "CH37")
              {
                  $TempCity = "Ma Wan";
              }
              else if($TempCity == "CH38")
              {
                  $TempCity = "Mid-Level";
              }
              else if($TempCity == "CH39")
              {
                  $TempCity = "Mongkok";
              }
              else if($TempCity == "CH40")
              {
                  $TempCity = "Ngau Tau Kok";
              }
              else if($TempCity == "CH41")
              {
                  $TempCity = "North Point";
              }
              else if($TempCity == "CH42")
              {
                  $TempCity = "Peak";
              }
              else if($TempCity == "CH43")
              {
                  $TempCity = "Peng Chau";
              }
              else if($TempCity == "CH44")
              {
                  $TempCity = "Pennys Bay";
              }
              else if($TempCity == "CH45")
              {
                  $TempCity = "Pokfulam";
              }
              else if($TempCity == "CH46")
              {
                  $TempCity = "Quarry Bay";
              }
              else if($TempCity == "CH47")
              {
                  $TempCity = "Queensway";
              }
              else if($TempCity == "CH48")
              {
                  $TempCity = "Repulse Bay";
              }
              else if($TempCity == "CH49")
              {
                  $TempCity = "Sai Kung";
              }
              else if($TempCity == "CH50")
              {
                  $TempCity = "Sai Wan Ho";
              }
              else if($TempCity == "CH51")
              {
                  $TempCity = "Sai Ying Pun";
              }
              else if($TempCity == "CH52")
              {
                  $TempCity = "San Po Kong";
              }
              else if($TempCity == "CH53")
              {
                  $TempCity = "Sham Shui Po";
              }
              else if($TempCity == "CH54")
              {
                  $TempCity = "Sham Tseng";
              }
              else if($TempCity == "CH55")
              {
                  $TempCity = "Shatin";
              }
              else if($TempCity == "CH56")
              {
                  $TempCity = "Shaukiwan";
              }
              else if($TempCity == "CH57")
              {
                  $TempCity = "Shek Kip Mei";
              }
              else if($TempCity == "CH58")
              {
                  $TempCity = "Shek O";
              }
              else if($TempCity == "CH59")
              {
                  $TempCity = "Sheung Shui";
              }
              else if($TempCity == "CH60")
              {
                  $TempCity = "Sheung Wan";
              }
              else if($TempCity == "CH61")
              {
                  $TempCity = "Siu Ho Wan";
              }
              else if($TempCity == "CH62")
              {
                  $TempCity = "Siu Lik Yuen";
              }
              else if($TempCity == "CH63")
              {
                  $TempCity = "Siu Sai Wan";
              }
              else if($TempCity == "CH64")
              {
                  $TempCity = "South Bay";
              }
              else if($TempCity == "CH65")
              {
                  $TempCity = "Stanley";
              }
              else if($TempCity == "CH66")
              {
                  $TempCity = "Tai Hang";
              }
              else if($TempCity == "CH67")
              {
                  $TempCity = "Tai Kok Tsui";
              }
              else if($TempCity == "CH68")
              {
                  $TempCity = "Tai O";
              }
              else if($TempCity == "CH69")
              {
                  $TempCity = "Tai Wai";
              }
              else if($TempCity == "CH70")
              {
                  $TempCity = "Taipo";
              }
              else if($TempCity == "CH71")
              {
                  $TempCity = "Tin Shui Wai";
              }
              else if($TempCity == "CH72")
              {
                  $TempCity = "Tokwawan";
              }
              else if($TempCity == "CH73")
              {
                  $TempCity = "Tseung Kwan O";
              }
              else if($TempCity == "CH74")
              {
                  $TempCity = "Tsimshatsui";
              }
              else if($TempCity == "CH75")
              {
                  $TempCity = "Tsimshatsui East";
              }
              else if($TempCity == "CH76")
              {
                  $TempCity = "Tsing Yi";
              }
              else if($TempCity == "CH77")
              {
                  $TempCity = "Tsuen Wan";
              }
              else if($TempCity == "CH78")
              {
                  $TempCity = "Tsz Wan Shan";
              }
              else if($TempCity == "CH79")
              {
                  $TempCity = "Tuen Mun";
              }
              else if($TempCity == "CH80")
              {
                  $TempCity = "Tung Chung";
              }
              else if($TempCity == "CH81")
              {
                  $TempCity = "Wanchai";
              }
              else if($TempCity == "CH82")
              {
                  $TempCity = "Wang Tau Hom";
              }
              else if($TempCity == "CH83")
              {
                  $TempCity = "Western";
              }
              else if($TempCity == "CH84")
              {
                  $TempCity = "Wong Chuk Hang";
              }
              else if($TempCity == "CH85")
              {
                  $TempCity = "Yau Ma Tei";
              }
              else if($TempCity == "CH86")
              {
                  $TempCity = "Yau Tong";
              }
              else if($TempCity == "CH87")
              {
                  $TempCity = "Yau Yat Chuen";
              }
              else if($TempCity == "CH88")
              {
                  $TempCity = "Yuen Long";
              }

		$TempState = $_POST['ShipState'];
        $TempPostalCode = $_POST['ShipPostalCode'];
        $ShipAddress1 = ereg_replace("[^A-Za-z0-9 ]", "", $TempAddress1);
		$ShipAddress2 = ereg_replace("[^A-Za-z0-9 ]", "", $TempAddress2);
        $ShipCity = ereg_replace("[^A-Za-z0-9 ]", "", $TempCity);
		$ShipState = ereg_replace("[^A-Za-z0-9 ]", "", $TempState);
        $ShipPostalCode = ereg_replace("[^A-Za-z0-9 ]", "", $TempPostalCode);

        $qryUpdateShippingInfo = mssql_query("UPDATE tblCustomers_ShipTo SET Attn = '" . $ShipAttn . "', Address1 = '" . $ShipAddress1 . "', Address2 = '" . $ShipAddress2 . "', City = '" . $ShipCity . "', State = '" . $ShipState . "', PostalCode = '" . $ShipPostalCode . "', CountryCode = '" . $ShipCountryCode . "', CompanyName = '" . $ShipCompany . "' WHERE RecordID = '" . $_SESSION['CustomerShipToID'] . "'");

		$confirmation = 'The customers shipping information was updated successfully.';
    }
    
    $_SESSION['DeleteEditAddress'] = "True";
}

elseif ($_GET['Action'] == 'NewOrder')
// ********** PLACE ORDER **********
{
	$pagerror = 0;

   // GMC - 03/26/12 - MediaKit Process
   if (isset($_GET['OrderType']))
   {
       if($_GET['OrderType'] == 'MediaKit')
       {
           $_SESSION['OrderType'] =  "MediaKit";
       }
       else
       {
           $_SESSION['OrderType'] =  "";
       }
   }

	// CONNECT TO SQL SERVER DATABASE
	$connNewOrder = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connNewOrder);
	
	// ATTEMPT TO SET CUSTOMER ID BASED ON URL STRING
	if (isset($_GET['NavCustomerID']))
	{
		$qryGetNavID = mssql_query("SELECT RecordID FROM tblCustomers WHERE NavisionCustomerID = '" . $_GET['NavCustomerID'] . "'");
		
		while($rowGetNavID = mssql_fetch_array($qryGetNavID))
		{ $intCustomerID = $rowGetNavID["RecordID"]; }
		
		if (!isset($intCustomerID))
		{
			$intCustomerID = 0;
		}
	}
	else
		$intCustomerID = $_GET['CustomerID'];
		
	// VALIDATE CUSTOMER VIA DATABASE
	if ($intCustomerID != 0)
	{
		$_SESSION['CustomerID'] = $intCustomerID;
		
		// QUERY CUSTOMER RECORDS
		$qryTestCustomer = mssql_query("SELECT RecordID FROM tblCustomers WHERE IsActive = 1 AND RecordID = " . $intCustomerID);

        // GMC - 11/16/08 Domestic Vs. International 2nd Phase
        // GMC - 07/20/10 - Reseller EU has reseller number no VAT charge
        // GMC - 03/12/12 - Add Billing Information to Order Confirmations
        // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
        // GMC - 04/27/14 - New dspCustomers_OrderConfirmation Format like Magento
        // GMC - 01/26/15 - Fix RLA Billing Address for International and Domestic
        // $qryGetCustomer = mssql_query("SELECT * FROM tblCustomers WHERE IsActive = 1 AND RecordID = " . $intCustomerID);
		// $qryGetCustomer = mssql_query("SELECT tblCustomers.CustomerTypeID, tblCustomers.EMailAddress, tblCustomers.IsApprovedTerms, tblCustomers_ShipTo.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName FROM tblCustomers_ShipTo, tblCustomers WHERE tblCustomers.recordid = tblCustomers_shipto.customerid and tblcustomers_shipto.IsActive = 1 AND tblcustomers_shipto.IsDefault = 1 AND tblcustomers_shipto.customerid = " . $intCustomerID);
		// $qryGetCustomer = mssql_query("SELECT tblCustomers.CustomerTypeID, tblCustomers.ResellerNumber, tblCustomers.EMailAddress, tblCustomers.IsApprovedTerms, tblCustomers_ShipTo.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName FROM tblCustomers_ShipTo, tblCustomers WHERE tblCustomers.recordid = tblCustomers_shipto.customerid and tblcustomers_shipto.IsActive = 1 AND tblcustomers_shipto.IsDefault = 1 AND tblcustomers_shipto.customerid = " . $intCustomerID);
        // $qryGetCustomer = mssql_query("SELECT tblCustomers.CustomerTypeID, tblCustomers.ResellerNumber, tblCustomers.EMailAddress, tblCustomers.Address1, tblCustomers.Address2, tblCustomers.City, tblCustomers.State, tblCustomers.PostalCode, tblCustomers.CountryCode, tblCustomers.IsApprovedTerms, tblCustomers_ShipTo.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName FROM tblCustomers_ShipTo, tblCustomers WHERE tblCustomers.recordid = tblCustomers_shipto.customerid and tblcustomers_shipto.IsActive = 1 AND tblcustomers_shipto.IsDefault = 1 AND tblcustomers_shipto.customerid = " . $intCustomerID);
		// $qryGetCustomer = mssql_query("SELECT tblCustomers.CustomerTypeID, tblCustomers.ResellerNumber, tblCustomers.EMailAddress, tblCustomers.Address1, tblCustomers.Address2, tblCustomers.City, tblCustomers.State, tblCustomers.PostalCode, tblCustomers.CountryCode, tblCustomers.IsApprovedTerms, tblCustomers_ShipTo.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, tblCustomers.CARLASIGNED FROM tblCustomers_ShipTo, tblCustomers WHERE tblCustomers.recordid = tblCustomers_shipto.customerid and tblcustomers_shipto.IsActive = 1 AND tblcustomers_shipto.IsDefault = 1 AND tblcustomers_shipto.customerid = " . $intCustomerID);
        // $qryGetCustomer = mssql_query("SELECT tblCustomers.NavisionCustomerID, tblCustomers.CustomerTypeID, tblCustomers.ResellerNumber, tblCustomers.EMailAddress, tblCustomers.Address1, tblCustomers.Address2, tblCustomers.City, tblCustomers.State, tblCustomers.PostalCode, tblCustomers.CountryCode, tblCustomers.IsApprovedTerms, tblCustomers_ShipTo.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, tblCustomers.Telephone, tblCustomers.CARLASIGNED FROM tblCustomers_ShipTo, tblCustomers WHERE tblCustomers.recordid = tblCustomers_shipto.customerid and tblcustomers_shipto.IsActive = 1 AND tblcustomers_shipto.IsDefault = 1 AND tblcustomers_shipto.customerid = " . $intCustomerID);
        $qryGetCustomer = mssql_query("SELECT tblCustomers.NavisionCustomerID, tblCustomers.CustomerTypeID, tblCustomers.ResellerNumber, tblCustomers.EMailAddress, tblCustomers.Address1, tblCustomers.Address2, tblCustomers.City, tblCustomers.State, tblCustomers.PostalCode, tblCustomers.CountryCode, tblCustomers.IsApprovedTerms, tblCustomers_ShipTo.CountryCode AS ShipToCC, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName, tblCustomers.Telephone, tblCustomers.CARLASIGNED FROM tblCustomers_ShipTo, tblCustomers WHERE tblCustomers.recordid = tblCustomers_shipto.customerid and tblcustomers_shipto.IsActive = 1 AND tblcustomers_shipto.IsDefault = 1 AND tblcustomers_shipto.customerid = " . $intCustomerID);

        // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
		$qryGetCustomer_CA = mssql_query("SELECT tblCustomers.CustomerTypeID, tblCustomers.ResellerNumber, tblCustomers.EMailAddress, tblCustomers.IsApprovedTerms, tblCustomers_ShipTo.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName FROM tblCustomers_ShipTo, tblCustomers WHERE tblCustomers.recordid = tblCustomers_shipto.customerid and tblcustomers_shipto.IsActive = 1 AND tblcustomers_shipto.IsDefault = 1 AND tblcustomers_shipto.customerid = " . $intCustomerID);

        while($rowGetCustomer_CA = mssql_fetch_array($qryGetCustomer_CA))
        {
	        if ($rowGetCustomer_CA["CountryCode"] == 'CA')
            {
                $CustomerFromCA = "True";
            }
            else
            {
                $CustomerFromCA = "False";
            }
        }

        // GMC - 11/16/08 Domestic Vs. International 2nd Phase
        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
		// $qryGetCustomerShipTo = mssql_query("SELECT RecordID, Attn, Address1, Address2, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND CustomerID = " . $intCustomerID . " ORDER BY Attn ASC");
		// $qryGetCustomerShipTo = mssql_query("SELECT RecordID, Attn, Address1, Address2, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND IsDefault = 1 AND CustomerID = " . $intCustomerID . " ORDER BY Attn ASC");
		$qryGetCustomerShipTo = mssql_query("SELECT RecordID, CompanyName, Attn, Address1, Address2, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND IsDefault = 1 AND CustomerID = " . $intCustomerID . " ORDER BY Attn ASC");

		$qryGetCustomerPayment = mssql_query("SELECT * FROM tblCustomers_PayMethods WHERE CustomerID = " . $intCustomerID . " ORDER BY IsDefault DESC");

        // GMC - 04/24/10 - Order Products Drop Down Alpha Asc
        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
            // $cboProducts1 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
            // $cboProducts1 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
            // $cboMarkets1 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit1 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
            // $cboProducts1 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
            // $cboProducts1 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
            // $cboMarkets1 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit1 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit1_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts2 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts2 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets2 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit2 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts2 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts2 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets2 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit2 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
               $cboMediaKit2_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts3 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts3 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets3 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit3 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts3 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts3 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets3 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit3 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit3_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts4 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts4 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets4 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit4 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts4 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts4 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets4 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit4 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit4_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts5 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts5 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets5 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit5 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts5 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts5 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets5 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit5 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit5_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts6 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts6 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets6 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit6 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts6 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts6 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets6 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit6 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit6_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts7 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts7 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets7 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit7 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts7 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts7 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets7 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit7 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit7_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts8 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts8 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets8 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit8 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts8 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts8 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets8 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit8 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit8_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts9 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts9 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets9 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit9 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts9 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts9 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets9 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit9 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");


            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit9_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }
        
        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts10 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts10 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets10 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit10 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts10 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts10 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets10 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit10 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit10_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 03/18/10 - Add 10 Line Items Admin

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
            // $cboProducts11 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
            // $cboProducts11 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
            // $cboMarkets11 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit11 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
            // $cboProducts11 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
            // $cboProducts11 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
            // $cboMarkets11 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit11 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit11_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts12 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts12 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets12 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit12 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts12 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts12 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets12 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit12 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit12_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts13 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts13 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets13 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit13 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts13 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts13 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets13 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit13 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit13_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts14 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts14 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets14 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit14 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts14 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts14 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets14 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit14 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit14_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts15 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts15 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets15 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit15 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts15 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts15 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets15 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit15 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit15_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts16 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts16 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets16 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit16 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts16 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts16 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets16 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit16 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit16_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts17 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts17 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets17 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit17 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts17 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts17 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets17 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit17 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit17_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts18 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts18 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets18 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit18 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts18 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts18 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets18 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit18 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit18_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts19 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts19 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets19 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit19 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts19 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts19 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets19 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit19 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit19_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 01/01/09 - To hide certain products from Reps
        if ($_SESSION['UserTypeID'] == 1)
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts20 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts20 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets20 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit20 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 order by productname asc");
                // $cboProducts20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            // GMC - 11/07/08 - To divide Domestic and International Products
            // GMC - 08/16/11 - To divide Products and Marketing Materials
		    // $cboProducts20 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 order by productname asc");
            // GMC - 10/17/12 - Configure the Exclusion process for Revitalash Advanced Sales based on tblProducts - Exclusion Field
            // GMC - 01/23/12 Configure the Exclusion process for Marketing Products
		    // $cboProducts20 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 1 order by productname asc");
		    // $cboMarkets20 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 2 order by productname asc");

            // GMC - 03/26/12 - MediaKit Process
            $cboMediaKit20 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");

            // GMC - 05/18/11 - Block Canada from Ordering due to Formula Issue
            if($CustomerFromCA == "True")
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
                // $cboProducts20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit20Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 08/16/11 - To divide Products and Marketing Materials
                // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
                // $cboProducts20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 order by productname asc");
                // $cboProducts20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 1 order by productname asc");

                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                // GMC - 03/26/12 - MediaKit Process
                $cboMediaKit20_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        // GMC - 10/16/13 - Add 20 Line Items Admin

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit21 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets21_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit21_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets21_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit21_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit21 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets21_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit21Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets21_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit21_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit22 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets22_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit22_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets22_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit22_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit22 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets22_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit22Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets22_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit22_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit23 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets23_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit23_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets23_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit23_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit23 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets23_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit23Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets23_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit23_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit24 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets24_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit24_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets24_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit24_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit24 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets24_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit24Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets24_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit24_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit25 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets25_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit25_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets25_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit25_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit25 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets25_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit25Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets25_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit25_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit26 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets26_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit26_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets26_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit26_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit26 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets26_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit26Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets26_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit26_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit27 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets27_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit27_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets27_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit27_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit27 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets27_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit27Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets27_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit27_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit28 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets28_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit28_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets28_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit28_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit28 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets28_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit28Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets28_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit28_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit29 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets29_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit29_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets29_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit29_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit29 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets29_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit29Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets29_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit29_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit30 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets30_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit30_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets30_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit30_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit30 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets30_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit30Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets30_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit30_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit31 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets31_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit31_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets31_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit31_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit31 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets31_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit31Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets31_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit31_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit32 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets32_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit32_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets32_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit32_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit32 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets32_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit32Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets32_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit32_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit33 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets33_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit33_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets33_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit33_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit33 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets33_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit33Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets33_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit33_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit34 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets34_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit34_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets34_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit34_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit34 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets34_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit34Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets34_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit34_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit35 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets35_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit35_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets35_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit35_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit35 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets35_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit35Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets35_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit35_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit36 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets36_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit36_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets36_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit36_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit36 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets36_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit36Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets36_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit36_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit37 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets37_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit37_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets37_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit37_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit37 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets37_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit37Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets37_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit37_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit38 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets38_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit38_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets38_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit38_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit38 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets38_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit38Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets38_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit38_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit39 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets39_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit39_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets39_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit39_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit39 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets39_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit39Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets39_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit39_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

        if ($_SESSION['UserTypeID'] == 1)
        {
            $cboMediaKit40 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and IsRep = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets40_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit40_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryId = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets40_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 2 order by productname asc");

                $cboMediaKit40_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and IsRep = 1 and CategoryID = 3 order by productname asc");
            }
        }
        else
        {
            $cboMediaKit40 = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 1 and CategoryID = 3 order by productname asc");
            if($CustomerFromCA == "True")
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets40_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");

                $cboMediaKit40Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 and RecordId <> 289 and RecordId <> 282 and RecordId <> 306 and RecordId <> 315 and RecordId <> 234 and RecordId <> 255 and RecordId <> 311 and RecordId <> 261 and RecordId <> 331 and RecordId <> 195 and RecordId <> 280 and RecordId <> 278 and RecordId <> 291 and RecordId <> 290 and RecordId <> 305 and RecordId <> 285 and RecordId <> 314 and RecordId <> 308 and RecordId <> 309 and RecordId <> 298 and RecordId <> 299 and RecordId <> 300 and RecordId <> 301 and RecordId <> 302 and RecordId <> 304 and RecordId <> 313 order by productname asc");
            }
            else
            {
                // GMC - 07/03/14 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller) for Marketing Materials
                // $cboMarkets40_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 2 order by productname asc");

                $cboMediaKit40_Int = mssql_query("SELECT * FROM tblProducts WHERE IsActive = 1 and IsDomestic = 0 and CategoryID = 3 order by productname asc");
            }
        }

		$cboCountryCodes = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes ORDER BY SortOrder ASC, CountryName ASC");

		// GMC - 03/26/09 - Show New Layout for TradeShows
		// $cboCampaigns = mssql_query("SELECT NavisionCode, CampaignName, Location + ' (' + CONVERT(VARCHAR, StartDate, 101) + ' - ' + CONVERT(VARCHAR, EndDate, 101) + ')' AS CampaignDisplay FROM tblCampaigns WHERE IsActive = 1 ORDER BY StartDate ASC, CampaignName ASC");
        $cboCampaigns = mssql_query("SELECT NavisionCode, CampaignName, NavisionCode + ' ~ ' + CampaignName + ' ~ ' + Location + ' ~ (' + CONVERT(VARCHAR, StartDate, 101) + ' - ' + CONVERT(VARCHAR, EndDate, 101) + ')' AS CampaignDisplay FROM tblCampaigns WHERE IsActive = 1 ORDER BY StartDate ASC, CampaignName ASC");

		while($rowTestCustomer = mssql_fetch_array($qryTestCustomer))
		{
			$blnIsApproved = 1;
		}
		
		if (isset($_POST['cmdContinue']))
		{
			// SET SESSION FOR FORM RETRIEVAL

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID1'] != 0)
            {

                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty1'] == 0 || $_POST['ItemQty1'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID1'] = $_POST['ItemID1'];
			$_SESSION['FORMItemStockLocation1'] = $_POST['ItemStockLocation1'];
            $_SESSION['FORMItemQty1'] = $_POST['ItemQty1'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
            $_SESSION['FORMItemMID1'] = $_POST['ItemMID1'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT1'] = $_POST['ItemMKT1'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID2'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty2'] == 0 || $_POST['ItemQty2'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID2'] = $_POST['ItemID2'];
			$_SESSION['FORMItemStockLocation2'] = $_POST['ItemStockLocation2'];
			$_SESSION['FORMItemQty2'] = $_POST['ItemQty2'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID2'] = $_POST['ItemMID2'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT2'] = $_POST['ItemMKT2'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID3'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty3'] == 0 || $_POST['ItemQty3'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID3'] = $_POST['ItemID3'];
			$_SESSION['FORMItemStockLocation3'] = $_POST['ItemStockLocation3'];
			$_SESSION['FORMItemQty3'] = $_POST['ItemQty3'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID3'] = $_POST['ItemMID3'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT3'] = $_POST['ItemMKT3'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID4'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty4'] == 0 || $_POST['ItemQty4'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID4'] = $_POST['ItemID4'];
			$_SESSION['FORMItemStockLocation4'] = $_POST['ItemStockLocation4'];
			$_SESSION['FORMItemQty4'] = $_POST['ItemQty4'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID4'] = $_POST['ItemMID4'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT4'] = $_POST['ItemMKT4'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID5'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty5'] == 0 || $_POST['ItemQty5'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID5'] = $_POST['ItemID5'];
			$_SESSION['FORMItemStockLocation5'] = $_POST['ItemStockLocation5'];
			$_SESSION['FORMItemQty5'] = $_POST['ItemQty5'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID5'] = $_POST['ItemMID5'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT5'] = $_POST['ItemMKT5'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID6'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty6'] == 0 || $_POST['ItemQty6'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID6'] = $_POST['ItemID6'];
			$_SESSION['FORMItemStockLocation6'] = $_POST['ItemStockLocation6'];
			$_SESSION['FORMItemQty6'] = $_POST['ItemQty6'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID6'] = $_POST['ItemMID6'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT6'] = $_POST['ItemMKT6'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID7'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty7'] == 0 || $_POST['ItemQty7'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID7'] = $_POST['ItemID7'];
			$_SESSION['FORMItemStockLocation7'] = $_POST['ItemStockLocation7'];
			$_SESSION['FORMItemQty7'] = $_POST['ItemQty7'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID7'] = $_POST['ItemMID7'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT7'] = $_POST['ItemMKT7'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID8'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty8'] == 0 || $_POST['ItemQty8'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID8'] = $_POST['ItemID8'];
			$_SESSION['FORMItemStockLocation8'] = $_POST['ItemStockLocation8'];
			$_SESSION['FORMItemQty8'] = $_POST['ItemQty8'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID8'] = $_POST['ItemMID8'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT8'] = $_POST['ItemMKT8'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID9'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty9'] == 0 || $_POST['ItemQty9'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID9'] = $_POST['ItemID9'];
			$_SESSION['FORMItemStockLocation9'] = $_POST['ItemStockLocation9'];
			$_SESSION['FORMItemQty9'] = $_POST['ItemQty9'];
			
            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID9'] = $_POST['ItemMID9'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT9'] = $_POST['ItemMKT9'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID10'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty10'] == 0 || $_POST['ItemQty10'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID10'] = $_POST['ItemID10'];
			$_SESSION['FORMItemStockLocation10'] = $_POST['ItemStockLocation10'];
			$_SESSION['FORMItemQty10'] = $_POST['ItemQty10'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID10'] = $_POST['ItemMID10'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT10'] = $_POST['ItemMKT10'];
            }

            // GMC - 03/18/10 - Add 10 Line Items Admin

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID11'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty11'] == 0 || $_POST['ItemQty11'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID11'] = $_POST['ItemID11'];
			$_SESSION['FORMItemStockLocation11'] = $_POST['ItemStockLocation11'];
			$_SESSION['FORMItemQty11'] = $_POST['ItemQty11'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID11'] = $_POST['ItemMID11'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT11'] = $_POST['ItemMKT11'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID12'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty12'] == 0 || $_POST['ItemQty12'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID12'] = $_POST['ItemID12'];
			$_SESSION['FORMItemStockLocation12'] = $_POST['ItemStockLocation12'];
			$_SESSION['FORMItemQty12'] = $_POST['ItemQty12'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID12'] = $_POST['ItemMID12'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT12'] = $_POST['ItemMKT12'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID13'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty13'] == 0 || $_POST['ItemQty13'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID13'] = $_POST['ItemID13'];
			$_SESSION['FORMItemStockLocation13'] = $_POST['ItemStockLocation13'];
			$_SESSION['FORMItemQty13'] = $_POST['ItemQty13'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID13'] = $_POST['ItemMID13'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT13'] = $_POST['ItemMKT13'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID14'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty14'] == 0 || $_POST['ItemQty14'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID14'] = $_POST['ItemID14'];
			$_SESSION['FORMItemStockLocation14'] = $_POST['ItemStockLocation14'];
			$_SESSION['FORMItemQty14'] = $_POST['ItemQty14'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID14'] = $_POST['ItemMID14'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT14'] = $_POST['ItemMKT14'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID15'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty15'] == 0 || $_POST['ItemQty15'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID15'] = $_POST['ItemID15'];
			$_SESSION['FORMItemStockLocation15'] = $_POST['ItemStockLocation15'];
			$_SESSION['FORMItemQty15'] = $_POST['ItemQty15'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID15'] = $_POST['ItemMID15'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT15'] = $_POST['ItemMKT15'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID16'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty16'] == 0 || $_POST['ItemQty16'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID16'] = $_POST['ItemID16'];
			$_SESSION['FORMItemStockLocation16'] = $_POST['ItemStockLocation16'];
			$_SESSION['FORMItemQty16'] = $_POST['ItemQty16'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID16'] = $_POST['ItemMID16'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT16'] = $_POST['ItemMKT16'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID17'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty17'] == 0 || $_POST['ItemQty17'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID17'] = $_POST['ItemID17'];
			$_SESSION['FORMItemStockLocation17'] = $_POST['ItemStockLocation17'];
			$_SESSION['FORMItemQty17'] = $_POST['ItemQty17'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID17'] = $_POST['ItemMID17'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT17'] = $_POST['ItemMKT17'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID18'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty18'] == 0 || $_POST['ItemQty18'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID18'] = $_POST['ItemID18'];
			$_SESSION['FORMItemStockLocation18'] = $_POST['ItemStockLocation18'];
			$_SESSION['FORMItemQty18'] = $_POST['ItemQty18'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID18'] = $_POST['ItemMID18'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT18'] = $_POST['ItemMKT18'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID19'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty19'] == 0 || $_POST['ItemQty19'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID19'] = $_POST['ItemID19'];
			$_SESSION['FORMItemStockLocation19'] = $_POST['ItemStockLocation19'];
			$_SESSION['FORMItemQty19'] = $_POST['ItemQty19'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID19'] = $_POST['ItemMID19'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT19'] = $_POST['ItemMKT19'];
            }

            // GMC - 05/07/10 - Check Qty Not Zero in CSRADMIN
            if($_POST['ItemID20'] != 0)
            {
                // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
                if($_POST['ItemQty20'] == 0 || $_POST['ItemQty20'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID20'] = $_POST['ItemID20'];
			$_SESSION['FORMItemStockLocation20'] = $_POST['ItemStockLocation20'];
			$_SESSION['FORMItemQty20'] = $_POST['ItemQty20'];

            // GMC - 08/16/11 - To divide Products and Marketing Materials
			$_SESSION['FORMItemMID20'] = $_POST['ItemMID20'];

            // GMC - 03/26/12 - MediaKit Process
            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT20'] = $_POST['ItemMKT20'];
            }

            // GMC - 10/16/13 - Add 20 Line Items Admin

            if($_POST['ItemID21'] != 0)
            {
                if($_POST['ItemQty21'] == 0 || $_POST['ItemQty21'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID21'] = $_POST['ItemID21'];
			$_SESSION['FORMItemStockLocation21'] = $_POST['ItemStockLocation21'];
			$_SESSION['FORMItemQty21'] = $_POST['ItemQty21'];
			$_SESSION['FORMItemMID21'] = $_POST['ItemMID21'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT21'] = $_POST['ItemMKT21'];
            }

            if($_POST['ItemID22'] != 0)
            {
                if($_POST['ItemQty22'] == 0 || $_POST['ItemQty22'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID22'] = $_POST['ItemID22'];
			$_SESSION['FORMItemStockLocation22'] = $_POST['ItemStockLocation22'];
			$_SESSION['FORMItemQty22'] = $_POST['ItemQty22'];
			$_SESSION['FORMItemMID22'] = $_POST['ItemMID22'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT22'] = $_POST['ItemMKT22'];
            }

            if($_POST['ItemID23'] != 0)
            {
                if($_POST['ItemQty23'] == 0 || $_POST['ItemQty23'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID23'] = $_POST['ItemID23'];
			$_SESSION['FORMItemStockLocation23'] = $_POST['ItemStockLocation23'];
			$_SESSION['FORMItemQty23'] = $_POST['ItemQty23'];
			$_SESSION['FORMItemMID23'] = $_POST['ItemMID23'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT23'] = $_POST['ItemMKT23'];
            }

            if($_POST['ItemID24'] != 0)
            {
                if($_POST['ItemQty24'] == 0 || $_POST['ItemQty24'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID24'] = $_POST['ItemID24'];
			$_SESSION['FORMItemStockLocation24'] = $_POST['ItemStockLocation24'];
			$_SESSION['FORMItemQty24'] = $_POST['ItemQty24'];
			$_SESSION['FORMItemMID24'] = $_POST['ItemMID24'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT24'] = $_POST['ItemMKT24'];
            }

            if($_POST['ItemID25'] != 0)
            {
                if($_POST['ItemQty25'] == 0 || $_POST['ItemQty25'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID25'] = $_POST['ItemID25'];
			$_SESSION['FORMItemStockLocation25'] = $_POST['ItemStockLocation25'];
			$_SESSION['FORMItemQty25'] = $_POST['ItemQty25'];
			$_SESSION['FORMItemMID25'] = $_POST['ItemMID25'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT25'] = $_POST['ItemMKT25'];
            }

            if($_POST['ItemID26'] != 0)
            {
                if($_POST['ItemQty26'] == 0 || $_POST['ItemQty26'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID26'] = $_POST['ItemID26'];
			$_SESSION['FORMItemStockLocation26'] = $_POST['ItemStockLocation26'];
			$_SESSION['FORMItemQty26'] = $_POST['ItemQty26'];
			$_SESSION['FORMItemMID26'] = $_POST['ItemMID26'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT26'] = $_POST['ItemMKT26'];
            }

            if($_POST['ItemID27'] != 0)
            {
                if($_POST['ItemQty27'] == 0 || $_POST['ItemQty27'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID27'] = $_POST['ItemID27'];
			$_SESSION['FORMItemStockLocation27'] = $_POST['ItemStockLocation27'];
			$_SESSION['FORMItemQty27'] = $_POST['ItemQty27'];
			$_SESSION['FORMItemMID27'] = $_POST['ItemMID27'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT27'] = $_POST['ItemMKT27'];
            }

            if($_POST['ItemID28'] != 0)
            {
                if($_POST['ItemQty28'] == 0 || $_POST['ItemQty28'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID28'] = $_POST['ItemID28'];
			$_SESSION['FORMItemStockLocation28'] = $_POST['ItemStockLocation28'];
			$_SESSION['FORMItemQty28'] = $_POST['ItemQty28'];
			$_SESSION['FORMItemMID28'] = $_POST['ItemMID28'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT28'] = $_POST['ItemMKT28'];
            }

            if($_POST['ItemID29'] != 0)
            {
                if($_POST['ItemQty29'] == 0 || $_POST['ItemQty29'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID29'] = $_POST['ItemID29'];
			$_SESSION['FORMItemStockLocation29'] = $_POST['ItemStockLocation29'];
			$_SESSION['FORMItemQty29'] = $_POST['ItemQty29'];
			$_SESSION['FORMItemMID29'] = $_POST['ItemMID29'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT29'] = $_POST['ItemMKT29'];
            }

            if($_POST['ItemID30'] != 0)
            {
                if($_POST['ItemQty30'] == 0 || $_POST['ItemQty30'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID30'] = $_POST['ItemID30'];
			$_SESSION['FORMItemStockLocation30'] = $_POST['ItemStockLocation30'];
			$_SESSION['FORMItemQty30'] = $_POST['ItemQty30'];
			$_SESSION['FORMItemMID30'] = $_POST['ItemMID30'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT30'] = $_POST['ItemMKT30'];
            }

            if($_POST['ItemID31'] != 0)
            {
                if($_POST['ItemQty31'] == 0 || $_POST['ItemQty31'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID31'] = $_POST['ItemID31'];
			$_SESSION['FORMItemStockLocation31'] = $_POST['ItemStockLocation31'];
			$_SESSION['FORMItemQty31'] = $_POST['ItemQty31'];
			$_SESSION['FORMItemMID31'] = $_POST['ItemMID31'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT31'] = $_POST['ItemMKT31'];
            }

            if($_POST['ItemID32'] != 0)
            {
                if($_POST['ItemQty32'] == 0 || $_POST['ItemQty32'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID32'] = $_POST['ItemID32'];
			$_SESSION['FORMItemStockLocation32'] = $_POST['ItemStockLocation32'];
			$_SESSION['FORMItemQty32'] = $_POST['ItemQty32'];
			$_SESSION['FORMItemMID32'] = $_POST['ItemMID32'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT32'] = $_POST['ItemMKT32'];
            }

            if($_POST['ItemID33'] != 0)
            {
                if($_POST['ItemQty33'] == 0 || $_POST['ItemQty33'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID33'] = $_POST['ItemID33'];
			$_SESSION['FORMItemStockLocation33'] = $_POST['ItemStockLocation33'];
			$_SESSION['FORMItemQty33'] = $_POST['ItemQty33'];
			$_SESSION['FORMItemMID33'] = $_POST['ItemMID33'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT33'] = $_POST['ItemMKT33'];
            }

            if($_POST['ItemID34'] != 0)
            {
                if($_POST['ItemQty34'] == 0 || $_POST['ItemQty34'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID34'] = $_POST['ItemID34'];
			$_SESSION['FORMItemStockLocation34'] = $_POST['ItemStockLocation34'];
			$_SESSION['FORMItemQty34'] = $_POST['ItemQty34'];
			$_SESSION['FORMItemMID34'] = $_POST['ItemMID34'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT34'] = $_POST['ItemMKT34'];
            }

            if($_POST['ItemID35'] != 0)
            {
                if($_POST['ItemQty35'] == 0 || $_POST['ItemQty35'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID35'] = $_POST['ItemID35'];
			$_SESSION['FORMItemStockLocation35'] = $_POST['ItemStockLocation35'];
			$_SESSION['FORMItemQty35'] = $_POST['ItemQty35'];
			$_SESSION['FORMItemMID35'] = $_POST['ItemMID35'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT35'] = $_POST['ItemMKT35'];
            }

            if($_POST['ItemID36'] != 0)
            {
                if($_POST['ItemQty36'] == 0 || $_POST['ItemQty36'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID36'] = $_POST['ItemID36'];
			$_SESSION['FORMItemStockLocation36'] = $_POST['ItemStockLocation36'];
			$_SESSION['FORMItemQty36'] = $_POST['ItemQty36'];
			$_SESSION['FORMItemMID36'] = $_POST['ItemMID36'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT36'] = $_POST['ItemMKT36'];
            }

            if($_POST['ItemID37'] != 0)
            {
                if($_POST['ItemQty37'] == 0 || $_POST['ItemQty37'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID37'] = $_POST['ItemID37'];
			$_SESSION['FORMItemStockLocation37'] = $_POST['ItemStockLocation37'];
			$_SESSION['FORMItemQty37'] = $_POST['ItemQty37'];
			$_SESSION['FORMItemMID37'] = $_POST['ItemMID37'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT37'] = $_POST['ItemMKT37'];
            }

            if($_POST['ItemID38'] != 0)
            {
                if($_POST['ItemQty38'] == 0 || $_POST['ItemQty38'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID38'] = $_POST['ItemID38'];
			$_SESSION['FORMItemStockLocation38'] = $_POST['ItemStockLocation38'];
			$_SESSION['FORMItemQty38'] = $_POST['ItemQty38'];
			$_SESSION['FORMItemMID38'] = $_POST['ItemMID38'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT38'] = $_POST['ItemMKT38'];
            }

            if($_POST['ItemID39'] != 0)
            {
                if($_POST['ItemQty39'] == 0 || $_POST['ItemQty39'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID39'] = $_POST['ItemID39'];
			$_SESSION['FORMItemStockLocation39'] = $_POST['ItemStockLocation39'];
			$_SESSION['FORMItemQty39'] = $_POST['ItemQty39'];
			$_SESSION['FORMItemMID39'] = $_POST['ItemMID39'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT39'] = $_POST['ItemMKT39'];
            }

            if($_POST['ItemID40'] != 0)
            {
                if($_POST['ItemQty40'] == 0 || $_POST['ItemQty40'] < 0)
                {
                    header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                    // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                }
            }

			$_SESSION['FORMItemID40'] = $_POST['ItemID40'];
			$_SESSION['FORMItemStockLocation40'] = $_POST['ItemStockLocation40'];
			$_SESSION['FORMItemQty40'] = $_POST['ItemQty40'];
			$_SESSION['FORMItemMID40'] = $_POST['ItemMID40'];

            if($_SESSION['OrderType'] == 'MediaKit')
            {
                $_SESSION['FORMItemMKT40'] = $_POST['ItemMKT40'];
            }

            // GMC - 09/17/12 - Fix BUG regarding NavisionCampaign POST Value
            if(isset($_POST["NavisionCampaign"]) && ($_POST["NavisionCampaign"] != ""))
            {
                $_SESSION['FORMNavisionCampaign'] = $_POST['NavisionCampaign'];
            }
            else
            {
                $_POST['NavisionCampaign'] = "";
                $_SESSION['FORMNavisionCampaign'] = $_POST['NavisionCampaign'];
            }

            // GMC - 09/05/09 - Promotion Section - Drop Down for CSR's Only
            if($_POST['NavisionCampaign'] == "FTBD")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount']= $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            // GMC - 10/17/11 - FTB10 Promotion Code 10%
            if($_POST['NavisionCampaign'] == "FTB10")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount']= $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            // GMC - 07/12/11 - PKW Promotion Code 15%
            if($_POST['NavisionCampaign'] == "PKW")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount']= $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            // GMC - 07/21/11 - AMERSPA Promotion Code 15%
            if($_POST['NavisionCampaign'] == "AMERSPA")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount']= $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            // GMC - 02/14/12 - LASH Promotion Code 15%
            if($_POST['NavisionCampaign'] == "LASH")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount']= $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            // GMC - 06/04/12 - SUMMER2012 Special Promotion Code 15% just for NAV974/620 FineLine Primer
            if($_POST['NavisionCampaign'] == "SUMMER2012")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount'] = $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            if($_POST['NavisionCampaign'] == "NAT10")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount'] = $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            if($_POST['NavisionCampaign'] == "DIST10")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount'] = $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            if($_POST['NavisionCampaign'] == "DIST15")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount'] = $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            // GMC - 02/03/14 - LASHLOVE Promotion Code 15%
            if($_POST['NavisionCampaign'] == "LASHLOVE")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount'] = $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

            // GMC - 04/17/14 - OBAGI Promotion Code 25%
            if($_POST['NavisionCampaign'] == "OBAGI")
            {
                $_SESSION['Promo_Code'] = $_POST['NavisionCampaign'];
                $cboPromotionCodesSelected = mssql_query("SELECT * from tblCampaigns where NavisionCode = '" . $_SESSION['Promo_Code'] . "'");
                while($rowPromotionCodesSelected = mssql_fetch_array($cboPromotionCodesSelected))
                {
		            $_SESSION['Promo_Code_Discount'] = $rowPromotionCodesSelected["Discount"];
		            $_SESSION['Promo_Code_Description'] = $rowPromotionCodesSelected["CampaignName"];
                }
            }

			//PROCESS PAYMENT SUBMISSION
			if ($_POST['PaymentType'] == 'CreditCard')
			{
				if ((($_POST['CC_Number'] == '') || ($_POST['CC_Cardholder'] == '') || ($_POST['CC_ExpMonth'] == '') || ($_POST['CC_ExpYear'] == '') || ($_POST['CC_CVV'] == '') || ($_POST['CC_BillingPostalCode'] == '')) && $_POST['CC_SwipedAuthorization'] == '')
					$pageerror = 'One or more required credit card fields is missing. Please try again.';
				else
				{
					//SET PAYMENT METHOD
					include("../modules/pro_setpayment.php");
				}
			}
			elseif ($_POST['PaymentType'] == 'ECheck')
			{
				if (($_POST['CK_AccountType'] == '') || ($_POST['CK_BankName'] == '') || ($_POST['CK_AccountName'] == '') || ($_POST['CK_BankRouting'] == '') || ($_POST['CK_BankAccount'] == ''))
					$pageerror = 'One or more required electronic check fields is missing. Please try again.';
				else
				{
					//SET PAYMENT METHOD
					include("../modules/pro_setpayment.php");
				}
			}
			elseif ($_POST['PaymentType'] == 'Terms')
			{
				if ($_POST['PO_Number'] == '')
					$pageerror = 'Purchase order number is missing. Please try again.';
				else
				{
					//SET PAYMENT METHOD
					include("../modules/pro_setpayment.php");
				}
			}
			elseif ($_POST['PaymentType'] == 'NOCHARGE') // GMC - 10/31/08 - To accomodate the NOCHARGE Process
			{
                   //SET PAYMENT METHOD
					include("../modules/pro_setpayment.php");
			}

            //GMC - 02/20/09 - New Payment Types visible for CRSAdmins Only
            elseif ($_POST['PaymentType'] == 'Check')
			{
                if ($_POST['Check_Number'] == '')
					$pageerror = 'Check number is missing. Please try again.';
				else
				{
					//SET PAYMENT METHOD
					include("../modules/pro_setpayment.php");
				}
			}
            elseif ($_POST['PaymentType'] == 'Wire')
			{
                if ($_POST['Wire_Number'] == '')
					$pageerror = 'Wire number is missing. Please try again.';
				else
				{
					//SET PAYMENT METHOD
					include("../modules/pro_setpayment.php");
				}
			}
            elseif ($_POST['PaymentType'] == 'Cash')
			{
					//SET PAYMENT METHOD
					include("../modules/pro_setpayment.php");
			}

            // GMC - 05/12/10 - LTL Shipment Not a Payment Type -->
            /*
			elseif ($_POST['PaymentType'] == 'LTL') // GMC - 04/12/10 - LTL Shipments
			{
                   //SET PAYMENT METHOD
					include("../modules/pro_setpayment.php");
			}
            */
            
			if (isset($_POST['LTLShipmentType']) && $_POST['LTLShipmentType'] == 'LTL')
			{
                $_SESSION['LTLShipmentType'] = $_POST['LTLShipmentType'];
			}

            // GMC - 11/13/08 - Found a bug from White Star
			// if ($_POST['NavisionCampaign'] != 0 && $_POST['CC_SwipedAuthorization'] = '')
			if ($_POST['NavisionCampaign'] != '0' && $_POST['CC_SwipedAuthorization'] = '')
			{
				$pageerror = 'You must specify the tradeshow code and payment method for tradeshow orders.';
			}

			// GMC - 11/13/08 - To accomodate that Trade Shows must not give free goods
			if ($_POST['NavisionCampaign'] != '0')
			{
                $TradeShow = 'True';
			}

            // GMC - 02/16/13 - Special Customer Code passed to NAV
            if (isset($_POST['SpecialCustomerCode']) && $_POST['SpecialCustomerCode'] == 'True')
			{
                $_SESSION['SpecialCustomerCode'] = "True";
            }
		}
		// PROCESS 'REVIEW' BUTTON
		elseif (isset($_POST['cmdReview']))
		{
            // GMC - 07/02/12 - Check for Negative Values on Qty and Unit Price
            if(isset($_POST['ItemPrice1']) && $_POST['ItemPrice1'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice2']) && $_POST['ItemPrice2'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice3']) && $_POST['ItemPrice3'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice4']) && $_POST['ItemPrice4'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice5']) && $_POST['ItemPrice5'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice6']) && $_POST['ItemPrice6'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice7']) && $_POST['ItemPrice7'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice8']) && $_POST['ItemPrice8'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice9']) && $_POST['ItemPrice9'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice10']) && $_POST['ItemPrice10'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice11']) && $_POST['ItemPrice11'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice12']) && $_POST['ItemPrice12'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice13']) && $_POST['ItemPrice13'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice14']) && $_POST['ItemPrice14'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice15']) && $_POST['ItemPrice15'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice16']) && $_POST['ItemPrice16'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice17']) && $_POST['ItemPrice17'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice18']) && $_POST['ItemPrice18'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice19']) && $_POST['ItemPrice19'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice20']) && $_POST['ItemPrice20'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            // GMC - 10/16/13 - Add 20 Line Items Admin

            if(isset($_POST['ItemPrice21']) && $_POST['ItemPrice21'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice22']) && $_POST['ItemPrice22'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice23']) && $_POST['ItemPrice23'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice24']) && $_POST['ItemPrice24'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice25']) && $_POST['ItemPrice25'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice26']) && $_POST['ItemPrice26'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice27']) && $_POST['ItemPrice27'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice28']) && $_POST['ItemPrice28'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice29']) && $_POST['ItemPrice29'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice30']) && $_POST['ItemPrice30'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice31']) && $_POST['ItemPrice31'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice32']) && $_POST['ItemPrice32'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice33']) && $_POST['ItemPrice33'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice34']) && $_POST['ItemPrice34'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice35']) && $_POST['ItemPrice35'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice36']) && $_POST['ItemPrice36'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice37']) && $_POST['ItemPrice37'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice38']) && $_POST['ItemPrice38'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice39']) && $_POST['ItemPrice39'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            if(isset($_POST['ItemPrice40']) && $_POST['ItemPrice40'] < 0)
            {
                header("Location: http://secure.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
            }

            // GMC - 05/03/15 - Fix Select Shipping Method Not Selected by CSRADMINS
			// $_SESSION['ShippingMethod'] = $_POST['ShipMethodID'];

             if(isset($_POST['ShipMethodID']) && $_POST['ShipMethodID'] <= 0)
            {
                // header("Location: https://ae.revitalash.com/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );
                // header("Location: http://localhost/csradmin/customers.php?Action=NewOrder&CustomerID=" . $intCustomerID );

                if($_SESSION['IsInternational'] == 0)
                {
                    $_POST['ShipMethodID'] = 199;
                    $_SESSION['ShippingMethod'] = $_POST['ShipMethodID'];
                }
                else
                {
                    $_POST['ShipMethodID'] = 206;
                    $_SESSION['ShippingMethod'] = $_POST['ShipMethodID'];
                }
            }
            else
            {
			    $_SESSION['ShippingMethod'] = $_POST['ShipMethodID'];
			}

            // GMC - 04/02/09 - Activate Fedex Web Services (Domestic - International - Exclude Netherlands)
            if($_SESSION['IsInternational'] == 0)
            {
			    $rsGetHandling = mssql_query("SELECT Handling FROM tblSite_Options WHERE RecordID = 1");
            }
            else
            {
			    $rsGetHandling = mssql_query("SELECT Handling FROM tblSite_Options WHERE RecordID = 2");
            }

			while($rowGetHandling = mssql_fetch_array($rsGetHandling))
			{
				$_SESSION['OrderHandling'] = $rowGetHandling["Handling"];
			}
		}
		elseif (isset($_POST['cmdProcess']))
		{
            //PROCESS ORDER
			include("../modules/process_order.php");
			$_SESSION['OrderShipping'] = $_SESSION['OrderShipping'] + $_SESSION['OrderHandling'];		
		}
	}
	
	// CLOSE DATABASE CONNECTION
	mssql_close($connNewOrder);
}

// GMC - 11/16/08 - Domestic Vs. International 2nd Phase
elseif ($_GET['Action'] == 'UpdateAddress')
// ********** UPDATE ADDRESS **********
{
	$pagerror = 0;

	// CONNECT TO SQL SERVER DATABASE
	$connNewOrder = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connNewOrder);

	// ATTEMPT TO SET CUSTOMER ID BASED ON URL STRING
	if (isset($_GET['NavCustomerID']))
	{
		$qryGetNavID = mssql_query("SELECT RecordID FROM tblCustomers WHERE NavisionCustomerID = '" . $_GET['NavCustomerID'] . "'");

		while($rowGetNavID = mssql_fetch_array($qryGetNavID))
		{ $intCustomerID = $rowGetNavID["RecordID"]; }

		if (!isset($intCustomerID))
		{
			$intCustomerID = 0;
		}
	}
	else
	{
		$intCustomerID = $_GET['CustomerID'];
    }
    
	// VALIDATE CUSTOMER VIA DATABASE
	if ($intCustomerID != 0)
	{
		$_SESSION['CustomerID'] = $intCustomerID;

		// QUERY CUSTOMER RECORDS

        // GMC - 11/16/08 Domestic Vs. International 2nd Phase
		// $qryGetCustomer = mssql_query("SELECT * FROM tblCustomers WHERE IsActive = 1 AND RecordID = " . $intCustomerID);
		$qryGetCustomer = mssql_query("SELECT tblCustomers.CustomerTypeID, tblCustomers.IsApprovedTerms, tblCustomers_ShipTo.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName FROM tblCustomers_ShipTo, tblCustomers WHERE tblCustomers.recordid = tblCustomers_shipto.customerid and tblcustomers_shipto.IsActive = 1 AND tblcustomers_shipto.IsDefault = 1 AND tblcustomers_shipto.customerid = " . $intCustomerID);

        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
        // GMC - 02/04/16 Modifications for the Edit - Delete - Address Shipping Addresses
		// $qryGetCustomerShipTo = mssql_query("SELECT RecordID, Attn, Address1, Address2, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND CustomerID = " . $intCustomerID . " ORDER BY Attn ASC");
		// $qryGetCustomerShipTo = mssql_query("SELECT RecordID, CompanyName, Attn, Address1, Address2, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND CustomerID = " . $intCustomerID . " ORDER BY Attn ASC");
		$qryGetCustomerShipTo = mssql_query("SELECT RecordID, CompanyName, Attn, Address1, Address2, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND IsDisplay = 1 AND CustomerID = " . $intCustomerID . " ORDER BY Attn ASC");

        $cboCountryCodes = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes ORDER BY SortOrder ASC, CountryName ASC");
    }

    // GMC - 02/04/16 Modifications for the Edit - Delete - Address Shipping Addresses
	if (isset($_POST['cmdContinue']) && $_POST['cmdContinue'] == "Add Address")
	{
	   if (isset($_POST['ShippingAddress']) && $_POST['ShippingAddress'] == 'New')
	   // ADD NEW SHIPPING ADDRESS AND SET SESSION
	   {
          // GMC - 03/24/09 - Add *REQUIRED on New Address
          // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
          // if(($_POST['FirstName'] != "") && ($_POST['LastName'] != "") && ($_POST['Address1'] != "") && ($_POST['City'] != "") && ($_POST['State'] != "") && ($_POST['PostalCode'] != "") && ($_POST['CountryCode'] != ""))
          if(($_POST['CompanyName'] != "") && ($_POST['ContactName'] != "") && ($_POST['Address1'] != "") && ($_POST['City'] != "") && ($_POST['State'] != "") && ($_POST['PostalCode'] != "") && ($_POST['CountryCode'] != ""))
          {
              // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
              $strCompanyName = $_POST['CompanyName'];
              // $strAttn = $_POST['FirstName'] . ' ' . $_POST['LastName'];
              $strAttn = $_POST['ContactName'];

              $strCountryCode = $_POST['CountryCode'];

              // GMC - 11/14/11 - Replace String process for Shipping Address
		      $tmpAddress1 = $_POST['Address1'];
		      $tmpAddress2 = $_POST['Address2'];
		      $tmpCity = $_POST['City'];

              // GMC - 03/18/12 - Hong Kong Change for City and Zip Code
              if($tmpCity == "CH01")
              {
                  $tmpCity = "Aberdeen";
              }
              else if($tmpCity == "CH02")
              {
                  $tmpCity = "Admiralty";
              }
              else if($tmpCity == "CH03")
              {
                  $tmpCity = "Ap lei Chau";
              }
              else if($tmpCity == "CH04")
              {
                  $tmpCity = "Causeway Bay";
              }
              else if($tmpCity == "CH05")
              {
                  $tmpCity = "Central";
              }
              else if($tmpCity == "CH06")
              {
                  $tmpCity = "Chaiwan";
              }
              else if($tmpCity == "CH07")
              {
                  $tmpCity = "Chek Lap Kok";
              }
              else if($tmpCity == "CH08")
              {
                  $tmpCity = "Cheung Chau";
              }
              else if($tmpCity == "CH09")
              {
                  $tmpCity = "Cheung Sha Wan";
              }
              else if($tmpCity == "CH10")
              {
                  $tmpCity = "Choi Hung";
              }
              else if($tmpCity == "CH11")
              {
                  $tmpCity = "Chok Ko Wan";
              }
              else if($tmpCity == "CH12")
              {
                  $tmpCity = "Chung Hom Kok";
              }
              else if($tmpCity == "CH13")
              {
                  $tmpCity = "Clear Water Bay";
              }
              else if($tmpCity == "CH14")
              {
                  $tmpCity = "Discovery Bay";
              }
              else if($tmpCity == "CH15")
              {
                  $tmpCity = "Fanling";
              }
              else if($tmpCity == "CH16")
              {
                  $tmpCity = "Fo Tan";
              }
              else if($tmpCity == "CH17")
              {
                  $tmpCity = "Happy Valley";
              }
              else if($tmpCity == "CH18")
              {
                  $tmpCity = "Ho Man Tin";
              }
              else if($tmpCity == "CH19")
              {
                  $tmpCity = "Hong Lok Yuen";
              }
              else if($tmpCity == "CH20")
              {
                  $tmpCity = "Hung Hom";
              }
              else if($tmpCity == "CH21")
              {
                  $tmpCity = "Jardine Lookout";
              }
              else if($tmpCity == "CH22")
              {
                  $tmpCity = "Jordan";
              }
              else if($tmpCity == "CH23")
              {
                  $tmpCity = "Junk Bay";
              }
              else if($tmpCity == "CH24")
              {
                  $tmpCity = "Kennedy Town";
              }
              else if($tmpCity == "CH25")
              {
                  $tmpCity = "Kowloon Bay";
              }
              else if($tmpCity == "CH26")
              {
                  $tmpCity = "Kowloon City";
              }
              else if($tmpCity == "CH27")
              {
                  $tmpCity = "Kowloon Tong";
              }
              else if($tmpCity == "CH28")
              {
                  $tmpCity = "Kwai Chung";
              }
              else if($tmpCity == "CH29")
              {
                  $tmpCity = "Kwai Fong";
              }
              else if($tmpCity == "CH30")
              {
                  $tmpCity = "Kwun Tong";
              }
              else if($tmpCity == "CH31")
              {
                  $tmpCity = "Lai Chi Kok";
              }
              else if($tmpCity == "CH32")
              {
                  $tmpCity = "Lam Tin";
              }
              else if($tmpCity == "CH33")
              {
                  $tmpCity = "Lamma Island";
              }
              else if($tmpCity == "CH34")
              {
                  $tmpCity = "Lantau Island";
              }
              else if($tmpCity == "CH35")
              {
                  $tmpCity = "Lei Yue Mun";
              }
              else if($tmpCity == "CH36")
              {
                  $tmpCity = "Ma On Shan";
              }
              else if($tmpCity == "CH37")
              {
                  $tmpCity = "Ma Wan";
              }
              else if($tmpCity == "CH38")
              {
                  $tmpCity = "Mid-Level";
              }
              else if($tmpCity == "CH39")
              {
                  $tmpCity = "Mongkok";
              }
              else if($tmpCity == "CH40")
              {
                  $tmpCity = "Ngau Tau Kok";
              }
              else if($tmpCity == "CH41")
              {
                  $tmpCity = "North Point";
              }
              else if($tmpCity == "CH42")
              {
                  $tmpCity = "Peak";
              }
              else if($tmpCity == "CH43")
              {
                  $tmpCity = "Peng Chau";
              }
              else if($tmpCity == "CH44")
              {
                  $tmpCity = "Pennys Bay";
              }
              else if($tmpCity == "CH45")
              {
                  $tmpCity = "Pokfulam";
              }
              else if($tmpCity == "CH46")
              {
                  $tmpCity = "Quarry Bay";
              }
              else if($tmpCity == "CH47")
              {
                  $tmpCity = "Queensway";
              }
              else if($tmpCity == "CH48")
              {
                  $tmpCity = "Repulse Bay";
              }
              else if($tmpCity == "CH49")
              {
                  $tmpCity = "Sai Kung";
              }
              else if($tmpCity == "CH50")
              {
                  $tmpCity = "Sai Wan Ho";
              }
              else if($tmpCity == "CH51")
              {
                  $tmpCity = "Sai Ying Pun";
              }
              else if($tmpCity == "CH52")
              {
                  $tmpCity = "San Po Kong";
              }
              else if($tmpCity == "CH53")
              {
                  $tmpCity = "Sham Shui Po";
              }
              else if($tmpCity == "CH54")
              {
                  $tmpCity = "Sham Tseng";
              }
              else if($tmpCity == "CH55")
              {
                  $tmpCity = "Shatin";
              }
              else if($tmpCity == "CH56")
              {
                  $tmpCity = "Shaukiwan";
              }
              else if($tmpCity == "CH57")
              {
                  $tmpCity = "Shek Kip Mei";
              }
              else if($tmpCity == "CH58")
              {
                  $tmpCity = "Shek O";
              }
              else if($tmpCity == "CH59")
              {
                  $tmpCity = "Sheung Shui";
              }
              else if($tmpCity == "CH60")
              {
                  $tmpCity = "Sheung Wan";
              }
              else if($tmpCity == "CH61")
              {
                  $tmpCity = "Siu Ho Wan";
              }
              else if($tmpCity == "CH62")
              {
                  $tmpCity = "Siu Lik Yuen";
              }
              else if($tmpCity == "CH63")
              {
                  $tmpCity = "Siu Sai Wan";
              }
              else if($tmpCity == "CH64")
              {
                  $tmpCity = "South Bay";
              }
              else if($tmpCity == "CH65")
              {
                  $tmpCity = "Stanley";
              }
              else if($tmpCity == "CH66")
              {
                  $tmpCity = "Tai Hang";
              }
              else if($tmpCity == "CH67")
              {
                  $tmpCity = "Tai Kok Tsui";
              }
              else if($tmpCity == "CH68")
              {
                  $tmpCity = "Tai O";
              }
              else if($tmpCity == "CH69")
              {
                  $tmpCity = "Tai Wai";
              }
              else if($tmpCity == "CH70")
              {
                  $tmpCity = "Taipo";
              }
              else if($tmpCity == "CH71")
              {
                  $tmpCity = "Tin Shui Wai";
              }
              else if($tmpCity == "CH72")
              {
                  $tmpCity = "Tokwawan";
              }
              else if($tmpCity == "CH73")
              {
                  $tmpCity = "Tseung Kwan O";
              }
              else if($tmpCity == "CH74")
              {
                  $tmpCity = "Tsimshatsui";
              }
              else if($tmpCity == "CH75")
              {
                  $tmpCity = "Tsimshatsui East";
              }
              else if($tmpCity == "CH76")
              {
                  $tmpCity = "Tsing Yi";
              }
              else if($tmpCity == "CH77")
              {
                  $tmpCity = "Tsuen Wan";
              }
              else if($tmpCity == "CH78")
              {
                  $tmpCity = "Tsz Wan Shan";
              }
              else if($tmpCity == "CH79")
              {
                  $tmpCity = "Tuen Mun";
              }
              else if($tmpCity == "CH80")
              {
                  $tmpCity = "Tung Chung";
              }
              else if($tmpCity == "CH81")
              {
                  $tmpCity = "Wanchai";
              }
              else if($tmpCity == "CH82")
              {
                  $tmpCity = "Wang Tau Hom";
              }
              else if($tmpCity == "CH83")
              {
                  $tmpCity = "Western";
              }
              else if($tmpCity == "CH84")
              {
                  $tmpCity = "Wong Chuk Hang";
              }
              else if($tmpCity == "CH85")
              {
                  $tmpCity = "Yau Ma Tei";
              }
              else if($tmpCity == "CH86")
              {
                  $tmpCity = "Yau Tong";
              }
              else if($tmpCity == "CH87")
              {
                  $tmpCity = "Yau Yat Chuen";
              }
              else if($tmpCity == "CH88")
              {
                  $tmpCity = "Yuen Long";
              }

		      $tmpState = $_POST['State'];
		      $tmpPostalCode = $_POST['PostalCode'];
		      $strAddress1 = ereg_replace("[^A-Za-z0-9 ]", "", $tmpAddress1);
		      $strAddress2 = ereg_replace("[^A-Za-z0-9 ]", "", $tmpAddress2);
		      $strCity = ereg_replace("[^A-Za-z0-9 ]", "", $tmpCity);
		      $strState = ereg_replace("[^A-Za-z0-9 ]", "", $tmpState);
		      $strPostalCode = ereg_replace("[^A-Za-z0-9 ]", "", $tmpPostalCode);

              // GMC - 02/01/11 - Order Closed By CSR ADMIN Partner - Rep
              $_SESSION['OrderClosedBy'] = $_POST['OrderClosedBy'];

		      //INITIALIZE SPROC
		      $qrySetShipping = mssql_init("spOrders_SetShipping", $connNewOrder);
		      $intStatusCode = 0;

		      //BIND INPUT PARAMETERS
		      mssql_bind($qrySetShipping, "@prmCustomerID", $_GET['CustomerID'], SQLINT4);

              // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
		      mssql_bind($qrySetShipping, "@prmCompanyName", $strCompanyName, SQLVARCHAR);

		      mssql_bind($qrySetShipping, "@prmAttn", $strAttn, SQLVARCHAR);
		      mssql_bind($qrySetShipping, "@prmAddress1", $strAddress1, SQLVARCHAR);
		      mssql_bind($qrySetShipping, "@prmAddress2", $strAddress2, SQLVARCHAR);
		      mssql_bind($qrySetShipping, "@prmCity", $strCity, SQLVARCHAR);
		      mssql_bind($qrySetShipping, "@prmState", $strState, SQLVARCHAR);
		      mssql_bind($qrySetShipping, "@prmPostalCode", $strPostalCode, SQLVARCHAR);
		      mssql_bind($qrySetShipping, "@prmCountryCode", $strCountryCode, SQLVARCHAR);

		      $rsGetAddress = mssql_execute($qrySetShipping);

		      if (mssql_num_rows($rsGetAddress) > 0)
		      {
			     while($row = mssql_fetch_array($rsGetAddress))
			     {
				    $_SESSION['IsShippingSet'] = 1;
				    $_SESSION['CustomerShipToID'] = $row["RecordID"];

                    // GMC - 01/14/09 - CA - NV Sales Tax
				    $_SESSION['Ship_State'] = $row["State"];
		         }
              }
		  }
		  else
		  {
		      $_SESSION['NoNewAddress'] = "True";
              echo '<script language="javascript">alert("You have not entered a valid shipping address, the required fields are: CompanyName, ContactName, Address1, City, State or Province, Postal Code and Country, please try again.")</script>;';
		  }
	   }
       else
       {
		    $_SESSION['NoNewAddress'] = "True";
            echo '<script language="javascript">alert("You have not selected the New Shipping Address Radio Button, please try again.")</script>;';
       }
    }
    else if (isset($_POST['cmdContinue']) && $_POST['cmdContinue'] == "Use Address")
    {
       if (isset($_POST['ShippingAddress']) && $_POST['ShippingAddress'] != "")
       // SET SESSION
       {
           $_SESSION['CustomerShipToID'] = $_POST['ShippingAddress'];
           $_SESSION['DeleteEditAddress'] = 'True';
           $_SESSION['IsShippingSet'] = 1;

           if ($_SESSION['UserTypeID'] == 2 || $_SESSION['UserTypeID'] == 4)
           {
               $_SESSION['OrderClosedBy'] = $_POST['OrderClosedBy'];
           }

           // UPDATE RECORD IN TBLCUSTOMERS_SHIPTO

           //INITIALIZE SPROC
		   $qrySetUpdateShipping = mssql_init("spOrders_SetUpdateShipping", $connNewOrder);
		   $intStatusCode = 0;

		   //BIND INPUT PARAMETERS
		   mssql_bind($qrySetUpdateShipping, "@prmRecordID", $_SESSION['CustomerShipToID'], SQLINT4);
		   mssql_bind($qrySetUpdateShipping, "@prmCustomerID", $_SESSION['CustomerID'], SQLINT4);

		   $rsSetUpdateAddress = mssql_execute($qrySetUpdateShipping);

           // GMC - 01/14/09 - CA - NV Sales Tax
           if (mssql_num_rows($rsSetUpdateAddress) > 0)
		   {
			  while($row = mssql_fetch_array($rsSetUpdateAddress))
			  {
				  $_SESSION['Ship_State'] = $row["State"];
		      }
           }
           $_SESSION['UseDeleteEditAddress'] = 'True';
       }
       else
       {
           $_SESSION['UseDeleteEditAddress'] = 'False';
           echo '<script language="javascript">alert("You have not selected a shipping address, please try again.")</script>;';
       }
    }
    else if (isset($_POST['cmdContinue']) && $_POST['cmdContinue'] == "Edit Address")
    {
       if (isset($_POST['ShippingAddress']) && $_POST['ShippingAddress'] != "")
       // SET SESSION
       {
		   include("../includes/selCountries_Prior.php");
           $_SESSION['CustomerShipToID'] = $_POST['ShippingAddress'];
           $qryGetShippingAddressesToModify = mssql_query("SELECT * FROM tblCustomers_ShipTo WHERE RecordID = " . $_SESSION['CustomerShipToID'] . " AND CustomerID = " . $intCustomerID);
           $_SESSION['UseDeleteEditAddress'] = 'True';
       }
       else
       {
           $_SESSION['UseDeleteEditAddress'] = 'False';
           echo '<script language="javascript">alert("You have not selected a shipping address, please try again.")</script>;';
       }
    }
    else if (isset($_POST['cmdContinue']) && $_POST['cmdContinue'] == "Delete Address")
    {
       if (isset($_POST['ShippingAddress']) && $_POST['ShippingAddress'] != "")
       // SET SESSION
       {
           $_SESSION['CustomerShipToID'] = $_POST['ShippingAddress'];

           // DELETE (SOFT) RECORD IN TBLCUSTOMERS_SHIPTO

		   //INITIALIZE SPROC
		   $qrySetDeleteShipping = mssql_init("spOrders_SetDeleteShipping", $connNewOrder);
		   $intStatusCode = 0;

		   //BIND INPUT PARAMETERS
		   mssql_bind($qrySetDeleteShipping, "@prmRecordID", $_SESSION['CustomerShipToID'], SQLINT4);

		   $rsSetDeleteAddress = mssql_execute($qrySetDeleteShipping);
     
           $_SESSION['UseDeleteEditAddress'] = 'True';
       }
       else
       {
           $_SESSION['UseDeleteEditAddress'] = 'False';
           echo '<script language="javascript">alert("You have not selected a shipping address, please try again.")</script>;';
       }
    }

	// CLOSE DATABASE CONNECTION
	mssql_close($connNewOrder);
}

// GMC - 12/03/08 - Domestic Vs. International 3rd Phase
elseif ($_GET['Action'] == 'SelectAddress')
// ********** UPDATE ADDRESS **********
{
	$pagerror = 0;

	// CONNECT TO SQL SERVER DATABASE
	$connNewOrder = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connNewOrder);

	// ATTEMPT TO SET CUSTOMER ID BASED ON URL STRING
	if (isset($_GET['NavCustomerID']))
	{
		$qryGetNavID = mssql_query("SELECT RecordID FROM tblCustomers WHERE NavisionCustomerID = '" . $_GET['NavCustomerID'] . "'");

		while($rowGetNavID = mssql_fetch_array($qryGetNavID))
		{ $intCustomerID = $rowGetNavID["RecordID"]; }

		if (!isset($intCustomerID))
		{
			$intCustomerID = 0;
		}
	}
	else
	{
		$intCustomerID = $_GET['CustomerID'];
    }

	// VALIDATE CUSTOMER VIA DATABASE
	if ($intCustomerID != 0)
	{
		$_SESSION['CustomerID'] = $intCustomerID;

		// QUERY CUSTOMER RECORDS

        // GMC - 11/16/08 Domestic Vs. International 2nd Phase
		// $qryGetCustomer = mssql_query("SELECT * FROM tblCustomers WHERE IsActive = 1 AND RecordID = " . $intCustomerID);
		$qryGetCustomer = mssql_query("SELECT tblCustomers.CustomerTypeID, tblCustomers.IsApprovedTerms, tblCustomers_ShipTo.CountryCode, tblCustomers.CompanyName, tblCustomers.FirstName, tblCustomers.LastName FROM tblCustomers_ShipTo, tblCustomers WHERE tblCustomers.recordid = tblCustomers_shipto.customerid and tblcustomers_shipto.IsActive = 1 AND tblcustomers_shipto.IsDefault = 1 AND tblcustomers_shipto.customerid = " . $intCustomerID);

        // GMC - 06/03/10 - Add Company Name to tblCustomers_ShiptTo
        // GMC - 02/04/16 Modifications for the Edit - Delete - Address Shipping Addresses
		// $qryGetCustomerShipTo = mssql_query("SELECT RecordID, Attn, Address1, Address2, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND CustomerID = " . $intCustomerID . " ORDER BY Attn ASC");
		// $qryGetCustomerShipTo = mssql_query("SELECT RecordID, CompanyName, Attn, Address1, Address2, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND CustomerID = " . $intCustomerID . " ORDER BY Attn ASC");
		$qryGetCustomerShipTo = mssql_query("SELECT RecordID, CompanyName, Attn, Address1, Address2, City, State, PostalCode, CountryCode FROM tblCustomers_ShipTo WHERE IsActive = 1 AND  IsDisplay = 1 AND CustomerID = " . $intCustomerID . " ORDER BY Attn ASC");

		$cboCountryCodes = mssql_query("SELECT CountryCode, CountryName FROM conCountryCodes ORDER BY SortOrder ASC, CountryName ASC");
    }

	// CLOSE DATABASE CONNECTION
	mssql_close($connNewOrder);
}

elseif ($_GET['Action'] == 'Search')
// ********** CUSTOMER SEARCH **********
{
    // GMC - 12/03/08 - Domestic vs International 3rd Phase

	// RESET SESSION VARIABLES
	foreach ($_SESSION as $key => $value)
    {
        // GMC - 12/03/08 - Domestic vs International 3rd Phase
		// if ($key != 'IsRevitalashLoggedIn' && $key != 'UserID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress' && $key != 'UserTypeID')
		if ($key != 'IsRevitalashLoggedIn' && $key != 'UserID' && $key != 'FirstName' && $key != 'LastName' && $key != 'EMailAddress' && $key != 'Address' && $key != 'UserTypeID')
		{
			unset($_SESSION[$key]);
		}
	}
	
	$tblCustomerList = '';
	
	// CONNECT TO SQL SERVER DATABASE
	$connCustomer = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	$selected = mssql_select_db($dbName, $connCustomer);
	
	if ($_SESSION['UserTypeID'] == 2)
	{
		$strSQL = "SELECT TOP 50 RecordID, FirstName, LastName, CompanyName, EMailAddress, CustomerTypeID, Telephone, Address1, City, State, PostalCode, NavisionCustomerID FROM tblCustomers WHERE IsActive = 1";

        // GMC - 12/17/08 - Add CustomerId Search
		if ($_POST['CustomerID'] != '')
			$strSQL .= " AND NavisionCustomerID LIKE '%" . $_POST['CustomerID'] . "%'";

        // GMC - 12/18/08 - Add Telephone and join FirstName + LastName = Contact Search
        /*
        if ($_POST['FirstName'] != '')
			$strSQL .= " AND FirstName LIKE '%" . $_POST['FirstName'] . "%'";
		if ($_POST['LastName'] != '')
			$strSQL .= " AND LastName LIKE '%" . $_POST['LastName'] . "%'";
		*/
			
        if ($_POST['ContactName'] != '')
			$strSQL .= " AND FirstName LIKE '%" . $_POST['ContactName'] . "%'";
		if ($_POST['ContactName'] != '')
			$strSQL .= " OR LastName LIKE '%" . $_POST['ContactName'] . "%'";
		if ($_POST['ContactNumber'] != '')
			$strSQL .= " AND Telephone LIKE '%" . $_POST['ContactNumber'] . "%'";

		if ($_POST['CompanyName'] != '')
			$strSQL .= " AND CompanyName LIKE '%" . $_POST['CompanyName'] . "%'";
		if ($_POST['EMailAddress'] != '')
			$strSQL .= " AND EMailAddress LIKE '%" . $_POST['EMailAddress'] . "%'";

        // GMC - 12/03/08 - Domestic vs International 3rd Phase
		if ($_POST['Address'] != '')
			$strSQL .= " AND Address1 LIKE '%" . $_POST['Address'] . "%'";

		$strSQL .= " ORDER BY RecordID DESC";
	}
 
    // GMC - 03/17/14 - New User ID 3 Sales Specialist
	// elseif ($_SESSION['UserTypeID'] == 1)
	elseif ($_SESSION['UserTypeID'] == 1 || $_SESSION['UserTypeID'] == 3)
	{
		$strSQL = "SELECT TOP 50 RecordID, FirstName, LastName, CompanyName, EMailAddress, CustomerTypeID, Telephone, Address1, City, State, PostalCode, NavisionCustomerID FROM tblCustomers WHERE IsActive = 1";

        // GMC - 12/17/08 - Add CustomerId Search
		if ($_POST['CustomerID'] != '')
			$strSQL .= " AND NavisionCustomerID LIKE '%" . $_POST['CustomerID'] . "%'";

        // GMC - 12/18/08 - Add Telephone and join FirstName + LastName = Contact Search
        /*
		if ($_POST['FirstName'] != '')
			$strSQL .= " AND FirstName LIKE '%" . $_POST['FirstName'] . "%'";
		if ($_POST['LastName'] != '')
			$strSQL .= " AND LastName LIKE '%" . $_POST['LastName'] . "%'";
        */
        
        if ($_POST['ContactName'] != '')
			$strSQL .= " AND FirstName LIKE '%" . $_POST['ContactName'] . "%'";
		if ($_POST['ContactName'] != '')
			$strSQL .= " OR LastName LIKE '%" . $_POST['ContactName'] . "%'";
		if ($_POST['ContactNumber'] != '')
			$strSQL .= " AND Telephone LIKE '%" . $_POST['ContactNumber'] . "%'";

        if ($_POST['CompanyName'] != '')
			$strSQL .= " AND CompanyName LIKE '%" . $_POST['CompanyName'] . "%'";
		if ($_POST['EMailAddress'] != '')
			$strSQL .= " AND EMailAddress LIKE '%" . $_POST['EMailAddress'] . "%'";
		
        // GMC - 12/03/08 - Domestic vs International 3rd Phase
		if ($_POST['Address'] != '')
			$strSQL .= " AND Address1 LIKE '%" . $_POST['Address'] . "%'";

		$strSQL .= " AND SalesRepID = " . $_SESSION['UserID'];
		$strSQL .= " ORDER BY RecordID DESC";
	}

    // GMC - 11/13/14 - Fix Search for UserTypeID 4
	elseif ($_SESSION['UserTypeID'] == 4)
	{
		$strSQL = "SELECT TOP 50 RecordID, FirstName, LastName, CompanyName, EMailAddress, CustomerTypeID, Telephone, Address1, City, State, PostalCode, NavisionCustomerID FROM tblCustomers WHERE IsActive = 1";

		if ($_POST['CustomerID'] != '')
			$strSQL .= " AND NavisionCustomerID LIKE '%" . $_POST['CustomerID'] . "%'";
        if ($_POST['ContactName'] != '')
			$strSQL .= " AND FirstName LIKE '%" . $_POST['ContactName'] . "%'";
		if ($_POST['ContactName'] != '')
			$strSQL .= " OR LastName LIKE '%" . $_POST['ContactName'] . "%'";
		if ($_POST['ContactNumber'] != '')
			$strSQL .= " AND Telephone LIKE '%" . $_POST['ContactNumber'] . "%'";

        if ($_POST['CompanyName'] != '')
			$strSQL .= " AND CompanyName LIKE '%" . $_POST['CompanyName'] . "%'";
		if ($_POST['EMailAddress'] != '')
			$strSQL .= " AND EMailAddress LIKE '%" . $_POST['EMailAddress'] . "%'";
		if ($_POST['Address'] != '')
			$strSQL .= " AND Address1 LIKE '%" . $_POST['Address'] . "%'";

		$strSQL .= " ORDER BY RecordID DESC";
	}

	// QUERY CUSTOMER RECORDS
	$qryGetCustomer = mssql_query($strSQL);

    // GMC - 07/22/09 - Change the order of the columns by JS
	while($row = mssql_fetch_array($qryGetCustomer))
	{
		$tblCustomerList .= '<tr class="tdwhite">';
		$tblCustomerList .= '<td>' . $row["NavisionCustomerID"] . '</td>';
		$tblCustomerList .= '<td><a href="/csradmin/customers.php?Action=Detail&CustomerID=' . $row["RecordID"] . '">' . $row["CompanyName"] . '</a></td>';
		$tblCustomerList .= '<td><a href="/csradmin/customers.php?Action=Detail&CustomerID=' . $row["RecordID"] . '">' . $row["FirstName"] . ' ' . $row["LastName"] . '</a></td>';
		$tblCustomerList .= '<td>' . $row["Address1"] . ' ' . $row["City"] . ', ' . $row["State"] . ' ' . $row["PostalCode"] . '</td>';
		$tblCustomerList .= '<td>' . $row["Telephone"] . '</td>';
		$tblCustomerList .= '</tr>';
	}

	// CLOSE DATABASE CONNECTION
	mssql_close($connCustomer);
}

?>

<head>
	<title>Customer Management | Revitalash Administration</title>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="/csradmin/styles/revitalash.css" type="text/css" />

    <!-- GMC - 07/14/12 - Image Pop Up for Products -->
	<link rel="stylesheet" href="/csradmin/styles/ImagePopup.css" type="text/css" />
	<script type="text/javascript" src="/csradmin/js/ImagePopup.js"></script>
</head>

<body>

	<div id="wrapper">

        <!-- GMC - 12/03/08 - Domestic vs International 3rd Phase
		<div><img src="/csradmin/images/bg_masthead.gif" alt="Revitalash Administration" width="950" height="91" /></div>
        -->
        
		<?php include("includes/dspMasthead.php"); ?>
		
		<div id="content">

			<?php

			if ((!isset($_GET['Action'])) || ($_GET['Action'] == 'Index') || ($_GET['Action'] == 'Overview'))
			{
				include("includes/dspCustomers_Index.php");
            }
            elseif ($_GET['Action'] == 'Detail')
            {
				include("includes/dspCustomers_Detail.php");
            }

            // GMC - 05/23/13 - CRM LM System
            elseif ($_GET['Action'] == 'CRMSummary')
            {
				include("includes/dspCRM_Summary.php");
            }

            elseif ($_GET['Action'] == 'CRMDetail')
            {
				include("includes/dspCRM_Detail.php");
            }

            elseif ($_GET['Action'] == 'CRMEntry')
            {
				include("includes/dspCRM_Entry_Result.php");
            }

            elseif ($_GET['Action'] == 'SearchCRMList')
            {
				include("includes/dspCRM_Summary.php");
            }

            elseif ($_GET['Action'] == 'EditProfile')
            {
                // GMC - 02/04/16 Modifications for the Edit - Delete - Address Shipping Addresses
				// include("includes/dspCustomers_EditProfile.php");
                if ($_SESSION['DeleteEditAddress'] == 'True')
                {
                    // header("Location: http://secure.revitalash.com/csradmin/customers.php");
                    header("Location: http://localhost/csradmin/customers.php");
                }
            }
			elseif ($_GET['Action'] == 'NewOrder')
			{
				if (isset($blnIsApproved))
				{
					if (isset($_POST['cmdProcess']) && !isset($blnPaymentError))
					{
                       // Fourth and Last Screen if No Errors in New Order Process
					   include("includes/dspCustomers_OrderConfirmation.php");
                    }
                    elseif (isset($_POST['cmdProcess']) && isset($blnPaymentError))
                    {
                        // Fifth Screen if Error in New Order Process
      		           include("includes/dspCustomers_PaymentError.php");
                    }
                    elseif (isset($_POST['cmdReview']))
                    {
                        // Third Screen in New Order Process
                       include("includes/dspCustomers_ReviewOrder.php");
                    }
                    elseif (isset($_POST['cmdContinue']) && !isset($pageerror))
					{
					    // Second Screen in New Order Process
					   include("includes/dspCustomers_OrderPricing.php");
                    }
                    else
					{
					    // First Screen in New Order Process
					   include("includes/dspCustomers_NewOrder.php");
                    }
                }
				else
				{
					include("includes/dspCustomers_NotFound.php");
				}
			}

            // GMC - 12/03/08 - Domestic Vs. International 3rd Phase
			elseif ($_GET['Action'] == 'SelectAddress')
			{
                   include("includes/dspCustomers_UpdateAddress.php");
            }
            // GMC - 02/04/16 Modifications for the Edit - Delete - Address Shipping Addresses
			elseif ($_GET['Action'] == 'UpdateAddress')
			{
                    if($_SESSION['NoNewAddress'] == 'True')
                    {
                        include("includes/dspCustomers_UpdateAddress.php");
                    }
                    elseif (isset($_POST['cmdContinue']) && $_POST['cmdContinue'] == "Use Address")
                    {
                        if ($_SESSION['UseDeleteEditAddress'] == 'True')
                        {
                            include("includes/dspCustomers_BackNewOrder.php");
                        }
                        else
                        {
                            include("includes/dspCustomers_UpdateAddress.php");
                        }
                    }
                    elseif (isset($_POST['cmdContinue']) && $_POST['cmdContinue'] == "Delete Address")
                    {
                        if ($_SESSION['UseDeleteEditAddress'] == 'True')
                        {
                            // header("Location: https://ae.secure.revitalash.com/csradmin/customers.php");
                            header("Location: http://localhost/csradmin/customers.php");
                        }
                        else
                        {
                            include("includes/dspCustomers_UpdateAddress.php");
                        }
                    }
                    elseif (isset($_POST['cmdContinue']) && $_POST['cmdContinue'] == "Edit Address")
                    {
                        if ($_SESSION['UseDeleteEditAddress'] == 'True')
                        {
                            include("includes/dspCustomers_EditProfile.php");
                        }
                        else
                        {
                            include("includes/dspCustomers_UpdateAddress.php");
                        }
                    }
            }

            elseif ($_GET['Action'] == 'Search')
            {
				include("includes/dspCustomers_Search.php");
            }
            
			?>
		
		</div>
		
	</div>
    
    <?php
		//include_once("../modules/dBug.php");
		//new dBug($_SESSION);
	?>

</body>

</html>
