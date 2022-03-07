<?php
function isUserAllowed($pdoUser, $params)
{
	$session = $_SESSION['id'];
	$placeholders = implode(',', array_fill(0, count($params), '?'));
	$req = $pdoUser->prepare("SELECT id_user FROM attributions WHERE id_droit IN($placeholders) AND id_user=$session");
	$req->execute($params);
	$datas = $req->fetchAll(PDO::FETCH_ASSOC);
	if (empty($datas)) {
		return false;
	}
	return true;
}

function getListServiceContact($pdoUser)
{
	$req = $pdoUser->query("SELECT * FROM services WHERE  mask_contact= 0 Order by service");
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

$listServiceContact = getListServiceContact($pdoUser);

$dBtlec = isUserAllowed($pdoUser, [4]);
$dMag = isUserAllowed($pdoUser, [2]);
$dBaseMag = isUserAllowed($pdoUser, [95]);
$dLitigeBt = isUserAllowed($pdoUser, [69]);
$dExploit = isUserAllowed($pdoUser, [5]);
$dEvo = isUserAllowed($pdoUser, [87]);
$dConseil = isUserAllowed($pdoUser, [9, 10, 27]);
$dLcommerce = isUserAllowed($pdoUser, [43, 44]);
$dMission = isUserAllowed($pdoUser,  [78, 5]);
$dOccasionBt = isUserAllowed($pdoUser, [83]);
$dOccasionMag = isUserAllowed($pdoUser, [84]);
$dPilotage = isUserAllowed($pdoUser, [98]);
$dWorkflow = isUserAllowed($pdoUser, [100]);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-main">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarToggler">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
			<!-- CONTENU NAV-->
			<?php if ($_SESSION['id'] == 1531) : ?>

				<li class="nav-item">
					<a class="nav-link" href="<?= ROOT_PATH . '/public/gtocc/offre-produit.php' ?>">Offres produits</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?= ROOT_PATH ?>/public/logoff.php"><i class="fa fa-power-off"></i></a>
				</li>
			<?php else : ?>
				<!-- home -->
				<li class="nav-item">
					<a class="nav-link" href='<?= ROOT_PATH ?>/public/home/home.php'><i class="fa fa-home fa-2x" aria-hidden="true"></i></a>
				</li>
				<!-- contact + litiges + occas  magasin -->
				<?php if ($dMag) : ?>
					<li class="nav-item">
						<a class="nav-link" href="<?= ROOT_PATH ?>/public/mag/histo-mag.php">Vos demandes</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-contact-mag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Contacter nos services</a>
						<ul class="dropdown-menu" aria-labelledby="nav-contact-mag">
							<?php foreach ($listServiceContact as $key => $serviceNav) : ?>
								<li>
									<a class="dropdown-item" href="<?= ROOT_PATH ?>/public/mag/contact.php?id=<?= $serviceNav['id'] ?> "><?= $serviceNav['service'] ?></a>
								</li>
							<?php endforeach ?>
						</ul>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-litige-mag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Litiges</a>
						<ul class="dropdown-menu" aria-labelledby="nav-litige-mag">
							<li>
								<a class="dropdown-item" href="<?= ROOT_PATH ?>/public/litiges/declaration-stepone.php">Déclaration de litige</a>
							</li>
							<li>
								<a class="dropdown-item" href="<?= ROOT_PATH ?>/public/litiges/mag-litige-listing.php">Mes litiges</a>
							</li>
						</ul>
					</li>
					<?php if ($dOccasionMag) : ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="nav-occasion-mag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Leclerc Occasion</a>
							<ul class="dropdown-menu" aria-labelledby="nav-occasion-mag">
								<li>
									<a class="dropdown-item" href="<?= ROOT_PATH ?>/public/gtocc/offre-produit.php">Offres produits</a>
								</li>
								<li>
									<a class="dropdown-item" href="<?= ROOT_PATH ?>/public/gtocc/occ-mag-cdes.php">Vos Commandes</a>
								</li>
								<li>
									<a class="dropdown-item" href="<?= ROOT_PATH ?>/public/gtocc/occ-news.php">Informations Leclerc occasion</a>
								</li>
							</ul>
						</li>
					<?php endif ?>
				<?php endif ?>
				<!-- fin contact + litige + occas magasin -->
				<!-- bt : demandes mag -->
				<?php if (isset($_SESSION['type']) && $_SESSION['type'] == "btlec") : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-contact-bt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Demandes magasin</a>
						<ul class="dropdown-menu" aria-labelledby="nav-contact-bt">
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/btlec/dashboard.php">En cours</a></li>
							<li> <a class="dropdown-item" href="<?= ROOT_PATH ?>/public/btlec/histo.php">Clôturées</a></li>
							<li> <a class="dropdown-item" href="<?= ROOT_PATH ?>/public/btlec/search.php">Histo par magasin</a></li>
						</ul>
					</li>
				<?php endif ?>
				<!-- bt occasion -->
				<?php if ($dOccasionBt) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-occasion-bt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Leclerc occasion</a>
						<ul class="dropdown-menu" aria-labelledby="nav-occasion-bt">
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/gtocc/offre-produit.php">Offres produits</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/gtocc/occ-exploit.php">Gestion GT Occasion</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/gtocc/palettes-dispo.php">Palettes à traiter</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/gtocc/occ-expedie.php">Palettes expédiées</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/gtocc/occ-editinfo.php">Exploit infos mag GT13</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/gtocc/occ-news.php">Informations Leclerc occasion</a></li>
						</ul>
					</li>
				<?php endif ?>
				<!-- bt litiges -->
				<?php if ($dBtlec || $dLitigeBt) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-litige-bt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Litiges</a>
						<ul class="dropdown-menu" aria-labelledby="nav-litige-bt">
							<?php if ($_SESSION['id_type'] == 1 || $_SESSION['id_type'] == 3) : ?>
								<li><a class="dropdown-item lighter-blue" href="<?= PORTAIL_FOU ?>home/home.php">Litiges fournisseurs</a></li>
							<?php endif ?>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/litiges/declaration-choix-mag.php">Déclarer un litige pour un magasin</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/litiges/declaration-robbery.php">Déclarer un vol</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/litiges/bt-litige-encours.php">Litiges en cours</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/litiges/bt-ouvertures.php">Demandes d'ouverture de dossier</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/litiges/stat-litige-mag.php">Réclamations par magasin</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/litiges/exploit-ltg.php">Exploitation</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/litiges/ctrl-stock.php">Contrôle de stock</a></li>
							<li class="dropdown-submenu"> <a class="dropdown-item dropdown-toggle" href="#">Interventions</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item " href='<?= ROOT_PATH ?>/public/litiges/intervention.php?id_contrainte=8'>Achats</a></li>
									<li><a class="dropdown-item " href='<?= ROOT_PATH ?>/public/litiges/intervention.php?id_contrainte=12'>Commission SAV</a></li>
									<li><a class="dropdown-item " href='<?= ROOT_PATH ?>/public/litiges/intervention.php?id_contrainte=4'>SAV</a></li>
									<li><a class="dropdown-item " href='<?= ROOT_PATH ?>/public/litiges/intervention.php?id_contrainte=6'>Vidéo</a></li>
								</ul>
							</li>
							<li><a class="dropdown-item lighter-blue" href="<?= ROOT_PATH ?>/public/casse/casse-dashboard.php">Traitement casse</a></li>
							<li><a class="dropdown-item lighter-blue" href="<?= ROOT_PATH ?>/public/casse/declare-casse.php">Déclarer une casse</a></li>
							<li><a class="dropdown-item lighter-blue" href="<?= ROOT_PATH ?>/public/casse/histo-casse.php">Historique casse</a></li>

						</ul>
					</li>
				<?php endif ?>
				<!-- commun achat -->
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="nav-achat" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Achats</a>
					<ul class="dropdown-menu" aria-labelledby="nav-achat">
						<?php if ($dBtlec) : ?>
							<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle lighter-blue" href="#">Exploitation achats</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item lighter-blue" href='<?= ROOT_PATH ?>/public/doc/upload-main.php'>Ajouter des documents</a></li>
									<li><a class="dropdown-item lighter-blue" href='<?= ROOT_PATH ?>/public/achats-offres/offre-gestion.php'>Gestion des offres TEL BRII</a></li>
									<li><a class="dropdown-item lighter-blue" href='<?= ROOT_PATH ?>/public/achats-odr/odr-gestion.php'>Gestion des odr</a></li>
									<li><a class="dropdown-item lighter-blue" href='<?= ROOT_PATH ?>/public/achats-suivi-livraison/suivi-liv-gestion.php'>Gestion Suivi livraison</a></li>
									<li><a class="dropdown-item lighter-blue" href='<?= ROOT_PATH ?>/public/achats-gesap/gesap-gestion.php'>Gestion des GESAP</a></li>
									<li><a class="dropdown-item lighter-blue" href="<?= ROOT_PATH ?>/public/achats-opp/opp-exploit.php">Ajout opportunités</a></li>
									<li><a class="dropdown-item lighter-blue" href="<?= ROOT_PATH ?>/public/achats-gazette/gestion-gazette.php">Ajout de gazettes</a></li>

								</ul>
							</li>
							<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle lighter-blue" href="#">Commandes en cours</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item lighter-blue" href="<?= ROOT_PATH ?>/public/achats-cdes-encours/cdes-encours.php">Commandes en cours</a></li>
									<li><a class="dropdown-item lighter-blue" href="<?= ROOT_PATH ?>/public/achats-cdes-encours/encours-relances.php">Relances</a></li>
								</ul>
							</li>
						<?php endif ?>
						<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/achats-gazette/gazette.php">La gazette</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/achats-gesap/gesap.php">Les gesaps</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/achats-odr/odr.php">Les offres ODR</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/achats-offres/offres.php">Les offres TEL - BRII</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/achats-opp/opp-encours.php">Les offres spéciales</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/achats-suivi-livraison/suivi-livraison.php">Suivi livraisons catalogues</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH . '/public/doc/display-doc.php#assortiment-title' ?>">Assortiment et panier Promo</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH . '/public/doc/display-doc.php#mdd-title' ?>">MDD</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH . '/public/doc/display-doc.php#gfk-title' ?>">GFK</a></li>
					</ul>
				</li>

				<!-- commun document -->

				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="nav-document" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Documents</a>
					<ul class="dropdown-menu" aria-labelledby="nav-document">
						<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Communication</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?= ROOT_PATH . '/public/doc/plancom2022.php' ?>">Plan de Comm OP BTLec 2022</a></li>
								<li><a class="dropdown-item" href="<?= ROOT_PATH . '/public/doc/plancom2021.php' ?>">Plan de Comm OP BTLec 2021</a></li>
								<li><a class="dropdown-item" href="<?= ROOT_PATH . '/public/doc/kitaffiche.php' ?>">Kit affiches OP BTLec</a></li>
								<?php if (!isset($_SESSION['centrale'])) : ?>
									<li><a class="dropdown-item" href="<?= ROOT_PATH . '/public/infos/twentyfour.php#plv' ?>">PLV 48h</a></li>
								<?php elseif ($_SESSION['centrale'] != "SOCARA") : ?>
									<li><a class="dropdown-item" href="<?= ROOT_PATH . '/public/infos/twentyfour.php#plv' ?>">PLV 48h</a></li>
								<?php endif ?>
							</ul>
						</li>
						<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle " href="#">Comptabilité</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/doc/exploit_rev.php'>Exploit reversements</a></li>
								<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/doc/histo_rev.php">Reversements</a></li>

							</ul>
						</li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/doc/doris.php">Doris</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/doc/extralec.php">Application Extralec</a></li>
						<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/salon/presentation-salon-2020.php">Convention 2020</a></li>
						<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/doc/upload-main.php'>Ajouter des documents</a></li>
					</ul>
				</li>
				<!-- chargé mission mag -->
				<?php if ($dMag) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-mag-cm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Chargé de mission<?= isset($_SESSION['rdv_cm']) ? "<i class='fas fa-bell text-danger nav-bg-icon'></i>" : "" ?></a>
						<ul class="dropdown-menu" aria-labelledby="nav-mag-cm">
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/cm/rdv.php">Vos rendez-vous</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/cm/rapport-accueil.php">Vos comptes rendu</a></li>
						</ul>
					</li>
				<?php endif ?>
				<?php if ($dBtlec || $dBaseMag) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-base-mag" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Magasins</a>
						<ul class="dropdown-menu" aria-labelledby="nav-base-mag">
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/basemag/base-mag.php">Base magasins</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/basemag/fiche-mag.php">Fiches magasins</a></li>
						</ul>
					</li>
				<?php endif ?>




				<?php if ($dBtlec) : ?>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-exploit-bt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Exploit</a>
						<ul class="dropdown-menu" aria-labelledby="nav-exploit-bt">
							<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Statistiques salons</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/salon/stats-salon-2021.php'>Salon 2021</a></li>
									<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/salon/stats-salon-2020.php'>Salon 2020</a></li>
									<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/salon/stats-salon-2019.php'>Salon 2019</a></li>

								</ul>
							</li>
							<li><a class="dropdown-item" href="<?= PORTAIL_SALON ?>home/home.php">Salon fournisseurs</a></li>
							<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/exploit/connexion.php">Connexions magasins</a></li>
							<?php if ($dExploit) : ?>
								<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/exploit/ld-exploit.php'>Listes de diffu BTLec</a></li>
								<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/exploit/add-flash.php'>Saisie d'info urgente</a></li>
								<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/exploit/upload-adh.php'>Upload documents Adhérents</a></li>
							<?php endif ?>


						</ul>
					</li>

				<?php endif ?>

				<?php if ($dLcommerce) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-lcommerce" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">LCommerce</a>
						<ul class="dropdown-menu" aria-labelledby="nav-lcommerce">
							<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/lcom/doc-lcom.php'>Documents</a></li>
							<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/lcom/upload-lcom.php'>Ajout de documents</a></li>
							<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/lcom/move-lcom.php'>Gérer les documents</a></li>
						</ul>
					</li>

				<?php endif ?>
				<?php if ($dConseil) : ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-adherent" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Adhérents</a>
						<ul class="dropdown-menu" aria-labelledby="nav-adherent">
							<li><a class="dropdown-item" href='<?= CONSEIL ?>home.php'>Conseil</a></li>
							<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/exploit/doc-adh.php'>Documents</a></li>
							<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/pres/home-pres.php'>Présentations</a></li>
						</ul>
					</li>
				<?php endif ?>


				<?php if ($dMission) : ?>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="nav-cm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Chargés de mission</a>
						<ul class="dropdown-menu" aria-labelledby="nav-cm">
							<li><a class="dropdown-item" href='<?= PORTAIL_CM ?>cm/index.php'>Portail CM</a></li>
							<li><a class="dropdown-item" href='<?= ROOT_PATH ?>/public/cm/cm-news.php'>Fil d'actu</a></li>
						</ul>
					</li>
				<?php endif ?>
				<li class="nav-item">
					<a class="nav-link" href="<?= PORTAIL_SAV ?>scapsav/home.php">Portail SAV</a>
				</li>
				<?php if ($dWorkflow) : ?>
					<?php
					if ($_SESSION['id'] == 1053 || $_SESSION['id'] == 1895 || $_SESSION['id'] == 974 || $_SESSION['id'] == 687 || $_SESSION['id'] == 968) {
						$hassub = "";
						$lienindex = "indexutilisateur.php";
					} else {
						$hassub = "";
						$lienindex = "index.php";
					}
					?>
					<?php if (empty($hassub)) : ?>
						<li class="nav-item">
							<a class="nav-link" href="<?= ROOT_PATH ?>/public/workflow/<?php echo $lienindex; ?>">Workflow</a>
						</li>
					<?php else : ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="nav-workflow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Workflow</a>
							<ul class="dropdown-menu" aria-labelledby="nav-workflow">
								<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/workflow/dashboard.php">Tableau de bord</a></li>
								<li><a class="dropdown-item" href="<?= ROOT_PATH ?>/public/workflow/indexchoixservice.php">Accèdez aux différents services/personnes</a></li>
							</ul>
						</li>
					<?php endif ?>
				<?php endif ?>


				<?php if ($dBtlec || $dPilotage) : ?>
					<li class="nav-item">
						<a class="nav-link" href="http://<?= SERVER_NAME . "/" . VERSION ?>pilotage">Pilotage</a>
					</li>
				<?php endif ?>


				<?php if ($dBtlec) : ?>
					<li class="nav-item dropdown bg-red">
						<a class="nav-link dropdown-toggle bg-red" href="#" id="nav-evo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Evolutions</a>
						<ul class="dropdown-menu bg-red" aria-labelledby="nav-evo">
							<?php if ($dEvo) : ?>
								<li><a class="dropdown-item bg-red" href="<?= ROOT_PATH ?>/public/evo/dde-evo.php">Demande d'évo</a></li>
								<li><a class="dropdown-item bg-red" href="<?= ROOT_PATH ?>/public/evo/dashboard-evo.php">Supervision</a></li>
								<li><a class="dropdown-item bg-red" href="<?= ROOT_PATH ?>/public/evo/exploit-main.php">Exploitation</a></li>
								<?php if ($_SESSION['id_web_user'] == 981) : ?>
									<li><a class="dropdown-item bg-red" href="<?= ROOT_PATH ?>/public/batch-exploit/batch-monitoring.php">Batch monitoring</a></li>
									<li><a class="dropdown-item bg-red" href="<?= ROOT_PATH ?>/public/exploit/droit.php">Droits</a></li>
								<?php endif ?>
							<?php endif ?>
							<li><a class="dropdown-item bg-red" href="<?= ROOT_PATH ?>/public/evo/planning-evo.php">Planning</a></li>
							<li><a class="dropdown-item bg-red" href="<?= ROOT_PATH ?>/public/evo/vosdemandes-evo.php">Vos demandes</a></li>
						</ul>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?= ROOT_PATH ?>/public/mailsend/sendmail.php"><i class="fas fa-envelope"></i></a>
					</li>
				<?php endif ?>
				<?php if ($dMag) : ?>
					<li class="nav-item">
						<a class="nav-link" href="<?= ROOT_PATH ?>/public/user/profil.php">Votre magasin</a>
					</li>
				<?php endif ?>


				<!-- logoff -->
				<li class="nav-item">
					<a class="nav-link" href="<?= ROOT_PATH ?>/public/logoff.php"><i class="fa fa-power-off"></i></a>
				</li>
			<?php endif ?>
			<!-- FIN CONTENU NAV-->
		</ul>
	</div>
</nav>