<?php 

$_SESSION['IsPaymentSet'] = 1;

if ($_POST['PaymentType'] == 'CreditCard')
{
	if (isset($_POST['CC_SwipedAuthorization']) && $_POST['CC_SwipedAuthorization'] != '')
	{
		$_SESSION['PaymentType'] = 'CreditCardSwiped';
		$_SESSION['PaymentCC_SwipedAuth'] = $_POST['CC_SwipedAuthorization'];
  
        // GMC - 07/14/11 - Distributors Change CSRADMIN
        $_SESSION['Distributor_Code'] =  $_POST['Distributor_Code'];
        $_SESSION['Promised_Date'] =  $_POST['Promised_Date'];

        // GMC - 12/11/13 - Magento Manual Process
        $_SESSION['MagentoFlag'] = 'True';
	}
	else
	{	
		$_SESSION['PaymentType'] = $_POST['PaymentType'];
		$_SESSION['PaymentCC_Number'] = $_POST['CC_Number'];
		$_SESSION['PaymentCC_Cardholder'] = $_POST['CC_Cardholder'];
		$_SESSION['PaymentCC_ExpMonth'] = $_POST['CC_ExpMonth'];
		$_SESSION['PaymentCC_ExpYear'] = $_POST['CC_ExpYear'];
		$_SESSION['PaymentCC_CVV'] = $_POST['CC_CVV'];
		$_SESSION['PaymentCC_BillingPostalCode'] = $_POST['CC_BillingPostalCode'];

        // GMC - 01/03/12 - Promo Code for Resellers
        // GMC - 09/12/13 - Cancel Promo Code for Resellers
        // GMC - 02/02/14 - Put Back Promo Code for Resellers
        if(isset($_POST['CC_PromoCode']))
        {
		    $_SESSION['PaymentCC_PromoCode'] = strtoupper($_POST['CC_PromoCode']);
        }

        // GMC - 07/17/10 - Clean up PO Number
        // GMC - 07/22/10 - Fix bug clean up PO number
		// $_SESSION['PaymentPO_Number'] = $_POST['PO_Number'];

        if($_POST['PO_Number'] == '')
        {
            $_SESSION['PaymentPO_Number'] = "";
        }
        else
        {
            $str = strtoupper($_POST['PO_Number']);
            $str = just_clean($str);
            $_SESSION['PaymentPO_Number'] = $str;
        }

        // GMC - 07/14/11 - Distributors Change CSRADMIN
        $_SESSION['Distributor_Code'] =  $_POST['Distributor_Code'];
        $_SESSION['Promised_Date'] =  $_POST['Promised_Date'];
	}
}
elseif ($_POST['PaymentType'] == 'ECheck')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];
	$_SESSION['PaymentCK_AccountType'] = $_POST['CK_AccountType'];
	$_SESSION['PaymentCK_BankName'] = $_POST['CK_BankName'];
	$_SESSION['PaymentCK_AccountName'] = $_POST['CK_AccountName'];
	$_SESSION['PaymentCK_BankRouting'] = $_POST['CK_BankRouting'];
	$_SESSION['PaymentCK_BankAccount'] = $_POST['CK_BankAccount'];

    // GMC - 07/17/10 - Clean up PO Number
    // GMC - 07/22/10 - Fix bug clean up PO number
	// $_SESSION['PaymentPO_Number'] = $_POST['PO_Number'];

    if($_POST['PO_Number'] == '')
    {
	    $_SESSION['PaymentPO_Number'] = "";
    }
    else
    {
        $str = strtoupper($_POST['PO_Number']);
        $str = just_clean($str);
	    $_SESSION['PaymentPO_Number'] = $str;
    }
    
    // GMC - 07/14/11 - Distributors Change CSRADMIN
    $_SESSION['Distributor_Code'] =  $_POST['Distributor_Code'];
    $_SESSION['Promised_Date'] =  $_POST['Promised_Date'];
}
elseif ($_POST['PaymentType'] == 'Terms')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];

    // GMC - 07/17/10 - Clean up PO Number
    // GMC - 07/22/10 - Fix bug clean up PO number
	// $_SESSION['PaymentPO_Number'] = $_POST['PO_Number'];

    if($_POST['PO_Number'] == '')
    {
	    $_SESSION['PaymentPO_Number'] = "";
    }
    else
    {
        $str = strtoupper($_POST['PO_Number']);
        $str = just_clean($str);
	    $_SESSION['PaymentPO_Number'] = $str;
    }

    // GMC - 07/14/11 - Distributors Change CSRADMIN
    $_SESSION['Distributor_Code'] =  $_POST['Distributor_Code'];
    $_SESSION['Promised_Date'] =  $_POST['Promised_Date'];
}

// GMC - 10/31/08 - To accomodate the NOCHARGE Process
elseif ($_POST['PaymentType'] == 'NOCHARGE')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];

    // GMC - 07/14/11 - Distributors Change CSRADMIN
    $_SESSION['Distributor_Code'] =  $_POST['Distributor_Code'];
    $_SESSION['Promised_Date'] =  $_POST['Promised_Date'];
}

//GMC - 02/20/09 - New Payment Types visible for CRSAdmins Only
elseif ($_POST['PaymentType'] == 'Check')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];
    $_SESSION['PaymentCK_BankAccount'] = $_POST['Check_Number'];

    // GMC - 07/14/11 - Distributors Change CSRADMIN
    $_SESSION['Distributor_Code'] =  $_POST['Distributor_Code'];
    $_SESSION['Promised_Date'] =  $_POST['Promised_Date'];
}
elseif ($_POST['PaymentType'] == 'Wire')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];
    $_SESSION['PaymentCK_BankAccount'] = $_POST['Wire_Number'];

    // GMC - 07/14/11 - Distributors Change CSRADMIN
    $_SESSION['Distributor_Code'] =  $_POST['Distributor_Code'];
    $_SESSION['Promised_Date'] =  $_POST['Promised_Date'];
}
elseif ($_POST['PaymentType'] == 'Cash')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];

    // GMC - 07/14/11 - Distributors Change CSRADMIN
    $_SESSION['Distributor_Code'] =  $_POST['Distributor_Code'];
    $_SESSION['Promised_Date'] =  $_POST['Promised_Date'];
}

//  GMC - 04/12/10 - LTL Shipments
// GMC - 05/12/10 - LTL Shipment Not a Payment Type
/*
elseif ($_POST['PaymentType'] == 'LTL')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];
}
*/

?>
