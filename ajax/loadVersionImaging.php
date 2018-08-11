<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

try
{
	$bdd2 = new PDO('mysql:host=localhost;dbname=ecsupgrader;charset=utf8', 'yoda', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
    
    
$i = $_REQUEST['id'];
$uid = '';
$version = '';
$array['uid'] = '';

$select = $bdd->query('SELECT CLI_VERSION FROM YDA_CLIENT WHERE CLI_ID ="' . $i . '"');
                    
    while ($query = $select->fetch()){
        if($query['CLI_VERSION'] == 'v7'){
            $color = '#87cdf1';
        }
        if($query['CLI_VERSION'] == 'v6'){
            $color = '#f6e18b';
        }
    }

$select = $bdd->query('SELECT CLI_UID FROM YDA_CLIENT WHERE CLI_ID ="' . $i . '"');
                    
    while ($query = $select->fetch()){
        $uid = $query['CLI_UID'];
    }
    // echo $uid;
if ($uid == ''){
    $select = $bdd->query('SELECT CLI_NUM_VERSION FROM YDA_CLIENT WHERE CLI_ID ="' . $i . '"');
                    
        while ($query = $select->fetch()){
            $version = $query['CLI_NUM_VERSION'];
        }
    $array['uid'] = 'non';    
}else{
    $array['uid'] = 'oui';    
    $select = $bdd2->query('SELECT * FROM wrk_client where wrk_client.uid = "' . $uid . '"');
                    
    while ($query = $select->fetch()){
        $version = $query['version'] . '.' . $query['hotfix'];
    }
    
}

// echo $historique;
$array['version'] = $version;
header("content-type:application/json");
echo json_encode($array);