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


date_default_timezone_set('Europe/London');
$selectVersion = $bdd->query('SELECT CFG_VERSION FROM YDA_CONFIG');
    while ($version = $selectVersion->fetch()){
        $yodaVersion = $version['CFG_VERSION'];
    }
    
    
    $ip = $_SERVER['REMOTE_ADDR'];
$page = $_SERVER['PHP_SELF'];
$browserArray = get_browser(NULL, true);
$browser = $browserArray['parent'];  
$now = date("Y-m-d H:i:s");
            
$req = $bdd->prepare('INSERT INTO YDA_SPY(SPY_IP, SPY_TIME, SPY_PAGE, SPY_BROWSER) VALUES (:ip, :now, :page, :browser)');
    
    $req->execute(array(
	'ip' => $ip,
	'now' => $now,
	'page' => $page,
	'browser' => $browser )) or die(print_r($bdd->errorCode()));    
	
require_once('checkCookie.php');
checkCookie('mdp.php');	



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

	
?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bookmarks for all the ETMI Customer Application. For RIS and PACS. Internal user Only !">
    <meta name="author" content="Yohann LOPEZ">
    <title>Password Generator</title>
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
                        if($_SERVER['PHP_SELF'] == $query['MEN_PAGE']){echo 'active';}
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
    <div class="text-center" >
        <div class="input-group col-lg-6 col-12" style="margin-left: auto;margin-right: auto;margin-top: 15px;">
  
            <input type="password" class ="form-control col-12" id="motDePasseNormal" placeholder="Mot de Passe" >
            <div class="input-group-append">
                <span class="input-group-text" id="eye"><i class="fa fa-eye-slash"></i></span>
                
            </div>
            
        </div>
        
        
        <div class="collapse col-lg-6 col-sm-12" id="collapseGenerator" style="margin-left: auto;margin-right: auto;margin-top: 15px;">
            <div class="card card-body" style="display:flex;flex-flow:row wrap;">
                <span class="col-12">Sélectionnez les options de votre mot de passe sécurisé : </span>
            
                <div class="btn-group-toggle col-lg-4 col-sm-12" data-toggle="buttons" style="margin-top:15px;" >
                    <label class="btn btn-outline-secondary" style="width:100%;" id="numbers">
                        <input type="checkbox" checked autocomplete="off"  >Chiffres
                    </label>
                </div>
                <div class="btn-group-toggle col-lg-4 col-sm-12" data-toggle="buttons" style="margin-top:15px;">
                    <label class="btn btn-outline-secondary " style="width:100%;" id='mini'>
                        <input type="checkbox" checked autocomplete="off" >Minuscules
                    </label>
                </div>
                <div class="btn-group-toggle col-lg-4 col-sm-12" data-toggle="buttons" style="margin-top:15px;">
                    <label class="btn btn-outline-secondary " style="width:100%;" id='maj'>
                        <input type="checkbox" checked autocomplete="off" >Majuscules
                    </label>
                </div>
                <div class="btn-group-toggle col-lg-6 col-sm-12" data-toggle="buttons" style="margin-top:15px;">
                    <label class="btn btn-outline-secondary " style="width:100%;" id='spec'>
                        <input type="checkbox" checked autocomplete="off" > Caractères Spéciaux
                    </label>
                </div>
                <div class="btn-group-toggle col-lg-6 col-sm-12" data-toggle="buttons" style="margin-top:15px;">
                    <label class="btn btn-outline-secondary active" style="width:100%;" id='simi'>
                        <input type="checkbox" checked autocomplete="off" > Caractères Similaires
                    </label>
                </div>
                
                <div class="input-group col-12" style="margin-top:15px;">
                    <div class="input-group-prepend ">
                        <label class="input-group-text" >Caractères : </label>
                    </div>
                    <input type="text" class="form-control" placeholder="4 - 255" id='nbCarac'>
                    
                    
                </div>
                <div class="alert alert-danger col-12" style="margin-left: auto;margin-right: auto;margin-top: 15px;" id="alertCreate"></div>
            </div>
            <button type="button" class="btn btn-success col-12" id="createPassword" style="margin-top:15px; margin-right:15px;">Créer</button>
        </div>
        
        <div class="col-12">
            <button type="button" class="btn btn-light col-sm-12 col-lg-2" id="createGeneratePassword" style="margin-top:15px; margin-right:15px;" data-toggle="collapse" data-target="#collapseGenerator" aria-expanded="false" aria-controls="collapseGenerator">Générer</button>
            <button type="button" class="btn btn-info col-sm-12 col-lg-2" id="validGeneratePassword" style="margin-top:15px; margin-right:15px;">Crypter</button>
            <button type="button" class="btn btn-primary col-sm-12 col-lg-2" id="copyGeneratePassword" style="margin-top:15px">Copier</button> 
        </div>
        
        
        
       
        <div class="col-lg-6 col-sm-12 card sha1-md5" id="losMotdePassos">
            <div class="card-body">
                <span class="font-weight-bold">SHA1 : </span><span id="sha1Pass"></span><br/>
                <span class="font-weight-bold">MD5 : </span><span id="md5Pass"></span>
            
            </div>
        </div>
            
       
        
        <div class="alert alert-success col-lg-6 col-sm-12 alertCopy" id="alertCopy"></div>
        
    </div>
    
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
    
        $("#alertCopy").hide();
        $("#alertCreate").hide();
        $("#losMotdePassos").hide();
       
        
        $('#validGeneratePassword').click(function(){
    
            var array = [] ;
            var motDePasse = document.getElementById('motDePasseNormal').value;
                $.post("ajax/generatePassword.php", 
                    {mdp: motDePasse}, 
                    function(ok){
                        ok = JSON.parse(ok);
                        
                        document.getElementById('sha1Pass').innerText = ok[0];
                        document.getElementById('md5Pass').innerText = ok[1];
                        
                });
            $("#losMotdePassos").slideDown(125);
        });
        
        
        $('#copyGeneratePassword').click(function(){
            // Create a new textarea element and give it id='t'
            let textarea = document.createElement('textarea')
            textarea.id = 't'
            // Optional step to make less noise on the page, if any!
            textarea.style.height = 0
            // Now append it to your page somewhere, I chose <body>
            document.body.appendChild(textarea)
            // Give our textarea a value of whatever inside the div of id=containerid
            textarea.value = document.getElementById("losMotdePassos").innerText
            // Now copy whatever inside the textarea to clipboard
            let selector = document.querySelector('#t')
            selector.select()
            document.execCommand('copy')
            // Remove the textarea
            document.body.removeChild(textarea)
            
            $("#alertCopy").html('Texte copié dans le presse papier');
            $("#alertCopy").fadeTo(1000, 500).slideUp(500, function(){
                $("#alertCopy").slideUp(500);
            });
        });
        
        
        $('#createGeneratePassword').click(function(){
            $("#losMotdePassos").fadeTo(0, 500).slideUp(125, function(){
                    $("#losMotdePassos").slideUp(125);
                });
        });
        
        
        
        $('#createPassword').click(function(){
    
            var array = [] ;
            
            var numbers = document.getElementById('numbers').classList.contains('active');
            // console.log(numbers);
            var mini = document.getElementById('mini').classList.contains('active');
            // console.log(mini);
            var maj = document.getElementById('maj').classList.contains('active');
            // console.log(maj);
            var spec = document.getElementById('spec').classList.contains('active');
            // console.log(spec);
            var simi = document.getElementById('simi').classList.contains('active');
            // console.log(simi);
            var nbCarac = document.getElementById('nbCarac').value;
            
            var flag = 0;
            var errors = [];
            var regex = /^([4-9]|[1-9][0-9]|[1-2][0-5][0-9])$/;
            
            
            var match = regex.test(nbCarac);
            
            
            if(!match){
                errors.push('Merci de choisir un nombre de caratère valide entre 4 et 255');
                flag = 1;
            }
            if(numbers == false && mini == false && maj == false && spec == false){
                errors.push('Merci de choisir un type de caratère contenu dans le mot de passe');
                flag = 1;
            }
            
           if (flag == 1) {
                
                flag = '';
                $("#alertCreate").html(errors.join("<br>"));
                $("#alertCreate").fadeTo(3000, 500).slideUp(500, function(){
                    $("#alertCreate").slideUp(500);
                });
                
            } else {
                    
                $.post("ajax/createPassword.php", 
                    {numbers: numbers, mini: mini, maj: maj, spec: spec, simi: simi, nbCarac: nbCarac}, 
                    function(ok){
                        document.getElementById('motDePasseNormal').value = ok;
                        
                });
            }
        });
        
        $('#eye').click(function(){
            var input = document.getElementById('motDePasseNormal');
            var eye = document.getElementById('eye');
         
            if (input.type == 'text'){
                $('#motDePasseNormal').attr('type', 'password');
                eye.innerHTML = '<i class="fa fa-eye-slash"></i>'
            }else if (input.type == 'password'){
                $('#motDePasseNormal').attr('type', 'text');
                eye.innerHTML = '<i class="fa fa-eye"></i>'
            }
        });
        
        
        
    </script>

    
</body>
      

</html>