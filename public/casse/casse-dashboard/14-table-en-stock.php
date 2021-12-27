<?php foreach ($expeds as $expId =>$exp): ?>
	<div class="row">
		<div class="col border rounded p-2">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row" id="exp-<?=$expId?>">
					<div class="col">
						<p class="text-main-blue">Groupement de palettes n°<?=$expId?> pour le magasin <?=$exp[0]['btlec']?></p>
					</div>
				</div>


				<?php

				$expHisto=[];
				if (isset($trtHisto[$expId])) {
					$expHistoTemp=$trtHisto[$expId];
					foreach ($expHistoTemp as $key => $histo) {
						$expHisto[$histo['id_trt']]=$histo['insert_on'];
					}

				}
				?>
				<div class="row">
					<div class="col-4">
						<?php foreach ($exp as $key => $palette): ?>
							<div class="row">
								<div class="col">
									<?=$palette['palette']?>
								</div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" name="contremarque[<?=$palette['id_palette']?>]" value="<?=$palette['contremarque']?>" placeholder="Pal contremarque">
									</div>
								</div>
							</div>
						<?php endforeach ?>
						<div class="row">
							<div class="col">
								<div class="row">
									<div class="col text-right">
										<input type="hidden" class="form-control" name="id_exp" value="<?=$expId?>">
										<button class="btn btn-primary" name="save_contremarque">Enregistrer</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-1"></div>
					<div class="col">

						<?php if ($exp[0]['id_affectation']!=""): ?>
							<?php
							switch ($exp[0]['id_affectation']) {
								case 1:
								$listTrt=$listTrtMag;
								$typeExp="livraison Magasin";
								break;
								case 2:
								$listTrt=$listTrtOcc;
								$typeExp="réaffectation occasion";
								break;
								case 3:
								$listTrt=$listTrtSav;
								$typeExp="livraison SAV";

								break;
								default:
								$listTrt=$listTrtMag;
								$typeExp="livraison Magasin";
								break;
							}
							?>
							<div class="row">
								<div class="col alert-primary py-2 rounded font-weight-bold mx-2">
									Traitement <?=$typeExp?> :
								</div>
							</div>
							<?php foreach ($listTrt as $key => $trt): ?>

								<div class="row">
									<div class="col">
										<a href="?id_exp=<?=$expId?>&id_trt=<?=$trt['id']?>" class="<?=isset($expHisto[$trt['id']])? "text-success": "text-danger"?>"><i class="fas fa-check pr-3"></i><?=$trt['traitement']?></a>
									</div>
									<div class="col-auto">
										<?=isset($expHisto[$trt['id']])? date('d-m-Y', strtotime($expHisto[$trt['id']])): "à faire"?>
									</div>
								</div>
							<?php endforeach ?>
						<?php else: ?>
							<div class="alert alert-primary">Palettes en attente d'affectation</div>
						<?php endif ?>
					</div>
				</div>
			</form>

		</div>
	</div>
<?php endforeach ?>

