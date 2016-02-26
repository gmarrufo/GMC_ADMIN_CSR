<?php 

// CONNECT TO SQL SERVER DATABASE
$conncats = mssql_connect($dbServer, $dbUser, $dbPass)
  or die("Couldn't connect to SQL Server on $dbServer");

// OPEN REVITALASH DATABASE
mssql_select_db($dbName, $conncats);

//execute the SQL query and return records
$rsCategoryList = mssql_query("SELECT RecordID, CategoryName FROM tblCategories WHERE IsActive = 1 ORDER BY DisplayOrder ASC");

//$numRows = mssql_num_rows($result);

// CLOSE DATABASE CONNECTION
mssql_close($conncats);

?>

<table width="160" cellpadding="0" cellspacing="0" border="0">
            
<tr><td><div class="pro_nav_header">Categories</div></td></tr>
<tr><td><div class="pro_nav_td"><a href="/pro/catalog.php">All Items</a></div></td></tr>
<?php
while($row = mssql_fetch_array($rsCategoryList))
{
  echo '<tr><td><div class="pro_nav_td"><a href="/pro/catalog.php?CategoryID=' . $row["RecordID"] . '">' . $row["CategoryName"] . '</a></div></td></tr>';
}
?>

<tr><td>&nbsp;</td></tr>

<tr><td><div class="pro_nav_header">Current Cart</div></td></tr>
<tr><td><div class="pro_nav_td"><?php
if ((!isset($_SESSION['cart'])) || (count($_SESSION['cart']) == 0))
    echo 'Your cart is empty';
elseif (count($_SESSION['cart']) == 1)
    echo '<a href="/pro/cart.php">1 Item</a>';
elseif (count($_SESSION['cart']) >= 2)
    echo '<a href="/pro/cart.php">' . count($_SESSION['cart']) . ' Items</a>';
?></div></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td><div class="pro_nav_header">Your Account</div></td></tr>
<tr><td><div class="pro_nav_td">Order History</div></td></tr>

<tr><td><div class="pro_nav_td"><a href="/pro/account.php">Account Settings</a></div></td></tr>

<tr><td>&nbsp;</td></tr>

<tr><td><div class="pro_nav_td"><a href="/pro/index.php?logout">Logout</a></div></td></tr>

<tr><td>&nbsp;</td></tr>

</table>
