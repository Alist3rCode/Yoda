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
$page = '';
$array = [];
switch ($_REQUEST['page']) {
    case 'Dashboard' :
        $page = 'index.php';
        break;
    case 'Clients' :
        $page = 'yoda.php';
        break;
    case 'Clients_Vers' :
        $page = 'yoda.php?filter=ok';
        break;
    case 'Clients_Acti' :
        $page = 'yoda.php?filter=activity';
        break;
    case 'Carte' :
        $page = 'maps.php';
        break;
    case 'Interne' :
        $page = 'interne.php';
        break;
}



$req = $bdd->prepare('INSERT INTO YDA_USERS( USR_MAIL, USR_PASSWORD, USR_ID_PRO, USR_FIRST_NAME, USR_NAME, USR_PAGE) VALUES (:email, :password, :profil, :name, :lastName, :page)');
    
    $req->execute(array(
	'email' => strtolower($_REQUEST['email']),
	'password' => sha1($_REQUEST['password']),
	'profil' => '2',
	'name' => strtolower($_REQUEST['name']),
	'lastName' => strtolower($_REQUEST['lastName']),
	'page' => $page)) or die(print_r($req->errorInfo()));
	$array['ok'] = 'ok';

$select = $bdd->query('SELECT MAX(USR_ID) FROM YDA_USERS WHERE USR_MAIL="' . $_REQUEST["email"] . '"');

    while ($query = $select->fetch()){
    
        $array['id'] = $query['MAX(USR_ID)'];
    }

header("content-type:application/json");    
echo json_encode($array);


?>