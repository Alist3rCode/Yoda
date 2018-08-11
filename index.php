<?php
// server should keep session data for AT LEAST 1 hour
// ini_set('session.gc_maxlifetime', 0);
// session_set_cookie_params(0);
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
checkCookie('index.php');

$right=[];
$select = $bdd->query('SELECT RGT_CODE FROM YDA_RIGHT JOIN YDA_HOOK on HOK_ID_RGT = RGT_ID JOIN YDA_PROFIL ON HOK_ID_PRO = PRO_ID JOIN YDA_USERS ON USR_ID_PRO = PRO_ID WHERE USR_ID ="'.$_SESSION["id_user"].'"');

while ($query = $select->fetch()){

    array_push($right, $query['RGT_CODE']);
   
}            

$planningAccess = 0;
    
$select = $bdd->query('SELECT USR_TECH, USR_DIRECTION FROM YDA_USERS WHERE USR_ID ="'.$_SESSION["id_user"].'"');

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
    <title>Bookmarks clients</title>
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
<div class="widget">
    <div class="dateTime ">
        <div class="date" id="date">
            <div id="startDate" class="startDate h3 text-center"></div>
            <div class="year display-1 text-center" id="year"></div>
        </div>
        <div class="time text-center" id="time"></div>
        
        
    </div>
</div>
<div>
            
        </div>

<div class="widget">
    
    <a href="yoda.php">
        <div class="widgetVersion">    
            <div class="vxCustomer">
                <span class="display-4">
                <?php $select = $bdd->query('SELECT count(*) FROM YDA_CLIENT WHERE CLI_VALID = 1');
                while ($version = $select->fetch()){
                    echo $version['count(*)'];
                }
                ?>
                </span> Clients
            </div>
            <div class="vxVersion display-2"><span class="version text-center">All</span></div>
            <div class="vxPlus">
                <?php $select = $bdd->query('SELECT count(PHO_ID) FROM YDA_PHONE JOIN YDA_CLIENT ON CLI_ID = PHO_ID_CLI WHERE CLI_VALID = 1 AND PHO_VALID = 1');
                while ($version = $select->fetch()){
                    echo $version['count(PHO_ID)'];
                }
                ?> Sites</div>
            
        </div>
    </a>
    
    <a href="yoda.php?filter=v6">
        <div class="widgetVersion">    
            <div class="v6Customer">
                <span class="display-4">
                <?php $select = $bdd->query('SELECT count(*) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v6"');
                while ($version = $select->fetch()){
                    echo $version['count(*)'];
                }
                ?>
                </span> Clients
            </div>
            <div class="v6Version display-2"><span class="version text-center">V6</span></div>
            <div class="v6Plus">
                <?php $select = $bdd->query('SELECT count(PHO_ID) FROM YDA_PHONE JOIN YDA_CLIENT ON CLI_ID = PHO_ID_CLI WHERE CLI_VALID = 1 AND PHO_VALID = 1 AND CLI_VERSION = "v6"');
                while ($version = $select->fetch()){
                    echo $version['count(PHO_ID)'];
                }
                ?> Sites</div>
            
        </div>
    </a>
    
    <a href="yoda.php?filter=v7">
        <div class="widgetVersion">    
            <div class="v7Customer">
                <span class="display-4">
                <?php $select = $bdd->query('SELECT count(*) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v7"');
                while ($version = $select->fetch()){
                    echo $version['count(*)'];
                }
                ?>
                </span> Clients
            </div>
            <div class="v7Version display-2"><span class="version text-center">V7</span></div>
            <div class="v7Plus">
                <?php $select = $bdd->query('SELECT count(PHO_ID) FROM YDA_PHONE JOIN YDA_CLIENT ON CLI_ID = PHO_ID_CLI WHERE CLI_VALID = 1 AND PHO_VALID = 1 AND CLI_VERSION = "v7"');
                while ($version = $select->fetch()){
                    echo $version['count(PHO_ID)'];
                }
                ?> Sites</div>
            
        </div>
    </a>
    
    <a href="yoda.php?filter=v8">
        <div class="widgetVersion">    
            <div class="v8Customer">
                <span class="display-4">
                <?php $select = $bdd->query('SELECT count(*) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_VERSION = "v8"');
                while ($version = $select->fetch()){
                    echo $version['count(*)'];
                }
                ?>
                </span> Clients
            </div>
            <div class="v8Version display-2"><span class="version text-center">V8</span></div>
            <div class="v8Plus">
                <?php $select = $bdd->query('SELECT count(PHO_ID) FROM YDA_PHONE JOIN YDA_CLIENT ON CLI_ID = PHO_ID_CLI WHERE CLI_VALID = 1 AND PHO_VALID = 1 AND CLI_VERSION = "v8"');
                while ($version = $select->fetch()){
                    echo $version['count(PHO_ID)'];
                }
                ?> Sites</div>
            
        </div>
    </a>
    
    

</div>
 
