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
	h1{
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
	.myfooter {
		position: absolute;
		overflow: visible;
		left: 0;
		bottom: 40mm;
		right: 0;
		width: 210mm;
		margin-top: auto;
		margin-bottom: auto;
		margin-left: 50px;
		margin-right: auto;
	}

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




