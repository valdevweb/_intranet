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
	<p class="intro"><b>Aucun bagde ne sera délivré sur le salon, en revanche, nous vous fournirons le support de badge</b></p>
	<p class="intro">Avant d'imprimer, pensez à vérifier vos options d'impression, l'échelle doit être sur "défaut" (ce paramètre se trouve dans les options avancées)</p>
	<div class="main-center">
		<table class="border-table-grey">
			<tr>
				<td class="width">
					<table class="full-size no-border" cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2" class="text-right">
								<img src="../img/logo_bt/bt30-transparent.png">
							</td>
						</tr>
						<tr>
							<td colspan="2" class="full-size text-center text-primary" ><h3><?= MagHelpers::deno($pdoMag,$invit['galec'])?></h3><br></td>
						</tr>
						<tr>
							<td  colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2"  class="full-size text-center text-secondary"><h3><?=$invit['nom'] .' '.$invit['prenom']?></h3></td>
						</tr>
						<tr>
							<td  colspan="2">&nbsp;</td>
						</tr>
				<!-- 		<tr>
							<td  colspan="2">&nbsp;</td>
						</tr> -->
						<tr>
							<td class="text-center col-text vbottom">
								<h3><?=MagHelpers::centraleName($pdoMag, $invit['galec'])?></h3><br>
								<p class="text-center">Salon BTlec Est <?=YEAR_SALON?></p>
							</td>

							<td class="text-right col-qr">
								<img class="qrcode" src="<?=DIR_UPLOAD.'qrcodes\\'.$invit['qrcode']?>">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<p class='header text-center'><img  src="../img/logos/ciseaux-14.jpg"><i>Découpez moi avant votre arrivée au salon, pour gagner du temps </i></p>

	</div>




</body>
</html>




