<?php

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


if(isset($_REQUEST["id"]) && $_REQUEST["id"] != ''){
    $select = $bdd->query('SELECT * FROM YDA_USERS JOIN YDA_PROFIL ON USR_ID_PRO = PRO_ID WHERE USR_ID="' . $_REQUEST["id"] . '"');

    while ($query = $select->fetch()){
    
        $array['email'] = $query['USR_MAIL'];
        $array['name'] = $query['USR_FIRST_NAME'];
        $array['lastName'] = $query['USR_NAME'];
        echo $query['page'];
        $page = '';
        switch ($query['USR_PAGE']) {
            case 'index.php' :
                $page = 'Dashboard';
                break;
            case 'yoda.php' :
                $page = 'Clients';
                break;
            case 'yoda.php?filter=ok' :
                $page = 'Clients_Vers';
                break;
            case 'yoda.php?filter=activity' :
                $page = 'Clients_Acti';
                break;
            case 'maps.php' :
                $page = 'Carte';
                break;
            case 'interne.php' :
                $page = 'Interne';
                break;
        }
        
        
        
        if($query['USR_CREATE'] == ''){
            $array['actif'] = 0;
        }else if($query['USR_DELETE'] != ''){
            $array['actif'] = 1;
        }else{
            $array['actif'] = 2;
        }

        $array['page'] = $page;
        $array['profil'] = $query['PRO_NAME'];
        $array['idProfil'] = $query['USR_ID_PRO'];
        
        $array['isTech'] = $query['USR_TECH'];
        $array['isRef'] = $query['USR_REFERING'];
        $array['isDirection'] = $query['USR_DIRECTION'];
        $array['surname'] = $query['USR_SURNAME'];
        $array['teamviewer'] = $query['USR_TEAMVIEWER'];
        
    }
}
echo json_encode($array);
    
?>