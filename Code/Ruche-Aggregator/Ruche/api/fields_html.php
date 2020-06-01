<?php
    /** fichier		 : api/fields_html.php
	    description  : modele fields pour renvoyer un selecteur html contenant le nom des champs 
					 : pour un channel identifié par son id
	    author       : Philippe SIMIER Lycée Touchard Le Mans
		
	**/

	include "../administration/authentification/authcheck.php" ;

	require_once('../definition.inc.php');
	require_once('Api.php');

	use Aggregator\Support\Api;

	$bdd = Api::connexionBD(BASE);

	// Lecture du paramétre obligatoire channelId
	$channelId = Api::obtenir("channelId", FILTER_VALIDATE_INT);
	
	try{
		$sql = "SELECT * FROM `channels` where `id` = ". $bdd->quote($channelId);
		$stmt = $bdd->query($sql);
		
		$reponse = '<option selected="selected" value="">Choose your field</option>';
		
		$channel = $stmt->fetchObject();
		$reponse .= "<option value='1'>{$channel->field1}</option>";
		$reponse .= "<option value='2'>{$channel->field2}</option>";
		$reponse .= "<option value='3'>{$channel->field3}</option>";
		$reponse .= "<option value='4'>{$channel->field4}</option>";    
		$reponse .= "<option value='5'>{$channel->field5}</option>";
		$reponse .= "<option value='6'>{$channel->field6}</option>";
		$reponse .= "<option value='7'>{$channel->field7}</option>";
		$reponse .= "<option value='8'>{$channel->field8}</option>";	
		echo $reponse;
	}
	catch (\PDOException $ex) 
	{
	    echo($ex->getMessage());
        return;		
	}