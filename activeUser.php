<?php
ini_set('session.gc_maxlifetime', 0);
session_set_cookie_params(0);
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

	
	if(isset($_GET['idUser']) && $_GET['idUser'] != '' && isset($_GET['activeUser']) && $_GET['activeUser'] != ''){
	    
	
    	if(!isset($_SESSION['login']) || $_SESSION['login'] != 'ok'){
    	    
            header('Location: login.php?redirect=profil.php');
            
    	}else if(!isset($_SESSION['id_user']) || $_SESSION['id_user'] == ''){
    	   
    	   header('Location: login.php?redirect=profil.php');
    	    
    	}
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
    	
    	
    	$mailUser = '';
    	$nameUser = '';
    	$lastNameUser = '';
    	
	    $selectUser = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_ID="' . $_GET['idUser'] . '"');
	    while ($query = $selectUser->fetch()){
	        $mailUser = $query['USR_MAIL'];
	        $nameUser = ucfirst($query['USR_FIRST_NAME']);
	        $lastNameUser = strtoupper($query['USR_NAME']);
	    }
	    
    	 if($_GET['activeUser'] == 1){
    	        // destinataire utilisateur
             $toUser  = $mailUser;
        
             // Sujet
             $subjectUser = 'Activation de votre compte YODA';
        
             $messageUser = '
             <div>
            	<div style="margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;"><p>
            			Bonjour '.$nameUser.' '.$lastNameUser.', 
            		</p></span>
            		</font>
            	</div>
            	<div style="margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;"><p>Vous recevez cet eMail car votre compte sur la plateforme YODA a été activé par un administrateur.</p></span>
            		</font>
            	</div>
            	<div style="margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;"><p>
            			Vous pouvez à présent vous connecter en cliquant directement sur ce lien : 
            			<a href="https://maj-imaging.evolucare.com/yoda" target="_blank">https://maj-imaging.evolucare.com/yoda</a>
            		<p style="color:red;"><b>Ceci est un mail automatique, merci de ne pas y répondre directement.</b></p>
            		</p></span>
            		</font>
            	</div>
            	<div style="margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;"><font color="#595959">Cordialement, </font></span>
            		</font>
            	</div>
            	<div style="margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;">
            				<font color="#595959">
            					<b>Mail automatique </b>
            				</font>
            				<font color="#595959">
            					| YODA
            				</font>
            			</span>
            		</font>
            	</div>
            	<div style="margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;">
            				<font color="#595959">
            					<b>Groupe Evolucare Technologies</b>
            				</font>
            			</span>
            		</font>
            	</div>
            	<div style="margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;"><font color="#595959">290 avenue Galillée, Parc Cézanne 2, Bat G </font></span>
            		</font>
            	</div>
            	<div style="margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;"><font color="#595959">13857 Aix en Provence Cedex 03</font></span>
            		</font>
            	</div>
            	<div style="background-color:white;margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;background-color:white;">
            				<font color="#E36C0A">
            					<b>Hotline :&nbsp;01.84.86.06.00</b><br>
            					<b>Mail Support :&nbsp;<a href="mailto:support@imaging.evolucare.com">support@imaging.evolucare.com</a></b>
            				</font>
            				<font color="#1F497D">
            					&nbsp;
            				</font>
            			</span>
            		</font>
            	</div>
            	<div style="margin:0;">
            		<font face="Calibri,sans-serif" size="2">
            			<span style="font-size:11pt;"><img src="https://release-imaging.evolucare.com/upgrade/signatureYoda.png"></span>
            		</font>
            	</div>
            </div>
        
             ';
        
             // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
             $headersUser  = 'MIME-Version: 1.0' . "\r\n";
             $headersUser .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        
             // En-têtes additionnels
            //  $headersUser .= 'To: '.ucfirst($user["name"]).' '.strtoupper($user["lastName"]).' <'.$user["mail"].'> '. "\r\n";
             $headersUser .= 'From: YODA <noreply@mailevolucare.pictime.fr>' . "\r\n";
             
             // Envoi
              mail($toUser, $subjectUser, $messageUser, $headersUser);
             
             
            $update = $bdd->prepare('UPDATE YDA_USERS SET USR_CREATE = :create, USR_DELETE = :delete WHERE USR_ID = :id');
            $update ->execute(array(
                'id' => $_GET['idUser'],
            	'create' => date("Y-m-d H:i:s"),
                'delete' => $delete
        	));
    	        
    	        
    	        
        }else if ($_GET['activeUser'] == 0){
            $update = $bdd->prepare('UPDATE YDA_USERS SET USR_CREATE = :create, USR_DELETE = :delete WHERE USR_ID = :id');
            $update ->execute(array(
                'id' => $_GET['idUser'],
            	'create' => date("Y-m-d H:i:s"),
                'delete' => date("Y-m-d H:i:s")
        	));
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
		<title>Activation Utilisateur</title>
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
								<a class="dropdown-item" href="index.php">Dashboard</a>
								<a class="dropdown-item" href="yoda.php">Clients</a>
								<a class="dropdown-item" href="maps.php">Carte</a>
								<a class="dropdown-item" href="interne.php">Lien Interne</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item active" href="profil.php">Profil</a>
								<a class="dropdown-item" href="#">Notifications</a>
								<a class="dropdown-item" href="ajax/logout.php" style="color:red;">Deconnexion</a>
							</div>
						</div>
					</ul>
				</div>
			</div>
		</nav>
		<!--style="display:flex; flex-flow:row wrap; justify-content: space-around;"-->
		<div class="card mx-auto col-6" style="padding:0px; margin-top:15px;">
            <div class="card-header" >
            Activation de l'utilisateur <?=$nameUser?> <?=$lastNameUser?>
            </div>
            <div class="card-body mx-auto text-center">
                <?php if($_GET['activeUser'] == '1'): ?><p class="card-text alert alert-success">L'utilisateur a été correctement activé.</p> <?php endif;?>
                <?php if($_GET['activeUser'] == '0'): ?><p class="card-text alert alert-danger">L'utilisateur n'a pas été activé.</p> <?php endif;?>
                <a class="btn btn-primary" id='closeTab'>Fermer l'onglet</a>
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