<?php

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ERROR | E_WARNING | E_PARSE);

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
    
    
$i = $_GET['id'];
$version = '';


$select = $bdd->query('SELECT CLI_VERSION, CLI_URL FROM YDA_CLIENT WHERE CLI_ID ="' . $i .'"');
                    
    while ($query = $select->fetch()){
        $version = $query['CLI_VERSION'];
        $url = $query['CLI_URL'];
    }
                    
$ifPhone = 0;
           
$select2 = $bdd->query('SELECT COUNT(*) FROM YDA_PHONE WHERE PHO_ID_CLI ="' . $i .'" AND PHO_VALID =  1');
    while ($phone = $select2->fetch()){
        $ifPhone = $phone['COUNT(*)'];
    }
  
  
$TV=0;
$select = $bdd->query('SELECT USR_TEAMVIEWER FROM YDA_USERS WHERE USR_ID ="'.$_REQUEST["user"].'"');

while ($query = $select->fetch()){

    $TV = $query['USR_TEAMVIEWER'];
   
}
  
    
    
    

function DECtoDMS_LAT($dec)
{

// Converts decimal longitude / latitude to DMS
// ( Degrees / minutes / seconds ) 

// This is the piece of code which may appear to 
// be inefficient, but to avoid issues with floating
// point math we extract the integer part and the float
// part by using a string function.

    $vars = explode(".",$dec);
    $deg = $vars[0];
    if (substr($deg, 0,1) == '-'){
        $letter = 'S';
        $deg = substr($deg, 1);
    }else{
        $letter = 'N';
    }
    
    $tempma = "0.".$vars[1];

    $tempma = $tempma * 3600;
    $min = floor($tempma / 60);
    $sec = $tempma - ($min*60);

    return $result = $deg . '°' . $min . '\'' . $sec.$letter;
   
    // return array("deg"=>$deg,"min"=>$min,"sec"=>$sec);
}   

function DECtoDMS_LON($dec)
{

// Converts decimal longitude / latitude to DMS
// ( Degrees / minutes / seconds ) 

// This is the piece of code which may appear to 
// be inefficient, but to avoid issues with floating
// point math we extract the integer part and the float
// part by using a string function.

    $vars = explode(".",$dec);
    $deg = $vars[0];
    if (substr($deg, 0,1) == '-'){
        $letter = 'W';
        $deg = substr($deg, 1);
    }else{
        $letter = 'E';
        
    }
    $tempma = "0.".$vars[1];

    $tempma = $tempma * 3600;
    $min = floor($tempma / 60);
    $sec = $tempma - ($min*60);

    return $result = $deg . '°' . $min . '\'' . $sec.$letter;
   
    // return array("deg"=>$deg,"min"=>$min,"sec"=>$sec);
}   
?>   
    
