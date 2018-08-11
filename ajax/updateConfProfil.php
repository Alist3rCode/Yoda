<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}


$idProfil = '';
$name = '';
$hook = $_REQUEST['hook'];

$valid = '';

if ($_REQUEST['mode'] == 'update' && $_REQUEST['id'] != 'NEW'){
    
    $select = $bdd->query('SELECT * FROM YDA_PROFIL WHERE PRO_ID="' . $_REQUEST["id"] . '"');

    while ($query = $select->fetch()){
    
        $idProfil = $_REQUEST["id"];
        $name = ucfirst(strtolower($_REQUEST['name']));
        $valid = $query['PRO_VALID'];
        
    }
    
    $delete = $bdd->exec('DELETE FROM YDA_HOOK WHERE HOK_ID_PRO="' . $_REQUEST["id"] . '"');

    for($i=0; $i<count($hook); $i++){
    
    $req = $bdd->prepare('INSERT INTO YDA_HOOK( HOK_ID_PRO, HOK_ID_RGT) VALUES (:idProfil, :right)');
    
    $req->execute(array(
	'idProfil' => $idProfil,
	'right' => $hook[$i])) or die(print_r($req->errorInfo()));
    
    }
    $retour = 'ok';
     
}else if($_REQUEST['mode'] == 'update' && $_REQUEST['id'] == 'NEW'){
    

$req = $bdd->prepare('INSERT INTO YDA_PROFIL(PRO_NAME, PRO_VALID) VALUES (:name, :valid)');
    
    $req->execute(array(
	'name' => ucfirst(strtolower($_REQUEST['name'])),
	'valid' => 1)) or die(print_r($req->errorInfo()));
	
	$select = $bdd->query('SELECT MAX(PRO_ID) FROM YDA_PROFIL');

    while ($query = $select->fetch()){
    
        $retour = 'id-' . $query['MAX(PRO_ID)'];
    }
    for($i=0; $i<strlen($hook); $i++){
    
    $req = $bdd->prepare('INSERT INTO YDA_HOOK( HOK_ID_PRO, HOK_ID_RGT) VALUES (:idProfil, :right)');
    
    $req->execute(array(
	'idProfil' => substr($retour, 0, 3),
	'right' => $hook[$i])) or die(print_r($req->errorInfo()));
    
    }

}

if ($_REQUEST['mode'] == 'valid'){
        
    $select = $bdd->query('SELECT * FROM YDA_PROFIL WHERE PRO_ID="' . $_REQUEST["id"] . '"');

    while ($query = $select->fetch()){
    
        $idProfil = $_REQUEST["id"];
        $name = $query['PRO_NAME'];
        $valid = $_REQUEST['valid'];
     
    }
        
        $retour = 'ok';
    
}



$update = $bdd->prepare('UPDATE YDA_PROFIL SET PRO_NAME = :name, PRO_VALID = :valid WHERE PRO_ID = :id');
    $update ->execute(array(
    	'id' => $idProfil,
    	'name' => $name,
    	'valid' => $valid
	));


echo $retour;


// echo '</pre>';