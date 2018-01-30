<div class="container">
	<h1 class="blue-text text-darken-4">ODR - BRII - TICKETS</h1>
	<!-- nav tab -->
	<ul class="nav nav-tabs">
	  <li class="nav-item"><a href="#current" class="nav-link active">En cours</a></li>
	  <li class="nav-item"><a href="#next" class="nav-link">A venir</a></li>
	</ul>

<!-- <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="#">Active</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Link</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled" href="#">Disabled</a>
  </li>
</ul>
 -->
	<br><br>



	<!-- formulaire d'uplaod -->
	<div class="row">
		<div class="col">

			<h4 class="blue-text text-darken-4" id="current"><i class="fa fa-hand-o-right" aria-hidden="true"></i>ODR, BRII, TICKETS EN COURS :</h4>
			<hr>
			<br><br>
			<table width="100%" class="table table-bordered" id="">
				<thead>
					<tr>
						<th>Nom de l'opération</th>
						<th>Marque</th>
						<th>GT</th>
						<th>date de début</th>
						<th>date de fin</th>
						<th>fichiers joints</th>
					</tr>
				</thead>
				<tbody>
					<?= $currentOdrHtml?>
				</tbody>
			</table>
		</div>

	</div>
	<br><br>

<div class="row">
		<div class="col">

			<h4 class="blue-text text-darken-4" id="next"><i class="fa fa-hand-o-right" aria-hidden="true"></i>ODR, BRII, TICKETS A VENIR :</h4>
			<hr>
			<br><br>
			<table width="100%" class="table table-bordered" id="">
				<thead>
					<tr>
						<th>Nom de l'opération</th>
						<th>Marque</th>
						<th>GT</th>
						<th>date de début</th>
						<th>date de fin</th>
						<th>fichiers joints</th>
					</tr>
				</thead>
				<tbody>
					<?= $nextOdrHtml  ?>
				</tbody>
			</table>
		</div>

	</div>



</div>
	<br><br>