<div class="widget">
    
    <a href="yoda.php?filter=activity">
        <div class="widgetVersion">    
            <div class="risCustomer">
                <span class="display-4">
                <?php $select = $bdd->query('SELECT count(*) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_RIS = 1 AND CLI_PACS = 0');
                while ($version = $select->fetch()){
                    echo $version['count(*)'];
                }
                ?>
                </span> Clients
            </div>
            <div class="risVersion display-2"><span class="activity text-center">RIS</span></div>
            <div class="risPlus">
                <?php $select = $bdd->query('SELECT count(PHO_ID) FROM YDA_PHONE JOIN YDA_CLIENT ON CLI_ID = PHO_ID_CLI WHERE CLI_VALID = 1 AND PHO_VALID = 1 AND CLI_RIS = 1 AND CLI_PACS = 0');
                while ($version = $select->fetch()){
                    echo $version['count(PHO_ID)'];
                }
                ?> Sites</div>
            
        </div>
    </a>
    
    <a href="yoda.php?filter=activity">
        <div class="widgetVersion">    
            <div class="pacsCustomer">
                <span class="display-4">
                <?php $select = $bdd->query('SELECT count(*) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_RIS = 0 AND CLI_PACS = 1');
                while ($version = $select->fetch()){
                    echo $version['count(*)'];
                }
                ?>
                </span> Clients
            </div>
            <div class="pacsVersion display-2" ><span class="activity text-center" style="font-size: 54px;">PACS</span></div>
            <div class="pacsPlus">
                <?php $select = $bdd->query('SELECT count(PHO_ID) FROM YDA_PHONE JOIN YDA_CLIENT ON CLI_ID = PHO_ID_CLI WHERE CLI_VALID = 1 AND PHO_VALID = 1 AND CLI_RIS = 0 AND CLI_PACS = 1');
                while ($version = $select->fetch()){
                    echo $version['count(PHO_ID)'];
                }
                ?> Sites</div>
            
        </div>
    </a>
    
    <a href="yoda.php?filter=activity">
        <div class="widgetVersion">    
            <div class="risPacsCustomer">
                <span class="display-4">
                <?php $select = $bdd->query('SELECT count(*) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND CLI_RIS = 1 AND CLI_PACS = 1');
                while ($version = $select->fetch()){
                    echo $version['count(*)'];
                }
                ?>
                </span> Clients
            </div>
            <div class="risPacsVersion "><span class="version text-center" style="font-size: 54px;font-weight: 300;line-height: 1.2;">RIS<br>PACS</span></div>
            <div class="risPacsPlus">
                <?php $select = $bdd->query('SELECT count(PHO_ID) FROM YDA_PHONE JOIN YDA_CLIENT ON CLI_ID = PHO_ID_CLI WHERE CLI_VALID = 1 AND PHO_VALID = 1 AND CLI_RIS = 1 AND CLI_PACS = 1');
                while ($version = $select->fetch()){
                    echo $version['count(PHO_ID)'];
                }
                ?> Sites</div>
            
        </div>
    </a>
    
    

</div>
<div class="widget">
    
    <a href="yoda.php?filter=activity">
        <div class="widgetVersion">    
            <div class="viewCustomer">
                <span class="display-4">
                <?php $select = $bdd->query('SELECT count(*) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND TRIM(CLI_VIEW) != ""');
                while ($version = $select->fetch()){
                    echo $version['count(*)'];
                }
                ?>
                </span> Clients
            </div>
            <div class="viewVersion display-4"><span class="activity text-center">View</span></div>
            <div class="viewPlus">
                <?php $select = $bdd->query('SELECT count(PHO_ID) FROM YDA_PHONE JOIN YDA_CLIENT ON CLI_ID = PHO_ID_CLI WHERE CLI_VALID = 1 AND PHO_VALID = 1 AND TRIM(CLI_VIEW) != ""');
                while ($version = $select->fetch()){
                    echo $version['count(PHO_ID)'];
                }
                ?> Sites</div>
            
        </div>
    </a>
    
    <a href="yoda.php?filter=activity">
        <div class="widgetVersion">    
            <div class="uViewCustomer">
                <span class="display-4">
                <?php $select = $bdd->query('SELECT count(*) FROM YDA_CLIENT WHERE CLI_VALID = 1 AND TRIM(CLI_UVIEW) != ""');
                while ($version = $select->fetch()){
                    echo $version['count(*)'];
                }
                ?>
                </span> Clients
            </div>
            <div class="uViewVersion" style="font-size: 3.1rem;font-weight: 300;line-height: 1.2;"><span class="activity text-center">uView</span></div>
            <div class="uViewPlus">
                <?php $select = $bdd->query('SELECT count(PHO_ID) FROM YDA_PHONE JOIN YDA_CLIENT ON CLI_ID = PHO_ID_CLI WHERE CLI_VALID = 1 AND PHO_VALID = 1 AND TRIM(CLI_UVIEW) != ""');
                while ($version = $select->fetch()){
                    echo $version['count(PHO_ID)'];
                }
                ?> Sites</div>
            
        </div>
    </a>
</div>



    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/yoda.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script>
    var d = new Date();
    var options = {weekday: "long", month: "long", day: "numeric"};
    var n = d.toLocaleDateString("fr-FR", options);
    // var y = d.getFullYear();
    document.getElementById("startDate").innerHTML = n;
    // document.getElementById("year").innerHTML = y;
    
    var clockDiv = document.getElementById("time");
    
    function Clock() {
    	var date = new Date(),
    		hr = date.getHours(),
    		min = date.getMinutes(),
    		sec = date.getSeconds();
    	
    	// hr > 12 ? hr = hr - 12 : hr = hr;
    	hr < 10 ? (hr = "0" + hr) : (hr = hr);
    	min <= 9 ? (min = "0" + min) : (min = min);
    	sec <= 9 ? (sec = "0" + sec) : (sec = sec);
    	clockDiv.innerHTML = hr + ":" + min + ":" + sec;
    }
    window.onload = function() {
    	setInterval(Clock, 400);
    };

    
   
    </script>
 
</body>
      

</html>