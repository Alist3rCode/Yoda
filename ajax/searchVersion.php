<?php
header("content-type:application/json");
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
// print_r($_REQUEST['search']);
$array =[];
$flagActi = 0; 
if (in_array("1", $_REQUEST['search'])) {
    // echo "yeah";
    $flagActi = 1;
    $selectRis = $bdd->query('SELECT CLI_ID FROM YDA_CLIENT WHERE CLI_RIS = "1" AND CLI_PACS = "0" AND CLI_VALID = "1"');
    
        while ($query = $selectRis->fetch()){
            array_push($array, $query['CLI_ID']); 
        }
    // print_r($array);
}
if (in_array("2", $_REQUEST['search'])) {
    $flagActi = 1;

    $selectPacs = $bdd->query('SELECT CLI_ID FROM YDA_CLIENT WHERE CLI_RIS = 0 AND CLI_PACS = 1 AND CLI_VALID = 1');
    
        while ($query = $selectPacs->fetch()){
            array_push($array, $query['CLI_ID']); 
        }
    
}
if (in_array("0", $_REQUEST['search'])) {
    $flagActi = 1;

    $selectNada = $bdd->query('SELECT CLI_ID FROM YDA_CLIENT WHERE CLI_RIS = 0 AND CLI_PACS = 0 AND CLI_VALID = 1');
    
        while ($query = $selectNada->fetch()){
            array_push($array, $query['CLI_ID']); 
        }
    
}

if (in_array("3", $_REQUEST['search'])) {
    $flagActi = 1;

    $selectRisPacs = $bdd->query('SELECT CLI_ID FROM YDA_CLIENT WHERE CLI_RIS = 1 AND CLI_PACS = 1 AND CLI_VALID = 1');
    
        while ($query = $selectRisPacs->fetch()){
            array_push($array, $query['CLI_ID']); 
        }
    
}
if ($flagActi == 0){
    for ($x = 0; $x < count($_REQUEST['search']); $x++){
    
        $select = $bdd->query('SELECT CLI_ID FROM YDA_CLIENT WHERE CLI_NUM_VERSION = "' . $_REQUEST['search'][$x] . '" AND CLI_UID IS NULL AND CLI_VALID = 1');
        
            while ($query = $select->fetch()){
                array_push($array, $query['CLI_ID']); 
            }
        
        
        $select3 = $bdd2->query('SELECT uid FROM wrk_client where concat(version, ".", hotfix) ="' . $_REQUEST['search'][$x] . '"');
            
            while ($query3 = $select3->fetch()){
                // echo $query3['uid'];
                $select2 = $bdd->query('SELECT CLI_ID FROM YDA_CLIENT WHERE CLI_UID = "' . $query3['uid'] . '" AND CLI_VALID = 1');
        
                    while ($query2 = $select2->fetch()){
                        // echo $query2['CLI_ID'] ;
                        array_push($array, $query2['CLI_ID']);                                           
                    }
            }
                
        
    }
}
    
    echo json_encode($array);
    
?>