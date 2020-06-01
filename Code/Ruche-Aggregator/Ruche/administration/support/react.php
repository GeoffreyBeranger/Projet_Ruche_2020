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

    <title>Support - REACT</title>
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
					<h4>Vue d'ensemble de reacts (réagir aux évènements)</h4>
					<p>Réagissez lorsque les données d'un canal remplissent certaines conditions</p>
					<p>les Reacts fonctionnent avec les applications ThingHTTP, pour effectuer des actions lorsque les données d'un canal remplissent une certaine condition.
					Vous pouvez configurer un react pour notifier un évenement par SMS ou Mail.
					Par exemple lorsque le poids d'une ruche chute soudainement, demandez à ThingHTTP d'envoyer un SMS contenant une description de l'évènement.</p></br>
					<p></p>
					<h4>Configurer un react </h4>
					<p>Sélectionner:
					<ul><li>Fréquence de test</li>
						<li>Action</li>
						<li>Options</li>
						<li>Cliquez sur appliquer</li>
					</ul>
					</p>
					<p>Condition sur les données dans un champ de canal :
					<ul><li>est supérieur à</li>
						<li>est supérieur ou égal à</li>
						<li>est inférieur à</li>
						<li>est inférieur ou égal à</li>
						<li>est égal à</li>
						<li>n'est pas égal à</li>
					</ul>
					</p>
				</div>
			</div>
		</div>
		<?php require_once '../../support/piedDePage.php'; ?>
	</div>


</body>
