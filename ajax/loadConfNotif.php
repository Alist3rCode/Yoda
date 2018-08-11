<?php

error_reporting(E_ALL);
set_time_limit(0);

header("content-type:application/json");

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
$arrayCustomer = [];
    
    $select2 = $bdd->query('SELECT * FROM YDA_CLIENT WHERE CLI_VALID = 1 ORDER BY CLI_VILLE ASC ');
    $array['client'] = [];
    while ($query = $select2->fetch()){
        
        $arrayTemp['id']= $query['CLT_ID'];
        $arrayTemp['ville'] = $query['CLT_VILLE'];
        $arrayTemp['nom'] = $query['CLT_NOM'];
        array_push($arrayCustomer['client'], $arrayTemp);
        
    }

    $select = $bdd->query('SELECT * FROM YDA_NOTIF WHERE NTF_ID_USR="' . $_REQUEST["id"] . '"');

    while ($query = $select->fetch()){
    
        $arrayCustomer['notif'] = $query['NTF_UPDATE'];
        

    }
    


echo json_encode($array);
    
?>