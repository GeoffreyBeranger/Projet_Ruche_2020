<!----------------------------------------------------------------------------------
    @fichier  support/thingSpeakView.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release						
    @details  support pour la page thingSpeakView.php
------------------------------------------------------------------------------------>

<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Support - Channel View</title>
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
	
	<?php require_once '../menu.php'; ?>
	
	<div class="container" style="padding-top: 75px;" >
		<div style="min-height : 500px">
			<div class="row" style="background-color:white; padding-top: 10px; ">
				<div class="col-lg-12">
					<h2>Visualisation des données</h2>
					<p></p>	
					<img src="images/ThingSpeakView.PNG" class="img-fluid" alt="Graphic view" >	
					
					
				</div>
			</div>
		</div>			
	</div>
	<?php require_once 'piedDePage.php'; ?>
	
</body>	