<?php
    header('content-type: text/css');//Déclare la page en tant que feuille de style
    try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$arraySlot = []; 
$arrayPlanning = [];
$arrayTech = [];

$idx = 0;
$y = 0;
    $selectUser = $bdd->query('SELECT USR_SURNAME FROM YDA_USERS WHERE USR_TECH = 1 AND USR_DELETE IS NULL ORDER BY USR_ID ASC');
        
    while($queryUSer = $selectUser->fetch()){
        $arrayTech['trigramme'][$y] = $queryUser['USR_SURNAME'];
        $y += 1;
    }

	$select = $bdd->query('SELECT * FROM PLA_SLOT_CONFIG WHERE SCO_VALID = 1');

    while ($query = $select->fetch()){
    
       $arraySlot['name'][$idx] = $query['SCO_NAME'];
       $arraySlot['code'][$idx] = $query['SCO_CODE'];
       $arraySlot['start'][$idx] = date_create_from_format('H:i:s', $query['SCO_START']);
       $arraySlot['stop'][$idx] = date_create_from_format('H:i:s', $query['SCO_STOP']);
       $arraySlot['color'][$idx] = $query['SCO_COLOR'];
       
       $idx +=1;
    }
    
    $select3 = $bdd->query('SELECT count(*) FROM YDA_USERS WHERE USR_TECH = 1 AND USR_DELETE IS NULL');

    while ($query3 = $select3->fetch()){
    
       $nbTech = $query3['count(*)'];
    }
    
    $select2 = $bdd->query('SELECT * FROM PLA_CONFIG');

    while ($query2 = $select2->fetch()){
        
       $arrayPlanning['start'] = date_create_from_format('H:i:s', $query2['PCO_START_PLANNING']);
       $arrayPlanning['stop'] = date_create_from_format('H:i:s', $query2['PCO_STOP_PLANNING']);
       $workingDays = explode(',' , $query2['PCO_WEEKDAY']);
    //   var_dump($arrayPlanning['start']);
        $diff = $arrayPlanning['stop']->diff($arrayPlanning['start']);
        $workingTime = $diff->h;
        $workingTime = intval($workingTime) * 2;
        if ($diff->i == 30){
            $workingTime += 1;
        }
    
    }
    
    
?>
 
.grid{
    display: grid;
    grid-template-columns: repeat(<?=$workingTime?>, 1fr);
    grid-template-rows : repeat(<?=$nbTech?>, 3fr) 1fr;
    border : 1px solid #999;
    background :white;
    font-size:0.9em;
}

.tech{
    padding-left: 5px;
}
<?php 
for($i = 0; $i< $idx; $i++):
    
    $start = $arrayPlanning['start']->diff($arraySlot['start'][$i]);
    $startDay = $start->h;
    $startDay = intval($startDay) * 2;
    if ($start->i == 30){
        $startDay += 1;
    }
    
    
    $long = $arraySlot['stop'][$i]->diff($arraySlot['start'][$i]);
    $longDay = $long->h;
    $longDay = intval($longDay) * 2;
    if ($long->i == 30){
        $longDay += 1;
    }
?>
.<?=$arraySlot['code'][$i]?>{
    background-color:#<?=$arraySlot['color'][$i]?>;
    grid-column: <?=$startDay . ' / span ' . $longDay?>;
    
}

<?php endfor;?>


.YLP{
    grid-row : 1;
}

.JSA{
    grid-row : 2;
}

.hour{
    grid-row-start:<?=intval($nbTech) + 1?>;
    grid-column: 1/ <?=$workingTime + 1 ?>;
    opacity:0.9;
    display:grid;
    grid-template-columns : repeat(3, 1fr);
    font-size:0.3em;
    
}
.start{
    grid-column-start: 1;
    grid-row-start : 1;
    /*font-size: 0.2em;*/
    text-align : left;
    
}

.middle{
    grid-column-start: 2;
    grid-row-start : 1;
    /*font-size: 0.2em; */
    text-align:center;
    
}
.end{
    grid-column-start: 3;
    grid-row-start : 1;
    /*font-size: 0.2em; */
    text-align: right;
    
}