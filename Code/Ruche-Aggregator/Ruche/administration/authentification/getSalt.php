<?php
   
	require_once('../../definition.inc.php');
	require_once('../../api/Api.php');	
	
	use Aggregator\Support\Api;
    
	$login = Api::obtenir("login");
	$bdd = Api::connexionBD(BASE);
	
	$sql = sprintf("SELECT `password_salt` FROM `data`.`users`  WHERE `users`.`login` = %s;",
		$bdd->quote($login)
	);
	
	$stmt = $bdd->query($sql);
	
	$user =  $stmt->fetchObject();
 
    if ($user){	
		echo $user->password_salt;
	}
	else{
		echo "inconnu";
	}