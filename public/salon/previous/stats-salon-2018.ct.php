<div class="container">
	<!-- main title -->
	<div class="row bgwhite">
		<div class="int-padding">
			<h1 class="blue-text text-darken-4">Salon BTLEC Est 2018</h1>
			<br>
		</div>
	</div>
	<div class="row bgwhite">
		<div class="int-padding">
			<h4 class="blue-text text-darken-4" id="salon-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Bilan salon</h4>
			<hr>
			<ul>
				<li>Nombre de participants effectifs sur <?=$nbInscription?> prévus: <strong><?=$nbVenusReels?></strong> ( <?=round($deltaInscritReel) ?> %)</li>
				<li>Participants sans qrcodes : <strong><?= $noScan ?></strong> ( <?=round($deltaScan) ?> %)</li>
			</ul>
			<div id="graph">
				<div id="heuresMardi"></div>
				<div id="heuresMercredi"></div>

			  <div id="venuesCentrales"></div>
			</div>
		</div>
	</div>


	<div class="row bgwhite">
		<div class="int-padding">
			<h4 class="blue-text text-darken-4" id="salon-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Récapitulatif</h4>
			<hr>
			<ul>
				<li>Nombre de participants : <?=$nbInscription?></li>
				<li>Nombre de magasin inscrits : <?= $nbMag ?></li>
				<li>Nombre de repas : <?= $nbRepas ?></li>
				<li>Nombre d'inscrits le 12/06/2018 : <?=$dayOne ?></li>
				<li>Nombre d'inscrits le 13/06/2018 : <?= $dayTwo ?></li>
				<li>Nombre de visite le 12/06/2018 : <?=$visiteOne ?></li>
				<li>Nombre de visite le 13/06/2018 : <?= $visiteTwo?></li>
			</ul>
			<div id="graph">
			  <div id="chartDiv"></div>
			</div>
		</div>
	</div>


	<div class="row bgwhite">
		<div class="int-padding">


			<h4 class="blue-text text-darken-4" id="salon-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>Export</h4>
			<hr>


			<p>Cliquez <a href="export.php" class="blue-link">ici pour exporter la liste des inscrits</a></p>
		</div>
	</div>
	<br>
	<div class="row bgwhite">
		<div class="int-padding">
			<h4 class="blue-text text-darken-4" id="salon-lk"><i class="fa fa-hand-o-right" aria-hidden="true"></i>listing des inscriptions</h4>
			<hr>
			<table class="striped">
				<tr>
					<th>panonceau</th>
					<th>centrale</th>
					<th>magasin</th>
					<th>nom</th>
					<th>prenom</th>
					<th>fonction</th>
					<th>12/06/2018</th>
					<th>13/06/2018</th>
					<th>date visite</th>
					<th>repas</th>
					<th>date inscription</th>
				</tr>
				<?=$listing?>
			</table>
		</div>
	</div>




</div>