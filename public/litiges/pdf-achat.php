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
p{
	font-size: 12px;
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
.footer{
	font-size: 10px;
	text-align : right;
}
</style>
<?php

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

$sumValo=number_format((float)$sumValo,2,'.','');

?>
<title></title>
</head>
<body>
	<p class="text-right entete">date d'édition : <?= date('d-m-Y')?></p>

	<table class="padding-table border-table-prim">
		<tr>
			<td class="deux bg-prim text-white bigger text-center" colspan="4">LITIGE n°  <?= $litige[0]['dossier']?></td>
			<!-- <td class="deux bg-prim text-white bigger" colspan="2"></td> -->
		</tr>
		<tr>
			<td class="quatre heavy"><?=$litige[0]['centrale']?></td>
			<td class="quatre"><?=$litige[0]['mag'] .' - '. $litige[0]['btlec']?></td>
			<td class="quatre  heavy">DATE DECLARATION</td>
			<td class="quatre"><?=$litige[0]['datecrea']?></td>
		</tr>

	</table>


	<div class="spacing-s"></div>

	<h2 class="text-center">Détail du litige</h2>

	<p>Commentaire du magasin : <?= isset($firstCmt['msg']) ? $firstCmt['msg'] : ''?></p>

	<div class="spacing-s"></div>
	<table class="padding-table border-table-sec">
		<tr>
			<td class="cinq bg-sec text-white">CODE ARTICLE</td>
			<td class="cinq bg-sec text-white">DESIGNATION</td>
			<td class="cinq bg-sec text-white">QUANTITE</td>
			<td class="cinq bg-sec text-white">VALORISATION</td>
			<td class="cinq bg-sec text-white">RECLAMATION</td>
		</tr>
		<?php
		$sumValo=0;
		foreach ($litige as $prod)
		{
			echo '<tr>';
			echo'<td>'.$prod['article'].'</td>';
			echo'<td>'.$prod['descr'].'</td>';
			echo'<td class="text-right">'.$prod['qte_litige'].'</td>';
			echo'<td class="text-right">'.number_format((float)$prod['valo_line'],2,'.','').'&euro;</td>';
			echo'<td>'.$prod['reclamation'].'</td>';
			echo '</tr>';
			if($prod['inversion'] !="")
			{
				$valoInv=round( $prod['qte_cde']*$prod['inv_tarif'],2);
				echo '<tr><td colspan="5" class="text-center text-prim heavy">Produit reçu à la place de la référence ci-dessus :</td></tr>';
				echo '<tr>';
				echo'<td class="text-prim heavy">'.$prod['inv_article'].'</td>';
				echo'<td class="text-prim heavy">'.$prod['inv_descr'].'</td>';
				echo'<td class="text-right text-prim heavy">'.$prod['qte_litige'].'</td>';
				echo'<td class="text-right text-prim heavy">'.number_format((float)$valoInv,2,'.','').'&euro;</td>';
				echo'<td class="text-right"></td>';
				echo '</tr>';
			}

		}

		?>

	</table>
	<div class="spacing-m"></div>
	<h2 class="text-center">Echanges avec le magasin</h2>


	<?php
	if(isset($dials[0]['msg']))
	{

		echo '<table class="padding-table border-table-sec">';
		echo '<tr>';
		echo '<td class="bg-sec text-white">date</td>';
		echo '<td class="bg-sec text-white">Interlocuteur</td>';
		echo '<td class="bg-sec text-white">Message</td>';
		echo '</tr>';
		foreach ($dials as $dial) {
			if($dial['mag']==1){
					$personn=UserHelpers::getMagInfoByIdWebUser($pdoUser, $pdoMag, $dial['id_web_user'],'deno');

			}else{
				$personn=UserHelpers::getInternUser($pdoUser, $dial['id_web_user']);
				$personn=$personn['fullname'];
			}

			echo '<tr>';
			echo '<td>'.$dial['dateFr'].'</td>';
			echo '<td>'.$personn.'</td>';
			echo '<td>'.$dial['msg'].'</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
	else{
		echo '<p>Aucun message n\'a été échangé avec le magasin</p>';

	}


	?>

</body>
</html>




