<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{
			font-family: helvetica, sans-serif;
			font-size: 14pt;
			color: #303030

		}
		.container{
			margin-top: 200px;
		}
		.bigger{
			font-size: 20px;
		}
		.txt-small{
			font-size: 10pt;
		}
		@page{
			margin-top: 4cm;
			margin-bottom: 4cm;
			header: myHeader;
			footer: myFooter;
		}
		.text-center{
			text-align: center;
		}
		h2{
			font-size: 22px;
		}
		table,td, tr{
			font-size: 11px;
			border-collapse: collapse;

		}
		p{
			font-size: 11px;
		}
		table.one-border{
			overflow:wrap;
			border: 1px solid #999;

		}

		table.one-border td{
			font-size: 10pt;
		}
		table.border-table{
			overflow:wrap;
			border: 1px solid #999;
		}
		table.border-table td{
			border: 1px solid #999;
			padding: 10px;
		}
		table.full-width{
			width: 100%;
		}
		.text-right{
			text-align: right;
		}

		tr.thead td{
			padding:5px 10px;
			vertical-align: middle !important;
			background-color: #303030;
			color: #fff;
			font-weight: bold;
		}
		.table-footer td{
			font-weight: bold;
			font-size: 14px;

		}

		.font-weight-bold{
			font-weight: bold;
		}
	</style>
	<title></title>
</head>
<body>
	<htmlpageheader name="myHeader" style="display:none">
		<table style="border-bottom: 1px solid #ccc; width:100%">
			<tr>
				<td style="width :50%; text-align: left;  font-weight: bold; font-size: 14pt; color: #606060">
					Bon de livraison
				</td>
				<td style="width :50%;  text-align: right;  font-weight: bold; font-size: 10pt; color: #999">
					<img src="../img/logos/leclerc-occasion.gif"> by BTlec
				</td>
			</tr>
		</table>
		</htmlpageheader>

		<table class="one-border">
			<tr>
				<td style="padding: 10px;">Magasin : </td>
			</tr>
			<tr>
				<td  style="padding: 5px 20px 10px;">
					<div class="font-weight-bold"><?=$infoMag['deno']?></div>
					<?=$infoMag['ad1']?><br>
					<?=$infoMag['ad2']?><br>
					<?=$infoMag['cp']?> <?=$infoMag['ville']?>

				</td>
			</tr>

		</table>
		<h2 class="text-center">Commande n° <?=$infoCde[0]['id_cde']?></h2>
		<p class="text-right">Date de la commande : <?=date('d/m/Y', strtotime($infoCde[0]['date_cde']))?> </p>
		<table class="border-table full-width" autosize="1" style="overflow: wrap">

			<tr class="thead">
				<td>Palette</td>
				<td>Code article</td>
				<td>Marque</td>
				<td>Désignation</td>
				<td>EAN</td>
				<td>Quantité</td>
				<td>Prix d'achat</td>
				<td>PPI</td>

			</tr>

			<tbody>

				<?php foreach ($infoCde as $key => $cde): ?>
					<?php
					if(!empty($cde['id_palette'])){
						$article=$cde['code_article'];
						$designation=$cde['designation'];
						$ean=$cde['ean'];
						$qte=$cde['quantite'];
						$palette=$arrayListPalette[$cde['id_palette']];
						$tarif=$cde['pa'];
						$ppi=$cde['pvc'];
						$marque="";
					}else{
						$article=$cde['article_occ'];
						$designation=$cde['design_occ'];
						$ean=$cde['ean_occ'];
						$qte=$cde['qte_cde'];
						$palette="";
						$tarif=$cde['panf_occ'];
						$ppi=$cde['ppi_occ'];
						$marque=$cde['marque_occ'];

					}

					?>

					<tr>
						<td><?=$palette?></td>
						<td><?=$article?></td>
						<td><?=$marque?></td>
						<td><?=$designation?></td>
						<td><?=$ean?></td>
						<td class="text-right"><?=$qte?></td>
						<td class="text-right"><?=$tarif?></td>
						<td class="text-right"><?=$ppi?></td>
					</tr>

					<?php
					$totalPa+=$tarif;
					$totalQte+=$qte;
					?>

				<?php endforeach ?>
				<tr class="table-footer">
					<td colspan="5">Totaux : </td>
					<td class="text-right"><?=$totalQte?></td>
					<td class="text-right" ><?=$totalPa?></td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<htmlpagefooter name="myFooter" style="display:none">
			<table class="padding-table"><tr><td class="footer full-width">BTLEC EST - 2 rue des Moissons - Parc d\'activité Witry Caurel - 51420 Witry les Reims</td></tr></table>
			</htmlpagefooter>

		</body>
		</html>