<!----------------------------------------------------------------------------------
    @fichier  administration/support/administration/sms.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page administration/sms.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - SMS</title>
    <!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="/Ruche/css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="/Ruche/scripts/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="/Ruche/css/bootstrap.min.css" >
    <link rel="stylesheet" href="/Ruche/css/ruche.css" />

	<link rel="manifest" href="/Ruche/manifest.json">
	<link rel="icon" type="image/png" href="/Ruche/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/Ruche/favicon-16x16.png" sizes="16x16">
	
</head>

<body>
	
	<?php require_once '../../menu.php'; ?>
	
	<div class="container" >
		<div style="min-height : 500px">	
			<div class="row" style="background-color:white; padding-top: 65px; ">
				<div class="col-lg-12">
					<h4>SMS Vue d'ensemble </h4>
					<p> La vue SMS permet de lire les SMS archivés, envoyés ou recus  par le serveur.
					Il est également possible de créer et d'envoyer un SMS</p></br>
					<h4>Lire les SMS envoyés archivés </h4>
					<p> Cliquer sur l'onglet SMS sent pour afficher le tableau des sms envoyés. la date d'envoie, le numéro du destinataire, le début du message et
					le login du créateur du SMS sont affichés.</p>
					<p> Vous pouvez supprimer un ou plusieurs SMS archivés. Cocher les SMS à supprimer puis cliquer sur le bouton Delete. Une fenêtre de confirmation s'ouvre.
					Valider l'action</p></br>
					<h4>Lire les SMS reçus </h4>
					<p> Cliquer sur l'onglet SMS received pour afficher le tableau des sms recus. la date de reception, 
					le numéro de expéditeur, le message du SMS sont affichés.</p></br>
					
					<h4>Envoyer un SMS </h4>
					<p> Pour envoyer un sms cliquer sur le bouton Write. Une fenêtre popup s'ouvre. Compléter le numero et le message puis cliquer sur Sending </p>
				</div>
			</div>
		</div>
		<?php require_once '../../support/piedDePage.php'; ?>
	</div>
	
	
</body>	