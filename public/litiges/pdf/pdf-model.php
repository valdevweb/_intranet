<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
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

</style>
</style>
<title></title>
</head>
<body>
	<table>
		<!-- en-tête -->
		<tr>
			<td>
				<h2>Bordereau de rétrocession magasin</h2>
				<p>(à coller sur le(s) produit(s))</p>
			</td>
			<td width="200px"></td>
			<td class="text-right"><img src="../img/logo/sav_sans_tel_200.png"></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
			<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<!-- infos -->
		<tr>
			<td colspan="3" class="text-center"><h1></h1></td>
		</tr>
		<tr>
			<td colspan="3" class="text-center"><h2></h2></td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" class="text-center">Demandeur : </td>
		</tr>

		<tr>
			<td></td>
			<td></td>
			<td class="text-right">date : </td>
		</tr>
		<!-- titre -->
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>


		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
	</table>

	<table class="border-table">
		<tr>
			<th>Chargement</th>
			<th>Type produit</th>
			<th>Gencod </th>
			<th>Qte</th>
			<th>Valeur</th>
			<th>Poids (kg)</th>
			<th>Conditionnement</th>

		</tr>
		<?php

			echo '<tr><td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td></tr>';

		?>
	</table>
	<div class="myfooter">
		<!-- <p>SIgnature expéditeur<span class="text-right">SIgnature chauffeur</span></p> -->
		<table class="border-table">
			<tr >
				<td colspan="4" width="700px" class="text-center">
					<p><strong>Etat du produit :</strong></p>
					<br>
					<p>
						<input type="checkbox">&nbsp;Emballé&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="checkbox">&nbsp;Remballé&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="checkbox">&nbsp;Endommagé (non pris)
			</p>

				</td>
			</tr>


				<tr >
				<td colspan="4" width="700px">
				<p><b>Commentaires : </b></p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
			</td>
			</tr>
		</table>
				<p>&nbsp;</p>
				<p>&nbsp;</p>

		<table>
			<tr>
				<td>Date : </td>
				<td width="400px"></td>
				<td>Date : </td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td width="400px"></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Heure :</td>
				<td width="400px"></td>
				<td>Heure :</td>
			</tr>
			<tr>
				<td>Signature expéditeur</td>
				<td width="400px"></td>
				<td class="text-right">Signature chauffeur</td>
			</tr>
		</table>

	</div>

</body>
</html>




