	<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

		<table class="table table-sm table-striped" id="table-cde-encours">
			<thead class="thead-dark">
				<thead>
					<?php if (!isset($_SESSION['encours_col'])): ?>
						<tr>
							<?php for ($i=0; $i< count($tableColTh);$i++): ?>
								<th class="align-top bg-blue"><?=$tableColTh[$i]?></th>
							<?php endfor ?>
							<th class="align-top bg-blue text-center"><i class="far fa-square"></i></th>
							<th class="align-top bg-blue">Prévisions/commentaires</th>

						<?php else: ?>
							<?php for ($i=0; $i< count($tableColTh);$i++): ?>
								<?php  if(in_array(0,$_SESSION['encours_col'])): ?>
									<th class="align-top bg-blue"><?=$tableColTh[$i]?></th>
								<?php endif ?>
							<?php endfor ?>
							<th class="align-top bg-blue text-center"><i class="far fa-square"></i></th>
							<th class="align-top bg-blue">Prévisions/commentaires</th>
						<?php endif ?>

					</thead>
				</thead>
				<tbody>
					<?php foreach ($listCdes as $key => $cdes): ?>
						<?php
						$bgColor="";
						$percentRecu="";
						$totalPrevi=0;
						$restant="";

						if($cdes['qte_init']!=0){
							$recu=$cdes['qte_init']-$cdes['qte_cde'];
							if($recu!=0){
								$percentRecu=($recu*100)/$cdes['qte_init'];
								$percentRecu=floor ($percentRecu);
							}else{
								$percentRecu=0 ;
							}
							if($percentRecu<50){
								$bgColor="bg-red";
							}elseif($percentRecu>=50 && $percentRecu<90){
								$bgColor="bg-yellow";
							}elseif($percentRecu>=90){
								$bgColor="bg-green";
							}
							$percentRecu=$percentRecu."%";
						}
						?>
						<?php if (!isset($_SESSION['encours_col'])): ?>
							<tr id="<?=$cdes['id']?>" data="nosession">
								<?php include '11-table-line-no.php' ?>
							</tr>
						<?php else: ?>
							<tr id="<?=$cdes['id']?>" data="session">
								<?php include '11-table-line-yes.php' ?>
							</tr>

						<?php endif ?>

					<?php endforeach ?>

				</tbody>
			</table>
			<div class="row">
				<div class="col text-right">Pour sélectionner toutes les lignes affichées :</div>
				<div class="col-auto">
					<input class="form-check-input" type="checkbox" value="1"  name="checkall" id="checkall">
					<label class="form-check-label" >Cocher tout</label>
				</div>
				<div class="col-"></div>
			</div>

			<div id="floating-nav">
				<button class="btn btn-orange" name="save_all"><i class="fas fa-save pr-3"></i>Saisir</button>
			</div>
		</form>
