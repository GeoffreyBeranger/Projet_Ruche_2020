<?php

    // Contrôle des variables de sessions
	include "../administration/authentification/authcheck.php" ;    
   
	require_once('../definition.inc.php');
	require_once('Api.php');

    use Aggregator\Support\Api;	
	
	// Lecture des paramétres obligatoires
	$channelId = Api::obtenir("channelId", FILTER_VALIDATE_INT);

	// Connexion à la base avec session heure UTC
	$bdd = Api::connexionBD(BASE, "+00:00");
	
	$sql = sprintf("DELETE FROM `data`.`feeds` WHERE `feeds`.`id_channel` = %s",
	       $channelId);
	
	$nb = $bdd->exec($sql);
	 
	if($nb > 0){
		
		$sql = sprintf("UPDATE `channels` SET `last_write_at`= NULL,`last_entry_id`= NULL WHERE `id`= %s",
			   $channelId);
		$nb = $bdd->exec($sql);
		
        if($nb > 0){		
			$data = array(
					'status' => "202 Accepted",
					'channel' => $channelId, 
				);

			header('HTTP/1.1 202 Accepted');
			header('content-type:application/json');
			echo json_encode($data);
		}	
	}
?>