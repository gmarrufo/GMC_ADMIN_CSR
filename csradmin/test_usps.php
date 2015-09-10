<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

    require_once("../modules/xmlparser.php");

    $ShipToZIP = "93015";
    $OrderWeight = "5";

	// may need to urlencode xml portion
	$str2 = "http://production.shippingapis.com/ShippingAPI.dll" . "?API=RateV4&XML=<RateV4Request%20USERID=\"";
    $str2 .= "004GEEKT1462" . "\"%20PASSWORD=\"" . "931HN41XW201" . "\"><Package%20ID=\"0\"><Service>";
	$str2 .= "All" . "</Service><ZipOrigination>" . "90014" . "</ZipOrigination>";
	$str2 .= "<ZipDestination>" . $ShipToZIP . "</ZipDestination>";
	$str2 .= "<Pounds>" . floor($OrderWeight) . "</Pounds><Ounces>" . ceil(($OrderWeight - floor($OrderWeight)) * 16) . "</Ounces>";
	$str2 .= "<Container>VARIABLE</Container><Size>REGULAR</Size>";
	$str2 .= "<Machinable>true</Machinable></Package></RateV4Request>";
	$ch2 = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch2, CURLOPT_URL, $str2);
    curl_setopt($ch2, CURLOPT_HEADER, 0);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

    // grab URL and pass it to the browser
    $data2 = curl_exec($ch2);

    // close curl resource, and free up system resources
    curl_close($ch2);

    $xmlParser2 = new xmlparser();
    $array2 = $xmlParser2->GetXMLTree($data2);

    // $xmlParser2->printa($array2);

    foreach ($array2['RATEV4RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value2)
    {
        if ($value2['MAILSERVICE'][0]['VALUE'] == 'Priority Mail&lt;sup&gt;&amp;reg;&lt;/sup&gt;')
        {
            $ResultCode = $value2['RATE'][0]['VALUE'];
        }
    }


    echo $ResultCode;

    
// USPS Express Mail
// USPS Express Main – Flat Rate
// USPS Priority Mail
// USPS Priority Mail – Flat Rate
// USPS First Class Mail
// USPS Standard Post

?>

<head>
	<title>Customer Management | Revitalash Administration |Test USPS</title>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="/csradmin/styles/revitalash.css" type="text/css" />
</head>

<body>
	<div id="wrapper">
	<?php include("includes/dspMasthead.php"); ?>
	<div id="content">
 

    </div>
    </div>
</body>
</html>
