	<div class="row pb-3">
		<div class="col-7">
			<?php
			echo '<table class="table text-right table-bordered ">';
			echo '<tr class="bg-blue">';
			echo '<td></td>';
			echo '<td>'.$yearN.'</td>';
			echo '<td>'.$yearNUn .'</td>';
			echo '<td>'.$yearNDeux .'</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td class="text-main-blue heavy"> Chiffres d\'affaire :</td>';
			echo '<td>'.number_format((float)$financeN['CA_Annuel'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$financeNUn['CA_Annuel'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$financeNDeux['CA_Annuel'],2,'.',' ').'&euro;</td>';
			echo '</tr>';



			echo '<tr>';
			echo '<td class="text-main-blue heavy">Réclamé :</td>';
			echo '<td>'.number_format((float)$reclameN['sumValo'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$reclameNUn['sumValo'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$reclameNDeux['sumValo'],2,'.',' ').'&euro;</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td class="text-main-blue heavy">Remboursé :</td>';
			echo '<td>'.number_format((float)$rembourseN['sumMtMag'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$rembourseNUn['sumMtMag'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$rembourseNDeux['sumMtMag'],2,'.',' ').'&euro;</td>';
			echo '</tr>';

			echo '<td class="text-main-blue heavy"> Coût BTlec</td>';
			echo '<td>'.number_format((float)$coutN,2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$coutNUn,2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$coutNDeux,2,'.',' ').'&euro;</td>';
			echo '</tr>';
			echo '</table>';
			?>
		</div>
		<div class="col">
			<p class="text-right pt-0">
				<?php if ($prev!=0): ?>
					<a href="bt-detail-litige.php?id=<?=$prev?>" class="grey-link"><i class="fas fa-angle-left pr-2 pt-2"></i>Litige précédent</a>
				<?php endif ?>
				<?php if ($next!=$last): ?>
					<a href="bt-detail-litige.php?id=<?=$next?>" class="grey-link"><i class="fas fa-angle-right pl-5 pr-2 pt-1"></i>Litige suivant</a>

				<?php endif ?>
			</p>
		</div>
		<div class="col-auto  pt-5">
			<p class="text-right"><a href="bt-litige-encours.php" class="btn btn-primary">Retour</a></p>
		</div>
	</div>