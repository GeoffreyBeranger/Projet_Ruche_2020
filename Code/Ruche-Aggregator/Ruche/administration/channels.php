<!-- /**
 * \file      channels.php
 * \author    G.BÉRANGER
 * \version   1.0
 * \date       01 Juin 2020
 * \brief       Tableau regroupant les informations sur les channels
 *
 * \details    Page qui affiche un tableau générer avec l'outil dataTables et qui va chercher
 *             les informations depuis la base de données.
 *             Cette page s'adapte en fonction de la langue de l'utilisateur.
 */ -->

<?php
include "authentification/authcheck.php" ;

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

// connexion à la base
$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);

$title = "Channels";

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


		$sql = "DELETE FROM `feeds` WHERE `id_channel` IN " . $supp;
		$bdd->exec($sql);
		$sql = "DELETE FROM `channels` WHERE `id` IN " . $supp;
		$bdd->exec($sql);
	}
}

?>
<!DOCTYPE html>

<html>
<head>
    <title>Channels - Aggregator</title>
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
				columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"} , {type:"text"}, {type:"text"}, {type:"text"}]

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
						content: 'Confirmez-vous la suppression de ' + nbCaseCochees + ' objet(s) ?',
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
					content: "Vous n'avez sélectionné aucun objet !"
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
					content: "Vous n'avez sélectionné aucun objet !"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous avez sélectionné plusieurs objets !"
					});
				}
				if(checkbox_val.length == 1){
					console.log("channel.php?id" + checkbox_val[0]);
					window.location = 'channel?id='+checkbox_val[0];
				}
			});

			$( "#btn_csv" ).click(function() {
				console.log("Bouton download CSV cliqué");

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
					content: "Vous n'avez sélectionné aucun objet !"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous avez sélectionné plusieurs objets !"
					});
				}
				if(checkbox_val.length == 1){
					console.log("../api/feeds.php?channelId="+ checkbox_val[0] + "&type=csv");
					window.location = "../api/feeds.php?channelId="+ checkbox_val[0] + "&type=csv";
				}
			});

			$( "#btn_clear" ).click(function() {
				console.log("Bouton download Clear cliqué");

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
					content: "Vous n'avez sélectionné aucun objet !"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous avez sélectionné plusieurs objets !"
					});
				}
				if(checkbox_val.length == 1){
					console.log("../api/clearChannel.php?channelId" + checkbox_val[0]);
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirm!',
						content: 'Are you sure you want to clear the channel id <b>' + checkbox_val[0] + '</b> ?',
						buttons: {
							confirm: {
								text: 'Confirmation', // text for button
								btnClass: 'btn-blue', // class for the button
								action: function () {
									console.log("Action clear confirmée");

									$.getJSON( '../api/clearChannel.php' , 'channelId='+checkbox_val[0], function( response,status, error ) {
										console.log("status : " + status);
										console.log("reponse : " +response);
										console.log("error : " +error);
										if (response.status == "202 Accepted"){
											console.log("message Accepted");
											$.dialog({
												title: "Info",
												content: "Clear Accepted"
											});
											window.location = 'channels'
										}
										else{
											$.dialog({
												title: "Erreur",
												content: response.message + " <em>" + response.detail + "</em>"
											});
										}
									});




								}
							},
					 		cancel: {
								text: 'Annuler', // text for button
								action: function () {}
							}
						}
					});
				}
			});

			$( "#btn_add" ).click(function() {
				console.log("Bouton Ajouter cliqué");
				window.location = 'channel'


			});



			$( "#btn_key" ).click(function() {
				console.log("Generate New API Key clicked");

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
					content: "Vous n'avez sélectionné aucun objet !"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous avez sélectionné plusieurs objets !"
					});
				}
				if(checkbox_val.length == 1){
					console.log("id = " + checkbox_val[0]);
					$.confirm({
						theme: 'bootstrap',
						closeIcon: true,
						columnClass: 'col-md-6 col-md-offset-3',
						title: 'Generate New API Key',
						content: '' +
						'<form action="" class="channel form-horizontal">' +

						'<div class="form-group">' +
						'<label class="col-sm-4 control-label">API Key : </label>' +
						'<input type="text" id="key" name="key" size="30" value="' + <?php echo "'".$key = Str::genererChaineAleatoire(). "'"; ?> +'"  /><br />' +
						'</div>' +
						'<input type="hidden"  name="id" value="' + checkbox_val[0] + '"  />' +
						'<input type="hidden" id="User_API_Key" name="User_API_Key"  value="' + <?php echo "'".$_SESSION['User_API_Key']. "'"; ?> + '"/>' +
						'</form>',
						buttons: {
							formSubmit: {
								text: 'Appliquer',
								btnClass: 'btn-blue',
								action: function () {

									var User_API_Key = this.$content.find('#User_API_Key').val();
									var form_data = this.$content.find('.channel').serialize();

									if(!key){
										$.alert('provide a valid Write API Key');
										return false;
									}

									console.log(' form_data : ' + form_data);

									$.getJSON( '../api/changeWriteAPIKey.php' , form_data, function( response,status, error ) {
										console.log("status : " + status);
										console.log("reponse : " +response);
										console.log("error : " +error);
										if (response.status == "200 OK"){
											console.log("message Accepted");
											$.dialog({
												title: "Info",
												content: "message Accepted"
											});
											setTimeout( function(){window.location = 'channels'}, 5000);
										}
										else{
											$.dialog({
												title: "Erreur",
												content: response.message + " <em>" + response.detail + "</em>"
											});
										}
									});
								}
							},
							cancel: function () {
								//close
							},
						},
						onContentReady: function () {
							// bind to events
							var jc = this;
							this.$content.find('form').on('submit', function (e) {
								// if the user submits the form by pressing enter in the field.
								e.preventDefault();
								jc.$$formSubmit.trigger('click'); // reference the button and click it
							});
						}
					});
				}


			});

		});

	</script>

 </head>

 <body>
	<?php require_once '../menu.php'; 	?>
	<div class="container" style="padding-top: 65px;">
		<div class="row popin card">
			<div class="col-md-12 col-sm-12 col-xs-12">
			<div  class="card-header" style=""><h4><?php echo $title ?></h4></div>
				<div class="table-responsive">
					<form method="post" id="supprimer">
					<table id="tableau" class="table display table-striped table-sm">
						<thead>
						  <tr>
							<th><input type='checkbox' name='all' value='all' id='all' ></th>
							<th>id</th>
							<th>Name</th>
							<th>tags</th>
							<th>Write API Key</th>
							<th>Last entry id</th>
							<th>Last write entry</th>
						  </tr>
						</thead>
						<tbody>
							<?php
								try{
									$sql = "SELECT * FROM `users_channels`";
									if ($_SESSION['droits'] > 1)
											$sql .= " where 1";
									else
											$sql .= " where user_id = '" . $_SESSION['id'] . "'";

									$sql .= " order by `tags` ";

									$stmt = $bdd->query($sql);

									while ($channel =  $stmt->fetchObject()){
										echo "<tr><td><input class='selection' type='checkbox' name='table_array[$channel->id]' value='$channel->id' ></td>";
										echo "<td>" . $channel->id . "</td>";
										echo "<td>" . $channel->name . "</td>";
										echo "<td>" . $channel->tags . "</td>";
										echo "<td>" . $channel->write_api_key . "</td>";
										echo "<td>" . $channel->last_entry_id . "</td>";
										echo "<td>" . $channel->last_write_at . "</td>";
										echo "</tr>";
									}
								}
								catch (\PDOException $ex)
								{
									echo($ex->getMessage());
									return;
								}
							?>
						</tbody>
					</table>

					<button id="btn_mod" type="button" class="btn btn-secondary">Edit settings</button>
					<button id="btn_add" type="button" class="btn btn-secondary">Add</button>
					<button id="btn_key" type="button" class="btn btn-warning">Generate New API Key</button>
					<button id="btn_csv" type="button" class="btn btn-secondary">Download CSV</button>
					<button id="btn_clear" type="button" class="btn btn-danger">Clear all feed</button>
					<input id="btn_supp" name="btn_supprimer" value="Delete" class="btn btn-danger" readonly size="9">
					</form>
				</div>
			</div>
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
</body>
</html>
