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
checkCookie('planning.php');


	$select = $bdd->query('SELECT USR_TECH, USR_DIRECTION, USR_REFERING
	                        FROM YDA_USERS 
	                        WHERE USR_ID ="'.$_SESSION["id_user"].'"');

    while ($query = $select->fetch()){
    
        if($query['USR_TECH'] == 1 || $query['USR_DIRECTION'] == 1){
            $planningAccess = 1;
        }else{
            $planningAccess = 0;
            header('Location: index.php');
        }
        
        if ($query['USR_REFERING'] == 1 || $query['USR_DIRECTION'] == 1 ){
            $configPlanningAccess = 1; 
        }else{
            $configPlanningAccess = 0; 
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

    while ($query3 = $select3->fetch()){
    
       $arrayConfig['name'][$idx] = $query3['SCO_NAME'];
       $arrayConfig['code'][$idx] = $query3['SCO_CODE'];
       $arrayConfig['start'][$idx] = date_create_from_format('H:i:s', $query3['SCO_START']);
       $arrayConfig['stop'][$idx] = date_create_from_format('H:i:s', $query3['SCO_STOP']);
       $arrayConfig['color'][$idx] = $query3['SCO_COLOR'];
       
       $idx +=1;
    }
    
    $select3 = $bdd->query('SELECT count(*) FROM YDA_USERS WHERE USR_TECH = 1 AND USR_DELETE IS NULL');

    while ($query3 = $select3->fetch()){
    
       $nbTech = $query3['count(*)'];
    }

	    


?>


<!doctype html>
<html>
	<header>
		<meta charset="utf-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="css/calendar.css">
		<link rel="stylesheet" href="css/planning_css.php">
		<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<meta name="description" content="Bookmarks for all the ETMI Customer Application. For RIS and PACS. Internal user Only !">
        <meta name="author" content="Yohann LOPEZ">
        <title>Planning Support</title>
     <link rel="icon" type="image/png" href="./img/yoda.png" />

	</header>
	<body background="css/Vignettes/Background.jpg" style="overflow-x:hidden;">
	    <div class="d-none" id="nbTech"><?=$nbTech?></div>
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

		<?php
		require '/var/www2/yoda/src/Date/month.php';
		require '/var/www2/yoda/src/Date/events.php';
		
		$events = new Date\Events();
		
		if(!isset($_GET['month']) || $_GET['month'] == ''){
		    $monthGet = null;
		}else{
		    $monthGet = $_GET['month'];
		}
		
		if(!isset($_GET['year']) || $_GET['year'] == ''){
		    $yearGet = null;
		}else{
		    $yearGet = $_GET['year'];
		}
		
		try{
		    $month = new Date\Month($monthGet, $yearGet);
		}catch (\Exception $e){
		    $month = new Date\Month();
		}
		$weeks = $month->getWeeks();
		$start = $month->getStartingDay();
		$start = $start->format('N') === '1' ? $start : $month->getStartingDay()->modify('last monday');
		$startClone = clone $start;
		$end = $startClone->modify("+". (6 + 7 * ($weeks -1)). " days");
		$events = $events->getEventsBetweenByDay($start,$end);
// 		var_dump($events);
        // $workingDays = $month->getWorkingDays();
        // var_dump($workingDays);
		
		?>
		<div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
		    <h1 style="color:white;"><?=$month->toString();?></h1>
		    <div>
		        <?php if($configPlanningAccess == 1):?><a href="configPlanning.php" class="btn btn-light"><i class="fa fa-gear"></i></a><?php endif;?>
    		    <a href="planning.php?month=<?=$month->previousMonth()->month;?>&year=<?=$month->previousMonth()->year;?>" class="btn btn-primary">&lt;</a>
    		    <a href="planning.php?month=<?=$month->nextMonth()->month;?>&year=<?=$month->nextMonth()->year;?>" class="btn btn-primary">&gt;</a>
		    </div>
		</div>
		
		    
		  
	    <table class="calendar__table calendar__table--<?=$weeks?>weeks">
	        <?php for($i =0; $i< $weeks;$i++):?>
	        <tr>
	            <?php foreach($workingDays as $k =>$day):
	            $startClone2 = clone $start;
	            $date = $startClone2->modify("+". ($k + $i * 7). " days");
	            if(isset($events[$date->format("Y-m-d")])){
	                $eventForDay = $events[$date->format("Y-m-d")];
	            }else{
	                $eventForDay = [];
	            }
	            ?>
	            
	            <td class="<?= $month->withinMonth($date) ? '' : 'calendar__othermonth'; ?>">
	                <div class="d-flex flex-row align-items-center justify-content-between">
	                    <?php if($i ==0):?>
	                    <div class="calendar__weekday"><?=$day?></div>
    	                <?php endif;?>
    	                <div class="calendar__day text-right <?php if($date->format('Y-m-d') === date("Y-m-d")){echo 'today';}?>" ><?= $date->format('d');?></div>
	                </div>
	                
	                <div class="grid" id='<?php if($date->format('Y-m-d') === date("Y-m-d")){echo 'today';}?>'>
	                    <?php foreach($eventForDay as $event):?>
	                    <div class="<?=$event['SCO_CODE']?> tech <?=$event['USR_SURNAME']?>"><?php echo $event['USR_SURNAME'] .' - '. $event['SCO_CODE'];?></div>
	                    
	                    <?php endforeach;?>
	                    <div class="hour mx-sm-1">
	                        <div class="start"><?=$arrayPlanning['start']->format('H:i');?></div>
	                        <div class="startJquery d-none"><?=$arrayPlanning['start']->format('H');?></div>
	                        <div class="middle">12</div>
	                        <div class="end"><?=$arrayPlanning['stop']->format('H:i');?></div>
	                        
	                    </div>
	                </div>
	           </td>
	            <?php endforeach;?>
	        </tr>
	            
	        <?php endfor;?>
	    </table>
	    
	       <script  src="https://code.jquery.com/jquery-3.2.1.js"  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="   crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    
    <script>
    
    function redBarFunction() {
        $('.redBar').remove();
        var time = new Date().toLocaleTimeString('fr-FR', { hour: "numeric", minute:"numeric"});
        var timeSplit = time.split(':');
        var startTime = Number(document.getElementsByClassName('startJquery')[0].innerHTML);
        var hour = timeSplit[0]-startTime + 1;
        var nbTech = document.getElementById('nbTech').innerHTML;
        // console.log(startTime);
        $( "#today" ).append( '<div class="redBar" style="grid-row:1/'+ (parseInt(nbTech)+1) +';grid-column:'+ 2 * hour +';"></div>' );
        console.log();
    }
    redBarFunction();
    setInterval(redBarFunction, 30*1000);
    
        
    </script>
	</body>
</html>