<?php

// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 8.0.0

require_once('fedex-common.php5');

$arValues = array('0' => array('Weight' => array('Value' => 35,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 12,
                      'Height' => 12,
                      'Units' => 'IN')));

                      /*,
                  '1' => array('Weight' => array('Value' => 2.2,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 15,
                      'Width' => 10,
                      'Height' => 10,
                      'Units' => 'IN')),
                  '2' => array('Weight' => array('Value' => 3.2,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 25,
                      'Width' => 10,
                      'Height' => 10,
                      'Units' => 'IN')));
                                           */
                                           
//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "RateService_v8.wsdl";

//Set commonly used variables in fedex-common.php5.  Set check to true.
if(setDefaults('check'))
{
	$key=setDefaults('key');
	$password=setDefaults('password');
	$shipAccount=setDefaults('shipaccount');
	$meter=setDefaults('meter');
	$billAccount=setDefaults('billaccount');
	$dutyAccount=setDefaults('dutyaccount');
}
//Set commonly used variables below.  Set check to false.
else
{
	$key='hRnybIZX3PKne28q';
	$password='yDjVeSkK252f1kFblX1AXy31b';
	$shipAccount='462227000';
	$meter='100677102';
	$billAccount='462227000';
	$dutyAccount='462227000';
}

ini_set("soap.wsdl_cache_enabled", "0");
 
$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array('UserCredential' =>
                                      array('Key' => $key, 'Password' => $password)); 
$request['ClientDetail'] = array('AccountNumber' => $shipAccount, 'MeterNumber' => $meter);
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
                                          'PostalCode' => '89120',
                                          'CountryCode' => 'US'));
                                          
$request['RequestedShipment']['Recipient'] = array('Address' => array (
                                               'StreetLines' => array('Piispansilta 16'), // Destination details
                                               'City' => 'Espoo',
                                               'StateOrProvinceCode' => '',
                                               'PostalCode' => '02230',
                                               'CountryCode' => 'FI'));

$request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
                                                        'Payor' => array('AccountNumber' => $billAccount, // Replace 'XXX' with payor's account number
                                                                     'CountryCode' => 'US'));
//$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
$request['RequestedShipment']['PackageCount'] = 100;
$request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';

/*
$request['RequestedShipment']['RequestedPackageLineItems'] = array('0' => array('Weight' => array('Value' => 1.2,
                                                                                    'Units' => 'LB'),
                                                                                    'Dimensions' => array('Length' => 5,
                                                                                        'Width' => 1,
                                                                                        'Height' => 1,
                                                                                        'Units' => 'IN')));
*/

$request['RequestedShipment']['RequestedPackageLineItems'] = $arValues;

try 
{
    $response = $client ->getRates($request);
        
    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
    {
        echo 'Rates for following service type(s) were returned.'. Newline. Newline;
        echo '<table border="1">';
        echo '<tr><td>Service Type</td><td>Amount</td><td>Delivery Date</td>';
        foreach ($response -> RateReplyDetails as $rateReply)
        {           
        	echo '<tr>';
        	$serviceType = '<td>'.$rateReply -> ServiceType . '</td>';
        	$amount = '<td>$' . number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount,2,".",",") . '</td>';
        	if(array_key_exists('DeliveryTimestamp',$rateReply)){
        		$deliveryDate= '<td>' . $rateReply->DeliveryTimestamp . '</td>';
        	}else{
        		$deliveryDate= '<td>' . $rateReply->TransitTime . '</td>';
        	}
        	echo $serviceType . $amount. $deliveryDate;
        	echo '</tr>';
        }
        echo '</table>'. Newline;
    	printSuccess($client, $response);
    }
    else
    {
        printError($client, $response); 
    } 
    
    writeToLog($client);    // Write to log file   

} catch (SoapFault $exception) {
   printFault($exception, $client);        
}

?>
