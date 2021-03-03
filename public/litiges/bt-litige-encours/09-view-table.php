<div class="row">
	<div class="col">

		<?php if (isset($paramList) && !empty($paramList) ): ?>

		<?php else: ?>
			<h4 class="text-main-blue text-center"> <?=$nbLitiges?> litiges pour un montant de <?= number_format((float)$valoTotalDefault,2,'.',' ')?></h4>

		<?php endif ?>
	</div>
</div>

<!-- start row -->
<div class="row">
	<div class="col">
		<form method="post" action=<?=$_SERVER['PHP_SELF']?>>
			<table class="table border" id="dossier">
				<thead class="thead-dark smaller">
					<th class="align-top">Dossier</th>
					<th class="smaller">Date déclaration</>
						<th class="align-top">Magasin</th>
						<th class="align-top">Code BT</th>
						<th class="align-top">Centrale</th>
						<th class="align-top">Etat</th>
						<th class="align-top">Typo</th>
						<th class="align-top text-right valo">Valo</th>
						<th class="text-center align-top">GT13</th>
						<th class="text-center align-top">Ctrl Stock</th>
						<th class="text-center align-top">Statué</th>
						<th class="text-center align-top"><input type="checkbox" name="title" id="main-check"></th>

						<th class="text-center align-top">24/48h</th>
						<th class="text-center align-top">Esp</th>

					</tr>
				</thead>
				<tbody id="tosort">
					<?php foreach ($listLitige as $active):?>
						<?php
						if($active['ctrl_ok']==0){
							$ctrl='';
						}
						elseif($active['ctrl_ok']==1){
							$ctrl= '<i class="fas fa-boxes pl-3 text-green"></i>';
						}
						elseif($active['ctrl_ok']==2){
							$ctrl='<i class="fas fa-hourglass-end pl-3 text-red"></i>';
						}
						if($active['occasion']==1){
							$icoOcc="<img src='../img/logos/leclerc-occasion-circle-mini.gif'>";
						}else{
							$icoOcc="";
						}
						if(!empty($active['id_robbery'])){
							$icoRobbery="<img src='../img/litiges/thief35.png'>";
						}else{
							$icoRobbery="";
						}

						if($active['commission']==0){
							$class='pending';
						}
						else{
							$class='validated';
						}


						$icoDemandeVideo="";
						if(isset($listVideoOk[$active['id_main']])){
							$icoDemandeVideo='<i class="fas fa-video text-green pl-3"></i>';

						}elseif (isset($listVideoKo[$active['id_main']])) {
							$icoDemandeVideo='<i class="fas fa-video text-red pl-3"></i>';
						}
						if(isset($arMagOcc[$active['btlec']])){
							$bgOccasion="bg-light-blue";
						}else{
							$bgOccasion="";
						}
						$unreadMsg="";
						$classUnreadMsg="read";
						if (!empty($unread)) {
							if(in_array($active['id_main'],$unread)){
								$unreadMsg="<i class='fas fa-bell pl-1 text-yellow'></i>";
								$classUnreadMsg="unread";
							}
						}
						$unreadActionSavIco="";
						if (!empty($unreadActionSav)) {
							if(in_array($active['id_main'],$unreadActionSav)){
								$unreadActionSavIco="<i class='fas fa-bell pl-1 text-green'></i>";
							}
						}

						?>


						<tr class="<?=$active['etat_dossier'] .' ' .$bgOccasion .' '. $classUnreadMsg?>" id="<?=$active['id_main']?>">
							<td class="nowrap"><a href="bt-detail-litige.php?id=<?=$active['id_main']?>"><?=$active['dossier'].$unreadMsg. $unreadActionSavIco?></a></td>
							<td><?=$active['datecrea']?></td>
							<td><a href="stat-litige-mag.php?galec=<?=$active['galec']?>"><?=$active['deno']?></a></td>
							<td><?=$active['btlec']?></td>
							<td><?= (isset($arCentrale[$active['centrale']]))?$arCentrale[$active['centrale']] :''?></td>
							<td class="<?=($active['id_etat']=="1" ||$active['id_etat']=="20" )?'text-dark-grey':'text-red'?>"><?=$active['etat']?></td>
							<td><?=(!empty($active['id_typo'])) ? $arTypo[$active['id_typo']]:""?></td>
							<td class="text-right nowrap"><?=number_format((float)$active['valo'],2,'.',' ')?>&euro;</td>
							<td class="text-center"><?=$icoOcc?></td>
							<td class="text-center"><?=$icoRobbery. $ctrl .$icoDemandeVideo?></td>
							<?php if ($class=='validated'): ?>
								<td class="text-center"><a href="commission-traitement.php?id=<?=$active['id_main'].'&etat='.$class?>" class="unvalidate"><i class="fas fa-user-check stamp <?= $class?>"></i></a></td>
								<?php else: ?>
									<td class="text-center"><a href="#modal1" data="<?=$active['id_main']?>" class="stamps"><i class="fas fa-user-check stamp <?=$class?>"></i></a></td>

								<?php endif ?>
								<td><input type="checkbox" name="pendingbox-<?=$active['id_main'].'-'.$active['commission']?>" class="cb-commission"></td>

								<td class="text-center"><?=($active['vingtquatre']==1)? '<img src="../img/litiges/2448_ico.png">' :''?></td>
								<td class="text-center"><?= ($active['esp']==1)? '<img src="../img/litiges/2448esp_ico.png">' :''?></td>
							</tr>



						<?php endforeach ?>
					</tbody>
				</table>



				<?php if($_SESSION['id_web_user'] ==959 || $_SESSION['id_web_user'] ==981): ?>

					<div class="row">
						<div class="col text-right mr-5">
							<button type="submit"  class="btn btn-red right mb-5" name="chg_pending"><i class="fas fa-user-check pr-3"></i>Statuer</button>
						</div>
					</div>
				<?php endif	?>

			</form>
		</div>

	</div>