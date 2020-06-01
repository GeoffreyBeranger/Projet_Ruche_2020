<?php
include "authentification/authcheck.php" ;
	
require_once('../definition.inc.php');
require_once('../api/Api.php');

use Aggregator\Support\Api;



$bdd = Api::connexionBD(BASE);

//------------si des données  sont soumises on les enregistre dans la table data.users ---------
if( !empty($_POST['envoyer'])){

	
	if(isset($_POST['action']) && ($_POST['action'] == 'update')){
		$sql = sprintf("UPDATE `users` SET `time_zone` = %s WHERE `users`.`id` = %s;"
					  , $bdd->quote($_POST['time_zone'])
					  , $_POST['id']
					  );

		$bdd->exec($sql);
		// Prise en compte immédiate du changement de time zone pour la session php et Bdd
		$_SESSION['time_zone'] = $_POST['time_zone'];
		// Prise en compte immédiate du changement de time zone pour la session bdd
		$sql = "SET @@session.time_zone = ". $bdd->quote($_POST['time_zone']) ;
		$stmt = $bdd->exec($sql);
		
		header("Location: users.php");
		return;
	}
}
// -------------- sinon lecture de la table data.users  -----------------------------
else
{
	if (isset($_GET['id'])){
 
		$sql = sprintf("SELECT * FROM `users` WHERE `id`=%s", $bdd->quote($_GET['id']));
		$stmt = $bdd->query($sql);
		if ($user =  $stmt->fetchObject()){
		   $_POST['action'] = "update";
		   $_POST['id']     = $user->id;
		   $_POST['time_zone']   = $user->time_zone;
		   
	   } 
	} 
}

function get_tz_options($selectedzone)
{
  echo '<select class="form-control" name="time_zone" id="user_time_zone">';
  
  function timezonechoice($selectedzone) {
    $all = timezone_identifiers_list();

    $i = 0;
    foreach($all AS $zone) {
      $zone = explode('/',$zone);
      $zonen[$i]['continent'] = isset($zone[0]) ? $zone[0] : '';
      $zonen[$i]['city'] = isset($zone[1]) ? $zone[1] : '';
      $zonen[$i]['subcity'] = isset($zone[2]) ? $zone[2] : '';
      $i++;
    }

    asort($zonen);
    $structure = '';
    foreach($zonen AS $zone) {
      extract($zone);
      if($continent == 'Africa' || $continent == 'America' || $continent == 'Antarctica' || $continent == 'Arctic' || $continent == 'Asia' || $continent == 'Atlantic' || $continent == 'Australia' || $continent == 'Europe' || $continent == 'Indian' || $continent == 'Pacific') {
        if(!isset($selectcontinent)) {
          $structure .= '<optgroup label="'.$continent.'">'; // continent
        } elseif($selectcontinent != $continent) {
          $structure .= '</optgroup><optgroup label="'.$continent.'">'; // continent
        }

        if(isset($city) != ''){
          if (!empty($subcity) != ''){
            $city = $city . '/'. $subcity;
          }
          $structure .= "<option ".((($continent.'/'.$city)==$selectedzone)?'selected="selected "':'')." value=\"".($continent.'/'.$city)."\">".str_replace('_',' ',$city)."</option>"; //Timezone
        } else {
          if (!empty($subcity) != ''){
            $city = $city . '/'. $subcity;
          }
          $structure .= "<option ".(($continent==$selectedzone)?'selected="selected "':'')." value=\"".$continent."\">".$continent."</option>"; //Timezone
        }

        $selectcontinent = $continent;
      }
    }
    $structure .= '</optgroup>';
    return $structure;
  }
  echo timezonechoice($selectedzone);
  echo '</select>';
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<title>Edit Profile - Aggregator</title>
		<!-- Bootstrap CSS version 4.1.1 -->
		<link rel="stylesheet" href="/Ruche/css/bootstrap.min.css" >
		<link rel="stylesheet" href="/Ruche/css/ruche.css" />
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="/Ruche/scripts/bootstrap.min.js"></script>	
	</head>
<body>

	<?php require_once '../menu.php'; ?>

	<div class="container" style="padding-top: 65px;">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="popin">
					<form class="form-horizontal" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" name="configuration" >
						
							<input type='hidden' name='action' value="<?php  echo $_POST["action"]; ?>" />
							
							
							<div class="form-group row">
								<label for="id"  class="font-weight-bold col-sm-4 col-form-label">id : </label>
								<div class="col-sm-8">
									<input type="text"  name="id" class="form-control" readonly value="<?php echo  $_POST['id']; ?>" />
								</div>
							</div>
							
							<div class="form-group row">
								<label for="name"  class="font-weight-bold col-sm-4 col-form-label">Time Zone : </label>
								<div class="col-sm-8">
								<?php
								   get_tz_options($_POST['time_zone']);
								?>	
								</div>
							</div>		
							
							<div class="form-group">
								</br>
								<button type="submit" class="btn btn-primary" value="Valider" name="envoyer" > Appliquer</button>
								<a  class="btn btn-info" role="button" href="users">Annuler</a>
							</div>	
					</form>
				</div>
			</div>
			
			<div class="col-md-6 col-sm-6 col-xs-12">
			    <div class="popin">
				<h3>Time Zone Settings</h3>
				<ul>
					<li>Time Zone is used when displaying data in your charts, and when scheduling your aggregator apps.</li>

					
				</ul>
				</div>
			</div>
	