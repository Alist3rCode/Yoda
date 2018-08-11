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
            
	if(!isset($_SESSION['login']) || $_SESSION['login'] != 'ok'){
	    
        header('Location: login.php?redirect=configPlanning.php');
        
	}else if(!isset($_SESSION['id_user']) || $_SESSION['id_user'] == ''){
	   
	   header('Location: login.php?redirect=configPlanning.php');
	    
	}
	
	$select = $bdd->query('SELECT USR_TECH, USR_DIRECTION, USR_REFERING
	                        FROM YDA_USERS 
	                        WHERE USR_ID ="'.$_SESSION["id_user"].'"');

    while ($query = $select->fetch()){
    
        if ($query['USR_REFERING'] == 1 || $query['USR_DIRECTION'] == 1 ){
            $configPlanningAccess = 1; 
        }else{
            $configPlanningAccess = 0; 
            header('Location: index.php');
        }
        
       
    }
//   var_dump($configPlanningAccess);
    
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
    
    

    
    $select2 = $bdd->query('SELECT * FROM PLA_CONFIG');

    while ($query2 = $select2->fetch()){
        
       $arrayPlanning['start'] = date_create_from_format('H:i:s', $query2['PCO_START_PLANNING']);
       $arrayPlanning['stop'] = date_create_from_format('H:i:s', $query2['PCO_STOP_PLANNING']);
       $workingDays = explode(',' , $query2['PCO_WEEKDAY']);
       
    //   var_dump($workingTime);

    }
    
    	$select3 = $bdd->query('SELECT * FROM PLA_SLOT_CONFIG WHERE SCO_VALID = 1');
$idx = 0;
    while ($query3 = $select3->fetch()){
    
        $arrayConfig['id'][$idx] = $query3['SCO_ID'];
       $arrayConfig['name'][$idx] = $query3['SCO_NAME'];
       $arrayConfig['code'][$idx] = $query3['SCO_CODE'];
       $arrayConfig['start'][$idx] = date_create_from_format('H:i:s', $query3['SCO_START']);
       $arrayConfig['stop'][$idx] = date_create_from_format('H:i:s', $query3['SCO_STOP']);
       $arrayConfig['color'][$idx] = $query3['SCO_COLOR'];
       
       $idx +=1;
    }
    
    // var_dump($arrayConfig);

?>


<!doctype html>
<html>
	<header>
		<meta charset="utf-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="css/calendar.css">
		<link rel="stylesheet" href="css/planning_css.php">
		<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="js/moment/moment.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
        

		<meta name="description" content="Bookmarks for all the ETMI Customer Application. For RIS and PACS. Internal user Only !">
        <meta name="author" content="Yohann LOPEZ">
        <title>Planning Support</title>
         <link rel="icon" type="image/png" href="./img/yoda.png" />

	</header>
	<body background="css/Vignettes/Background.jpg" style="overflow-x:hidden;">
		<!--<nav class="nav navbar-dark bg-primary mb-3">-->
		<!--	<a href="./index.php" class="navbar-brand">Mon Calendrier</a>-->
		<!--</nav>-->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container col-10">
        
		<h3 style="col-10"><font color="#9ACD32" class="yoda">Y</font><font color="white" class="yohann">ohann</font> <font color="#9ACD32" class="yoda">O</font><font color="white" class="yohann">ptimized</font> <font color="#9ACD32" class="yoda">D</font><font color="white" class="yohann">irect link to</font> <font color="#9ACD32" class="yoda">A</font><font color="white" class="yohann">pplications</font><font color="#9ACD32" class="yoda"> v<?=$yodaVersion?></font>
		</h3>
        
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
                    
                    <a class="dropdown-item" href="planning.php">Planning Support</a>
                    
                    <a class="dropdown-item" href="ajax/logout.php" style="color:red;">Deconnexion</a>
                </div>
            </div>
          </ul>
        </div> 
      </div>
    </nav>
    
    <div class="card text-center mx-auto" style="margin-top:15px;width:75%;" >
			<nav>
				<div class="nav nav-tabs" id="nav-tab" role="tablist" style="background-color:rgb(216, 216, 216);">
					<a class="nav-item nav-link active" id="nav-slot-tab" data-toggle="tab" href="#nav-slot" role="tab" aria-controls="nav-slot" aria-selected="true" style="color:black;">Créneaux Type</a>
					
					<a class="nav-item nav-link" id="nav-hotline-tab" data-toggle="tab" href="#nav-hotline" role="tab" aria-controls="nav-hotline" aria-selected="false" style="color:black;">Horaire Hotline</a>
					
				</div>
			</nav>
			<div class="tab-content" id="nav-tabContent" style="background-color:#f7f7f7">
				<div class="tab-pane fade show active col-12 my-3" id="nav-slot" role="tabpanel" aria-labelledby="nav-slot-tab" >
				    <div class="col-12 row">
    				    <div class="col-6">
    				        <table class="table table-sm table-hover table-light">
                                <thead>
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Nom</th>
                                        <th scope="col">Horaires</th>
                                        <th scope="col">Couleur</th>
                                        <th scope="col">Edition</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
    				            for($x=0; $x< $idx ; $x++):?>
                                    <tr>
                                        <td><?=$arrayConfig['code'][$x]?></td>
                                        <td><?=$arrayConfig['name'][$x]?></td>
                                        <td><?php echo $arrayConfig['start'][$x]->format('H:i') . ' - ' . $arrayConfig['stop'][$x]->format('H:i')?></td>
                                        <td><input type="color" disabled value="#<?=$arrayConfig['color'][$x]?>"></td>
                                        <td><button class="btn btn-primary"><i class="fa fa-pencil-square-o" onclick='editSlotConf(<?=$arrayConfig['name'][$x]?>)'></i></button></td>
                                    </tr>
                                
                                <?php endfor;?>
                                </tbody>
                            </table>
                            <div class="col-12">
                                <button class="btn btn-outline-success" data-toggle="modal" data-target="#createSlotModal"><i class="fa fa-plus-circle "></i> Ajouter un créneaux type</button>
                            </div>
    				        
    				    </div>
    				    <div class="col-6 row">
    				        <div class="col-6">
    				            <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Choisir Technicien
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                    </div>
                                </div>
    				        </div>
    				        <div class="col-6">
    				            <input type="text" class="form-control" placeholder="Créneau">
    				        </div>
    				        <div class="container">

</div>
    				    </div>
				    </div>
				</div>
				
				<div class="tab-pane fade col-12" id="nav-hotline" role="tabpanel" aria-labelledby="nav-hotline-tab">
				    
			</div>
			
		</div>
		</div>
		
		<!-- Modal Create Slot-->
<div class="modal fade" id="createSlotModal" tabindex="-1" role="dialog" aria-labelledby="createSlotModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ajout d'un créneaux type</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class=" row">
            <div class="col-6">
                <input type="text" class="form-control"  placeholder="Code" >
                <span style="font-size:0.6em">* 3 lettres en majuscules</span>
            </div>
            <div class="col-6">
                <input type="text" class="form-control"  placeholder="Nom" >
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="input-group date" id="datetimepickerStart" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerStart" placeholder="Début"/>
                        <div class="input-group-append" data-target="#datetimepickerStart" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <div class="input-group date" id="datetimepickerEnd" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepickerEnd" placeholder="Fin"/>
                        <div class="input-group-append" data-target="#datetimepickerEnd" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-clock-o"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <span class="col-6 text-center">Choisir une couleur : </span>
            <div class="col-6">
                <input type="color" >
            </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-success" >Valider</button>
      </div>
    </div>
  </div>
</div>
    
<script type="text/javascript">
    $(function () {
        $('#datetimepickerStart').datetimepicker({
            format: 'LT',
            locale: 'fr'
        });
    });
    $(function () {
        $('#datetimepickerEnd').datetimepicker({
            format: 'LT',
            locale: 'fr'
        });
    });
</script>

	    
	
    
    
    
	</body>
</html>