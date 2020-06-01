<!----------------------------------------------------------------------------------
    @fichier  piedDePage.php							    		
    @auteur   Philippe SIMIER (Touchard Washington le Mans)
    @date     Avril 2020
    @version  v2.0 - Second release						
    @details  pied de page pour le site web Aggregator 
------------------------------------------------------------------------------------>
	<?php
	require_once('api/Str.php');
	use Aggregator\Support\Str;
	
	$page = '';
	if  (Str::contains($_SERVER['PHP_SELF'], 'administration')){
		$page = Str::after($_SERVER['PHP_SELF'], 'administration');
	}
	else{
		$page = Str::after($_SERVER['PHP_SELF'], 'Ruche');
	}
    $lien = 'support' . $page;	
	
	?>
	<footer>
		<br /><br />
        <hr>
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<p><a href = "<?php echo $lien ?>">Aggregator - docs for this page</a></p>
			</div>
		
			<div class="col-md-6 col-sm-6 col-xs-12">
				<p style="float: right">Copyright &copy; Section Snir Lycée Touchard - le Mans</p>
			</div>
        </div>
    </footer>