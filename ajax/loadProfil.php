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

$select = $bdd->query('SELECT * FROM YDA_PROFIL WHERE PRO_VALID = 1');

while ($query = $select->fetch()){
        $arrayTemp = [];
        
        $arrayTemp['id'] = $query['PRO_ID'];
        $arrayTemp['name'] = $query['PRO_NAME'];
        
        array_push($array, $arrayTemp);
}


echo json_encode($array);
    
?>