<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$arrayCustomerConfig = [];
    $select = $bdd->query('SELECT * FROM YDA_CLIENT WHERE CLI_VALID = 1 ORDER BY CLI_VILLE ASC ');
    $arrayCustomerConfig['client'] = [];
    
    while ($AllCustomerConfig = $select->fetch()){
        
        $arrayTempConfig['id']= $AllCustomerConfig['CLI_ID'];
        $arrayTempConfig['ville'] = $AllCustomerConfig['CLI_VILLE'];
        $arrayTempConfig['nom'] = $AllCustomerConfig['CLI_NOM'];
        array_push($arrayCustomerConfig['client'], $arrayTempConfig);
        
    }

    $select3 = $bdd->query('SELECT * FROM YDA_NOTIF WHERE NTF_ID_USR="' . $_REQUEST['id'] . '"');

    while ($SelectedCustomerConfig = $select3->fetch()){
    
        $arrayCustomerConfig['notif'] = explode(',',$SelectedCustomerConfig['NTF_UPDATE']);
        $arrayCustomerConfig['create'] = $SelectedCustomerConfig['NTF_CREATE'];
        $arrayCustomerConfig['modif'] = $SelectedCustomerConfig['NTF_MODIF'];
        $arrayCustomerConfig['new_custo'] = $SelectedCustomerConfig['NTF_NEW_CUSTO'];
       
    }
    if(empty($arrayCustomerConfig['notif'])){
         $arrayCustomerConfig['notif'] = '';
        $arrayCustomerConfig['create'] = 0;
        $arrayCustomerConfig['modif'] = 0;
        $arrayCustomerConfig['new_custo'] = 0;
    }
    
    header("content-type:application/json");
    echo json_encode($arrayCustomerConfig);
    
?>