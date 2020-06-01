<?php
    include "../administration/authentification/authcheck.php" ;    
   
	require_once('../definition.inc.php');
    require_once('Api.php');

    use Aggregator\Support\Api;	
 
    // Contrôle de la présence des paramètres key id folder en GET ou POST
	$key    = Api::obtenir("key");
    $id     = Api::obtenir("id", FILTER_VALIDATE_INT);
    $folder = Api::obtenir("folder"); 
	
	// connexion à la base smsd
	$bdd = Api::connexionBD(BASESMS, $_SESSION['time_zone']);

	if ($folder == "sent")
		$sql = "SELECT `SendingDateTime` as dateTime,`DestinationNumber` as number,`TextDecoded` as text,`ID` FROM `sentitems` where id = ".$id. " order by SequencePosition";
	if ($folder == "inbox")
		$sql = "SELECT `ReceivingDateTime` as dateTime,`SenderNumber` as number,`TextDecoded` as text,`ID` FROM `inbox` where id = ".$id;
     	
	if (isset($sql))
		$stmt = $bdd->query($sql);
	else
		Api::envoyerErreur(500, "Internal Server Error", "Internal Server Error");
	 
	if($message =  $stmt->fetchObject()){
		
		$data = array(
                'status' => "202 Accepted",
				'date' => $message->dateTime, 
                'number' => $message->number,
				'text' => htmlentities($message->text)
            );

        header('HTTP/1.1 202 Accepted');
        header('content-type:application/json');
        echo json_encode($data);
	}
	else{
        Api::envoyerErreur(500, "Internal Server Error", "Internal Server Error");
    }	
?>	