<div class="row bg-white">
	<div class="col-lg-1 col-xl-2"></div>
	<div class="col ">
		<div class="row pt-5 pb-3">
			<div class="col-auto">
				<?php if (isset($data['date_start'])): ?>
					<time datetime="" class="icon">
						<em></em>
						<strong><?=DateHelpers::convertDateToStringMois($data['date_start'],'short') ?></strong>
						<span><?=date('d', strtotime($data['date_start']))?></span>
					</time>
				<?php endif ?>

			</div>
			<div class="col">
				<?php
				$htmlfile=$target_dir.$data['html_file'].'.html';

				include $htmlfile
				?>
			</div>

		</div>

		<div class="row bg-light-orange rounded ">
			<div class="col">
				<div class="row ">
					<div class="col pb-3 mt-3">
						<p class="text-orange font-weight-bold"><i class="fas fa-paperclip pr-3"></i>Fichiers joints :</p>
					</div>
				</div>

				<div class="row pb-2">
					<div class="col ">
						<?php
						$listPj=$occInfoDao->getPj($data['id']);
						?>
						<?php if (!empty($listPj)): ?>
							<?php foreach ($listPj as $key => $pj): ?>
								<a href="<?=$pjDir.$pj['pj']?>" class="pr-3" target="_blank"><?= $pj['pj']?></a><br>
							<?php endforeach ?>
							<?php else: ?>
								Pas de pièce jointe
							<?php endif ?>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="col-lg-1 col-xl-2"></div>

	</div>
