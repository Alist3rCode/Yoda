<?php

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
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

    // print_r($_REQUEST);
    // var_dump($contenu_json['tag']);

    $req = $bdd->prepare('INSERT INTO YDA_CLIENT(CLI_VILLE, CLI_NOM, CLI_URL, CLI_VERSION, CLI_TAG, CLI_VALID, CLI_RIS, CLI_PACS, CLI_VIEW, CLI_UVIEW, CLI_NUM_VERSION) VALUES (:ville, :nom, :url, :version, :tag, :valid, :ris, :pacs, :view, :uview, :imaging)');
    
    $req->execute(array(
	'ville' => $contenu_json['ville'],
	'nom' => $contenu_json['nom'],
	'url' => $contenu_json['url'],
	'version' => $contenu_json['version'],
	'tag' => $contenu_json['tag'],
    'valid' => '1',
    'ris' => $_REQUEST['ris'],
    'pacs' => $_REQUEST['pacs'],
    'view' => $_REQUEST['viewVersion'],
    'uview' => $_REQUEST['uViewVersion'],
    'imaging' => $_REQUEST['imagingVersion']
    )) or die(print_r($bdd->errorCode()));
    
    // echo 'insert';
    
    $idInsert = '';
    $select4 = $bdd->query('SELECT CLI_ID FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VILLE ="'. $contenu_json['ville'] .'" AND CLI_NOM = "' . $contenu_json['nom'] . '"');
    
    while ($query = $select4->fetch()){
                $idInsert = $query['CLI_ID'];
            }
    $select4->closeCursor();
    
    $nbPhone = $_REQUEST['nbPhone'];
    
    
   
    
    for ($i = 0; $i < $nbPhone; $i++){
        
        $phone=str_replace('.', '', $_REQUEST[phone][$i]);
        $phone=str_replace('-', '', $phone);
        $phone=str_replace(' ', '', $phone);
        $req = $bdd->prepare('INSERT INTO YDA_PHONE(PHO_ID_CLI, PHO_SITE, PHO_PHONE, PHO_VALID, PHO_MAIL, PHO_TX, PHO_TV_ID, PHO_TV_PASSWORD) VALUES (:id, :site, :phone, :valid, :mail, :TX, :idTV, :passwordTV)');
    
        $req->execute(array(
    	'id' => $idInsert,
    	'site' => $_REQUEST[site][$i],
    	'phone' => $phone,
        'valid' => '1',
        'mail' => $_REQUEST[email][$i],
        'TX' => $_REQUEST[TX][$i],
        'idTV' => $_REQUEST[idTV][$i],
        'passwordTV' => $_REQUEST[passwordTV][$i] )) or die(print_r($bdd->errorInfo()));
        
        $idPhoneInsert = '';
        
        $select4 = $bdd->query('SELECT PHO_ID FROM YDA_PHONE WHERE PHO_VALID = 1 AND PHO_PHONE ="'. $phone .'" AND PHO_SITE = "' . $_REQUEST[site][$i] . '"');
    
        while ($query = $select4->fetch()){
                    $idPhoneInsert = $query['PHO_ID'];
                }
        $select4->closeCursor();
        
        $req2 = $bdd->prepare('INSERT INTO YDA_MAPS(MPS_ID_PHO,MPS_ID_CLI,MPS_LAT,MPS_LON) VALUES (:id_pho, :id_cli, :lat, :lon)');
                
            $req2->execute(array(
        	'id_pho' => $idPhoneInsert,
        	'id_cli' => $idInsert,
        	'lat' => $_REQUEST[lat][$i],
            'lon' => $_REQUEST[lon][$i]	)) or die(print_r($bdd->errorInfo()));
    }
    
    $selectNewCusto = $bdd->query('SELECT * FROM YDA_NOTIF WHERE NTF_CREATE = 1');
    
        while ($query = $selectNewCusto->fetch()){
                    
                    $update = $bdd->prepare('UPDATE YDA_NOTIF SET NTF_UPDATE = :update WHERE NTF_ID_USR = :id');
                    $update ->execute(array(
                    	'id' => $query['NTF_ID_USR'],
                    	'update' => $query['NTF_UPDATE'].','.$idInsert
                	));
                }
        $select4->closeCursor();
    
    
    $array['result'] = 'ok';
    $array['id'] = $idInsert;

echo json_encode($array);

// echo '</pre>';