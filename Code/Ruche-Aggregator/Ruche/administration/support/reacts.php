<!----------------------------------------------------------------------------------
    @fichier  administration/support/administration/sms.php
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v1.0 - First release
    @details  support pour la page administration/sms.php
------------------------------------------------------------------------------------>

<?php

//session_start();
require_once "../lang.conf.php";

?>

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

          <?php
					echo "<h4>". $lang['reacts_title1'] . "</h4>";
					echo "<p>" . $lang['reacts_text1a'] . "</p></br>";
					echo "<p>" . $lang['reacts_text1b'] . "</p></br>";
					echo "<h4>". $lang['reacts_title2'] . "</h4>";
					echo "<p>" . $lang['reacts_text2a'] . "</p>";
					echo "<p>" . $lang['reacts_text2b'] . "<ul>";
					echo "<li>". $lang['reacts_text2c'] . "</li>";
					echo "<li>". $lang['reacts_text2d'] . "</li>";
					echo "<li>". $lang['reacts_text2e'] . "</li>";
					echo "<li>". $lang['reacts_text2f'];
					echo "<ul>";
					echo "<li>" . $lang['reacts_text2g'] . "</li>";
					echo "<li>" . $lang['reacts_text2h'] . "</li>";
					echo "<li>" . $lang['reacts_text2i'] ."</li>";
					echo "</ul>";
					echo "<li>" . $lang['reacts_text2j'];
					echo "</ul>";
					echo "<h4>" . $lang['reacts_title3'] . "</h4>";
					echo "<p>" . $lang['reacts_text3a'] . "</p></br>";
					echo "<h4>" . $lang['reacts_title4'] . "</h4>";
					echo "<p>" . $lang['reacts_text4a'] . "</p></br>";
          ?>

				</div>
			</div>
		</div>
		<?php require_once '../../support/piedDePage.php'; ?>
	</div>


</body>
