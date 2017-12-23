<div class="container">
	<!-- titre -->
	<h1 class="light-blue-text text-darken-2">Demandes magasin</h1>

	<!-- select -->
	<div class="row">
		<form class="browser-default" action="request.ct.php" >
			<select class="browser-default"  name="services" id="services">
				<option name="default" id="default" value="default">Tous les services</option>
				<?php foreach ($services as $service): ?>
					<option name='<?= $service['id']?>' value='<?= $service['id']?>' >   <?= $service['full_name']?></option>
				<?php endforeach ?>
			</select>
		</form>
	</div>
<!-- debut boucle pour affichage des resultats => messages envoyés au services -->
	<div id="list-msg">
		<?php foreach ($msg as $key => $value): ?>
		<!-- un message  -->
		<article class="msg"  data-service='<?= $value['id_service']; ?>' >
			<!-- entete -->
			<div class="row grey lighten-4 box-border" >
				<?php $idMag=$value['id_mag'];?>
				<?php $magInfo=getMag($pdoBt,$idMag); ?>
				<div class="col l3">
					<p class="boldtxt">MAGASIN :
						<?= $magInfo['mag'] ?>
					</p>
				</div>
				<div class="col l3">
					<p class="boldtxt">
						<?= $magInfo['city'] . ' - ' .$magInfo['cp']  ?>
					</p>
				</div>
				<div class="col l3">
					<p class="boldtxt">Code BTLec :
						<?= $magInfo['btlec']  ?>
					</p>
				</div>
				<div class="col l3">
					<p class="boldtxt">Panonceau galec :
						<?= $magInfo['galec']  ?>
					</p>
				</div>
			</div>
			<!-- contenu du message -->
			<div class="row white box-border">
				<div class="col l12">
					<?php
						$date=new DateTime($value['date_msg']);
						$date=$date->format('d-m-Y');
					?>
					<p><span class="boldtxt">Demande du : </span><?= $date ?></p>
					<p><span class="boldtxt">Objet : </span><?= $objet=$value['objet']; ?></p>
					<p><span class="boldtxt">Message :<br> </span><?= $msg=$value['msg']; ?></p>
					<p class="align-right"><a href="answer.php?msg=<?= $value['id']?>" class="waves-effect waves-light btn light-blue darken-3">Répondre</a></p>
				</div>
			</div>
			<div class="down"></div>
		</article> <!-- fin d'un message -->
		<?php endforeach ?>
	</div> <!-- end list-msg-->
</div> <!-- end container -->




