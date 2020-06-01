<!DOCTYPE html>

<?php
    session_start();
	require_once('definition.inc.php');
 
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Webcam</title>
    <!-- Bootstrap CSS version 4.1.1 -->
        <link rel="stylesheet" href="/Ruche/css/bootstrap.min.css">
	<link rel="stylesheet" href="/Ruche/css/ruche.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="/Ruche/scripts/bootstrap.min.js"></script>
	<script type="text/javascript">
		 "use strict";
		function dateTimeFr(date){
			// les noms de jours / mois
			var jours = new Array("dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi");
			var mois = new Array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "aout", "septembre", "octobre", "novembre", "décembre");
     
			// on construit le message
			var message = jours[date.getDay()] + " ";   // nom du jour
			message += date.getDate() + " ";            // numero du jour
			message += mois[date.getMonth()] + " ";     // nom du mois
			message += date.getFullYear();
			message += " à " + date.getHours() + ":";   
			var minutes = date.getMinutes();
			if(minutes < 10)
				minutes = "0" + minutes;
			message += minutes + ":";
			var secondes = date.getSeconds();
			if(secondes < 10)
				secondes = "0" + secondes;
			message += secondes;
			return message;
		}

		function affiche(){
            var img = document.getElementById("photo");
            img.src = 'http://touchardinforeseau.servehttp.com/Ruche/video/cam.jpg?'+new Date().getMilliseconds();
		    console.log("rafraichissement : " + img.src);	
		}
		
		function main(){
			affiche();
		    setInterval(affiche, 2002);       // appel de la fonction requete_ajax toutes les 2 secondes et 2 milliemes
			
		}
		
        $(document).ready(main);
    </script>

 </head>

 <body>
	<?php require_once 'menu.php'; ?>
	<div class="container" style="padding-top: 65px;">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<pre id="MetaDataSpan"></pre>
				<div >
			        <img src="" class="popin d-block mx-auto img-fluid"  id="photo" alt="Beehive on air" />
					
			    </div>  
				
			</div>

		</div>
		
		<div class="row">
			<a  class="btn btn-info" role="button" href = "export_webcam.php">Download picture</a>
		</div>	
		<?php require_once 'piedDePage.php'; ?>
	</div>

</body>
</html>

