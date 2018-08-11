<?php



function checkCookie($page){
    
    try
    {
    	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'root', 'cetroxNEST');
    }
    catch(Exception $e)
    {
            die('Erreur : '.$e->getMessage());
    }
    

    if (isset($_COOKIE['yoda'])){
        $cookie = $_COOKIE['yoda'];
        $parts = explode('====', $cookie);
        $id_user = $parts[0];
        $delete = 'fuck';
        
        $select = $bdd->query('SELECT USR_DELETE FROM YDA_USERS WHERE USR_ID ="'.$id_user.'"');
        
        while ($query = $select->fetch()){
        
            $delete = $query['USR_DELETE'];
           
        }
        
        // if($delete === null){
            
        
            $select = $bdd->query('SELECT * FROM YDA_SESSION WHERE SES_ID_USR ="'.$id_user.'"');
            $sessionCookie = [];
            while ($query = $select->fetch()){
            
                $sessionCookie['token'] = $query['SES_TOKEN'];
                $sessionCookie['timeout'] = new Datetime($query['SES_TIMEOUT']);
               
            }
            
            
            
            $expected = $id_user . '====' . $sessionCookie['token'] . '====' . $sessionCookie['timeout']->format('Y-m-d H:i:s');
            $now = new DateTime(date('Y-m-d H:i:s'));
            $interval = $now->diff($sessionCookie['timeout']);
        
        
        
            if ($expected == $cookie && intval($interval->format('%R%a')) > 0 ){
                $_SESSION['login'] = 'ok';
                $_SESSION['id_user'] = $id_user; 
                $_SESSION['token'] = $sessionCookie['token'];
                $now->modify('+4 days');
                $_SESSION['timeout'] = $now->format('Y-m-d H:i:s');
            
                $req = $bdd->prepare('UPDATE YDA_SESSION SET SES_TIMEOUT = :timeout WHERE SES_ID_USR = :id_user');
            
                $req->execute(array(
                'id_user' => $id_user,
                'timeout' => $now->format('Y-m-d H:i:s')
                )) or die(print_r($bdd->errorCode()));
            }
                
        }
        
        if(!isset($_SESSION['login']) || $_SESSION['login'] != 'ok'){
            
            header('Location: login.php?redirect='.$page);
            
        }else if(!isset($_SESSION['id_user']) || $_SESSION['id_user'] == ''){
           
           header('Location: login.php?redirect='.$page);
            
        }else{
            setcookie('yoda', $_SESSION['id_user'] . '====' . $_SESSION['token'] . '====' . $_SESSION['timeout'], time() + 60 * 60 * 24 * 4);
            // echo "<p style='color:white;'>Création cookie OK, veuillez fournir cette variable à Yohann : ". 'plop' ."</p>";
        }
    // }else{
    // //     header('Location: logout.php');
    //     echo 'plop du cul';
    // }
}