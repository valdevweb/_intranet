<?php
// $idMsg = array_search(170, array_column($nbRep, 'id_msg'));
// $thisMsgNbRep= $nbRep[$idMsg]['nb_rep'];
// $lastRepDate= $nbRep[$idMsg]['last_reply_date'];
// $by= $nbRep[$idMsg]['replied_by'];
?>

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
						<option name='9999' value='tous' >toutes les demandes</option>

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
			<?php if(isset($_GET['success'])){ echo  "reponse envoyée avec succès"; }?>

		</p>
<!-- debut boucle pour affichage des resultats => messages envoyés au services -->
	<br><br>
	<div id="list-msg">
		<?php foreach ($msg as $key => $value): ?>
		<!-- un message  -->
		<article class="msg"  data-service='<?= $value['id_service']; ?>' >
			<!-- entete -->

			<div class="row <?= color($value['id_service'],$services)?> box-border" >
				<?php $idMag=$value['id_mag'];

				$panoGalec=getPanoGalec($pdoUser,$idMag);
				$magInfo=getMag($pdoBt,$panoGalec['galec']); ?>
				<div class="col l4">
					<p class="boldtxt">MAGASIN :
						<?= $magInfo['mag']  ?>
					</p>
				</div>

				<div class="col l4">
					<p class="boldtxt">Interlocuteur :
						<?=$value['who'] ?>
						 <?php 	 // $magInfo['city'] . ' - ' .$magInfo['cp']  ?>
					</p>
				</div>
				<div class="col l2">
					<p class="boldtxt">Code BTLec :
						<?= $magInfo['btlec']  ?>
					</p>
				</div>
				<div class="col l2">
					<p class="boldtxt">Code Galec :
						<?= $magInfo['galec']  ?>
					</p>
				</div>
			</div>
			<?php
			//formatage des données pour affichage
				$date=new DateTime($value['date_msg']);
				$dateMsg=$date->format('d-m-Y');
			    $found_key = array_search($value['id_service'], array_column($services, 'id'));
				$serviceName= $services[$found_key]['full_name'];
			//si on a des réponse bt
			if($nbRep=nbRep($pdoBt, $value['id']))
			{
				$nbRepmsg=' - '. $nbRep['nb_rep'] . ' réponse(s)';
				$lastDateRep=$nbRep['last_reply_date'];
				$lastDateRep=date('d-m-Y', strtotime($lastDateRep));
				$by=$nbRep['replied_by'];

			}
			else
			{
				$nbRepmsg='';
				$by="";
				// ajout avertissement si message plus vieux de 5 jours et sans réponse
				$lastDateRep=warning($date);

			}

			?>

			<!-- contenu du message -->
			<div class="row white box-border">
				<div class="col l12">
					<p class="center">SERVICE <?=strtoupper($serviceName)?></p>
					<div class="col l9">
						<p><span class="labelFor">Demande du : </span><?= $dateMsg ?> </p>
					</div>
					<div class="col l3">
						<p><span class="labelForSmaller">Etat : </span> <?= $value['etat'] .' ' .$nbRepmsg ?> </p>
						<p>
					</div>
					<div class="col l9">
						<p><span class="labelFor">Dernière réponse le : </span><?= $lastDateRep ?> </p>
					</div>
					<div class="col l3">
						<p><span class="labelForSmaller">Par : </span><?=  repliedByIntoName($pdoBt,$by) ?> </p>
						<p>
					</div>

					<div class="col l12">
						<p><span class="labelFor">Objet : </span><?= $objet=$value['objet']; ?></p>
					</div>
					<div class="col l12">
						<p><span class="labelFor">Message : </span><br><?= $msg=$value['msg']; ?></p>
					</div>
					<div class="col l6 align-left"><span class="labelFor">Pièce jointe : </span><?=isAttached($value['inc_file']) ?></div>
					<div class="col l6 align-right"><a href="answer.php?msg=<?= $value['id']?>" class="waves-effect waves-light btn blue darken-2">Consulter</a></div>


				</div>
			</div>
		</article> <!-- fin d'un message -->
		<div class="down"></div>

		<?php endforeach ?>
	</div> <!-- end list-msg-->
</div>
</div> <!-- end container -->




