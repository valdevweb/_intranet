<div class="row mt-3">
	<div class="col">
		<div class="row">
			<div class="col">
				<h5 class="khand text-main-blue pb-3">Intervenir sur le dossier :</h5>
			</div>
		</div>
		<div class="row">
			<!-- <div class="col"></div> -->
			<div class="col-auto">
				<p class="text-right"><a href="bt-analyse.php?id=<?=$_GET['id']?>" class="btn btn-primary"><i class="fas fa-chart-area pr-3"></i>Analyser litige</a></p>
			</div>

			<div class="col-auto">
				<p class="text-right"><a href="bt-action-add.php?id=<?=$_GET['id']?>" class="btn btn-red"><i class="fas fa-plus-square pr-3"></i>Ajouter une action</a></p>
			</div>
			<div class="col-auto">
				<p class="text-right"><a href="bt-contact.php?id=<?=$_GET['id']?>" class="btn btn-kaki"><i class="fas fa-comment pr-3"></i>Contacter le magasin</a></p>
			</div>
			<div class="col-auto">
				<p class="text-right"><a href="bt-info-litige.php?id=<?=$_GET['id']?>" class="btn btn-yellow"><i class="fas fa-highlighter pr-3"></i>Ajouter des informations</a></p>
			</div>
			<div class="col-auto">
				<p class="text-right"><a href="bt-generate-fiche.php?id=<?=$_GET['id']?>" class="btn btn-black" target="_blank"><i class="fas fa-print pr-3"></i>Imprimer</a></p>
			</div>

			<!-- <div class="col"></div> -->
		</div>
	</div>

</div>