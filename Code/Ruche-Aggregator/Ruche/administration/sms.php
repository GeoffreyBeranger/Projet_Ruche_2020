<?php
include "authentification/authcheck.php" ;

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

//  Ceci est une fct du modele SMS
$bdd = Api::connexionBD(BASESMS,$_SESSION['time_zone']);
$title = "SMS - Aggregator";

// Si le formulaire supprimer sent à été soumis
if(isset($_POST['btn_supp_sent'])){
	
	// Si un élément a été sélectionné
	if (count($_POST['array_sent']) > 0){
		$Clef=$_POST['array_sent'];
		$supp = "(";
		foreach($Clef as $selectValue)
		{
			if($supp!="("){$supp.=",";}
			$supp.=$selectValue;
		}
		$supp .= ")";

		$sql = "DELETE FROM `sentitems` WHERE `ID` IN " . $supp;
		$bdd->exec($sql);
	}
}

// Si le formulaire supprimer receive à été soumis
if(isset($_POST['btn_supp_receive'])){
	
	// Si un élément a été sélectionné
	if (count($_POST['array_receive']) > 0){
		$Clef=$_POST['array_receive'];
		$supp = "(";
		foreach($Clef as $selectValue)
		{
			if($supp!="("){$supp.=",";}
			$supp.=$selectValue;
		}
		$supp .= ")";

		$sql = "DELETE FROM `inbox` WHERE `ID` IN " . $supp;
		
		$bdd->exec($sql);
	}
}



if ($_SESSION['droits'] > 1){		
		$sql = "SELECT `SendingDateTime`,`DestinationNumber`,`TextDecoded`,`CreatorId`,`ID` FROM `sentitems` order by `SendingDateTime` desc ";
}else{
		$sql = "SELECT `SendingDateTime`,`DestinationNumber`,`TextDecoded`,`CreatorId`,`ID` FROM `sentitems` where `CreatorId` = '".$_SESSION['login']."' order by `SendingDateTime` desc ";
}
	
$stmt = $bdd->query($sql);	

?>


