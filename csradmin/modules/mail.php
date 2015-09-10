<?php

$Name = "Giovanni Marrufo"; //senders name
$email = "sales@revitalash.com"; //senders e-mail adress
$recipient = "gmarrufo@unimerch.com"; //recipient
$mail_body = "The text for the mail..."; //mail body
$subject = "Test Subject"; //subject
//$header = "From: ". $Name . " <" . $email . ">\r\n"; //optional headerfields
$header = "From:" . $email . "\r\n"; //optional headerfields

mail($recipient, $subject, $mail_body, $header); //mail command :)
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Test Mail</title>
</head>

<body>
<p>Mail Sent!</p>
</body>
</html>
