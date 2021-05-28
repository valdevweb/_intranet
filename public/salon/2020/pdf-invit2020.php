<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style type="text/css">
		body{
			font-family: helvetica, sans-serif;
			color:  #4f4f4f;
		}


		.bigger{
			font-size: 20px;
		}
			.normal{
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

		.text-date{
			/* font-style:  italic; */
			font-family: Aegyptus;
			font-size: 28px;

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
			color: #0D47A1;
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
			background-color: #F19E0B;
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
			border: 1px solid #F18E0B;
			border-collapse: collapse;
		}
		.border-table-grey, .border-table-grey td{
			border: 1px solid #343a40;
			border-collapse: collapse;
		}
		h1{
			font-size:30px;
			padding-bottom: 0 !important;
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
		.autre{
			width: 468px;
		}

	</style>

	<title></title>
</head>
<body>
	<table>
		<tr>
			<td class="deux"><img src="../img/salon/bt200.jpg"></td>
			<td class="huit"></td>
			<td class="trois">BTLec Est<BR>
				Parc d'activités Witry Caurel - 2 rue des Moissons<br>
				51420 WITRY LES REIMS
			 </td>
		</tr>
	</table>
	<div class="spacing-l"></div>

	<table>
		<tr>
			<td class="full-width text-center"><h1 class="text-center">Votre invitation</h1></td>
		</tr>
		<tr>
			<td class="full-width text-center"><h1><span class="text-prim text-center"><?=$invit['genre'] .' '.$invit['prenom'].' ' .$invit['nom']?> </span></h1></td>
		</tr>
	</table>
	<div class="spacing-s"></div>

	<table class="">

		<tr>
			<td class="full-width text-center "><img src="../img/salon/salon2020-200.jpg"></td>
		</tr>

		<tr><td>&nbsp;</td></tr>
		<tr>
			<td class="bigger text-center text-prim text-date">
				Mardi 22 septembre 2020 : 9h30 - 17h30 et Mercredi 23 septembre 2020 : 9h00 - 16h30</td>
		</tr>
	</table>
<div class="spacing-l"></div>

	<table>
		<tr>
		<td class="six"></td>
		<td class="autre padding-table border-table text-center bg-sec text-white border-table-sec">
			<p class="normal  "><strong>PROGRAMME 2020 : </strong></p>
		</td>
		<td class="six"></td>
	</tr>
	<tr>
		<td class="six"></td>
		<td class="autre padding-table">
			<p class="normal"><strong>Mardi 22 septembre :</strong><br>
				- 11h30 : Conférence GFK Maison (Petit et Gros Electroménager)<br>
				- 14h30 : Conférence GFK Multimédia (Informatique, TV-Vidéo...) </p>

		</td>
		<td class="six"></td>
	</tr>
	<!-- <tr>
		<td></td>
		<td class="spacing-m"></td>
		<td></td>
	</tr> -->
	<tr>
		<td class="quatre"></td>
		<td class="deux padding-table">

			<p class="normal"><strong>Mercredi 23 septembre :</strong><br>
				- 9h30 : Convention</p>
		</td>
		<td class="quatre"></td>
	</tr>

</table>
<div class="spacing-l"></div>
<table>


	<tr>
		<td class="text-center"><img src="../img/qrcode/<?=$invit['qrcode']?>.jpg"></td>
	</tr>
		<tr>
		<td class=spacing-l></td>
	</tr>
	<tr>
		<td class="normal">Merci de vous munir de cette invitation lors de votre venue. Elle sera à présenter à l'accueil du salon pour obtenir votre bagde</td>
	</tr>
</table>




</body>
</html>




