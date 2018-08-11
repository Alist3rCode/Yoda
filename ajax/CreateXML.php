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

$select = $bdd->query("SELECT MPS_ID, CONCAT(CLI_VILLE, ' - ', CLI_NOM), PHO_SITE, MPS_LAT, MPS_LON, CLI_RIS, CLI_PACS, CLI_VERSION FROM YDA_MAPS JOIN YDA_CLIENT ON MPS_ID_CLI = CLI_ID JOIN YDA_PHONE ON MPS_ID_PHO = PHO_ID WHERE CLI_VALID = 1 AND PHO_VALID = 1");

$xml = '<markers>';

while ($query = $select->fetch()){
    
    if($query[7] == 'v6'){
        $version = 'v6';
    }else{
        $version = 'v7';
    }
    
    if($query[5] == 0 && $query[6] == 0){
        $activity = 'nada';
    }
    if($query[5] == 1 && $query[6] == 0){
        $activity = 'ris';
    }
    if($query[5] == 0 && $query[6] == 1){
        $activity = 'pacs';
    }
    if($query[5] == 1 && $query[6] == 1){
        $activity = 'rispacs';
    }
    
    $xml .= '<marker id="' . $query[0] . '" name="' . $query[1] . '" address= "' . $query[2] . '" lat="' . $query[3] . '" lng="' . $query[4] . '"  activity="' . $activity . '"  version="' . $version . '"  />' ;

}

$xml.='</markers>';

file_put_contents("../address.xml",$xml);

echo $xml;

?>