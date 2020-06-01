/**
 * \file      reacts.php
 * \author    G.BÉRANGER
 * \version   1.0
 * \date       01 Juin 2020
 * \brief       Tableau regroupant les informations sur les reacts
 *
 * \details    Page qui affiche un tableau générer avec l'outil dataTables et qui va chercher
 *             les informations depuis la base de données.
 *             Cette page s'adapte en fonction de la langue de l'utilisateur.
 */
<?php
include "authentification/authcheck.php" ;

require_once "lang.conf.php";
require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

// connexion à la base
$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);

$title = "Reacts";

// Si le formulaire a été soumis
if(isset($_POST['btn_supprimer'])){
	// Si un élément a été sélectionné création de la liste des id à supprimer
	if (count($_POST['table_array']) > 0){
		$Clef=$_POST['table_array'];
		$supp = "(";
		foreach($Clef as $selectValue)
		{
			if($supp!="("){$supp.=",";}
			$supp.=$selectValue;
		}
		$supp .= ")";


		$sql = "DELETE FROM `reacts` WHERE `id` IN " . $supp;
		$bdd->exec($sql);

	}
}

?>
<!DOCTYPE html>

<html>
<head>
    <title>Reacts - Aggregator</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="/Ruche/css/bootstrap.min.css">
	<link rel="stylesheet" href="/Ruche/css/ruche.css" />
	<link rel="stylesheet" href="/Ruche/css/jquery-confirm.min.css" />
	<link rel="stylesheet" href="/Ruche/css/datatables.min.css"/>
	<link rel="stylesheet" href="../css/dataTables.css" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="/Ruche/scripts/bootstrap.min.js"></script>
	<script src="/Ruche/scripts/jquery-confirm.min.js"></script>
	<script src="//cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>

	<script >
		$(document).ready(function(){

			let options = {

								dom: 'ptlf',
								pagingType: "simple_numbers",
                lengthMenu: [5, 10, 15, 20, 40],
                pageLength: 10,
                order: [[1, 'desc']],
								columns: [{orderable:false},  {type:"text"} , {type:"text"} , {type:"text"}, {type:"text"}, {type:"text"},{type:"text"}],
								language: {

														url: "<?php echo $lang['dataTables'] ?>"

													}
            };
			$('#tableau').DataTable(options);

			function cocherTout(etat)
			{
			  var cases = document.getElementsByTagName('input');   // on recupere tous les INPUT
			   for(var i=1; i<cases.length; i++)     // on les parcourt
				 if(cases[i].type == 'checkbox')     // si on a une checkbox...
					 {cases[i].checked = etat;}
			}


			$("#all").click(function(){
				cocherTout(this.checked);
			});


			$( "#btn_supp" ).click(function() {
				console.log("Bouton Supprimer cliqué");

				nbCaseCochees = $('input:checked').length - $('#all:checked').length;
				console.log(nbCaseCochees);
				if (nbCaseCochees > 0){

					$.confirm({
						theme: 'bootstrap',
						title: 'Confirm!',
						content: 'Confirmez-vous la suppression de ' + nbCaseCochees + ' reac(s) ?',
						buttons: {
							confirm: {
								text: 'Confirmation', // text for button
								btnClass: 'btn-blue', // class for the button
								action: function () {
								$( "#supprimer" ).submit(); // soumission du formulaire
								}
							},
					 		cancel: {
								text: 'Annuler', // text for button
								action: function () {}
							}
						}
					});

				}
				else{
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun react !"
					});

				}
			});

			$( "#btn_mod" ).click(function() {
				console.log("Bouton modifier cliqué");

			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.selection:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun react !"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous avez sélectionné plusieurs reacts !"
					});
				}
				if(checkbox_val.length == 1){
					console.log("react?id" + checkbox_val[0]);
					window.location = 'react?id='+checkbox_val[0];
				}
			});

			$( "#btn_add" ).click(function() {
				console.log("Bouton Ajouter cliqué");
				window.location = 'react'
			});
		});

	</script>

 </head>

 <body>
	<?php require_once '../menu.php'; 	?>
	<div class="container" style="padding-top: 65px; max-width: 90%;">
		<div class="row popin card">
			<div class="col-md-12 col-sm-12 col-xs-12">
			<div  class="card-header" style=""><h4><?php echo $title ?></h4></div>
				<div class="table-responsive">
					<form method="post" id="supprimer">
					<table id="tableau" class="table display table-striped table-sm">
						<thead>
						  <tr>
							<th><input type='checkbox' name='all' value='all' id='all' ></th>
							<?php
							echo "<th>". $lang['name'] . "</th>";
							echo "<th>". $lang['channel'] . "</th>";
							echo "<th>". $lang['field'] . "</th>";
							echo "<th>". $lang['condition'] . "</th>";
							echo "<th>". $lang['condition_value'] . "</th>";
							echo "<th>". $lang['action_name'] . "</th>";
							?>
						  </tr>
						</thead>
						<tbody>
							<?php




							try{
								$sql = "select * from `vue_reacts`";
                                if ($_SESSION['droits'] > 1)
										$sql .= " WHERE 1 ";
								else
								        $sql .= " where id_user = '" . $_SESSION['id'] . "'";
								//$sql .= " order by `reacts.id` ";


								$stmt = $bdd->query($sql);

								while ($react =  $stmt->fetchObject()){
										$sql_field = "select field{$react->field} as field from channels where id = {$react->channel_id}";
										$stmt_field = $bdd->query($sql_field);
										$field = $stmt_field->fetchObject();
									echo "<tr><td><input class='selection' type='checkbox' name='table_array[$react->id]' value='$react->id' ></td>";
									echo "<td>" . $react->name . "</td>";
									echo "<td>" . $react->channel . "</td>";
									echo "<td>" . $field->field . "</td>";
									echo "<td>" . $react->condition_type . "</td>";
									echo "<td>" . $react->value . "</td>";
									echo "<td>" . $react->action . "</td>";
									echo "</tr>";
								}
							}
							catch (\PDOException $ex)
								{
								   echo($ex->getMessage());
								}





							?>
						</tbody>
					</table>
					<?php
					     echo '<button id="btn_add" type="button" class="btn btn-secondary">'.$lang['add'].'</button>';
							 echo ' <button id="btn_mod" type="button" class="btn btn-secondary">'.$lang['edit_settings'].'</button> ';
							 echo ' <input id="btn_supp" name="btn_supprimer" value="'.$lang['delete'].'" class="btn btn-danger" readonly size="9">';
					 ?>
					<!-- <button id="btn_add" type="button" class="btn btn-secondary"></button> -->
					<!-- <button id="btn_mod" type="button" class="btn btn-secondary">Edit settings</button> -->
					<!-- <input id="btn_supp" name="btn_supprimer" value="Delete" class="btn btn-danger" readonly size="9"> -->
					</form>
				</div>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
</body>
</html>
