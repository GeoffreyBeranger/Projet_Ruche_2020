<!----------------------------------------------------------------------------------
    @fichier  support/index.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page administration/users.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Users</title>
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
		<h4>Vue d'ensemble</h4>
		<p>Un administrateur peut parcourir et rechercher la liste de tous les comptes d'utilisateur avec l'option <mark>Users</mark> du menu.</p>
		<p>L'administrateur peut rechercher un utilisateur particulier en utilisant son login comme terme de recherche, puis modifier son profil. 
		La modification du profil d'un utilisateur est un moyen de réinitialiser les mots de passe de l'utilisateur lorsqu'il ne peut pas se connecter.</p>
		<p>Un administrateur peut également déverrouiller un compte avec trop de tentatives de connexion infructueuses</p>
		
		<h4>Ajouter des utilisateurs</h4>
		<p>Des utilisateurs peuvent être ajoutés à la plateforme Aggregator. Lorsqu'un utilisateur est inscrit sur l'aggregator, cela lui permet de s'y connecter. 
		   Ce processus de connexion est appelé Authentification. 
		   Seul l'administrateur est autorisé à ajouter des utilisateurs à la plateforme. Il peut également suspendre  un utilisateur.
		</p>
		<h4>Création manuelle de comptes par l'administrateur</h4>
		<p>En tant qu'administrateur, vous pouvez ajouter des utilisateurs un à un. Pour ce faire, cliquer sur le bouton <mark>Add</mark>. Une fenêtre popup s'ouvre.
		Compléter les champs login password et confirm password. Une clé api est créé automatiquement.
		</p>
		<h4>Modification d'un compte par l'administrateur</h4>
		<p>L'administrateur a la possibilité de modifier les informations d'un compte. Cliquer sur la case à cocher relative au compte à modifier puis sur le bouton <mark>Setting</mark>.
		Le formulaire user s'ouvre. Modifier les champs puis cliquer sur <mark>Apply</mark>. Vous pouvez modifier le login, la clé API, le quota journalier de SMS, 
		le delai entre deux SMS consécutifs etc.
		</p>
		<h4>Suspension d'un compte</h4>
		<p>Les utilisateurs dont le compte est suspendu ne peuvent plus se connecter ou utiliser les services Web de la plateforme</p>
		<p>Un administrateur peut suspendre temporairement un compte utilisateur en sélectionnant un compte dans la liste puis en cliquant sur le bouton <mark>Suspending</mark>.
		La liste des utilisateurs suspendus peut être visualisée en cliquant sur le bouton <mark>Suspended Users</mark></p>
		<p>La suspension d'un compte utilisateur peut être retirée en cliquant sur le bouton <mark>Cancel</mark></p>
			
			
		</div>		
	</div>		
	
	
		</div>
		<?php require_once '../../support/piedDePage.php'; ?>
	</div>
	
	
</body>	