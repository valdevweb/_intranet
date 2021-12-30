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

function getListServiceContact($pdoUser){
	$req=$pdoUser->query("SELECT * FROM services WHERE  mask_contact= 0 Order by service");
	return $req->fetchAll(PDO::FETCH_ASSOC);

}

$listServiceContact=getListServiceContact($pdoUser);

$dBtlec=isUserAllowed($pdoUser,[4]);
$dMag=isUserAllowed($pdoUser, [2]);
$dBaseMag=isUserAllowed($pdoUser, [95]);
$dLitigeBt=isUserAllowed($pdoUser,[69]);
$dExploit=isUserAllowed($pdoUser, [5]);
$dEvo=isUserAllowed($pdoUser, [87]);
$dConseil=isUserAllowed($pdoUser, [9,10,27]);
$dLcommerce=isUserAllowed($pdoUser, [43,44]);
$dMission=isUserAllowed($pdoUser,  [78,5]);
$dOccasionBt=isUserAllowed($pdoUser, [83]);
$dOccasionMag=isUserAllowed($pdoUser, [84]);
$dPilotage=isUserAllowed($pdoUser, [98]);
$dWorkflow=isUserAllowed($pdoUser, [100]);
?>
<?php if ($_SESSION['id']==1531): ?>
	<div id='cssmenu'>
		<ul>
			<li class='active has-sub'>
				<a href="<?= ROOT_PATH?>/public/gtocc/#"><span>Leclerc occasion</span></a>
				<ul>
					<li>
						<a href="<?= ROOT_PATH. '/public/gtocc/offre-produit.php'?>">Offres produits</a>
					</li>
				</ul>
			</li>
			<li>
				<a href="<?= ROOT_PATH ?>/public/logoff.php"><span><i class="fa fa-power-off"></i></span></a>
			</li>
		</ul>
	</div>
