<?php
include "authentification/authcheck.php" ;

require_once('../definition.inc.php');
require_once('../api/Api.php');
require_once('../api/Str.php');

use Aggregator\Support\Api;
use Aggregator\Support\Str;

// connexion à la base
    
	$bdd = Api::connexionBD(BASE, $_SESSION['time_zone']);

// construction du titre de la page	
	if ( $_SESSION['droits'] > 1) 
		$title =  "Users"; 
	else 
		$title = "My Account";
	
// Si le formulaire suspending a été soumis
if(isset($_POST['suspending'])){
    try{
	// Si un élément a été sélectionné création de la liste des id à suspendre
		if (count($_POST['array_suspending']) > 0 && $_SESSION['tokenCSRF'] === $_POST['tokenCSRF']){
			$Clef=$_POST['array_suspending'];
			$selection= "(";
			foreach($Clef as $selectValue)
			{
				if( $selection != "(" ){$selection .= ",";}
				$selection .= $selectValue;
			}
			$selection .= ")";
			
			$sql = "UPDATE `data`.`users` SET `allow` =  0 WHERE `users`.`id` IN " . $selection;		
			
			$bdd->exec($sql);
		}
	}	
	catch (\PDOException $ex) 
	{
	   echo($ex->getMessage());       	   
	}
}

// Si le formulaire cancel a été soumis
if(isset($_POST['cancel'])){
	
	try{
	// Si un élément a été sélectionné création de la liste des id à annuler
		if (count($_POST['array_cancel']) > 0 && $_SESSION['tokenCSRF'] === $_POST['tokenCSRF']){
			$Clef=$_POST['array_cancel'];
			$selection= "(";
			foreach($Clef as $selectValue)
			{
				if( $selection != "(" ){$selection .= ",";}
				$selection .= $selectValue;
			}
			$selection .= ")";

			if ($_POST['action'] === 'cancel'){
				$sql = "UPDATE `data`.`users` SET `allow` = 1  WHERE `users`.`id` IN " . $selection;		
			}
			if ($_POST['action'] === 'delete'){
				$sql = "DELETE FROM `data`.`users`   WHERE `users`.`id` IN " . $selection;
				
			}
			$bdd->exec($sql);
		}
	}
	catch (\PDOException $ex) 
	{
	   echo($ex->getMessage());       	   
	}
}

