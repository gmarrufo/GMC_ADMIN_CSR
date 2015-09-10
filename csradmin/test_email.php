<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

$strMessage = '<p>This is a Test Email</p>';
$mailrecepient = 'gmarrufo@gmdsconsulting.com';
$mailsubject = 'Test Email';
$mailheader = 'MIME-Version: 1.0' . "\r\n";
$mailheader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$mailheader .= "From:" . 'sales@revitalash.com' . "\r\n";
mail($mailrecepient, $mailsubject, $strMessage, $mailheader);

?>

<body>
      <p style="font-weight:bold;">Email Sent</p>
</body>

</html>
