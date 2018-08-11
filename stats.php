<?php
ini_set('session.gc_maxlifetime', 0);
session_set_cookie_params(0);
session_start();

// print_r($_SESSION['timeout']);

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
            
// $req = $bdd->prepare('INSERT INTO YDA_SPY(SPY_IP, SPY_TIME, SPY_PAGE, SPY_BROWSER) VALUES (:ip, :now, :page, :browser)');
    
//     $req->execute(array(
// 	'ip' => $ip,
// 	'now' => $now,
// 	'page' => $page,
// 	'browser' => $browser )) or die(print_r($bdd->errorCode()));           
            

?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Bookmarks for all the ETMI Customer Application. For RIS and PACS. Internal user Only !">
    <meta name="author" content="Yohann LOPEZ">
    <title>Yoda Stat</title>
    <!--<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!--<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">-->
    <link href="css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!--style map-->
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
    </style


	<!-- CSS Yoda -->
    <link href="css/css_yoda.css" rel="stylesheet">
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     <link rel="icon" type="image/png" href="./img/yoda.png" />
  </head>

  <body background="css/Vignettes/Background.jpg" style="overflow-x:hidden;">
      <div id='ipLAN' class="d-none"></div>
    <!-- Navigation -->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container col-10">
        
		<h3 style="col-10"><FONT color="#9ACD32" class="yoda">Y</font><FONT color="white" class="yohann">ohann</font> <FONT color="#9ACD32" class="yoda">O</font><FONT color="white" class="yohann">ptimized</font> <FONT color="#9ACD32" class="yoda">D</font><FONT color="white" class="yohann">irect link to</font> <FONT color="#9ACD32" class="yoda">A</font><FONT color="white" class="yohann">pplications</font><FONT color="#9ACD32" class="yoda"> v<?=$yodaVersion?></font></h3>
        
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
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
                    <a class="dropdown-item" href="ajax/logout.php" style="color:red;">Deconnexion</a>
                </div> 
      </div>
    </nav> 
    <div class="col-10 mx-auto" style="margin-top:15px;display:flex;flex-flow:row wrap;">
        <div class="mx-auto col-12" style="margin:15px;">
            <div class="card">
                <div class="card-body mx-auto">
                    Entre 
                    <input id="datetime" type="date" id="debut"> et 
                    <input id="datetime" type="date" id="fin">
                    <button type="button" class="btn btn-success" id="timeSearch">Rechercher</button>
                    <div id="alertTimeSearch" class="alert alert-warning d-none"></div>
                </div>
            </div>
            
        </div>
        
        <!-- Area Chart Example-->
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-area-chart"></i> Connexion par date 
                </div>
                <div class="card-body">
                <canvas id="myAreaChart" width="100%" height="51"></canvas>
                </div>
                <div class="card-footer small col-12">
                    <div class="btn-group btn-group-toggle col-12" data-toggle="buttons" >
                        <label class="btn btn-outline-info active col-3">
                            <input type="radio" name="options" id="option1" autocomplete="off" checked > Mois
                        </label>
                        <label class="btn btn-outline-info col-3">
                            <input type="radio" name="options" id="option2" autocomplete="off" > Semaine
                        </label>
                        <label class="btn btn-outline-info col-3">
                            <input type="radio" name="options" id="option3" autocomplete="off" > Jour
                        </label>
                        <label class="btn btn-outline-info col-3">
                            <input type="radio" name="options" id="option4" autocomplete="off" > Heure
                        </label>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
        <!-- Example Pie Chart Card-->
            <div class="card mb-3">
                <div class="card-header">
                <i class="fa fa-pie-chart"></i> Activité par Page</div>
            <div class="card-body">
                <canvas id="myPieChart" width="100%" height="100"></canvas>
            </div>
            
            </div>
        </div>
        
        
      
        <div class="col-lg-8">
          <!-- Example Bar Chart Card-->
          
            <div id="map" class="card"></div>
        </div>
        <div class="col-lg-4">
          <!-- Example Pie Chart Card-->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-pie-chart"></i> Activité par Navigateur</div>
            <div class="card-body">
              <canvas id="myPieChart2" width="100%" height="100"></canvas>
            </div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
          </div>
        </div>

        
    </div>





        
    
    
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/popper/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="locales/bootstrap-datepicker.fr.min.js" charset="UTF-8"></script>
    <script src="js/stat.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>
    <script src="js/chart.js"></script>
    <script src="js/ipLan.js"></script>
    <script src="js/sb-admin-charts.js"></script>
    
    
    
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCo_lLa3e8UeMBQdc6EYS5Wbw7udYxl3_o&callback=initMap">
    </script>
    

    
</body>
      

</html>