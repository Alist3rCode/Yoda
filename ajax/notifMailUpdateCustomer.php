<?php

error_reporting(E_ALL);
set_time_limit(0);

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=yoda;charset=utf8', 'yoda', 'cetroxNEST');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}

$user = [];
$customer = [];
$select = $bdd->query('SELECT * FROM YDA_NOTIF INNER JOIN YDA_USERS ON NTF_ID_USR = USR_ID WHERE NTF_CREATE = 1');
$idx = 0;
    while ($query = $select->fetch()){
    
        $user[$idx]['mail'] = $query['USR_MAIL'];
        $user[$idx]['name'] = $query['USR_FIRST_NAME'];
        $user[$idx]['lastName'] = $query['USR_NAME'];
        $user[$idx]['notif'] = explode(',',$query['NTF_UPDATE']);
        $idx = $idx + 1;
    }

$select2 = $bdd->query('SELECT * FROM YDA_CLIENT WHERE CLI_UID = "'.$_REQUEST['uid'].'" AND CLI_VALID = 1');

    while ($query = $select2->fetch()){
        $customer['id'] = $query['CLI_ID'];
        $customer['ville'] = $query['CLI_VILLE'];
        $customer['nom'] = $query['CLI_NOM'];
        $customer['url'] = $query['CLI_URL'];
    }
    
    
for($y=0;$y < count($user); $y++){
    if (in_array($customer['id'],$user[$y]['notif'] )){
        // print_r($user[$y]);

     $toUser  = $user[$y]['mail'];

     // Sujet
     $subjectUser = ucfirst($customer['ville']).' - '.ucfirst($customer['nom']). ' - Mise à jour en v'.$_REQUEST['version'];

      
     $messageUser = '
     <div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;"><p>
			Bonjour '.ucfirst($user[$y]["name"]).' '.strtoupper($user[$y]["lastName"]).',
			<br>
			<br>
		</p></span>
		</font>
	</div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;"><p>
			Le client <b>'. ucfirst($customer['ville']).' - '.ucfirst($customer['nom']).'</b> vient d\'être mis à jour en <b>v'.$_REQUEST['version'].'</b>.  
			<br>
			
			Si vous souhaitez vous rendre sur l\'application de ce client, cliquez sur ce lien : <a href="'.$customer['url'].'" target="_blank">'.$customer['url'].'</a>
			<br>
			<br>
			
		    Si vous souhaitez modifier les notifications que vous recevez de la part de YODA <a href="https://maj-imaging.evolucare.com/yoda/notif.php" target="_blank">cliquez sur ce lien.</a>
		</p>
		<br>
		<br>
		
		
		<p style="color:red;"><b>Ceci est un mail automatique, merci de ne pas y répondre directement.</b></p>
		</p></span>
		</font>
	</div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;"><font color="#595959">Cordialement, </font></span>
		</font>
	</div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;">
				<font color="#595959">
					<b>Mail automatique </b>
				</font>
				<font color="#595959">
					| YODA
				</font>
			</span>
		</font>
	</div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;">
				<font color="#595959">
					<b>Groupe Evolucare Technologies</b>
				</font>
			</span>
		</font>
	</div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;"><font color="#595959">290 avenue Galillée, Parc Cézanne 2, Bat G </font></span>
		</font>
	</div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;"><font color="#595959">13857 Aix en Provence Cedex 03</font></span>
		</font>
	</div>
	<div style="background-color:white;margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;background-color:white;">
				<font color="#E36C0A">
					<b>Hotline :&nbsp;01.84.86.06.00</b><br>
					<b>Mail Support :&nbsp;<a href="mailto:support@imaging.evolucare.com">support@imaging.evolucare.com</a></b>
				</font>
				<font color="#1F497D">
					&nbsp;
				</font>
			</span>
		</font>
	</div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;"><img src="https://release-imaging.evolucare.com/upgrade/signatureYoda.png"></span>
		</font>
	</div>
</div>

     ';

     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
     $headersUser  = 'MIME-Version: 1.0' . "\r\n";
     $headersUser .= 'Content-type: text/html; charset="utf-8"' . "\r\n";

     // En-têtes additionnels
    //  $headers .= 'To: Yohann <y.lopez@evolucare.com>' . "\r\n";
     $headersUser .= 'From: YODA <noreply@mailevolucare.pictime.fr>' . "\r\n";
    //  $headers .= 'Cc: anniversaire_archive@example.com' . "\r\n";
    //  $headers .= 'Bcc: anniversaire_verif@example.com' . "\r\n";
     // Envoi
     mail($toUser, $subjectUser, $messageUser, $headersUser);
     
    //  echo $messageUser;
    
    }
}   
?>