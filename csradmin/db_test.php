<?php
$serverName = "GIOVANNIMAR9351"; //serverName\instanceName

$connectionInfo = array( "Database"=>"revitalash", "UID"=>"afowler", "PWD"=>"password");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}
?>