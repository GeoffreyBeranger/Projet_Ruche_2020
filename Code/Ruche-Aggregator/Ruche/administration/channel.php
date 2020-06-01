/**
 * \file      channel.php
 * \author    G.BÉRANGER
 * \version   1.0
 * \date       01 Juin 2020
 * \brief      Formulaire de création ou de modification  d'un channel
 *
 * \details    Ce Formulaire permet de créer ou de modifier un channel dans la base de données en y renseignant les différentes informations.
 *             Si l'utilisateur a selectionner ajouter sur la page channels.php le formulaire sera vide,
 *             Si l'utilisateur a selectionner modifier sur la page channels.php,
 *             les informations vont etre recupérées dans la BDD et affichées dans le formulaire puis ensuite mis a jour dans la bdd
 */

<?php
include "authentification/authcheck.php" ;

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');
require_once('../api/Form.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;
use Aggregator\Support\Form;

$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);

//------------si des données  sont soumises on les enregistre dans la table data.channels ---------
if( !empty($_POST['envoyer'])){
	if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) { // si le token est valide
		try{
			if(isset($_POST['action']) && ($_POST['action'] == 'insert')){
				$sql = sprintf("INSERT INTO `data`.`channels` (`name`, `field1`, `field2`, `field3`, `field4`, `field5`, `field6`, `field7`, `field8`, `status`, `tags`,`write_api_key` ) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);"
							  , $bdd->quote($_POST['name'])   // utf8_decode()
							  , $bdd->quote($_POST['field1'])
							  , $bdd->quote($_POST['field2'])
							  , $bdd->quote($_POST['field3'])
							  , $bdd->quote($_POST['field4'])
							  , $bdd->quote($_POST['field5'])
							  , $bdd->quote($_POST['field6'])
							  , $bdd->quote($_POST['field7'])
							  , $bdd->quote($_POST['field8'])
							  , $bdd->quote($_POST['status'])
							  , $bdd->quote($_POST['tags'])
							  , $bdd->quote($_POST['write_api_key'])
							  );
				$bdd->exec($sql);

			}
			if(isset($_POST['action']) && ($_POST['action'] == 'update')){
				$sql = sprintf("UPDATE `channels` SET `name` = %s, `field1`=%s, `field2`=%s, `field3`=%s, `field4`=%s, `field5`=%s, `field6`=%s , `field7`=%s , `field8`=%s , `status`=%s, `tags`=%s WHERE `channels`.`id` = %s;"
							  , $bdd->quote($_POST['name'])
							  , $bdd->quote($_POST['field1'])
							  , $bdd->quote($_POST['field2'])
							  , $bdd->quote($_POST['field3'])
							  , $bdd->quote($_POST['field4'])
							  , $bdd->quote($_POST['field5'])
							  , $bdd->quote($_POST['field6'])
							  , $bdd->quote($_POST['field7'])
							  , $bdd->quote($_POST['field8'])
							  , $bdd->quote($_POST['status'])
							  , $bdd->quote($_POST['tags'])
							  , $_POST['id']
							  );

				$bdd->exec($sql);
			}
		}
		catch (\PDOException $ex)
		{
		    echo($ex->getMessage());
			return;
		}

		// destruction du tokenCSRF
		unset($_SESSION['tokenCSRF']);

		header("Location: channels.php");
		return;
	}
}
// -------------- sinon lecture de la table data.channels  -----------------------------
else
{
	try{
		if (isset($_GET['id'])){

			$sql = sprintf("SELECT * FROM `channels` WHERE `id`=%s", $bdd->quote($_GET['id']));
			$stmt = $bdd->query($sql);
			if ($channel =  $stmt->fetchObject()){
			   $channel->action = "update";

		   }
		}else {
			$channel->action = "insert";
			$channel->id = 0;
			$channel->name = "";
			$channel->field1 = "";
			$channel->field2 = "";
			$channel->field3 = "";
			$channel->field4 = "";
			$channel->field5 = "";
			$channel->field6 = "";
			$channel->field7 = "";
			$channel->field8 = "";
			$channel->status = "";
			$channel->tags = "";
			$channel->write_api_key = Api::genererKey($bdd);
			$channel->last_write_at = "";
		}

		// Création du selectTag
		$sql = "SELECT tag FROM `things`";
		$stmt = $bdd->query($sql);

		$selectTag = array();
		while ($thing = $stmt->fetchObject()){
			$selectTag[$thing->tag] = $thing->tag;
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
}
?>

<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>Channel Settings - Aggregator</title>
		<!-- Bootstrap CSS version 4.1.1 -->
		<link rel="stylesheet" href="/Ruche/css/bootstrap.min.css" >
		<link rel="stylesheet" href="/Ruche/css/ruche.css" />

		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="/Ruche/scripts/bootstrap.min.js"></script>

	</head>
<body>

	<?php require_once '../menu.php'; ?>

	<div class="container-fluid" style="padding-top: 65px;">
		<div class="row">
			<div class="col-md-6 col-sm-12 col-12">
				<div class="popin">
					<form class="form-horizontal" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" name="configuration" >
							<?php
								echo Form::hidden('action', $channel->action);
								echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"] );

								$options = array( 'class' => 'form-control', 'readonly' => null);
								echo Form::input( 'int', 'id', $channel->id, $options, 'Id' );
								echo Form::input( 'text', 'write_api_key', $channel->write_api_key, $options, 'write api key' );
								echo Form::input( 'text', 'last_write_at', $channel->last_write_at, $options, 'last write at' );

								$options = array( 'class' => 'form-control');
								echo Form::input( 'text', 'name', $channel->name, $options);

								echo Form::select("tags", $selectTag , "Tag", $channel->tags);
								echo Form::input( 'field1', 'field1', $channel->field1, $options);
								echo Form::input( 'field2', 'field2', $channel->field2, $options);
								echo Form::input( 'field3', 'field3', $channel->field3, $options);
								echo Form::input( 'field4', 'field4', $channel->field4, $options);
								echo Form::input( 'field5', 'field5', $channel->field5, $options);
								echo Form::input( 'field6', 'field6', $channel->field6, $options);
								echo Form::input( 'field7', 'field7', $channel->field7, $options);
								echo Form::input( 'field8', 'field8', $channel->field8, $options);
								echo Form::input( 'status', 'status', $channel->status, $options);

							?>

							<div class="form-group">
								</br>
								<button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > Apply</button>
								<a  class="btn btn-info" role="button" href="channels">Cancel</a>
							</div>
					</form>
				</div>
			</div>

			<div class="col-md-6 col-sm-12 col-12">
			    <div class="popin">
				<h3>Channel Settings</h3>
				<ul>
					<li>Channel Name: Enter a unique name for the channel.</li>

					<li>Description: Enter a description of the channel.</li>

					<li>Field#: Check the box to enable the field, and enter a field name. Each  channel can have up to 8 fields.</li>


					<li>Tags: Enter keywords that identify the thing. Separate tags with commas.</li>

					<li>Show Channel Location:
						<ul>
							<li>Latitude: Specify the latitude position in decimal degrees. For example, the latitude of the city of London is 51.5072.</li>

							<li>Longitude: Specify the longitude position in decimal degrees. For example, the longitude of the city of London is -0.1275.</li>

							<li>Elevation: Specify the elevation position meters. For example, the elevation of the city of London is 35.052.</li>
						</ul>
					</li>
				</ul>
				</div>
			</div>
		</div>

		<?php require_once '../piedDePage.php'; ?>
	</div>

</body>
