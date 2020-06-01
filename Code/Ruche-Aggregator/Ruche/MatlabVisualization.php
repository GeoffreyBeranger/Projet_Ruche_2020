<!DOCTYPE html>

<?php
    session_start();
    require_once('definition.inc.php');
?>

<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type">
	<!-- Bootstrap CSS version 4.1.1 -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="scripts/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/ruche.css" />
	<title><?php echo $_GET['name'] ?></title>
    </head>
    <body>
	<?php require_once 'menu.php'; ?>
	<div class="container" style="padding-top: 56px;">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
			    <div class="popin responsive-video" >
			        <iframe class="col-lg-12 col-md-12 col-sm-12" style="border: 1px solid #fff; height:630px" src="
					<?php echo "https://thingspeak.com/apps/matlab_visualizations/" . $_GET['id'] . "?height=600" ?>
					">
					</iframe>
			    </div>
			</div>
		</div>
                
	<?php require_once 'piedDePage.php'; ?>
</body>
</html>
