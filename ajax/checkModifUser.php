<?php

error_reporting(E_ALL);
set_time_limit(0);

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}



if(isset($_REQUEST["email"]) && $_REQUEST["email"] != ''){
    $select = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_MAIL="' . $_REQUEST["email"] . '"');

    while ($query = $select->fetch()){
    
        $idUser = $query['USR_ID'];
    }
}

echo $idUser;
    
?>