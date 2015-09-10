<?php

require_once("../modules/db.php");

// CONNECT TO SQL SERVER DATABASE
$connCustomers = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connCustomers);

// Get the list of record ids from the file
// Create a FOR - LOOP
$fh = fopen('C:\\Inetpub\\wwwroot\\csradmin\\RawData071714.txt','r');

while ($line = fgets($fh)) {

  $RawRecord = clean($line);

  echo "<br />";
  echo($RawRecord);
  echo "<br />";

  // Using the RecordID from the list obtain the CustomerID
  // select CustomerID from tblCRM_LOG where RecordID = 67807
  $qryGetCustomerID = mssql_query("SELECT CustomerID FROM tblCRM_LOG WHERE RecordID = " . $RawRecord);

  echo ("SELECT CustomerID FROM tblCRM_LOG WHERE RecordID = " . $RawRecord);
  echo "<br />";

  // EXECUTE QUERY
  while($row = mssql_fetch_array($qryGetCustomerID))
  {
      $CustomerID = $row["CustomerID"];
  }

  echo $CustomerID;
  echo "<br />";

  // Using the CustomerID from the previous select statement obtain the last RecordID of the Customer
  // select top 1 recordid from tblcrm_log where customerid = 88888 order by recordid desc
  $qryLastRecordID = mssql_query("SELECT TOP 1 RecordID from tblCRM_LOG where CustomerID  = " . $CustomerID . " ORDER BY RecordID DESC");

  echo ("SELECT TOP 1 RecordID from tblCRM_LOG where CustomerID  = " . $CustomerID . " ORDER BY RecordID DESC");
  echo "<br />";

  // EXECUTE QUERY
  while($row = mssql_fetch_array($qryLastRecordID ))
  {
      $LastRecordID = $row["RecordID"];
  }

  echo $LastRecordID ;
  echo "<br />";

  // Using the RecordID of the last record update the LastContactFlag
  // update tblcrm_log set lastcontactflag = 1 where recordid = 18778508
  $strSQL = "UPDATE tblCRM_LOG SET LastContactFlag = 1 WHERE RecordID = " . $LastRecordID;

  echo ($strSQL);
  echo "<br />";

  // $qryUpdateCRM_LOG = mssql_query($strSQL);
}

fclose($fh);

// CLOSE DATABASE CONNECTION
mssql_close($connCustomers);

function clean($string) {
  $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
  return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

?>

