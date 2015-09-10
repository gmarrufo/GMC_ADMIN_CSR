<?php
require_once("xmlparser.php");

class USPS {

    var $server = "";
    var $user = "";
    var $pass = "";
    var $service = "";
    var $dest_zip;
    var $orig_zip;
    var $pounds;
    var $ounces;
    var $container = "None";
    var $size = "REGULAR";
    var $machinable;
    var $country = "USA";
    
    function setServer($server) {
        $this->server = $server;
    }

    function setUserName($user) {
        $this->user = $user;
    }

    function setPass($pass) {
        $this->pass = $pass;
    }

    function setService($service) {
        /* Must be: Express, Priority, or Parcel */
        $this->service = $service;
    }
    
    function setDestZip($sending_zip) {
        /* Must be 5 digit zip (No extension) */
        $this->dest_zip = $sending_zip;
    }

    function setOrigZip($orig_zip) {
        $this->orig_zip = $orig_zip;
    }

    function setWeight($pounds, $ounces=0) {
        /* Must weight less than 70 lbs. */
        $this->pounds = $pounds;
        $this->ounces = $ounces;
    }

    function setContainer($cont) {
        $this->container = $cont;
    }

    function setSize($size) {
        $this->size = $size;
    }

    function setMachinable($mach) {
        /* Required for Parcel Post only, set to True or False */
        $this->machinable = $mach;
    }
    
    function setCountry($country) {
        $this->country = $country;
    }
    
    function getPrice() {
        if($this->country=="USA"){
            // may need to urlencode xml portion
            $str = "http://testing.shippingapis.com/ShippingAPITest.dll" . "?API=RateV2&XML=<RateV2Request%20USERID=\"";
            $str .= "004GEEKT1462" . "\"%20PASSWORD=\"" . "931HN41XW201" . "\"><Package%20ID=\"0\"><Service>";
            $str .= "All" . "</Service><ZipOrigination>" . "10022" . "</ZipOrigination>";
            $str .= "<ZipDestination>" . "20008" . "</ZipDestination>";
            $str .= "<Pounds>" . "2" . "</Pounds><Ounces>" . "0" . "</Ounces>";
            $str .= "<Container>" . urlencode($this->container) . "</Container><Size>" . "LARGE" . "</Size>";
            $str .= "<Machinable>" . "true" . "</Machinable></Package></RateV2Request>";
        }
        
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $str);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // grab URL and pass it to the browser
        $ats = curl_exec($ch);

        // close curl resource, and free up system resources
        curl_close($ch);
        $xmlParser = new xmlparser();
        $array = $xmlParser->GetXMLTree($ats);
        //$xmlParser->printa($array);
        if (isset($array['RATEV2RESPONSE']) && count($array['RATEV2RESPONSE']))
		{ // if everything OK
            //print_r($array['RATEV2RESPONSE']);
            $this->zone = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ZONE'][0]['VALUE'];
            foreach ($array['RATEV2RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value)
			{
				if ($value['MAILSERVICE'][0]['VALUE'] == 'Priority Mail')
				{
                	$decPrice = $value['RATE'][0]['VALUE'] + 5;
				}
            }
        }
		else
			$decPrice = 0; 
        
        return $decPrice;
    }
}
class price
{
    var $mailservice;
    var $rate;
}
class intPrice
{
    var $id;
    var $rate;
}
?> 
