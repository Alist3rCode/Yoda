<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$id = '';
$email = '';
$password = '';
$profil = '';
$name = '';
$firstName = '';
$create = '';
$delete = '';

$today = date("Y-m-d H:i:s");  

if ($_REQUEST['mode'] == 'reset'){
    $select = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_MAIL="' . $_REQUEST["email"] . '"');

    while ($query = $select->fetch()){
    
        $id = $query['USR_ID'];
        $email = $query['USR_MAIL'];
        $password = $_REQUEST['password'];
        $profil = $query['USR_ID_PRO'];
        $name = $query['USR_NAME'];
        $firstName = $query['USR_FIRST_NAME'];
        $page = $query['USR_PAGE'];
        $create = $query['USR_CREATE'];
        $delete = $query['USR_DELETE'];
        $isTech = $query['USR_TECH'];
        $isRef = $query['USR_REFERING'];
        $isDirection = $query['USR_DIRECTION'];
        $surname = $query['USR_SURNAME'];
        $teamviewer = $query['USR_TEAMVIEWER'];
        
    }
    $retour = 'ok';
}

if ($_REQUEST['mode'] == 'update' && $_REQUEST['id'] != 'NEW'){
    
    $select = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_ID="' . $_REQUEST["id"] . '"');

    while ($query = $select->fetch()){
        
        $id = $_REQUEST["id"];
        $email = $_REQUEST['email'];
        
        if($_REQUEST['password'] == 'PASTOUCHE'){
            $password = $query['USR_PASSWORD'];
        }else{
           $password = sha1($_REQUEST['password']);
        }
        
        
        $profil = $_REQUEST['idProfil'];
        
        
        $name = strtolower($_REQUEST['lastName']);
        $firstName = strtolower($_REQUEST['name']);
        
        $create = $query['USR_CREATE'];
        $delete = $query['USR_DELETE'];
        
        $page = '';
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
        
        $isTech = $_REQUEST['isTech'];
        $isRef = $_REQUEST['isRef'];
        $isDirection = $_REQUEST['isDirection'];
        
        $surname = $_REQUEST['surname'];
        $teamviewer = $_REQUEST['teamviewer'];
        
    }
    
     $retour = 'ok';
     
}else if($_REQUEST['mode'] == 'update' && $_REQUEST['id'] == 'NEW'){
    $page = '';
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

$req = $bdd->prepare('INSERT INTO YDA_USERS(USR_MAIL, USR_PASSWORD, USR_ID_PRO, USR_FIRST_NAME, USR_NAME, USR_PAGE, USR_TECH, USR_REFERING, USR_DIRECTION) VALUES (:email, :password, :profil, :name, :lastName, :page, :isTech, :isRef, :isDirection)');
    
    $req->execute(array(
	'email' => strtolower($_REQUEST['email']),
	'password' => sha1($_REQUEST['password']),
	'profil' => $_REQUEST["idProfil"],
	'name' => strtolower($_REQUEST['name']),
	'lastName' => strtolower($_REQUEST['lastName']),
	'page' => $page,
	'isTech' => $_REQUEST['isTech'],
	'isRef' => $_REQUEST['isRef'],
	'isDirection' => $_REQUEST['isDirection'])) or die(print_r($req->errorInfo()));
	
	$select = $bdd->query('SELECT MAX(USR_ID) FROM YDA_USERS');

    while ($query = $select->fetch()){
    
        $retour = 'id-' . $query['MAX(USR_ID)'];
    }

}

if ($_REQUEST['mode'] == 'active'){
    if($_REQUEST['actif'] == 0){
        
        $select = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_ID="' . $_REQUEST["id"] . '"');
    
        while ($query = $select->fetch()){
            
            $id = $query['USR_ID'];
            $email = $query['USR_MAIL'];
            $password = $query['USR_PASSWORD'];
            $profil = $query['USR_ID_PRO'];
            $name = $query['USR_NAME'];
            $firstName = $query['USR_FIRST_NAME'];
            $page = $query['USR_PAGE'];
            $create = $query['USR_CREATE'];
            $delete = $today;
            $isTech = $query['USR_TECH'];
            $isRef = $query['USR_REFERING'];
            $isDirection = $query['USR_DIRECTION'];
            $surname = $query['USR_SURNAME'];
            $teamviewer = $query['USR_TEAMVIEWER'];
            
        }
        
         $retour = 'ok';
    }else if($_REQUEST['actif'] == 1){
        
        $select = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_ID="' . $_REQUEST["id"] . '"');
    
        while ($query = $select->fetch()){
            
            $id = $query['USR_ID'];
            $email = $query['USR_MAIL'];
            $password = $query['USR_PASSWORD'];
            $profil = $query['USR_ID_PRO'];
            $name = $query['USR_NAME'];
            $firstName = $query['USR_FIRST_NAME'];
            $page = $query['USR_PAGE'];
            $create = $query['USR_CREATE'];
            $delete = NULL;
            $isTech = $query['USR_TECH'];
            $isRef = $query['USR_REFERING'];
            $isDirection = $query['USR_DIRECTION'];
            $surname = $query['USR_SURNAME'];
            $teamviewer = $query['USR_TEAMVIEWER'];
            
        }
        
         $retour = 'ok';
    }else if($_REQUEST['actif'] == 2){
        
        $select = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_ID="' . $_REQUEST["id"] . '"');
    
        while ($query = $select->fetch()){
            
            $id = $query['USR_ID'];
            $email = $query['USR_MAIL'];
            $password = $query['USR_PASSWORD'];
            $profil = $query['USR_ID_PRO'];
            $name = $query['USR_NAME'];
            $firstName = $query['USR_FIRST_NAME'];
            $page = $query['USR_PAGE'];
            $create = $today;
            $delete = NULL;
            $isTech = $query['USR_TECH'];
            $isRef = $query['USR_REFERING'];
            $isDirection = $query['USR_DIRECTION'];
            $surname = $query['USR_SURNAME'];
            $teamviewer = $query['USR_TEAMVIEWER'];
            
            
            
        }
        
         $retour = 'ok';
    }
}



$update = $bdd->prepare('UPDATE YDA_USERS SET USR_MAIL = :mail, USR_PASSWORD = :password, USR_ID_PRO = :profil, USR_NAME = :name, USR_FIRST_NAME = :firstName, USR_PAGE = :page, USR_CREATE = :create, USR_DELETE = :delete, USR_TECH = :isTech, USR_REFERING = :isRef, USR_DIRECTION = :isDirection, USR_SURNAME = :surname, USR_TEAMVIEWER = :teamviewer WHERE USR_ID = :id');
    $update ->execute(array(
    	'id' => $id,
    	'mail' => $email,
    	'password' => $password,
    	'profil' => $profil,
    	'name' => $name,
    	'firstName' => $firstName,
    	'page' => $page,
    	'create' => $create,
        'delete' => $delete,
        'isTech' =>$isTech,
        'isRef' => $isRef,
        'isDirection' => $isDirection,
        'surname' => strtoupper($surname),
        'teamviewer' => $teamviewer
        
	));



echo $retour;


// echo '</pre>';