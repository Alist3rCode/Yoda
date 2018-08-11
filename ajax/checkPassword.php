<?php
session_start();

error_reporting(E_ALL);
set_time_limit(0);

header("content-type:application/json");

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
// 	$bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
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
    
    $token = sha1(microtime(true));
    $_SESSION['token'] = $token;
    $timeout = new DateTime(date('Y-m-d H:i:s'));
    $timeout->modify('+4 days');
    $_SESSION['timeout'] = $timeout->format('Y-m-d H:i:s');

    
    $req = $bdd->prepare('INSERT INTO YDA_SESSION(SES_TOKEN, SES_ID_USR, SES_TIMEOUT) VALUES (:token, :id_user, :timeout)');
    
    $req->execute(array(
    'token' => $token,
    'id_user' => $id,
    'timeout' => $timeout->format('Y-m-d H:i:s')
    )) or die(print_r($bdd->errorCode()));
    
    
    
} else{
    $array[0] = 'NOK';

    $_SESSION['login'] = 'nok';
    
}

echo json_encode($array);

?>