	<div class="row  pb-3 align-items-center">

		<div class="col">
			<h1 class="text-main-blue ">
				Dossier N° <?= $infoLitige[0]['dossier']?>
			</h1>
		</div>

		<div class="col">
			<p class="text-right text-main-blue bigger my-auto">
				déclaration du <?=$infoLitige[0]['datecrea'] ?>
			</p>
		</div>
		<div class="col-auto">
			<?php
			$vingtquatre="";
			$litigeOcc="";
			$magOcc="";
			if($infoLitige[0]['vingtquatre']==1){
				$vingtquatre='<img src="../img/litiges/2448_40.png">';

			}
			if($infoLitige[0]['esp']==1){
				$vingtquatre='<img src="../img/litiges/2448esp_40.png">';

			}
			if($infoLitige[0]['occasion']==1){
				$litigeOcc='<img src="../img/litiges/occasion-40.png">';
			}
			if(isset($arMagOcc[$infoLitige[0]['btlec']])){
				$magOcc='<img src="../img/logos/leclerc-occasion-circle-mini.gif" class="pr-2">';
			}
			?>

			<?=$vingtquatre .$litigeOcc?>
		</div>
	</div>

	<!-- info mag -->
	<div class="row mb-3">
		<div class="col-lg-2"></div>
		<div class="col">
			<div class="row bg-alert-primary border light-shadow no-gutters">
				<div class="col-auto my-auto">
					<div class="align-middle"><img src="../img/litiges/mag-sm.jpg"></div>
				</div>
				<div class="col pl-5">
					<div class="row">
						<div class="col">
							<h4 class="khand pt-2">
								<a href="stat-litige-mag.php?galec=<?=$infoLitige[0]['galec']?>">
									<?= $magOcc.$infoLitige[0]['mag'] .' - '.$infoLitige[0]['btlec'].'<br> ('.$infoLitige[0]['galec'].')' ?>

								</a>
							</h4>
						</div>
						<div class="col-auto">
							<h4 class="khand pt-2 text-right pr-3"><?= ($infoLitige[0]['centrale']!=0)?MagHelpers::centraleToSTring($pdoMag,$infoLitige[0]['centrale']):"" ?></h4>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<span class="heavy">Interlocuteur : </span><?= $infoLitige[0]['nom'] ?>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<span class="heavy">Commentaire : </span><?=isset($firstDial['msg'])?$firstDial['msg']:"" ?>
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="col-lg-2"></div>
	</div>