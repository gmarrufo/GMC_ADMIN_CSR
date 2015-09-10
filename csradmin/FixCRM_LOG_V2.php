<?php

require_once("../modules/db.php");

// CONNECT TO SQL SERVER DATABASE
$connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connCustomers);

// Get the list of record ids from the file
// Create a FOR - LOOP
$fh = fopen('C:\\Inetpub\\wwwroot\\csradmin\\RawData091514.txt','r');

while ($line = fgets($fh)) {
	$text_line = explode(",",$line);

	// Using the RecordID update the LastContactDate
	$strSQL = "UPDATE tblCRM_LOB_BU SET LastContactDate = " . $text_line[1] . "  WHERE RecordID = " . $text_line[0];

	echo ($strSQL);
	echo "<br />";

	$qryUpdateCRM_LOG = mssql_query($strSQL);
}

fclose($fh);

// CLOSE DATABASE CONNECTION
mssql_close($connCustomers);

?>
