<?php
// var_dump(session_get_cookie_params ());
// server should keep session data for AT LEAST 1 hour//ini_set('session.gc_maxlifetime', 14400);

ini_set('session.gc_maxlifetime', 14400);
// session_set_cookie_params(0);
  session_start();


 // var_dump(session_get_cookie_params ());
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
    // var_dump($_SESSION);        

?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bookmarks for all the ETMI Customer Application. For RIS and PACS. Internal user Only !">
    <meta name="author" content="Yohann LOPEZ">
    <title>Login</title>
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
    <!-- Navigation -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container col-10">
        
		<h3 style="col-10"><FONT color="#9ACD32" class="yoda">Y</font><FONT color="white" class="yohann">ohann</font> <FONT color="#9ACD32" class="yoda">O</font><FONT color="white" class="yohann">ptimized</font> <FONT color="#9ACD32" class="yoda">D</font><FONT color="white" class="yohann">irect link to</font> <FONT color="#9ACD32" class="yoda">A</font><FONT color="white" class="yohann">pplications</font><FONT color="#9ACD32" class="yoda"> v<?=$yodaVersion?></font></h3>
        
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

      </div>
    </nav> 
<!--style="display:flex; flex-flow:row wrap; justify-content: space-around;"-->
    <div class="card mx-auto login">
      <div class="card-header" style="background-color:rgb(216, 216, 216);">Vous enregistrer vous devez...</div>
      <div class="card-body" style="background-color:#f7f7f7">
        <form autocomplete="off">
            
            
          <div class="form-group">
            <label for="inputEmail">Adresse Mail</label>
            <input class="form-control" id="inputEmail" type="email" placeholder="Adresse eMail" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="inputPassword">Mot de Passe</label>
            <input class="form-control" id="inputPassword" type="password" placeholder="Mot de Passe" autocomplete="nope"> 
          </div>
          <!--<div class="form-group">-->
          <!--  <div class="form-check">-->
          <!--    <label class="form-check-label">-->
          <!--      <input class="form-check-input" type="checkbox">Se rappeler de moi</label>-->
          <!--  </div>-->
          <!--</div>-->
          <a class="btn btn-success btn-block" id="login">Connexion</a>
        </form>
        <div class="text-center">
            <button type="button"  class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal" style="margin-top:10px;">Rejoindre YODA</button>
            
       </div>
       <div class="text-center">
            
            <button type="button"  class="btn btn-warning btn-sm" data-toggle="modal" data-target="#forgetModal" style="margin-top:10px;">Mot de Passe oublié</button>
       </div>
       <div id='alertSlideUp' class="alert alert-danger text-center" style="margin-top:15px;"></div>
       
      </div>
    </div>



<!-- Modal Create-->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:rgb(216, 216, 216);">
        <h5 class="modal-title" id="exampleModalLabel">Création d'un compte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body col-12 " style="display:flex; flex-flow:row wrap;background-color:#f7f7f7">
          <p>Tout compte créé par cette interface disposera des droits minimum sur l'application. Il sera nécessaire de contacter l'administrateur afin d'acquerir les droits supplémentaires lié à votre fonction.</p>
        <div class="col-md-6 form-group">
            
            <input class="form-control" id="createName" type="text" placeholder="Prénom">
        </div>
        
        <div class="col-md-6 form-group">
            
            <input class="form-control" id="createLastName" type="text" placeholder="Nom">
        </div>
        
        <div class="col-12 form-group">
            
            <input class="form-control" id="createEmail" type="email" placeholder="Adresse eMail">
        </div>
        
        <div class="col-md-6 col-12 form-group">
            
            <input class="form-control" id="createPassword" type="password" placeholder="Mot de Passe">
        </div>
        
        <div class="col-md-6 col-12 form-group">
            
            <input class="form-control" id="createConfirmPassword" type="password" placeholder="Confirmer Mot de Passe">
        </div>
        
        <div class="col-12 form-group">
            
            <select class="custom-select" id="defaultPage">
                <option value="0" selected>Page par défaut...</option>
                <option value="Dashboard">Dashboard</option>
                <option value="Clients">Clients</option>
                <option value="Clients_Vers">Clients - Filtre Version</option>
                <option value="Clients_Acti">Clients - Filtre Activité</option>
                <option value="Carte">Carte</option>
                <option value="Interne">Liens Interne</option>
            </select>
        </div>
        <div id='alertCreate' class="alert alert-danger col-12 text-center" style="margin-top:15px;"></div>
        <div id='confirmCreate' class="alert alert-success col-12 text-center" style="margin-top:15px;"></div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-success" id="createAccount">Créer le compte</button>
        
      </div>
      
      </div>
    </div>
  </div>
</div>
<!--Fin Modal Create-->
<!-- Forget Modal -->
<div class="modal fade" id="forgetModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:rgb(216, 216, 216);">
        <h5 class="modal-title" id="exampleModalLabel">Mot de passe oublié</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="background-color:#f7f7f7">
        <p>Veuillez renseigner votre adresse eMail dans le champ ci-dessous. Un mail automatique vous sera envoyé avec votre nouveau mot de passe.</p>

        <div class="col-12 form-group">
            
            <input class="form-control" id="forgetEmail" type="email" placeholder="Adresse eMail">
        </div>
        <div id='confirmReset' class="alert alert-success col-12 text-center" style="margin-top:15px;"></div>
        <div id='alertReset' class="alert alert-danger col-12 text-center" style="margin-top:15px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-success" id="resetPassword">Réinitialiser le mot de passe</button>
        
    </div>
  </div>
</div>
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="js/login.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    
</body>
      

</html>