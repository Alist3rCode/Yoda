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
$array2 = [];
if($_REQUEST['id'] == 'ALL'){
    if ($_REQUEST['mode'] == 'notif'){
        $select = $bdd->query('SELECT * FROM YDA_USERS JOIN YDA_PROFIL ON PRO_ID = USR_ID_PRO AND USR_DELETE IS NULL ORDER BY USR_FIRST_NAME ');
    }
    if ($_REQUEST['mode'] == 'profil'){
        $select = $bdd->query('SELECT * FROM YDA_USERS JOIN YDA_PROFIL ON PRO_ID = USR_ID_PRO ORDER BY USR_FIRST_NAME ');
    }
    

    while ($query = $select->fetch()){
    
        $array['id'] = $query['USR_ID'];
        $array['profil'] = $query['PRO_NAME'];
        $array['name'] = $query['USR_FIRST_NAME'];
        $array['lastName'] = $query['USR_NAME'];
        if($query['USR_CREATE'] == ''){
            $array['color'] = 'orange';
        }else if($query['USR_DELETE'] != ''){
            $array['color'] = 'red';
        }else{
            $array['color'] = 'black';
        }
        array_push($array2, $array);
    }
}else{
    $select = $bdd->query('SELECT * FROM YDA_USERS JOIN YDA_PROFIL ON PRO_ID = USR_ID_PRO WHERE USR_ID in (' . $_REQUEST['id'] . ') ORDER BY USR_FIRST_NAME');

    while ($query = $select->fetch()){
    
        $array['id'] = $query['USR_ID'];
        $array['profil'] = $query['PRO_NAME'];
        $array['name'] = $query['USR_FIRST_NAME'];
        $array['lastName'] = $query['USR_NAME'];
        if($query['USR_CREATE'] == ''){
            $array['color'] = 'orange';
        }else if($query['USR_DELETE'] != ''){
            $array['color'] = 'red';
        }else{
            $array['color'] = 'black';
        }
        array_push($array2, $array);
    }
}

header("content-type:application/json");    
echo json_encode($array2);
    
?>