<div class="collapsePhone" id="phone_<?=$i?>">
    <div class="phoneWithLink<?=$version?>">
        
            <?php $select3 = $bdd->query('SELECT * FROM YDA_PHONE JOIN YDA_CLIENT ON PHO_ID_CLI = CLI_ID WHERE PHO_ID_CLI ="' . $i .'" AND PHO_VALID = 1');
                        $idx = intval($ifPhone);
                        $first = 0;
                        while ($nbPhone = $select3->fetch()):?>
                        
                        
                        <?php $idPhone = $nbPhone['PHO_ID'];
                            $select4 = $bdd->query('SELECT MPS_LAT, MPS_LON FROM YDA_MAPS WHERE MPS_ID_PHO ="' . $idPhone .'"');
                            $arrayLat = [];
                            $strLat = '';
                            $arrayLon = [];
                            $strLon = '';
                            while ($GPS = $select4->fetch()){
                                
                                $strLat = $GPS['MPS_LAT'];
                                // var_dump(DECtoDMS($strLat));
                                // $arrayLat[1] = str_split($arrayLat[1], 2);
                                // $strLat = $arrayLat[0] . '°' . $arrayLat[1][0] . '\'' . $arrayLat[1][1] . '.'  . $arrayLat[1][2];
                                
                                // $arrayLon = explode('.', $GPS['MPS_LON']);
                                // $arrayLon[1] = str_split($arrayLon[1], 2);
                                $strLon = $GPS['MPS_LON'];
                                // $arrayLon[0] . '°' . $arrayLon[1][0] . '\'' . $arrayLon[1][1] . '.'  . $arrayLon[1][2];
                            }
                        ?>
        <div style="display:flex;flex-flow:row wrap;">
            
            <div class='phoneNumber col-md-12'>
                <?php if($nbPhone['PHO_SITE'] != ''){echo $nbPhone['PHO_SITE'] . '<br>';}?>
                <a href='tel:<?=$nbPhone['PHO_PHONE'];?>'><?=$nbPhone['PHO_PHONE']?></a>
                
            
                <div class="btn-group col-md-12" role="group" style="padding-left: 0;">
                    <!--<a href="https://www.google.fr/maps/search/<?=DECtoDMS_LAT($strLat)?>+<?=DECtoDMS_LON($strLon)?>/" target="_blank">-->
                        <a href="https://www.google.fr/maps/search/<?=DECtoDMS_LAT($strLat)?>+<?=DECtoDMS_LON($strLon)?>/" target="_blank" class="btn btn-sm <?php if($nbPhone['CLI_VERSION'] == 'v6'){echo 'btn-warning';}else{echo 'btn-primary';}?> iconMaps">
                            <i class=" fa fa-location-arrow"></i>
                        </a>
                    <!--</a>-->
                    <?php if($first == 0):?>
                        
                        <a href="<?=$url?>" target="_blank" class="btn btn-sm btn-success">
                            <i class=" fa fa-desktop"></i>
                        </a>
                        <?php $first = 1; ?>
                    
                    <?php elseif(trim($nbPhone['PHO_TX']) == '' || $nbPhone['PHO_TX'] == 'NULL' || is_null($nbPhone['PHO_TX'])):?>
                        
                        <button class="btn btn-sm btn-danger" disabled>
                            <i class=" fa fa-desktop"></i>
                            
                        </button>
                    <?php elseif(trim($nbPhone['PHO_TX']) !== '' && $nbPhone['PHO_TX'] != 'NULL' && !is_null($nbPhone['PHO_TX'])):?>
                        
                        <a href="<?=$nbPhone['PHO_TX']?>" target="_blank" class="btn btn-sm btn-success">
                            <i class=" fa fa-desktop"></i>
                        </a>
                            
                    <?php endif;?>
                    
                    <?php if(trim($nbPhone['PHO_MAIL']) != '' && $nbPhone['PHO_MAIL'] != 'NULL'):?>
                        
                        <a href='mailto:<?=$nbPhone['PHO_MAIL'];?>' class="btn btn-sm btn-success">
                            <i class="fa fa-envelope"></i> 
                        </a>
                    <?php elseif(trim($nbPhone['PHO_MAIL']) == '' || $nbPhone['PHO_MAIL'] == 'NULL'|| is_null($nbPhone['PHO_MAIL'])):?>
                        <button class="btn btn-sm btn-danger" disabled >
                            <i class="fa fa-envelope"></i>
                        </button>
                    <?php endif;?>
                    <!--teamviewer-->
                    <?php if(trim($nbPhone['PHO_TV_ID']) != '' && $nbPhone['PHO_TV_ID'] != 'NULL' && $TV == 1):?>
                    <a href='teamviewer10://control?device=<?=$nbPhone['PHO_TV_ID'];?>' class="rounded-circle">
                            <div style="background:dodgerBlue;width:32px;;height:32px;border-top-right-radius:.2rem;border-bottom-right-radius:.2rem;padding-top:2px;padding-left:2px;" >
                                <div class="rounded-circle" style="background:white;width:28px;height:28px;">
                                    <i class="fa fa-arrows-h" style="padding-left:6px; padding-top:6px"></i> 
                                </div>
                            </div>
                        </a>
                    <?php elseif(trim($nbPhone['PHO_TV_ID']) == '' || $nbPhone['PHO_TV_ID'] == 'NULL'|| is_null($nbPhone['PHO_TV_ID']) || $TV == 0):?>
                        
                   
                    <?php endif;?>
                            
                        
                </div>
                            
            </div>
        </div>
                        <?php //if (($idx - 1) > 0){echo '<hr>'; $idx = $idx - 1;}?>
        
        <?php endwhile;?>
    </div>
</div>