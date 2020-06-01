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

//------------si des données  sont soumises on les enregistre dans la table data.thinghttps ---------
if( !empty($_POST['envoyer'])){
	if ($_SESSION['tokenCSRF'] === $_POST['tokenCSRF']) {
		if(isset($_POST['action']) && ($_POST['action'] == 'insert')){
			$sql = sprintf("INSERT INTO `data`.`thinghttps` (`user_id`, `api_key`, `url`, `auth_name`, `auth_pass`, `method`, `content_type`, `http_version`, `host`, `body`, `name`, `parse`) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s);"
						  , $bdd->quote($_POST['user_id'])   
						  , $bdd->quote($_POST['api_key'])
						  , $bdd->quote($_POST['url'])
						  , $bdd->quote($_POST['auth_name'])
						  , $bdd->quote($_POST['auth_pass'])
						  , $bdd->quote($_POST['method'])
						  , $bdd->quote($_POST['content_type'])
						  , $bdd->quote($_POST['http_version'])
						  , $bdd->quote($_POST['host'])
						  , $bdd->quote($_POST['body'])
						  , $bdd->quote($_POST['name'])
						  , $bdd->quote($_POST['parse'])				  
						  ); 	
			$bdd->exec($sql);
		}
		if(isset($_POST['action']) && ($_POST['action'] == 'update')){
			$sql = sprintf("UPDATE `thinghttps` SET `user_id` = %s, `url` = %s, `method`=%s, `auth_name`=%s, `auth_pass`=%s, `content_type`=%s, `http_version`=%s, `host`=%s, `body`=%s, `name`=%s , `parse`=%s  WHERE `thinghttps`.`id` = %s;"
						  , $bdd->quote($_POST['user_id'])
						  , $bdd->quote($_POST['url'])
						  , $bdd->quote($_POST['method'])
						  , $bdd->quote($_POST['auth_name'])
						  , $bdd->quote($_POST['auth_pass'])
						  , $bdd->quote($_POST['content_type'])
						  , $bdd->quote($_POST['http_version'])
						  , $bdd->quote($_POST['host'])
						  , $bdd->quote($_POST['body'])
						  , $bdd->quote($_POST['name'])
						  , $bdd->quote($_POST['parse'])
						  , $_POST['id']
						  );

			$bdd->exec($sql);
		}
	// destruction du tokenCSRF
	unset($_SESSION['tokenCSRF']);
	
	header("Location: thingHTTPs.php");
	return;
	
	}	
}
// -------------- sinon lecture de la table data.channels  -----------------------------
else
{
	if (isset($_GET['id'])){
 
		$sql = sprintf("SELECT * FROM `thinghttps` WHERE `id`=%s", $bdd->quote($_GET['id']));
		$stmt = $bdd->query($sql);
		if ($thinghttp =  $stmt->fetchObject()){
		   $_POST['action'] = "update";
		   $_POST['id']     = $thinghttp->id;
		   $_POST['user_id']   = $thinghttp->user_id;
		   $_POST['api_key'] = $thinghttp->api_key;
		   $_POST['url'] = $thinghttp->url;
		   $_POST['auth_name'] = $thinghttp->auth_name;
		   $_POST['auth_pass'] = $thinghttp->auth_pass;
		   $_POST['method'] = $thinghttp->method;
		   $_POST['content_type'] = $thinghttp->content_type;
		   $_POST['http_version'] = $thinghttp->http_version;
		   $_POST['host'] = $thinghttp->host;
		   $_POST['body'] = $thinghttp->body;
		   $_POST['name'] = $thinghttp->name;
		   $_POST['parse'] = $thinghttp->parse;
	   } 
	}else {
		$_POST['action'] = "insert";
		$_POST['id'] = 0;
		$_POST['user_id'] = $_SESSION['id'];
		$_POST['api_key'] = Str::genererChaineAleatoire();
		$_POST['url'] = "";
		$_POST['auth_name'] = "";
		$_POST['auth_pass'] = "";
		$_POST['method'] = "";
		$_POST['content_type'] = "";
		$_POST['http_version'] = "";
		$_POST['host'] = "";
		$_POST['body'] = "";
		$_POST['name'] = "";
		$_POST['parse'] = "";		
   }

    // Création du selectUser 
	$sql = "SELECT id,login FROM users ORDER BY id;";
	$stmt = $bdd->query($sql);
	
	$selectUser = array();
	while ($user = $stmt->fetchObject()){
		$selectUser[$user->id] = $user->login;
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

		<title>thingHTTP Settings - Aggregator</title>
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
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="popin">
					<form class="form-horizontal" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" name="configuration" >
						
						<?php
							echo Form::hidden('action', $_POST['action']);
							echo Form::hidden('user_id', $_POST['user_id']);
							echo Form::hidden("tokenCSRF", $_SESSION["tokenCSRF"] );
							
							
							$options = array( 'class' => 'form-control', 'readonly' => null);
							echo Form::input( 'int', 'id', $_POST['id'], $options, 'Id' );
							echo Form::input( 'text', 'api_key', $_POST['api_key'], $options, 'api key' );
							
							if($_SESSION['droits'] > 1) //  un selecteur pour les administrateur
								echo Form::select("user_id", $selectUser, "User ", $_POST["user_id"]);
							else
								echo Form::hidden("user_id", $_POST["user_id"]);
							
							$options = array( 'class' => 'form-control');							
							echo Form::input( 'text', 'name', $_POST['name'], $options, 'name' );
							echo Form::input( 'text', 'url', $_POST['url'], $options, 'url' );
							echo Form::input( 'text', 'auth_name', $_POST['auth_name'], $options, 'auth name' );
							echo Form::input( 'text', 'auth_pass', $_POST['auth_pass'], $options, 'Auth Password' );
							$listMethod = array('GET'=>'GET' , 'POST'=>'POST' , 'PUT'=>'PUT' , 'DELETE'=>'DELETE' );
							echo Form::select("method", $listMethod , "method", $_POST['method']);
							echo Form::input( 'text', 'content_type', $_POST['content_type'], $options, 'content type' );
							$listVersion = array('1.0' => '1.0', '1.1' => '1.1');
							echo Form::select("http_version", $listVersion , "http version", $_POST['http_version']);
							echo Form::input( 'text', 'host', $_POST['host'], $options, 'host' );
							$optionsArea = array( 'class' => 'form-control', 'rows' => '4');
							echo Form::textarea("body", $_POST['body'], $optionsArea, 'body');
							echo Form::input( 'text', 'parse', $_POST['parse'], $options, 'parse' );
							
						?>
														
							<div class="form-group">
								</br>
								<button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > Appliquer</button>
								<a  class="btn btn-info" role="button" href="thingHTTPs">Annuler</a>
							</div>	
					</form>
				</div>
			</div>
			
			<div class="col-md-6 col-sm-12 col-xs-12">
			    <div class="popin">
				<h3>ThingHTTP Settings</h3>
				<ul>
					<li><b>Name</b>: Enter a unique name for your ThingHTTP request.</li>

					<li><b>API Key</b>: Auto generated API key for the ThingHTTP request.</li>

					<li><b>URL</b>: Enter the address of the website you are requesting data from or writing data to starting with either http:// or https://.</li>
					
					<li><b>HTTP Auth Username</b>: If your URL requires authentication, enter the username for authentication to access private channels or websites.</li>
					
					<li><b>HTTP Auth Password</b>: If your URL requires authentication, enter the password for authentication to access private channels or websites.</li>

					<li><b>Method</b>: Select the HTTP method required to access the URL.</li>

					<li><b>Content Type</b>: Enter the MIME or form type of the request content. For example, application/x-www-form-urlencoded.</li>

					<li><b>HTTP Version</b>: Select the version of HTTP on your server.</li>
					
					<li><b>Host</b>: If your ThingHTTP request requires a host address, enter the domain name here. For example, api.aggregate.com.</li>
					
					<li><b>Headers</b>: If your ThingHTTP request requires custom headers, enter the information here. You must specify the name of the header and a value.</li>
					
					<li><b>Body</b>: Enter the message you want to include in your request.</li>
					
					<li><b>Parse String</b>: If you want to parse the response, enter the exact string to look for in the response data.</li>
				</ul>
				</div>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
</body>	