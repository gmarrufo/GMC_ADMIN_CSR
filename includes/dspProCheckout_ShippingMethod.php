<p>Please select a shipping method.</p>

<form action="/pro/checkout.php" method="post">
<table width="100%" cellpadding="0" cellspacing="5">

<?php

// GMC - 01/22/11 - Replace UPS Shipping Rates For FedEx ShoppingCart
// $path_to_wsdl = "http://localhost/wsdl/RateService_v8.wsdl"; // Test
$path_to_wsdl = "https://secure.revitalash.com/wsdl/RateService_v8.wsdl"; // Production

$_SESSION['ShipToZip'] = "";
$_SESSION['ShipToCountry'] = "";
$_SESSION['ShipToCity'] = "";
$_SESSION['ShipToState'] = "";
$_SESSION['ShipHandlingCharge'] = 0;

if (!isset($_SESSION['OrderWeight']))
	$_SESSION['OrderWeight'] = 0.5;

// GMC - 01/22/11 - Replace UPS Shipping Rates For FedEx ShoppingCart
if (mssql_num_rows($rs) > 0)
{
 while($row = mssql_fetch_array($rs))
 {
  $_SESSION['ShipToZip'] = $row["ShipToZIP"];
  $_SESSION['ShipToCountry'] = $row["ShipToCountry"];
  $_SESSION['ShipToCity'] = $row["ShipToCity"];
  $_SESSION['ShipToState'] = $row["ShipToState"];
 }
}

if ($_SESSION['ShipToCountry'] == 'US')
{
   $_SESSION['ShipHandlingCharge'] = 5;
}
else
{
   $_SESSION['ShipHandlingCharge'] = 15;
}

// GMC - 01/22/11 - Replace UPS Shipping Rates For FedEx ShoppingCart
ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array('UserCredential' =>

// array('Key' => 'XXX', 'Password' => 'YYY')); // Replace 'XXX' and 'YYY' with FedEx provided credentials
array('Key' => 'hRnybIZX3PKne28q', 'Password' => 'yDjVeSkK252f1kFblX1AXy31b')); // Replace 'XXX' and 'YYY' with FedEx provided credentials

// $request['ClientDetail'] = array('AccountNumber' => 'XXX', 'MeterNumber' => 'XXX');// Replace 'XXX' with your account and meter number
$request['ClientDetail'] = array('AccountNumber' => '462227000', 'MeterNumber' => '100677102');// Replace 'XXX' with your account and meter number

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
                          'City' => $_SESSION['ShipToCity'],
                          'StateOrProvinceCode' => $_SESSION['ShipToState'],
                          'PostalCode' => $_SESSION['ShipToZip'],
                          'CountryCode' => $_SESSION['ShipToCountry']));
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
               echo '<tr>
                    <td width="30"><input type="radio" name="ShipMethodID" value="199" /></td>
                    <td width="*">Ground ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                    </tr>';
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="201" /></td>
                   <td width="*">2 Day ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="200" /></td>
                   <td width="*">Express Saver ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="211" /></td>
                   <td width="*">1 Day Freight ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="212" /></td>
                   <td width="*">2 Day Freight ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="213" /></td>
                   <td width="*">3 Day Freight ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
          }

          // International Override
          /*
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="205" /></td>
                   <td width="*">International Priority ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="206" /></td>
                   <td width="*">International Economy ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="214" /></td>
                   <td width="*">International Priority Freight ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="215" /></td>
                   <td width="*">International Economy Freight ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
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
              echo '<tr>
                   <td width="30"><input type="radio" name="ShipMethodID" value="207" /></td>
                   <td width="*">International First ($' . number_format($ResultCode, 2, '.', '') . ')</td>
                   </tr>';
          }
          */
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
 echo $exception;
}

// International Override
if ($_SESSION['ShipToCountry'] != 'US')
{
    $ResultCode = 41;
    echo '<tr>
         <td width="30"><input type="radio" name="ShipMethodID" value="205" /></td>
         <td width="*">International Override ($' . number_format($ResultCode, 2, '.', '') . ')</td>
         </tr>';
}

?>

<tr>
    <td colspan="2">&nbsp;</td>
</tr>


<tr>
    <td colspan="2"><input type="submit" name="cmdSetShippingMethod" value="Continue" class="formSubmit" /></td>
</tr>

</table>
</form>
