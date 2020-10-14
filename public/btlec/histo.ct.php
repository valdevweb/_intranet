<?php
// $idMsg = array_search(170, array_column($nbRep, 'id_msg'));
// $thisMsgNbRep= $nbRep[$idMsg]['nb_rep'];
// $lastRepDate= $nbRep[$idMsg]['last_reply_date'];
// $by= $nbRep[$idMsg]['replied_by'];
?>

<div class="container">
	<!-- titre -->
	<h1 class="blue-text text-darken-2">Demandes magasin clôtures</h1>

	<!-- select -->
	<div class="row">
		<form class="browser-default" action="<?=htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" >
			<div class="col l4"></div>
			<div class="col l4">
				<!-- <p class="center">Choisir un service</p> -->
				<select class="browser-default select-service"  name="services" id="services"  onchange="this.form.submit()">
					<option name='service' value='' >toutes les demandes</option>
					<?php foreach ($listServicesContact as $key => $service): ?>
						<option name='service' value='<?= $service['id']?>' <?= checkSelectedHisto($service['id'])?>><?= $service['service']?></option>
					<?php endforeach ?>

				</select>
			</div>
			<div class="col l4"></div>
		</form>
	</div>

	<div class="row">
		<p class="success-msg">
			<?php
			if(isset($_GET['success']))
			{
				if($_GET['success']==1)
				{
					echo  "reponse envoyée avec succès";
				}
				elseif ($_GET['success']==2)
				{
					echo "demande clôturée avec succès";
				}
			}?>
		</p>


		<!-- debut boucle pour affichage des resultats => messages envoyés au services -->
		<br><br>
		<div id="list-msg">
			<?php foreach ($msg as $key => $value): ?>
				<?php



				 ?>
				<!-- un message  -->
				<article class="msg"  data-service='<?= $value['id_service']; ?>' >
					<!-- entete -->

			<div class="row <?= $value['color']?> box-border" >
						<?php $idMag=$value['id_mag'];


						$magInfo=$magManager->getMagByGalec($value['id_galec']);

						?>
						<div class="col l3">
							<p class="boldtxt">N° dossier :
								<?= $value['idMsg']  ?>
							</p>
						</div>

						<div class="col l5">
							<p class="boldtxt">MAGASIN :
								<?= $magInfo->getDeno()  ?>
							</p>
						</div>


						<div class="col l2">
							<p class="boldtxt">Code BTLec :
								<?= $magInfo->getId()  ?>
							</p>
						</div>
						<div class="col l2">
							<p class="boldtxt">Code Galec :
								<?= $magInfo->getGalec()  ?>
							</p>
						</div>
					</div>
					<?php
			//formatage des données pour affichage
					$date=new DateTime($value['date_msg']);
					$dateMsg=$date->format('d-m-Y à  H:i');

			//si on a des réponse bt
					if($nbRep=nbRep($pdoBt, $value['idMsg']))
					{
						$nbRepmsg=' - '. $nbRep['nb_rep'] . ' réponse(s)';
						$lastDateRep=$nbRep['last_reply_date'];
						$lastDateRep=date('d-m-Y à H:i', strtotime($lastDateRep));
						$by=$nbRep['replied_by'];

					}
					else
					{
						$nbRepmsg='';
						$by="";
				// ajout avertissement si message plus vieux de 5 jours et sans réponse
						$lastDateRep=warning($date);

					}


					// $nbRep

					?>

					<!-- contenu du message -->
					<div class="row white box-border">
						<div class="col l12">
							<p class="center">SERVICE <?=strtoupper($value['service'])?></p>
							<div class="col l4">
								<p><span class="labelFor">Demande du : </span><?= $dateMsg ?> </p>
							</div>
							<div class="col l5">
								<p class="boldtxt">Interlocuteur :
									<?=$value['who'] ?>
									<?php 	 // $magInfo['city'] . ' - ' .$magInfo['cp']  ?>
								</p>
							</div>
							<div class="col l3">
								<p><span class="labelForSmaller">Etat : </span> <?= $value['etat'] .' ' .$nbRepmsg ?> </p>
								<p>
								</div>
								<div class="col l9">
									<p><span class="labelFor">Dernière réponse le : </span><?= $lastDateRep ?> </p>
								</div>
								<div class="col l3">
									<p><span class="labelForSmaller">Par : </span><?= empty(!$by)? repliedByIntoName($pdoUser,$by):'' ?> </p>
									<p>
									</div>

									<div class="col l12">
										<p><span class="labelFor">Objet : </span><?= $objet=$value['objet']; ?></p>
									</div>
									<div class="col l12">
										<p><span class="labelFor">Message : </span><br><?= $msg=$value['msg']; ?></p>
									</div>
									<div class="col l6 align-left"><span class="labelFor">Pièce jointe : </span><?=formatPJ($value['inc_file']) ?></div>
									<div class="col l6 align-right"><a href="answer.php?msg=<?= $value['idMsg']?>" class="waves-effect waves-light btn blue darken-2">Consulter</a></div>


								</div>
							</div>
						</article> <!-- fin d'un message -->
						<div class="down"></div>

					<?php endforeach ?>
				</div> <!-- end list-msg-->
			</div>
		</div> <!-- end container -->




