<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{
			font-family: helvetica, sans-serif;
			/* font-size: 10pt; */
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
		.entete{
			font-size: 10px;
			font-style: italic;
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

	</style>


	<title></title>
</head>
<body>
	<p class="text-right entete">état au <?= date('d-m-Y')?></p>
	<h3>Chiffres d'affaire du magasin <?= $listLitige[0]['mag']?> - <?= $listLitige[0]['btlec']?> </h3>

	<table class="border-table border-table-sec">
		<tr class="bg-sec">
			<th><?=$yearN?></th>
			<th><?=$yearNUn?></th>
			<th><?=$yearNDeux?></th>
		</tr>
		<tr>
			<td class="text-right"><?=number_format((float)$financeN['CA_Annuel'],2,'.',' ')?>&euro;</td>
			<td class="text-right"><?=number_format((float)$financeNUn['CA_Annuel'],2,'.',' ')?>&euro;</td>
			<td class="text-right"><?=number_format((float)$financeNDeux['CA_Annuel'],2,'.',' ')?>&euro;</td>
		</tr>
	</table>
	<div class="spacing-l"></div>
	<h3>Réclamations : </h3>
	<table class="border-table border-table-sec">
		<tr class="bg-sec">
			<th>N°</th>
			<th>Date</th>
			<th>Service</th>
			<th>Typologie</th>
			<th>Imputation</th>
			<th>Statut</th>
			<th>Valorisation magasin</th>
			<th>Analyse</th>
			<th>Réponse</th>
			<th>Coût BTlec</th>
		</tr>
		<tbody>

			<?php
			foreach ($listLitige as $litige)
			{
				$cout=$litige['mt_transp']+$litige['mt_assur']+$litige['mt_fourn']+$litige['mt_mag'];
				$cout=number_format((float)$cout,2,'.','');
				echo '<tr>';
				echo '<td>'.$litige['dossier'].'</td>';
				echo '<td>'.$litige['datecrea'].'</td>';
				echo '<td>'.$litige['gt'].'</td>';
				echo '<td>'.$litige['typo'].'</td>';
				echo '<td>'.$litige['imputation'].'</td>';
				echo '<td>'.$litige['etat'].'</td>';
				echo '<td class="text-right">'.$litige['valo'].'&euro;</td>';
				echo '<td>'.$litige['analyse'].'</td>';
				echo '<td>'.$litige['conclusion'].'</td>';
				echo '<td class="text-right">'.$cout.' &euro;</td>';
				echo '</tr>';


			}

			?>
		</tbody>
	</table>
	<p>Nombre de déclarations : <?= $nbLitiges?><br>
		Valeur totale déclarée par le magasin : <?= $valoTotal?> &euro;</p>
	</body>
	</html>




