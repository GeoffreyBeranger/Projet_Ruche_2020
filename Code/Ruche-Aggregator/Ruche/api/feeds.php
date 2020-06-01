<?php
    /** fichier		     : feeds.php
	    description      : Lecture des données pour tous les champs d'un canal avec HTTP GET
	    author           : Philippe SIMIER Lycée Touchard Le Mans
		version          : 1.0 du 31 mars 2020
		
		réécriture       : RewriteRule ^channels/([0-9]+)/feeds.([a-zA-Z]+)$   api/feeds.php?channelId=$1&type=$2 [QSA]
		endpoint         : channels/<channelId>/feeds.<type>
		endpoint réécrit : api/feeds.php?channelId=539387&type=json
		
	    Parameters : 
			channelId (Required) Channel ID for the channel of interest.
			type      (Required) le type de sortie JSON CSV
			results   (Optional) Number of entries to retrieve. The maximum number is 8000
			callback  (Optional) le nom de la fonction de retour JSONP
			start	  (Optional) la date de départ
			end	      (Optional) la date de fin
				
		Retour :  les données au format json ou csv suivant la demande
	**/
	
	require_once('../definition.inc.php');
	require_once('Api.php');

	use Aggregator\Support\Api;
	
   
	// Lecture des paramétres obligatoires
	$channelId = Api::obtenir("channelId", FILTER_VALIDATE_INT);
	$type      = Api::obtenir("type");
		
	// Lecture des paramétres facultatifs
	$results   = Api::facultatif("results", "8000", FILTER_VALIDATE_INT);
	$callback  = Api::facultatif("callback", NULL);
	$start     = Api::facultatif("start", NULL);
	$end       = Api::facultatif("end", NULL);

	// Connexion à la base avec session heure UTC
	$bdd = Api::connexionBD(BASE, "+00:00");
	
	// construction de la requête SQL pour obtenir les valeurs enregistrées dans la table feeds
	$sql = "SELECT * FROM `feeds` where `id_channel` = ". $channelId;
	if ($start !== NULL and $end !== NULL){
		$sql .= " and `date` between '" . $start . "' and '" . $end. "'";
	}	
    $sql .= " ORDER BY `id` DESC limit " . $results;
    
	// Mise en forme des données 
	if ($type == "json"){
		// Lecture des informations correspondant au channel dans la table channels
		$sqlChannels = "SELECT * FROM `channels` WHERE `id` = " . $channelId;
		$stmt = $bdd->query($sqlChannels);
		if ($result =  $stmt->fetchObject()){
				
			$channel = array(
				'id' => intval($result->id),
				'name' => $result->name,
			);
			if ($result->field1 != "") $channel['field1'] = $result->field1;
			if ($result->field2 != "") $channel['field2'] = $result->field2;
			if ($result->field3 != "") $channel['field3'] = $result->field3;
			if ($result->field4 != "") $channel['field4'] = $result->field4;
			if ($result->field5 != "") $channel['field5'] = $result->field5;
			if ($result->field6 != "") $channel['field6'] = $result->field6;
			if ($result->field7 != "") $channel['field7'] = $result->field7;
			if ($result->field8 != "") $channel['field8'] = $result->field8;
			
			$stmt = $bdd->query($sql);
			$feeds = array();
			while ($feed =  $stmt->fetchObject()){
				$data = array(
						'created_at' => formatDate($feed->date),
						'entry_id' => intval($feed->id), 
					);	
				if ($result->field1 != "") $data['field1'] = Api::nan($feed->field1);
				if ($result->field2 != "") $data['field2'] = Api::nan($feed->field2);
				if ($result->field3 != "") $data['field3'] = Api::nan($feed->field3);
				if ($result->field4 != "") $data['field4'] = Api::nan($feed->field4);
				if ($result->field5 != "") $data['field5'] = Api::nan($feed->field5);
				if ($result->field6 != "") $data['field6'] = Api::nan($feed->field6);	
				if ($result->field7 != "") $data['field7'] = Api::nan($feed->field7);	
				if ($result->field8 != "") $data['field8'] = Api::nan($feed->field8);			
				array_push($feeds, $data);							
			}
			
			$output = array(
				'channel' => $channel,
				'feeds' => $feeds
			);
			
			header("Access-Control-Allow-Origin: *");
			header('Content-type: application/json');
			if ($callback !== NULL){ 	
			    echo $callback . '('; 
			}
			echo json_encode($output);
			if ($callback !== NULL){ 	echo ')'; }
		}else{
			echo "-1";
		}
    }
    if($type == "csv"){

		$nom_fichier = "channel_". $channelId . ".csv";

		header('Content-Type: application/csv-tab-delimited-table');
		header('Content-Disposition:attachment;filename='.$nom_fichier);
		
		
		echo "created_at,entry_id,field1,field2,field3,field4,field5,field6,field7,field8,latitude,longitude,elevation,status\n";
		$stmt = $bdd->query($sql);
		while ($data = $stmt->fetchObject()){
				echo $data->date." UTC,";
				echo $data->id.",";
				echo Api::nan($data->field1).",";
				echo Api::nan($data->field2).",";
				echo Api::nan($data->field3).",";
				echo Api::nan($data->field4).",";
				echo Api::nan($data->field5).",";
				echo Api::nan($data->field6).",";
				echo Api::nan($data->field7).",";
				echo Api::nan($data->field8).",";
				echo $data->latitude.",";
				echo $data->longitude.",";
				echo $data->elevation.",";			
				echo $data->status."\n";
		}
	}
?>