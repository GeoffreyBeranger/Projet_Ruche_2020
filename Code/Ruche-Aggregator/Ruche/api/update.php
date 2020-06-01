<?php
	/** Write Data Update channel data with HTTP GET or POST
	    Parameters : 
			api_key     (Required) Write API Key for this specific channel.
			field<X>    (Optional) Field X data, where X is the field ID
			created_at  (Optional) Date when feed entry was created, in ISO 8601 format
			lat			(Optional) Latitude in degrees
			long		(Optional) Longitude in degrees
			elevation   (Optional) Elevation in meters
			status		(Optional) Status update message
			
		reponse :
			The response is a JSON object of the new entry, for example:

				{
				  "channel_id": 3,
				  "field1": '73',
				  "field2": null,
				  "field3": null,
				  "field4": null,
				  "field5": null,
				  "field6": null,
				  "field7": null,
				  "field8": null,
				  "created_at": '2014-02-25T14:13:01-05:00',
				  "entry_id": 320,
				  "status": null,
				  "latitude": null,
				  "longitude": null,
				  "elevation": null
				}

	
	**/

	require_once('../definition.inc.php');
	require_once('Api.php');

	use Aggregator\Support\Api;

    // fonction pour obtenir la date UTC	
	function ObtenirDateUTC(){
		$dt = new DateTime();
		$dt->setTimeZone(new DateTimeZone('UTC'));
        return $dt->format('Y-m-d H-i-s');
	}
	
	
    // Lecture des paramètres obligatoires
	$api_key = Api::obtenir("api_key");
	
	// Lecture des paramètres facultatifs
	$val1      = Api::verifier("field1", FILTER_VALIDATE_FLOAT);
	$val2      = Api::verifier("field2", FILTER_VALIDATE_FLOAT);
	$val3      = Api::verifier("field3", FILTER_VALIDATE_FLOAT);
	$val4      = Api::verifier("field4", FILTER_VALIDATE_FLOAT);
	$val5      = Api::verifier("field5", FILTER_VALIDATE_FLOAT);
	$val6      = Api::verifier("field6", FILTER_VALIDATE_FLOAT);
	$val7      = Api::verifier("field7", FILTER_VALIDATE_FLOAT);
	$val8      = Api::verifier("field8", FILTER_VALIDATE_FLOAT);
	$status    = Api::verifier("status");
	$lat       = Api::verifier("lat"   , FILTER_VALIDATE_FLOAT);
	$long      = Api::verifier("long"  , FILTER_VALIDATE_FLOAT);
	$elevation = Api::verifier("elevation", FILTER_VALIDATE_FLOAT);
	$date      = Api::facultatif("created_at", ObtenirDateUTC());
	
	$flag = true;
	//connexion à la base data
	
	$bdd = Api::connexionBD(BASE, "+00:00");
		
	$channel = Api::obtenirChannel($bdd, $api_key);
	
	$colonnes =  "(`id_channel`";
	$valeurs  =  " VALUES (". $channel->id;
	
	if ($channel->field1 !== "" && $val1 !== NULL) { $colonnes .= ", `field1`"; $valeurs .= ", ".$val1; $flag=false;}
	if ($channel->field2 !== "" && $val2 !== NULL) { $colonnes .= ", `field2`"; $valeurs .= ", ".$val2; $flag=false;}
	if ($channel->field3 !== "" && $val3 !== NULL) { $colonnes .= ", `field3`"; $valeurs .= ", ".$val3; $flag=false;}
	if ($channel->field4 !== "" && $val4 !== NULL) { $colonnes .= ", `field4`"; $valeurs .= ", ".$val4; $flag=false;}
	if ($channel->field5 !== "" && $val5 !== NULL) { $colonnes .= ", `field5`"; $valeurs .= ", ".$val5; $flag=false;}
	if ($channel->field6 !== "" && $val6 !== NULL) { $colonnes .= ", `field6`"; $valeurs .= ", ".$val6; $flag=false;}
	if ($channel->field7 !== "" && $val7 !== NULL) { $colonnes .= ", `field7`"; $valeurs .= ", ".$val7; $flag=false;}
	if ($channel->field8 !== "" && $val8 !== NULL) { $colonnes .= ", `field8`"; $valeurs .= ", ".$val8; $flag=false;}
	if ($status !== NULL) 						   { $colonnes .= ", `status`"; $valeurs .= ", ".$bdd->quote($status); $flag=false;}
	if ($lat    !== NULL) 						   { $colonnes .= ", `latitude`";  $valeurs .= ", ".$lat; $flag=false;}
	if ($long   !== NULL) 						   { $colonnes .= ", `longitude`"; $valeurs .= ", ".$long; $flag=false;}
	if ($elevation !== NULL) 					   { $colonnes .= ", `elevation`"; $valeurs .= ", ".$elevation; $flag=false;}
	if ($date   !== NULL) 						   { $colonnes .= ", `date`";   $valeurs .= ", ".$bdd->quote($date);}
	
	
	$colonnes .= ")";
	$valeurs  .= ")";
	
	// si flag est resté à true alors il n'y avait rien à sauvegarder donc erreur 421
	if ($flag) 
		Api::envoyerErreur(421, "No Action Performed", "The server attempted to process your request, but has no action to perform."); 
	
    $sql = "INSERT INTO `feeds` " . $colonnes . $valeurs;	
	
	$nb = $bdd->exec($sql);

	if($nb == 1){
		
		$data = array(
                'channel_id' => $channel->id ,
				'field1' => $val1 ,
				'field2' => $val2 ,
				'field3' => $val3 ,
				'field4' => $val4 ,
				'field5' => $val5 ,
				'field6' => $val6 ,
				'field7' => $val7 ,
				'field8' => $val8 ,  
				'created_at' => $date ,
				'status' => $status ,
				'latitude' => $lat ,
				'longitude' => $long ,
				'elevation' => $elevation
            );

        header('HTTP/1.1 200 OK');
        header('content-type:application/json');
        echo json_encode($data);
	}
	else{
        Api::envoyerErreur(500, "Internal Server Error", "Internal Server Error");
    }
 
?>