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
$admin = [];
$select = $bdd->query('SELECT * FROM YDA_USERS WHERE USR_ID="' . $_REQUEST['id'] . '"');

    while ($query = $select->fetch()){
    
        $user['mail'] = $query['USR_MAIL'];
        $user['name'] = $query['USR_FIRST_NAME'];
        $user['lastName'] = $query['USR_NAME'];
        $user['password'] = $_REQUEST['password'];
        
    }
    
    
    // destinataire utilisateur
     $toUser  = $user['mail'];

     // Sujet
     $subjectUser = 'Réinitialisation du mot de passe de votre compte YODA';

      
     $messageUser = '
     <div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;"><p>
			Bonjour '.ucfirst($user["name"]).' '.strtoupper($user["lastName"]).', 
		</p></span>
		</font>
	</div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;"><p>Vous recevez cet eMail car vous venez de réinitaliser votre mot de passe pour la plateforme YODA.</p></span>
		</font>
	</div>
	<div style="margin:0;">
		<font face="Calibri,sans-serif" size="2">
			<span style="font-size:11pt;"><p>
			Voici vos nouveaux identifiants personnels, il est fortement recommandé de changer de mot de passe lors de votre connexion.
			<br>
			<br>
			Login : 
			<b>'.$user["mail"].'</b>
			<br>
			Mot de passe : 
			<b>'.$mdp.'</b>
		</p>
		<p>
			Pour rappel, voici l\'adresse de la plateforme : 
			<br>
			<a href="https://maj-imaging.evolucare.com/yoda" target="_blank">https://maj-imaging.evolucare.com/yoda</a>
		</p>
		
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
			<span style="font-size:11pt;"><img data-imagetype="AttachmentByCid" originalsrc="cid:image001.png@01D39527.EDF20240" data-custom="AAMkADA1NjEzOTNjLTM4ZGYtNDg3MC05OGRmLTk4ZDFkZGVhYWNlYwBGAAAAAAAA7mQp2g%2FQRY%2BMq3dOiMq9BwAdOsclH4JiRKfj6TKmrRG4AAAAAAEJAAAdOsclH4JiRKfj6TKmrRG4AAAAATIpAAABEgAQAAhHhIvL2ZdAo8NAwcmERLQ%3D" naturalheight="0" naturalwidth="0" src="https://outlook.office365.com/owa/service.svc/s/GetFileAttachment?id=AAMkADA1NjEzOTNjLTM4ZGYtNDg3MC05OGRmLTk4ZDFkZGVhYWNlYwBGAAAAAAAA7mQp2g%2FQRY%2BMq3dOiMq9BwAdOsclH4JiRKfj6TKmrRG4AAAAAAEJAAAdOsclH4JiRKfj6TKmrRG4AAAAATIpAAABEgAQAAhHhIvL2ZdAo8NAwcmERLQ%3D&amp;X-OWA-CANARY=Gtjas-PUz0SwOlZSV26LAvCxugrni9UYrHNr7O6j7RgY55f43RrXKW-gw-BbDNzLlEkFc_P8smY.&amp;isImagePreview=True" width="375" height="43" alt="1464018038031_PastedImage" id="Image_x0020_1" data-downloadimage="service.svc/s/GetFileAttachment?id=AAMkADA1NjEzOTNjLTM4ZGYtNDg3MC05OGRmLTk4ZDFkZGVhYWNlYwBGAAAAAAAA7mQp2g%2FQRY%2BMq3dOiMq9BwAdOsclH4JiRKfj6TKmrRG4AAAAAAEJAAAdOsclH4JiRKfj6TKmrRG4AAAAATIpAAABEgAQAAhHhIvL2ZdAo8NAwcmERLQ%3D&amp;X-OWA-CANARY=Gtjas-PUz0SwOlZSV26LAvCxugrni9UYrHNr7O6j7RgY55f43RrXKW-gw-BbDNzLlEkFc_P8smY." data-thumbnailimage="service.svc/s/GetFileAttachment?id=AAMkADA1NjEzOTNjLTM4ZGYtNDg3MC05OGRmLTk4ZDFkZGVhYWNlYwBGAAAAAAAA7mQp2g%2FQRY%2BMq3dOiMq9BwAdOsclH4JiRKfj6TKmrRG4AAAAAAEJAAAdOsclH4JiRKfj6TKmrRG4AAAAATIpAAABEgAQAAhHhIvL2ZdAo8NAwcmERLQ%3D&amp;X-OWA-CANARY=Gtjas-PUz0SwOlZSV26LAvCxugrni9UYrHNr7O6j7RgY55f43RrXKW-gw-BbDNzLlEkFc_P8smY.&amp;isImagePreview=True" style=""></span>
		</font>
	</div>
</div>

     ';

     // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
     $headersUser  = 'MIME-Version: 1.0' . "\r\n";
     $headersUser .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

     // En-têtes additionnels
     $headersUser .= 'To: '.ucfirst($user["name"]).' '.strtoupper($user["lastName"]).' <'.$user["mail"].'> '. "\r\n";
     $headersUser .= 'From: K-9 - YODA <noreply@maj-imaging.evolucare.com>' . "\r\n";
     
     // Envoi
    //  echo $messageUser;
     mail($toUser, $subjectUser, $messageUser, $headersUser);
     
     

?>