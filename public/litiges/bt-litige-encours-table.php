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
					<?php
					foreach ($listLitige as $active)
					{
						if($active['vingtquatre']==1){
							$vingtquatre='<img src="../img/litiges/2448_ico.png">';

						}
						else{
							$vingtquatre="";
						}

						if($active['esp']==1){
							$esp='<img src="../img/litiges/2448esp_ico.png">';
						}
						else{
							$esp="";
						}



						if($active['etat']=="Cloturé"){
							$etat="text-dark-grey";
						}
						else{
							$etat="text-red";
						}

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

						if(isset($arCentrale[$active['centrale']])){
							$centrale=$arCentrale[$active['centrale']];
						}else{
							$centrale='';
						}


						echo '<tr class="'.$active['etat_dossier'].'" id="'.$active['id_main'].'">';
						echo'<td><a href="bt-detail-litige.php?id='.$active['id_main'].'">'.$active['dossier'].'</a></td>';
						echo'<td>'.$active['datecrea'].'</td>';
						echo'<td><a href="stat-litige-mag.php?galec='.$active['galec'].'">'.$active['deno'].'</a></td>';
						echo'<td>'.$active['btlec'].'</td>';
						echo'<td>'.$centrale.'</td>';
						echo'<td class="'.$etat.'">'.$active['etat'].'</td>';
						echo'<td class="text-right">'.number_format((float)$active['valo'],2,'.',' ').'&euro;</td>';
						echo '<td class="text-center">'.$ctrl .$icoDemandeVideo.'</td>';
						if($class=='validated'){

							echo '<td class="text-center"><a href="commission-traitement.php?id='.$active['id_main'].'&etat='.$class.'" class="unvalidate"><i class="fas fa-user-check stamp '.$class.'"></i></a></td>';
						}
						else{
							echo '<td class="text-center"><a href="#modal1" data="'.$active['id_main'].'" class="stamps"><i class="fas fa-user-check stamp '.$class.'"></i></a></td>';

						}
						echo '<td><input type="checkbox" name="pendingbox-'.$active['id_main'].'-'.$active['commission'].'"></td>';

						echo '<td class="text-center">'.$vingtquatre .'</td>';
						echo '<td class="text-center">'.$esp .'</td>';
						echo '</tr>';

					}

					?>
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