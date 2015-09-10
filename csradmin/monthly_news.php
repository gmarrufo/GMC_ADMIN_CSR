<h1>Monthly Newsletters</h1>

<form method="post" action="">

<p>
Below is a list of the customers that requested a Monthly Newsletter.<br>
If you want to "EXTRACT A CSV FILE" reflecting the Monthly News table right click and save target locally from <a href="tblMonthlyNewsletters.csv">here</a><br>
</p>

<div class="bluediv_content">

    <table width="100%" cellpadding="3" cellspacing="1" bgcolor="#B4C8E3">

    <tr class="tdwhite" style="font-weight:bold;">
        <td width="2">Record ID</td>
		<td width="2">Customer ID</td>
		<td width="2">First Name</td>
		<td width="2">Last Name</td>
		<td width="2">Email Address</td>
		<td width="2">Extracted for Marketing</td>
    </tr>

    <?php echo $tblMonthlyNewsletters; ?>

    </table>

    <table width="80%" cellpadding="3" cellspacing="1">
    <tr class="tdwhite" style="font-weight:bold;">
        <td>
        <input type="submit" name="monthly_news_back" value="Back" />
        </td>
    </tr>
    </table>

</div>

</form>
