<h1>Revitalash Users</h1>

<form method="post" action="">

<!-- GMC - 12/18/08 - Add Telephone and join FirstName + LastName = Contact Search
<p>Below is a list of the most recent customers. Please click on a customer to place a new order or edit the customer information.</p>
-->

<p>
Below is a list of current Users.<br>
If you want to "ADD" a User to the Revitalash Users Table, click <a href="revitalash_users_add.php">here</a>.<br>
If you want to "EDIT" or "DELETE" a User from the Revitalash Users Table (One Record at a time), select the record with the proper link and click on it.

<!-- GMC - 01/15/13 - tblUsers to Excel -->
If you want to "OPEN A CSV FILE" reflecting the Users table right click and save target locally from <a href="tblUsers.csv">here</a><br>

</p>

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">

    <tr class="tdwhite" style="font-weight:bold;">
        <td width="2">Edit</td>
        <td width="2">Delete</td>
        <td width="2">Record ID</td>
        <td width="2">Revitalash ID</td>
        <td width="2">First Name</td>
        <td width="2">Last Name</td>
        <td width="2">User Type ID</td>
        <td width="2">Navision User ID</td>
        <td width="2">Email Address</td>
        <td width="2">Password</td>
        <td width="2">Is Active</td>
    </tr>

    <?php echo $tblRevitalashUsers; ?>

    </table>

</div>

</form>
