<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">

			/*
colors :

rouge :A63446;
vert clair :9bdeac;
beige : ead3c1;
rose : #ff6f87;
vert bleu foncé :00a6a2;

*/
body{
	font-family: helvetica, sans-serif;
	font-size: 14pt;
}
.bigger{
	font-size: 20px;
}
th{
	background-color :#0075BC;
	color: #fff;
	padding: 10px;
}
table,td, tr{
	font-size: 10px;

}

.heavy{
	font-weight: bold;
}
.text-white{
	color: #ffffff;
}
.text-black{
	color: #000;
}
.text-prim{
	color: #A63446;
}
.text-sec{
	color: #347fa6;
}
.text-third{
	color: #ead3c1;
}
.text-fourth{
	color: #ff6f87;
}
.text-fifth{
	color: #00A6A2;
}

.bg-white{
	background-color: #fff;
}
.bg-black{
	background-color: #000;
}
.bg-prim{
	background-color: #A63446;
}
.bg-sec{
	background-color: #347fa6;
}
.bg-third{
	background-color: #ead3c1;
}
.bg-fourth{
	background-color: #ff6f87;
}
.bg-fifth{
	background-color: #00A6A2;
}

.bg-dark-grey{
	background-color:#343a40;

}


.bordered-prim{
	border: 1px solid #A63446;

}

.border-table-prim, .border-table-prim td{
	border: 1px solid #A63446;
	border-collapse: collapse;
}

.border-table-sec, .border-table-sec td{
	border: 1px solid #347fa6;
	border-collapse: collapse;
}
.border-table-grey, .border-table-grey td{
	border: 1px solid #343a40;
	border-collapse: collapse;
}



h2{
	font-size: 16px;
}
.text-center{
	text-align: center;
}
.text-right{
	text-align : right;
}

.border-table, .border-table td{
	border: 1px solid black;
	border-collapse: collapse;
}
.border-table td{
	padding:  10px;
}

.spacing-xs{
	height:  1px;

}

.spacing-s{
	height:  15px;
	border : 0;
}

.spacing-m{
	height:  20px;
	border : 0;
}
.spacing-l{
	height:  40px;
	border : 0;
}
.padding-table, .padding-table td{
	padding:  10px;
	border-collapse: collapse;
	border:  0;
}



.full-width{
	width: 700px;
}

.dix{
	width: 70px;
}
.neuf{
	width: 77px;
}
.huit{
	width: 87px;
}
.sept{
	width: 100px;
}
.six{
	width: 116px;
}
.cinq{
	width: 140px;
}
.quatre{
	width: 175px;
}
.trois{
	width: 233px;
}
.deux{
	width: 350px;
}
	.entete{
			font-size: 10px;
			font-style: italic;
		}
</style>
<?php

$sumValo=0;
foreach ($fLitige as $prod)
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
$financeN=getFinance($pdoQlik,$fLitige[0]['btlec'],$yearN);
$financeNUn=getFinance($pdoQlik,$fLitige[0]['btlec'],$yearNUn);
$financeNDeux=getFinance($pdoQlik,$fLitige[0]['btlec'],$yearNDeux);
$sumValo=number_format((float)$sumValo,2,'.','');


