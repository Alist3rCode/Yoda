<?php
// server should keep session data for AT LEAST 1 hour
// ini_set('session.gc_maxlifetime', 0);
// session_set_cookie_params(0);
// each client should remember their session id for EXACTLY 1 hour
ini_set('session.gc_maxlifetime', 14400);
session_start();
	
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
	}
	catch(Exception $e)
	{
	        die('Erreur : '.$e->getMessage());
	}
	
	error_reporting(E_ALL);
	set_time_limit(0);
	
require_once('checkCookie.php');
checkCookie('notif.php');


	$right=[];
	$select = $bdd->query('SELECT RGT_CODE 
	                        FROM YDA_RIGHT 
	                        JOIN YDA_HOOK on HOK_ID_RGT = RGT_ID 
	                        JOIN YDA_PROFIL ON HOK_ID_PRO = PRO_ID 
	                        JOIN YDA_USERS ON USR_ID_PRO = PRO_ID 
	                        WHERE USR_ID ="'.$_SESSION["id_user"].'"');

    while ($query = $select->fetch()){
    
        array_push($right, $query['RGT_CODE']);
       
    }
    
    $planningAccess = 0;
    
    $select = $bdd->query('SELECT USR_TECH, USR_DIRECTION 
	                        FROM YDA_USERS 
	                        WHERE USR_ID ="'.$_SESSION["id_user"].'"');

    while ($query = $select->fetch()){
    
        if($query['USR_TECH'] == 1 || $query['USR_DIRECTION'] == 1){
            $planningAccess = 1;
        }else{
            $planningAccess = 0;
        }
       
    }
    // var_dump($_SESSION['id_user']);
    // var_dump($right);
	    
	
	
	date_default_timezone_set('Europe/London');
	$selectVersion = $bdd->query('SELECT CFG_VERSION FROM YDA_CONFIG');
	    while ($version = $selectVersion->fetch()){
	        $yodaVersion = $version['CFG_VERSION'];
	    }
	    
	    
	// $ip = $_SERVER['REMOTE_ADDR'];
	// $page = $_SERVER['PHP_SELF'];
	// $browserArray = get_browser(NULL, true);
	// $browser = $browserArray['parent'];  
	// $now = date("Y-m-d H:i:s");
	            
	// $req = $bdd->prepare('INSERT INTO YDA_SPY(SPY_IP, SPY_TIME, SPY_PAGE, SPY_BROWSER) VALUES (:ip, :now, :page, :browser)');
	    
	//     $req->execute(array(
	// 	'ip' => $ip,
	// 	'now' => $now,
	// 	'page' => $page,
	// 	'browser' => $browser )) or die(print_r($bdd->errorCode()));           
	           
	$mail = '';
	$profil = '';
	$name = '';
	$lastName = '';
	$page = '';
	
	// var_dump($_SESSION['id_user']);
	if(isset($_SESSION['id_user']) && $_SESSION['id_user'] != ''){
	    $selectUser = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_ID="' . $_SESSION['id_user'] . '"');
	    while ($query = $selectUser->fetch()){
	        $mail = $query['USR_MAIL'];
	        $profil = $query['USR_PROFIL'];
	        $name = ucfirst($query['USR_FIRST_NAME']);
	        $lastName = ucfirst($query['USR_NAME']);
	        $page = $query['USR_PAGE'];
	    }
	}
	
	?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Bookmarks for all the ETMI Customer Application. For RIS and PACS. Internal user Only !">
		<meta name="author" content="Yohann LOPEZ">
		<title>Centre de Notifications</title>
		<!--<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
		<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<script src="js/tinymce/jquery.tinymce.min.js"></script>
		<script src="js/tinymce/tinymce.min.js"></script>
        <script>tinymce.init({
        selector: "#textarea",
        height:450,
        contextmenu: false,
        plugins: "textcolor link",
        font_formats: "Sans Serif = arial, helvetica, sans-serif;Serif = times new roman, serif;Fixed Width = monospace;Wide = arial black, sans-serif;Narrow = arial narrow, sans-serif;Comic Sans MS = comic sans ms, sans-serif;Garamond = garamond, serif;Georgia = georgia, serif;Tahoma = tahoma, sans-serif;Trebuchet MS = trebuchet ms, sans-serif;Verdana = verdana, sans-serif",
        toolbar: "fontselect | fontsizeselect | bold italic underline | forecolor | numlist bullist | alignleft aligncenter alignright alignjustify | outdent indent | link unlink | undo redo"
});
</script>
		<!-- CSS Yoda -->
		<link href="css/css_yoda.css" rel="stylesheet">
		<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link rel="icon" type="image/png" href="./img/yoda.png" />
	</head>
	<body background="css/Vignettes/Background.jpg" style="overflow-x:hidden;">
	    <div class="d-none" id='id_user'><?=$_SESSION['id_user']?></div>
		<!-- Navigation -->
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container col-10">
				<h3 style="col-10"><FONT color="#9ACD32" class="yoda">Y</font><FONT color="white" class="yohann">ohann</font> <FONT color="#9ACD32" class="yoda">O</font><FONT color="white" class="yohann">ptimized</font> <FONT color="#9ACD32" class="yoda">D</font><FONT color="white" class="yohann">irect link to</font> <FONT color="#9ACD32" class="yoda">A</font><FONT color="white" class="yohann">pplications</font><FONT color="#9ACD32" class="yoda"> v<?=$yodaVersion?></font></h3>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarResponsive">
					<ul class="navbar-nav ml-auto">
						<div class="dropdown col-12">
							<button class="btn btn-outline-light dropdown-toggle col-12" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Menu
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php $select = $bdd->query('SELECT * FROM YDA_MENU WHERE MEN_GROUP = 1');
                    // echo $_SERVER['PHP_SELF'];
                    while ($query = $select->fetch()){
                    
                        echo '<a class="dropdown-item ';
                        if($_SERVER['PHP_SELF'] == '/yoda/'.$query['MEN_PAGE']){echo 'active';}
                        echo'" href="'.$query['MEN_PAGE'].'">'.$query['MEN_NAME'].'</a>';
                       
                    } ?>           
                    <div class="dropdown-divider"></div>
                    <?php $select = $bdd->query('SELECT * FROM YDA_MENU WHERE MEN_GROUP = 2');

                    while ($query = $select->fetch()){
                    
                        echo '<a class="dropdown-item ';
                        if($_SERVER['PHP_SELF'] == '/yoda/'.$query['MEN_PAGE']){echo 'active';}
                        echo'" href="'.$query['MEN_PAGE'].'">'.$query['MEN_NAME'].'</a>';
                       
                    } ?>   
                    <?php if($planningAccess == 1):?>
                    <a class="dropdown-item" href="planning.php">Planning Support</a>
                    <?php endif;?>
                    <a class="dropdown-item" href="ajax/logout.php" style="color:red;">Deconnexion</a>
                </div>
						</div>
					</ul>
				</div>
			</div>
		</nav>
		<!--style="display:flex; flex-flow:row wrap; justify-content: space-around;"-->
		<div class="card text-center mx-auto" style="margin-top:15px;width:75%;" >
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist" style="background-color:rgb(216, 216, 216);">
					<a class="nav-item nav-link active" id="nav-notif-tab" data-toggle="tab" href="#nav-notif" role="tab" aria-controls="nav-notif" aria-selected="true" style="color:black;">Mes notifications</a>
					<?php if(in_array("rgt_cod_notification", $right)):?>
					<a class="nav-item nav-link" id="nav-config-notif-tab" data-toggle="tab" href="#nav-config-notif" role="tab" aria-controls="nav-config-notif" aria-selected="false" style="color:black;">Configuration</a>
					<a class="nav-item nav-link" id="nav-notif-users-tab" data-toggle="tab" href="#nav-notif-users" role="tab" aria-controls="nav-notif-users" aria-selected="false" style="color:black;">Notifications Utilisateurs</a>
					<a class="nav-item nav-link" id="nav-notif-clients-tab" data-toggle="tab" href="#nav-notif-clients" role="tab" aria-controls="nav-notif-clients" aria-selected="false" style="color:black;">Notifications Clients</a>
					<?php endif; ?>
				</div>
			</nav>
			<div class="tab-content" id="nav-tabContent" style="background-color:#f7f7f7">
				<div class="tab-pane fade show active col-12" id="nav-notif" role="tabpanel" aria-labelledby="nav-notif-tab" >
				    <div class="col-md-12" style="border-bottom: 1px solid lightgray;">
						<p>
						<h4>Salutations, <span id="nameDisplay"><?=$name?></span> <span id="lastNameDisplay"><?=$lastName?></span>. </h4>
						<br>C'est ici que viennent se configurer les différentes notifications que YODA peut générer. Pensez à sauvegarder avant de partir.</p>
					</div>
					<?php 
            	    $arrayCustomer = [];
                        $select2 = $bdd->query('SELECT * FROM YDA_CLIENT WHERE CLI_VALID = 1 ORDER BY CLI_VILLE ASC ');
                        $arrayCustomer['client'] = [];
                        
                        while ($AllCustomer = $select2->fetch()){
                            
                            $arrayTemp['id']= $AllCustomer['CLI_ID'];
                            $arrayTemp['ville'] = $AllCustomer['CLI_VILLE'];
                            $arrayTemp['nom'] = $AllCustomer['CLI_NOM'];
                            array_push($arrayCustomer['client'], $arrayTemp);
                            
                        }
                    
                        $select3 = $bdd->query('SELECT * FROM YDA_NOTIF WHERE NTF_ID_USR="' . $_SESSION['id_user'] . '"');
                    
                        while ($SelectedCustomer = $select3->fetch()){
                        
                            $arrayCustomer['notif'] = explode(',',$SelectedCustomer['NTF_UPDATE']);
                            $arrayCustomer['create'] = $SelectedCustomer['NTF_CREATE'];
                            $arrayCustomer['modif'] = $SelectedCustomer['NTF_MODIF'];
                            $arrayCustomer['new_custo'] = $SelectedCustomer['NTF_NEW_CUSTO'];
                           
                        }
                        // print_r($arrayCustomer);
                        
            	    ?>
					<div class="row col-md-12" style="margin-top:50px;">
                        <div class="col-md-4 col-xs-12" >
                            <p>Recevoir une notification lors de la création de nouveaux clients dans YODA</p>
                            <button class="btn btn-success <?php if($arrayCustomer['create']==0){echo 'd-none';}?>  " type="button" id='activeCreate'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                            <button class="btn btn-danger <?php if($arrayCustomer['create']==1){echo 'd-none';}?> " type="button" id='desactiveCreate'><i class="fa fa-times" style="color:darkRed;"></i></button>
                        </div>
                        
                        <div class="col-md-4  col-xs-12">
                            <p>Recevoir une notification lors de la modification de clients dans YODA</p>
                            <button class="btn btn-success <?php if($arrayCustomer['modif']==0){echo 'd-none';}?>" type="button" id='activeModif'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                            <button class="btn btn-danger <?php if($arrayCustomer['modif']==1){echo 'd-none';}?> " type="button" id='desactiveModif'><i class="fa fa-times" style="color:darkRed;"></i></button>
                        </div>
                        
                        <div class="col-md-4  col-xs-12">
                            <p>Ajouter à la liste ci-dessous les nouveaux clients comme sélectionnés</p>
                            <button class="btn btn-success <?php if($arrayCustomer['new_custo']==0){echo 'd-none';}?>" type="button" id='activeNewCusto'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                            <button class="btn btn-danger <?php if($arrayCustomer['new_custo']==1){echo 'd-none';}?>" type="button" id='desactiveNewCusto'><i class="fa fa-times" style="color:darkRed;"></i></button>
                        </div>
                        
                    </div>
					
					<div class="row text-center mx-auto" style="margin-top:50px;border-top: 1px solid lightgray;">
					    <p class="mx-auto col-md-12" style="margin-top:15px;">Recevoir une notification lors de la création de mise à jour des clients sélectionnés ci-dessous</p>
                            	<div class="col-md-6 mx-auto">
                            	    
                            	    
                            		<button type="button" id="selectAllCustomer" class="btn btn-primary" style="margin-bottom:15px;"><i class="fa fa-plus"></i> Tout ajouter</button>
                                	<div class="input-group mb-3">	
                                		<div class="input-group-prepend">
                                            <button class="btn btn-outline-danger" id="searchUnselectCustomerButton"><i class="fa fa-times"></i></button>
                                        </div>
                                		<input type="text" id="searchUnselectCustomer" class="form-control" placeholder="Rechercher...">
                                    </div>
                                    
                                    
                            		<br>
                            		<ul class="list-group" id="unselectedCustomer" style="max-height:50vh; overflow:auto;margin-bottom:15px;">
                        			<?php
                        			for ($y = 0; $y < count($arrayCustomer['client']); $y++) {
                        			 //   var_dump (in_array($arrayCustomer['client'][$y]['id'], $arrayCustomer['notif']));
                        				if (!in_array($arrayCustomer['client'][$y]['id'], $arrayCustomer['notif'])) {
                        					echo '<li class=" text-capitalize list-group-item list-group-item-danger selected" data-id=' . $arrayCustomer['client'][$y]['id'] . ' id="selectItem-' . $arrayCustomer['client'][$y]['id'] . '"  onclick="unselectCustomer(' . $arrayCustomer['client'][$y]['id'] . ')">' . $arrayCustomer['client'][$y]['ville'] . ' - ' . $arrayCustomer['client'][$y]['nom'] .'</li>'."\n";
                        					}
                        			}
                        			?>
                        			</ul>
                        			<div class="alert alert-warning d-none" role="alert" id='alertUnselectedCustomer'>
                        			    <p>Attention, une recherche est en cours, tous les clients présent dans la base peuvent ne pas être affichés.</p>
                        			</div>
                            	</div>
                            	<div class="col-md-6 mx-auto">
                            		<button type="button" id="unselectAllCustomer" class="btn btn-info col-md-6" style="margin-bottom:15px;"><i class="fa fa-undo"></i> Tout retirer</button>
                                    <div class="input-group mb-3">	
                                		<div class="input-group-prepend">
                                            <button class="btn btn-outline-danger" id="searchSelectCustomerButton"><i class="fa fa-times"></i></button>
                                        </div>
                                        <input type="text" id="searchSelectCustomer" class="form-control" placeholder="Rechercher...">
                                    </div>
                            		
                            		<br>
                            		<ul class="list-group" id="selectedCustomer" style="max-height:50vh; overflow:auto;margin-bottom:15px;">
                            			
                            			<?php
                        			
                            			for ($y = 0; $y < count($arrayCustomer['client']); $y++) {
                            				if (in_array($arrayCustomer['client'][$y]['id'], $arrayCustomer['notif'])) {
                            				echo '<li class=" text-capitalize list-group-item list-group-item-success unselected" data-id=' . $arrayCustomer['client'][$y]['id'] . ' id="unselectItem-' . $arrayCustomer['client'][$y]['id'] . '"  onclick="selectCustomer(' . $arrayCustomer['client'][$y]['id'] . ')">' . $arrayCustomer['client'][$y]['ville'] . ' - ' . $arrayCustomer['client'][$y]['nom'] .'</li>'."\n";
                            				
                            				}
                            			}
                            			
                            			?>
                            		</ul>
                            		<div class="alert alert-warning d-none" role="alert" id='alertSelectedCustomer'>
                            		    <p>Attention, une recherche est en cours, tous les clients présent dans la base peuvent ne pas être affichés.</p>
                            		</div>
                            	</div>                            
                        </div>
					<div id='alertNotif' class="alert alert-danger col-12 text-center" style="margin-top:15px;"></div>
					<div id='confirmNotif' class="alert alert-success col-12 text-center" style="margin-top:15px;"></div>
					<div class="col-12" style="margin-bottom:15px;">
						
						<button type="button" class="btn btn-warning btn-lg" id="resetNotif">Réinitialiser</button>
						<button type="button" class="btn btn-success btn-lg" id="updateNotif">Enregistrer</button>
					</div>
				</div>
				<?php if(in_array("rgt_cod_notification", $right)):?>
				<div class="tab-pane fade col-12" id="nav-config-notif" role="tabpanel" aria-labelledby="nav-config-notif-tab">
				    <p style="margin-top:15px;">Choisir un utilisateur à configurer : </p>
				    <div class="col-8 form-group input-group dropdown mx-auto">
					    <input class="form-control" id="searchUser" type="text" placeholder="Utilisateurs...">
					    <div class="input-group-append" id="divDropDown">
                            <button class="btn btn-outline-secondary rounded-right" type="button" id="dropdownMenuUser" data-toggle="dropdown" ><i class="fa fa-search"></i></button>
                            <div class="d-none" id="listUser"></div>
                            <div class="dropdown-menu dropdown-menu-right col-11 text-truncate" id="dropDownUser" aria-labelledby="clickTest">
                            </div>
                        </div>
				    </div>
				    <div id='selectedUser' class="d-none"></div>
				    <div id='configNotifUserHidden' class="d-none"> 
				    <?php 
                	    
                            
                	    ?>
    					<div class="row col-md-12" style="margin-top:50px;">
                            <div class="col-md-4 col-xs-12" >
                                <p>L'utilisateur doit-il recevoir une notification lors de la création de nouveaux clients dans YODA</p>
                                <button class="btn btn-success" type="button" id='activeCreateConfig'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                <button class="btn btn-danger" type="button" id='desactiveCreateConfig'><i class="fa fa-times" style="color:darkRed;"></i></button>
                            </div>
                            
                            <div class="col-md-4  col-xs-12">
                                <p>L'utilisateur doit-il recevoir une notification lors de la modification de clients dans YODA</p>
                                <button class="btn btn-success" type="button" id='activeModifConfig'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                <button class="btn btn-danger" type="button" id='desactiveModifConfig'><i class="fa fa-times" style="color:darkRed;"></i></button>
                            </div>
                            
                            <div class="col-md-4  col-xs-12">
                                <p>Ajouter à la liste ci-dessous les nouveaux clients comme sélectionnés pour l'utilisateur en cours</p>
                                <button class="btn btn-success" type="button" id='activeNewCustoConfig'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                <button class="btn btn-danger" type="button" id='desactiveNewCustoConfig'><i class="fa fa-times" style="color:darkRed;"></i></button>
                            </div>
                            
                        </div>
    					
    					<div class="row text-center mx-auto" style="margin-top:50px;border-top: 1px solid lightgray;">
    					    <p class="mx-auto col-md-12" style="margin-top:15px;">L'utilisateur doit-il recevoir une notification lors de la création de mise à jour des clients sélectionnés ci-dessous</p>
                                	<div class="col-md-6 mx-auto">
                                	    
                                	    
                                		<button type="button" id="selectAllCustomerConfig" class="btn btn-primary" style="margin-bottom:15px;"><i class="fa fa-plus"></i> Tout ajouter</button>
                                    	<div class="input-group mb-3">	
                                    		<div class="input-group-prepend">
                                                <button class="btn btn-outline-danger" id="searchUnselectCustomerButtonConfig"><i class="fa fa-times"></i></button>
                                            </div>
                                    		<input type="text" id="searchUnselectCustomerConfig" class="form-control" placeholder="Rechercher...">
                                        </div>
                                        
                                        
                                		<br>
                                		<ul class="list-group" id="unselectedCustomerConfig" style="max-height:50vh; overflow:auto;margin-bottom:15px;">
                            			
                            			</ul>
                            			<div class="alert alert-warning d-none" role="alert" id='alertUnselectedCustomerConfig'>
                            			    <p>Attention, une recherche est en cours, tous les clients présent dans la base peuvent ne pas être affichés.</p>
                            			</div>
                                	</div>
                                	<div class="col-md-6 mx-auto">
                                		<button type="button" id="unselectAllCustomerConfig" class="btn btn-info col-md-6" style="margin-bottom:15px;"><i class="fa fa-undo"></i> Tout retirer</button>
                                        <div class="input-group mb-3">	
                                    		<div class="input-group-prepend">
                                                <button class="btn btn-outline-danger" id="searchSelectCustomerButtonConfig"><i class="fa fa-times"></i></button>
                                            </div>
                                            <input type="text" id="searchSelectCustomerConfig" class="form-control" placeholder="Rechercher...">
                                        </div>
                                		
                                		<br>
                                		<ul class="list-group" id="selectedCustomerConfig" style="max-height:50vh; overflow:auto;margin-bottom:15px;">
                                			
                                		
                                		</ul>
                                		<div class="alert alert-warning d-none" role="alert" id='alertSelectedCustomerConfig'>
                                		    <p>Attention, une recherche est en cours, tous les clients présent dans la base peuvent ne pas être affichés.</p>
                                		</div>
                                	</div>                            
                            </div>
    					<div id='alertNotifConfig' class="alert alert-danger col-12 text-center" style="margin-top:15px;"></div>
    					<div id='confirmNotifConfig' class="alert alert-success col-12 text-center" style="margin-top:15px;"></div>
    					<div class="col-12" style="margin-bottom:15px;">
    						
    						<button type="button" class="btn btn-warning btn-lg" id="resetNotifConfig">Réinitialiser</button>
    						<button type="button" class="btn btn-success btn-lg" id="updateNotifConfig">Enregistrer</button>
    					</div>
    				        
    				</div>
				</div>
				<div class="tab-pane fade col-12" id="nav-notif-users" role="tabpanel" aria-labelledby="nav-notif-users" >
			        <div class=" row col-md-12" style="margin-bottom:15px;margin-top:15px;">
                        <div class="col-md-4">
                            <?php 
                        	    $arrayUser = [];
                                    $selectUser = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_DELETE IS NULL ORDER BY USR_FIRST_NAME ASC');
                                    $arrayUser['user'] = [];
                                    
                                    while ($AllUser = $selectUser->fetch()){
                                        
                                        $arrayTempUser['id']= $AllUser['USR_ID'];
                                        $arrayTempUser['mail'] = $AllUser['USR_MAIL'];
                                        $arrayTempUser['profil'] = $AllUser['USR_ID_PRO'];
                                        $arrayTempUser['prenom'] = $AllUser['USR_FIRST_NAME'];
                                        $arrayTempUser['nom'] = $AllUser['USR_NAME'];
                                        
                                        array_push($arrayUser['user'], $arrayTempUser);
                                        
                                    }
                                
                                    $selectProfil = $bdd->query('SELECT * FROM YDA_PROFIL WHERE PRO_VALID = 1');
                                $arrayUser['profil'] = [];
                                    while ($profil = $selectProfil->fetch()){
                                    
                                        $arrayTempProf['id'] = $profil['PRO_ID'];
                                        $arrayTempProf['name'] = $profil['PRO_NAME'];
                                        
                                        array_push($arrayUser['profil'], $arrayTempProf);
                                    }
                                    // print_r($arrayCustomer);
                                    // var_dump($arrayUser);
                        	    ?>
                        	    
                        	    <div class="row text-center mx-auto" style="margin-top:10px;">
					                <p class="mx-auto col-md-12" style="margin-top:15px;">Sélectionnez les utilisateurs ou profil que vous souhaitez contacter</p>
                                	<div class="col-md-6 mx-auto" style="padding: 0 5px;">
                                		<button type="button" id="selectAllUser" class="btn btn-primary col-md-8" style="margin-bottom:15px;"><i class="fa fa-plus"></i> Tous</button>
                                		<div class="col-md-12"><span id='selectedProfilNumber' >0</span><span>/<?=count($arrayUser['profil'])?> profil(s)<br>sélectionnés</span></div>
                                		<br>
                                		<ul class="list-group" id="selectProfilList" style="max-height:50vh; overflow:auto;margin-bottom:15px;">
                            			<?php
                            			    for ($y = 0; $y < count($arrayUser['profil']); $y++) {
                            			    //   var_dump (in_array($arrayCustomer['client'][$y]['id'], $arrayCustomer['notif']));
                            					echo '<li class=" text-capitalize list-group-item list-group-item-light profilNotif" id="profil-' . $arrayUser['profil'][$y]['id'] . '"  onclick="selectProfil(' . $arrayUser['profil'][$y]['id'] . ')">' . $arrayUser['profil'][$y]['name'] .'</li>'."\n";
                                			}
                            			?>
                            			</ul>
                                	</div>
                                	<div class="col-md-6 mx-auto" style="padding: 0 5px;"> 
                                		<button type="button" id="unselectAllUser" class="btn btn-warning col-md-8" style="margin-bottom:15px;"><i class="fa fa-undo"></i> Aucun</button>
                                        <div class="col-md-12"><span id='selectedUserNumber' >0</span><span>/<?=count($arrayUser['user'])?> utilisateur(s) sélectionnés</span></div>
                                		
                                		<br>
                                		<ul class="list-group" id="selectUserList" style="max-height:50vh; overflow:auto;margin-bottom:15px;">
                                			
                                			<?php
                            			
                                    			for ($y = 0; $y < count($arrayUser['user']); $y++) {
                                    				
                                    				echo '<li class=" text-capitalize list-group-item list-group-item-light userNotif" data-user-id=' . $arrayUser['user'][$y]['id'] . ' data-profil-id=' . $arrayUser['user'][$y]['profil'] . ' id="user-' . $arrayUser['user'][$y]['id'] . '"  onclick="selectUser(' . $arrayUser['user'][$y]['id'] . ')">' . ucfirst($arrayUser['user'][$y]['prenom']) .' ' . ucfirst($arrayUser['user'][$y]['nom']) . '</li>'."\n";
                                    			}
                                			?>
                                		</ul>
                                	</div>                            
                            </div>
                        </div>
    			        <div class="col-md-8 " style="margin-top:10px;">
    			            <p class="mx-auto col-md-12" style="margin-top:15px;">Renseignez un objet au mail, ainsi que le corps du message dans la zone de saisie.</p>
    			             
    			            <input type="text" class="form-control col-md-8 mx-auto" id='objet' placeholder="Objet..." style="margin:15px">
                            <textarea class="form-control" id="textarea" name="textarea"></textarea>
                            <div class="alert alert-danger" style="margin-top:15px" id="alertNotifUser"></div>
                            <div class="alert alert-success" style="margin-top:15px" id="successNotifUser"></div>
                            <button class="btn btn-primary form-control" style="margin-top:15px;" id="sendMailButton">Envoyer</button>
                            <!--<div class="alert alert-danger"></div>-->
                        </div>  
                    </div>   
				</div>
				<div class="tab-pane fade col-12" id="nav-notif-clients" role="tabpanel" aria-labelledby="nav-notif-clients">
				    <div class="alert alert-primary" role="alert">
                        En travaux nav-notif-clients                
                    </div>
				</div>
				<?php endif;?>
			</div>
		</div>
		<!-- Bootstrap core JavaScript -->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/popper/popper.min.js"></script>
		<script src="js/notif.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	</body>
</html>