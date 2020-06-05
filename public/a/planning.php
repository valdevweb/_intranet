<?php

include("../config/autoload.php");
//------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."css/".$pageCss.".css";









//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];
//------------------------------------------------------
//			VIEW
//------------------------------------------------------

function getPlanning($pdo,$espace,$day, $periode){
	$req=$pdo->prepare("SELECT * FROM planning LEFT JOIN concepts ON planning.id_concept=concepts.id WHERE id_espace= :id_espace AND periode = :periode AND jour = :jour ORDER BY jour, hour_start ");
	$req->execute([
		':jour'		=>$day,
		':id_espace'		=>$espace,
		':periode'		=>$periode,
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}




function getPlanningCourcelles($pdo,$espace){
	$req=$pdo->prepare("SELECT * FROM planning LEFT JOIN concepts ON planning.id_concept=concepts.id WHERE id_espace= :id_espace AND periode = :periode AND jour = :jour ORDER BY jour, hour_start ");
	$req->execute([
		':jour'		=>$day,
		':id_espace'		=>$espace,
		':periode'		=>$periode,
	]);
	return $req->fetchAll(PDO::FETCH_ASSOC);
}








include('../view/_head.php');


?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container-fluid">
	<div class="row">
		<div class="col pt-3  mb-3 text-center">
			<h1>COURCELLES - FITNESS</h1>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
	</div>
	<!-- COURCELLES -->
	<div class="row no-gutters">
		<div class="col">
			<div class="planning-zone">
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Lundi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Mardi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Mercredi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Jeudi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Vendredi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Samedi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Dimanche</div>
					</div>
				</div>
			</div>
			<div class="planning-zone matin">
				<?php for ($i=1;$i<=7;$i++): ?>
					<?php $courcelleMa=getPlanning($pdo,1,$i,"ma"); ?>
					<?php if (!empty($courcelleMa)): ?>
						<div class="planning-col">
							<?php foreach ($courcelleMa as  $planning): ?>
								<div class="cours <?=$planning['periode']?>" >
									<div class="concept"><?= $planning['concept']?></div>
									<div class="time"><?= substr($planning['hour_start'],0,-3).' - '.substr($planning['hour_end'],0,-3)?></div>
								</div>
							<?php endforeach ?>
						</div>
						<?php else: ?>
							<div class="planning-col">
								<div class="cours"></div>
							</div>
						<?php endif ?>
					<?php endfor ?>
				</div>
				<!-- ./matin courcelle -->
				<!-- midi courcelle -->
				<div class="planning-zone midi">
					<?php for ($i=1;$i<=7;$i++): ?>
						<?php $courcelleMi=getPlanning($pdo,1,$i,"mi"); ?>
						<?php if (!empty($courcelleMi)): ?>
							<div class="planning-col">
								<?php foreach ($courcelleMi as  $planning): ?>
									<div class="cours <?=$planning['periode']?>" >
										<div class="concept"><?= $planning['concept']?></div>

										<div class="time"><?= substr($planning['hour_start'],0,-3).' - '.substr($planning['hour_end'],0,-3)?></div>

									</div>
								<?php endforeach ?>
							</div>
							<?php else: ?>
								<div class="planning-col">
									<div class="cours"></div>
								</div>
							<?php endif ?>
						<?php endfor ?>
					</div>
					<!-- ./am courcelle -->
					<div class="planning-zone afternoon">
						<?php for ($i=1;$i<=7;$i++): ?>
							<?php $courcelleAm=getPlanning($pdo,1,$i,"am"); ?>
							<?php if (!empty($courcelleAm)): ?>
								<div class="planning-col">
									<?php foreach ($courcelleAm as  $planning): ?>
										<div class="cours <?=$planning['periode']?>" >
											<div class="concept"><?= $planning['concept']?></div>

											<div class="time"><?= substr($planning['hour_start'],0,-3).' - '.substr($planning['hour_end'],0,-3)?></div>

										</div>
									<?php endforeach ?>
								</div>
								<?php else: ?>
									<div class="planning-col">
										<div class="cours"></div>
									</div>
								<?php endif ?>
							<?php endfor ?>
						</div>
						<!-- ./am courcelle -->
						<!-- ./so courcelle -->
						<div class="planning-zone soir">
							<?php for ($i=1;$i<=7;$i++): ?>
								<?php $courcelleS=getPlanning($pdo,1,$i,"s"); ?>
								<?php if (!empty($courcelleS)): ?>
									<div class="planning-col">
										<?php foreach ($courcelleS as  $planning): ?>
											<div class="cours <?=$planning['periode']?>" >
												<div class="concept"><?= $planning['concept']?></div>

												<div class="time"><?= substr($planning['hour_start'],0,-3).' - '.substr($planning['hour_end'],0,-3)?></div>

											</div>
										<?php endforeach ?>
									</div>
									<?php else: ?>
										<div class="planning-col">
											<div class="cours"></div>
										</div>
									<?php endif ?>
								<?php endfor ?>
							</div>
							<!-- ./so courcelle -->


						</div>
					</div>
					<!-- ./COURCELLES -->

<div class="row">
		<div class="col pt-3  mb-3 text-center">
			<h1>COURCELLES - SMALLGROUP</h1>
		</div>
	</div>

<!-- SMALLGROUP -->
	<div class="row no-gutters">
		<div class="col">
			<div class="planning-zone">
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Lundi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Mardi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Mercredi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Jeudi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Vendredi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Samedi</div>
					</div>
				</div>
				<div class="planning-col-titre">
					<div class="planning-titre">
						<div class="titre">Dimanche</div>
					</div>
				</div>
			</div>
			<div class="planning-zone matin">
				<?php for ($i=1;$i<=7;$i++): ?>
					<?php $courcelleMa=getPlanning($pdo,2,$i,"ma"); ?>
					<?php if (!empty($courcelleMa)): ?>
						<div class="planning-col">
							<?php foreach ($courcelleMa as  $planning): ?>
								<div class="cours <?=$planning['periode']?>" >
									<div class="concept"><?= $planning['concept']?></div>
									<div class="time"><?= substr($planning['hour_start'],0,-3).' - '.substr($planning['hour_end'],0,-3)?></div>
								</div>
							<?php endforeach ?>
						</div>
						<?php else: ?>
							<div class="planning-col">
								<div class="cours"></div>
							</div>
						<?php endif ?>
					<?php endfor ?>
				</div>
				<!-- ./matin courcelle -->
				<!-- midi courcelle -->
				<div class="planning-zone midi">
					<?php for ($i=1;$i<=7;$i++): ?>
						<?php $courcelleMi=getPlanning($pdo,2,$i,"mi"); ?>
						<?php if (!empty($courcelleMi)): ?>
							<div class="planning-col">
								<?php foreach ($courcelleMi as  $planning): ?>
									<div class="cours <?=$planning['periode']?>" >
										<div class="concept"><?= $planning['concept']?></div>

										<div class="time"><?= substr($planning['hour_start'],0,-3).' - '.substr($planning['hour_end'],0,-3)?></div>

									</div>
								<?php endforeach ?>
							</div>
							<?php else: ?>
								<div class="planning-col">
									<div class="cours"></div>
								</div>
							<?php endif ?>
						<?php endfor ?>
					</div>
					<!-- ./am courcelle -->
					<div class="planning-zone afternoon">
						<?php for ($i=1;$i<=7;$i++): ?>
							<?php $courcelleAm=getPlanning($pdo,2,$i,"am"); ?>
							<?php if (!empty($courcelleAm)): ?>
								<div class="planning-col">
									<?php foreach ($courcelleAm as  $planning): ?>
										<div class="cours <?=$planning['periode']?>" >
											<div class="concept"><?= $planning['concept']?></div>

											<div class="time"><?= substr($planning['hour_start'],0,-3).' - '.substr($planning['hour_end'],0,-3)?></div>

										</div>
									<?php endforeach ?>
								</div>
								<?php else: ?>
									<div class="planning-col">
										<div class="cours"></div>
									</div>
								<?php endif ?>
							<?php endfor ?>
						</div>
						<!-- ./am courcelle -->
						<!-- ./so courcelle -->
						<div class="planning-zone soir">
							<?php for ($i=1;$i<=7;$i++): ?>
								<?php $courcelleS=getPlanning($pdo,2,$i,"s"); ?>
								<?php if (!empty($courcelleS)): ?>
									<div class="planning-col">
										<?php foreach ($courcelleS as  $planning): ?>
											<div class="cours <?=$planning['periode']?>" >
												<div class="concept"><?= $planning['concept']?></div>

												<div class="time"><?= substr($planning['hour_start'],0,-3).' - '.substr($planning['hour_end'],0,-3)?></div>

											</div>
										<?php endforeach ?>
									</div>
									<?php else: ?>
										<div class="planning-col">
											<div class="cours"></div>
										</div>
									<?php endif ?>
								<?php endfor ?>
							</div>
							<!-- ./so courcelle -->
						</div>
					</div>








					<!-- ./container -->
				</div>

				<?php
				require '../view/_footer.php';
				?>