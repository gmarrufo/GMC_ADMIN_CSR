<?php

	require_once('../modules/authorize_check.php');
	
	$a = new authorizenet_class;

	$a->add_field('x_login', '5Qv27aBQJ3');
	$a->add_field('x_tran_key', '282K76ys6Lb2XBPq');
	$a->add_field('x_version', '3.1');
	//$a->add_field('x_test_request', 'FALSE');
	$a->add_field('x_test_request', 'TRUE');    // Test transaction
	$a->add_field('x_relay_response', 'FALSE');
	$a->add_field('x_delim_data', 'TRUE');
	$a->add_field('x_delim_char', '|');     
	$a->add_field('x_encap_char', '');
	$a->add_field('x_first_name', 'Test');
	$a->add_field('x_last_name', 'Tester');
	$a->add_field('x_invoice_num', 'ABC123');
	$a->add_field('x_method', 'ECHECK');
	$a->add_field('x_echeck_type', 'WEB');
	$a->add_field('x_bank_aba_code', '121000358');
	$a->add_field('x_bank_acct_num', '123456789');
	$a->add_field('x_bank_acct_type', 'CHECKING');
	$a->add_field('x_bank_name', 'Bank of America');
	$a->add_field('x_bank_acct_name', 'Alexander Fowler');
	$a->add_field('x_recurring_billing', 'FALSE');
	$a->add_field('x_amount', '1.00');
	
	// Process the payment and output the results
	switch ($a->process())
	{
		case 1:  // Successs
		echo $a->dump_response();
		break;
	
		case 2:  // Declined
		$blnPaymentError = 1;
		echo $a->dump_response();
		break;
	
		case 3:  // Error
		$blnPaymentError = 2;
		echo $a->dump_response();
		break;
	}*/


?>