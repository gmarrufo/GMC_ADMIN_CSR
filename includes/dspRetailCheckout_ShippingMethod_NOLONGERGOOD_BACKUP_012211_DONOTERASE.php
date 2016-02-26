<p>Please select a shipping method below.</p>

<form action="/retail/checkout.php" method="post">
<table width="100%" cellpadding="0" cellspacing="5">

<?php

$a = "here";

echo $a;

// GMC - 01/22/11 - Replace UPS Shipping Rates For FedEx ShoppingCart
require_once('library/fedex-common.php5');
$path_to_wsdl = "wsdl/RateService_v8.wsdl";
$_SESSION['ShipToZip'] = "";
$_SESSION['ShipToCountry'] = "";
$_SESSION['ShipHandlingCharge'] = 0;

$intShippingMethodsReturned = 0;

if (!isset($_SESSION['OrderWeight']))
	$_SESSION['OrderWeight'] = 0.5;

// GMC - 05/06/09 - FedEx Netherlands
// GMC - 11/06/10 - Cancel Shipping and Handling = 0 for EU Orders
/*
if( $_SESSION['CountryCodeFedExEu_Retail'] != '')
{
   // Set Up the Sessions for the different European Services
   $_SESSION['IntraEuroGroundCharge'] = 0;
   $_SESSION['IntraEuroStandardCharge'] = 0;
   $_SESSION['IntraEuroExpressCharge'] = 0;
   $blnIsError = 0;

   // Calculate IntraEuropean Ground Service
	$rsIEGS = mssql_query("SELECT TOP 1 Rate FROM conIntraEuropeanGroundService WHERE CountryCode = '" . $_SESSION['CountryCodeFedExEu_Retail'] . "' AND Weight > " . $_SESSION['OrderWeight'] . "");
    while($rowIEGS = mssql_fetch_array($rsIEGS))
	{
        $_SESSION['IntraEuroGroundCharge'] = $rowIEGS["Rate"];
	}

   // Calculate IntraEuropean Standard Service
	$rsZSS = mssql_query("SELECT TOP 1 CodeZone FROM conZoningStandardService WHERE CountryCode = '" . $_SESSION['CountryCodeFedExEu_Retail'] . "' AND EUCountry = 'True'");
    while($rowZSS = mssql_fetch_array($rsZSS))
	{
        if($rowZSS["CodeZone"] == '1') // Zone1
        {
            $rsIESS = mssql_query("SELECT TOP 1 Zone1 FROM conIntraEuropeanStandardService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIESS = mssql_fetch_array($rsIESS))
	        {
                $_SESSION['IntraEuroStandardCharge'] = $rowIESS['Zone1'];
	        }
        }
        else if($rowZSS["CodeZone"] == '2') // Zone2
        {
            $rsIESS = mssql_query("SELECT TOP 1 Zone2 FROM conIntraEuropeanStandardService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIESS = mssql_fetch_array($rsIESS))
	        {
                $_SESSION['IntraEuroStandardCharge'] = $rowIESS['Zone2'];
	        }
        }
        else if($rowZSS["CodeZone"] == '3') // Zone3
        {
            $rsIESS = mssql_query("SELECT TOP 1 Zone3 FROM conIntraEuropeanStandardService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIESS = mssql_fetch_array($rsIESS))
	        {
                $_SESSION['IntraEuroStandardCharge'] = $rowIESS['Zone3'];
	        }
        }
        else if($rowZSS["CodeZone"] == '4') // Zone4
        {
            $rsIESS = mssql_query("SELECT TOP 1 Zone4 FROM conIntraEuropeanStandardService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIESS = mssql_fetch_array($rsIESS))
	        {
                $_SESSION['IntraEuroStandardCharge'] = $rowIESS['Zone4'];
	        }
        }
        else if($rowZSS["CodeZone"] == '5') // Zone5
        {
            $rsIESS = mssql_query("SELECT TOP 1 Zone5 FROM conIntraEuropeanStandardService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIESS = mssql_fetch_array($rsIESS))
	        {
                $_SESSION['IntraEuroStandardCharge'] = $rowIESS['Zone5'];
	        }
        }
        else if($rowZSS["CodeZone"] == '6') // Zone6
        {
            $rsIESS = mssql_query("SELECT TOP 1 Zone6 FROM conIntraEuropeanStandardService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIESS = mssql_fetch_array($rsIESS))
	        {
                $_SESSION['IntraEuroStandardCharge'] = $rowIESS['Zone6'];
	        }
        }
	}

   // Calculate IntraEuropean Express Service
	$rsZES = mssql_query("SELECT TOP 1 CodeZone FROM conZoningExpressService WHERE CountryCode = '" . $_SESSION['CountryCodeFedExEu_Retail'] . "' AND EUCountry = 'True'");
    while($rowZES = mssql_fetch_array($rsZES))
	{
        if($rowZES["CodeZone"] == 'R') // ZoneR
        {
            $rsIEES = mssql_query("SELECT TOP 1 ZoneR FROM conIntraEuropeanExpressService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIEES = mssql_fetch_array($rsIEES))
	        {
                $_SESSION['IntraEuroExpressCharge'] = $rowIEES['ZoneR'];
	        }
        }
        else if($rowZES["CodeZone"] == 'S') // ZoneS
        {
            $rsIEES = mssql_query("SELECT TOP 1 ZoneS FROM conIntraEuropeanExpressService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIEES = mssql_fetch_array($rsIEES))
	        {
                $_SESSION['IntraEuroExpressCharge'] = $rowIEES['ZoneS'];
	        }
        }
        else if($rowZES["CodeZone"] == 'T') // ZoneT
        {
            $rsIEES = mssql_query("SELECT TOP 1 ZoneT FROM conIntraEuropeanExpressService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIEES = mssql_fetch_array($rsIEES))
	        {
                $_SESSION['IntraEuroExpressCharge'] = $rowIEES['ZoneT'];
	        }
        }
        else if($rowZES["CodeZone"] == 'U') // ZoneU
        {
            $rsIEES = mssql_query("SELECT TOP 1 ZoneU FROM conIntraEuropeanExpressService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIEES = mssql_fetch_array($rsIEES))
	        {
                $_SESSION['IntraEuroExpressCharge'] = $rowIEES['ZoneU'];
	        }
        }
        else if($rowZES["CodeZone"] == 'V') // ZoneV
        {
            $rsIEES = mssql_query("SELECT TOP 1 ZoneV FROM conIntraEuropeanExpressService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIEES = mssql_fetch_array($rsIEES))
	        {
                $_SESSION['IntraEuroExpressCharge'] = $rowIEES['ZoneV'];
	        }
        }
        else if($rowZES["CodeZone"] == 'Domestic') // Domestic
        {
            $rsIEES = mssql_query("SELECT TOP 1 Domestic FROM conIntraEuropeanExpressService WHERE Weight > " . $_SESSION['OrderWeight'] . "");
            while($rowIEES = mssql_fetch_array($rsIEES))
	        {
                $_SESSION['IntraEuroExpressCharge'] = $rowIEES['Domestic'];
	        }
        }
    }

   // Present the Radio Buttons
    if(($_SESSION['IntraEuroGroundCharge'] == 0) && ($_SESSION['IntraEuroStandardCharge'] == 0) && ($_SESSION['IntraEuroExpressCharge'] == 0))
    {
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="999" /></td>';
	    echo '<td width="*">Will Call (' . convert(0,$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
        $blnIsError = 1;
    }
    
    // GMC - 09/27/09 - To Fix When Any of the FedExEU is 0
    else if(($_SESSION['IntraEuroGroundCharge'] == 0) && ($_SESSION['IntraEuroStandardCharge'] == 0) && ($_SESSION['IntraEuroExpressCharge'] != 0))
    {
        $intShippingMethodsReturned = 1;
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="210" /></td>';
	    echo '<td width="*">FedEx EU Express (' . convert($_SESSION['IntraEuroExpressCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
    }
    else if(($_SESSION['IntraEuroGroundCharge'] == 0) && ($_SESSION['IntraEuroStandardCharge'] != 0) && ($_SESSION['IntraEuroExpressCharge'] == 0))
    {
        $intShippingMethodsReturned = 1;
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="209" /></td>';
	    echo '<td width="*">FedEx EU Standard (' . convert($_SESSION['IntraEuroStandardCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
    }
    else if(($_SESSION['IntraEuroGroundCharge'] == 0) && ($_SESSION['IntraEuroStandardCharge'] != 0) && ($_SESSION['IntraEuroExpressCharge'] != 0))
    {
        $intShippingMethodsReturned = 1;
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="209" /></td>';
	    echo '<td width="*">FedEx EU Standard (' . convert($_SESSION['IntraEuroStandardCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="210" /></td>';
	    echo '<td width="*">FedEx EU Express (' . convert($_SESSION['IntraEuroExpressCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
    }
    else if(($_SESSION['IntraEuroGroundCharge'] != 0) && ($_SESSION['IntraEuroStandardCharge'] == 0) && ($_SESSION['IntraEuroExpressCharge'] == 0))
    {
        $intShippingMethodsReturned = 1;
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="208" /></td>';
	    echo '<td width="*">FedEx EU Ground (' . convert($_SESSION['IntraEuroGroundCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
    }
    else if(($_SESSION['IntraEuroGroundCharge'] != 0) && ($_SESSION['IntraEuroStandardCharge'] == 0) && ($_SESSION['IntraEuroExpressCharge'] != 0))
    {
        $intShippingMethodsReturned = 1;
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="208" /></td>';
	    echo '<td width="*">FedEx EU Ground (' . convert($_SESSION['IntraEuroGroundCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="210" /></td>';
	    echo '<td width="*">FedEx EU Express (' . convert($_SESSION['IntraEuroExpressCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
    }
    else if(($_SESSION['IntraEuroGroundCharge'] != 0) && ($_SESSION['IntraEuroStandardCharge'] != 0) && ($_SESSION['IntraEuroExpressCharge'] == 0))
    {
        $intShippingMethodsReturned = 1;
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="208" /></td>';
	    echo '<td width="*">FedEx EU Ground (' . convert($_SESSION['IntraEuroGroundCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="209" /></td>';
	    echo '<td width="*">FedEx EU Standard (' . convert($_SESSION['IntraEuroStandardCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
    }
    else
    {
        $intShippingMethodsReturned = 1;
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="208" /></td>';
	    echo '<td width="*">FedEx EU Ground (' . convert($_SESSION['IntraEuroGroundCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="209" /></td>';
	    echo '<td width="*">FedEx EU Standard (' . convert($_SESSION['IntraEuroStandardCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
        echo '<tr>';
        echo '<td width="30"><input type="radio" name="ShipMethodID" value="210" /></td>';
	    echo '<td width="*">FedEx EU Express (' . convert($_SESSION['IntraEuroExpressCharge'],$decExchangeRate,$strCurrencyName) . ')</td>';
	    echo '</tr>';
    }
}
else
{
*/

  // GMC - 01/22/11 - Replace UPS Shipping Rates For FedEx ShoppingCart
  if (mssql_num_rows($rs) > 0)
  {
	while($row = mssql_fetch_array($rs))
	{
       $_SESSION['ShipToZip'] = $row["ShipToZIP"];
       $_SESSION['ShipToCountry'] = $row["ShipToCountry"];
       $_SESSION['ShipHandlingCharge'] = $row['HandlingCharge'];
    }
  }

  /*
  if (mssql_num_rows($rs) > 0)
  {
	while($row = mssql_fetch_array($rs))
	{
		if ($row["Carrier"] == 'UPS')
		// UPS RATE QUOTES
		{
			$blnIsError = 0;

            // GMC - 01/07/11 - UPS Rate and Service DataStream API - Change
            // GMC - 01/17/11 - Cancel UPS Rate and Service DataStream API - Change
            /*
			$urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
			"10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."90014","19_destPostal=".$row["ShipToZIP"],
			"22_destCountry="."US","23_weight=".$_SESSION['OrderWeight'],"47_rateChart="."Regular+Daily+Pickup","48_container="."00","49_residential="."01"));

            $urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
            "10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."89074","19_destPostal=".$row["ShipToZIP"],
            "22_destCountry="."US","23_weight=".$_SESSION['OrderWeight'],"47_rateChart="."Letter+Center","48_container="."00","49_residential="."01"));
            */

            // GMC - 01/22/11 - Replace UPS Shipping Rates For FedEx ShoppingCart
            /*
			$urlUPS = join("&", array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
			"10_action=3","13_product=".$row["XMLServiceClass"],"14_origCountry="."US","15_origPostal="."90014","19_destPostal=".$row["ShipToZIP"],
			"22_destCountry="."US","23_weight=".$_SESSION['OrderWeight'],"47_rateChart="."Regular+Daily+Pickup","48_container="."00","49_residential="."01"));

			$strResponse = fopen($urlUPS, "r");
			
			while(!feof($strResponse))
			{   
			  $Result = fgets($strResponse, 500);
			  $Result = explode("%", $Result);
			  $Err = substr($Result[0], -1);
			
			  switch($Err)
			  {
				 case 3: $ResultCode = $Result[8]; break;
				 case 4: $ResultCode = $Result[8]; break;
				 case 5: $ResultCode = $Result[1]; $blnIsError = 1; break;
				 case 6: $ResultCode = $Result[1]; $blnIsError = 1; break;
			  }
			}
			
			fclose($strResponse);
			
			if(!$ResultCode)
			{
				$blnIsError = 1;
				$ResultCode = "An error occured.";
			}
		}
		elseif ($row["Carrier"] == 'FedEx')
		// FEDEX RATE QUOTES
		{
            $blnIsError = 0;

			if ($row["XMLServiceClass"] == 'INTERNATIONALPRIORITY')
			{
				$ResultCode = 26;
			}
			else
			{
				require_once("../modules/xmlparser.php");
				
				//INIT XML CALL
				$str1 = '<?xml version="1.0" encoding="UTF-8" ?>';
				$str1 .= '    <FDXRateRequest xmlns:api="http://www.fedex.com/fsmapi" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="FDXRateRequest.xsd">';
				$str1 .= '        <RequestHeader>';
				$str1 .= '            <CustomerTransactionIdentifier>Express Rate</CustomerTransactionIdentifier>';
				$str1 .= '            <AccountNumber>156895695</AccountNumber>';
				$str1 .= '            <MeterNumber>1053804</MeterNumber>';
				$str1 .= '            <CarrierCode>' . $row["XMLCarrierClass"] . '</CarrierCode>';
				$str1 .= '        </RequestHeader>';
				$str1 .= '        <DropoffType>REGULARPICKUP</DropoffType>';
				$str1 .= '        <Service>' . $row["XMLServiceClass"] . '</Service>';
				$str1 .= '        <Packaging>YOURPACKAGING</Packaging>';
				$str1 .= '        <WeightUnits>LBS</WeightUnits>';

                // GMC - 04/06/10 - Add 1.2 lb to weight
                // $str1 .= '        <Weight>'.number_format($_SESSION['OrderWeight'], 1, '.', '').'</Weight>';
                $str1 .= '        <Weight>'.number_format($_SESSION['OrderWeight'] + 1.2, 1, '.', '').'</Weight>';

				$str1 .= '        <OriginAddress>';
				//$str1 .= '            <StateOrProvinceCode>CA</StateOrProvinceCode>';
				$str1 .= '            <PostalCode>97086</PostalCode>';
				$str1 .= '            <CountryCode>US</CountryCode>';
				$str1 .= '        </OriginAddress>';
				$str1 .= '        <DestinationAddress>';
				//$str1 .= '            <StateOrProvinceCode>IL</StateOrProvinceCode>';
				$str1 .= '            <PostalCode>' . $row["ShipToZIP"] . '</PostalCode>';
				$str1 .= '            <CountryCode>' . $row["ShipToCountry"] . '</CountryCode>';
				$str1 .= '        </DestinationAddress>';
				$str1 .= '        <Payment>';
				$str1 .= '            <PayorType>SENDER</PayorType>';
				$str1 .= '        </Payment>';
				$str1 .= '        <PackageCount>1</PackageCount>';
				$str1 .= '    </FDXRateRequest>';
				$header1[] = "Host: www.vistabella.com";
				$header1[] = "MIME-Version: 1.0";
				$header1[] = "Content-type: multipart/mixed; boundary=----doc";
				$header1[] = "Accept: text/xml";
				$header1[] = "Content-length: ".strlen($str1);
				$header1[] = "Cache-Control: no-cache";
				$header1[] = "Connection: close \r\n";
				$header1[] = $str1;
				
				// CALL XML
				$ch1 = curl_init();
				curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch1, CURLOPT_URL,'https://gatewaybeta.fedex.com/GatewayDC');
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch1, CURLOPT_TIMEOUT, 4);
				curl_setopt($ch1, CURLOPT_CUSTOMREQUEST,'POST');
				curl_setopt($ch1, CURLOPT_HTTPHEADER, $header1);
				$data1 = curl_exec($ch1);        
				
				if (curl_errno($ch1))
					$blnIsError = 1;
				else
				{
					// close curl resource, and free up system resources
					curl_close($ch1);
					$xmlParser1 = new xmlparser();
					$array1 = $xmlParser1->GetXMLTree($data1);
					
					if(isset($array1['FDXRATEREPLY'][0]['ERROR']) && count($array1['FDXRATEREPLY'][0]['ERROR']))
						$blnIsError = 1;
					else if (count($array1['FDXRATEREPLY'][0]['ESTIMATEDCHARGES'][0]['DISCOUNTEDCHARGES'][0]['NETCHARGE']))
					{
						
						$ResultCode = $array1['FDXRATEREPLY'][0]['ESTIMATEDCHARGES'][0]['DISCOUNTEDCHARGES'][0]['NETCHARGE'][0]['VALUE'];
						unset($str1);
						unset($header1);
						unset($ch1);
						unset($data1);
						unset($xmlParser1);
						unset($array1);
					}
				}
			}
        }
		elseif ($row["Carrier"] == 'USPS')
		// USPS RATE QUOTE
		{
			$blnIsError = 0;
			
			require_once("../modules/xmlparser.php");
			
			// may need to urlencode xml portion
			//$str2 = "http://testing.shippingapis.com/ShippingAPITest.dll" . "?API=RateV2&XML=<RateV2Request%20USERID=\"";
			$str2 = "http://Production.ShippingAPIs.com/ShippingAPI.dll" . "?API=RateV2&XML=<RateV2Request%20USERID=\"";
			$str2 .= "004GEEKT1462" . "\"%20PASSWORD=\"" . "931HN41XW201" . "\"><Package%20ID=\"0\"><Service>";
			$str2 .= "All" . "</Service><ZipOrigination>" . "90014" . "</ZipOrigination>";
			$str2 .= "<ZipDestination>" . $row["ShipToZIP"] . "</ZipDestination>";
			$str2 .= "<Pounds>" . floor($_SESSION['OrderWeight']) . "</Pounds><Ounces>" . ceil(($_SESSION['OrderWeight'] - floor($_SESSION['OrderWeight'])) * 16) . "</Ounces>";
			$str2 .= "<Container>VARIABLE</Container><Size>REGULAR</Size>";
			$str2 .= "<Machinable>true</Machinable></Package></RateV2Request>";
			
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
			//$xmlParser->printa($array);
			if (isset($array2['RATEV2RESPONSE']) && count($array2['RATEV2RESPONSE']))
			{
				foreach ($array2['RATEV2RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value2)
				{
					if ($value2['MAILSERVICE'][0]['VALUE'] == 'Priority Mail')
					{
						$ResultCode = $value2['RATE'][0]['VALUE'];
					}
				}
			}
			else
				$blnIsError = 1;
		}
		
		if ($blnIsError == 0)
		{
			$intShippingMethodsReturned += 1;

			// GMC - 10/27/08 - To accomodate $10 Extra Surcharge			
			if ($_SESSION['IsInternational'] == 1)
			{
                // GMC - 07/31/09 - Fix the extra $10 shown but not charged
                $ResultCode += $row["HandlingCharge"];
				// $ResultCode += 10;
			}
			else
			{
                // GMC - 10/26/10 - Stop the extra charge of $10
				// $ResultCode += $row["HandlingCharge"];
			}
			
			//echo '<option value="' . $row["RecordID"]. '">' . $row["ShippingMethodDisplay"] . ' ($' . number_format($ResultCode, 2, '.', '') . ')</option>';
			
			echo '<tr>';
			echo '<td width="30"><input type="radio" name="ShipMethodID" value="' . $row["RecordID"]. '" /></td>';

            // GMC - 02/03/10 - Take amount off and change shipping method display text
            // GMC - 10/20/10 - Put back the amount- related to 02-03-10 - Take amount off and change shipping method display text
            // echo '<td width="*">' . $row["ShippingMethodDisplay"] . '</td>';

            // GMC - 11/04/10 - Put back $5 to US Shipping to Show in page by JS
			if ($_SESSION['IsInternational'] == 1)
			{
                echo '<td width="*">' . $row["ShippingMethodDisplay"] . ' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>';
			}
			else
			{
                echo '<td width="*">' . $row["ShippingMethodDisplay"] . ' (' . convert($ResultCode + 5,$decExchangeRate,$strCurrencyName) . ')</td>';
			}

			echo '</tr>';
		}
	}
  }
  */
