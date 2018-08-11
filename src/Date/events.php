<?php
namespace Date;

class Events{
    
    public function getEventsBetween($start, $end){
        
        try
        {
        	$bdd = new \PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST',[
        	    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        	    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        	]);
        }
        catch(Exception $e)
        {
                die('Erreur : '.$e->getMessage());
        }
        
        $sql = "SELECT USR_SURNAME, SLO_DATE, SCO_CODE FROM PLA_SLOT 
        INNER JOIN YDA_USERS ON SLO_ID_USR = USR_ID 
        INNER JOIN PLA_SLOT_CONFIG ON SLO_ID_SCO = SCO_ID
        WHERE SLO_DATE BETWEEN '{$start->format('Y-m-d')}' AND '{$end->format('Y-m-d')}'
        AND SLO_VALID = 1";
        
        $statement = $bdd->query($sql);
        $results = $statement->fetchAll();
        return $results;
    }
    
    public function getEventsBetweenByDay($start, $end){
        $events = $this->getEventsBetween($start,$end);
        $days = [];
        foreach($events as $event){
            $date = $event['SLO_DATE'];
            if (!isset($days[$date])){
                $days[$date] = [$event];
                
            }else{
                $days[$date][] = $event;
            }
        }
        
        return $days;
    }
}