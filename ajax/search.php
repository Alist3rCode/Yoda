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
if ($_REQUEST['filter'] == "none" || $_REQUEST['filter'] == "ok"){
    $select = $bdd->query('SELECT DISTINCT CLI_ID FROM YDA_CLIENT LEFT JOIN YDA_PHONE ON (CLI_ID = PHO_ID_CLI AND PHO_VALID = 1) WHERE (CLI_VILLE LIKE "%' . $_REQUEST['search'] . '%" OR CLI_NOM LIKE "%' . $_REQUEST['search'] . '%" OR CLI_URL LIKE "%' . $_REQUEST['search'] . '%" OR CLI_TAG LIKE "%' . $_REQUEST['search'] . '%" OR PHO_SITE LIKE "%' . $_REQUEST['search'] . '%" OR PHO_PHONE LIKE "%' . $_REQUEST['search'] . '%" OR PHO_MAIL LIKE "%' . $_REQUEST['search'] . '%" OR PHO_TX LIKE "%' . $_REQUEST['search'] . '%") AND CLI_VALID=1');

}else{
    $select = $bdd->query('SELECT DISTINCT CLI_ID FROM YDA_CLIENT LEFT JOIN YDA_PHONE ON (CLI_ID = PHO_ID_CLI AND PHO_VALID = 1) WHERE (CLI_VILLE LIKE "%' . $_REQUEST['search'] . '%" OR CLI_NOM LIKE "%' . $_REQUEST['search'] . '%" OR CLI_URL LIKE "%' . $_REQUEST['search'] . '%" OR CLI_TAG LIKE "%' . $_REQUEST['search'] . '%" OR PHO_SITE LIKE "%' . $_REQUEST['search'] . '%" OR PHO_PHONE LIKE "%' . $_REQUEST['search'] . '%"  OR PHO_MAIL LIKE "%' . $_REQUEST['search'] . '%" OR PHO_TX LIKE "%' . $_REQUEST['search'] . '%") AND CLI_VERSION = "'.$_REQUEST['filter'].'" AND CLI_VALID=1');
}


while ($query = $select->fetch()){
      array_push($array, $query['CLI_ID']); 
    }
$select->closeCursor();

    header("content-type:application/json");
    echo json_encode($array);
    
?>