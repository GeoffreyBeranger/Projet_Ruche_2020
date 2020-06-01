<?php

    // Contrôle des variables de sessions
	include "../administration/authentification/authcheck.php" ;
   
	require_once('../definition.inc.php');
	require_once('Api.php');

	use Aggregator\Support\Api;	
    
	// Lecture des paramètres requis
	$id	          = Api::obtenir("id", FILTER_VALIDATE_INT);
	$key          = Api::obtenir("key");
	$User_API_Key = Api::obtenir("User_API_Key");
	
	$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);
	// Contrôle de la clé User_API_Key
	Api::controlerkey($bdd, $User_API_Key);
	
	$sql = sprintf("UPDATE `data`.`channels` SET `write_api_key` = %s WHERE `channels`.`id` = %s;",
		$bdd->quote($key),
		$bdd->quote($id)
	);
	
	$nb = $bdd->exec($sql);
	 
	if($nb == 1){
		
		$data = array(
                'status' => "200 OK",
            );

        header('HTTP/1.1 200 OK');
        header('content-type:application/json');
        echo json_encode($data);
	}
	else{
        Api::envoyerErreur(500, "Internal Server Error", "Internal Server Error");
    }
    


?>