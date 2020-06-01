<?php
	/** Créer et Envoyer un SMS avec HTTP GET ou POST
	    Parametres : 
		    key     (Requis)  La clé API de l'utilisateur du service.
			number  (Requis)  le numéro de téléphone du destinataire
			message (Requis)  le message du SMS
			
		réponse :
			la réponse est un objet JSON  par example:

				{
				  "status": "202 Accepted",
				  "numero": "+3361234567",
				  "creator": "philippe",
                  "message": "votre message",
				  "encodage": "Default_No_Compression"
				}
	**/   
 
    require_once('../definition.inc.php');
	require_once('Api.php');
	
	use Aggregator\Support\Api;
  
	
	// Contrôle de la présence des paramètres key number message en GET ou POST
	$key     = Api::obtenir("key");
    $number  = Api::obtenir("number");
    $message = Api::obtenir("message"); 

    
    // Contrôle de la clé
	// La clé doit appartenir à un utilisateur de la table users
	
	// connexion à la base data
	$bdd = Api::connexionBD(BASE);
	Api::controlerkey($bdd, $key);

    // Contrôle du numéro de téléphone destinataire
    if (strlen($number)<10 || !is_numeric($number)){
        Api::envoyerErreur(403, "Bad Request", "The request cannot be fulfilled due to bad number.");
        return;
    }
	
	// Détermination de l'encodage et de la longueur maximum du message
	// Si l'encodage en ASCII donne la même longueur que le message en UTF_8 alors le message peut être
	// envoyé en ASCII sinon il faut utiliser Unicode
	if (strlen(iconv('UTF-8', 'ASCII//IGNORE', $message)) == strlen($message)) {
        $encodage = 'Default_No_Compression';
        $longueurMax = 160;
    } else {
        $encodage = 'Unicode_No_Compression';
        $longueurMax = 80;
    }

    // Contrôle de la longueur du message
    if (strlen($message)<1 || strlen($message)> $longueurMax){
        Api::envoyerErreur(403, "Request Entity Too Large", "Your message is too large. Please reduce the size and try again.");
        return;
    }
    
	
	// Lecture du login, quotaSMS et delaySMS pour l'utilisteur dans la table users
    $sql = sprintf("SELECT * FROM `users` WHERE `users`.`User_API_Key` = %s", $bdd->quote($key));
    $stmt = $bdd->query($sql);
	$utilisateur =  $stmt->fetchObject();
	$creator = $utilisateur->login;
	$quota = $utilisateur->quotaSMS;   // Nb de sms pouvant être envoyé par jour
	$delay = $utilisateur->delaySMS;   // delai entre deux envois consécutifs pour le même utilisateur
	
	
    $message = $message;  

	// Connexion à la base SMS 
	$bdd = Api::connexionBD(BASESMS);
	
	// Contrôle du nombre de messages envoyés au cours des dernières 24h
	$sql = sprintf("SELECT count(*) AS nb FROM `sentitems` WHERE `CreatorID` = %s AND DATE(`SendingDateTime`) = DATE( NOW() )", $bdd->quote($creator));
	$stmt = $bdd->query($sql);
	$res =  $stmt->fetchObject();
	if ($res->nb >= $quota){
		Api::envoyerErreur(406, "daily quota exceeded", "You have exceeded your daily quota");
		return;
	}	

	// Contrôle du delai entre deux envois consécutifs
	// Le message précedent est-il envoyé
	$sql = sprintf("SELECT count(*) as nb FROM `outbox` where `CreatorID` = %s", $bdd->quote($creator));
	$stmt = $bdd->query($sql);
	$res =  $stmt->fetchObject();
	if ($res->nb > 0){
		Api::envoyerErreur(429, "Too Many Requests", "Wait before making another request.");
		return;
	}
	// Si oui depuis plus de delay secondes ?
	$sql = sprintf("SELECT count(*) as nb FROM `sentitems` where `CreatorID` = %s and `SendingDateTime` > DATE_SUB(NOW(), INTERVAL %s SECOND);",
		$bdd->quote($creator), $delay			
	);
	$stmt = $bdd->query($sql);
	$res =  $stmt->fetchObject();
	if ($res->nb > 0){
		Api::envoyerErreur(429, "Too Many Requests", "Wait before making another request.");
		return;
	}
	
	// Tout est OK on envoie
	$sql = sprintf("INSERT INTO outbox (DestinationNumber, TextDecoded, CreatorID, Coding) VALUES ( %s, %s, %s, %s )",
		$bdd->quote($number),
		$bdd->quote($message),
		$bdd->quote($creator),
		$bdd->quote($encodage)
	);
	$reponse = $bdd->exec($sql);
	
	
    if ($reponse !== false){
        $data = array(
                'status' => "202 Accepted",
                'numero' => $number,
                'creator' => $creator,
				'message' => $message,
				'encodage' => $encodage
            );

        header('HTTP/1.1 202 Accepted');
        header('content-type:application/json');
		// L'entête- Access-Control-Allow-Origin  indique que la ressource peut être partagée 
		header('Access-Control-Allow-Origin: *');
		
        echo json_encode($data);
		echo "\n";
    }
    else{
        Api::envoyerErreur(500, "Internal Server Error", "Internal Server Error");
    }
