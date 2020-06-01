<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>the connected beehive</title>
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
	
	<div class="row" style="background-color:white; padding-top: 35px; ">
		<div class="col-lg-12">
			
			<a href="index.php">
			<img  style="width:100%;" title="Retour accueil" src="/Ruche/images/bandeau_ruche.png" />
			</a>
		</div>
		
	</div>
	
	<?php require_once 'menu.php'; ?>
	
	<div class="container" >
			
	<div class="row popin" style="padding-top: 35px; ">
		<div class="col-lg-12">
			<h2>La ruche connectée</h2>
			<p>Gagnez en sérénité ! Suivez vos ruches en direct depuis votre smartphone. Economisez votre temps et vos déplacements.</p>
			<p>D’une installation facile et rapide, le système se positionne sous n’importe quelle ruche et délivre en temps réel un suivi précis des grandeurs mesurées, 
			via des vues graphiques. Ces données sont autant d’indices qui permettent à l’apiculteur de surveiller la production à distance. Plus besoin d’inspecter au petit bonheur
			la chance ses ruches disséminées dans la nature, dans des endroits souvent d’accès malaisé.</p> 
			<p> Un SMS prévient de l’augmentation du poids de la ruche, signe du début de la miellée. C'est le moment crucial qui ne dure que de une à trois semaines pour transhumer
            un rucher complet sur les lieux de la floraison.
			En cas de besoin d’intervention, des alertes sont transmises par mail, sms, ou notification sur votre smartphone. Ce service est assurée par IFTTT (if this then that).</p>
		</div>
	</div>
	
	
	<div class="row popin">
	<div class="col-md-4 col-sm-4 col-xs-12">
		<p style="text-align:center;"><img src="/Ruche/images/picto_masse.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Masse</strong></p>
		<p><span class="span_ent_defaut"> La balance indique le bon moment pour : </span></p>
		<ul>
		<li> installer les « hausses » sur lesquelles les abeilles vont travailler </li>
		<li> retirer les hausses, afin de récolter le fruit de leur butinage.</li>
		</ul>
		<p> A la fin de la miellée, une baisse brutale du poids de la ruche – entre 2 et 4 kilos – signale l’essaimage.</p>
		
		<p style="text-align:center;"><img src="/Ruche/images/picto_eclairement.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Eclairement</strong>
		<br><span class="span_ent_defaut">la mesure de l'éclairement évalue la période de pollinisation des abeilles dans la journée</span></p>
	</div>
	
	<div class="col-md-4 col-sm-4 col-xs-12">
		<p style="text-align:center;"><img src="/Ruche/images/picto_temperature.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Température</strong></p>
		<p><span class="span_ent_defaut">La température idéale au sein de la ruche est de 35°C. Les abeilles peuvent produire de la chaleur en utilisant leurs muscles. 
		Cette tension musculaire sans mouvement produit de la chaleur qui réchauffe la partie de la ruche réservée au couvain. 
		En été, lorsque la température du couvain dépasse les 35 °C, le développement des larves est en danger. 
		La thermo-régulation est assurée par l'évaporation de l'eau dans la ruche. ainsi que par la ventilation des ouvrières.</span></p>
		
		<p style="text-align:center;"><img src="/Ruche/images/picto_humidite.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Humidité</strong>
		<br><span class="span_ent_defaut">Suivi de l’état de la ruche (ouverture possible, ajout d’un abreuvoir nécessaire...)</span></p>
		
	</div>
	
	<div class="col-md-4 col-sm-4 col-xs-12">
		<p style="text-align:center;"><img src="/Ruche/images/picto_pression.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Pression atmosphérique</strong>
		<br><span class="span_ent_defaut">Prévision des changements météo influençant le comportement des abeilles.</span></p>
		
		<p style="text-align:center;"><img src="/Ruche/images/picto_autres.png" alt="" width="60" height="60">
		<br><strong style="font-size:18px;color:#000000;line-height:1.4;">Autres fonctionnalités</strong>
		<br><span class="span_ent_defaut">Le tableau de bord intègre également un état des capteurs et de la batterie.</span></p>
	</div>
	
	</div>
	
	<?php 
		require_once 'piedDePage.php'; 
	?>

	</div>
</body>	