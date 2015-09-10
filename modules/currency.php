<?php

function ip_address_to_number($IPaddress)
{
    if ($IPaddress == "") {
        return 0;
    } else {
        $ips = split ("\.", "$IPaddress");
        return ($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
    }
}

if (!isset($_SESSION['CurrencyCountryCode']) || !isset($_SESSION['CurrencyCode']))
{
	// CURRENCY CONVERTER
	$connGeolocation = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
	mssql_select_db($dbName, $connGeolocation);
	
	$fltIPNumber = ip_address_to_number($_SERVER["REMOTE_ADDR"]);
	$qryTarget = mssql_init("spGeolocation", $connGeolocation);
	mssql_bind($qryTarget, "@prmIPNumber", $fltIPNumber, SQLFLT8);
	$rsGetTarget = mssql_execute($qryTarget);
	
	while($rowTarget = mssql_fetch_array($rsGetTarget))
	{
		$_SESSION['CurrencyCountryCode'] = $rowTarget["CountryCode"];
		$_SESSION['CurrencyCode'] = $rowTarget["CurrencyCodes"];
	}
	
	// CLOSE DATABASE CONNECTION
	mssql_close($connGeolocation);

}

// CURRENCY CONVERTER
$connCurrency = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
mssql_select_db($dbName, $connCurrency);

$decExchangeRate = 1;
$strCurrencyName = 'USD';

$qryConvert = mssql_init("spCurrencyRate", $connCurrency);
mssql_bind($qryConvert, "@prmCountryCode", $_SESSION['CurrencyCountryCode'], SQLVARCHAR);
$rsGetConversion = mssql_execute($qryConvert);

while($rowConversion = mssql_fetch_array($rsGetConversion))
{
	$decExchangeRate = $rowConversion["ExchangeRate"];
	$strCurrencyName = $rowConversion["CurrencyName"];
}

// CLOSE DATABASE CONNECTION
mssql_close($connCurrency);

// CONVERT CURRENCY
function convert($amount, $exchange, $currency)
{
    // GMC - 07/31/09 - Japan and South Korea - No Decimals
    if($_SESSION['CurrencyCountryCode'] == "JP" || $_SESSION['CurrencyCountryCode'] == "KR")
    {
        $strConvertedCurrency = number_format($amount / $exchange, 0, '.', '') . ' ' . $currency;
    }
    else
    {
        $strConvertedCurrency = number_format($amount / $exchange, 2, '.', '') . ' ' . $currency;
    }
    
  	return($strConvertedCurrency);
}
?>
