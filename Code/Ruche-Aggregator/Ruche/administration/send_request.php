<?php
	include "authentification/authcheck.php" ;

	require_once('../definition.inc.php');
	require_once('../api/Api.php');
	
	use Aggregator\Support\Api;
	
	include('thingHTTP.class.php');

	// connexion à la base
	$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);
	$id  = Api::obtenir("id", FILTER_VALIDATE_INT);
	
	try{
		$objet = new thingHTTP($bdd,$id);
		echo $objet->send_request();
	}
	//catch exception
	catch(thingHTTPException $e) {
		Api::envoyerErreur(500, $e->getMessage(), $e->getMessage());
	}
?>