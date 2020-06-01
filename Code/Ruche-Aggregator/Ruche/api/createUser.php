<?php
    // Contrôle des variables de sessions
	include "../administration/authentification/authcheck.php" ;    
   
	require_once('../definition.inc.php');
	require_once('Api.php');
	require_once('Str.php');
	
	use Aggregator\Support\Api;
	use Aggregator\Support\Str;
	
	// Contrôle de la présence des paramètres requis
	$key          = Api::obtenir("key");
    $login        = Api::obtenir("login");
    $User_API_Key = Api::obtenir("User_API_Key");
    $pwd	      = Api::obtenir("pwd");
	$conf_pwd     = Api::obtenir("conf_pwd");
  
	// la variable conf_pwd doit être la même que pwd
	if($conf_pwd != $pwd){
        Api::envoyerErreur(403, "Bad Request", "The pwd and conf_pwd is not the same." );
        return;		
	}	
	
	// Tous les paramètres sont présents on fait le travail
		
	$bdd = Api::connexionBD(BASE);
	
	// Contrôle de la clé
	// La clé doit appartenir à un utilisateur ayant les droits d'administrateur
	
    $sql = sprintf("SELECT * FROM `users` WHERE `users`.`User_API_Key`=%s", $bdd->quote($key));
	
    $stmt = $bdd->query($sql);
	if (!($res =  $stmt->fetchObject())){
		Api::envoyerErreur(405, "Authorization Required", "Please provide proper authentication details." );
        return;
	}	
	
    // si les droits ne correspondent pas à administrateur
    if ( $res->droits < 2) {
        Api::envoyerErreur(406, "Authorization Required", "Please provide proper authentication details." );
        return;
    }
	
    // Générer un grain de sel
	$salt = Str::genererChaineAleatoire(20);

	
	$sql = sprintf("INSERT INTO `users` (`login`, `encrypted_password`, `password_salt`, `User_API_Key`, `Created_at`, `sign_in_count`) VALUES (%s, %s, %s, %s, CURRENT_TIMESTAMP, '0')",
		$bdd->quote($login),
		$bdd->quote(hash('sha256', $pwd . $salt)),
		$bdd->quote($salt),
		$bdd->quote($User_API_Key)
	);
	
	
	$nb = $bdd->exec($sql);
	 
	if($nb == 1){
		
		$data = array(
                'status' => "201 Created",
				'login' => utf8_encode($login), 
				'User_API_Key' => utf8_encode($User_API_Key)
            );

        header('HTTP/1.1 201 Created');
        header('content-type:application/json');
        echo json_encode($data);
	}
	else{
        Api::envoyerErreur(409, "duplicate login", "duplicate login");
    }

?>	