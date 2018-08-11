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
checkCookie('maps.php');


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
<style>
	/* Always set the map height explicitly to define the size of the div
	* element that contains the map. */
	#map {
	height: calc(100% - 102px);
	}
	/* Optional: Makes the sample page fill the window. */
</style>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Bookmarks for all the ETMI Customer Application. For RIS and PACS. Internal user Only !">
		<meta name="author" content="Yohann LOPEZ">
		<title>Yoda Maps</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
		<!-- CSS Yoda -->
		<link href="css/css_yoda.css" rel="stylesheet">
		<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="css/scrolltabs.css" rel="stylesheet">
		<link rel="icon" type="image/png" href="./img/yoda.png" />
		<script  src="https://code.jquery.com/jquery-3.2.1.js"  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="   crossorigin="anonymous"></script>
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
		<div style="background-color:#343a40;" class="col-12">
			<div class="btn-group btn-group-toggle col-12" data-toggle="buttons">
				<label class="btn btn-outline-primary col-4 active">
				<input type="radio" name="filter" id="aucunRB" autocomplete="off" checked> Aucun
				</label>
				<label class="btn btn-outline-info col-4">
				<input type="radio" name="filter" id="versionRB" autocomplete="off"> Version
				</label>
				<label class="btn btn-outline-light col-4">
				<input type="radio" name="filter" id="activityRB" autocomplete="off"> Activité
				</label>
			</div>
		</div>
		<div class="d-none" id='filterResult'></div>
		<div id="map"></div>
		<script>
			var customLabelActi = {
			    nada: { icon: './css/Vignettes/greyPin.png'},
			    ris: { icon: './css/Vignettes/redPin.png'},
			    pacs: { icon: './css/Vignettes/greenPin.png' },
			    rispacs: { icon: './css/Vignettes/purplePin.png' },
			};
			
			var customLabelVers = {
			    v6: { icon: './css/Vignettes/orangePin.png'},
			    v7: { icon: './css/Vignettes/bluePin.png'}
			};
			
			var filter = 0;
			
			
			
			$('input[type=radio][name=filter]').change(function() {
			    if (this.id == 'aucunRB') {
			         filter = 0;
			         initMap(this);
			    }
			    else if (this.id == 'versionRB') {
			        
			        filter = 1;
			        initMap(this);
			    }
			    else if (this.id == 'activityRB') {
			        
			        filter = 1;
			        initMap(this);
			    }
			});   
			
			
			
			function firstUpper(string) {
			    return string.charAt(0).toUpperCase() + string.slice(1);
			}
			
			function initMap(RB) {
			    
			var map = new google.maps.Map(document.getElementById('map'), {
			  center: new google.maps.LatLng(20,0),
			  zoom: 3
			});
			var infoWindow = new google.maps.InfoWindow;
			
			$.get("ajax/CreateXML.php", function(json){
			 //   console.log(json);
			});
			
			  // Change this depending on the name of your PHP or XML file
			  downloadUrl('./address.xml' , function(data) {
			    var xml = data.responseXML;
			    var markers = xml.documentElement.getElementsByTagName('marker');
			    console.log(xml);
			    Array.prototype.forEach.call(markers, function(markerElem) {
			      var name = markerElem.getAttribute('name');
			      name = firstUpper(name);
			      var address = markerElem.getAttribute('address');
			      address = firstUpper(address);
			      var activity = markerElem.getAttribute('activity');
			      var version = markerElem.getAttribute('version');
			      var point = new google.maps.LatLng(
			          parseFloat(markerElem.getAttribute('lat')),
			          parseFloat(markerElem.getAttribute('lng')));
			
			      var infowincontent = document.createElement('div');
			      var strong = document.createElement('strong');
			      strong.textContent = name
			      infowincontent.appendChild(strong);
			      infowincontent.appendChild(document.createElement('br'));
			
			      var text = document.createElement('text');
			      text.textContent = address
			      infowincontent.appendChild(text);
			      
			      if (RB== null || RB.id == 'aucunRB') {
			         filter = 0;
			         
			        }
			        else if (RB.id == 'versionRB') {
			             var icon = customLabelVers[version] || {};
			            filter = 1;
			            
			        }
			        else if (RB.id == 'activityRB') {
			            var icon = customLabelActi[activity] || {};
			            filter = 1;
			            
			        }
			      
			      console.log(filter);
			      if(filter == 0){
			        var marker = new google.maps.Marker({
			            map: map,
			            position: point
			        });
			      }else{
			        var marker = new google.maps.Marker({
			            map: map,
			            position: point,
			            icon: icon.icon
			        });
			      
			      }
			      
			      marker.addListener('click', function() {
			        infoWindow.setContent(infowincontent);
			        infoWindow.open(map, marker);
			      });
			    });
			  });
			
			}
			
			
			
			
			function downloadUrl(url, callback) {
			var request = window.ActiveXObject ?
			    new ActiveXObject('Microsoft.XMLHTTP') :
			    new XMLHttpRequest;
			
			request.onreadystatechange = function() {
			  if (request.readyState == 4) {
			    request.onreadystatechange = doNothing;
			    callback(request, request.status);
			  }
			};
			
			request.open('GET', url, true);
			request.send(null);
			}
			
			function doNothing() {}
		</script>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCo_lLa3e8UeMBQdc6EYS5Wbw7udYxl3_o&callback=initMap"></script>
		<!-- Bootstrap core JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<script src="js/jquery.scrolltabs.js"></script>
		<script src="js/yoda.js"></script>
		<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
		<script></script>
	</body>
</html>