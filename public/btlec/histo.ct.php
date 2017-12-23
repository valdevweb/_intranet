<div class="container">
	<!-- titre -->
	<h1 class="blue-text text-darken-2">Demandes magasin clôturées</h1>

	<!-- select -->
	<div class="row">
		<form class="browser-default" action="request.ct.php" >
			<div class="col l4"></div>
			<div class="col l4">
				<!-- <p class="center">Choisir un service</p> -->
				<select class="browser-default select-service"  name="services" id="services">
					<option name="default" id="default" value="default">Tous les services</option>
					<?php foreach ($services as $service): ?>
						<option name='<?= $service['id']?>' value='<?= $service['id']?>' >   <?= $service['full_name']?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="col l4"></div>
		</form>
	</div>

	<div class="row">
		<p class="err-msg">
			En cours de construction
		</p>
<!-- debut boucle pour affichage des resultats => messages envoyés au services -->
	<br><br>
	<div id="list-msg">
		<?php foreach ($msg as $key => $value): ?>
		<!-- un message  -->
		<article class="msg"  data-service='<?= $value['id_service']; ?>' >
			<!-- entete -->
			<div class="row blue-grey darken-1 box-border" >
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
					//formatage date
						$date=new DateTime($value['date_msg']);
						$date=$date->format('d-m-Y');
					?>
					<p><span class="boldtxt">Demande du : </span><?= $date ?></p>
					<p><span class="boldtxt">Objet : </span><?= $objet=$value['objet']; ?></p>
				</div>
					<div class="col l12 align-left"><?=isAttached($value['inc_file']) ?></div>
					<div class="col l12"><span class="boldtxt">Message : </span><?= $msg=$value['msg']; ?></div>

					<div class="col l16"><span class="boldtxt">Répondu le : </span><?= $value['date_reply'] ?></div>
					<div class="col l16"><span class="boldtxt">Par </span><?= $value['reply_by']; ?></div>
					<div class="col l12"><span class="boldtxt">Réponse : </span><?= $value['reply']; ?></div>

				</div>
			</div>
		</article> <!-- fin d'un message -->
		<div class="down"></div>

		<?php endforeach ?>
	</div> <!-- end list-msg-->
</div> <!-- end container -->





