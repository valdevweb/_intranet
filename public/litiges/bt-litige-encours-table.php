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
					<th class="sortable align-top">Dossier</th>
					<th class="sortable smaller">Date déclaration</>
						<th class="sortable align-top">Magasin</th>
						<th class="sortable align-top">Code BT</th>
						<th class="sortable align-top">Centrale</th>
						<th class="sortable align-top">Etat</th>
						<th class="sortable align-top text-right">Valo</th>
						<th class="sortable text-center align-top">Ctrl Stock</th>
						<th class="sortable text-center align-top">Statué</th>
						<th class="sortable text-center align-top"><input type="checkbox" name="title"></th>

						<th class="sortable text-center align-top">24/48h</th>
						<th class="sortable text-center align-top">Esp</th>

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


						if($active['commission']==0){
							$class='pending';

						}
						else{
							$class='validated';
						}



						// if(isAction($pdoLitige,$active['id_main'],7)){
						// 	$icoDemandeVideo='<i class="fas fa-video text-green pl-3"></i>';
						// }else{
						// 	if(isAction($pdoLitige,$active['id_main'],6)){
						// 		$icoDemandeVideo='<i class="fas fa-video text-red pl-3"></i>';
						// 	}else{
						// 		$icoDemandeVideo="";
						// 	}
						// }
						$icoDemandeVideo="";


						?>


						<tr class="<?=$active['etat_dossier']?>" id="<?=$active['id_main']?>">
							<td><a href="bt-detail-litige.php?id=<?=$active['id_main']?>"><?=$active['dossier']?></a></td>
							<td><?=$active['datecrea']?></td>
							<td><a href="stat-litige-mag.php?galec=<?=$active['galec']?>"><?=$active['deno']?></a></td>
							<td><?=$active['btlec']?></td>
							<td><?= (isset($arCentrale[$active['centrale']]))?$arCentrale[$active['centrale']] :''?></td>
							<td class="<?=($active['etat']=="Cloturé")?'text-dark-grey':'text-red'?>"><?=$active['etat']?></td>
							<td class="text-right"><?=number_format((float)$active['valo'],2,'.',' ')?>&euro;</td>
							<td class="text-center"><?=$ctrl .$icoDemandeVideo?></td>
							<?php if ($class=='validated'): ?>
								<td class="text-center"><a href="commission-traitement.php?id='.$active['id_main'].'&etat='.$class.'" class="unvalidate"><i class="fas fa-user-check stamp <?= $class?>"></i></a></td>
								<?php else: ?>
									<td class="text-center"><a href="#modal1" data="'.$active['id_main'].'" class="stamps"><i class="fas fa-user-check stamp <?=$class?>"></i></a></td>

								<?php endif ?>
								<td><input type="checkbox" name="pendingbox-<?=$active['id_main'].'-'.$active['commission']?>"></td>

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