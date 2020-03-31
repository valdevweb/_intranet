		<div class="bg-separation-small"></div>
		<!-- <div class="sub-title-ico"><i class="fas fa-home text-light-grey pt-3"></i></div> -->
		<h5 class="text-center font-weight-bold  mag-title sub-title">Information générales</h5>

		<div class="row yanone py-3 light-shadow-round">
			<div class="col">
				<div class="text-orange">
					<i class="fas fa-map-marked pr-3"></i>
					<?= 'Leclerc '.$mag->getDeno()?>
				</div>

				<div class="pl-2  border-left-dashed">
					<?= $mag->getAd1()?><br>
					<?= $ad2?>
					<?= $mag->getCp() . ' '. $mag->getVille()?>

				</div>
			</div>
			<div class="col-3 border-left-dashed">

				<i class="fas fa-user pr-3 text-orange "></i>
				<?= $mag->getAdherent()?><br>

				<br><br>
				<i class="fas fa-phone pr-3 text-orange"></i>
				<?= $mag->getTel();?>
			</div>
			<div class="col-3">
				<br><br><br>
				<span class="border-left-dashed-simple"></span><i class="fas fa-fax pr-3 text-orange"></i>
				<?= $mag->getFax()?>
			</div>
		</div>
	</div>
</section>

<div class="container">
	<section class="second-container">
		<h5 class="text-center font-weight-bold  mag-title sub-title">Informations Complémentaires</h5>

		<div class="row py-3">
			<div class="col py-3 light-shadow-round">

				<div class="row yanone ">
					<div class="col-3">
						<span class="text-orange">Centrale : </span>
						<?= $centraleGessica;?>
					</div>
					<div class="col">
						<i class="fas fa-arrows-alt-h pr-3 text-orange"></i>
						<?= $mag->getSurfaceStrg();?>
					</div>
					<div class="col-3">
						<span class="text-orange">Pole SAV : </span>

						<?= $mag->getPoleSavSca()?>
					</div>
					<div class="col">
						<span class="text-orange">Antenne : </span>

						<?= $mag->getAntenne();?>
					</div>

				</div>
				<div class="row yanone">
					<div class="col-3">
						<span class="text-orange">TVA : </span>
						<?= $mag->getTva();?>

					</div>
					<div class="col-3">
						<span class="text-orange">Siret : </span>
						<?= $mag->getSiret();?>

					</div>
					<div class="col-3">
						<span class="text-orange">Code REI : </span>
						<?= $mag->getRei();?>
					</div>
					<div class="col-3">
						<span class="text-orange">Centre de redevance : </span>
						<?= $centreRei?>

					</div>
				</div>
			</div>

		</div>
		<div class="bg-separation-small"></div>
		<!-- <div class="sub-title-ico pt-3"><i class="fas fa-at text-light-grey"></i></div> -->
		<h5 class="text-center font-weight-bold  sub-title ">Listes de diffusion</h5>

		<div class="row py-3">
			<div class="col light-shadow-round">
				<div class="row yanone pt-3">
					<div class="col text-orange"><?=$ldAdhName?></div>
					<div class="col text-orange"><?=$ldDirName?></div>
					<div class="col text-orange"><?=$ldRbtName?></div>
				</div>
				<div class="row yanone pb-3">
					<div class="col"><div class="pl-2 border-left"><?=$ldAdh?></div></div>
					<div class="col"><div class="pl-2 border-left"><?=$ldDir?></div> </div>
					<div class="col"><div class="pl-2 border-left"><?=$ldRbt?></div></div>
				</div>
			</div>
		</div>
		<div class="bg-separation-small"></div>
		<!-- <div class="sub-title-ico  pt-3"><i class="fas fa-calendar text-light-grey"></i></div> -->
		<h5 class="text-center font-weight-bold  sub-title">Historique magasin</h5>

		<div class="row light-shadow-round py-3 mb-3">
			<div class="col yanone">
				<div class="font-weight-bold"><i class="far fa-calendar pr-3 text-orange"></i> de <?=$mag->getDateOuvertureFr()?> à aujourd'hui :</div>
				<div class="pl-5"><?=$mag->getId() .' - '. $mag->getDeno()?></div>

				<?php if (!empty($histo)): ?>
					<?php foreach ($histo as $key => $prevMag): ?>

						<?= ($key+1==ceil((count($histo)+1)/2))? "</div><div class='col yanone'>" :'' ?>

						<div class="font-weight-bold"><i class="far fa-calendar pr-3 text-orange"></i><?=$prevMag['dateOuv'] .'<i class="fas fa-long-arrow-alt-right px-3"></i> '.$prevMag['dateFerm']?> :</div>
						<div class="pl-5"><?=$prevMag['btlec_old'] .' - '.$prevMag['deno_sca']?></div>


					<?php endforeach ?>

				<?php endif ?>
			</div>
		</div>
		<div class="bg-separation-small"></div>

		<div class="row">
			<div class="col">
				<!-- <div class="sub-title-ico  pt-3"><i class="fas fa-user-lock text-light-grey "></i></div> -->
				<h5 class="text-center font-weight-bold  sub-title">Identifiants</h5>
			</div>
		</div>
		<div class="row yanone light-shadow-round py-3 mb-3">
			<div class="col">
				<img src="../img/logos/docubase-logo.png" class="pr-3">
				<div class="text-orange">Docubase :</div>
				<span class="text-orange pl-3" id="docubase">Login :</span> <?= $mag->getDocubaseLogin()?> <br>
				<span class="text-orange pl-3">Mot de Passe : </span> <?= $mag->getDocubasePwd() ?><br>

			</div>
			<div class="col">
				<img src="../img/logo_bt/bt-rond-20.jpg" class="pr-3">
				<div class="text-orange">Portail :</div>
				<?php if (!empty($webusers)): ?>
					<?php foreach ($webusers as $key => $webuser): ?>
						<span class="text-orange pl-3">Login :</span> <?= $webuser['login']?> <br>
						<span class="text-orange pl-3">Mot de Passe : </span> <?= $webuser['nohash_pwd']?><br>
						<span class="text-orange pl-3">Ident : </span><?= $webuser['id_web_user']?><br>
					<?php endforeach ?>

					<?php else: ?>
						Ce magasin n'a pas de compte sur le portail
					<?php endif ?>

				</div>
			</div>
