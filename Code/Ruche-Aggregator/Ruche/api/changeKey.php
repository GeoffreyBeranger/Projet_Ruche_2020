<?php
    // Contrôle des variables de sessions
	include "../administration/authentification/authcheck.php" ;
   
	require_once('../definition.inc.php');
	require_once('Api.php');	
	
	use Aggregator\Support\Api;
    
	$id	          = Api::obtenir("id", FILTER_VALIDATE_INT);
	$key       	  = Api::obtenir("key");
	$User_API_Key = Api::obtenir("User_API_Key");
	
	$bdd = Api::connexionBD(BASE);
	// Contrôle de la clé API
	Api::controlerkey($bdd, $User_API_Key);
	
	$sql = sprintf("UPDATE `data`.`users` SET `User_API_Key` = %s WHERE `users`.`id` = %s;",
		$bdd->quote($key),
		$bdd->quote($id)
	);
	
	$nb = $bdd->exec($sql);
	 
	if($nb == 1){
		
		$data = array(
                'status' => "202 Accepted",
            );

        header('HTTP/1.1 202 Accepted');
        header('content-type:application/json');
        echo json_encode($data);
	}
	else{
        Api::envoyerErreur(500, "Internal Server Error", "Internal Server Error");
    }
    



?>