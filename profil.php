<?php
// server should keep session data for AT LEAST 1 hour
// ini_set('session.gc_maxlifetime', 0);
// session_set_cookie_params(0);

// each client should remember their session id for EXACTLY 1 hour
// session_set_cookie_params(14400);
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
checkCookie('profil.php');

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
	        $surname = $query['USR_SURNAME'];
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
		<title>Profil Utilisateur</title>
		<!--<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
		<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
					<a class="nav-item nav-link active" id="nav-profil-tab" data-toggle="tab" href="#nav-profil" role="tab" aria-controls="nav-profil" aria-selected="true" style="color:black;">Mon Profil</a>
					<?php if(in_array("rgt_cod_configuration", $right)):?>
					<a class="nav-item nav-link" id="nav-config-tab" data-toggle="tab" href="#nav-config" role="tab" aria-controls="nav-config" aria-selected="false" style="color:black;">Configuration</a>
					<?php endif; ?>
				</div>
			</nav>
			<div class="tab-content" id="nav-tabContent" style="background-color:#f7f7f7">
				<div class="tab-pane fade show active col-12" id="nav-profil" role="tabpanel" aria-labelledby="nav-profil-tab" >
				    <div style="display:flex;flex-flow: row wrap;">
					<div class="col-12">
						<p>
						<h4>Salutations, <span id="nameDisplay"><?=$name?></span> <span id="lastNameDisplay"><?=$lastName?></span>. </h4>
						<br>Que la force soit avec toi.</p>
					</div>
					<div class="col-md-4 form-group">
						<input class="form-control" id="updateName" type="text" placeholder="Prénom" value="<?=$name?>">
					</div>
					<div class="col-md-4 form-group">
						<input class="form-control" id="updateLastName" type="text" placeholder="Nom" value="<?=$lastName?>">
					</div>
					<div class="col-md-4 form-group">
						<input class="form-control text-uppercase" id="updateSurname" type="text" placeholder="Trigramme" disabled value="<?=$surname?>">
					</div>
					<div class="col-12 form-group">
						<input class="form-control" id="updateEmail" type="email" placeholder="Adresse eMail" value="<?=$mail?>">
					</div>
					<div class="col-12 form-group" id="selectPageUser">
						<select class="custom-select" id="updatePage">
							<option value="0"<?=$page == '0' ? 'selected' : '';?>>Page par défaut...</option>
							<option value="Dashboard"<?=$page == 'index.php' ? 'selected' : '';?>>Dashboard</option>
							<option value="Clients"<?=$page == 'yoda.php' ? 'selected' : '';?>>Clients</option>
							<option value="Clients_Vers"<?=$page == 'yoda.php?filter=ok' ? 'selected' : '';?>>Clients - Filtre Version</option>
							<option value="Clients_Acti"<?=$page == 'yoda.php?filter=activity' ? 'selected' : '';?>>Clients - Filtre Activité</option>
							<option value="Carte"<?=$page == 'maps.php' ? 'selected' : '';?>>Carte</option>
							<option value="Interne"<?=$page == 'interne.php' ? 'selected' : '';?>>Liens Interne</option>
						</select>
					</div>
					
					<div class="col-12 text-center">
						<p>Modifier le mot de passe ? </p>
					</div>
					<div class="col-md-6 col-12 form-group">
						<input class="form-control" id="updatePassword" type="password" placeholder="Mot de Passe">
					</div>
					<div class="col-md-6 col-12 form-group">
						<input class="form-control" id="updateConfirmPassword" type="password" placeholder="Confirmer Mot de Passe">
					</div>
					<div id='alertModif' class="alert alert-danger col-12 text-center" style="margin-top:15px;"></div>
					<div id='confirmModif' class="alert alert-success col-12 text-center" style="margin-top:15px;"></div>
					<div class="col-12" style="margin-bottom:15px;">
						<a href="mdp.php" target="_blank"><button type="button" class="btn btn-info">Mot de passe aléatoire</button></a>
						<button type="button" class="btn btn-warning" id="resetProfil">Réinitialiser</button>
						<button type="button" class="btn btn-success" id="updateProfil">Enregistrer</button>
					</div>
				</div>
				</div>
				<?php if(in_array("rgt_cod_configuration", $right)):?>
				<div class="tab-pane fade col-12" id="nav-config" role="tabpanel" aria-labelledby="nav-config-tab">
				    <div style="display:flex;flex-flow: row wrap;">
				    <div class="col-12">
						<p>
						<h4>Mes respects, administrateur <span id="administratorName"><?=$lastName?></span>. </h4>
						<br>Quelle est la cible aujourd'hui ?</p>
					</div>
					<div class="col-md-4 col-xs-12">
					    
    					<div class="col-12 form-group input-group dropdown">
    					    <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" id="newProfile"><i class="fa fa-plus-square-o"></i></button>
                            </div>
                                <input class="form-control rounded-right" id="configChooseProfil" type="text" placeholder="Profil..." data-toggle="dropdown">
                                
                            <div class="dropdown-menu dropdown-menu-right col-11 text-truncate" aria-labelledby="configChooseProfil" id='dropdownProfil'></div>
                            
        			    </div>
					    <hr>
    				    <div class="col-12 form-group input-group dropdown">
    				        <?php if(in_array("rgt_cod_add_user", $right)):?>
    				        <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" id="newUser"><i class="fa fa-user-plus"></i></button>
                            </div>
                            <?php endif;?>
    					    <input class="form-control" id="searchUser" type="text" placeholder="Utilisateurs...">
    					    <div class="input-group-append" id="divDropDown">
                                <button class="btn btn-outline-secondary rounded-right" type="button" id="dropdownMenuUser" data-toggle="dropdown" ><i class="fa fa-search"></i></button>
                                <div class="d-none" id="listUser"></div>
                                <div class="dropdown-menu dropdown-menu-right col-11 text-truncate" id="dropDownUser" aria-labelledby="clickTest">
                                </div>
                            </div>
    				    </div>
					</div>
                    <div class="card col-md-8 col-xs-12 d-none" style="padding:0;margin-bottom:15px;" id="cardUser">
                        <div class="card-header" style='background-color:#d8d8d8'>
                            <h4><span id='nameSelected'></span> <span id='lastNameSelected'></span> - <span id='profilSelected'></span></h4>
                            <div class="d-none" id="selectedUser"></div>
                        </div>
                        <div class="card-body col-12" style="display:flex;flex-flow: row wrap;">
                            <div class="col-md-4 col-xs-12" id='activeOrNot'>
                                Utilisateur actif : 
                                <?php if(in_array("rgt_cod_valid_user", $right)):?>
                                <div class="d-none" id="droitActifUser">OK</div>
                                <?php endif;?>
                                <button class="btn btn-outline-success" type="button" id='actifUser'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                <button class="btn btn-outline-danger d-none" type="button" id='desactifUser'><i class="fa fa-times" style="color:darkRed;"></i></button>
                            </div>
                            <div class="col-md-8 col-xs-12 dropdown" style='margin-bottom:15px;'>
                                <input class="form-control rounded-right" id="configUserProfil" type="text" placeholder="Profil..." data-toggle="dropdown">
                                <div class="dropdown-menu dropdown-menu-right col-11 text-truncate" aria-labelledby="configUserProfil" id='dropdownUserProfil'></div>
                            </div>
        					<div class="col-md-4 form-group">
        						<input class="form-control" id="updateAdminName" type="text" placeholder="Prénom">
        					</div>
        					<div class="col-md-4 form-group">
        						<input class="form-control" id="updateAdminLastName" type="text" placeholder="Nom" >
        					</div>
        					<div class="col-md-4 form-group">
        						<input class="form-control" id="updateAdminSurname" type="text" placeholder="Trigramme" >
        					</div>
        					<div class="col-12 form-group">
        						<input class="form-control" id="updateAdminEmail" type="email" placeholder="Adresse eMail" >
        					</div>
        					<div class="col-12 form-group">
        						<select class="custom-select" id="updateAdminPage">
        						    <option value="0" selected>Page par défault...</option>
        							<option value="Dashboard">Dashboard</option>
        							<option value="Clients">Clients</option>
        							<option value="Clients_Vers">Clients - Filtre Version</option>
        							<option value="Clients_Acti">Clients - Filtre Activité</option>
        							<option value="Carte">Carte</option>
        							<option value="Interne">Liens Interne</option>
        						</select>
        					</div>
        					<div class="col-12 row" id="isTechnician" style="margin-bottom:15px;">
					            <div class="col-md-6" id='planningAccess' style="margin-bottom:15px;">
                                Accès Planning Maintenance : 
                                
                                    <button class="btn btn-success  d-none" type="button" id='activePlanning'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                    <button class="btn btn-danger " type="button" id='desactivePlanning'><i class="fa fa-times" style="color:darkRed;"></i></button>
                                </div>
                                <div class="col-md-6" id='teamviewerAccess' style="margin-bottom:15px;">
                                Accès TeamViewer VM Support : 
                                
                                    <button class="btn btn-success  d-none" type="button" id='activeTV'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                    <button class="btn btn-danger " type="button" id='desactiveTV'><i class="fa fa-times" style="color:darkRed;"></i></button>
                                </div>
                                <div class="col-12 row" id="technicianPlanningQuestion">
                                    <div class="col-md-4 col-xs-12" id='technicianQuestion'>
                                    Technicien Support : 
                                    
                                        <button class="btn btn-success  d-none" disabled type="button" id='isTech'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                        <button class="btn btn-danger " disabled type="button" id='isNotTech'><i class="fa fa-times" style="color:darkRed;"></i></button>
                                    </div>
                                    <div class="col-md-4 col-xs-12" id='ReferringQuestion'>
                                    Référent Support : 
                                    
                                        <button class="btn btn-success  d-none" type="button" id='isRef'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                        <button class="btn btn-danger " type="button" id='isNotRef'><i class="fa fa-times" style="color:darkRed;"></i></button>
                                    </div>
                                    <div class="col-md-4 col-xs-12" id='DirectingQuestion'>
                                    Responsable : 
                                    
                                        <button class="btn btn-success  d-none" type="button" id='isDirection'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                        <button class="btn btn-danger " type="button" id='isNotDirection'><i class="fa fa-times" style="color:darkRed;"></i></button>
                                    </div>
                                </div>
					        </div>
        					<div class="col-12 text-center">
        						<p>Modifier le mot de passe ?</p>
        					</div>
        					<div class="col-md-6 col-12 form-group">
        						<input class="form-control" id="updateAdminPassword" type="password" placeholder="Mot de Passe">
        					</div>
        					<div class="col-md-6 col-12 form-group">
        						<input class="form-control" id="updateAdminConfirmPassword" type="password" placeholder="Confirmer Mot de Passe">
        					</div>
        					<div id='alertAdminModif' class="alert alert-danger col-12 text-center" style="margin-top:15px;"></div>
        					<div id='confirmAdminModif' class="alert alert-success col-12 text-center" style="margin-top:15px;"></div>
        					<div class="col-12" style="margin-bottom:15px;">
        						<a href="mdp.php" target="_blank"><button type="button" class="btn btn-info">Mot de passe aléatoire</button></a>
        						<button type="button" class="btn btn-warning" id="resetAdminProfil">Réinitialiser</button>
        						<button type="button" class="btn btn-success" id="updateAdminProfil">Enregistrer</button>
                            
                        </div>
                    </div>
				</div>
				    <div class="card col-md-8 col-xs-12 d-none" style="padding:0;margin-bottom:15px;" id="cardProfil">
                        <div class="card-header" style='background-color:#d8d8d8'>
                            <h4>Configuration du profil <span id='profilConfSelected'></span></h4>
                            <div class="d-none" id="selectedProfil"></div>
                        </div>
                        <div class="card-body col-12 mx-auto" style="display:flex;flex-flow: row wrap;">
                            <div class="col-md-4 col-xs-12" id='ProfilActiveOrNot'>
                                Profil actif : 
                                <button class="btn btn-outline-success active" type="button" id='actifProfil'><i class="fa fa-check" style="color:darkGreen;"></i></button>
                                <button class="btn btn-outline-danger active d-none" type="button" id='desactifProfil'><i class="fa fa-times" style="color:darkRed;"></i></button>
                            </div>
                            <div class="col-md-8 col-xs-12 dropdown" style='margin-bottom:15px;'>
                                <input class="form-control rounded-right" id="configNameProfil" type="text" placeholder="Libellé...">
                            </div>
        				    <div class="row col-12 mx-auto">
                            	<div class="col-md-6 mx-auto">
                            		<button type="button" id="selectAllRights" class="btn btn-primary" style="margin-bottom:15px;"><i class="fa fa-plus"></i> Tout ajouter</button>
                            		<!--<input type="text"  id="inputSearchRights" class="form-control" placeholder="Recherche des droits non attribués" style="margin-bottom:15px;">-->
                            		
                            		<ul class="list-group" id="unselectedRights" style="max-height:30vh; overflow:auto;margin-bottom:15px;">
                            			
                            			
                            			
                            		</ul>
                            	</div>
                            	<div class="col-md-6 mx-auto">
                            		<button type="button" id="unselectAllRights" class="btn btn-info" style="margin-bottom:15px;"><i class="fa fa-undo"></i> Tout retirer</button>
                            		
                            		<!--<input type="text" class="form-control" id="input-search-selected" placeholder="Recherche des protocoles déjà attribués">-->
                            		<br>
                            		<ul class="list-group" id="selectedRights" style="max-height:30vh; overflow:auto;margin-bottom:15px;">
                            			
                            			
                            		</ul>
                            	</div>
                            </div>
        					<div id='alertProfilModif' class="alert alert-danger col-12 text-center" style="margin-top:15px;"></div>
        					<div id='confirmProfilModif' class="alert alert-success col-12 text-center" style="margin-top:15px;"></div>
        					<div class="col-12" style="margin-bottom:15px;">
        						<button type="button" class="btn btn-warning" id="resetConfigProfil">Réinitialiser</button>
        						<button type="button" class="btn btn-success" id="updateConfigProfil">Enregistrer</button>
                            
                        </div>
                    </div>
				</div>
				</div>
			</div>
			<?php endif;?>
		</div>
		</div>
		<!-- Bootstrap core JavaScript -->
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/popper/popper.min.js"></script>
		<script src="js/login.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	</body>
</html>