<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");

//ATTEMPT TO LOGIN
if (isset($_POST['cmdLogin']))
	include("modules/revitalash_login.php");

//REDIRECT IF LOGGED IN
if ((isset($_SESSION['IsRevitalashLoggedIn'])) && ($_SESSION['IsRevitalashLoggedIn'] == 1))
{
	header("Location: index.php");
	exit;
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
        
			<?php
            if (isset($confirmation)) echo '<p class="confirmation">' . $confirmation . '</p>';
            if (isset($pageerror)) echo '<p class="error">' . $pageerror . '</p>';
            ?>

			<p><span style="font-weight:bold;">Access to this website is restricted to authorized employees and independent contractors of Athena Cosmetics, Inc.</span><br />
            If you do not have a username or password, please <a href="/csradmin/forgotpw.php">reset your password</a> or contact your supervisor for assistance.</p>

	
			<div id="login_table">
				<form action="/csradmin/login.php" method="post">
				<table>
					<tr>
						<th width="50%">Username:</th>
						<td width="50%"><input type="text" name="username" size="16"></td>
					</tr>
					
					<tr>
						<th>Password:</th>
						<td><input type="password" name="password" size="8"></td>
					</tr>
					
					<tr>
						<th>&nbsp;</th>
						<td><input type="submit" name="cmdLogin" value="Log In" class="formSubmit" /></td>
					</tr>
				</table>
				</form>
			</div>
		
		</div>
		
	</div>

</body>

</html>
