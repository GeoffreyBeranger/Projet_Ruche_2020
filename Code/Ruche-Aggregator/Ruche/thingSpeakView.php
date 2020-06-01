<!DOCTYPE html>
<html>
  <head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title>Graphique</title>

	<!-- Bootstrap CSS version 4.1.1 -->
    <link rel="stylesheet" href="/Ruche/css/bootstrap.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="/Ruche/scripts/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="/Ruche/css/ruche.css" />


    <script src="//code.highcharts.com/stock/highstock.js"></script>
    <script src="//code.highcharts.com/stock/modules/data.js"></script>
    <script src="//code.highcharts.com/stock/modules/exporting.js"></script>
    <script src="//code.highcharts.com/stock/indicators/indicators.js"></script>
    <script src="//code.highcharts.com/stock/indicators/ema.js"></script>
    	
    <script>
         
    // placez votre numéro de canal ThingSpeak, et votre clé d'API ici.
    var channelKeys =[];
    channelKeys.push({channelNumber: <?php if (isset($_GET['channel'])) { echo $_GET['channel']; } else {echo 01;} ?>, 
			          key:'<?php if (isset($_GET['key'])) { echo $_GET['key']; }; ?>',
                      fieldList:[<?php if (isset($_GET['field0'])) { echo "{field:".$_GET['field0'].",axis:'P'}"; } else { echo "{field:1,axis:'P'}"; }; 
					   if (isset($_GET['field1'])) { echo ",{field:".$_GET['field1'].",axis:'O'}"; };
					   if (isset($_GET['field2'])) { echo ",{field:".$_GET['field2'].",axis:'O'}"; };
					   if (isset($_GET['field4'])) { echo ",{field:".$_GET['field4'].",axis:'O'}"; };
					   if (isset($_GET['field5'])) { echo ",{field:".$_GET['field5'].",axis:'O'}"; };
					   if (isset($_GET['field6'])) { echo ",{field:".$_GET['field6'].",axis:'O'}"; };
					   if (isset($_GET['field7'])) { echo ",{field:".$_GET['field7'].",axis:'O'}"; };
				     ?>]});
	let urlAggregator = "https://www.thingspeak.com";
	//let urlAggregator = "https://philippes.ddns.net/Ruche";
	//let urlAggregator =   "http://touchardinforeseau.servehttp.com/Ruche"
	

	
	</script>
	<script src="scripts/channelView.js" type="text/javascript"></script>
	

</head>
<body>
	<?php require_once 'menu.php'; ?>
	
	<div style="padding-top: 56px;">
		<div class="popin" id="chart-container" style="height: 600px;">
		</div>

				<div class="popin form-inline" id="below chart"> 
					<div class="form-group">
						<button class="btn btn-primary mb-2 mr-sm-2"  value="Hide All" name="Hide All Button" id="hideAll" >Hide All</button>
						<button class="btn btn-primary mb-2 mr-sm-2"  value="Load More Data" name="Load More Data" id="loadMore" >More Historical Data </button>
						<button class="btn btn-primary mb-2 mr-sm-2"  value="Filter" name="Filter" id="filter" >Setting filter</button>
					</div>
					<select id="Channel_Select" class="form-control mb-2 mr-sm-2"></select>
					<select id="Loads" class="form-control mb-2 mr-sm-2">
						<option value="1" selected="selected">1 Load</option>
						<option value="2">2 Loads</option>
						<option value="3">3 Loads</option>
						<option value="4">4 Loads</option>
						<option value="5">5 Loads</option>
						<option value="6">6 Loads</option>
						<option value="7">7 Loads</option>
						<option value="8">8 Loads</option>
						<option value="9">9 Loads</option>
						<option value="10">10 Loads</option>
						<option value="15">15 Loads</option>
						<option value="20">20 Loads</option>
						<option value="25">25 Loads</option>
						<option value="30">30 Loads</option>
						<option value="40">40 Loads</option>
						<option value="50">50 Loads</option>
					</select>
					<div class="form-check mb-2 mr-sm-2">
						<label class="form-check-label">
						<input class="form-check-input" id="Update" name="Update" type="checkbox">
						Update(Latency)</label>
					</div>
					
				</div>	
	</div>
	
	<?php require_once 'piedDePage.php'; ?>
	
	<!--Fenêtre Modal pour filter-->
	<div class="modal" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenter" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="ModalLongTitle">Setting Filter</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body" id="modal-contenu">
				<div class="form-group">
					<label for="type" class="mr-sm-2">Type </label>
					<select id="type" class="form-control mb-2 mr-sm-2">
						<option value="sma">SMA - Simple Moving Average</option>
						<option value="ema" selected="selected">EMA - Exponential Moving Average</option>
					</select>
				</div>
				
				<div class="form-group">
					<label for="period" class="mr-sm-2">Period </label>
					<select id="period" class="form-control mb-2 mr-sm-2">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="5">5</option>
						<option value="10" selected="selected">10</option>
						<option value="20">20</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
				</div>
			  </div>
			  <div class="modal-footer">
			    <button type="button" class="btn btn-primary btn-afficher" data-dismiss="modal">Appliquer</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  </div>
			</div>
		 </div>
	</div>
	<!--Fin de fenêtre Modal -->
</body>
</html>
