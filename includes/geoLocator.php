<?php

    // GMC - 08/16/12 - Split US Other Countries at Shipping Selection Shopping Cart Consumer
    function ip_address_to_number($IPaddress)
    {
        if ($IPaddress == "")
        {
           return 0;
        }
        else
        {
            $ips = split ("\.", "$IPaddress");
            return ($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
        }
    }

	// CURRENCY CONVERTER
	$connGeolocation = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	mssql_select_db($dbName, $connGeolocation);

    // echo $_SERVER["REMOTE_ADDR"];
	$fltIPNumber = ip_address_to_number($_SERVER["REMOTE_ADDR"]); // Production
    // echo $fltIPNumber + "\n";

	$qryTarget = mssql_init("spGeolocationGMC", $connGeolocation);
	mssql_bind($qryTarget, "@prmIPNumber", $fltIPNumber, SQLFLT8);
	$rsGetTarget = mssql_execute($qryTarget);

	while($rowTarget = mssql_fetch_array($rsGetTarget))
	{
	   $CountryRaw = $rowTarget["CountryCode"];
       $StateRegionRaw = $rowTarget["StateRegion"];
    }

    if($CountryRaw == "-")
    {
        $_SESSION['Country_Customer'] = "US";
    }
    else
    {
        $_SESSION['Country_Customer'] = $CountryRaw;

        // GMC - 03/01/13 - Configure the Country Exclusion process - CSRADMIN and Shopping Cart (Consumer and Reseller)
        $_SESSION['CountryExclusion'] = $CountryRaw;
    }

    if($StateRegionRaw == "-")
    {
        $_SESSION['StateExclusion'] = "CA";
    }
    else
    {
        if($StateRegionRaw == "CALIFORNIA")
        {
            $_SESSION['StateExclusion'] = "CA";
        }
        else if($StateRegionRaw == "TENNESSEE")
        {
            $_SESSION['StateExclusion'] = "TN";
        }
    }

     // GMC - FOR TESTING PURPOSES ONLY - UNCOMMENT WHEN TO PRODUCTION
     // $_SESSION['StateExclusion'] = "ANY STATE WE WANT TO TEST";
     // $_SESSION['CountryExclusion'] = "ANY COUNTRY WE WANT TO TEST";

    // CLOSE DATABASE CONNECTION
    mssql_close($connGeolocation);
?>