<?php else: ?>


	<div id='cssmenu'>
		<ul>
			<li>
				<a class="less-padding"  href='<?= ROOT_PATH ?>/public/home/home.php'><i class="fa fa-home fa-2x" aria-hidden="true"></i></a>
			</li>
			<?php if ($dMag): ?>
				<li>
					<a href="<?=ROOT_PATH?>/public/mag/histo-mag.php"><span>Vos demandes</span></a>
				</li>
				<li class='active has-sub'>
					<a href='#'>Contacter nos services</a>
					<ul>
						<?php foreach ($listServiceContact as $key => $serviceNav): ?>
							<li>
								<a href="<?=ROOT_PATH?>/public/mag/contact.php?id=<?=$serviceNav['id']?> "><?=$serviceNav['service']?></a>
							</li>
						<?php endforeach ?>
					</ul>
				</li>
				<li class='has-sub'>
					<a href="#"><span>Litiges</span></a>
					<ul>
						<li>
							<a href="<?=ROOT_PATH?>/public/litiges/declaration-stepone.php">Déclaration de litige</a>
						</li>
						<li>
							<a href="<?=ROOT_PATH?>/public/litiges/mag-litige-listing.php">Mes litiges</a>
						</li>
					</ul>
				</li>

				<?php if ($dOccasionMag): ?>
					<li class='active has-sub'><a href="<?=ROOT_PATH?>/public/gtocc/#"><span>Leclerc occasion</span></a>
						<ul>
							<li>
								<a href="<?= ROOT_PATH?>/public/gtocc/offre-produit.php">Offres produits</a>
							</li>
							<li>
								<a href="<?= ROOT_PATH?>/public/gtocc/occ-mag-cdes.php">Vos Commandes</a>
							</li>
							<li>
								<a href="<?= ROOT_PATH?>/public/gtocc/occ-news.php">Informations Leclerc occasion</a>
							</li>
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
						<li><a href="<?=ROOT_PATH?>/public/gtocc/palettes-dispo.php">Palettes à traiter</a></li>
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
						<li><a href="<?=ROOT_PATH?>/public/casse/casse-dashboard.php" class="lighter-blue">Traitement casse</a></li>
						<li><a href="<?=ROOT_PATH?>/public/casse/declare-casse.php" class="lighter-blue">Déclarer une casse</a></li>
						<li><a href="<?=ROOT_PATH?>/public/casse/histo-casse.php" class="lighter-blue">Historique casse</a></li>

					</ul>
				</li>
			<?php endif ?>

			<li  class='active has-sub'><a href="#" >Achats</a>
				<ul>
					<?php if ($dBtlec ): ?>

						<li class='has-sub'><a href="#" class="lighter-blue">Exploitation achats</a>
							<ul>
								<li><a href='<?=ROOT_PATH?>/public/doc/upload-main.php'  class="lighter-blue" >Ajouter des documents</a></li>
								<li><a href='<?=ROOT_PATH?>/public/achats-offres/offre-gestion.php'  class="lighter-blue" >Gestion des offres TEL BRII</a></li>
								<li><a href='<?=ROOT_PATH?>/public/achats-odr/odr-gestion.php'  class="lighter-blue" >Gestion des odr</a></li>
								<li><a href='<?=ROOT_PATH?>/public/achats-suivi-livraison/suivi-liv-gestion.php'  class="lighter-blue" >Gestion Suivi livraison</a></li>
								<li><a href='<?=ROOT_PATH?>/public/achats-gesap/gesap-gestion.php'  class="lighter-blue" >Gestion des GESAP</a></li>
								<li><a href="<?= ROOT_PATH?>/public/achats-opp/opp-exploit.php"  class="lighter-blue" >Ajout opportunités</a></li>
								<li><a href="<?= ROOT_PATH?>/public/achats-gazette/gestion-gazette.php"  class="lighter-blue" >Ajout de gazettes</a></li>
							</ul>
						</li>
						<li class='has-sub'><a href="#" class="lighter-blue">Commandes en cours</a>
							<ul>
								<li><a href="<?= ROOT_PATH?>/public/achats-cdes-encours/cdes-encours.php"  class="lighter-blue" >Commandes en cours</a></li>
								<li><a href="<?= ROOT_PATH?>/public/achats-cdes-encours/encours-relances.php"  class="lighter-blue" >Relances</a></li>
							</ul>
						</li>
					<?php endif ?>

					<li><a href="<?=ROOT_PATH?>/public/achats-gazette/gazette.php">La gazette</a></li>
					<li><a href="<?=ROOT_PATH?>/public/achats-gesap/gesap.php">Les gesaps</a></li>
					<li><a href="<?=ROOT_PATH?>/public/achats-odr/odr.php">Les offres ODR</a></li>
					<li><a href="<?=ROOT_PATH?>/public/achats-offres/offres.php">Les offres TEL - BRII</a></li>
					<li><a href="<?=ROOT_PATH?>/public/achats-opp/opp-encours.php">Les offres spéciales</a></li>
					<li><a href="<?=ROOT_PATH?>/public/achats-suivi-livraison/suivi-livraison.php">Suivi livraisons catalogues</a></li>
					<li><a href="<?= ROOT_PATH. '/public/doc/display-doc.php#assortiment-title'?>">Assortiment et panier Promo</a></li>
					<li><a href="<?= ROOT_PATH. '/public/doc/display-doc.php#mdd-title'?>">MDD</a></li>
					<li><a href="<?= ROOT_PATH. '/public/doc/display-doc.php#gfk-title'?>">GFK</a></li>

				</ul>
			</li>
			<li  class='active has-sub'><a href="#" >documents</a>
				<ul>
					<li class='has-sub'><a href="<?= ROOT_PATH. '/public/doc/com_menu.php'?>">Communication</a>
						<ul>
							<li><a href="<?= ROOT_PATH. '/public/doc/plancom2022.php'?>">Plan de Comm OP BTLec 2022</a></li>
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
				</ul>
			</li>
			<?php if ($dMag): ?>

				<li>
					<a href="#" class="">Chargé de mission <?=isset($_SESSION['rdv_cm'])?"<i class='fas fa-bell text-danger nav-bg-icon'></i>" :""?></a>
					<ul>
						<li><a href="<?=ROOT_PATH?>/public/cm/rdv.php">Vos rendez-vous</a></li>
						<li><a href="<?=ROOT_PATH?>/public/cm/rapport-accueil.php">Vos comptes rendu</a></li>
					</ul>
				</li>
			<?php endif ?>

			<?php if ($dBtlec || $dBaseMag): ?>
				<li  class='active has-sub'><a href="#" >Magasins</a>
					<ul>
						<li><a href="<?=ROOT_PATH?>/public/basemag/base-mag.php">Base magasins</a></li>
						<li><a href="<?=ROOT_PATH?>/public/basemag/fiche-mag.php"><span>Fiches magasins</span></a></li>
					</ul>
				</li>
			<?php endif ?>
			<?php if ($dBtlec):?>

				<li class='active has-sub'><a href='#' ><span>Exploit</span></a>
					<ul>
						<li class='active has-sub'><a href="#">Statistiques salon</a>
							<ul>
								<li><a href='<?=ROOT_PATH?>/public/salon/stats-salon-2021.php'><span>Salon 2021</span></a></li>
								<li><a href='<?=ROOT_PATH?>/public/salon/stats-salon-2020.php'><span>Salon 2020</span></a></li>
								<li><a href='<?=ROOT_PATH?>/public/salon/stats-salon-2019.php'><span>Salon 2019</span></a></li>
							</ul>
						</li>
						<li><a href="<?=PORTAIL_SALON?>home/home.php" >Salon fournisseurs</a></li>
						<li><a href='<?=ROOT_PATH?>/public/exploit/connexion.php' id="nav-connexion-mag"><span>Connexions magasins</span></a></li>
						<?php if ($dExploit): ?>
							<li><a href='<?=ROOT_PATH?>/public/exploit/ld-exploit.php'><span>Listes de diffu BTLec</span></a></li>
							<li><a href='<?=ROOT_PATH?>/public/exploit/add-flash.php'><span>Saisie d'info urgente</span></a></li>
							<li><a href='<?=ROOT_PATH?>/public/exploit/upload-adh.php'><span>Upload documents Adhérents</span></a></li>
						<?php endif ?>
					</ul>
				</li>
			<?php endif ?>
			<?php if ($dLcommerce): ?>
				<li class='active has-sub'><a href='#' title='espace LCommerce - documents' ><span>LCommerce</span></a>
					<ul>
						<li><a href='<?=ROOT_PATH?>/public/lcom/doc-lcom.php'><span>Documents</span></a></li>
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

			<?php



			if($dWorkflow){


				if($_SESSION['id_service'] == 12 || $_SESSION['id_service'] == 30 ){
					$hassub = "";
					$lienindex = "indexutilisateur.php?session=".$_SESSION['id'];
				}else{
					$hassub = "";
					if($_SESSION['id_web_user'] == 1405){
						$lienindex = "index.php?session=1405";
					}else{
						$lienindex = "index.php?session=".$_SESSION['id_service'];
					}

				}
				if($_SESSION['id_service'] == 5 || $_SESSION['id_service'] == 18 || $_SESSION['id'] == 702 || $_SESSION['id'] == 959 ){
					$hassub = "has-sub";
					$lienindex = "dashboard.php";
				}

				if(empty($hassub)){
					?>
					<li class="active">
						<a href="<?=ROOT_PATH?>/public/workflow/<?php echo $lienindex; ?>" >Workflow</a>
					</li>
				<?php }else{ ?>
					<li class="active has-sub">
						<a href="<?=ROOT_PATH?>/public/workflow/<?php echo $lienindex; ?>" >Workflow</a>
						<ul>

							<li><a href="<?=ROOT_PATH?>/public/workflow/dashboard.php">Tableau de bord</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/index.php?session=9">Service R.H</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/index.php?session=7">Service Informatique</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/index.php?session=31">Service Pilotage</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/indexutilisateur.php?session=1053">Benoit Dubots</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/indexutilisateur.php?session=687">Benoit Chamarre</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/indexutilisateur.php?session=968">Claire Serrano</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/indexutilisateur.php?session=974">Cédric Vasseur</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/indexutilisateur.php?session=1895">Arnaud Fleury</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/index.php?session=1405">Salem MOUSSONI</a></li>
							<li><a href="<?=ROOT_PATH?>/public/workflow/index.php?session=686">Nathalie Castelletta</a></li>


						</ul>
					</li>

					<!-- <li><a href="index.php?session=18">Alex</a></li> -->
				<?php }
			}
			?>

			<?php if ($dBtlec || $dPilotage): ?>
				<li><a href="http://172.30.92.53/<?=VERSION?>pilotage" >Pilotage</a></li>
			<?php endif ?>

			<?php if ($dBtlec): ?>
				<li  class='active has-sub red-nav'><a href="#" >Evolutions</a>
					<ul>
						<?php if ($dEvo): ?>

							<li><a href="<?=ROOT_PATH?>/public/evo/dde-evo.php" class="red-nav">Demande d'évo</a></li>
							<li><a href="<?=ROOT_PATH?>/public/evo/dashboard-evo.php" class="red-nav">Supervision</a></li>
							<li><a href="<?=ROOT_PATH?>/public/evo/exploit-main.php" class="red-nav">Exploitation</a></li>
							<?php if ($_SESSION['id_web_user']==981): ?>
								<li><a href="<?=ROOT_PATH?>/public/batch-exploit/batch-monitoring.php" class="red-nav">Batch monitoring</a></li>
								<li><a href="<?=ROOT_PATH?>/public/exploit/droit.php" class="red-nav">Droits</a></li>
							<?php endif ?>
						<?php endif ?>
						<li><a href="<?=ROOT_PATH?>/public/evo/planning-evo.php" class="red-nav">Planning</a></li>
						<li><a href="<?=ROOT_PATH?>/public/evo/vosdemandes-evo.php" class="red-nav">Vos demandes</a></li>


					</ul>
				</li>
			<?php endif ?>
			<?php if ($dBtlec): ?>
				<li><a href="<?=ROOT_PATH?>/public/mailsend/sendmail.php" ><i class="fas fa-envelope"></i></a></li>
			<?php endif ?>
			<?php if ($dMag): ?>
				<li><a href="<?=ROOT_PATH ?>/public/user/profil.php" class="tooltipped" data-position="bottom" data-tooltip="Votre compte"><span>Votre magasin<i class="fa fa-user pl-3"></i></span></a></li>
			<?php endif ?>
			<li><a href="<?=ROOT_PATH ?>/public/logoff.php" class="tooltipped" data-position="bottom" data-tooltip="se déconnecter"><span><i class="fa fa-power-off"></i></span></a></li>
		</ul>
	</div>

<?php endif ?>