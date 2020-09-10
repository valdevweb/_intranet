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
		.text-center{
			text-align: center;
		}


		table,td, tr{
			vertical-align: top;
			margin: 0;
			padding : 0;
		}



		.border-table-grey, .border-table-grey td{
			border: 1px solid #343a40;
			border-collapse: collapse;
			margin :0 auto;
			background-color:#ffdfb1;
		}

		.width{
			width: 9cm !important;
			height: 5.4cm !important;
		}
		.no-border, .no-border td{
			border: none ;
		}
		.full-size{
			width: 100%;
		}
		.col-qr{
			width:26%;

		}
		.col-text{
			width:70%;

		}
		.qrcode{
			width: 2.2cm;
			height: auto;
		}

		.text-right{
			text-align: right;
		}
		.text-left{
			text-align: left;
		}
		.text-primary{
			color: #0D47A1;
		}

		.text-secondary{
			color: #f57c00;
		}
		.vbottom{
			vertical-align: bottom;
			padding-bottom: 10px;
		}
		.main-center{
			width: 100%;
			display: block;

		}
		h1{
			margin-top:  50px;
		}

		.intro{
			margin: 50px 0;
			font-size: 18px;
		}
		.smaller-text{
			font-size: 14px;
		}
		.header{
			font-size: 10px !important;
		}

		.border{
			border: 1px solid black;
		}
	</style>

	<title></title>
</head>
<body>
	<table class="header full-size">
		<tr>
			<td><img src="../img/salon/bt200.jpg"></td>

			<td>BTLec Est<BR>
				Parc d'activités Witry Caurel - 2 rue des Moissons<br>
				51420 WITRY LES REIMS
			</td>
		</tr>
	</table>
	<div class="spacing-l"></div>
	<h1 class="text-center text-primary">
		Bonjour,
	</h1>
	<p class="intro">Merci de vous munir du badge ci-dessous sur le salon. A l'accueil du salon, il vous sera demandé de scanner le qrcode afin d'enregistrer votre présence</p>

	<div class="main-center">
		<table class="border-table-grey">
			<tr>
				<td class="width">
					<table class="full-size no-border" cellpadding="0" cellspacing="0">
						<tr>
							<td class="text-right">
								<img src="../img/logo_bt/bt30-transparent.png">
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="full-size text-center text-primary" ><h3><?= $participant['fullname']?></h3><br></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td  class="full-size text-center text-secondary">
								<h3>
									<?php if ($participant['service_name']!="" && $participant['service_name']!='NULL'): ?>
											Service <?=$participant['service_name']?>
											<?php else: ?>
												BTLec Est
									<?php endif ?>

								</h3>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td class="full-size text-center">Salon et convention BTlec Est 2020</td>
						</tr>

					</table>
				</td>
			</tr>
		</table>
	</div>




</body>
</html>