?>
<title></title>
</head>
<body>
	<p class="text-right entete">fiche au <?= date('d-m-Y')?></p>

	<table class="padding-table border-table-prim">
		<tr>
			<td class="deux bg-prim text-white bigger text-center" colspan="4">FICHE DE SUIVI LITIGE - litige n°  <?= $fLitige[0]['dossier']?></td>
			<!-- <td class="deux bg-prim text-white bigger" colspan="2"></td> -->
		</tr>
		<tr>
			<td class="quatre heavy">ADHERENT</td>
			<td class="quatre"><?=$fLitige[0]['mag'] .' - '. $fLitige[0]['btlec']?></td>
			<td class="quatre  heavy">DATE DECLARATION</td>
			<td class="quatre"><?=$fLitige[0]['datecrea']?></td>
		</tr>

	</table>
	<div class="spacing-s"></div>

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
				<?= $firstDial['msg']?>
			</td>
		</tr>

	</table>


	<p class="text-center heavy text-prim">VALORISATION : <?= $valoMag?></p>
	<div class="spacing-s"></div>

	<table class="padding-table border-table-grey">

		<tr>
			<td class="trois text-center bg-dark-grey text-white">IMPUTATION</td>
			<td class="trois text-center bg-dark-grey text-white">TYPOLOGIE</td>
			<td class="trois text-center bg-dark-grey text-white">TRAITEMENT</td>
		</tr>
		<tr>
			<td class="trois text-center"><?=$analyse['imputation']?></td>
			<td class="trois text-center"><?=$analyse['typo']?></td>
			<td class="trois text-center"><?=$analyse['conclusion']?></td>
		</tr>
	</table>
	<div class="spacing-s"></div>
	<table class="padding-table border-table-grey">
		<tr>
			<td class="trois text-center bg-dark-grey text-white">PREPARATEUR</td>
			<td class="trois text-center bg-dark-grey text-white">CONTROLEUR</td>
			<td class="trois text-center bg-dark-grey text-white">CHARGEUR</td>
		</tr>
		<tr>
			<td class="trois text-center"><?=$infos['fullprepa']?></td>
			<td class="trois text-center"><?=$infos['fullctrl']?></td>
			<td class="trois text-center"><?=$infos['fullchg']?></td>
		</tr>
	</table>

	<div class="spacing-s"></div>

	<table>
		<tr>
			<td>
				<table class="padding-table border-table-grey">
					<tr>
						<td class="cinq bg-dark-grey text-white">DATE PREPA :</td>
						<td class="cinq"><?=$infos['dateprepa']?></td>

					</tr>
				</table>
			</td>
			<td class="text-right" style="width: 350px;">
				<?= $vingtquatre?>
			</td>
		</tr>


	</table>

	<div class="spacing-s"></div>

	<table class="padding-table border-table-grey">
		<tr>
			<td class="trois text-center bg-dark-grey text-white">TRANSPORTEUR</td>
			<td class="trois text-center bg-dark-grey text-white">AFFRETE</td>
			<td class="trois text-center bg-dark-grey text-white">TRANSIT</td>
		</tr>
		<tr>
			<td class="trois text-center"><?=$infos['transporteur']?></td>
			<td class="trois text-center"><?=$infos['affrete']?></td>
			<td class="trois text-center"><?=$infos['transit']?></td>
		</tr>
	</table>
	<div class="spacing-s"></div>
	<table class="padding-table border-table-sec">
		<tr>
			<td class="dix bg-sec bg-sec text-white">CODE ARTICLE</td>
			<td class="dix bg-sec bg-sec text-white">DOSSIER</td>
			<td class="cinq bg-sec text-white">DESIGNATION</td>
			<td class="bg-sec text-white" style="width:50px;">QTE</td>
			<td class="bg-sec text-white">FOURNISSEUR</td>
			<td class="huit bg-sec text-white">VALORISATION</td>
			<td class="cinq bg-sec text-white">RECLAMATION</td>
		</tr>
		<?php
		// $sumValo=0;
		foreach ($fLitige as $prod)
		{
			$valo=round(($prod['tarif'] / $prod['qte_cde'])*$prod['qte_litige'],2);
			echo '<tr>';
			echo'<td>'.$prod['article'].'</td>';
			echo'<td>'.$prod['dossier_gessica'].'</td>';
			echo'<td>'.$prod['descr'].'</td>';
			echo'<td class="text-right">'.$prod['qte_litige'].'</td>';
			echo'<td>'.$prod['fournisseur'].'</td>';
			echo'<td class="text-right">'.number_format((float)$valo,2,'.','').'&euro;</td>';
			echo'<td>'.$prod['reclamation'].'</td>';
			echo '</tr>';
			if($prod['inversion'] !="")
			{
				$valoInv=round( $prod['inv_qte']*$prod['inv_tarif'],2);
				echo '<tr><td colspan="5" class="text-center text-prim heavy">Produit reçu à la place de la référence ci-dessus :</td></tr>';
				echo '<tr>';
				echo'<td class="text-prim heavy">'.$prod['inv_article'].'</td>';
				echo'<td class="text-prim heavy">'.$prod['inv_descr'].'</td>';
				echo'<td class="text-right text-prim heavy">'.$prod['inv_qte'].'</td>';
				echo'<td class="text-right text-prim heavy">'.$prod['inv_fournisseur'].'</td>';
				echo'<td class="text-right text-prim heavy">'.number_format((float)$valoInv,2,'.','').'&euro;</td>';
				echo'<td class="text-right"></td>';
				echo '</tr>';
				// $sumValo=$sumValo+$valo-$valoInv;
			}
			else
			{
				// $sumValo=$sumValo + $valo;

			}
		}

		?>

	</table>
	<div class="spacing-s"></div>
	<table class="padding-table border-table-grey">
		<tr>
			<td class="cinq bg-dark-grey text-white">Date</td>
			<td class="bg-dark-grey text-white" style="width:560px;">Action</td>
		</tr>
		<?php
		foreach ($actionList as $action)
		{
			echo '<tr>';
			echo '<td>'.$action['dateFr'].'</td>';
			echo '<td>'.$action['libelle'].'</td>';


		}


		?>
	</table>





</body>
</html>




