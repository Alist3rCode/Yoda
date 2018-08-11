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



$today = date("Y-m-d H:i:s");  

if(isset($_REQUEST["email"]) && $_REQUEST["email"] != ''){
    $select = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_MAIL="' . $_REQUEST["email"] . '"');

    while ($query = $select->fetch()){
    
        $dateCrea = $query['USR_CREATE'];
        $dateSupp = $query['USR_DELETE'];
        $mailSql = $query['USR_MAIL'];
        
       
    }
}
if ($dateCrea == '' && $mailSql !=''){
    echo 'WAIT';
}else if($dateCrea < $today && $dateCrea != ''){
    if($dateSupp > $today && $dateSupp != ''){
        echo 'NOPE';
    }else{
        echo 'WELCOME';
    }
    
}else{
    echo 'DUNNO';
}
    
?>