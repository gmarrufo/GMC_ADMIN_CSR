<?php

			// GMC - 02/24/09 - New Fedex Code
            require_once('../../library/fedex-common.php5');

			$newline = "<br />";

            //The WSDL is not included with the sample code.
            //Please include and reference in $path_to_wsdl variable.
            $path_to_wsdl = "../../wsdl/RateService_v5.wsdl";

            ini_set("soap.wsdl_cache_enabled", "0");

            $client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

            $request['WebAuthenticationDetail'] = array('UserCredential' =>

            // array('Key' => 'XXX', 'Password' => 'YYY')); // Replace 'XXX' and 'YYY' with FedEx provided credentials
            // array('Key' => 'EXwnDNgYUFzjTZ3d', 'Password' => 'AIqrN4wRcprZfjLvUXK49SAMH')); // Replace 'XXX' and 'YYY' with FedEx provided credentials
            array('Key' => 'hRnybIZX3PKne28q', 'Password' => 'yDjVeSkK252f1kFblX1AXy31b')); // Replace 'XXX' and 'YYY' with FedEx provided credentials

            // $request['ClientDetail'] = array('AccountNumber' => 'XXX', 'MeterNumber' => 'XXX');// Replace 'XXX' with your account and meter number
            // $request['ClientDetail'] = array('AccountNumber' => '510087283', 'MeterNumber' => '100001092');// Replace 'XXX' with your account and meter number
            $request['ClientDetail'] = array('AccountNumber' => '462227000', 'MeterNumber' => '100677102');// Replace 'XXX' with your account and meter number

            $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Available Services Request v4 using PHP ***');

            // $request['Version'] = array('ServiceId' => 'crs', 'Major' => '4', 'Intermediate' => '0', Minor => '0');
            $request['Version'] = array('ServiceId' => 'crs', 'Major' => '5', 'Intermediate' => '0', 'Minor' => '0');

            $request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
            $request['RequestedShipment']['ShipTimestamp'] = date('c');

            // Service Type and Packaging Type are not passed in the request
            $request['RequestedShipment']['Shipper'] = array('Address' => array(
                                          'StreetLines' => array('10 Fed Ex Pkwy'), // Origin details
                                          'City' => 'Beaverton',
                                          'StateOrProvinceCode' => 'NV',
                                          'PostalCode' => '89120',
                                          'CountryCode' => 'US'));
            $request['RequestedShipment']['Recipient'] = array('Address' => array (
                                          'StreetLines' => array('Main St'), // Destination details
                                          'City' => 'Tokwawan',
                                          'StateOrProvinceCode' => '',
                                          'PostalCode' => '',
                                          'CountryCode' => 'HK'));
            $request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
                                          'Payor' => array('AccountNumber' => '462227000', // Replace "XXX" with payor's account number  - 510087283
                                          'CountryCode' => 'US'));
            $request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT';
            // $request['RequestedShipment']['RateRequestTypes'] = 'LIST';
            $request['RequestedShipment']['PackageCount'] = '14';
            $request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
            $request['RequestedShipment']['RequestedPackages'] = array('0' => array('SequenceNumber' => '1',
                                          'InsuredValue' => array('Amount' => 0.0,
                                          'Currency' => 'USD'),
                                          'ItemDescription' => 'College Transcripts',
                                          'Weight' => array('Value' => 223.0,
                                          'Units' => 'LB'),
                                          'Dimensions' => array('Length' => 5,
                                          'Width' => 1,
                                          'Height' => 1,
                                          'Units' => 'IN'),
                                          'CustomerReferences' => array('CustomerReferenceType' => 'CUSTOMER_REFERENCE',
                                          'Value' => 'Undergraduate application')));

										  
			echo "Address: Any St.|";
			echo "City:Tokwawan|";
			echo "Postal Code:|";
			echo "Country: HK|";
			echo "Weight: 223lb|";
			echo "Package Count: 14";
			echo "<br /><br />";
										  
            try
            {
                 $response = $client ->getRates($request);
                 
                 if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
                 {
                      echo 'Rates for following service type(s) were returned.'. $newline. $newline;

                      foreach ($response -> RateReplyDetails as $rateReply)
                      {
                          // echo $rateReply -> ServiceType;
                          // echo $newline;
                          $serviceType = $rateReply -> ServiceType;
                          
                          if(is_array($response->RateReplyDetails))
                          {
                              $tnfec = $rateReply->RatedShipmentDetails;
                              foreach ($tnfec as $wha)
                              {
                                  // echo $wha->ShipmentRateDetail->TotalNetFedExCharge->Amount;
                                  // echo $newline;
                                  $amount = $wha->ShipmentRateDetail->TotalNetFedExCharge->Amount;
                              }
                          }
                          
                          echo $serviceType;
                          echo "  $";
                          echo $amount;
                          echo $newline;
                          
                      }

                      // printRequestResponse($client);
                 }
                 else
                 {
                      echo 'Error in processing transaction.'. $newline. $newline;

                      foreach ($response -> Notifications as $notification)
                      {
                          if(is_array($response -> Notifications))
                          {
                              echo $notification -> Severity;
                              echo ': ';
                              echo $notification -> Message . $newline;
                          }
                          else
                          {
                              echo $notification . $newline;
                          }
                      }
                 }

                 // writeToLog($client);    // Write to log file

            }
            catch (SoapFault $exception)
            {
                printFault($exception, $client);
            }

?>
