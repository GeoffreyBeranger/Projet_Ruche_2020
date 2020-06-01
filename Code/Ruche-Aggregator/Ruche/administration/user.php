<?php
include "authentification/authcheck.php" ;

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../api/Form.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;
use Aggregator\Support\Form;

$bdd = Api::connexionBD(BASE);

$error = "";

//------------si des données  sont soumises on les enregistre dans la table data.users ---------
if( !empty($_POST['envoyer'])){
	if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) { // si le token est valide
		try{

			if(isset($_POST['action']) && ($_POST['action'] == 'update')){
				$sql = sprintf("UPDATE `data`.`users` SET `login` = %s, `droits` = %s, `email` = %s, `telNumber` = %s, `User_API_Key`=%s, `quotaSMS`=%s, `delaySMS`=%s, `language`=%s WHERE `users`.`id` = %s;"
							  , $bdd->quote($_POST['login'])
							  , $bdd->quote($_POST['droits'])
							  , $bdd->quote($_POST['email'])
							  , $bdd->quote($_POST['telNumber'])
							  , $bdd->quote($_POST['User_API_Key'])
							  , $bdd->quote($_POST['quotaSMS'])
							  , $bdd->quote($_POST['delaySMS'])
							  , $bdd->quote($_POST['language'])
							  , $_POST['id']
							  );

				$bdd->exec($sql);
			}

			// destruction du tokenCSRF
			unset($_SESSION['tokenCSRF']);
			header("Location: users.php");
			return;
		}
		catch (\PDOException $ex)
		{
		    $error = $ex->getMessage();

		}
	}
}

// --------------- sinon lecture de la table data.users  -----------------------------

	// Le parametre id est obligatoire pour modifier un utilisateur
	$id = Api::obtenir("id");

	// Création d'un objet utilisateur
    try{
		$sql = sprintf("SELECT * FROM `users` WHERE `id`=%s", $bdd->quote($id));
		$stmt = $bdd->query($sql);
		if ($user =  $stmt->fetchObject()){
			$user->action = 'update';
		}
		else{
			echo "Erreur users.id inconnu ";
			return;
		}
	}
	catch (\PDOException $ex)
	{
	    echo($ex->getMessage());
		return;
	}


	// Création du tokenCSRF
	$tokenCSRF = STR::genererChaineAleatoire(32);
	$_SESSION['tokenCSRF'] = $tokenCSRF;


?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>User Settings - Aggregator</title>
		<!-- Bootstrap CSS version 4.1.1 -->
		<link rel="stylesheet" href="/Ruche/css/bootstrap.min.css" >
		<link rel="stylesheet" href="/Ruche/css/ruche.css" />

		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="/Ruche/scripts/bootstrap.min.js"></script>

	</head>
<body>

	<?php require_once '../menu.php'; ?>

	<div class="container-fluid" style="padding-top: 65px; ">
		<div class="row">
			<div class="col-md-6 col-sm-12 col-12">
				<div class="popin">
					<?php echo '<p style="color: #ff0000;">' . $error . '</p>'; ?>
					<form class="form-horizontal" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" name="configuration" >

							<?php
							    echo Form::hidden("action",    $user->action );
								echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"] );

								$options = array( 'class' => 'form-control', 'readonly' => null);
								echo Form::input( 'number', 'id', $user->id, $options, 'Id ' );
								echo Form::input( 'text', 'User_API_Key', $user->User_API_Key, $options , 'Api key ');
								echo Form::input( 'text', 'time_zone', $user->time_zone, $options , 'Time zone ');

								$options = array( 'class' => 'form-control');
								echo Form::input( 'text', 'login', $user->login, $options , 'Login ');

								$droits = array(1 => "Utilisateur", 2 =>"Administrateur" );
								echo Form::select("droits", $droits,     "Droits  ", $user->droits);

								echo Form::input( 'email' , 'email',     $user->email,     $options , 'E mail ');
								echo Form::input( 'text'  , 'telNumber', $user->telNumber, $options , 'Tel number ');
								echo Form::input( 'number'   , 'quotaSMS',  $user->quotaSMS,  $options , 'Quota SMS ');
								echo Form::input( 'number'   , 'delaySMS',  $user->delaySMS,  $options , 'Delay SMS ');

								$language = array('FR' => "French", 'EN' => "English" );
								echo Form::select("language", $language,     "language  ", $user->language);
							?>

							<div class="form-group">
								</br>
								<button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > Apply</button>
								<a  class="btn btn-info" role="button" href="users">Cancel</a>
							</div>
					</form>
				</div>
			</div>

			<div class="col-md-6 col-sm-12 col-12">
			    <div class="popin">
				<h3>User Settings</h3>
				<ul>
					<li><b>Login</b>: Enter a unique login for your user.</li>
					<li><b>API Key</b>: Auto generated API key for the user.</li>
					<li><b>Quota</b>: Enter the quota of daily SMS</li>
					<li><b>Delay</b>: Enter the delay  in seconds between two SMS transmissions </li>
				</ul>
				</div>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
</body>	
