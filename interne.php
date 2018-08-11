<?php
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 14400);
// session_set_cookie_params(0);
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
checkCookie('interne.php');


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
    <title>Internal Links</title>
    <!--<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">


	<!-- CSS Yoda -->
    <link href="css/css_yoda.css" rel="stylesheet">
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     <link rel="icon" type="image/png" href="./img/yoda.png" />
  </head>

  <body background="css/Vignettes/Background.jpg" style="overflow-x:hidden;">
      <div id='id_user' class="d-none"><?=$_SESSION['id_user']?></div>
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
        <div class="internal">
        <div class="internal"><figure><a href="https://release-imaging.evolucare.com/download/interne/BI/" > <img src=" css/Interne/bi.png"  width=100 height=100 alt ="Business Intelligence" title= "Business Intelligence"/></a><figcaption>B.I.</figcaption></figure></div>
        <div class="internal"><figure><a href="https://release-imaging.evolucare.com/download/interne/Goodies/" > <img src=" css/Interne/design.png"  width=100 height=100 alt ="Goodies" title="Goodies"/></a><figcaption>Goodies</figcaption></figure></div>
        <div class="internal"><figure><a href="https://release-imaging.evolucare.com/download/interne/Logiciels/" > <img src=" css/Interne/download.png"  width=100 height=100 alt ="Logiciels" title="Logiciels"/></a><figcaption>Download</figcaption></figure></div>
        <div class="internal"><figure><a href="https://release-imaging.evolucare.com/download/interne/formation/" > <img src=" css/Interne/formation.png"  width=100 height=100 alt ="Formation" title="Formation"/></a><figcaption>Formation</figcaption></figure></div>
        <div class="internal"><figure><a href="https://release-imaging.evolucare.com/download/interne/masters/" > <img src=" css/Interne/evolucare.png"  width=100 height=100 alt ="Master ECS Imaging" title="Master"/></a><figcaption>Masters</figcaption></figure></div>
        <div class="internal"><figure><a href="https://release-imaging.evolucare.com/upgrade/"> <img src=" css/Interne/upgrade.png"  width=100 height=100 alt ="Upgrade" title="Upgrade"/></a><figcaption>Upgrade</figcaption></figure></div>
        <div class="internal"><figure><a href="https://release-imaging.evolucare.com/release_note/ECSImaging/fr/" target="_blank"> <img src=" css/Interne/release.png"  width=100 height=100 alt ="release_note" title="Releases_notes"/></a><figcaption>Release Notes</figcaption></figure></div>
        <div class="internal"><figure><a href="https://release-imaging.evolucare.com/release_note/ReleaseNoteCreator/index.php" target="_blank"> <img src=" css/Interne/creator.png"  width=100 height=100 alt ="release_note_creator" title="Release_Note_Creator"/></a><figcaption>Kreator</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://evolucare.slack.com/messages" target="_blank"> <img src=" css/Interne/slackIcon.png"  width=100 height=100 alt ="Slack" title="Slack"/></a><figcaption>Slack</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://maj-imaging.evolucare.com/yoda/mdp.php"> <img src="css/Interne/lock.png"  width=100 height=100 alt ="Password Generator" title="Password Generator"/></a><figcaption>Password</figcaption></figure></div>
        
        </div>
        
        <div class="internal">  
        
        <div class="internal"><figure><a href="https://192.168.75.38/" target="_blank"> <img src=" css/Interne/base_test.png"  width=100 height=100 alt ="Base de test" title="Base de test"/></a><figcaption>Base 38</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://192.168.75.46/" target="_blank"> <img src=" css/Interne/base_test.png"  width=100 height=100 alt ="Base de test" title="Base de test"/></a><figcaption>Base 46</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://172.20.81.147/" target="_blank"> <img src=" css/Interne/base_test.png"  width=100 height=100 alt ="Base de test" title="Base de test"/></a><figcaption>Base Support</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://kelio.evolucare.com/open/login" target="_blank"> <img src=" css/Interne/kelio.png"  width=100 height=100 alt ="Kelio" title="Kelio"/></a><figcaption>Kelio - Badgeuse</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://login.microsoftonline.com" target="_blank"> <img src=" css/Interne/office.png"  width=100 height=100 alt ="Office" title="Office"/></a><figcaption>Office</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://confluence.evolucare.com:8443" target="_blank"> <img src=" css/Interne/confluence.png"  width=100 height=100 alt ="Confluence" title="Confluence"/></a><figcaption>Confluence</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://jira.evolucare.com/secure/Dashboard.jspa" target="_blank"> <img src=" css/Interne/jira.png"  width=100 height=100 alt ="Jira" title="Jira"/></a><figcaption>Jira</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://confluence.evolucare.com:8443/display/ETMI/1+-+Documentation+Hotline" target="_blank"> <img src=" css/Interne/documentation.png"  width=100 height=100 alt ="Doc Hotline" title="Doc Hotline"/></a><figcaption>Doc Hotline</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://portailclient.evolucare.com/#/" target="_blank"> <img src=" css/Interne/support.png"  width=100 height=100 alt ="Support" title="Support"/></a><figcaption>ECS Support</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://www.aideaucodage.fr/ccam" target="_blank"> <img src=" css/Interne/aac.png"  width=100 height=100 alt ="Aide Au Codage" title="Aide Au Codage"/></a><figcaption>Aide Au Codage</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://sno.phy.queensu.ca/~phil/exiftool/TagNames/DICOM.html" target="_blank"> <img src=" css/Interne/tag.png"  width=100 height=100 alt ="Tag Dicom" title="Tag Dicom"/></a><figcaption>Tag Dicom</figcaption></figure></div>
        
        <div class="internal"><figure><a href="https://www.dicomlibrary.com/dicom/sop/" target="_blank"> <img src=" css/Interne/soap.png"  width=100 height=100 alt ="Classes SOP" title="Classes SOP"/></a><figcaption>Classes SOP</figcaption></figure></div>
        </div>
        
        <?php
        //phpinfo();?>



    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/yoda.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    
</body>
      

</html>