<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title ?></title>
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
			
		    let options0 = {
                dom: 'ptlf',
                pagingType: "simple_numbers",
                lengthMenu: [5, 10, 15, 20, 40],
                pageLength: 10,
                order: [[1, 'desc']],
				columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"} , {type:"text"}]
                
            };
			$('#tableau0').DataTable(options0);
			
			let options1 = {
                dom: 'ptlf',
                pagingType: "simple_numbers",
                lengthMenu: [5, 10, 15, 20, 40],
                pageLength: 10,
                order: [[1, 'desc']],
				columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"}]
                
            };
			$('#tableau1').DataTable(options1);
			
			function cocherTout(etat,formulaire)
			{
			  var cases = document.getElementsByClassName(formulaire);   // on recupere tous les éléments ayant la classe formulaire
			   for(var i=0; i<cases.length; i++)     // on les parcourt
				 if(cases[i].type == 'checkbox')     // si on a une checkbox...
					 {cases[i].checked = etat;}
			}
			
			
			$("#all_sent").click(function(){	
				cocherTout(this.checked, 'array_sent');
			});
			
			$("#all_receive").click(function(){	
				cocherTout(this.checked, 'array_receive');
			});
			
			
			$( "#btn_supp_sent" ).click(function() {
				
				nbCaseCochees = $('.array_sent:checked').length;
				if(nbCaseCochees == 1) 
					message = "1 message envoyé sera supprimé.";
				else
					message = nbCaseCochees + " messages envoyés seront supprimés.";
				
				if (nbCaseCochees > 0){
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirmation !',
						content: message,
						buttons: {
							cancel: {
								text: 'Annuler', // text for button
								action: function () {}
							},
							confirm: {
								text: 'Confirmer', // text for button
								btnClass: 'btn-blue', // class for the button
								action: function () {
								$( "#supp_sent" ).submit(); // soumission du formulaire
								
								}
							}
					 		
						}
					});
				
				}
				else{
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun message !"
					});
			
				}
			});
			
			$( "#btn_lire_sent" ).click(function() {
				console.log("Bouton lire_sent cliqué");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox class array_sent checkées"
				$('.array_sent:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun objet !"
					});
				}else{
				
					var url = "../api/lireSMS.php?key=azerty&id=" + checkbox_val[0] + "&folder=sent"
					$.get(url, function(data, status){
						if (status == "success")
							console.log(data);
							$.dialog({
								title: data.number,
								content: data.text + "<br/><br/>" + data.date,
							});
					});
					
				}
			});
			
			// Action sur les boutons ecrire
			$( ".btn_ecrire" ).click(function() {
				console.log("Bouton ecrire cliqué");
				$.confirm({
					title: 'Write a SMS',
					content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label class="font-weight-bold">Number : </label><br />' +
					'<input type="text" id="number" name="number" size="12" placeholder="Number"  /><br />' +
					'</div>' +
					'<div class="form-group">' +
					'<label class="font-weight-bold">Message : </label>' +
					'<textarea  rows="5" id="message" name="message" maxlength="160" class="form-control" ></textarea>' +
					'</div>' +
					'<input type="hidden" id="key" name="key"  value="' + <?php echo "'".$_SESSION['User_API_Key']. "'"; ?> + '"/>' +
					'</form>',
					buttons: {
						formSubmit: {
							text: 'Sending',
							btnClass: 'btn-blue',
							action: function () {
								var message = this.$content.find('#message').val();
								var number = this.$content.find('#number').val();
								var form_data = this.$content.find('.formName').serialize();
								
								if(!message || isNaN(number)){
									$.alert('provide a valid number and message');
									return false;
								}
								console.log(' form_data : ' + form_data);
								
								$.getJSON( '../api/sendSMS.php' , form_data, function( response,status, error ) {
									console.log("status : " + status);
									console.log("reponse : " +response);
									console.log("error : " +error);
									if (response.status == "202 Accepted"){
										console.log("message Accepted");
										$.dialog({
											title: "Info",
											content: "Message Accepted"
										});
										setTimeout( function(){window.location = 'sms'}, 10000);
									}	
									else{
										$.dialog({
											title: "Erreur",
											content: response.message + " <em>" + response.detail + "</em>"
										});
									}
									
								}).fail(function(response,status, error) {
									console.log("status : " + status);
									console.log("reponse : " + response.detail);
									console.log("error : " + error);
									$.dialog({
										title: "Erreur",
										content: error
									});
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
			});
			
			
			// Action sur le bouton supprimer SMS receive
			$( "#btn_supp_receive" ).click(function() {
				console.log("Bouton Supp_receive cliqué");
				
				nbCaseCochees = $('.array_receive:checked').length;
				
				if(nbCaseCochees == 1) 
					message = "1 message reçu sera supprimé.";
				else
					message = nbCaseCochees + " messages reçus seront supprimés.";
				
				if (nbCaseCochees > 0){
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirmation!',
						content: message,
						buttons: {
							cancel: {
								text: 'Annuler', // text for button
								action: function () {}
							},
							confirm: {
								text: 'Confirmer', // text for button
								btnClass: 'btn-blue', // class for the button
								action: function () {
									$( "#supp_receive" ).submit(); // soumission du formulaire
								}
							}				 		
						}
					});
				
				}
				else{
					$.alert({
					theme: 'bootstrap',	
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun message !"
					});
			
				}
			});
			
			// Action sur le bouton lire SMS reçu
			$( "#btn_lire_receive" ).click(function() {
				console.log("Bouton lire_receive cliqué");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.array_receive:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "Vous n'avez sélectionné aucun SMS reçu !"
					});
				}else{
				
					var url = "../api/lireSMS.php?key=azerty&id=" + checkbox_val[0] + "&folder=inbox"
					$.get(url, function(data, status){
						if (status == "success")
							console.log(data);
							$.dialog({
								title: data.number,
								content: data.text + "<br/><br/>" + data.date,
							});
					});
				}		
				
			});
			
		});	
	
		
		
	</script>
    
 </head>

 <body>
	<?php require_once '../menu.php'; 	?>
	<div class="container" style="padding-top: 65px; max-width: 90%;">
		<div class="popin">
			<nav class="nav nav-tabs">
				<a class="nav-item nav-link active" href="#p0" data-toggle="tab">SMS sent</a>
				<a class="nav-item nav-link" href="#p1" data-toggle="tab">SMS Received</a>
				<a class="nav-item nav-link" href="#p2" data-toggle="tab">GSM Modem</a>
			</nav>
			<div class="tab-content">
				<div class="tab-pane fade show active" id="p0">
					<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">	
						<div class="table-responsive">
							<form method="post" id="supp_sent">
							<table id="tableau0" class="display table table-striped" >
								<thead>
								  <tr>
									<th><input type='checkbox' name='all_sent' value='all_sent' id='all_sent' ></th>
									<th>Date of issue</th>
									<th>To</th>
									<th>Message</th>
									<th>Creator</th>
									
								  </tr>
								</thead>
								<tbody>
									
									<?php
																	
										while ($message =  $stmt->fetchObject()){
											echo "<tr>\n    <td><input type='checkbox' class='array_sent' name='array_sent[$message->ID]' value='$message->ID' ></td>\n";
											echo "    <td>" . $message->SendingDateTime . "</td>\n";
											echo "    <td>" . $message->DestinationNumber . "</td>\n";
											echo "    <td>" . Str::reduire($message->TextDecoded) . "</td>\n";
											echo "    <td>" . $message->CreatorId . "</td>\n</tr>\n";
											
										}
									?>
								</tbody>
							</table>
							<input id="btn_supp_sent" name="btn_supp_sent" value="Delete" class="btn btn-danger" readonly size="9">
							<button id="btn_lire_sent" type="button" class="btn btn-info">Read</button>
							<button  type="button" class="btn btn-info btn_ecrire">Write</button>
							</form>	
						</div>			
					</div>
					</div>
				</div>
				<div class="tab-pane fade" id="p1">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="table-responsive">
							<form method="post" id="supp_receive">
								<table id="tableau1" class="display table table-striped">
									<thead>
									  <tr>
										<th><input type='checkbox' name='all_receive' value='all_receive' id='all_receive' ></th>
										<th>Date of receipt</th>
										<th>From</th>
										<th>Message</th>														
									  </tr>
									</thead>
									<tbody>
										
										<?php
											
											$sql = "SELECT `ReceivingDateTime`,`SenderNumber`,`TextDecoded`,`ID` FROM `inbox` order by `ReceivingDateTime` desc ";
											
											$stmt = $bdd->query($sql);
											
											while ($message =  $stmt->fetchObject()){
												echo "<tr>\n    <td><input type='checkbox' class='array_receive' name='array_receive[$message->ID]' value='$message->ID' ></td>\n";
												echo "    <td>" . $message->ReceivingDateTime . "</td>\n";
												echo "    <td>" . $message->SenderNumber . "</td>\n";
												echo "    <td>" . Str::reduire($message->TextDecoded) . "</td>\n";
												echo "</tr>\n";
												
											}
										?>
									</tbody>
								</table>
								<input id="btn_supp_receive" name="btn_supp_receive" value="Delete" class="btn btn-danger" readonly size="9">
								<button id="btn_lire_receive" type="button" class="btn btn-info">Read</button>
								<button  type="button" class="btn btn-info btn_ecrire">Write</button>			
							</form>	
						</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="p2">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
								<br /><br />
								<?php
									$sql = "SELECT * FROM `phones` WHERE 1";
									$stmt = $bdd->query($sql);
									$modem = $stmt->fetchObject();
									if ($modem){
											echo "<h5>Modem GSM</h5>";
											echo "Date de démarrage : " . $modem->InsertIntoDB . "<br />";
											echo "IMEI : " . $modem->IMEI . "<br />";
											echo "Signal : " . $modem->Signal . "<br />";
									} else {
											echo "<h5>Modem GSM absent !!</h5>";
									}	
								?>
						</div>
					</div>	
				</div>
			</div>	
		</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>

	
	
</body>
</html>
	