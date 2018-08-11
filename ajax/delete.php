<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST',[
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
    // echo '<pre>';
    // print_r($_REQUEST);
    $contenu_json = [];
    $contenu_json['ville']=strtolower($_REQUEST['ville']);
    $contenu_json['nom']=strtolower($_REQUEST['nom']);
    $contenu_json['url']=$_REQUEST['url'];
    
    if (substr($contenu_json['url'], -1) != '/' ){
        $contenu_json['url'] = $contenu_json['url'] . '/' ; 
    }

    
    $contenu_json['version']=$_REQUEST['version'];
    $contenu_json['tag'] = strtolower($_REQUEST['tag']);
    
    $id = $_REQUEST['id'];
    
    // print_r($_REQUEST);
    $update = $bdd->prepare('UPDATE YDA_CLIENT SET CLI_VILLE = :ville, CLI_NOM = :nom, CLI_URL = :url, CLI_VERSION = :version, CLI_TAG = :tag, CLI_VALID = :valide, CLI_RIS = :ris, CLI_PACS = :pacs, CLI_VIEW = :view, CLI_UVIEW = :uview, CLI_NUM_VERSION = :imaging WHERE CLI_ID = :id');
    $update ->execute(array(
	'ville' => $contenu_json['ville'],
	'nom' => $contenu_json['nom'],
	'url' => $contenu_json['url'],
	'version' => $contenu_json['version'],
	'tag' => $contenu_json['tag'],
	'valide' => '0',
	'id' => $id ,
    'ris' => $_REQUEST['ris'],
    'pacs' => $_REQUEST['pacs'],
    'view' => $_REQUEST['viewVersion'],
    'uview' => $_REQUEST['uviewVersion'],
    'imaging' => $_REQUEST['imagingVersion']
	));
	
    $nbPhone = $_REQUEST['nbPhone'];
    for ($i = 0; $i < $nbPhone; $i++){
        $phone=str_replace('.', '', $_REQUEST[phone][$i]);
        $phone=str_replace('-', '', $phone);
        $phone=str_replace(' ', '', $phone);
       
            if(!empty($_REQUEST[listIdPhone][$i]) && $_REQUEST[listIdPhone][$i] != '' ){
                
                $req = $bdd->prepare('UPDATE YDA_PHONE SET PHO_ID_CLI = :id, PHO_SITE = :site, PHO_PHONE = :phone, PHO_VALID = :valid, PHO_MAIL = :mail, PHO_TX = :TX, PHO_TV_ID = :idTV , PHO_TV_PASSWORD = :passwordTV  WHERE PHO_ID ="' . $_REQUEST[listIdPhone][$i] .'"' );
          
                $req->execute(array(
            	'id' => $id,
            	'site' => $_REQUEST[site][$i],
            	'phone' => $phone,
                'valid' => '0',
                'mail' => $_REQUEST[email][$i],
                'TX' => $_REQUEST[TX][$i],
                'idTV' => $_REQUEST[idTV][$i],
                'passwordTV' => $_REQUEST[passwordTV][$i])) or die(print_r($bdd->errorInfo()));

                $req2 = $bdd->prepare('UPDATE YDA_MAPS SET MPS_LAT = :lat, MPS_LON = :lon, MPS_ID_PHO = :id_pho, MPS_ID_CLI = :id_cli WHERE MPS_ID_PHO ="' . $_REQUEST[listIdPhone][$i] .'"' );

                $req2->execute(array(
            	'lat' => $_REQUEST[lat][$i],
            	'lon' => $_REQUEST[lon][$i],
            	'id_pho' => $_REQUEST[listIdPhone][$i],
                'id_cli' => $id)) or die(print_r($bdd->errorInfo()));
               
            }else{
                
                $req = $bdd->prepare('INSERT INTO YDA_PHONE(PHO_ID_CLI, PHO_SITE, PHO_PHONE, PHO_VALID,PHO_MAIL, PHO_TX, PHO_TV_ID, PHO_TV_PASSWORD) VALUES (:id, :site, :phone, :valid, :mail, :tx, :idTV, :passwordTV)');
                
                $req->execute(array(
            	'id' => $id,
            	'site' => $_REQUEST[site][$i],
            	'phone' => $phone,
                'valid' => '0',
                'mail' => $_REQUEST[email][$i],
                'TX' => $_REQUEST[TX][$i],
                'idTV' => $_REQUEST[idTV][$i],
                'passwordTV' => $_REQUEST[passwordTV][$i])) or die(print_r($bdd->errorInfo()));
                
                $req2 = $bdd->prepare('INSERT INTO YDA_MAPS(MPS_ID_PHO,MPS_ID_CLI,MPS_LAT,MPS_LON) VALUES (:id_pho, :id_cli, :lat, :lon)');
                
                $req2->execute(array(
            	'id_pho' => $_REQUEST[listIdPhone][$i],
            	'id_cli' => $id,
            	'lat' => $_REQUEST[lat][$i],
                'lon' => $_REQUEST[lon][$i]	)) or die(print_r($bdd->errorInfo()));
            }
    }


echo 'ok';

// echo '</pre>';