<?php if(isset($arMagOcc[$codeBt]) ):?>


	<table class="table text-right table-bordered table-sm">
		<tr class="bg-blue">
			<td></td>
			<td colspan="2"><?=$yearN?></td>
			<td colspan="2"><?=$yearNUn ?></td>
			<td colspan="2"><?=$yearNDeux ?></td>
		</tr>
			<tr class="bg-light-grey">
			<td class="heavy nowrap"> Chiffres d'affaire :</td>

			<td class="font-weight-bold" colspan="2"><?=isset($financeN['CA_Annuel'])?number_format((float)$financeN['CA_Annuel'],2,'.',' '):""?>&euro;</td>

			<td class="font-weight-bold"  colspan="2"><?=isset($financeNUn['CA_Annuel'])?number_format((float)$financeNUn['CA_Annuel'],2,'.',' '):0?>&euro;</td>
			<td class="font-weight-bold" colspan="2"><?=isset($financeNDeux['CA_Annuel'])?number_format((float)$financeNDeux['CA_Annuel'],2,'.',' '):"0"?>&euro;</td>
		</tr>
		<tr >
			<td class="bg-blue"></td>
			<td class="nowrap bg-blue">hors occasion</td>
			<td class="bg-blue" >occasion</td>
			<td class="nowrap bg-blue">hors occasion</td>
			<td class="bg-blue">occasion</td>
			<td class="nowrap bg-blue">hors occasion</td>
			<td class="bg-blue">occasion</td>
		</tr>

		<tr>
			<td class=" text-main-blue heavy">Réclamé :</td>
			<td class="nowrap"><?=number_format((float)$horsOccReclameN,2,'.',' ')?>&euro;</td>
			<td class="nowrap bg-light-blue"><?=number_format((float)$occReclameN['sumValo'],2,'.',' ')?>&euro;</td>
			<td class="nowrap" ><?=number_format((float)$horsOccReclameNUn,2,'.',' ')?>&euro;</td>
			<td class="nowrap bg-light-blue"><?=number_format((float)$occReclameNUn['sumValo'],2,'.',' ')?>&euro;</td>
			<td class="nowrap"><?=number_format((float)$horsOccReclameNDeux,2,'.',' ')?>&euro;</td>
			<td  class="nowrap bg-light-blue"><?=number_format((float)$occReclameNDeux['sumValo'],2,'.',' ')?>&euro;</td>

		</tr>
		<tr>

			<td class="text-main-blue heavy">Remboursé :</td>
			<td class="nowrap" ><?=number_format((float)$horsOccRembourseN,2,'.',' ')?>&euro;</td>
			<td class="nowrap bg-light-blue"><?=number_format((float)$occRembourseN['sumMtMag'],2,'.',' ')?>&euro;</td>
			<td class="nowrap"><?=number_format((float)$horsOccRembourseNUn,2,'.',' ')?>&euro;</td>
			<td  class="nowrap bg-light-blue"><?=number_format((float)$occRembourseNUn['sumMtMag'],2,'.',' ')?>&euro;</td>
			<td class="nowrap"><?=number_format((float)$horsOccRembourseNDeux,2,'.',' ')?>&euro;</td>
			<td class="nowrap bg-light-blue"><?=number_format((float)$occRembourseNDeux['sumMtMag'],2,'.',' ')?>&euro;</td>
		</tr>

		<tr>
			<td class="text-main-blue heavy">Coût :</td>
			<td class="nowrap" ><?=number_format((float)$horsOccCoutN,2,'.',' ')?>&euro;</td>
			<td class="nowrap bg-light-blue"><?=number_format((float)$occCoutN,2,'.',' ')?>&euro;</td>
			<td class="nowrap"><?=number_format((float)$horsOccCoutNUn,2,'.',' ')?>&euro;</td>
			<td  class="nowrap bg-light-blue"><?=number_format((float)$occCoutNUn,2,'.',' ')?>&euro;</td>
			<td class="nowrap" ><?=number_format((float)$horsOccCoutNDeux,2,'.',' ')?>&euro;</td>
			<td class="nowrap bg-light-blue"><?=number_format((float)$occCoutNDeux,2,'.',' ')?>&euro;</td>

		</tr>

	</table>
<?php else: ?>

	<table class="table text-right table-bordered table-sm">
		<tr class="bg-blue">
			<td></td>
			<td><?=$yearN?></td>
			<td><?=$yearNUn ?></td>
			<td><?=$yearNDeux ?></td>
		</tr>
		<tr>
			<td class="text-main-blue heavy"> Chiffres d'affaire :</td>
			<td><?=isset($financeN['CA_Annuel'])?number_format((float)$financeN['CA_Annuel'],2,'.',' '):""?>&euro;</td>
			<td><?=isset($financeNUn['CA_Annuel'])?number_format((float)$financeNUn['CA_Annuel'],2,'.',' '):0?>&euro;</td>
			<td><?=isset($financeNDeux['CA_Annuel'])?number_format((float)$financeNDeux['CA_Annuel'],2,'.',' '):"0"?>&euro;</td>
		</tr>
		<tr>
			<td class="text-main-blue heavy">Réclamé :</td>
			<td><?=number_format((float)$totalReclameN['sumValo'],2,'.',' ')?>&euro;</td>
			<td><?=number_format((float)$totalReclameNUn['sumValo'],2,'.',' ')?>&euro;</td>
			<td><?=number_format((float)$totalReclameNDeux['sumValo'],2,'.',' ')?>&euro;</td>
		</tr>
		<tr>
			<td class="text-main-blue heavy">Remboursé :</td>
			<td><?=number_format((float)$totalRembourseN['sumMtMag'],2,'.',' ')?>&euro;</td>
			<td><?=number_format((float)$totalRembourseNUn['sumMtMag'],2,'.',' ')?>&euro;</td>
			<td><?=number_format((float)$totalRembourseNDeux['sumMtMag'],2,'.',' ')?>&euro;</td>
		</tr>
		<tr>
			<td class="text-main-blue heavy"> Coût BTlec</td>
			<td><?=number_format((float)$totalCoutN,2,'.',' ')?>&euro;</td>
			<td><?=number_format((float)$totalCoutNUn,2,'.',' ')?>&euro;</td>
			<td><?=number_format((float)$totalCoutNDeux,2,'.',' ')?>&euro;</td>
		</tr>
	</table>



	<?php endif	 ?>