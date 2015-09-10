<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

if (isset($_POST['cmdSendPW']))
{

	$newPW = rand(800000,1000000);
	$hashPW = md5($newPW);
	
	// CONNECT TO SQL SERVER DATABASE
	$connPW = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	mssql_select_db($dbName, $connPW);
	
	$qryResetPW = mssql_init("spAdmins_PasswordReset", $connPW);
	mssql_bind($qryResetPW, "@prmRevitalashID", $_POST['RevitalashID'], SQLVARCHAR);
	mssql_bind($qryResetPW, "@prmNewPassword", $hashPW, SQLVARCHAR);
	
	$rs = mssql_execute($qryResetPW);
	
	if (is_resource($rs))
	{
	
		while($rowPW = mssql_fetch_array($rs))
		{
			if ($rowPW['RecordID'] == 0)
				$blnNotFound = 1;
			else
			{
				$strMessage = '<p>Your password has been reset to the following:</p>';
	
				$strMessage .= '<p>' . $newPW . '</p>';
				
				$mailrecepient = $rowPW['EMailAddress'];
				$mailsubject = 'RevitaLash Admin Password Reset';
				$mailheader = 'MIME-Version: 1.0' . "\r\n";
				$mailheader .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$mailheader .= "From:" . 'sales@revitalash.com' . "\r\n";
				//$mailheader .= 'Bcc: jstancarone@revitalash.com' . "\r\n";
				//$mailheader .= 'Bcc: gayleb@revitalash.com,lashgro@aol.com' . "\r\n";
				mail($mailrecepient, $mailsubject, $strMessage, $mailheader);
			}
		}
	
	}
	
	// CLOSE SQL SERVER CONNECTION
	mssql_close($connPW);
}

?>

<head>
	<title>Login | Revitalash Administration</title>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="/csradmin/styles/revitalash.css" type="text/css" />
</head>

<body>

	<div id="wrapper">
		
        <!-- GMC - 12/03/08 - Domestic vs International 3rd Phase
		<div><img src="/csradmin/images/bg_masthead.gif" alt="Revitalash Administration" width="950" height="91" /></div>
        -->
        
		<?php include("includes/dspMasthead.php"); ?>
		
		<div id="welcome_screen">
        
			<p style="font-weight:bold;">Password Reset</p>
        
			<?php
            
            if (!isset($_POST['cmdSendPW']))
            {
                echo '<p>Please enter your Revitalash ID below to reset your password. A new password will be emailed to the address on file.</p>
            
                <form action="/csradmin/forgotpw.php" method="post">
                <table width="100%" cellpadding="5" cellspacing="0">
                
                <tr>
                    <th width="120">E-Mail Address</th>
                    <td width="*"><input type="text" name="RevitalashID" size="40" value="" /></td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="cmdSendPW" value="Send Password Reset" class="formSubmit" /></td>
                </tr>
                
                </table>
                </form>
                
                <p>&nbsp;</p>';
            }
            else
            {
                if (isset($blnNotFound))
                {
                    echo '<p class="error">That Revitalash ID was not found. Please try again.</p>';
                    
                    echo '<p>Please enter your Revitalash ID below to reset your password. A new password will be emailed to the address on file.</p>
                
                    <form action="/csradmin/forgotpw.php" method="post">
                    <table width="100%" cellpadding="5" cellspacing="0">
                    
                    <tr>
                        <th width="120">E-Mail Address</th>
                        <td width="*"><input type="text" name="RevitalashID" size="40" value="" /></td>
                    </tr>
                    
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" name="cmdSendPW" value="Send Password Reset" class="formSubmit" /></td>
                    </tr>
                    
                    </table>
                    </form>
                    
                    <p>&nbsp;</p>';
                }
                else
                {
                    echo '<p>Your new password has been sent to the email address on file for the Revitalash ID specified. <a href="/csradmin/login.php">Click here to login</a>.</p>';
                }
            
            }
            ?>
		
		</div>
		
	</div>

</body>

</html>
