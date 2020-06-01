<?php
    // Contrôle des variables de sessions
	include "../administration/authentification/authcheck.php" ;
   
	require_once('../definition.inc.php');
	require_once('Api.php');
	require_once('Str.php');
	
	use Aggregator\Support\Api;
	use Aggregator\Support\Str;
    
	$id	       = Api::obtenir("id", FILTER_VALIDATE_INT);
	$pwd	   = Api::obtenir("pwd");
	$conf_pwd  = Api::obtenir("conf_pwd");
	$User_API_Key = Api::obtenir("User_API_Key");
    
	
	// la variable conf_pwd doit être la même que pwd
	if($conf_pwd != $pwd){
        Api::envoyerErreur(403, "Bad Request", "The pwd and conf_pwd is not the same." );
        return;		
	}	
	
	// Tous les paramètres sont présents on fait le travail
	$bdd = Api::connexionBD(BASE);

	
	// Contrôle de la clé API
	Api::controlerkey($bdd, $User_API_Key);
	
	// Générer et enregistrer un grain de sel
	$salt = Str::genererChaineAleatoire(20);
	$sql = sprintf("UPDATE `data`.`users` SET `password_salt` = %s WHERE `users`.`id` = %s;",
		$bdd->quote($salt),
		$bdd->quote($id)
	);

	$nb = $bdd->exec($sql);

	
	$sql = sprintf("UPDATE `data`.`users` SET `encrypted_password` = %s WHERE `users`.`id` = %s;",
		
		$bdd->quote(hash('sha256', $pwd . $salt)),
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