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
	font-size: 14px;
}
.bigger{
	font-size: 20px;
}

.medium{
	font-size: 12px;

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

<title>Palette</title>
</head>
<body>
	<p class="entete text-right">date d'édition : <?= date('d-m-Y')?></p>

	<table class="padding-table border-table-prim">
		<tr>
			<td class="full-width bg-prim text-white bigger text-center" colspan="4">JUSTIFCATIF DEEE</td>
			<!-- <td class="deux bg-prim text-white bigger" colspan="2"></td> -->
		</tr>

	</table>

	<?php
	$len=count($expInfo);
	$i=1;
	?>
	<?php foreach ($expInfo as $exp): ?>
		<?php 	$paletteInfo=getPaletteInfo($pdoCasse, $exp['paletteid']); ?>
		<div class="spacing-l"></div>

		<table class="padding-table">
			<tr>
				<td class="full-width bigger text-center" colspan="4">PALETTE  <?= $paletteInfo[0]['contremarque']?></td>
				<!-- <td class="deux bg-prim text-white bigger" colspan="2"></td> -->
			</tr>

		</table>



		<!-- <div class="spacing-s"></div> -->
		<table class="padding-table border-table">
			<tr>
				<td class="cinq bg-black text-white heavy">N° CASSE</td>
				<td class="cinq bg-black text-white heavy">ARTICLE</td>
				<td class="cinq bg-black text-white heavy">DESIGNATION</td>
				<td class="cinq bg-black text-white heavy">NB COLIS</td>
				<td class="cinq bg-black text-white heavy">PCB</td>
			</tr>

			<?php foreach ($paletteInfo as $palette): ?>

				<tr>
					<td class="cinq"><?=$palette['idcasse']?></td>
					<td class="cinq"><?=$palette['article']?></td>
					<td class="cinq"><?=$palette['designation']?></td>
					<td class="cinq"><?=$palette['nb_colis']?></td>
					<td class="cinq"><?=$palette['pcb']?></td>
				</tr>
			<?php endforeach ?>

		</table>
		<?php if ($i< $len): ?>
			<div style="page-break-after:always"></div>

			<?php else: ?>
				<div style="page-break-after:avoid"></div>

			<?php endif ?>

			<?php $i++ ?>

		<?php endforeach ?>
		<div class="spacing-l"></div>

		<p>Ces produits ont été détruits par l'intermédiaire d'un organisme agréé</p>
		<table class="padding-table">
			<tr>
				<td class="deux"></td>
				<td class="deux medium" colspan="2">
				Fait le ______________________________ </p>
			</td>
		</tr>

		<tr>
			<td class="deux"></td>
			<td class="deux medium" colspan="2">
				Signature :
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			</td>
		</tr>
				<tr>
			<td class="deux"></td>
			<td class="deux medium" colspan="2">
				<?= $address ?>
			</td>
		</tr>
	</table>

	<p class="text-center">
		<p class="text-center"></p>

	</body>
	</html>




