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

$select = $bdd->query('SELECT USR_ID FROM YDA_USERS WHERE (USR_MAIL LIKE "%' . $_REQUEST['search'] . '%" OR USR_FIRST_NAME LIKE "%' . $_REQUEST['search'] . '%" OR USR_NAME LIKE "%' . $_REQUEST['search'] . '%" OR USR_SURNAME LIKE "%' . $_REQUEST['search'] . '%")');

while ($query = $select->fetch()){
      array_push($array, $query['USR_ID']); 
    }
$select->closeCursor();

    header("content-type:application/json");
    echo json_encode($array);
    
?>