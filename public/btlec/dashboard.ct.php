<div class="container">
	<!-- titre -->
	<h1 class="blue-text text-darken-2">Demandes magasin</h1>

	<!-- select -->
	<div class="row">
		<form class="browser-default" action="request.ct.php" >
			<div class="col l4"></div>
			<div class="col l4">
				<!-- <p class="center">Choisir un service</p> -->
				<select class="browser-default select-service"  name="services" id="services">
					<?php foreach ($userService as $serviceu): ?>
						<option name='<?= $serviceu['id']?>' selected='selected' value='<?= $serviceu['id']?>' ><?= $serviceu['full_name']?></option>
					<?php endforeach ?>
					<?php foreach ($one as $serviceo): ?>
						<option name='<?= $serviceo['id']?>' value='<?= $serviceo['id']?>' ><?= $serviceo['full_name']?></option>
					<?php endforeach ?>
					<?php foreach ($two as $servicet): ?>
						<option name='<?= $servicet['id']?>' value='<?= $servicet['id']?>' >   <?= $servicet['full_name']?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="col l4"></div>
		</form>
	</div>

	<div class="row">
		<p class="success-msg">
			<?= $_GET['success'] ? "reponse envoyée avec succès" : ""?>
		</p>
<!-- debut boucle pour affichage des resultats => messages envoyés au services -->
	<br><br>
	<div id="list-msg">
		<?php foreach ($msg as $key => $value): ?>
		<!-- un message  -->
		<article class="msg"  data-service='<?= $value['id_service']; ?>' >
			<!-- entete -->

			<div class="row <?= color($value['id_service'],$services)?> box-border" >
				<?php $idMag=$value['id_mag'];?>
				<?php $magInfo=getMag($pdoBt,$idMag); ?>
				<div class="col l3">
					<p class="boldtxt">MAGASIN :
						<?= $magInfo['mag']  ?>
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
						$found_key = array_search($value['id_service'], array_column($services, 'id'));
						$serviceName= $services[$found_key]['full_name'];
					?>
					<p class="center">SERVICE <?=strtoupper($serviceName)?></p>
					<p><span class="boldtxt">Demande du : </span><?= $date ?> au </p>
					<p><span class="boldtxt">Objet : </span><?= $objet=$value['objet']; ?></p>
					<p><span class="boldtxt">Message :<br> </span><?= $msg=$value['msg']; ?></p>
					<div class="col l6 align-left"><?=isAttached($value['inc_file']) ?></div>
					<div class="col l6 align-right"><a href="answer.php?msg=<?= $value['id']?>" class="waves-effect waves-light btn blue darken-2">Répondre</a></div>

				</div>
			</div>
		</article> <!-- fin d'un message -->
		<div class="down"></div>

		<?php endforeach ?>
	</div> <!-- end list-msg-->
</div>
</div> <!-- end container -->




