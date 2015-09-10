<?php 

$_SESSION['IsPaymentSet'] = 1;

if ($_POST['PaymentType'] == 'CreditCard')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];
	$_SESSION['PaymentCC_Number'] = $_POST['CC_Number'];
	$_SESSION['PaymentCC_Cardholder'] = $_POST['CC_Cardholder'];
	$_SESSION['PaymentCC_ExpMonth'] = $_POST['CC_ExpMonth'];
	$_SESSION['PaymentCC_ExpYear'] = $_POST['CC_ExpYear'];
	$_SESSION['PaymentCC_CVV'] = $_POST['CC_CVV'];
	$_SESSION['PaymentCC_BillingPostalCode'] = $_POST['CC_BillingPostalCode'];
}
elseif ($_POST['PaymentType'] == 'ECheck')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];
	$_SESSION['PaymentCK_AccountType'] = $_POST['CK_AccountType'];
	$_SESSION['PaymentCK_BankName'] = $_POST['CK_BankName'];
	$_SESSION['PaymentCK_AccountName'] = $_POST['CK_AccountName'];
	$_SESSION['PaymentCK_BankRouting'] = $_POST['CK_BankRouting'];
	$_SESSION['PaymentCK_BankAccount'] = $_POST['CK_BankAccount'];
}

elseif ($_POST['PaymentType'] == 'Terms')
{
	$_SESSION['PaymentType'] = $_POST['PaymentType'];
}

?>