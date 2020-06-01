<?php
	// page d'authentification pour la partie sécurisée du site
	// cette page affiche un formulaire avec deux champs (login et passe)
	// et un bouton pour soumettre au script 

	require_once('../definition.inc.php');
	require_once('../api/Api.php');
	require_once('../api/Str.php');
	
	use Aggregator\Support\Api;
	use Aggregator\Support\Str;
	
	session_start();
	unset($_SESSION['identite']);
	unset($_SESSION['login']);
	unset($_SESSION['ID_user']);
	unset($_SESSION['email']);
	unset($_SESSION['droits']);
	unset($_SESSION['language']);

	
	$erreur = "";
	$bdd = Api::connexionBD(BASE);
	
	// Si le formulaire a été soumis
	if(isset($_POST['B1'])){
		
		$token   = Api::obtenir("token");
		$md5     = Api::obtenir("md5");
		$login   = Api::obtenir("login"); 
		$retour  = Api::facultatif("retour","");
	
		if($token !== $_SESSION['token']){
			Api::envoyerErreur(403, "Authorization Required", "Erreur interne token invalide !!" );		
		}
		
		try{
			$sql = sprintf("SELECT * FROM `users` WHERE `login`=%s AND `allow` = 1; ", $bdd->quote($login));
			$stmt = $bdd->query($sql);
			$utilisateur =  $stmt->fetchObject();
		}
		catch (\PDOException $ex) 
		{
		   Api::envoyerErreur('503','Service Unavailable',$ex->getMessage());       	   
		}
		// vérification des identifiants login et encrypted_password par rapport à ceux enregistrés dans la table users
		
		
	
		if ( $utilisateur && $login == $utilisateur->login && $md5 == $utilisateur->encrypted_password){
			// A partir de cette ligne l'utilisateur est authentifié donc nouvelle session
			// écriture des variables de session pour cet utilisateur

			$_SESSION['last_access'] = time();
			$_SESSION['ipaddr']		 = $_SERVER['REMOTE_ADDR'];
			$_SESSION['login'] 		 = $utilisateur->login;
			$_SESSION['id']			 = $utilisateur->id;
			$_SESSION['User_API_Key']= $utilisateur->User_API_Key;
			$_SESSION['time_zone']   = $utilisateur->time_zone;
			$_SESSION['droits'] 	 = $utilisateur->droits;
			$_SESSION['language'] 	 = $utilisateur->language;
       
			// mise à jours de la date et heure de son passage dans le champ last_sign_in_at de la table users
	        try{
				$sql = "UPDATE `users` SET `last_sign_in_at` = `current_sign_in_at`  WHERE `users`.`id` = $utilisateur->id LIMIT 1; " ;
				$stmt = $bdd->query($sql);
			
				$sql = "UPDATE `users` SET `current_sign_in_at` = CURRENT_TIMESTAMP  WHERE `users`.`id` = $utilisateur->id LIMIT 1; " ;
				$stmt = $bdd->query($sql);

				// Incrémentation du compteur de session
				$sql = "UPDATE `users` SET `sign_in_count` = `sign_in_count`+1 WHERE `users`.`id` = $utilisateur->id LIMIT 1" ;
				$stmt = $bdd->query($sql);
			}
			catch (\PDOException $ex) 
			{
				Api::envoyerErreur('503','Service Unavailable',$ex->getMessage());       	   
			}
			
			// sélection de la page de retour
			if ($retour!== ""){
				header("Location: " . $_POST['retour'] );
				exit;
			}
			else{
				header("Location: ../index.php");
				exit;
			}
		}
		else{
			try{
				// Erreur d'identification enregistrement des informations dans la table `failed_logins`
				$ip_address =  $_SERVER['REMOTE_ADDR'];
				$sql = sprintf("INSERT INTO `data`.`failed_logins` (`login`, `password`, `ip_address`, `created_at`) VALUES (%s, %s, %s, CURRENT_TIMESTAMP);"
							,$bdd->quote($login)
							,$bdd->quote($md5)
							,$bdd->quote($_SERVER['REMOTE_ADDR']));
				$stmt = $bdd->query($sql);			
				
				// Comptage du nombre d'erreurs lors de la dernière heure
				$sql = sprintf("SELECT count(*) as nb FROM `failed_logins` where `login` = %s AND `created_at` > DATE_SUB(NOW(), INTERVAL 3600 SECOND)"
							,$bdd->quote($login));
				$stmt = $bdd->query($sql);
				$res =  $stmt->fetchObject();
				
				// Si plus de trois erreurs 
				if ($res->nb > 3) {
					$erreur = "Attention! plus de trois erreurs !!!";
					$sql = sprintf("UPDATE `data`.`users` SET `allow` = 0 WHERE `users`.`login` = %s" 
						,$bdd->quote($login));
					$stmt = $bdd->query($sql);	
				}else{	
					$erreur = "Incorrectes! Vérifiez vos identifiant et mot de passe.";
				}
			}
			catch (\PDOException $ex) 
			{
				Api::envoyerErreur('503','Service Unavailable',$ex->getMessage());       	   
			}
		}		
	}
	
	$token =  Str::genererChaineAleatoire(20);
	//Mémorisation du token dans la variable de session
	$_SESSION['token'] 	 =  $token;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Authentification</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="/Ruche/css/bootstrap.min.css">
	<link rel="stylesheet" href="/Ruche/css/ruche.css" />
	
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="/Ruche/scripts/bootstrap.min.js"></script> 
    

    <script  src="./authentification/login.js"></script>
</head>
<body>

	<?php require_once '../menu.php'; ?>

	<div class="container" style="padding-top: 65px;">
    

		<div class="row">
			
			<div  class="col-md-6 col-sm-6 col-xs-12">
				<div class="popin">
				<h2>Sign in to Aggregator</h2>
				
				  <?php echo '<p style="color: #ff0000;">' . $erreur . '</p>'; ?>
				
				<form method="POST" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"  name="form2" id="form2">
					<input type='hidden' name='md5' />
					<input type='hidden' name='retour' value = "<?php if (isset($_GET["retour"])) echo $_GET["retour"]; ?>" />
					<input type='hidden' name='token' value = "<?php echo $token; ?>" />
						<div class="form-group">
							<label for="login" class="font-weight-bold">User login :</label>
							<input type="text"  name="login" class="form-control"  required="">
						</div>
						
						<div class="form-group">
							<label for="password" class="font-weight-bold">Password :</label>
							<input type="password" name="passe" class="form-control" required="">
						</div>
						<br />
						<input   id="Valider" class="btn btn-primary" value="Valider" name="B1"   readonly size="9">		
				</form>
				</div>
			</div>
			
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
</body>	