// Création du tokenCSRF
$tokenCSRF = STR::genererChaineAleatoire(32);
$_SESSION['tokenCSRF'] = $tokenCSRF;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Users - Aggregator</title>
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
				columns: [{orderable:false}, {type:"text"}, {type:"text"} , {type:"text"} , {type:"text"}, {type:"text"}]
                
            };
			$('#tableau1').DataTable(options);
			$('#tableau0').DataTable(options);
	
						
			function cocherTout(etat,formulaire)
			{
			  var cases = document.getElementsByClassName(formulaire);   // on recupere tous les éléments ayant la classe 
			   for(var i=0; i<cases.length; i++)     // on les parcourt
				 if(cases[i].type == 'checkbox')     // si on a une checkbox...
					 {cases[i].checked = etat;}
			}
			
			
			$("#all1").click(function(){	
				cocherTout(this.checked,'array_suspending');
			});
			
			
			$("#all0").click(function(){	
				cocherTout(this.checked,'array_cancel');
			});
			
			
			
			$( "#btn_suspending" ).click(function() {
				console.log("Bouton suspending cliqué");
				
				nbCaseCochees = $('.array_suspending:checked').length;
				console.log(nbCaseCochees);
				if (nbCaseCochees > 0){
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirm!',
						content: 'Confirmez-vous la suspension sur ' + nbCaseCochees + ' utilisateur(s) ?',
						buttons: {
							confirm: {
								text: 'Apply', 
								btnClass: 'btn-blue', 
								action: function () {
								$( "#suspending" ).submit(); // soumission du formulaire suspending
								}
							},
					 		cancel: {
								text: 'Cancel', 
								action: function () {}
							}
						}
					});
				
				}
				else{
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You haven't selected any user!"
					});
			
				}
			});
			
			$( "#btn_cancel" ).click(function() {
				console.log("Bouton cancel cliqué");
				
				nbCaseCochees = $('.array_cancel:checked').length;
				console.log(nbCaseCochees);
				if (nbCaseCochees > 0){
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirm!',
						content: 'Confirmez-vous l\'annulation sur ' + nbCaseCochees + ' utilisateur(s) ?',
						buttons: {
							confirm: {
								text: 'Apply', 
								btnClass: 'btn-blue', 
								action: function () {
								$( "#action" ).val("cancel"); // Mise à jour du champ caché action
								$( "#cancel" ).submit(); // soumission du formulaire cancel
								}
							},
					 		cancel: {
								text: 'Cancel', // text for button
								action: function () {}
							}
						}
					});
				
				}
				else{
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You haven't selected any user!"
					});
			
				}
			});
			
			$( "#btn_delete" ).click(function() {
				console.log("Bouton delete cliqué");
				
				nbCaseCochees = $('.array_cancel:checked').length;
				console.log(nbCaseCochees);
				if (nbCaseCochees > 0){
					
					$.confirm({
						theme: 'bootstrap',
						title: 'Confirm!',
						content: 'Confirmez-vous la suppression sur ' + nbCaseCochees + ' utilisateur(s) ?',
						buttons: {
							confirm: {
								text: 'Apply', 
								btnClass: 'btn-blue', 
								action: function () {
								$( "#action" ).val("delete");	
								$( "#cancel" ).submit(); // soumission du formulaire cancel
								}
							},
					 		cancel: {
								text: 'Cancel', // text for button
								action: function () {}
							}
						}
					});
				
				}
				else{
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You haven't selected any user!"
					});
			
				}
			});
			
			
			
			$( "#btn_mod" ).click(function() {
				console.log("Bouton modifier cliqué");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.array_suspending:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You haven't selected any user!"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You have selected several users!"
					});
				}
				if(checkbox_val.length == 1){
					console.log("id = " + checkbox_val[0]);
					
					$.confirm({
						theme: 'bootstrap',
						closeIcon: true, 
						columnClass: 'col-md-6 col-md-offset-3',
						title: 'Change Password',
						content: '' +
						'<form action="" class="passwd form-horizontal">' +
						'<div class="form-group">' +
						'<label class="col-sm-4 control-label">Password : </label>' +
						'<input type="password" id="pwd" name="pwd" size="30"  /><br />' +				
						'</div>' +
						'<div class="form-group">' +
						'<label class="col-sm-4 control-label">Confirm Password : </label>' +
						'<input type="password" id="conf_pwd" name="conf_pwd" size="30"  /><br />' +				
						'</div>' +
						'<input type="hidden"  name="id" value="' + checkbox_val[0] + '"  />' +
						'<input type="hidden" id="key" name="User_API_Key"  value="' + <?php echo "'".$_SESSION['User_API_Key']. "'"; ?> + '"/>' +
						'</form>',
						buttons: {
							formSubmit: {
								text: 'Apply',
								btnClass: 'btn-blue',
								action: function () {
									var pwd = this.$content.find('#pwd').val();
									var conf_pwd = this.$content.find('#conf_pwd').val();
									var form_data = this.$content.find('.passwd').serialize();
																	
									if (pwd != conf_pwd){
										$.alert('Password and Confirm Password not OK');
										return false;
									}
									console.log(' form_data : ' + form_data);
									
									$.getJSON( '../api/changePwd.php' , form_data, function( response,status, error ) {
										console.log("status : " + status);
										console.log("reponse : " +response);
										console.log("error : " +error);
										if (response.status == "202 Accepted"){
											console.log("message Accepted");
											$.dialog({
												title: "Info",
												content: "Change Accepted"
											});	
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
			
			$( "#btn_add" ).click(function() {
				console.log("Bouton Ajouter cliqué");
				
				$.confirm({
					theme: 'bootstrap',
					closeIcon: true, 
					columnClass: 'col-md-6 col-md-offset-3',
					title: 'Add user',
					content: '' +
					'<form action="" class="user form-horizontal">' +
					'<div class="form-group">' +
					'<label class="col-sm-4 control-label">Login : </label>' +
					'<input type="text" id="login" name="login" size="30" placeholder="Login"  /><br />' +
					'</div>' +
					'<div class="form-group">' +
					'<label class="col-sm-4 control-label">API Key : </label>' +
					'<input type="text" id="User_API_Key" name="User_API_Key" size="30" value="' + <?php echo "'".$key = Api::genererKey($bdd). "'"; ?> +'"  /><br />' +				
					'</div>' +
					'<div class="form-group">' +
					'<label class="col-sm-4 control-label">Passwd : </label>' +
					'<input type="password" id="pwd" name="pwd" size="30"  /><br />' +				
					'</div>' +
					'<div class="form-group">' +
					'<label class="col-sm-4 control-label">Confirm Passwd : </label>' +
					'<input type="password" id="conf_pwd" name="conf_pwd" size="30"  /><br />' +				
					'</div>' +
					'<input type="hidden" id="key" name="key"  value="' + <?php echo "'".$_SESSION['User_API_Key']. "'"; ?> + '"/>' +
					'</form>',
					buttons: {
						formSubmit: {
							text: 'Apply',
							btnClass: 'btn-blue',
							action: function () {
								var login = this.$content.find('#login').val();
								var User_API_Key = this.$content.find('#User_API_Key').val();
								var pwd = this.$content.find('#pwd').val();
								var conf_pwd = this.$content.find('#conf_pwd').val();
								var form_data = this.$content.find('.user').serialize();
								
								if(!login || !User_API_Key){
									$.alert('provide a valid login and API Key');
									return false;
								}
								
								if (pwd != conf_pwd){
									$.alert('passwd and Confirm Passwd');
									return false;
								}
								console.log(' form_data : ' + form_data);
								
								$.getJSON( '../api/createUser.php' , form_data, function( response,status, error ) {
									console.log("status : " + status);
									console.log("response.status : " +response.status);
									console.log("error : " +error);
									$.dialog({
										title: "Info",
										content: "Message Accepted"
									});
									window.location = 'users'
										
								}).fail(function(response,status, error) {
									$.dialog({
										title: status,
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
			
			$( "#btn_typeZone" ).click(function() {
				console.log("Bouton Time Zone cliqué");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.array_suspending:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You haven't selected any user!"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You have selected several users!"
					});
				}
				if(checkbox_val.length == 1){
					console.log("timeZone.php?id" + checkbox_val[0]);
					window.location = 'timeZone?id='+checkbox_val[0];
				}
			});
			
			$( "#btn_key" ).click(function() {
				console.log("Generate New API Key clicked");
				
				// Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.array_suspending:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You haven't selected any user!"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You have selected several users!"
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
						'<form action="" class="user form-horizontal">' +
						
						'<div class="form-group">' +
						'<label class="col-sm-4 control-label">API Key : </label>' +
						'<input type="text" id="key" name="key" size="30" value="' + <?php echo "'".$key = Api::genererKey($bdd). "'"; ?> +'"  /><br />' +				
						'</div>' +
						'<input type="hidden"  name="id" value="' + checkbox_val[0] + '"  />' +
						'<input type="hidden" id="User_API_Key" name="User_API_Key"  value="' + <?php echo "'".$_SESSION['User_API_Key']. "'"; ?> + '"/>' +
						'</form>',
						buttons: {
							formSubmit: {
								text: 'Apply',
								btnClass: 'btn-blue',
								action: function () {
									
									var User_API_Key = this.$content.find('#User_API_Key').val();
									var form_data = this.$content.find('.user').serialize();
									
									if(!key){
										$.alert('provide a valid User API Key');
										return false;
									}
									
									console.log(' form_data : ' + form_data);
									
									$.getJSON( '../api/changeKey.php' , form_data, function( response,status, error ) {
										console.log("status : " + status);
										console.log("reponse : " +response);
										console.log("error : " +error);
										if (response.status == "202 Accepted"){
											console.log("message Accepted");
											$.dialog({
												title: "Info",
												content: "Message Accepted"
											});
											setTimeout( function(){window.location = 'users'}, 5000); 								
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
			
			$( "#btn_setting" ).click(function() {
				console.log("Bouton setting cliqué");
				
			    // Ce tableau va stocker les valeurs des checkbox cochées
				var checkbox_val = [];

				// Parcours de toutes les checkbox checkées"
				$('.array_suspending:checked').each(function(){
					checkbox_val.push($(this).val());
				});
				if(checkbox_val.length == 0){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You haven't selected any user!"
					});
				}
				if(checkbox_val.length > 1){
					$.alert({
					theme: 'bootstrap',
					title: 'Alert!',
					content: "You have selected several users!"
					});
				}
				if(checkbox_val.length == 1){
					console.log("user?id" + checkbox_val[0]);
					window.location = 'user?id='+checkbox_val[0];
				}
			});
			
			
		});	
	
	</script>   
 </head>

 <body>
	<?php require_once '../menu.php'; 	?>
	<div class="container" style="padding-top: 65px; max-width: 90%">
	<div class="popin">
		<nav class="nav nav-tabs">
			<a class="nav-item nav-link active" href="#p0" data-toggle="tab">Users</a>
			<a class="nav-item nav-link" href="#p1" data-toggle="tab">Suspended Users</a>
		</nav>
	
		<div class="tab-content">
			<div class="tab-pane fade show active" id="p0">
			<div class="row">
				
				<div class="col-md-12 col-sm-12 col-xs-12">	
					<div class="table-responsive">
						<form method="post" id="suspending">
							<table id="tableau1" class="display"  class="table table-striped">
							
								<thead>
								  <tr>
									<th><input type='checkbox' name='all' value='all' id='all1' ></th>
									<th>Login</th>
									<th>API Key</th>
									<th>Time Zone</th>
									<th>Last sign in</th>
									<th>Count</th>
								  </tr>
								</thead>
								<tbody>
									
									<?php
										$sql = "SELECT * FROM `users`";
										if ($_SESSION['droits'] > 1){
												$sql .= " where allow = 1";
										}		
										else	
												$sql .= " where login = '" . $_SESSION['login'] . "'";
										$sql .= " order by `login` ";
										try{
											$stmt = $bdd->query($sql);
											
											while ($thing =  $stmt->fetchObject()){
												echo "<tr><td><input type='checkbox' class='array_suspending' name='array_suspending[$thing->id]' value='$thing->id' ></td>";
												echo "<td>" . $thing->login . "</td>";
												echo "<td>" . $thing->User_API_Key . "</td>";
												echo "<td>" . $thing->time_zone . "</td>";
												echo "<td>" . $thing->last_sign_in_at . "</td>";
												echo "<td>" . $thing->sign_in_count . "</td></tr>";								
											}
										} 
										catch (\PDOException $ex) 
										{
										   echo($ex->getMessage());       	   
										}
										
									?>
								</tbody>
							</table>

							<button id="btn_mod" type="button" class="btn btn-warning">Change Password</button>
							<button id="btn_typeZone" type="button" class="btn btn-warning">Change Time Zone</button>
							<button id="btn_key" type="button" class="btn btn-warning">Generate New API Key</button>
							<?php
							if ($_SESSION['droits'] > 1){
								echo ' <input id="btn_suspending" name="suspending" value="Suspending" class="btn btn-danger" readonly >';
								echo ' <button id="btn_add" type="button" class="btn btn-secondary">Add</button>';
								echo ' <button id="btn_setting" type="button" class="btn btn-secondary">Setting</button>';
								echo " <input type='hidden' name='tokenCSRF' value='{$tokenCSRF}'>";
							}	
							?>					
						</form>	
					</div>
				</div>
			</div>
			</div>
			<div class="tab-pane fade" id="p1">
				<div class="row">
				
				<div class="col-md-12 col-sm-12 col-xs-12">	
					<div class="table-responsive">
						<form method="post" id="cancel">
						<table id="tableau0" class="display"  class="table table-striped">
						
							<thead>
							  <tr>
								<th><input type='checkbox' name='all' value='all' id='all0' ></th>
								<th>Login</th>
								<th>API Key</th>
								<th>Time Zone</th>
								<th>Last sign in</th>
								<th>Count</th>
							  </tr>
							</thead>
							<tbody>
								
								<?php
								try{
									$sql = "SELECT * FROM `users`";
									if ($_SESSION['droits'] > 1){
											$sql .= " where allow = 0";
									}		
									else	
											$sql .= " where login = '" . $_SESSION['login'] . "' and allow = 0";
									$sql .= " order by `login` ";
									
									$stmt = $bdd->query($sql);
									
									while ($thing =  $stmt->fetchObject()){
										echo "<tr><td><input type='checkbox' class='array_cancel' name='array_cancel[$thing->id]' value='$thing->id' ></td>";
										echo "<td>" . $thing->login . "</td>";
										echo "<td>" . $thing->User_API_Key . "</td>";
										echo "<td>" . $thing->time_zone . "</td>";
										echo "<td>" . $thing->last_sign_in_at . "</td>";
										echo "<td>" . $thing->sign_in_count . "</td>";								
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
						if ($_SESSION['droits'] > 1){
							echo ' <input id="btn_cancel" name="cancel" value="Cancel" class="btn btn-warning" readonly > ';	
							echo ' <input id="btn_delete" name="Delete" value="Delete" class="btn btn-danger" readonly > ';	
							echo ' <input type="hidden" id="action" name="action" value="cancel" >';
							echo " <input type='hidden' name='tokenCSRF' value='{$tokenCSRF}'>";
						}	
						?>					
						</form>	
					</div>
				</div>
			</div>
				
				
				
				
				
				
			</div>
		</div>
	</div>
		<?php require_once '../piedDePage.php'; ?>
	</div>
	
</body>
</html>
	