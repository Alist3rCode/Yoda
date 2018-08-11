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
$array = [];
    
    $select2 = $bdd->query('SELECT * FROM YDA_RIGHT');
    $array['right'] = [];
    while ($query = $select2->fetch()){
        
        $arrayTemp['id']= $query['RGT_ID'];
        $arrayTemp['name'] = $query['RGT_NAME'];
        array_push($array['right'], $arrayTemp);
        
    }

    $select = $bdd->query('SELECT * FROM YDA_PROFIL WHERE PRO_ID="' . $_REQUEST["id"] . '"');

    while ($query = $select->fetch()){
    
        $array['profil']['name'] = $query['PRO_NAME'];
        $array['profil']['actif'] = $query['PRO_VALID'];

    }
    
    $select3 = $bdd->query('SELECT * FROM YDA_HOOK WHERE HOK_ID_PRO="' . $_REQUEST["id"] . '"');
    $array['hook'] = [];
    while ($query = $select3->fetch()){
    
        array_push($array['hook'],$query['HOK_ID_RGT']);

    }
    
    

echo json_encode($array);
    
?>