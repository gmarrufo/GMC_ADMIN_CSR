<h1>Order Error</h1>

<p>Your order was not processed for the following reasons:</p>

<?php

if ($_SESSION['PaymentType'] == 'CreditCard' && $blnPaymentError == 1)
	echo '<p style="text-align:center; font-weight:bold; margin:100px;">The customer&rsquo;s credit card was declined. <a href="/csradmin/customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '&EditPayment">Click here to input new payment information</a>.</p>';
elseif ($_SESSION['PaymentType'] == 'ECheck' && $blnPaymentError == 1)
	echo '<p style="text-align:center; font-weight:bold; margin:100px;">The customer&rsquo;s bank account charge was declined. <a href="/csradmin/customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '&EditPayment">Click here to input new payment information</a>.</p>';
elseif ($_SESSION['PaymentType'] == 'CreditCard' && $blnPaymentError == 2)
	echo '<p style="text-align:center; font-weight:bold; margin:100px;">There was an error attempting to charge the customer&rsquo;s credit card. Please refresh or <a href="/csradmin/customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '&EditPayment">click here to input new payment information</a>.</p>';
elseif ($_SESSION['PaymentType'] == 'ECheck' && $blnPaymentError == 2)
	echo '<p style="text-align:center; font-weight:bold; margin:100px;">There was an error attempting to charge the customer&rsquo;s bank account. Please refresh or <a href="/csradmin/customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '&EditPayment">click here to input new payment information</a>.</p>';
	      
?>