/*
}
*/

            // GMC - 01/22/11 - Replace UPS Shipping Rates For FedEx ShoppingCart
             ini_set("soap.wsdl_cache_enabled", "0");

             $client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

             $request['WebAuthenticationDetail'] = array('UserCredential' =>

             // array('Key' => 'XXX', 'Password' => 'YYY')); // Replace 'XXX' and 'YYY' with FedEx provided credentials
             array('Key' => 'hRnybIZX3PKne28q', 'Password' => 'yDjVeSkK252f1kFblX1AXy31b')); // Replace 'XXX' and 'YYY' with FedEx provided credentials

             // $request['ClientDetail'] = array('AccountNumber' => 'XXX', 'MeterNumber' => 'XXX');// Replace 'XXX' with your account and meter number
             $request['ClientDetail'] = array('AccountNumber' => '462227000', 'MeterNumber' => '100677102');// Replace 'XXX' with your account and meter number

             // GMC - 08/17/10 - FedEx Box Project
             /*
             $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Available Services Request v4 using PHP ***');

             // $request['Version'] = array('ServiceId' => 'crs', 'Major' => '4', 'Intermediate' => '0', Minor => '0');
             $request['Version'] = array('ServiceId' => 'crs', 'Major' => '5', 'Intermediate' => '0', 'Minor' => '0');
             */

             $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Available Services Request v8 using PHP ***');
             $request['Version'] = array('ServiceId' => 'crs', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
             $request['ReturnTransitAndCommit'] = true;

             $request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
             $request['RequestedShipment']['ShipTimestamp'] = date('c');

             // Service Type and Packaging Type are not passed in the request
             $request['RequestedShipment']['Shipper'] = array('Address' => array(
                                          'StreetLines' => array('701 N. Green Valley Parkway St # 200'), // Origin details
                                          'City' => 'Henderson',
                                          'StateOrProvinceCode' => 'NV',
                                          'PostalCode' => '89074',
                                          'CountryCode' => 'US'));

             $request['RequestedShipment']['Recipient'] = array('Address' => array (
                                          'StreetLines' => array('123 Main St'), // Destination details

                                          // 'City' => $_SESSION["city"],
                                          // 'StateOrProvinceCode' => $_SESSION["state"],

                                          'City' => 'Any City',
                                          'StateOrProvinceCode' => 'Any State',

                                          'PostalCode' => $_SESSION["ShipToZip"],
                                          'CountryCode' => $_SESSION["ShipToCountry"]));
             $request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
                                          'Payor' => array('AccountNumber' => '462227000', // Replace "XXX" with payor's account number
                                          'CountryCode' => 'US'));
             $request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT';
             $request['RequestedShipment']['PackageCount'] = '1';
             $request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
             $request['RequestedShipment']['RequestedPackageLineItems'] = array('0' => array('Weight' => array('Value' => $_SESSION['OrderWeight'] + 1.2,
                                                                                    'Units' => 'LB'),
                                                                                    'Dimensions' => array('Length' => 12,
                                                                                    'Width' => 12,
                                                                                    'Height' => 12,
                                                                                    'Units' => 'IN')));

             try
             {
                 $response = $client -> getRates($request);
                 if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
                 {
                      foreach ($response -> RateReplyDetails as $rateReply)
                      {
                          $serviceType = $rateReply -> ServiceType;

                          if($serviceType == "FEDEX_GROUND")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $ResultCode = $rateReply -> RatedShipmentDetails -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="199" /></td>'
                                   '<td width="*">Ground' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }
                          elseif($serviceType == "FEDEX_2_DAY")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="201" /></td>'
                                   '<td width="*">2 Day' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }
                          elseif($serviceType == "FEDEX_EXPRESS_SAVER")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="200" /></td>'
                                   '<td width="*">Express Saver' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }
                          elseif($serviceType == "FEDEX_1_DAY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="211" /></td>'
                                   '<td width="*">1 Day Freight' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }

                          elseif($serviceType == "FEDEX_2_DAY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="212" /></td>'
                                   '<td width="*">2 Day Freight' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }
                          elseif($serviceType == "FEDEX_3_DAY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="213" /></td>'
                                   '<td width="*">3 Day Freight' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }

                          elseif($serviceType == "INTERNATIONAL_PRIORITY")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge->Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="205" /></td>'
                                   '<td width="*">International Priority' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }
                          elseif($serviceType == "INTERNATIONAL_ECONOMY")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="206" /></td>'
                                   '<td width="*">International Economy' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }
                          elseif($serviceType == "INTERNATIONAL_PRIORITY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge->Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="214" /></td>'
                                   '<td width="*">International Priority Freight' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }
                          elseif($serviceType == "INTERNATIONAL_ECONOMY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="215" /></td>'
                                   '<td width="*">International Economy Freight' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }

                          elseif($serviceType == "INTERNATIONAL_FIRST")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              $ResultCode += $_SESSION['ShipHandlingCharge'];
                              echo '<tr>'
                                   '<td width="30"><input type="radio" name="ShipMethodID" value="207" /></td>'
                                   '<td width="*">International First' (' . convert($ResultCode,$decExchangeRate,$strCurrencyName) . ')</td>'
                                   '</tr>';
                              $blnIsError = 0;
                              $intShippingMethodsReturned = 1;
                          }
                      }
                 }
                 else
                 {
                      foreach ($response -> Notifications as $notification)
                      {
                          if(is_array($response -> Notifications))
                          {
                              $blnIsError = 1;
                          }
                          else
                          {
                              $blnIsError = 1;
                          }
                      }
                 }
             }
             catch (SoapFault $exception)
             {
                $blnIsError = 1;
             }

if ($intShippingMethodsReturned == 0)
{
	unset($_SESSION['IsShippingSet']);
	unset($_SESSION['CustomerShipToID']);
	unset($_SESSION['IsShippingMethodSet']);
	unset($_SESSION['ShippingMethod']);

	echo '<p>The address you provided was not recognized by any of our providers. Please <a href="/retail/checkout.php?EditShipping">click here to confirm and/or edit your address</a>.</p>';
}
else
{
	echo '<tr>
    	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="cmdSetShippingMethod" value="Continue" class="formSubmit" /></td>
	</tr>';
}

?>

</table>
</form>