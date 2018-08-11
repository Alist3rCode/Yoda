<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$array =[];


$select = $bdd->query('SELECT * FROM YDA_CLIENT WHERE CLI_ID="' . $_REQUEST['id'] . '"');

while ($query = $select->fetch()){
     $array['CLI_ID'] = $query['CLI_ID']; 
     $array['CLI_VILLE'] = $query['CLI_VILLE']; 
     $array['CLI_NOM'] = $query['CLI_NOM']; 
     $array['CLI_URL'] = $query['CLI_URL']; 
     $array['CLI_VERSION'] = $query['CLI_VERSION']; 
     $array['CLI_RIS'] = $query['CLI_RIS']; 
     $array['CLI_PACS'] = $query['CLI_PACS']; 
     $array['CLI_VIEW'] = $query['CLI_VIEW']; 
     $array['CLI_UVIEW'] = $query['CLI_UVIEW']; 
     $arrayTag =  $query['CLI_TAG'];
    
    }
$select->closeCursor();
// echo $arrayTag;
$array['CLI_TAG'] = explode(',', $arrayTag);
$arrayPhone =[];
$arraySite = [];
$arrayMail = [];
$arrayTX = [];
$arrayIdTV = [];
$arrayPasswordTV = [];
$arrayLat = [];
$arrayLon = [];
$idPhone = [];
$nbPhone = 0;
$select2 = $bdd->query('SELECT * FROM YDA_PHONE JOIN YDA_MAPS ON MPS_ID_PHO = PHO_ID WHERE PHO_ID_CLI="' . $_REQUEST['id'] . '" AND PHO_VALID = 1');

while ($query = $select2->fetch()){
     array_push($idPhone, $query['PHO_ID']);
     array_push($arrayPhone, $query['PHO_PHONE']);
     array_push($arraySite, $query['PHO_SITE']);
     array_push($arrayMail, $query['PHO_MAIL']);
     array_push($arrayTX, $query['PHO_TX']);
     array_push($arrayIdTV, $query['PHO_TV_ID']);
     array_push($arrayPasswordTV, $query['PHO_TV_PASSWORD']);
     array_push($arrayLat, $query['MPS_LAT']);
     array_push($arrayLon, $query['MPS_LON']);
     $nbPhone = $nbPhone + 1;
     
    }
$select2->closeCursor();
$array['PHO_ID'] = $idPhone;
$array['PHO_PHONE'] = $arrayPhone;
$array['PHO_MAIL'] = $arrayMail;
$array['PHO_TX'] = $arrayTX;
$array['PHO_TV_ID'] = $arrayIdTV;
$array['PHO_TV_PASSWORD'] = $arrayPasswordTV;
$array['MPS_LAT'] = $arrayLat;
$array['MPS_LON'] = $arrayLon;
$array['PHO_SITE'] = $arraySite;
$array['nbPhone'] = $nbPhone;

    header("content-type:application/json");
    echo json_encode($array);
    
?>