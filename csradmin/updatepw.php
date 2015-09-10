<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php
ini_set('display_errors', false);
require_once("../modules/session.php");
require_once("../modules/db.php");

if ((!isset($_SESSION['IsRevitalashLoggedIn'])) || ($_SESSION['IsRevitalashLoggedIn'] == 0))
{
	header("Location: login.php");
	exit;
}

if (isset($_POST['cmdUpdatePW']))
{

	if ($_POST['Password'] == '' || ($_POST['Password'] != $_POST['PasswordConfirm']))
		$page_error = 'The passwords you entered were blank or did not match.';
	else
	{
		$newPW = $_POST['Password'];
		$hashPW = md5($newPW);
		
		// CONNECT TO SQL SERVER DATABASE
		$connPW = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
		mssql_select_db($dbName, $connPW);
		
		$qryUpdatePW = mssql_init("spAdmins_PasswordUpdate", $connPW);
		mssql_bind($qryUpdatePW, "@prmUserID", $_SESSION['UserID'], SQLINT4);
		mssql_bind($qryUpdatePW, "@prmNewPassword", $hashPW, SQLVARCHAR);
		
		$rs = mssql_execute($qryUpdatePW);
		
		if (is_resource($rs))
		{
			while($rowPW = mssql_fetch_array($rs))
			{
				if ($rowPW['RecordID'] == 0)
					$page_error = 'An unspecified database error occurred.';
				else
					$page_confirmation = '<p>Your password has been updated.</p>';
			}
		}
	
		// CLOSE SQL SERVER CONNECTION
		mssql_close($connPW);
	}
}

?>

<head>
	<title>Update Password | Revitalash Administration</title>
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
            
            if (!isset($_POST['cmdUpdatePW']))
            {
                echo '<p>Please enter your desired password below to reset your password.</p>
            
                <form action="/csradmin/updatepw.php" method="post">
                <table width="100%" cellpadding="5" cellspacing="0">
                
                <tr>
                    <th width="120">Password</th>
                    <td width="*"><input type="text" name="Password" size="10" value="" /></td>
                </tr>
				
				<tr>
                    <th width="120">Confirm Password</th>
                    <td width="*"><input type="text" name="PasswordConfirm" size="10" value="" /></td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="cmdUpdatePW" value="Update Password" class="formSubmit" /></td>
                </tr>
                
                </table>
                </form>
                
                <p>&nbsp;</p>';
            }
            else
            {
                if (isset($page_error))
                {
                    echo '<p class="error">' . $page_error . '</p>';
                    
                    echo '<p>Please enter your desired password below to reset your password.</p>
            
					<form action="/csradmin/updatepw.php" method="post">
					<table width="100%" cellpadding="5" cellspacing="0">
					
					<tr>
						<th width="120">Password</th>
						<td width="*"><input type="text" name="Password" size="10" value="" /></td>
					</tr>
					
					<tr>
						<th width="120">Confirm Password</th>
						<td width="*"><input type="text" name="PasswordConfirm" size="10" value="" /></td>
					</tr>
					
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" name="cmdUpdatePW" value="Update Password" class="formSubmit" /></td>
					</tr>
					
					</table>
					</form>
					
					<p>&nbsp;</p>';
                }
                else
                {
                    echo '<p>' . $page_confirmation . '</p>';
                }
            
            }
            ?>
		
		</div>
		
	</div>

</body>

</html>
