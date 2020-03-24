
<!DOCTYPE html>
<html lang="fr">
<head>
	<!-- <meta charset="UTF-8"> -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="../css/font.css">
	<link rel="icon" href="http://172.30.92.53/btlecest/favicon.ico" />

	<!-- nouvelle page pour style commun qui remplacera main  05/02/2019 -->
	<link rel="stylesheet" href="../css/commun.css?1583316390">
	<link rel="stylesheet" href="../css/nav.css">
	<link rel="stylesheet" type="text/css" href="../css/footer.css">
	<!-- style propre  -->
	<link rel="stylesheet" href="../css/base-mag.css?1585042344">
	<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap.css">
	<link href="../../vendor/fontawesome5/css/all.css" rel="stylesheet">

	<!--  Scripts-->
	<!-- on charge jquery dès le début pour pouvoir ajouter dynamiquement des scripts qui utilisent jquery -->
	<script src="../../vendor/jquery/jquery-3.2.1.min_ex.js"></script>
	<script src="../../vendor/bootstrap/js/bootstrap.js"></script>
	<script src="../../vendor/igorescobar/jquery-mask-plugin/src/jquery.mask.js"></script>

	<title>Portail BTLec</title>
</head>
<body>


	<div id='cssmenu'>
		<ul>
			<li><a class="less-padding"  href='/_btlecest/public/home/home.php' data-tooltip="Accueil"><span><i class="fa fa-home fa-2x" aria-hidden="true"></i></span></a></li>
			<!-- sous menu 1 -->
			<li class='active has-sub'><a href="/_btlecest/public/btlec/dashboard.php"><span>Demandes magasin</span></a>
				<ul>
					<li><a href="/_btlecest/public/btlec/dashboard.php">En attente</a></li>
					<li> <a href="/_btlecest/public/btlec/histo.php">Clôturées</a></li>
					<li> <a href="/_btlecest/public/btlec/search.php">Histo par magasin</a></li>

				</ul>
			</li>

			<li class='has-sub'><a href="#"><span>Litiges</span></a>
				<ul>
					<li><a href="/_btlecest/public/litiges/declaration-bt-basic.php">Déclarer un litige pour un magasin</a></li>
					<li><a href="/_btlecest/public/litiges/declaration-robbery.php">Déclarer un vol</a></li>
					<li><a href="/_btlecest/public/litiges/bt-litige-encours.php">Litiges en cours</a></li>
					<li><a href="/_btlecest/public/litiges/bt-ouvertures.php">Demandes d'ouverture de dossier</a></li>
					<li><a href="/_btlecest/public/litiges/stat-litige-mag.php">Réclamations par magasin</a></li>
					<li><a href="/_btlecest/public/litiges/exploit-ltg.php">Exploitation</a></li>
					<li><a href="/_btlecest/public/litiges/ctrl-stock.php">Contrôle de stock</a></li>
					<li><a href="/_btlecest/public/litiges/intervention-commission-sav.php">Retour Commission SAV</a></li>

					<li><a href="/_btlecest/public/litiges/intervention-sav.php">Retour SAV</a></li>

					<li><a href="/_btlecest/public/litiges/intervention-achats.php">Retour Service achats</a></li>

					<li><a href="/_btlecest/public/casse/bt-casse-dashboard.php" class="lighter-blue">Traitement casse</a></li>
					<li><a href="/_btlecest/public/casse/histo-casse.php" class="lighter-blue">Historique casse</a></li>

				</ul>
			</li>


			<!-- section sans sous menu -->
			<li><a href="/_btlecest/public/entrepot/discover.php"><span>Entrepôt</span></a></li>

			<li><a href="/_btlecest/public/gazette/gazette.php" >Les gazettes</a></li>
			<li  class='active has-sub'><a href="#" >documents</a>
				<ul>
					<li class='has-sub'><a href="/_btlecest/public/doc/display-doc.php">Achats</a>
						<ul>
							<li><a href="/_btlecest/public/doc/display-doc.php#odr-title">ODR</a></li>
							<li><a href="/_btlecest/public/doc/display-doc.php#tel-title">TEL/BRII</a></li>
							<li><a href="/_btlecest/public/doc/display-doc.php#assortiment-title">Assortiment et panier Promo</a></li>
							<li><a href="/_btlecest/public/doc/display-doc.php#mdd-title">MDD</a></li>
							<li><a href="/_btlecest/public/doc/display-doc.php#gfk-title">GFK</a></li>
						</ul>
					</li>
					<li class='has-sub'><a href="/_btlecest/public/doc/com_menu.php">Communication</a>

						<ul>
							<li><a href="/_btlecest/public/doc/plancom2020.php">Plan de Comm OP BTLec 2020</a></li>
							<li><a href="/_btlecest/public/doc/plancom2019.php">Plan de Comm OP BTLec 2019</a></li>
							<li><a href="/_btlecest/public/doc/kitaffiche.php">Kit affiches OP BTLec</a></li>
							<li><a href='/_btlecest/public/infos/twentyfour.php#plv'>PLV 48h</a></li>					</ul>
						</li>
						<li class='has-sub'><a href="#">Comptabilité</a>
							<ul>
								<li><a href='/_btlecest/public/doc/exploit_rev.php'>Exploit reversements</a></li>						<li><a href="/_btlecest/public/doc/histo_rev.php">Reversements</a></li>

							</ul>
						</li>
						<li><a href="/_btlecest/public/doc/doris.php">Doris</a></li>
						<li><a href="/_btlecest/public/doc/extralec.php">Application Extralec</a></li>
						<li><a href="/_btlecest/public/salon/presentation-salon-2019.php">Convention 2019</a></li>
						<li><a href='/_btlecest/public/doc/upload-main.php'>Ajouter des documents</a></li><li><a href='/_btlecest/public/doc/flash-add.php'>Ajouter une info flash</a></li>

					</ul>
				</li>

				<li  class='active has-sub'><a href="#" >Magasins</a>
					<ul>
						<li><a href="/_btlecest/public/basemag/base-mag.php">Base magasins</a></li>
						<li><a href="/_btlecest/public/basemag/fiche-mag.php"><span>Fiches magasins</span></a></li>
					</ul>
				</li>



				<li class='active has-sub'><a href='/_btlecest/public/exploit/connexion.php' ><span>Exploit</span></a><ul><li><a href='/_btlecest/public/salon/stats-salon-2020.php'><span>Stats Salon 2020</span></a></li><li><a href='/_btlecest/public/salon/stats-salon-2019.php'><span>Stats Salon 2019</span></a></li><li><a href='/_btlecest/public/exploit/connexion.php'><span>Suivi magasins</span></a></li><li><a href='/_btlecest/public/exploit/ld-exploit.php'><span>Listes de diffu BTLec</span></a></li><li><a href='/_btlecest/public/doc/flash-validation.php'><span>Suivi des infos flash</span></a></li><li><a href='/_btlecest/public/exploit/upload-adh.php'><span>Upload documents Adhérents</span></a></li></ul></li><li class='has-sub'><a href='http://172.30.92.53/_conseil/home.php' class='tooltipped' data-position='bottom' data-tooltip='Réservé adhérents / conseil'><span>adhérents & pres</span></a><ul><li><a href='http://172.30.92.53/_conseil/home.php' class='tooltipped' data-position='bottom' data-tooltip='Conseil'><span>Conseil</span></a></li><li><a href='/_btlecest/public/exploit/doc-adh.php' class='tooltipped' data-position='bottom' data-tooltip='documents réservés adhérents'><span>Documents</span></a></li><li><a href='/_btlecest/public/pres/home-pres.php' ><span>Présentations</span></a></li></ul></li><li class='has-sub'><a href='http://172.30.92.53/_cm/cm/index.php' ><span>CHARGES DE MISSION</span></a><ul><li><a href='http://172.30.92.53/_cm/cm/index.php' ><span>Portail CM</span></a></li><li><a href='http://172.30.92.53/_btlecest/public/cm/cm-news.php' ><span>Fil d'actu</span></a></li></ul></li>		<li><a href="http://172.30.92.53/_sav/scapsav/home.php" class="tooltipped" data-position="bottom" data-tooltip="site du portail SAV">Portail SAV</a></li>
				<li><a href="/_btlecest/public/user/profil.php" class="tooltipped" data-position="bottom" data-tooltip="Votre compte"><span><i class="fa fa-user"></i></span></a></li>
				<li><a href="/_btlecest/public/logoff.php" class="tooltipped" data-position="bottom" data-tooltip="se déconnecter"><span><i class="fa fa-power-off"></i></span></a></li>
			</ul>
		</div>

