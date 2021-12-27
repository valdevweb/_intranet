<div class="row mb-3">
	<div class="col border rounded">
		<div class="row py-2 border-dark bg-light-grey">
			<div class="col-lg-4">
				Application : <div class="badge badge-btlec"><a class="text-white"  href="exploit-appli.php?id=<?=$evo['id_appli']?>"><?=$evo['appli']?></a></div>
			</div>
			<div class="col-lg-4">
				<?php if ($evo['module']!=""): ?>
					Module : <div class="badge badge-btlec"><a class="text-white" href="exploit-module.php?id=<?=$evo['id_module']?>"><?=$evo['module']?></a></div>
				<?php endif ?>

			</div>
			<div class="col-lg-4 text-right">
				<i class="fas fa-code text-orange pr-3"></i>Développeur : <?=$evo['fullname_dd']?><br>
			</div>

		</div>
		<div class="row bg-light-grey">
			<div class="col">
				<i class="fas fa-user text-orange pr-3"></i>Demandeur : <?=UserHelpers::getFullnameIdwebuser($pdoUser,$evo['id_from'])?>
			</div>
			<div class="col text-right">
				<i class="fas fa-calendar-alt text-orange pr-3"></i>Date de la demande : <?=date('d-m-Y', strtotime($evo['date_dde']))?>

			</div>
		</div>
		<?php if (isset($affectation)): ?>
			<div class="row py-2  bg-light-grey">

				<div class="col">
					<i class="fas fa-users text-orange pr-3"></i>Affectation :

					<?php foreach ($affectation as $key => $aff): ?>
						<?php if ($key==5): ?>
							<span class="badge badge-secondary" id="open">plus...</span>
							<span id="more">
							<?php endif ?>
							<?=$aff['fullname']?>,
						<?php endforeach ?>
						<?php if (count($affectation)>=5): ?>
						</span>
					<?php endif ?>
				</div>
			</div>
		<?php endif ?>

		<div class="row py-2 bg-light-grey">
			<div class="col-auto">
				<i class="fas fa-calendar-check text-orange pr-3"></i>Planification du :<br>
			</div>
			<div class="col">
				<?php if (!empty($plannings)): ?>
					<?php foreach ($plannings as $key => $planning): ?>
						<?=date('d/m/y',strtotime($planning['date_start'])).' au '.date('d/m/y' ,strtotime($planning['date_end']))?><br>
					<?php endforeach ?>
				<?php endif ?>

			</div>
			<div class="col text-right">
				Statut : <div class="badge badge-orange"><?=$listEtat[$evo['id_etat']]?></div>
			</div>
			<div class="col-auto">Chrono : <i class="fas fa-tachometer-alt <?= isset($listLevel[$evo['id_chrono']])?'text-'.$listLevel[$evo['id_chrono']]['class']:""?>"></i></div>
		</div>



		<div class="row border-top border-dark">
			<div class="col my-2 ">
				<h5>Objet : <?=$evo['objet']?></h5>
			</div>
			<div class="col-1 text-right"><h5>#<?=$evo['id']?></h5></div>
		</div>

		<div class="row">
			<div class="col">
				<div class="row">
					<div class="col">
						<div class="alert alert-secondary"><?=nl2br($evo['evo'])?></div>
					</div>
				</div>
				<?php if (!empty($evo['cmt_dd'])): ?>

					<div class="row">
						<div class="col">
							Commentaire à l'intention du demandeur :
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="alert alert-light"><?=$evo['cmt_dd']?></div>
						</div>
					</div>
				<?php endif ?>

				<?php if (!empty($evo['cmt_dev'])): ?>
					<div class="row">
						<div class="col">
							Commentaires à l'intension du développeur :
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="alert alert-light"><?=$evo['cmt_dev']?></div>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col">
		<i class="fas fa-file pr-3 text-orange"></i>Documents :
		<?php if (!empty($docs)): ?>
			<?php foreach ($docs as $key => $doc): ?>
				<a href="<?=UPLOAD_URL_EVO.$doc['file']?>" target='_blank' class="grey-link"><?=$doc['filename']?></a> -
			<?php endforeach ?>
		<?php else: ?>
			<div class="alert alert-primary">Aucun document lié à cette évolution</div>
		<?php endif ?>
	</div>
</div>
<?php if (!$droitExploit): ?>
	<div class="row pb-5">
		<div class="col">

		</div>
	</div>
<?php endif ?>
