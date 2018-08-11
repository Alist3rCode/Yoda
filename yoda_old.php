<?php
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 0);

// each client should remember their session id for EXACTLY 1 hour
// session_set_cookie_params(14400);
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

try
{
	$bdd2 = new PDO('mysql:host=localhost;dbname=ecsupgrader;charset=utf8', 'yoda', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

error_reporting(E_ALL);
set_time_limit(0);
ini_set('display_errors', 1);




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
	

if(!isset($_SESSION['login']) || $_SESSION['login'] != 'ok'){
    
    header('Location: login.php?redirect=yoda.php');
    
}else if(!isset($_SESSION['id_user']) || $_SESSION['id_user'] == ''){
   
   header('Location: login.php?redirect=yoda.php');
    
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

    

?>




<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bookmarks for all the ETMI Customer Application. For RIS and PACS. Internal user Only !">
    <meta name="author" content="Yohann LOPEZ">
    <title>Bookmarks clients</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">


	<!-- CSS Yoda -->
    <link href="css/css_yoda.css" rel="stylesheet">
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     <link href="css/scrolltabs.css" rel="stylesheet">
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
            
            <li class="nav-item">
                
                
                
				<div class="input-group btn-group" role="group">
				    <?php if(in_array("rgt_cod_add_client", $right)):?>
				    <button type="button" class="btn btn-outline-success createIcon"  id="create" data-toggle="modal" data-target="#myModal"><i class="fa fa-fw fa-plus-circle"></i></button>
				    <?php endif;?>
					<input id="searchBar"  type="text" class="form-control searchBar" placeholder="Recherche...">
					<button class="btn btn-outline-success " id="resetSearch" ><i class="fa fa-fw fa-times"></i></button>
				</div>
	        </li>
	        
	        <div class="dropdown yoda_menu" >
                <button class="btn btn-outline-light dropdown-toggle col-12" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Menu
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="index.php">Dashboard</a>
                    <a class="dropdown-item active" href="yoda.php">Clients</a>
                    <a class="dropdown-item <?php 
                      if(isset($_GET['filter']) && $_GET['filter'] == 'ok'){
                          echo 'filter';
                          }?>" href="#" id='switchFilter'>Filtres Version</a>
                    <a class="dropdown-item <?php 
                      if(isset($_GET['filter']) && $_GET['filter'] == 'activity'){
                          echo 'filter';
                          }?>" href="#" id='activityFilter'>Filtres Activité</a>
                    <a class="dropdown-item" href="maps.php">Carte</a>
                    <a class="dropdown-item" href="interne.php" >Lien Interne</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="profil.php">Profil</a>
                    <a class="dropdown-item" href="notif.php">Notifications</a>
                    <a class="dropdown-item" href="ajax/logout.php" style="color:red;">Deconnexion</a>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary"  id="helpMePlease" data-toggle="modal" data-target="#modalHelp" style="margin-left:15px;"><i class="fa fa-fw fa-question-circle"></i></button>
          </ul>
        </div> 
      </div>
      
    </nav> 
    
    
 
       
       <?php 
       
       $arrayV8 = [];
       
       $select = $bdd->query('SELECT distinct(CLI_NUM_VERSION) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v8" AND CLI_NUM_VERSION IS NOT NULL AND CLI_UID IS NULL order by cli_num_version asc');
            while ($version = $select->fetch()){
                array_push($arrayV8, $version['CLI_NUM_VERSION']);
            }
        $select2 = $bdd->query('SELECT CLI_UID FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v8" AND CLI_UID IS NOT NULL');
            while ($uid = $select2->fetch()){
                // print_r($uid);
                $select3 = $bdd2->query('SELECT * FROM wrk_client where wrk_client.uid = "' . $uid['CLI_UID'] . '"');
                    
                    while ($query = $select3->fetch()){
                       
                        $version = $query['version'] . '.' . $query['hotfix'];
                        if (!in_array($version, $arrayV8)){
                            array_push($arrayV8, $version);
                        }
                        
                    }
            }
        rsort($arrayV8);
        // print_r($arrayV7);
        
       
       $arrayV7 = [];
       
       $select = $bdd->query('SELECT distinct(CLI_NUM_VERSION) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v7" AND CLI_NUM_VERSION IS NOT NULL AND CLI_UID IS NULL order by cli_num_version asc');
            while ($version = $select->fetch()){
                array_push($arrayV7, $version['CLI_NUM_VERSION']);
            }
        $select2 = $bdd->query('SELECT CLI_UID FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v7" AND CLI_UID IS NOT NULL');
            while ($uid = $select2->fetch()){
                // print_r($uid);
                $select3 = $bdd2->query('SELECT * FROM wrk_client where wrk_client.uid = "' . $uid['CLI_UID'] . '"');
                    
                    while ($query = $select3->fetch()){
                       
                        $version = $query['version'] . '.' . $query['hotfix'];
                        if (!in_array($version, $arrayV7)){
                            array_push($arrayV7, $version);
                        }
                        
                    }
            }
        rsort($arrayV7);
        // print_r($arrayV7);
        
        $arrayV6 = [];
       
        $select = $bdd->query('SELECT distinct(CLI_NUM_VERSION) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v6" AND CLI_NUM_VERSION IS NOT NULL AND CLI_UID IS NULL order by cli_num_version asc');
            while ($version = $select->fetch()){
                array_push($arrayV6, $version['CLI_NUM_VERSION']);
            }
        $select2 = $bdd->query('SELECT CLI_UID FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v6" AND CLI_UID IS NOT NULL');
            while ($uid = $select2->fetch()){
                $select3 = $bdd2->query('SELECT * FROM wrk_client where wrk_client.uid = "' . $uid['CLI_UID'] . '"');
                    
                    while ($query = $select3->fetch()){
                        $version = $query['version'] . '.' . $query['hotfix'];
                        if (!in_array($version, $arrayV6)){
                            array_push($arrayV6, $version);
                        }
                    }
            }
        rsort($arrayV6);
       
       
       if(isset($_GET['filter'])):?>
            <?php if($_GET['filter'] == 'v7'):?>
            <div id="tabSetV7" style='background-color: #6C7A89;'>   
                <span class="bigSearchV7" data-ok='0' id='searchV7'>v7</span>
                <?php 
                
                for ($i = 0; $i < count($arrayV7); $i ++){
                    echo '<span class="searchV7 text-center"  id="searchVersion_'. $arrayV7[$i] .'" onclick="searchVersionV7(\''. $arrayV7[$i] .'\')">' . $arrayV7[$i] . '</span>';
                }
                 
            endif;
            if($_GET['filter'] == 'v6'):?>
            <div id="tabSetV6" style='background-color: #6C7A89;'>   
                <span class="bigSearchV6" data-ok='0' id='searchV6'>v6</span>
                <?php 
                
                for ($i = 0; $i < count($arrayV6); $i ++){
                    echo '<span class="searchV6 text-center"  id="searchVersion_'. $arrayV6[$i] .'" onclick="searchVersionV6(\''. $arrayV6[$i] .'\')">' . $arrayV6[$i] . '</span>';
                }
                
            endif; 
            if($_GET['filter'] == 'v8'):?>
            <div id="tabSetV8" style='background-color: #6C7A89;'>   
                <span class="bigSearchV8" data-ok='0' id='searchV8'>v6</span>
                <?php 
                
                for ($i = 0; $i < count($arrayV8); $i ++){
                    echo '<span class="searchV8 text-center"  id="searchVersion_'. $arrayV8[$i] .'" onclick="searchVersionV8(\''. $arrayV8[$i] .'\')">' . $arrayV8[$i] . '</span>';
                }
                
            endif; 
            
            if($_GET['filter'] == 'activity'):?>
            <div class ="text-center" id="tabSetActivity" style='background-color: #6C7A89;'>   
                <!--<div style='background-color: #6C7A89;height:40px;' class="text-center">-->
                    <span class="searchActivity text-center" id="SearchActivity0" onclick="searchActivity(0)" style="margin-left:5px;margin-right:5px; color:white"><img style="margin-top: 5px;" src="css/Vignettes/nada.png" height="30"/> :Aucun</span>
                    <span class="searchActivity text-center" id="SearchActivity1" onclick="searchActivity(1)" style="margin-left:5px;margin-right:5px;color:white"><img style="margin-top: 5px;" src="css/Vignettes/RIS.png" height="30"/> : RIS</span>
                    <span class="searchActivity text-center" id="SearchActivity2" onclick="searchActivity(2)" style="margin-left:5px;margin-right:5px;color:white"><img style="margin-top: 5px;" src="css/Vignettes/PACS.png" height="30"/> : PACS</span>
                    <span class="searchActivity text-center" id="SearchActivity3" onclick="searchActivity(3)" style="margin-left:5px;margin-right:5px;color:white"><img style="margin-top: 5px;" src="css/Vignettes/PACS-RIS.png" height="30"/> : RIS - PACS</span>
                <!--</div>-->
                
            <?php endif; 
            
            
            if($_GET['filter'] == 'ok'):?>
            <div id="tabSetV8" style='background-color: #6C7A89;    margin-bottom: 0;'>   
                <span class="bigSearchV8"  data-ok='0' id='searchV8'>v8</span>
                <?php 
                
                for ($i = 0; $i < count($arrayV8); $i ++){
                    echo '<span class="searchV8 text-center"  id="searchVersion_'. $arrayV8[$i] .'" onclick="searchVersionV8(\''. $arrayV8[$i] .'\')">' . $arrayV8[$i] . '</span>';
                }
            
            ?>
            </div>
            
            <div id="tabSetV7" style='background-color: #6C7A89;    margin-bottom: 0;'>   
                <span class="bigSearchV7"  data-ok='0' id='searchV7'>v7</span>
                <?php 
                
                for ($i = 0; $i < count($arrayV7); $i ++){
                    echo '<span class="searchV7 text-center"  id="searchVersion_'. $arrayV7[$i] .'" onclick="searchVersionV7(\''. $arrayV7[$i] .'\')">' . $arrayV7[$i] . '</span>';
                }
            
            ?>
            </div>
                
            <div id="tabSetV6" style='background-color: #6C7A89;'>   
                <span class="bigSearchV6"  data-ok='0' id='searchV6'>v6</span>
                <?php 
                
                for ($i = 0; $i < count($arrayV6); $i ++){
                    echo '<span class="searchV6 text-center" id="searchVersion_'. $arrayV6[$i] .'" onclick="searchVersionV6(\''. $arrayV6[$i] .'\')">' . $arrayV6[$i] . '</span>';
                }     
            endif;
            
            ?>
       
            </div>
        <?php endif; ?>
    
        
   
   
<div class="bookmarks" id="bookmarks">
    <!--<p><FONT color="white"><pre>-->
    <?php 
    
            if(isset($_GET['filter'])){
                if($_GET['filter'] == 'v7'){
                    $select = $bdd->query('SELECT * FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v7" ORDER BY CLI_VILLE, CLI_NOM ASC');
                }
                if($_GET['filter'] == 'v6'){
                    $select = $bdd->query('SELECT * FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v6" ORDER BY CLI_VILLE, CLI_NOM ASC');
                }
                if($_GET['filter'] == 'ok' || $_GET['filter'] == 'activity'){
                    $select = $bdd->query('SELECT * FROM YDA_CLIENT WHERE CLI_VALID = 1 ORDER BY CLI_VILLE, CLI_NOM ASC');
                }
            }else{
                $select = $bdd->query('SELECT * FROM YDA_CLIENT WHERE CLI_VALID = 1 ORDER BY CLI_VILLE, CLI_NOM ASC');
            }
            
            while ($bookmark = $select->fetch()): ?>
                
            <?php if (substr($bookmark['CLI_URL'],-1) !='/'){
                $bookmark['CLI_URL'] = $bookmark['CLI_URL'] . '/';
            } 
            $i = $bookmark['CLI_ID'];
            $ifPhone = 0;
           
            $select2 = $bdd->query('SELECT COUNT(*) FROM YDA_PHONE WHERE PHO_ID_CLI ="' . $i .'" AND PHO_VALID =  1');
                while ($phone = $select2->fetch()){
                    $ifPhone = $phone['COUNT(*)'];
                }
            $classVignette = '';
             
                if(isset($_GET['filter']) && $_GET['filter'] == 'activity'){
                 
                    $classVignette = 'class' . $bookmark['CLI_RIS'] . $bookmark['CLI_PACS'];   
                     
                }else{
                    $classVignette = $bookmark['CLI_VERSION'];
                }
             
            ?>
                <div class="vignette" id="vignette_<?=$i?>">
                    <a href="<?=$bookmark['CLI_URL']?>" target="_blank"id="<?=$i?>_vign_url">
                        <div class="<?=$classVignette?>" id="<?=$i?>_vign_version">
                        
                            <h4 class="nomVille"  id="<?=$i?>_vign_ville"><?=$bookmark['CLI_VILLE']?></h4>
                            <p class="nomClient"  id="<?=$i?>_vign_nom"><?=$bookmark['CLI_NOM']?></p>
                        
                        </div>
                    </a>
                    <div class="bdd">
                        <div class="phoneIcon text-center">
                            
                            <?php if($ifPhone != 0 ):?>
                                <a class="phoneIconLink" id="phoneLink_<?=$i?>">
                                   
                            <?php endif;
                             
                                    if($ifPhone != 0 ){
                                        echo '<i class="fa fa-phone phoneIconFa" id="phoneIcon_'. $i . '"></i>';
                                        
                                    }else{
                                        echo '<i class="fa fa-ban"></i>';
                                        
                                    }
                            ?>
                                
                        </a>
                        </div>
                        
                        <?php if(in_array("rgt_cod_database", $right)):?>
                        <a href="<?=$bookmark['CLI_URL']?>sqlpacsadmin" target="_blank" id="<?=$i?>_dbb_url" class="database text-center"><i class='fa fa-database'></i></a>
                        <?php endif;?>
                        <a class="versioning text-center" data-toggle="tooltip" data-html="true"  data-id="<?=$i?>" data-placement="bottom" data-title="test"><i class='fa fa-code-fork'></i></a>
                        <?php if(in_array("rgt_cod_modif_client", $right)):?>
                        <a class="modifIcon text-center" onclick="modif(<?=$i?>)" data-toggle="modal" data-target="#myModal" ><i class="fa fa-pencil-square-o"></i></a>
                        <?php endif;?>
                    </div>
                </div>
                
                
           <?php endwhile;
           $select->closeCursor();
            ?>

</div>
<?php if(in_array("rgt_cod_database", $right) || in_array("rgt_cod_add_client", $right)):?>
<!--Modal-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h4 class="modal-title" id="myModalLabel">Titre</h4>
                </div>
                <div class="modal-body" style="background-image:url(css/Vignettes/wall.jpg); width=500px;">
                    
                    <div id="myDIV" class="v7" style="margin:5px auto;">
                        <h4 id="id_ville" style="text-transform:capitalize;color:white;">Ville</h4>
                        <p id="id_nom" style="text-transform:capitalize;">Site Principal</p></div>
                    
                    <div class="form-control" style="display: flex;flex-flow: row wrap; justify-content: space-between; margin-top: 20px;" id="formulaire">
                        
                        <input class="form-control col-6" type="text" name="ville" id="ville" value="Ville..." onfocus="if(this.value=='Ville...')this.value=''" onblur="if(this.value=='')this.value='Ville...'" autocomplete="off" style=" margin:5px 0;" onkeyup="type_ville()">
                        <input type='hidden' name='taille_ville' id='taille_ville' value='24px'>
                        
                        <input class="form-control col-6" type="text" name="nom" id="nom" value="Site Principal..." onfocus="if(this.value=='Site Principal...')this.value=''" onblur="if(this.value=='')this.value='Site Principal...'" autocomplete="off" style=" margin:5px 0;" onkeyup="type_nom()">
                        <input type='hidden' name='taille_nom' id='taille_nom'  value='24px'>
                        
                        <input class="form-control col-12" type="text" name="url" id="url" value="https://..." onfocus="if(this.value=='https://...')this.value=''"  onblur="if(this.value=='')this.value='https://...'" autocomplete="off" style=" margin:0 0 5px 0;">
                        
                        <div id ='phones' class='col-12' style="padding-right:0px!important;padding-left:0px!important;">
                            <div id="divPhone0">
                                <div class="btn-group special col-12" role="group" style="padding-right:0px!important;padding-left:0px!important;" >
                                    
                                    <button type="button" class="btn btn-outline-success form-group col-1 newPhone"  id="newPhone0" onclick="newPhone(0)"><i class="fa fa-plus"></i></button>
                                    
                                    <input class="form-group col-10 phoneClass" type="text" name="phone0" id="phone0" value="Téléphone..." onfocus="if(this.value=='Téléphone...')this.value=''"  onblur="if(this.value=='')this.value='Téléphone...'" autocomplete="off" style=" margin:0 0 5px 0; height:38px;">
                                    
                                    <input class="form-group col-5 d-none siteClass" type="text" name="site0" id="site0" value="Site..." onfocus="if(this.value=='Site...')this.value=''"  onblur="if(this.value=='')this.value='Site...'" autocomplete="off" style=" margin:0 0 5px 0; height:38px;">
                                    
                                    <button type="button" class="btn btn-outline-secondary form-group col-1 deletePhone"  id="deletePhone0" disabled onclick="deletePhone(0)"><i class="fa fa-trash-o"></i></button>
                                
                                </div>
                            
                                <input class="form-group col-5 latClass" type="text" name="lat0" id="lat0" value="Latitude..." onfocus="if(this.value=='Latitude...')this.value=''"  onblur="if(this.value=='')this.value='Latitude...'" autocomplete="off" style="height:38px;margin-left: 15px; margin-right: 15px;">
                                        
                                <input class="form-group col-5 lonClass" type="text" name="lon0" id="lon0" value="Longitude..." onfocus="if(this.value=='Longitude...')this.value=''"  onblur="if(this.value=='')this.value='Longitude...'" autocomplete="off" style="height:38px;margin-left: 15px;margin-right: 15px;">
                           </div> 
                            <hr>
                            <input type='hidden' value ='1' id='delete0' name='delete0'>
                            <input type='hidden' value ='' id='id0' name='id0'>
                            
                        </div>
                        
                        <input type='hidden' value ='' id='nbPhone' name="nbPhone">
                        
                        <p class="col-12" style="text-align:center;">Saisir des tags séparés par des virgules : </p>
                        <div id="wrapper">
                            <ul class="tags-input col-12 "  id="tags-input">
                                <!--<li class="tags">Tags...<i class="fa fa-times"></i></li>-->
                                <li class="tags-new">
                                    
                                    <input type="text" id="tag" name="tag" value="Tags..." onfocus="if(this.value=='Tags...')this.value=''"  onblur="if(this.value=='')this.value='Tags...'" autocomplete="off" style=" margin:0 0 5px 0;"> 
                                    <input type='hidden' name='tag_hidden' id='tag_hidden'  value=''>
                                </li>
                            </ul>  
                        </div>
                        
                        
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-primary" id="version8Button">
                                <input type="radio" name="version" id="version8" autocomplete="off" onchange="switchv8()" value='v8'>v8</label>
                            <label class="btn btn-primary active" id="version7Button">
                                <input type="radio" name="version" id="version7" autocomplete="off" onchange="switchv7()" checked value='v7'>v7</label>
                            <label class="btn btn-primary" id="version6Button">
                                <input type="radio" name="version" id="version6" autocomplete="off" onchange="switchv6()" value='v6'>v6</label>
                            
                                
                        </div>
                        
                        <input type="hidden" id="versionHidden" value="v7">
                        
                         <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-outline-secondary" id="risButton">
                                <input type="checkbox" id="risCheckBox" onchange="switchRis()" autocomplete="off"> RIS</label>
                            <label class="btn btn-outline-secondary" id="pacsButton">
                                <input type="checkbox" id="pacsCheckBox" onchange="switchPacs()" autocomplete="off"> PACS</label>
                        </div>
                        
                        <input type="hidden" id="risHidden" value="0">
                        <input type="hidden" id="pacsHidden" value="0">
                        
                        <div class="col-12 row" style="margin-bottom:15px;margin-top:15px">
                            <input type="text" class="form-control col-4" id="viewVersion" placeholder="Version View">
                            <input type="text" class="form-control col-4" id="uViewVersion" placeholder="Version uView">
                            <input type="text" class="form-control col-4" id="imagingVersion" placeholder="Version Imaging">
                        </div>
                       
                        <button class="btn btn-danger" type="hidden" id ='buttonDelete' style="float:right;" value="Supprimer" >Supprimer</button>
                       
                        <button class="btn btn-primary"  id ='buttonSubmit' style="float:right;" value="Valider" >Valider</button>
                        <button class="btn btn-primary d-none"  id ='buttonModif' style="float:right;" value="Modifier" >Modifier</button>
                        
                        <div id='alerte' class="col-12" style="margin-top:10px;"></div>
                        <div id='id' class="d-none"></div>
                        
                   
                        
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
<?php endif;?>

        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="modalHelp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Trucs et Astuces</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                
                <small class="text-muted">Lors de la frappe, il est possible d'utiliser certaines touches pour effectuer des actions rapides sur la vignette sélectionnée par le halo vert.</small> 
                <br>
            
                <table class="table table-hover">
                  <thead>
                    <tr>
                      
                      <th scope="col">Touche</th>
                      <th scope="col">Raccourci</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Entrée</td>
                      <td>Ouvrir l'URL du client</td>
                      
                    </tr>
                    <tr>
                      <td>Ctrl + Entrée</td>
                      <td>Ouvrir la base de donnée du client soumis à droite</td>
                      
                    </tr>
                    <tr>
                      <td>Echap</td>
                      <td>Efface le texte renseigné dans la barre de recherche</td>
                      
                    </tr>
                    <tr>
                      <td><i class="fa fa-arrow-right"></i></td>
                      <td>Naviguer vers la droite sur les vignette affichées</td>
                      
                    </tr>
                    <tr>
                      <td><i class="fa fa-arrow-left"></i></td>
                      <td>Naviguer vers la gauche sur les vignette affichées</td>
                    </tr>
                    <tr>
                      <td><i class="fa fa-arrow-down"></i></td>
                      <td>Ouvrir la liste des numéros de téléphones / sites du client</td>
                    </tr>
                    <tr>
                      <td><i class="fa fa-arrow-up"></i></td>
                      <td>Fermer la liste des numéros de téléphones / sites du client</td>
                    </tr>
                  </tbody>
                </table>
                
              </div>
              
            </div>
          </div>
        </div>
        <!--fin Modale-->



    <!-- Bootstrap core JavaScript -->
    <script  src="https://code.jquery.com/jquery-3.2.1.js"  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="   crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script src="js/jquery.scrolltabs.js"></script>
    <script src="js/yoda.js"></script>
   

   
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script>
   
    
   
    </script>
 
</body>
      

</html>