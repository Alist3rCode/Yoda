<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

error_reporting(E_ALL);
set_time_limit(0);

include("geoipcity.inc");
include("geoipregionvars.php");

$geolocArray = [];


$selectIP = $bdd->query('SELECT SPY_IP FROM YDA_SPY');
while ($geoloc = $selectIP->fetch()){
    
    // echo $geoloc['SPY_IP']. "\n";
                
    $gi = geoip_open(realpath("GeoLiteCity.dat"),GEOIP_STANDARD);
    
    $record = geoip_record_by_addr($gi,$geoloc['SPY_IP']);
    
    // echo $record->country_name . "\n";
    // echo $GEOIP_REGION_NAME[$record->country_code][$record->region] . "\n";
    // echo $record->city . "\n";
    // echo $record->postal_code . "\n";
    // echo $record->latitude . "\n";
    // echo $record->longitude . "\n";
    $temp = [];
    $lat = $record->latitude;
    // echo $lat;
    $lon = $record->longitude;
    // echo $lon;
    $temp['lat'] = $lat;
    $temp['lng'] = $lon;
    array_push($geolocArray, $temp);

    geoip_close($gi);
}
// echo '<pre>';
echo json_encode($geolocArray);
// echo '</pre>';

?>