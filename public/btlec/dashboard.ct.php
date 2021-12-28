<?php
// $idMsg = array_search(170, array_column($nbRep, 'id_msg'));
// $thisMsgNbRep= $nbRep[$idMsg]['nb_rep'];
// $lastRepDate= $nbRep[$idMsg]['last_reply_date'];
// $by= $nbRep[$idMsg]['replied_by'];
?>
<div id="container" class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Demandes magasin</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row">
		<div class="col"></div>
		<div class="col">
			<div class="form-group">
				<form class="browser-default" action="<?=htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" >
					<select class="form-control select-service"  name="services" id="services"  onchange="this.form.submit()">
						<option name='service' value="" >toutes les demandes</option>
						<?php foreach ($listServicesContact as $key => $service): ?>
							<option name='service' value='<?= $service['id']?>' <?= checkSelectedDash($service['id'])?>><?= $service['service']?></option>
						<?php endforeach ?>

					</select>
				</form>

			</div>
		</div>
		<div class="col"></div>
	</div>



	<div class="row">
		<div class="col">
			<?php foreach ($msg as $key => $value): ?>
				<?php $idMag=$value['id_mag'];
				$magInfo=$magManager->getMagByGalec($value['id_galec']);
				?>
				<div class="row <?= $value['color']?>"  >
					<div class="col border font-weight-bold">
						<div class="row">
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
					</div>
				</div>

				<?php
				$date=new DateTime($value['date_msg']);
				$dateMsg=$date->format('d-m-Y à  H:i');
			//si on a des réponse bt
				if($nbRep=nbRep($pdoBt, $value['idMsg'])){
					$nbRepmsg=' - '. $nbRep['nb_rep'] . ' réponse(s)';
					$lastDateRep=$nbRep['last_reply_date'];
					$lastDateRep=date('d-m-Y à H:i', strtotime($lastDateRep));
					$by=$nbRep['replied_by'];
				}else{
					$nbRepmsg='';
					$by="";
				// ajout avertissement si message plus vieux de 5 jours et sans réponse
					$lastDateRep=warning($date);
				}
				?>
				<div class="row mb-3">
					<div class="col bg-white">
						<div class="row">
							<div class="col text-center font-weight-bold">
								SERVICE <?=strtoupper($value['service'])?>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<span class="font-weight-bold">Demande du : </span><?= $dateMsg ?>
							</div>
							<div class="col">
								<span class="font-weight-bold">Interlocuteur :</span>	<?=$value['who'] ?>
							</div>
							<div class="col">
								<span class="font-weight-bold">Etat : </span> <?= $value['etat'] .' ' .$nbRepmsg ?>
							</div>
						</div>
						<div class="row">
							<div class="col-8">
								<span class="font-weight-bold">Dernière réponse le : </span><?= $lastDateRep ?>
							</div>
							<div class="col">
								<span class="font-weight-bold">Par : </span><?= empty(!$by)? repliedByIntoName($pdoUser,$by):'' ?>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<span class="font-weight-bold">Objet : </span><?= $objet=$value['objet']; ?>
							</div>
						</div>
							<div class="row">
								<div class="col font-weight-bold">
									Message :
								</div>
							</div>
						<div class="row">
							<div class="col  border m-3">
								<?= $msg=$value['msg']; ?>
							</div>
						</div>

						<div class="row">
							<div class="col">
								<span class="font-weight-bold">Pièce jointe : </span><?=formatPJ($value['inc_file']) ?>
							</div>
						</div>
						<div class="row">
							<div class="col text-right">
								<a href="answer.php?msg=<?= $value['idMsg']?>" class="btn btn-primary">Consulter</a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach ?>
		</div> <!-- end list-msg-->
	</div>


	<!-- end container -->
</div>