<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue py-3 ">Base magasins</h1>
		</div>
		<div class="col-auto pt-5">
			<form method="post" action="">
				<div class="search justify-content-end">
					<input type="text" id="search_term" placeholder="Déno, ville, panonceau ou code BTLec " name="search_term" autocomplete="off">
					<button type="submit" class="search-button" name="search_form">
						<i class="fa fa-search"></i>
					</button>
					<button name="clear_form" id="clear"><i class="fas fa-times-circle fa-2x pt-1 pl-2 text-main-blue"></i></button>
				</div>
			</form>
			<div id="magList"></div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row mx-3">
		<div class="col">
			<form action="/_btlecest/public/basemag/base-mag.php" method="post">
				<div class="row">
					<div class="col">
						<fieldset class="position-relative">
							<legend><i class="fas fa-filter pr-3"></i> Filtrer par :</legend>
							<!--
										FILTRE PAR CENTRALE
									-->
									<p class="rubrique text-main-blue font-weight-bold">Centrales :</p>
									<div class="form-row">

										<div class="col pl-5">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="210" id="centrale-210" >
												<label for="centrale-210" class="form-check-label">Andorre</label>
											</div>
										</div>

										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="250" id="centrale-250"  >
												<label for="centrale-250" class="form-check-label">Corse</label>
											</div>
										</div>
										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="290" id="centrale-290"  >
												<label for="centrale-290" class="form-check-label">Lcommerce</label>
											</div>
										</div>
										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="20" id="centrale-20"  >
												<label for="centrale-20" class="form-check-label">Lecasud</label>
											</div>
										</div>
									</div>
									<div class="form-row">

										<div class="col pl-5">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="30" id="centrale-30" >
												<label for="centrale-30" class="form-check-label">Scacentre</label>
											</div>
										</div>

										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="50" id="centrale-50"  >
												<label for="centrale-50" class="form-check-label">Scadif</label>
											</div>
										</div>
										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="90" id="centrale-90"  >
												<label for="centrale-90" class="form-check-label">Scapalsace</label>
											</div>
										</div>
										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="100" id="centrale-100"  >
												<label for="centrale-100" class="form-check-label">Scapartois</label>
											</div>
										</div>
									</div>
									<div class="form-row">

										<div class="col pl-5">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="110" id="centrale-110" >
												<label for="centrale-110" class="form-check-label">Scapest</label>
											</div>
										</div>

										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="120" id="centrale-120"  >
												<label for="centrale-120" class="form-check-label">Scapnor</label>
											</div>
										</div>
										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="160" id="centrale-160"  >
												<label for="centrale-160" class="form-check-label">Socamil</label>
											</div>
										</div>
										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="170" id="centrale-170"  >
												<label for="centrale-170" class="form-check-label">Socara</label>
											</div>
										</div>
									</div>
									<!-- fermeture div quand par col 4 -->
									<div class="form-row">
										<div class="col pl-5">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="0" id="centrale-0?>"  >
												<label for="centrale-0" class="form-check-label">Pas de centrale </label>
											</div>
										</div>
										<div class="col">
											<div class="form-check">
												<input type="checkbox" class="form-check-input" name="centraleSelected[]" value="1" id="centrale-1?>"  checked>
												<label for="centrale-1" class="form-check-label">Sans filtre centrale</label>
											</div>
										</div>
										<div class="col"></div>
										<div class="col"></div>
									</div>
									<!--										FILTRE PAR TYPE									-->
									<div class="form-row my-3">
										<div class="col">
											<p class="rubrique text-main-blue font-weight-bold">Type d'établissement :</p>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="typeSelected[]" value="1" checked>
												<label class="form-check-label">magasin</label>
											</div>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="typeSelected[]" value="2" >
												<label class="form-check-label">centrale</label>
											</div>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="typeSelected[]" value="3" >
												<label class="form-check-label">divers</label>
											</div>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="typeSelected[]" value="5" >
												<label class="form-check-label">drive</label>
											</div>
										</div>
										<!--					FILTRE PAR ETAT				-->
										<div class="col">
											<p class="rubrique text-main-blue font-weight-bold">Ouvert/fermé :</p>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="closed[]" value="0" checked>
												<label class="form-check-label">Ouvert</label>
											</div>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="closed[]" value="1" >
												<label class="form-check-label">Fermé</label>
											</div>
										</div>



										<!--					FILTRE PAR CM				-->
										<div class="col">
											<p class="rubrique text-main-blue font-weight-bold">Suivi par :</p>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="cmSelected[]" value="1275" >
												<label class="form-check-label">Cyrille CANAVATE</label>
											</div>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="cmSelected[]" value="1274" >
												<label class="form-check-label">Julien GUEGAN</label>
											</div>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="cmSelected[]" value="1273" >
												<label class="form-check-label">Sébastien FOURNIER</label>
											</div>
											<div class="form-check pl-5">
												<input type="checkbox" class="form-check-input" name="cmSelected[]" value="NULL" >
												<label class="form-check-label">Non suivi</label>
											</div>


										</div>
									</div>
									<div class="form-row">
										<div class="col text-right">
											<button class="btn btn-orange" name="clear_filter">Effacer les filtres</button>
											<button class="btn btn-primary" name="filter">Filtrer</button>

										</div>
									</div>
								</fieldset>
							</div>
						</div>


					</form>

				</div>


			</div>

			<div class="row">
				<div class="col">
					<h5 class="text-main-blue text-center pt-5 pb-3">Nombre de magasins affichés : 408</h5>
					<div class="alert alert-primary">Pour obtenir plus d'information sur un magasin, veuillez cliquer sur son nom</div>
					<table class="table table-sm shadow">
						<thead class="thead-dark">
							<tr>
								<th>Btlec</th>
								<th>Deno</th>
								<th>Galec</th>
								<th>Ville</th>
								<th>Centrale</th>
								<th>Chargé de mission</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>4005</td>
								<td><a href="fiche-mag.php?id=4005">DOMMARTIN DISTRIBUTION</a></td>
								<td>1654</td>
								<td>54206 DOMMARTIN LES TOUL</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4006</td>
								<td><a href="fiche-mag.php?id=4006">METZDIS SAS</a></td>
								<td>0857</td>
								<td>57280 HAUCONCOURT</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4007</td>
								<td><a href="fiche-mag.php?id=4007">SAS SEDAN EXPLOITATION</a></td>
								<td>0208</td>
								<td>08200 SEDAN</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4009</td>
								<td><a href="fiche-mag.php?id=4009">CASTELDIS</a></td>
								<td>0402</td>
								<td>02400 CHATEAU THIERRY</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4010</td>
								<td><a href="fiche-mag.php?id=4010">VANDIS</a></td>
								<td>1254</td>
								<td>54500 VANDOEUVRE LES NANCY</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4011</td>
								<td><a href="fiche-mag.php?id=4011">DISBEAU</a></td>
								<td>0602</td>
								<td>02800 BEAUTOR</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4012</td>
								<td><a href="fiche-mag.php?id=4012">LECLERC NANCY - FROUDIS</a></td>
								<td>1754</td>
								<td>54000 NANCY</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4016</td>
								<td><a href="fiche-mag.php?id=4016">MAREUILDIS SAS</a></td>
								<td>0977</td>
								<td>77100 MAREUIL LES MEAUX</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4017</td>
								<td><a href="fiche-mag.php?id=4017">VAREDIS</a></td>
								<td>0477</td>
								<td>77130 VARENNES SUR SEINE</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4023</td>
								<td><a href="fiche-mag.php?id=4023">SAS VILLERDIS</a></td>
								<td>0502</td>
								<td>02603 VILLERS COTTERETS CEDEX</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4024</td>
								<td><a href="fiche-mag.php?id=4024">ESPACE CULTUREL DENIDIS</a></td>
								<td>6990</td>
								<td>89100 SENS</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4028</td>
								<td><a href="fiche-mag.php?id=4028">SODIBRAG</a></td>
								<td>0252</td>
								<td>52100 SAINT DIZIER</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4029</td>
								<td><a href="fiche-mag.php?id=4029">BARROIDIS</a></td>
								<td>0155</td>
								<td>55001 BAR LE DUC</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4033</td>
								<td><a href="fiche-mag.php?id=4033">SA SOLORMAG</a></td>
								<td>0457</td>
								<td>57100 THIONVILLE</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4035</td>
								<td><a href="fiche-mag.php?id=4035">CONFLANS DISTRIBUTION</a></td>
								<td>1054</td>
								<td>54801 JARNY CEDEX</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4036</td>
								<td><a href="fiche-mag.php?id=4036">VOUDIS</a></td>
								<td>0108</td>
								<td>08400 VOUZIERS</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4038</td>
								<td><a href="fiche-mag.php?id=4038">VIDIS SAS</a></td>
								<td>1051</td>
								<td>51303 Vitry le Francois cedex</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4040</td>
								<td><a href="fiche-mag.php?id=4040">SARREDIS</a></td>
								<td>0657</td>
								<td>57402 SARREBOURG</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4041</td>
								<td><a href="fiche-mag.php?id=4041">SODIAM Exploitation</a></td>
								<td>1795</td>
								<td>95570 MOISSELLLES</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4042</td>
								<td><a href="fiche-mag.php?id=4042">SAS SOLUC</a></td>
								<td>0425</td>
								<td>25800 VALDAHON</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4046</td>
								<td><a href="fiche-mag.php?id=4046">SODIFER</a></td>
								<td>0377</td>
								<td>77260 LA FERTE SOUS JOUARRE</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4047</td>
								<td><a href="fiche-mag.php?id=4047">PIERRY DISTRIBUTION</a></td>
								<td>1251</td>
								<td>51530 PIERRY</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4048</td>
								<td><a href="fiche-mag.php?id=4048">LURE DISTRIBUTION SA</a></td>
								<td>0170</td>
								<td>70200 LURE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4049</td>
								<td><a href="fiche-mag.php?id=4049">BOUCHE DIS</a></td>
								<td>0577</td>
								<td>77120 COULOMMIERS</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4050</td>
								<td><a href="fiche-mag.php?id=4050">SASU DISTRIVESLE</a></td>
								<td>1351</td>
								<td>51140 JONCHERY SUR VESLES</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4053</td>
								<td><a href="fiche-mag.php?id=4053">MAR-DIS</a></td>
								<td>0757</td>
								<td>57155 MARLY</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4057</td>
								<td><a href="fiche-mag.php?id=4057">SODICAMB</a></td>
								<td>0960</td>
								<td>60230 CHAMBLY</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4059</td>
								<td><a href="fiche-mag.php?id=4059">VERDUN DISTRIBUTION</a></td>
								<td>0355</td>
								<td>55100 VERDUN</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4063</td>
								<td><a href="fiche-mag.php?id=4063">SODIROM</a></td>
								<td>0110</td>
								<td>10100 ROMILLY SUR SEINE</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4064</td>
								<td><a href="fiche-mag.php?id=4064">CHAMDIS</a></td>
								<td>0751</td>
								<td>51370 SAINT-BRICE COURCELLES</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4065</td>
								<td><a href="fiche-mag.php?id=4065">SAS LAONDIS</a></td>
								<td>0902</td>
								<td>02000 CHAMBRY LAON</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4068</td>
								<td><a href="fiche-mag.php?id=4068">BBJ VERDUN DISTRIBUTION</a></td>
								<td>9455</td>
								<td>55100 VERDUN</td>
								<td>SCAPEST</td>
								<td></td>
							</tr>
							<tr>
								<td>4069</td>
								<td><a href="fiche-mag.php?id=4069">DENIDIS</a></td>
								<td>0389</td>
								<td>89100 SENS</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4070</td>
								<td><a href="fiche-mag.php?id=4070">PLESSIS DIS</a></td>
								<td>1460</td>
								<td>60330 LE PLESSIS BELLEVILLE</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4071</td>
								<td><a href="fiche-mag.php?id=4071">SAS LEXYDIS</a></td>
								<td>1854</td>
								<td>54720 LEXY</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4072</td>
								<td><a href="fiche-mag.php?id=4072">EPERDIS</a></td>
								<td>0951</td>
								<td>51200 EPERNAY</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4073</td>
								<td><a href="fiche-mag.php?id=4073">SODICHAMPS</a></td>
								<td>0851</td>
								<td>51500 RILLY LA MONTAGNE</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4074</td>
								<td><a href="fiche-mag.php?id=4074">LUNAMA SAS</a></td>
								<td>0954</td>
								<td>54300 LUNEVILLE</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4075</td>
								<td><a href="fiche-mag.php?id=4075">SAS CHADIS</a></td>
								<td>0551</td>
								<td>51510 FAGNIERES</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4076</td>
								<td><a href="fiche-mag.php?id=4076">SODIAM AMNEVILLE</a></td>
								<td>1057</td>
								<td>57360 AMNEVILLE</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4078</td>
								<td><a href="fiche-mag.php?id=4078">SEZADIS</a></td>
								<td>0351</td>
								<td>51120 SEZANNE</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4080</td>
								<td><a href="fiche-mag.php?id=4080">SOCLIDIS</a></td>
								<td>0893</td>
								<td>93390 CLICHY SOUS BOIS</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4082</td>
								<td><a href="fiche-mag.php?id=4082">PARISNORDIS</a></td>
								<td>9993</td>
								<td>93500 PANTIN</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4085</td>
								<td><a href="fiche-mag.php?id=4085">SODIMEAUX</a></td>
								<td>0777</td>
								<td>77100 MEAUX</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4090</td>
								<td><a href="fiche-mag.php?id=4090">CONTOYDIS</a></td>
								<td>1002</td>
								<td>02100 HARLY</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4091</td>
								<td><a href="fiche-mag.php?id=4091">SIPAN</a></td>
								<td>0310</td>
								<td>10410 ST PARRES AUX TERTRES</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4093</td>
								<td><a href="fiche-mag.php?id=4093">FIFAM</a></td>
								<td>0557</td>
								<td>57290 FAMECK</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4094</td>
								<td><a href="fiche-mag.php?id=4094">FROUDIS</a></td>
								<td>1454</td>
								<td>54390 FROUARD</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4095</td>
								<td><a href="fiche-mag.php?id=4095">AUXERRE DISTRIBUTION</a></td>
								<td>0289</td>
								<td>89000 AUXERRE</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4096</td>
								<td><a href="fiche-mag.php?id=4096">DISMI</a></td>
								<td>0189</td>
								<td>89400 MIGENNES</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4098</td>
								<td><a href="fiche-mag.php?id=4098">GREVIN Distribution</a></td>
								<td>0489</td>
								<td>89700 TONNERRE</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4103</td>
								<td><a href="fiche-mag.php?id=4103">CAUFFRIDIS</a></td>
								<td>0560</td>
								<td>60290 CAUFFRY</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4104</td>
								<td><a href="fiche-mag.php?id=4104">PROVINDIS</a></td>
								<td>0877</td>
								<td>77160 PROVINS</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4106</td>
								<td><a href="fiche-mag.php?id=4106">SODICO</a></td>
								<td>0678</td>
								<td>78702 CONFLANS ST HONORINE CEDEX</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4107</td>
								<td><a href="fiche-mag.php?id=4107">BAR DISTRIBUTION</a></td>
								<td>0410</td>
								<td>10200 BAR SUR AUBE</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4109</td>
								<td><a href="fiche-mag.php?id=4109">SERDIS</a></td>
								<td>1560</td>
								<td>95470 FOSSES</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4111</td>
								<td><a href="fiche-mag.php?id=4111">GONESDIS</a></td>
								<td>1895</td>
								<td>95500 GONESSE</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4120</td>
								<td><a href="fiche-mag.php?id=4120">SAINT JUDIST</a></td>
								<td>0360</td>
								<td>60130 SAINT JUST EN CHAUSSEE</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4124</td>
								<td><a href="fiche-mag.php?id=4124">STE TRIDIS</a></td>
								<td>1260</td>
								<td>60590 TRIE CHATEAU</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4125</td>
								<td><a href="fiche-mag.php?id=4125">MAVIDIS</a></td>
								<td>0177</td>
								<td>77270 VILLEPARISIS</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4126</td>
								<td><a href="fiche-mag.php?id=4126">AUBINS SAINT PRIX</a></td>
								<td>1095</td>
								<td>95390 SAINT PRIX</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4130</td>
								<td><a href="fiche-mag.php?id=4130">SODIVALD EXPLOITATION SAS</a></td>
								<td>0295</td>
								<td>95290 L 'ISLE ADAM</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4131</td>
								<td><a href="fiche-mag.php?id=4131">SAS SODIDIER EXPLOITATION</a></td>
								<td>0280</td>
								<td>80500 MONTDIDIER</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4134</td>
								<td><a href="fiche-mag.php?id=4134">SOD ESPACE CULTUREL</a></td>
								<td>1495</td>
								<td>95310 SAINT OUEN L'AUMONE</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4135</td>
								<td><a href="fiche-mag.php?id=4135">SODIPERS EXPANSION</a></td>
								<td>0695</td>
								<td>95340 PERSAN</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4136</td>
								<td><a href="fiche-mag.php?id=4136">GENEDIS SA</a></td>
								<td>1492</td>
								<td>92230 GENNEVILLIERS</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4137</td>
								<td><a href="fiche-mag.php?id=4137">SAS SODIRIB</a></td>
								<td>1760</td>
								<td>60170 RIBECOURT-DRESLINCOURT</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4138</td>
								<td><a href="fiche-mag.php?id=4138">SDRC DEVELOPPEMENT</a></td>
								<td>0260</td>
								<td>60160 MONTATAIRE</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4140</td>
								<td><a href="fiche-mag.php?id=4140">SOLORMAG SAS</a></td>
								<td>3392</td>
								<td>57100 THIONVILLE</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4141</td>
								<td><a href="fiche-mag.php?id=4141">SODIOS EXPLOITATION SAS</a></td>
								<td>0895</td>
								<td>95220 OSNY</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4142</td>
								<td><a href="fiche-mag.php?id=4142">BLANC MESNIL DISTRIBUTION</a></td>
								<td>1593</td>
								<td>93150 LE BLANC MESNIL</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4143</td>
								<td><a href="fiche-mag.php?id=4143">SODHIRS</a></td>
								<td>0802</td>
								<td>02500 HIRSON</td>
								<td>SCAPEST</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4144</td>
								<td><a href="fiche-mag.php?id=4144">BOBIGNY EXPLOITATION</a></td>
								<td>1693</td>
								<td>93000 BOBIGNY</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4145</td>
								<td><a href="fiche-mag.php?id=4145">EPINAY EXPLOITATION</a></td>
								<td>0993</td>
								<td>93800 EPINAY</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4146</td>
								<td><a href="fiche-mag.php?id=4146">CREVECOEUR DIS SAS</a></td>
								<td>2060</td>
								<td>60360 CREVECOEUR LE GRAND</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4147</td>
								<td><a href="fiche-mag.php?id=4147">EXPRESS CAUFFRIDIS</a></td>
								<td>8160</td>
								<td>60140 LIANCOURT</td>
								<td>SCAPNOR</td>
								<td></td>
							</tr>
							<tr>
								<td>4148</td>
								<td><a href="fiche-mag.php?id=4148">LALANDIS</a></td>
								<td>2891</td>
								<td>51200 SAINT DIZIER</td>
								<td>SCAPEST</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4149</td>
								<td><a href="fiche-mag.php?id=4149">SODIGEMA SAS</a></td>
								<td>1893</td>
								<td>93110 ROSNY SOUS BOIS</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4150</td>
								<td><a href="fiche-mag.php?id=4150">SODIMAGG SARL</a></td>
								<td>2195</td>
								<td>95420 MAGNY EN VEXIN</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4151</td>
								<td><a href="fiche-mag.php?id=4151">LACDIS SAS</a></td>
								<td>2160</td>
								<td>60610 LACROIX SAINT OUEN</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4152</td>
								<td><a href="fiche-mag.php?id=4152">SODIMAX SAS</a></td>
								<td>0760</td>
								<td>60700 PONT SAINT MAXENCE</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4153</td>
								<td><a href="fiche-mag.php?id=4153">CERGY EXPLOITATION</a></td>
								<td>2295</td>
								<td>95800 CERGY</td>
								<td>SCAPNOR</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4205</td>
								<td><a href="fiche-mag.php?id=4205">SODICA CARRIERES</a></td>
								<td>0178</td>
								<td>78955 CARRIERES SOUS POISSY</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4206</td>
								<td><a href="fiche-mag.php?id=4206">GEFICAR</a></td>
								<td>0378</td>
								<td>78955 CARRIERES SOUS POISSY</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4208</td>
								<td><a href="fiche-mag.php?id=4208">CLICHY DISTRIBUTION</a></td>
								<td>1892</td>
								<td>92110 CLICHY</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4211</td>
								<td><a href="fiche-mag.php?id=4211">FRANCONDIS</a></td>
								<td>1695</td>
								<td>95130 FRANCONVILLE</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4212</td>
								<td><a href="fiche-mag.php?id=4212">KREMLIN DISTRIBUTION</a></td>
								<td>0994</td>
								<td>94276 LE KREMLIN BICETRE</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4215</td>
								<td><a href="fiche-mag.php?id=4215">MASSY DISTRIBUTION</a></td>
								<td>0791</td>
								<td>91300 MASSY PALAISEAU</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4217</td>
								<td><a href="fiche-mag.php?id=4217">SONODINA NANDIS</a></td>
								<td>0892</td>
								<td>92000 NANTERRE</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4218</td>
								<td><a href="fiche-mag.php?id=4218">ORLY DISTRIBUTION</a></td>
								<td>1194</td>
								<td>94310 ORLY</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4219</td>
								<td><a href="fiche-mag.php?id=4219">PANTIN DISTRIBUTION</a></td>
								<td>1093</td>
								<td>93691 PANTIN CEDEX</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4220</td>
								<td><a href="fiche-mag.php?id=4220">RUMALDIS</a></td>
								<td>1692</td>
								<td>92502 RUEIL MALMAISON CEDEX</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4221</td>
								<td><a href="fiche-mag.php?id=4221">VIRYDIS</a></td>
								<td>0991</td>
								<td>91170 VIRY CHATILLON</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4222</td>
								<td><a href="fiche-mag.php?id=4222">VITRY DISTRIBUTION</a></td>
								<td>0694</td>
								<td>94405 VITRY SUR SEINE CEDEX</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4225</td>
								<td><a href="fiche-mag.php?id=4225">COVADIS</a></td>
								<td>1992</td>
								<td>92701 COLOMBES CEDEX</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4226</td>
								<td><a href="fiche-mag.php?id=4226">MONTGERON DIS</a></td>
								<td>0691</td>
								<td>91230 Montgeron</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4227</td>
								<td><a href="fiche-mag.php?id=4227">SAS FOSDIS</a></td>
								<td>1592</td>
								<td>92700 COLOMBES</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4229</td>
								<td><a href="fiche-mag.php?id=4229">ETAMPES DISTRIBUTION</a></td>
								<td>1391</td>
								<td>91150 ETAMPES</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4231</td>
								<td><a href="fiche-mag.php?id=4231">LEVALLOIS DISTRIBUTION</a></td>
								<td>0992</td>
								<td>92300 LEVALLOIS PERRET</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4232</td>
								<td><a href="fiche-mag.php?id=4232">DIS-PONTAULT</a></td>
								<td>1177</td>
								<td>77340 PONTAULT COMBAULT</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4233</td>
								<td><a href="fiche-mag.php?id=4233">CHATELET DIS</a></td>
								<td>1277</td>
								<td>77820 LE CHATELET EN BRIE</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4234</td>
								<td><a href="fiche-mag.php?id=4234">SAS CHAMPIMARNE</a></td>
								<td>0594</td>
								<td>94500 CHAMPIGNY</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4235</td>
								<td><a href="fiche-mag.php?id=4235">ACHERES EXPANSION</a></td>
								<td>0778</td>
								<td>78260 ACHERES</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4236</td>
								<td><a href="fiche-mag.php?id=4236">SONODINA NANDIS</a></td>
								<td>0001</td>
								<td>92000 NANTERRE</td>
								<td>SCADIF</td>
								<td></td>
							</tr>
							<tr>
								<td>4237</td>
								<td><a href="fiche-mag.php?id=4237">ARCYCOM</a></td>
								<td>1278</td>
								<td>78390 BOIS D'ARCY CEDEX</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4238</td>
								<td><a href="fiche-mag.php?id=4238">BONNEUIL EXPLOITATION SAS</a></td>
								<td>1294</td>
								<td>94868 BONNEUIL SUR MARNE</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4239</td>
								<td><a href="fiche-mag.php?id=4239">VALEDOR SAS</a></td>
								<td>1378</td>
								<td>78120 RAMBOUILLET</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4240</td>
								<td><a href="fiche-mag.php?id=4240">FLEURYDIS</a></td>
								<td>1491</td>
								<td>91700 FLEURY MEROGIS</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4241</td>
								<td><a href="fiche-mag.php?id=4241">MONTEDIS</a></td>
								<td>1377</td>
								<td>77144 MONTEVRAIN</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4242</td>
								<td><a href="fiche-mag.php?id=4242">OZAGORA SA</a></td>
								<td>1477</td>
								<td>77330 OZOIR LA FERRIERE</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4243</td>
								<td><a href="fiche-mag.php?id=4243">HOUDIS</a></td>
								<td>1478</td>
								<td>78800 HOUILLES</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4244</td>
								<td><a href="fiche-mag.php?id=4244">PARIS XIX</a></td>
								<td>1275</td>
								<td>75019 PARIS</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4245</td>
								<td><a href="fiche-mag.php?id=4245">DAM'DIS</a></td>
								<td>0677</td>
								<td>77190 DAMMARIE LES LYS</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4246</td>
								<td><a href="fiche-mag.php?id=4246">SAS VINTHAN</a></td>
								<td>1591</td>
								<td>91400 GOMETZ LA VILLE</td>
								<td>SCADIF</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4301</td>
								<td><a href="fiche-mag.php?id=4301">ALDIS SA</a></td>
								<td>0868</td>
								<td>68130 ALTKIRCH</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4305</td>
								<td><a href="fiche-mag.php?id=4305">BRUYERES DISTRIBUTION</a></td>
								<td>0288</td>
								<td>88600 BRUYERES</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4306</td>
								<td><a href="fiche-mag.php?id=4306">SODICER</a></td>
								<td>0268</td>
								<td>68703 CERNAY CEDEX</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4307</td>
								<td><a href="fiche-mag.php?id=4307">CHARDIS SA</a></td>
								<td>0588</td>
								<td>88130 CHARMES</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4308</td>
								<td><a href="fiche-mag.php?id=4308">CHAUMONDIS</a></td>
								<td>0352</td>
								<td>52000 CHAUMONT</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4309</td>
								<td><a href="fiche-mag.php?id=4309">SOCODIS</a></td>
								<td>0568</td>
								<td>68000 COLMAR</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4310</td>
								<td><a href="fiche-mag.php?id=4310">CONTREXEDIS</a></td>
								<td>0188</td>
								<td>88140 CONTREXVILLE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4311</td>
								<td><a href="fiche-mag.php?id=4311">PONTDIS SAS</a></td>
								<td>0225</td>
								<td>25300 HOUTAUD</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4312</td>
								<td><a href="fiche-mag.php?id=4312">APOLIDIS</a></td>
								<td>0321</td>
								<td>21000 DIJON</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4313</td>
								<td><a href="fiche-mag.php?id=4313">SODECCO</a></td>
								<td>0567</td>
								<td>67151 ERSTEIN CEDEX</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4317</td>
								<td><a href="fiche-mag.php?id=4317">ALBISSER SA</a></td>
								<td>0968</td>
								<td>68560 HIRSINGUE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4318</td>
								<td><a href="fiche-mag.php?id=4318">ISSEDIS</a></td>
								<td>0668</td>
								<td>68500 ISSENHEIM (GUEBWILLER)</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4319</td>
								<td><a href="fiche-mag.php?id=4319">SOLADI</a></td>
								<td>0152</td>
								<td>52200 LANGRES</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4321</td>
								<td><a href="fiche-mag.php?id=4321">SAS STRADIS</a></td>
								<td>0867</td>
								<td>67100 STRASBOURG</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4325</td>
								<td><a href="fiche-mag.php?id=4325">MUDIS</a></td>
								<td>0368</td>
								<td>68100 MULHOUSE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4326</td>
								<td><a href="fiche-mag.php?id=4326">NEOCADIS</a></td>
								<td>0688</td>
								<td>88300 NEUFCHATEAU</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4327</td>
								<td><a href="fiche-mag.php?id=4327">NOIDIS</a></td>
								<td>0270</td>
								<td>70000 PUSEY</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4329</td>
								<td><a href="fiche-mag.php?id=4329">RAON DISTRIBUTION</a></td>
								<td>0888</td>
								<td>88110 RAON L'ETAPE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4330</td>
								<td><a href="fiche-mag.php?id=4330">RIBODIS</a></td>
								<td>0468</td>
								<td>68150 RIBEAUVILLE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4331</td>
								<td><a href="fiche-mag.php?id=4331">DIEDIS SAS</a></td>
								<td>0488</td>
								<td>88100 SAINT DIE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4334</td>
								<td><a href="fiche-mag.php?id=4334">ALSEDIS</a></td>
								<td>0367</td>
								<td>67600 SELESTAT</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4335</td>
								<td><a href="fiche-mag.php?id=4335">SODIREM</a></td>
								<td>0788</td>
								<td>88200 ST ETIENNE LES REMIREMONT</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4336</td>
								<td><a href="fiche-mag.php?id=4336">XXX SODISAR XXX</a></td>
								<td>9817</td>
								<td>67260 SARRE-UNION</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4340</td>
								<td><a href="fiche-mag.php?id=4340">ALCOBA DISTRIBUTION</a></td>
								<td>1168</td>
								<td>68300 SAINT-LOUIS</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4341</td>
								<td><a href="fiche-mag.php?id=4341">GEDIS</a></td>
								<td>1067</td>
								<td>67118 GEISPOLSHEIM</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4342</td>
								<td><a href="fiche-mag.php?id=4342">SOMARDIS</a></td>
								<td>1167</td>
								<td>67440 MARMOUTIER</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4344</td>
								<td><a href="fiche-mag.php?id=4344">OBERDIS</a></td>
								<td>1267</td>
								<td>67210 OBERNAI</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4345</td>
								<td><a href="fiche-mag.php?id=4345">SCHILDIS</a></td>
								<td>1467</td>
								<td>67300 SCHILTIGHEIM</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4346</td>
								<td><a href="fiche-mag.php?id=4346">SOUFFLECO</a></td>
								<td>1367</td>
								<td>67620 SOUFFLENHEIM</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4348</td>
								<td><a href="fiche-mag.php?id=4348">BLOTZDIS</a></td>
								<td>1068</td>
								<td>68730 BLOTZHEIM</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4349</td>
								<td><a href="fiche-mag.php?id=4349">SELCODIS</a></td>
								<td>0967</td>
								<td>67600 SELESTAT</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4350</td>
								<td><a href="fiche-mag.php?id=4350">SODIKING</a></td>
								<td>1268</td>
								<td>68260 KINGERSHEIM</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4351</td>
								<td><a href="fiche-mag.php?id=4351">SAS HERDIS</a></td>
								<td>0370</td>
								<td>70400 HERICOURT</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4352</td>
								<td><a href="fiche-mag.php?id=4352">XXX BELFI SAS XXX</a></td>
								<td>9414</td>
								<td>90008 BELFORT CEDEX</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4353</td>
								<td><a href="fiche-mag.php?id=4353">MASDIS</a></td>
								<td>6896</td>
								<td>68290 MASEVAUX</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4354</td>
								<td><a href="fiche-mag.php?id=4354">XXX ILLKIRDIS XXX</a></td>
								<td>9920</td>
								<td>67230 BENFELD</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4358</td>
								<td><a href="fiche-mag.php?id=4358">XXX HILSEDIS XXX</a></td>
								<td>9598</td>
								<td>67230 BENFELD</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4359</td>
								<td><a href="fiche-mag.php?id=4359">PHALSDIS</a></td>
								<td>5796</td>
								<td>57370 PHALSBOURG</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4363</td>
								<td><a href="fiche-mag.php?id=4363">SOULTZDIS</a></td>
								<td>1567</td>
								<td>67250 SOULTZ-SOUS-FORET</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4366</td>
								<td><a href="fiche-mag.php?id=4366">HERRLIDIS</a></td>
								<td>6777</td>
								<td>67850 HERRLISHEIM</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4368</td>
								<td><a href="fiche-mag.php?id=4368">DUTTLEDIS</a></td>
								<td>6791</td>
								<td>67120 DUTTLENHEIM</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4372</td>
								<td><a href="fiche-mag.php?id=4372">NEUDIS</a></td>
								<td>6783</td>
								<td>67100 STRASBOURG</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4375</td>
								<td><a href="fiche-mag.php?id=4375">CRIDIS</a></td>
								<td>0139</td>
								<td>39300 CHAMPAGNOLE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4376</td>
								<td><a href="fiche-mag.php?id=4376">WASSDIS SAS</a></td>
								<td>1368</td>
								<td>67310 WASSELONNE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4377</td>
								<td><a href="fiche-mag.php?id=4377">AUXODIS SAS</a></td>
								<td>0521</td>
								<td>21130 AUXONNE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4378</td>
								<td><a href="fiche-mag.php?id=4378">GOLBEY DISTRIBUTION-GOLDIS</a></td>
								<td>0388</td>
								<td>88192 GOLBEY CEDEX</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4379</td>
								<td><a href="fiche-mag.php?id=4379">SAS MONTBEDIS</a></td>
								<td>0325</td>
								<td>25211 MONTBELIARD</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4380</td>
								<td><a href="fiche-mag.php?id=4380">ALSEDIS</a></td>
								<td>9848</td>
								<td>67600 SELESTAT</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4382</td>
								<td><a href="fiche-mag.php?id=4382">BDIS</a></td>
								<td>6774</td>
								<td>67240 BISCHWILLER</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4383</td>
								<td><a href="fiche-mag.php?id=4383">HDIS</a></td>
								<td>6769</td>
								<td>67500 HAGUENAU</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4384</td>
								<td><a href="fiche-mag.php?id=4384">SODIMARS SAS</a></td>
								<td>0221</td>
								<td>21160 MARSANNAY LA COTE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4385</td>
								<td><a href="fiche-mag.php?id=4385">FREYDIS EXPLOITATION</a></td>
								<td>0957</td>
								<td>57804 FREYMING MERLE BACH</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4386</td>
								<td><a href="fiche-mag.php?id=4386">SAS ANSOL</a></td>
								<td>0667</td>
								<td>67260 SARRE-UNION</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4387</td>
								<td><a href="fiche-mag.php?id=4387">HILSEDIS EXPLOITATION</a></td>
								<td>6772</td>
								<td>67600 HILSENHEIM</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4388</td>
								<td><a href="fiche-mag.php?id=4388">SCHILDIS-LEX HOCHEFELDEN</a></td>
								<td>6776</td>
								<td>67270 HOCHFELDEN</td>
								<td>SCAPALSACE</td>
								<td></td>
							</tr>
							<tr>
								<td>4389</td>
								<td><a href="fiche-mag.php?id=4389">SODECCO CULTUREL</a></td>
								<td>6722</td>
								<td>67150 ERSTEIN</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4390</td>
								<td><a href="fiche-mag.php?id=4390">BELDIS SAS</a></td>
								<td>0190</td>
								<td>90008 BELFORT CEDEX</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4391</td>
								<td><a href="fiche-mag.php?id=4391">CROIXDIS LG</a></td>
								<td>0357</td>
								<td>57150 CREUTZWALD</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4392</td>
								<td><a href="fiche-mag.php?id=4392">DOLDIS</a></td>
								<td>0239</td>
								<td>39100 DOLE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4393</td>
								<td><a href="fiche-mag.php?id=4393">BEAUNE DISTRIBUTION</a></td>
								<td>0421</td>
								<td>21200 BEAUNE</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4394</td>
								<td><a href="fiche-mag.php?id=4394">WINTZEDIS</a></td>
								<td>1468</td>
								<td>68124 WINTZHEIM-LOGELBACH</td>
								<td>SCAPALSACE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4404</td>
								<td><a href="fiche-mag.php?id=4404">BELLEROCHE DISTRIBUTION</a></td>
								<td>1969</td>
								<td>69400 VILLEFRANCHE SUR SAONE</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4405</td>
								<td><a href="fiche-mag.php?id=4405">BOURG DISTRIB</a></td>
								<td>0826</td>
								<td>26503 BOURG LES VALENCE</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4406</td>
								<td><a href="fiche-mag.php?id=4406">BOURGOIN DISTRIBUTION</a></td>
								<td>0838</td>
								<td>38300 BOURGOIN JAILLEU</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4408</td>
								<td><a href="fiche-mag.php?id=4408">CHALONDIS</a></td>
								<td>0471</td>
								<td>71106 CHALON SUR SAONE</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4409</td>
								<td><a href="fiche-mag.php?id=4409">CHAMBEDIS</a></td>
								<td>0273</td>
								<td>73000 CHAMBERY</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4410</td>
								<td><a href="fiche-mag.php?id=4410">ST CHAMOND DISTRIBUTION</a></td>
								<td>0542</td>
								<td>42403 SAINT-CHAMOND CEDEX</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4411</td>
								<td><a href="fiche-mag.php?id=4411">CIVRIDIS</a></td>
								<td>2169</td>
								<td>69380 CIVRIEUX D'AZERGUES</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4413</td>
								<td><a href="fiche-mag.php?id=4413">DRUMEDIS</a></td>
								<td>0373</td>
								<td>73420 VIVIERS DU LAC</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4414</td>
								<td><a href="fiche-mag.php?id=4414">ECHIROLLES DISTRIBUTION</a></td>
								<td>0638</td>
								<td>38130 ECHIROLLES</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4415</td>
								<td><a href="fiche-mag.php?id=4415">FIRMINY DISTRIBUTION</a></td>
								<td>0642</td>
								<td>42700 FIRMINY</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4416</td>
								<td><a href="fiche-mag.php?id=4416">GAILLOT DIS</a></td>
								<td>2069</td>
								<td>69800 SAINT PRIEST</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4417</td>
								<td><a href="fiche-mag.php?id=4417">GREZDIS</a></td>
								<td>2269</td>
								<td>69290 CRAPONNE</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4418</td>
								<td><a href="fiche-mag.php?id=4418">ISERE DISTRIBUTION</a></td>
								<td>0138</td>
								<td>38160 CHATTE</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4420</td>
								<td><a href="fiche-mag.php?id=4420">MEYZIEU DIS</a></td>
								<td>1269</td>
								<td>69883 MEYZIEU CEDEX</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4423</td>
								<td><a href="fiche-mag.php?id=4423">ROADIS</a></td>
								<td>0342</td>
								<td>42153 RIORGES</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4424</td>
								<td><a href="fiche-mag.php?id=4424">ROUDAUT SA</a></td>
								<td>0526</td>
								<td>26750 SAINT PAUL LES ROMANS</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4425</td>
								<td><a href="fiche-mag.php?id=4425">SODICRAN</a></td>
								<td>0174</td>
								<td>74960 CRAN GEVRIER</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4426</td>
								<td><a href="fiche-mag.php?id=4426">SODIVAL</a></td>
								<td>1284</td>
								<td>84600 VALREAS</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4429</td>
								<td><a href="fiche-mag.php?id=4429">VALDIS</a></td>
								<td>0926</td>
								<td>26007 VALENCE CEDEX</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4430</td>
								<td><a href="fiche-mag.php?id=4430">VAUX DISTRIBUTION</a></td>
								<td>0671</td>
								<td>71500 LOUHANS</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4431</td>
								<td><a href="fiche-mag.php?id=4431">VIENNEDIS</a></td>
								<td>0938</td>
								<td>38200 VIENNE</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4432</td>
								<td><a href="fiche-mag.php?id=4432">VILLE LA DIS</a></td>
								<td>0274</td>
								<td>74100 VILLE LA GRAND (ANNEMASSE)</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4434</td>
								<td><a href="fiche-mag.php?id=4434">SAINT MARTIN DISTRIBUTION</a></td>
								<td>0738</td>
								<td>38400 SAINT MARTIN D'HERES</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4436</td>
								<td><a href="fiche-mag.php?id=4436">LYON DIS</a></td>
								<td>1769</td>
								<td>69256 Lyon cedex 09</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4437</td>
								<td><a href="fiche-mag.php?id=4437">TIGNIEUDIS</a></td>
								<td>0438</td>
								<td>38230 TIGNIEU JAMEYZIEU</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4438</td>
								<td><a href="fiche-mag.php?id=4438">FERNEYDIS</a></td>
								<td>0301</td>
								<td>01210 Ferney Voltaire</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4439</td>
								<td><a href="fiche-mag.php?id=4439">CLAIRIDIS</a></td>
								<td>1138</td>
								<td>38370 SAINT CLAIR DU RHONE</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4440</td>
								<td><a href="fiche-mag.php?id=4440">HOLDIS SAS</a></td>
								<td>0101</td>
								<td>01700 BEYNOST</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4441</td>
								<td><a href="fiche-mag.php?id=4441">SAUGERAIES DISTRIB. SAS</a></td>
								<td>0271</td>
								<td>71000 MACON</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4442</td>
								<td><a href="fiche-mag.php?id=4442">SODALI SA</a></td>
								<td>0742</td>
								<td>42410 CHAVANAY</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4443</td>
								<td><a href="fiche-mag.php?id=4443">SAS SODIFER</a></td>
								<td>0374</td>
								<td>74140 SCIEZ sur Leman</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4444</td>
								<td><a href="fiche-mag.php?id=4444">SODICHAP</a></td>
								<td>2569</td>
								<td>69970 CHAPONNAY</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4445</td>
								<td><a href="fiche-mag.php?id=4445">JARDIS SAS</a></td>
								<td>0442</td>
								<td>42270 ST PRIEST EN JAREZ</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4446</td>
								<td><a href="fiche-mag.php?id=4446">SODIVAL ESPACE CULTUREL</a></td>
								<td>5084</td>
								<td>84600 VALREAS</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4447</td>
								<td><a href="fiche-mag.php?id=4447">SAS SODIRE</a></td>
								<td>2669</td>
								<td>69830 SAINT GEORGES DE RENEINS</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4450</td>
								<td><a href="fiche-mag.php?id=4450">LUXDIS SAS</a></td>
								<td>1171</td>
								<td>71100 LUX</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4452</td>
								<td><a href="fiche-mag.php?id=4452">BELLERIVEDIS</a></td>
								<td>0303</td>
								<td>03700 BELLERIVE SUR ALLIER</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4453</td>
								<td><a href="fiche-mag.php?id=4453">BOURGES DIS</a></td>
								<td>0318</td>
								<td>18000 BOURGES</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4454</td>
								<td><a href="fiche-mag.php?id=4454">CLAMECY DISTRIBUTION</a></td>
								<td>0258</td>
								<td>58500 CLAMECY</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4455</td>
								<td><a href="fiche-mag.php?id=4455">CLERDIS</a></td>
								<td>0463</td>
								<td>63100 CLERMONT FERRAND</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4456</td>
								<td><a href="fiche-mag.php?id=4456">SODICLER</a></td>
								<td>0163</td>
								<td>63100 CLERMONT FERRAND</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4457</td>
								<td><a href="fiche-mag.php?id=4457">NEVERS DISTRIBUTION</a></td>
								<td>0158</td>
								<td>58640 COULANGES LES NEVERS</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4459</td>
								<td><a href="fiche-mag.php?id=4459">DECIZE DIS. S.A.</a></td>
								<td>0358</td>
								<td>58300 DECIZE</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4460</td>
								<td><a href="fiche-mag.php?id=4460">DIGOIN DISTRIBUTION</a></td>
								<td>1071</td>
								<td>71160 DIGOIN</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4462</td>
								<td><a href="fiche-mag.php?id=4462">SODIMONT</a></td>
								<td>0871</td>
								<td>71300 MONTCEAU LES MINES</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4463</td>
								<td><a href="fiche-mag.php?id=4463">MARAIS DISTRIBUTION</a></td>
								<td>0403</td>
								<td>03100 MONTLUCON</td>
								<td>SCACENTRE</td>
								<td></td>
							</tr>
							<tr>
								<td>4464</td>
								<td><a href="fiche-mag.php?id=4464">CHATEAUGAY DISTRIBUTION</a></td>
								<td>0103</td>
								<td>03410 DOMERAT</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4466</td>
								<td><a href="fiche-mag.php?id=4466">SOFIPAR</a></td>
								<td>0571</td>
								<td>71602 PARAY LE MONIAL</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4467</td>
								<td><a href="fiche-mag.php?id=4467">SAINT LOUP DISTRIBUTION</a></td>
								<td>2369</td>
								<td>69490 PONTCHARRA SUR TURDINE</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4470</td>
								<td><a href="fiche-mag.php?id=4470">VIERZON DISTRIBUTION</a></td>
								<td>0118</td>
								<td>18103 VIERZON</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4471</td>
								<td><a href="fiche-mag.php?id=4471">ENVALDIS</a></td>
								<td>0363</td>
								<td>63530 VOLVIC</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4472</td>
								<td><a href="fiche-mag.php?id=4472">SOTUNDIS SAS</a></td>
								<td>0971</td>
								<td>71405 AUTUN CEDEX</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4473</td>
								<td><a href="fiche-mag.php?id=4473">DISTHIERS</a></td>
								<td>0563</td>
								<td>63300 THIERS</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4474</td>
								<td><a href="fiche-mag.php?id=4474">SAMDIS</a></td>
								<td>0518</td>
								<td>18200 ST AMAND MONTROND</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4475</td>
								<td><a href="fiche-mag.php?id=4475">ESPACE CULTUREL VIERZON</a></td>
								<td>EC38</td>
								<td>18103 VIERZON</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4476</td>
								<td><a href="fiche-mag.php?id=4476">LE BREUIL INVEST</a></td>
								<td>0371</td>
								<td>71670 LE BREUIL</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4477</td>
								<td><a href="fiche-mag.php?id=4477">EC MOULINS</a></td>
								<td>EC30</td>
								<td>03000 MOULINS</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4478</td>
								<td><a href="fiche-mag.php?id=4478">SAINT RAMBERT DIS SAS</a></td>
								<td>1126</td>
								<td>26140 SAINT RAMBERT D'ALBON</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4479</td>
								<td><a href="fiche-mag.php?id=4479">SAS AVERMES DISTRIBUTION</a></td>
								<td>0503</td>
								<td>03000 AVERMES</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4480</td>
								<td><a href="fiche-mag.php?id=4480">ESPACE CULTUREL LUXDIS</a></td>
								<td>9864</td>
								<td>71100 LUX</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4481</td>
								<td><a href="fiche-mag.php?id=4481">EXPRESS MONDIS</a></td>
								<td>LE01</td>
								<td>03100 MONTLUCON</td>
								<td>SCACENTRE</td>
								<td></td>
							</tr>
							<tr>
								<td>4482</td>
								<td><a href="fiche-mag.php?id=4482">NEUDIS SAS</a></td>
								<td>1669</td>
								<td>69730 GENAY</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4483</td>
								<td><a href="fiche-mag.php?id=4483">FLOURDIS</a></td>
								<td>0215</td>
								<td>15100 SAINT GEORGE</td>
								<td>SCACENTRE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4484</td>
								<td><a href="fiche-mag.php?id=4484">ESPACE TECHNOLOGIE AVERMES</a></td>
								<td>ET30</td>
								<td>03000 AVERMES</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4485</td>
								<td><a href="fiche-mag.php?id=4485">AIME DISTRIBUTION</a></td>
								<td>0473</td>
								<td>73210 AIME LA PLAGNE</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4486</td>
								<td><a href="fiche-mag.php?id=4486">ESP TECH SODICHAP</a></td>
								<td>9839</td>
								<td>69970 CHAPONNAY</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4487</td>
								<td><a href="fiche-mag.php?id=4487">ST DOULCHARD DISTRIBUTION</a></td>
								<td>0618</td>
								<td>18230 SAINT DOULCHARD</td>
								<td>SCACENTRE</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4488</td>
								<td><a href="fiche-mag.php?id=4488">ESPACE CULTUREL SAINT LOUP</a></td>
								<td>9857</td>
								<td>69170 TARARE</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4489</td>
								<td><a href="fiche-mag.php?id=4489">BRESSE DIS 2</a></td>
								<td>9702</td>
								<td>01000 BOURG EN BRESSE</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4490</td>
								<td><a href="fiche-mag.php?id=4490">BRESSE DIS 2 (EC)</a></td>
								<td>0201</td>
								<td>01000 BOURG EN BRESSE</td>
								<td>SOCARA</td>
								<td>Sébastien FOURNIER</td>
							</tr>
							<tr>
								<td>4491</td>
								<td><a href="fiche-mag.php?id=4491">IN AND OUT</a></td>
								<td>9942</td>
								<td>42160 ANDREZIEUX-BOUTHEON</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4500</td>
								<td><a href="fiche-mag.php?id=4500">Magasin test</a></td>
								<td>0500</td>
								<td>44150 nantes</td>
								<td>SCAPEST</td>
								<td></td>
							</tr>
							<tr>
								<td>4501</td>
								<td><a href="fiche-mag.php?id=4501">ITALIE 1</a></td>
								<td>0197</td>
								<td>ITALIE ITALIE</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>4502</td>
								<td><a href="fiche-mag.php?id=4502">ITALIE 2</a></td>
								<td>0297</td>
								<td>ITALIE ITALIE</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>4503</td>
								<td><a href="fiche-mag.php?id=4503">ITALIE 3</a></td>
								<td>0397</td>
								<td>ITALIE ITALIE</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>4504</td>
								<td><a href="fiche-mag.php?id=4504">ITALIE 4</a></td>
								<td>0497</td>
								<td>ITALIE ITALIE</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>4601</td>
								<td><a href="fiche-mag.php?id=4601">GAILLAC DISTRIBUTION</a></td>
								<td>0381</td>
								<td>81600 GAILLAC</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4602</td>
								<td><a href="fiche-mag.php?id=4602">SODEXCO</a></td>
								<td>1331</td>
								<td>31800 ESTANCARBON</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4603</td>
								<td><a href="fiche-mag.php?id=4603">AUCH HYPER-DISTRIBUTION</a></td>
								<td>0432</td>
								<td>32000 AUCH</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4604</td>
								<td><a href="fiche-mag.php?id=4604">HYPER SAINT AUNES</a></td>
								<td>0934</td>
								<td>34130 SAINT AUNES</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4605</td>
								<td><a href="fiche-mag.php?id=4605">SODIGAR</a></td>
								<td>1031</td>
								<td>31120 ROQUES SUR GARONNE</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4606</td>
								<td><a href="fiche-mag.php?id=4606">SODILANG</a></td>
								<td>0311</td>
								<td>11106 NARBONNE CEDEX</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4607</td>
								<td><a href="fiche-mag.php?id=4607">ARIEDIS</a></td>
								<td>0109</td>
								<td>09100 St JEAN DU FALGA</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4608</td>
								<td><a href="fiche-mag.php?id=4608">LIMOUX DISTRIBUTION</a></td>
								<td>0111</td>
								<td>11300 LIMOUX</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4609</td>
								<td><a href="fiche-mag.php?id=4609">CAHORS PRADIS</a></td>
								<td>0246</td>
								<td>46090 CAHORS PRADINES</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4610</td>
								<td><a href="fiche-mag.php?id=4610">SODIART</a></td>
								<td>0282</td>
								<td>82100 CASTELSARRASIN</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4611</td>
								<td><a href="fiche-mag.php?id=4611">DEVEDIS</a></td>
								<td>0334</td>
								<td>34534 BEZIERS</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4612</td>
								<td><a href="fiche-mag.php?id=4612">SOCAPDIS</a></td>
								<td>0346</td>
								<td>46100 CAPDENAC LE HAUT</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4613</td>
								<td><a href="fiche-mag.php?id=4613">SODITECH</a></td>
								<td>0366</td>
								<td>66162 LE BOULOU CEDEX</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4614</td>
								<td><a href="fiche-mag.php?id=4614">GRAULHET DISTRIBUTION</a></td>
								<td>0481</td>
								<td>81300 GRAULHET</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4616</td>
								<td><a href="fiche-mag.php?id=4616">SODIMAZ</a></td>
								<td>0581</td>
								<td>81660 BOUT DU PONT DE L'ARN</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4617</td>
								<td><a href="fiche-mag.php?id=4617">XXX VILDI PARTICIPATION XX</a></td>
								<td>9597</td>
								<td>31340 VILLEMUR SUR TARN</td>
								<td>SOCAMIL</td>
								<td></td>
							</tr>
							<tr>
								<td>4618</td>
								<td><a href="fiche-mag.php?id=4618">SODICAT</a></td>
								<td>0166</td>
								<td>66845 PERPIGNAN CEDEX</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4619</td>
								<td><a href="fiche-mag.php?id=4619">LAVIDA</a></td>
								<td>0181</td>
								<td>81000 ALBI</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4620</td>
								<td><a href="fiche-mag.php?id=4620">SODIBAG</a></td>
								<td>0182</td>
								<td>82000 MONTAUBAN</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4621</td>
								<td><a href="fiche-mag.php?id=4621">TPLM</a></td>
								<td>0211</td>
								<td>11000 CARCASSONNE</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4622</td>
								<td><a href="fiche-mag.php?id=4622">SEBADIS</a></td>
								<td>0212</td>
								<td>12850 ONET LE CHATEAU</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4623</td>
								<td><a href="fiche-mag.php?id=4623">SADAM</a></td>
								<td>0281</td>
								<td>81380 LESCURE D'ALBIGEOIS</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4624</td>
								<td><a href="fiche-mag.php?id=4624">AUDIS</a></td>
								<td>0382</td>
								<td>82000 MONTAUBAN</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4625</td>
								<td><a href="fiche-mag.php?id=4625">ROUFFIAC DISTRIBUTION</a></td>
								<td>1531</td>
								<td>31180 ROUFFIAC</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4626</td>
								<td><a href="fiche-mag.php?id=4626">VERNET DISTRIBUTION</a></td>
								<td>0266</td>
								<td>66962 PERPIGNAN CEDEX 9</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4627</td>
								<td><a href="fiche-mag.php?id=4627">SODIREV</a></td>
								<td>1231</td>
								<td>31650 St ORENS DE GAMEVILLE</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4628</td>
								<td><a href="fiche-mag.php?id=4628">NOBLADIS</a></td>
								<td>1431</td>
								<td>31715 BLAGNAC CEDEX</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4629</td>
								<td><a href="fiche-mag.php?id=4629">AURILLAC DISTRIBUTION</a></td>
								<td>0115</td>
								<td>15000 AURILLAC</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4630</td>
								<td><a href="fiche-mag.php?id=4630">FUXEDIS</a></td>
								<td>0209</td>
								<td>09000 FOIX</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4631</td>
								<td><a href="fiche-mag.php?id=4631">HABILOIS</a></td>
								<td>0312</td>
								<td>12200 VILLEFRANCHE DE ROUERGUE</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4632</td>
								<td><a href="fiche-mag.php?id=4632">SARL VILLEGOUDOU DISTRIBUT</a></td>
								<td>9959</td>
								<td>81100 CASTRES</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4633</td>
								<td><a href="fiche-mag.php?id=4633">MACRIS SAS</a></td>
								<td>0512</td>
								<td>12100 CREISSELS</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4634</td>
								<td><a href="fiche-mag.php?id=4634">HIPER ANDORRA</a></td>
								<td>01AD</td>
								<td>AD500 Andorra la Vella</td>
								<td>ANDORRE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4635</td>
								<td><a href="fiche-mag.php?id=4635">PUNT DE TROBADA</a></td>
								<td>02AD</td>
								<td>AD600 Sant Julia De Loria</td>
								<td>ANDORRE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4636</td>
								<td><a href="fiche-mag.php?id=4636">SAS LECADIS</a></td>
								<td>0781</td>
								<td>81100 CASTRES</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4637</td>
								<td><a href="fiche-mag.php?id=4637">LEVANDIS</a></td>
								<td>0534</td>
								<td>34403 LUNEL</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4638</td>
								<td><a href="fiche-mag.php?id=4638">SODIPI</a></td>
								<td>1234</td>
								<td>34120 PEZENAS</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4639</td>
								<td><a href="fiche-mag.php?id=4639">ESPACE CULTUREL CAHORS</a></td>
								<td>9910</td>
								<td>46000 CAHORS</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4640</td>
								<td><a href="fiche-mag.php?id=4640">SALAGOUDIS SAS</a></td>
								<td>1134</td>
								<td>34700 LE BOSC-LODEVE</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4641</td>
								<td><a href="fiche-mag.php?id=4641">SAS DISTRILYS</a></td>
								<td>1631</td>
								<td>31740 SAINT-LYS</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4642</td>
								<td><a href="fiche-mag.php?id=4642">SAS VILLEMUR DISTRIBUTION</a></td>
								<td>0731</td>
								<td>31340 VILLEMUR SUR TARN</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4643</td>
								<td><a href="fiche-mag.php?id=4643">XXX CONCEPT MEUBLE GAILLAC</a></td>
								<td>8581</td>
								<td>81600 GAILLAC</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4644</td>
								<td><a href="fiche-mag.php?id=4644">CASTRESDIS</a></td>
								<td>0881</td>
								<td>81100 CASTRES</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4645</td>
								<td><a href="fiche-mag.php?id=4645">CASTELNAUDIS</a></td>
								<td>0511</td>
								<td>11400 CASTELNAUDARY</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4646</td>
								<td><a href="fiche-mag.php?id=4646">OXYDIS</a></td>
								<td>0411</td>
								<td>11000 CARCASSONNE</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4647</td>
								<td><a href="fiche-mag.php?id=4647">SAS MARANDIS</a></td>
								<td>1334</td>
								<td>34500 BEZIERS</td>
								<td>SOCAMIL</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4702</td>
								<td><a href="fiche-mag.php?id=4702">SAS BRIGNOLDIS</a></td>
								<td>1983</td>
								<td>83170 BRIGNOLES</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4704</td>
								<td><a href="fiche-mag.php?id=4704">KERZIOU</a></td>
								<td>1713</td>
								<td>13127 VITROLLES</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4705</td>
								<td><a href="fiche-mag.php?id=4705">ANGLEDIS</a></td>
								<td>0530</td>
								<td>30133 LES ANGLES</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4706</td>
								<td><a href="fiche-mag.php?id=4706">BOLDIS</a></td>
								<td>0584</td>
								<td>84507 BOLLENE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4707</td>
								<td><a href="fiche-mag.php?id=4707">SAS SALONDIS</a></td>
								<td>1613</td>
								<td>13300 SALON DE PROVENCE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4708</td>
								<td><a href="fiche-mag.php?id=4708">SEYDIS</a></td>
								<td>1183</td>
								<td>83500 LA SEYNE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4710</td>
								<td><a href="fiche-mag.php?id=4710">SODISTRES SA</a></td>
								<td>2513</td>
								<td>13800 ISTRES</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4715</td>
								<td><a href="fiche-mag.php?id=4715">SAS SODIALPES</a></td>
								<td>0104</td>
								<td>04100 MANOSQUE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4716</td>
								<td><a href="fiche-mag.php?id=4716">SAS SUDALP II</a></td>
								<td>0105</td>
								<td>05010 GAP</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4719</td>
								<td><a href="fiche-mag.php?id=4719">SODICA II</a></td>
								<td>0306</td>
								<td>06110 LE CANNET</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4721</td>
								<td><a href="fiche-mag.php?id=4721">ROMANDIS</a></td>
								<td>1026</td>
								<td>26200 MONTELIMAR</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4722</td>
								<td><a href="fiche-mag.php?id=4722">KERROC</a></td>
								<td>1006</td>
								<td>06359 NICE CEDEX 4</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4725</td>
								<td><a href="fiche-mag.php?id=4725">MARIDIS</a></td>
								<td>2113</td>
								<td>13700 MARIGNANE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4727</td>
								<td><a href="fiche-mag.php?id=4727">SODISAPT</a></td>
								<td>0484</td>
								<td>84400 APT</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4729</td>
								<td><a href="fiche-mag.php?id=4729">AVIROC</a></td>
								<td>0384</td>
								<td>84000 AVIGNON</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4732</td>
								<td><a href="fiche-mag.php?id=4732">SOGARDIS</a></td>
								<td>0130</td>
								<td>30100 ALES</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4734</td>
								<td><a href="fiche-mag.php?id=4734">SAS TOULONDIS 1</a></td>
								<td>2183</td>
								<td>83100 TOULON</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4735</td>
								<td><a href="fiche-mag.php?id=4735">SAS KAMELIA</a></td>
								<td>1506</td>
								<td>06110 LE CANNET</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4739</td>
								<td><a href="fiche-mag.php?id=4739">VALESCURE DISTRIBUTION</a></td>
								<td>2283</td>
								<td>83700 SAINT RAPHAEL</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4741</td>
								<td><a href="fiche-mag.php?id=4741">SOSUMAR SAS</a></td>
								<td>0107</td>
								<td>07200 ST ETIENNE DE FONTBELLON</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4742</td>
								<td><a href="fiche-mag.php?id=4742">AUZON</a></td>
								<td>0284</td>
								<td>84976 CARPENTRAS CEDEX</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4743</td>
								<td><a href="fiche-mag.php?id=4743">AUREDIS</a></td>
								<td>0906</td>
								<td>06480 LA COLLE SUR LOUP</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4746</td>
								<td><a href="fiche-mag.php?id=4746">NEMODIS</a></td>
								<td>0230</td>
								<td>30000 NIMES</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4747</td>
								<td><a href="fiche-mag.php?id=4747">SAS HYPER GRASSE</a></td>
								<td>1606</td>
								<td>06130 GRASSE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4748</td>
								<td><a href="fiche-mag.php?id=4748">ROYDIS</a></td>
								<td>0313</td>
								<td>13009 MARSEILLE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4749</td>
								<td><a href="fiche-mag.php?id=4749">SAS SODESUP II</a></td>
								<td>0706</td>
								<td>06140 VENCE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4751</td>
								<td><a href="fiche-mag.php?id=4751">SA JEAN FORCONI</a></td>
								<td>2089</td>
								<td>20137 PORTO VECCHIO</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4752</td>
								<td><a href="fiche-mag.php?id=4752">SA U COTTONE</a></td>
								<td>2090</td>
								<td>20240 GHISONACCIA</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4753</td>
								<td><a href="fiche-mag.php?id=4753">SDA</a></td>
								<td>2091</td>
								<td>20270 ALERIA</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4754</td>
								<td><a href="fiche-mag.php?id=4754">SE SORBARA SANTINI</a></td>
								<td>2092</td>
								<td>20290 BORGO</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4756</td>
								<td><a href="fiche-mag.php?id=4756">LIBRE SERVICE DU PHARE</a></td>
								<td>2094</td>
								<td>20230 ALISTRO</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4757</td>
								<td><a href="fiche-mag.php?id=4757">ACQUAVIVA DISTRIBUTION</a></td>
								<td>2095</td>
								<td>20220 ILE ROUSSE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4759</td>
								<td><a href="fiche-mag.php?id=4759">HYPCO</a></td>
								<td>2097</td>
								<td>20000 AJACCIO IMPERIAL</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4760</td>
								<td><a href="fiche-mag.php?id=4760">ROCADE DISTRIBUTION</a></td>
								<td>2098</td>
								<td>20000 AJACCIO ROCADE</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4761</td>
								<td><a href="fiche-mag.php?id=4761">SODILUC SAS</a></td>
								<td>1683</td>
								<td>83340 LE LUC</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4765</td>
								<td><a href="fiche-mag.php?id=4765">HYERDIS</a></td>
								<td>2083</td>
								<td>83400 HYERES</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4767</td>
								<td><a href="fiche-mag.php?id=4767">SOCODAG 2 SAS</a></td>
								<td>2383</td>
								<td>83310 COGOLIN</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4768</td>
								<td><a href="fiche-mag.php?id=4768">SAS ARLESDIS</a></td>
								<td>1813</td>
								<td>13200 ARLES</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4769</td>
								<td><a href="fiche-mag.php?id=4769">RANDIS</a></td>
								<td>1806</td>
								<td>06150 CANNES LA BOCA</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4770</td>
								<td><a href="fiche-mag.php?id=4770">SODIME</a></td>
								<td>2613</td>
								<td>13650 MEYRARGUEs</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4771</td>
								<td><a href="fiche-mag.php?id=4771">SAS ASJP OLETTA</a></td>
								<td>0120</td>
								<td>20232 OLETTA</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4773</td>
								<td><a href="fiche-mag.php?id=4773">SADAJUP SAS</a></td>
								<td>1784</td>
								<td>84310 MORIERES LES AVIGNONS</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4774</td>
								<td><a href="fiche-mag.php?id=4774">SAS NIKAIADIS</a></td>
								<td>0806</td>
								<td>06201 NICE CEDEX 3</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4775</td>
								<td><a href="fiche-mag.php?id=4775">MONTAUDIS SAS</a></td>
								<td>1583</td>
								<td>83340 FAYENCE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4776</td>
								<td><a href="fiche-mag.php?id=4776">SAULDIS SAS</a></td>
								<td>1226</td>
								<td>26270 SAULCE SUR RHONE</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4778</td>
								<td><a href="fiche-mag.php?id=4778">BRIANCONDIS SAS</a></td>
								<td>0205</td>
								<td>05100 BRIANCON</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4779</td>
								<td><a href="fiche-mag.php?id=4779">LUNADIS</a></td>
								<td>1906</td>
								<td>06220 VALLAURIS</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4780</td>
								<td><a href="fiche-mag.php?id=4780">BASTIA DISCOUNT SA</a></td>
								<td>2087</td>
								<td>20600 BASTIA</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4781</td>
								<td><a href="fiche-mag.php?id=4781">SODEX</a></td>
								<td>2086</td>
								<td>20213 PENTA DI CASINCA</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4782</td>
								<td><a href="fiche-mag.php?id=4782">BALEODIS</a></td>
								<td>0220</td>
								<td>20167 SARROLA-CARCOPINO</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4786</td>
								<td><a href="fiche-mag.php?id=4786">SODIPLAN</a></td>
								<td>2213</td>
								<td>13480 CABRIES</td>
								<td>LECASUD</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4787</td>
								<td><a href="fiche-mag.php?id=4787">SA DU FANGO</a></td>
								<td>2093</td>
								<td>20600 BASTIA</td>
								<td>CORSE</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4800</td>
								<td><a href="fiche-mag.php?id=4800">PONT DISTRIBUTION</a></td>
								<td>1238</td>
								<td>38480 PONT DE BEAUVOISIN</td>
								<td>SOCARA</td>
								<td>Cyrille CANAVATE</td>
							</tr>
							<tr>
								<td>4850</td>
								<td><a href="fiche-mag.php?id=4850">S.D.HAZEBROUCK</a></td>
								<td>9847</td>
								<td>59190 HAZEBROUCK</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4901</td>
								<td><a href="fiche-mag.php?id=4901">SAS DUNDIS</a></td>
								<td>1959</td>
								<td>59140 DUNKERQUE</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4902</td>
								<td><a href="fiche-mag.php?id=4902">SAS SODIDOUAI</a></td>
								<td>0359</td>
								<td>59501 DOUAI CEDEX</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4903</td>
								<td><a href="fiche-mag.php?id=4903">BUGNIDIS</a></td>
								<td>2759</td>
								<td>59151 BUGNICOURT</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4904</td>
								<td><a href="fiche-mag.php?id=4904">ROUBAIX DIS</a></td>
								<td>2859</td>
								<td>59100 ROUBAIX</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4918</td>
								<td><a href="fiche-mag.php?id=4918">LCOMMERCE</a></td>
								<td>0100</td>
								<td>94200 IVRY-SUR-SEINE</td>
								<td>LCOMMERCE</td>
								<td></td>
							</tr>
							<tr>
								<td>4919</td>
								<td><a href="fiche-mag.php?id=4919">BTLEC-Pdts bloques ventes</a></td>
								<td>0999</td>
								<td>51420 WITRY LES REIMS</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>4922</td>
								<td><a href="fiche-mag.php?id=4922">NICOLADIS</a></td>
								<td>0762</td>
								<td>62223 SAINT LAURENT BLANGY</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4923</td>
								<td><a href="fiche-mag.php?id=4923">LIANOUDIS SA</a></td>
								<td>0662</td>
								<td>62230 OUTREAU</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4924</td>
								<td><a href="fiche-mag.php?id=4924">DETA DISTRIBUTION</a></td>
								<td>1459</td>
								<td>59135 BELLAING</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4925</td>
								<td><a href="fiche-mag.php?id=4925">SAS RIVERY EXPLOITATION</a></td>
								<td>0480</td>
								<td>80136 RIVERY LES AMIENS</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4926</td>
								<td><a href="fiche-mag.php?id=4926">ROSENDAEL DISTRIBUTION</a></td>
								<td>0159</td>
								<td>59240 ROSENDAEL</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4931</td>
								<td><a href="fiche-mag.php?id=4931">SAS ETADIS</a></td>
								<td>1076</td>
								<td>76620 ETALONDES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4932</td>
								<td><a href="fiche-mag.php?id=4932">DIEPPE DIS</a></td>
								<td>0876</td>
								<td>76205 DIEPPE</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4933</td>
								<td><a href="fiche-mag.php?id=4933">SAS LILLE SUD DIS</a></td>
								<td>2659</td>
								<td>59000 LILLE</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4937</td>
								<td><a href="fiche-mag.php?id=4937">ARRADIS</a></td>
								<td>0162</td>
								<td>62000 ARRAS</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4938</td>
								<td><a href="fiche-mag.php?id=4938">SALOUEL DISTRIBUTION</a></td>
								<td>0380</td>
								<td>80480 SALEUX</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4941</td>
								<td><a href="fiche-mag.php?id=4941">SDN</a></td>
								<td>0262</td>
								<td>62290 NOEUX LES MINES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4942</td>
								<td><a href="fiche-mag.php?id=4942">CARVIDIS</a></td>
								<td>0362</td>
								<td>62220 CARVIN</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4943</td>
								<td><a href="fiche-mag.php?id=4943">DISTRIFIVES</a></td>
								<td>1259</td>
								<td>59800 LILLE FIVES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4944</td>
								<td><a href="fiche-mag.php?id=4944">SODILOISON</a></td>
								<td>0462</td>
								<td>62218 LOISON SOUS LENS</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4947</td>
								<td><a href="fiche-mag.php?id=4947">ATTINDIS</a></td>
								<td>0562</td>
								<td>62170 ATTIN</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4948</td>
								<td><a href="fiche-mag.php?id=4948">CAUDIS EXPLOITATION</a></td>
								<td>0659</td>
								<td>59541 CAUDRY CEDEX</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4952</td>
								<td><a href="fiche-mag.php?id=4952">TEMPLEUVE DISTRIBUTION</a></td>
								<td>0859</td>
								<td>59242 TEMPLEUVE</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4954</td>
								<td><a href="fiche-mag.php?id=4954">WATTRELOS DISTRIBUTION</a></td>
								<td>0959</td>
								<td>59150 WATTRELOS</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4956</td>
								<td><a href="fiche-mag.php?id=4956">DAINVILDIS</a></td>
								<td>0862</td>
								<td>62000 ARRAS</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4957</td>
								<td><a href="fiche-mag.php?id=4957">AMANDIS</a></td>
								<td>1059</td>
								<td>59730 SAINT AMAND LES EAUX</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4958</td>
								<td><a href="fiche-mag.php?id=4958">FLANDREDIS</a></td>
								<td>1159</td>
								<td>59270 BAILLEUL</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4961</td>
								<td><a href="fiche-mag.php?id=4961">S.D.S.M. Exploitation</a></td>
								<td>0676</td>
								<td>76270 Neufchatel en Bray</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4962</td>
								<td><a href="fiche-mag.php?id=4962">VERMELLES DISTRIBUTION</a></td>
								<td>1262</td>
								<td>62980 VERMELLES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4963</td>
								<td><a href="fiche-mag.php?id=4963">SAS SECLINDIS</a></td>
								<td>1759</td>
								<td>59113 SECLIN</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4964</td>
								<td><a href="fiche-mag.php?id=4964">EXPANSION SAV</a></td>
								<td>4964</td>
								<td> -</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>4965</td>
								<td><a href="fiche-mag.php?id=4965">SAS COURCELDIS</a></td>
								<td>1362</td>
								<td>62970 COURCELLE LES LENS</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4966</td>
								<td><a href="fiche-mag.php?id=4966">SAS OLIBE</a></td>
								<td>1859</td>
								<td>59320 Hallennes-lez-Haubourdin</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4967</td>
								<td><a href="fiche-mag.php?id=4967">VILMURIER</a></td>
								<td>0580</td>
								<td>80400 MUILLE VILETTE</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4968</td>
								<td><a href="fiche-mag.php?id=4968">SA DENGI</a></td>
								<td>1562</td>
								<td>62143 ANGRES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4969</td>
								<td><a href="fiche-mag.php?id=4969">SAS LASSIDIS</a></td>
								<td>1860</td>
								<td>60310 Lassigny</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4970</td>
								<td><a href="fiche-mag.php?id=4970">SAS VIOLAINEDIS</a></td>
								<td>1462</td>
								<td>62138 VIOLAINES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4971</td>
								<td><a href="fiche-mag.php?id=4971">LURESSE SA</a></td>
								<td>2159</td>
								<td>59380 QUAEDYPRE</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4972</td>
								<td><a href="fiche-mag.php?id=4972">SODIBREUIL</a></td>
								<td>1960</td>
								<td>60120 BRETEUIL</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4973</td>
								<td><a href="fiche-mag.php?id=4973">ESPACE CULTUREL ETALONDES</a></td>
								<td>EC03</td>
								<td>76260 EU</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4974</td>
								<td><a href="fiche-mag.php?id=4974">AULNOYDIS</a></td>
								<td>2259</td>
								<td>59620 AULNOYE</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4975</td>
								<td><a href="fiche-mag.php?id=4975">SAS CORELISE</a></td>
								<td>0680</td>
								<td>80200 PERONNE</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4976</td>
								<td><a href="fiche-mag.php?id=4976">LECLERC ETAPLES</a></td>
								<td>1662</td>
								<td>62630 ETAPLES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4978</td>
								<td><a href="fiche-mag.php?id=4978">ORAUDIS</a></td>
								<td>2459</td>
								<td>59310 ORCHIES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4979</td>
								<td><a href="fiche-mag.php?id=4979">AIRE DISTRIBUTION</a></td>
								<td>1762</td>
								<td>62120 AIRE SUR LA LYS</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4980</td>
								<td><a href="fiche-mag.php?id=4980">HERLIN DISTRIBUTION</a></td>
								<td>1862</td>
								<td>62130 HERLIN LE SEC</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4981</td>
								<td><a href="fiche-mag.php?id=4981">DISTRAL EXPLOITATION</a></td>
								<td>0962</td>
								<td>62380 LUMBRES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4982</td>
								<td><a href="fiche-mag.php?id=4982">SAS SODIPONT</a></td>
								<td>0780</td>
								<td>80580 PONT REMY</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4983</td>
								<td><a href="fiche-mag.php?id=4983">SAS VALDIS</a></td>
								<td>2559</td>
								<td>59300 VALENCIENNES</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4984</td>
								<td><a href="fiche-mag.php?id=4984">SAS SAMER DISTRIBUTION</a></td>
								<td>1962</td>
								<td>62830 SAMER</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4985</td>
								<td><a href="fiche-mag.php?id=4985">CALAIS DIS</a></td>
								<td>2062</td>
								<td>62100 CALAIS</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4986</td>
								<td><a href="fiche-mag.php?id=4986">SAS BAPAUME DIS</a></td>
								<td>2162</td>
								<td>62450 BAPAUME</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4987</td>
								<td><a href="fiche-mag.php?id=4987">SAS SDH</a></td>
								<td>0559</td>
								<td>59190 HAZEBROUCK</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4988</td>
								<td><a href="fiche-mag.php?id=4988">SAS DOULLENDIS</a></td>
								<td>0880</td>
								<td>80600 DOULLENS</td>
								<td>SCAPARTOIS</td>
								<td>Julien GUEGAN</td>
							</tr>
							<tr>
								<td>4996</td>
								<td><a href="fiche-mag.php?id=4996">INIT SCACENTRE ABACO</a></td>
								<td>9830</td>
								<td>31100 TOULOUSE</td>
								<td>SCADIF</td>
								<td></td>
							</tr>
							<tr>
								<td>4998</td>
								<td><a href="fiche-mag.php?id=4998">XXX LE BREUIL XXX</a></td>
								<td>0097</td>
								<td>71670 LE BREUIL</td>
								<td>SCADIF</td>
								<td></td>
							</tr>
							<tr>
								<td>4999</td>
								<td><a href="fiche-mag.php?id=4999">SAS SODIMAX IMPLANTATION</a></td>
								<td>1896</td>
								<td>60700 PONT STE MAXENCE</td>
								<td>SCADIF</td>
								<td></td>
							</tr>


						</tbody>
					</table>

				</div>
			</div>





			<!-- ./container -->
		</div>
		<script type="text/javascript">
			// 		$(document).ready(function(){
			// 			$('#search_term').keyup(function(){
			// 				var path = window.location.pathname;
			// 	// var page = path.split("/").pop();
			// 	var page = 'fiche-mag.php';

			// 	var query = $(this).val()+"#"+page;
			// 	if(query != '')
			// 	{
			// 		$.ajax({
			// 			url:"ajax-search-mag.php",
			// 			method:"POST",
			// 			data:{query:query},
			// 			success:function(data)
			// 			{
			// 				$('#magList').fadeIn();
			// 				$('#magList').html(data);
			// 			}
			// 		});
			// 	}
			// });
			// 			$(document).on('click', 'li', function(){
			// 				$('#search_term').val($(this).text());
			// 				$('#magList').fadeOut();
			// 			});

			// 		});


		</script>
		<!-- footer -->


		<footer class="footer">
			<div class="container-fluid dark-blue-bg">
				<div class="row">
					<div class="col">

						<h5>BTLEC EST</h5>
						<p>2 rue des Moissons - Parc d'activité Witry Caurel</p>
						<p>51420 Witry les Reims</p>
					</div>

					<div class="col">
						<h5 class="white-text">Nous contacter</h5>
						<p><i class="fa fa-phone" aria-hidden="true"></i>&nbsp; &nbsp;&nbsp; 03 26 89 86 88<br></p>
						<p><i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp; &nbsp;&nbsp;<a class="link-white" href="#">Envoyer un mail à BTlec</a>


						</div>

						<div class="col">
							<h5 class="white-text">Plus d'infos</h5>
							<p>
								<i class="fa fa-globe" aria-hidden="true"></i>&nbsp; &nbsp;<a class="link-white" href="../mag/google-map.php">Venir à BTlec</a>
							</p>
							<p class="logo-footer"> <img src="../img/footer/eleclercblue.jpg"></p>

						</div>
					</div>

				</div>
			</footer>
			<script type="text/javascript">
				$(document).ready(function(){

					function checkSession(){

						$.ajax({
							url:"../../config/checksession.php",
							method:"POST",
							success:function(data){
								if(data==1){
									alert("Votre session a expirée, vous allez être déconnecté");
									window.location.href='../../index.php';
								}
								else{
            // console.log(data);
        }
    }
});
					}

					setInterval(function(){
						checkSession();
					},10000);

				});

			</script>


		</body>
		</html>