<div class="row mx-3">
	<div class="col">
		<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
			<div class="row">
				<div class="col">
					<fieldset class="position-relative">
						<legend><i class="fas fa-filter pr-3"></i> Filtrer par :</legend>
						<!-- FILTRE PAR CENTRALE -->
						<p class="rubrique text-main-blue font-weight-bold">Centrales :</p>
						<?php for ($i=0; $i<count($listCentrale);$i++): ?>
							<?php if ($iCentrale==0): ?>
								<div class="form-row">
									<div class="col-3">
										<div class="form-check  pl-5">
											<input type="checkbox" class="form-check-input centrale" name="centraleSelected[]" value="<?=$listCentrale[$i]['centrale_doris']?>"
											<?= checkChecked($listCentrale[$i]['centrale_doris'],'centraleSelected')?>>
											<label class="form-check-label"><?=ucfirst(strtolower($listCentrale[$i]['centrale']))?></label>
										</div>
									</div>
									<?php $iCentrale++ ?>

									<?php elseif ($iCentrale==3): ?>
										<div class="col-3">
											<div class="form-check  pl-5">
												<input type="checkbox" class="form-check-input centrale" name="centraleSelected[]" value="<?=$listCentrale[$i]['centrale_doris']?>"
												<?= checkChecked($listCentrale[$i]['centrale_doris'],'centraleSelected')?>>
												<label class="form-check-label"><?=ucfirst(strtolower($listCentrale[$i]['centrale']))?></label>
											</div>
										</div>
									</div>
									<?php $iCentrale=0 ?>
									<?php else: ?>
										<div class="col-3">
											<div class="form-check  pl-5">
												<input type="checkbox" class="form-check-input centrale" name="centraleSelected[]" value="<?=$listCentrale[$i]['centrale_doris']?>"
												<?= checkChecked($listCentrale[$i]['centrale_doris'],'centraleSelected')?>>
												<label class="form-check-label"><?=ucfirst(strtolower($listCentrale[$i]['centrale']))?></label>
											</div>
										</div>
										<?php $iCentrale++ ?>
									<?php endif ?>
								<?php endfor ?>
								<!-- fermeture div quand par col 4 -->
								<?= ($iCentrale!=0 )? "</div>" : ""?>
								<div class="form-row">
									<div class="col">
										<div class="form-check  pl-5">
											<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="0" id="no-centrale"  <?= checkChecked(0,'centraleSelected')?>>
											<label for="centrale-0" class="form-check-label">Pas de centrale </label>
										</div>
									</div>
									<div class="col">
										<div class="form-check  pl-5">
											<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="1" id="no-filtre-centrale"  <?= checkChecked(1,'centraleSelected')?>>
											<label for="centrale-1" class="form-check-label">Sans filtre centrale</label>
										</div>
									</div>
									<div class="col"></div>
									<div class="col"></div>
								</div>
								<!--										FILTRE PAR TYPE									-->
								<div class="form-row my-3">

									<div class="col-9">
										<div class="row">
											<div class="col-auto ">
												<p class="rubrique text-main-blue font-weight-bold">Code Acdlec :</p>
											</div>
											<div class="col-auto ">

												<div class="form-check form-check-inline">
													<input type="radio" class="form-check-input" name="check_code" id="check-all-code">
													<label class="form-check-label" for="check-all-code">Cocher tout</label>
												</div>

												<div class="form-check form-check-inline pl-5">
													<input type="radio" class="form-check-input" name="check_code" id="uncheck-code">
													<label class="form-check-label" for="uncheck-code">Décocher tout</label>
												</div>
											</div>
											<div class="col"></div>

										</div>

										<div class="row">
											<div class="col">
												<?php foreach ($listCodeAcdlec as $code): ?>
													<?php if (!empty($code['acdlec_code'])): ?>
														<?php
														if ($countItem==4){
															echo '</div><div class="col">';
															$countItem=0;
														}
														?>
														<div class="form-check pl-5">
															<input type="checkbox" class="form-check-input acdlec" name="acdlecSelected[]" value="<?=$code['acdlec_code']?>" <?= checkChecked($code['acdlec_code'],'acdlecSelected')?>>
															<label class="form-check-label" ><a href="" title="<?=$code['acdlec_code']?>"><?=$ets[ $code['acdlec_code']] ?></a></label>
														</div>
														<?php $countItem++; ?>
													<?php endif ?>
												<?php endforeach ?>
											</div>
										</div>
									</div>
									<div class="col-3">
										<p class="rubrique text-main-blue font-weight-bold">Ouvert/fermé :</p>
										<div class="form-check pl-5">
											<input type="radio" class="form-check-input" name="sorti[]" value="0" <?= checkChecked(0,'sorti')?>>
											<label class="form-check-label">Ouvert</label>
										</div>
										<div class="form-check pl-5">
											<input type="radio" class="form-check-input" name="sorti[]" value="9" <?= checkChecked(9,'sorti')?>>
											<label class="form-check-label">Fermé</label>
										</div>

									</div>
								</div>
								<div class="row">
									<!--					FILTRE PAR CM				-->
									<div class="col">
										<p class="rubrique text-main-blue font-weight-bold">Suivi par :</p>
										<?php foreach ($listCm as $key => $cm): ?>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="cmSelected[]" value="<?=$cm['id_web_user']?>" <?= checkChecked($cm['id_web_user'],'cmSelected')?>>
												<label class="form-check-label"><?=$cm['fullname']?></label>
											</div>
										<?php endforeach ?>
										<div class="form-check pl-5">
											<input type="checkbox" class="form-check-input" name="cmSelected[]" value="NULL" <?= checkChecked('NULL','cmSelected')?>>
											<label class="form-check-label">Non suivi</label>
										</div>
									</div>
									<div class="col">
										<p class="rubrique text-main-blue font-weight-bold">Données manquantes :</p>
										<div class="form-check form-check-inline pl-5">
											<input type="checkbox" class="form-check-input" name="no-docubase[]" value="1" <?= checkChecked(1,'no-docubase')?>>
											<label class="form-check-label">Codes docubase</label>
										</div>
										<div class="form-check form-check-inline pl-5">
											<input type="checkbox" class="form-check-input" name="no-portail[]" value="1" <?= checkChecked(1,'no-portail')?>>
											<label class="form-check-label">Compte portail</label>
										</div>
									</div>
								</div>


								<!--					FILTRE PAR ETAT				-->

								<!-- btn validation -->
								<div class="form-row">
									<div class="col text-right">
										<button class="btn btn-orange" name="clear_filter">Réinitialiser les filtres</button>
										<button class="btn btn-primary" name="filter">Filtrer</button>

									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col">
				<div class="alert border text-center">
					<span class="pr-5">Origine des données :</span>
					<span class="text-gessica pr-5"><i class="fas fa-palette pr-3"></i>gessica</span>
					<span class="text-sca pr-5"><i class="fas fa-palette pr-3"></i>sca3</span>
					<span class="text-ctbt pr-5"><i class="fas fa-palette pr-3"></i>centrale BT</span>
				</div>
			</div>
		</div>