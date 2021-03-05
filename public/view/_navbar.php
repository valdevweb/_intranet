<?php
function isUserAllowed($pdoUser, $params){
	$session=$_SESSION['id'];
	$placeholders=implode(',', array_fill(0, count($params), '?'));
	$req=$pdoUser->prepare("SELECT id_user FROM attributions WHERE id_droit IN($placeholders) AND id_user=$session" );
	$req->execute($params);
	$datas=$req->fetchAll(PDO::FETCH_ASSOC);
	if(empty($datas)){
		return false;
	}
	return true;

}
$dBtlec=isUserAllowed($pdoUser,[4]);
$dMag=isUserAllowed($pdoUser, [2]);

$dLitigeBt=isUserAllowed($pdoUser,[69]);
$dExploit=isUserAllowed($pdoUser, [5]);
$dEvo=isUserAllowed($pdoUser, [87]);
$dConseil=isUserAllowed($pdoUser, [9,10,27]);
$dLcommerce=isUserAllowed($pdoUser, [43,44]);
$dMission=isUserAllowed($pdoUser,  [78,5]);
$dOccasionBt=isUserAllowed($pdoUser, [83]);
$dOccasionMag=isUserAllowed($pdoUser, [84]);



?>
<?php if ($_SESSION['id']==1531): ?>
	<div id='cssmenu'>
		<ul>
			<li class='active has-sub'><a href="<?= ROOT_PATH?>/public/gtocc/#"><span>Leclerc occasion</span></a>
				<ul>
					<li><a href="<?= ROOT_PATH. '/public/gtocc/offre-produit.php'?>">Offres produits</a></li>
				</ul>
			</li>
			<li><a href="<?= ROOT_PATH ?>/public/logoff.php" class="tooltipped" data-position="bottom" data-tooltip="se déconnecter"><span><i class="fa fa-power-off"></i></span></a></li>
		</ul>
	</div>
	<?php else: ?>


		<div id='cssmenu'>
			<ul>
				<li>
					<a class="less-padding"  href='<?= ROOT_PATH ?>/public/home/home.php' data-tooltip="Accueil"><span><i class="fa fa-home fa-2x" aria-hidden="true"></i></span></a>
				</li>
				<?php if ($dMag): ?>
					<li><a href="<?=ROOT_PATH?>/public/mag/histo-mag.php"><span>Vos demandes</span></a></li>
					<li class='active has-sub'><a href='#'><span>Contacter nos services</span></a>
						<ul>
							<li data-module="7"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=1 ">achats brun</a></li>
							<li data-module="8"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=2 ">achats gris</a></li>
							<li data-module="9"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=3 ">achats PEM/GEM</a></li>
							<li data-module="16"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=10 ">comptabilité</a></li>
							<li data-module="10"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=4 ">communication</a></li>
							<li data-module="10"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=14 ">contre-marque</a></li>
							<li data-module="10"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=13">contrôle de gestion</a></li>
							<li data-module="11"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=5 ">direction</a></li>
							<li data-module="12"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=6 ">direction commerciale</a></li>
							<li data-module="13"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=7">exploitation informatique</a></li>
							<li data-module="13"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=27">Leclerc Occasion</a></li>
							<li data-module="14"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=8">logistique</a></li>
							<li data-module="15"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=9">social</a></li>
							<li data-module="17"><a href="<?=ROOT_PATH?>/public/mag/contact.php?id=11">qualité</a></li>
						</ul>
					</li>
					<li class='has-sub'><a href="#"><span>Litiges</span></a>
						<ul>
							<li><a href="<?=ROOT_PATH?>/public/litiges/declaration-stepone.php">Déclaration de litige</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/mag-litige-listing.php">Mes litiges</a></li>
						</ul>
					</li>
					<li>
						<a href="#" class="">Chargé de mission <?=isset($_SESSION['rdv_cm'])?"<i class='fas fa-bell text-danger nav-bg-icon'></i>" :""?></a>
						<ul>
							<li><a href="<?=ROOT_PATH?>/public/cm/rdv.php">Vos rendez-vous</a></li>
						</ul>
					</li>
					<?php if ($dOccasionMag): ?>
						<li class='active has-sub'><a href="<?=ROOT_PATH?>/public/gtocc/#"><span>Leclerc occasion</span></a>
							<ul>
								<li><a href="<?= ROOT_PATH?>/public/gtocc/offre-produit.php">Offres produits</a></li>
								<li><a href="<?= ROOT_PATH?>/public/gtocc/occ-mag-cdes.php">Vos Commandes</a></li>
								<li><a href="<?= ROOT_PATH?>/public/gtocc/occ-news.php">Informations Leclerc occasion</a></li>
							</ul>
						</li>
					<?php endif ?>
				<?php endif ?>

				<?php if (isset($_SESSION['type']) && $_SESSION['type']=="btlec"): ?>
					<li class='active has-sub'><a href="<?=ROOT_PATH?>/public/btlec/dashboard.php"><span>Demandes magasin</span></a>
						<ul>
							<li><a href="<?=ROOT_PATH?>/public/btlec/dashboard.php">En attente</a></li>
							<li> <a href="<?=ROOT_PATH?>/public/btlec/histo.php">Clôturées</a></li>
							<li> <a href="<?=ROOT_PATH?>/public/btlec/search.php">Histo par magasin</a></li>

						</ul>
					</li>
				<?php endif ?>
				<?php if ($dOccasionBt): ?>
					<li class='active has-sub'><a href="#"><span>Leclerc occasion</span></a>
						<ul>
							<li><a href="<?=ROOT_PATH?>/public/gtocc/offre-produit.php">Offres produits</a></li>
							<li><a href="<?=ROOT_PATH?>/public/gtocc/occ-exploit.php">Gestion GT Occasion</a></li>
							<li><a href="<?=ROOT_PATH?>/public/gtocc/occ-expedie.php">Palettes expédiées</a></li>
							<li><a href="<?=ROOT_PATH?>/public/gtocc/occ-editinfo.php">Exploit infos mag GT13</a></li>
							<li><a href="<?=ROOT_PATH?>/public/gtocc/occ-news.php">Informations Leclerc occasion</a></li>

						</ul>
					</li>
				<?php endif ?>


				<?php if ($dBtlec || $dLitigeBt): ?>

					<li class='has-sub'><a href="#"><span>Litiges</span></a>
						<ul>
							<?php if ($_SESSION['id_type']==1 || $_SESSION['id_type']==3): ?>
								<li><a href="<?=PORTAIL_FOU?>home/home.php" class="lighter-blue">Litiges fournisseurs</a></li>
							<?php endif ?>
							<li><a href="<?=ROOT_PATH?>/public/litiges/declaration-choix-mag.php">Déclarer un litige pour un magasin</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/declaration-robbery.php">Déclarer un vol</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/bt-litige-encours.php">Litiges en cours</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/bt-ouvertures.php">Demandes d'ouverture de dossier</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/stat-litige-mag.php">Réclamations par magasin</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/exploit-ltg.php">Exploitation</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/ctrl-stock.php">Contrôle de stock</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/intervention-commission-sav.php">Retour Commission SAV</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/intervention-sav.php">Retour SAV</a></li>
							<li><a href="<?=ROOT_PATH?>/public/litiges/intervention-achats.php">Retour Service achats</a></li>
							<li><a href="<?=ROOT_PATH?>/public/casse/bt-casse-dashboard.php" class="lighter-blue">Traitement casse</a></li>
							<li><a href="<?=ROOT_PATH?>/public/casse/histo-casse.php" class="lighter-blue">Historique casse</a></li>

						</ul>
					</li>
				<?php endif ?>
				<li  class='active has-sub'><a href="<?= ROOT_PATH?>/public/gazette/gazette.php" >Les gazettes</a>
					<ul>
						<li><a href="<?= ROOT_PATH?>/public/gazette/opp-exploit.php">Ajout opportunités</a></li>
						<li><a href="<?= ROOT_PATH?>/public/gazette/gestion-gazette.php">Ajout de gazettes</a></li>

					</ul>
				</li>
				<li  class='active has-sub'><a href="#" >documents</a>
					<ul>
						<li class='has-sub'><a href="<?= ROOT_PATH. '/public/doc/display-doc.php'?>">Achats</a>
							<ul>
								<li><a href="<?= ROOT_PATH. '/public/doc/display-doc.php#odr-title'?>">ODR</a></li>
								<li><a href="<?= ROOT_PATH. '/public/doc/display-doc.php#tel-title'?>">TEL/BRII</a></li>
								<li><a href="<?= ROOT_PATH. '/public/doc/display-doc.php#assortiment-title'?>">Assortiment et panier Promo</a></li>
								<li><a href="<?= ROOT_PATH. '/public/doc/display-doc.php#mdd-title'?>">MDD</a></li>
								<li><a href="<?= ROOT_PATH. '/public/doc/display-doc.php#gfk-title'?>">GFK</a></li>
							</ul>
						</li>
						<li class='has-sub'><a href="<?= ROOT_PATH. '/public/doc/com_menu.php'?>">Communication</a>
							<ul>
								<li><a href="<?= ROOT_PATH. '/public/doc/plancom2020.php'?>">Plan de Comm OP BTLec 2020</a></li>
								<li><a href="<?= ROOT_PATH. '/public/doc/plancom2021.php'?>">Plan de Comm OP BTLec 2021</a></li>
								<li><a href="<?= ROOT_PATH. '/public/doc/kitaffiche.php'?>">Kit affiches OP BTLec</a></li>
								<?php if (!isset($_SESSION['centrale'])): ?>
									<li><a href="<?= ROOT_PATH. '/public/infos/twentyfour.php#plv'?>">PLV 48h</a></li>
									<?php elseif($_SESSION['centrale'] !="SOCARA"):?>
										<li><a href="<?= ROOT_PATH. '/public/infos/twentyfour.php#plv'?>">PLV 48h</a></li>
									<?php endif ?>
								</ul>
							</li>
							<li class='has-sub'><a href="#">Comptabilité</a>
								<ul>

									<li><a href='<?=ROOT_PATH?>/public/doc/exploit_rev.php'>Exploit reversements</a></li>
									<li><a href="<?=ROOT_PATH?>/public/doc/histo_rev.php">Reversements</a></li>
								</ul>
							</li>
							<li><a href="<?=ROOT_PATH?>/public/doc/doris.php">Doris</a></li>
							<li><a href="<?=ROOT_PATH?>/public/doc/extralec.php">Application Extralec</a></li>
							<li><a href="<?=ROOT_PATH?>/public/salon/presentation-salon-2020.php">Convention 2020</a></li>
							<li><a href='<?=ROOT_PATH?>/public/doc/upload-main.php'>Ajouter des documents</a></li>
							<li><a href='<?=ROOT_PATH?>/public/doc/offre-gestion.php'>Gestion des offres produits</a></li>
							<li><a href='<?=ROOT_PATH?>/public/doc/odr-gestion.php'>Gestion des odr</a></li>
						</ul>
					</li>
					<?php if ($dBtlec): ?>
						<li  class='active has-sub'><a href="#" >Magasins</a>
							<ul>
								<li><a href="<?=ROOT_PATH?>/public/basemag/base-mag.php">Base magasins</a></li>
								<li><a href="<?=ROOT_PATH?>/public/basemag/fiche-mag.php"><span>Fiches magasins</span></a></li>
							</ul>
						</li>
						<li class='active has-sub'><a href='".ROOT_PATH. "/public/exploit/connexion.php' ><span>Exploit</span></a>
							<ul>
								<li><a href='<?=ROOT_PATH?>/public/salon/stats-salon-2019.php'><span>Stats Salon 2019</span></a></li>
								<li><a href='<?=ROOT_PATH?>/public/salon/stats-salon-2020.php'><span>Stats Salon 2020</span></a></li>
								<li><a href='<?=ROOT_PATH?>/public/salon/exploit-2020.php'><span>Exploit salon 2020</span></a></li>
								<li><a href='<?=ROOT_PATH?>/public/exploit/connexion.php'><span>Suivi magasins</span></a></li>
								<?php if ($dExploit): ?>
									<li><a href='<?=ROOT_PATH?>/public/exploit/ld-exploit.php'><span>Listes de diffu BTLec</span></a></li>

									<li><a href='<?=ROOT_PATH?>/public/exploit/add-flash.php'><span>Saisie d'info urgente</span></a></li>
									<li><a href='<?=ROOT_PATH?>/public/exploit/upload-adh.php'><span>Upload documents Adhérents</span></a></li>
								<?php endif ?>

								<?php if ($_SESSION['id_web_user']==981): ?>
									<li><a href="<?=ROOT_PATH?>/public/exploit/droit.php" class="red-nav">Droits</a></li>
								<?php endif ?>

						</ul>
					</li>
				<?php endif ?>
				<?php if ($dLcommerce): ?>
					<li class='active has-sub'><a href='#' title='espace LCommerce - documents' ><span>LCommerce</span></a>
						<ul><li><a href='<?=ROOT_PATH?>/public/lcom/doc-lcom.php'><span>Documents</span></a></li>
							<li><a href='<?=ROOT_PATH?>/public/lcom/upload-lcom.php'><span>Ajout de documents</span></a></li>
							<li><a href='<?=ROOT_PATH?>/public/lcom/move-lcom.php'><span>Gérer les documents</span></a></li>
						</ul>
					</li>
				<?php endif ?>

				<?php if ($dConseil): ?>
					<li class='has-sub'><a href='<?=CONSEIL?>home.php' class='tooltipped' data-position='bottom' data-tooltip='Réservé adhérents / conseil'><span>adhérents</span></a>
						<ul>
							<li><a href='<?=CONSEIL?>home.php' class='tooltipped' data-position='bottom' data-tooltip='Conseil'><span>Conseil</span></a></li>
							<li><a href='<?=ROOT_PATH?>/public/exploit/doc-adh.php' class='tooltipped' data-position='bottom' data-tooltip='documents réservés adhérents'><span>Documents</span></a></li>
							<li><a href='<?=ROOT_PATH?>/public/pres/home-pres.php' ><span>Présentations</span></a></li>
						</ul>
					</li>
				<?php endif ?>
				<?php if ($dMission): ?>
					<li class='has-sub'><a href='<?=PORTAIL_CM?>cm/index.php' ><span>CHARGES DE MISSION</span></a>
						<ul>
							<li><a href='<?=PORTAIL_CM?>cm/index.php' ><span>Portail CM</span></a></li>
							<li><a href='<?=ROOT_PATH?>/public/cm/cm-news.php' ><span>Fil d'actu</span></a></li>
						</ul>
					</li>
				<?php endif ?>

				<li><a href="<?=PORTAIL_SAV?>scapsav/home.php" class="tooltipped" data-position="bottom" data-tooltip="site du portail SAV">Portail SAV</a></li>
				<?php if ($dEvo): ?>
					<li  class='active has-sub red-nav'><a href="#" >Evolutions</a>
						<ul>
							<li><a href="<?=ROOT_PATH?>/public/evo/dde-evo.php" class="red-nav">Demande d'évo</a></li>
							<li><a href="<?=ROOT_PATH?>/public/evo/dashboard-evo.php" class="red-nav">Supervision</a></li>
							<li><a href="<?=ROOT_PATH?>/public/evo/vosdemandes-evo.php" class="red-nav">Vos demandes</a></li>

						</ul>
					</li>
				<?php endif ?>

				<?php if ($dMag): ?>
					<li><a href="<?=ROOT_PATH ?>/public/user/profil.php" class="tooltipped" data-position="bottom" data-tooltip="Votre compte"><span>Votre magasin<i class="fa fa-user pl-3"></i></span></a></li>
				<?php endif ?>
				<li><a href="<?=ROOT_PATH ?>/public/logoff.php" class="tooltipped" data-position="bottom" data-tooltip="se déconnecter"><span><i class="fa fa-power-off"></i></span></a></li>
			</ul>
		</div>

	<?php endif ?>
