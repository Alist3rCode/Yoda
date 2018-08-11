<?php
session_start();

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


$password = sha1($_REQUEST['password']);
$id = '';

if(isset($_REQUEST["email"]) && $_REQUEST["email"] != ''){
    $select = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_MAIL="' . $_REQUEST["email"] . '"');

    while ($query = $select->fetch()){
    
        $passwordSql = $query['USR_PASSWORD'];
        $page = $query['USR_PAGE'];
        $id = $query['USR_ID'];
        
    }
}
$array= [];

if($password == $passwordSql){
    
    $array[0] = 'OK';
    $array[1] = $page;
   
    
    $_SESSION['login'] = 'ok';
    $_SESSION['user'] = $_REQUEST["email"];
    $_SESSION['id_user'] = $id; 
    
} else{
    $array[0] = 'NOK';

    $_SESSION['login'] = 'nok';
    
}

echo json_encode($array);

?>