<?php
function getFinance($pdoQlik, $btlec, $year){
	$req=$pdoQlik->prepare("SELECT CA_Annuel FROM statsventesadh WHERE CodeBtlec= :btlec AND AnneeCA= :year");
	$req->execute(array(
		':btlec' =>$btlec,
		':year'	=>$year
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$sumValo=0;
foreach ($litige as $prod)
{
	$valo=round(($prod['tarif'] / $prod['qte_cde'])*$prod['qte_litige'],2);

	if($prod['inversion'] !="")
	{
		$valoInv=round( $prod['qte_cde']*$prod['inv_tarif'],2);
		$sumValo=$sumValo+$valo-$valoInv;
	}
	else
	{
		$sumValo=$sumValo + $valo;

	}
}
$yearN=date('Y');
$yearNUn= date("Y",strtotime("-1 year"));
$yearNDeux= date("Y",strtotime("-2 year"));

$financeN=getFinance($pdoQlik,$litige[0]['btlec'],$yearN);
$financeNUn=getFinance($pdoQlik,$litige[0]['btlec'],$yearNUn);
$financeNDeux=getFinance($pdoQlik,$litige[0]['btlec'],$yearNDeux);
$sumValo=number_format((float)$sumValo,2,'.','');

?>


<table class="padding-table border-table-prim">
		<tr>
			<td class="deux bg-prim text-white text-center" colspan="3">CHIFFRE D'AFFAIRE</td>
			<!-- <td class="deux bg-prim text-white bigger" colspan="2"></td> -->
		</tr>
		<tr>
			<td class="trois text-right heavy"><?=$yearN?></td>
			<td class="trois text-right heavy"><?=$yearNUn?></td>
			<td class="trois text-right heavy"><?=$yearNDeux?></td>
		</tr>
		<tr>
			<td class="trois text-right"><?=number_format((float)$financeN['CA_Annuel'],2,'.',' ')?>&euro;</td>
			<td class="trois text-right"><?=number_format((float)$financeNUn['CA_Annuel'],2,'.',' ')?>&euro;</td>
			<td class="trois text-right"><?=number_format((float)$financeNDeux['CA_Annuel'],2,'.',' ')?>&euro;</td>

		</tr>
	</table>
	<div class="spacing-s"></div>

	<table class="padding-table border-table-prim">

		<tr>
			<td class="full-width">
				<span class="heavy">Commentaires magasin :</span>
				<?= $initialCmt['msg']?>
			</td>
		</tr>

	</table>


	<p class="text-center heavy text-prim">VALORISATION : <?= $litige[0]['valo']?> &euro;</p>