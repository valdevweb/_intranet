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



// accès reversement : admin, compta, rev
$revIds=array(5,7,8);
$d_rev=isUserAllowed($pdoUser, $revIds);
// accès comm : admin, comm
$comIds=array(5,6);
$d_comm=isUserAllowed($pdoUser, $comIds);

$btlecIds=array(4);
$d_btlec=isUserAllowed($pdoUser, $btlecIds);


// accès exploit + : admin
$exploitIds=array(5);
$d_exploit=isUserAllowed($pdoUser, $exploitIds);

//accès restreint exploit (dsy, cde, luc, moi)
$strictAdminIds=array(82);
$d_strictAdmin=isUserAllowed($pdoUser, $strictAdminIds);

//accès restreint exploit (dsy, cde, luc, moi)
$restraintAdminIds=array(87);
$d_restraintAdmin=isUserAllowed($pdoUser, $restraintAdminIds);

// accès exploit stat : admin + chargé de mission
$exploitStatIds=array(5,78);
$d_exploitStat=isUserAllowed($pdoUser, $exploitStatIds);

$searchMagIds=array(5,78);
$d_searchMag=isUserAllowed($pdoUser,  $searchMagIds);
// accès conseil : admin, consultation conseil,exploit conseil, inscription
$conseilIds=array(5,9,10,27);
$d_conseil=isUserAllowed($pdoUser, $conseilIds);
// accès contactez nos services : compte mag
$magIds=array(2);
$d_mag=isUserAllowed($pdoUser, $magIds);
//accès lcom user (lecture uniquement)
$lcomUserIds=array(43,44);
$d_lcomUser=isUserAllowed($pdoUser, $lcomUserIds);
//accès lcom admin : luc et amenet
$lcomAdminIds=array(44);
$d_lcomAdmin=isUserAllowed($pdoUser, $lcomAdminIds);

$tempSavIds=array(62);
$d_tempSav=isUserAllowed($pdoUser, $tempSavIds);

$magSocamilIds=array(66,5);
$d_Socamil=isUserAllowed($pdoUser, $magSocamilIds);

$litigeBtIds=array(29,69,78,7,79,80);
$d_litigeBt=isUserAllowed($pdoUser, $litigeBtIds);


$missionIds=array(78,5);
$d_mission=isUserAllowed($pdoUser,  $missionIds);

$gtOccBtIds=array(83);
$d_occBt=isUserAllowed($pdoUser, $gtOccBtIds);

$gtOccMagIds=array(84);
$d_occMag=isUserAllowed($pdoUser, $gtOccMagIds);





?>


<div id='cssmenu'>
	<ul>
		<li><a class="less-padding"  href='<?= ROOT_PATH ?>/public/home/home.php' data-tooltip="Accueil"><span><i class="fa fa-home fa-2x" aria-hidden="true"></i></span></a></li>
		<?php if ($d_mag): ?>

		<li><a href="<?= ROOT_PATH?>/public/mag/histo-mag.php"><span>Vos demandes</span></a></li>
		<li class='active has-sub'><a href='#'><span>Contacter nos services</span></a>
			<ul>
				<!-- sous menu niv 1 -->
				<li data-module="7"><a href="<?= $contact?>id=1 ">achats brun</a></li>
				<li data-module="8"><a href="<?= $contact?>id=2 ">achats gris</a></li>
				<li data-module="9"><a href="<?= $contact?>id=3 ">achats PEM/GEM</a></li>
				<li data-module="16"><a href="<?= $contact?>id=10 ">comptabilité</a></li>
				<li data-module="10"><a href="<?= $contact?>id=4 ">communication</a></li>
				<li data-module="10"><a href="<?= $contact?>id=14 ">contre-marque</a></li>
				<li data-module="10"><a href="<?= $contact?>id=13">contrôle de gestion</a></li>
				<li data-module="11"><a href="<?= $contact?>id=5 ">direction</a></li>
				<li data-module="12"><a href="<?= $contact?>id=6 ">direction commerciale</a></li>
				<li data-module="13"><a href="<?= $contact?>id=7">exploitation informatique</a></li>
				<li data-module="13"><a href="<?= $contact?>id=27">Leclerc Occasion</a></li>
				<li data-module="14"><a href="<?= $contact?>id=8">logistique</a></li>
				<li data-module="15"><a href="<?= $contact?>id=9">social</a></li>
				<li data-module="17"><a href="<?= $contact?>id=11">qualité</a></li>
			</ul>
		</li>

		<li class='has-sub'><a href="#"><span>Litiges</span></a>
			<ul>
				<li><a href="<?= ROOT_PATH?>/public/litiges/declaration-stepone.php">Déclaration de litige</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/mag-litige-listing.php">Mes litiges</a></li>
			</ul>
		</li>
		<li>
			<a href="#" class="">Chargé de mission <?=isset($_SESSION['rdv_cm'])?"<i class='fas fa-bell text-danger nav-bg-icon'></i>" :""?></a>
			<ul>
				<li><a href="<?= ROOT_PATH?>/public/cm/rdv.php">Vos rendez-vous</a></li>
			</ul>
		</li>
		<?php endif ?>

		<?php if (isset($_SESSION['type']) && $_SESSION['type']=="btlec"): ?>

		<li class='active has-sub'><a href="<?= ROOT_PATH?>/public/btlec/dashboard.php"><span>Demandes magasin</span></a>
			<ul>
				<li><a href="<?= ROOT_PATH?>/public/btlec/dashboard.php">En attente</a></li>
				<li> <a href="<?= ROOT_PATH?>/public/btlec/histo.php">Clôturées</a></li>
				<li> <a href="<?= ROOT_PATH?>/public/btlec/search.php">Histo par magasin</a></li>

			</ul>
		</li>
		<?php endif ?>
		<?php if ($d_occBt): ?>
			<li class='active has-sub'><a href="#"><span>Leclerc occasion</span></a>
				<ul>
					<li><a href="<?= ROOT_PATH. '/public/gtocc/occ-palette.php'?>">Offres produits</a></li>
					<li><a href="<?= ROOT_PATH. '/public/gtocc/occ-exploit.php'?>">Gestion GT Occasion</a></li>
					<li><a href="<?= ROOT_PATH. '/public/gtocc/occ-expedie.php'?>">Palettes expédiées</a></li>
				</ul>
			</li>
		<?php endif ?>
		<?php if ($d_occMag): ?>
			<li class='active has-sub'><a href="<?= ROOT_PATH?>/public/gtocc/#"><span>Leclerc occasion</span></a>
				<ul>
					<li><a href="<?= ROOT_PATH. '/public/gtocc/occ-palette.php'?>">Offres produits</a></li>
					<li><a href="<?= ROOT_PATH. '/public/gtocc/occ-mag-cdes.php'?>">Vos Commandes</a></li>
				</ul>
			</li>
		<?php endif ?>

		<?php if ($d_litigeBt): ?>

		<li class='has-sub'><a href="#"><span>Litiges</span></a>
			<ul>
				<li><a href="<?= ROOT_PATH?>/public/litiges/declaration-choix-mag.php">Déclarer un litige pour un magasin</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/declaration-robbery.php">Déclarer un vol</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/bt-litige-encours.php">Litiges en cours</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/bt-ouvertures.php">Demandes d'ouverture de dossier</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/stat-litige-mag.php">Réclamations par magasin</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/exploit-ltg.php">Exploitation</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/ctrl-stock.php">Contrôle de stock</a></li>
				<?php if (isUserAllowed($pdoUser, [5,80])): ?>
					<li><a href="<?= ROOT_PATH?>/public/litiges/intervention-commission-sav.php">Retour Commission SAV</a></li>
				<?php endif ?>

				<?php if (isUserAllowed($pdoUser, [29,5])): ?>
					<li><a href="<?= ROOT_PATH?>/public/litiges/intervention-sav.php">Retour SAV</a></li>
				<?php endif ?>

				<?php if (isUserAllowed($pdoUser, [79,5])): ?>
					<li><a href="<?= ROOT_PATH?>/public/litiges/intervention-achats.php">Retour Service achats</a></li>
				<?php endif ?>

				<li><a href="<?= ROOT_PATH?>/public/casse/bt-casse-dashboard.php" class="lighter-blue">Traitement casse</a></li>
				<li><a href="<?= ROOT_PATH?>/public/casse/histo-casse.php" class="lighter-blue">Historique casse</a></li>

			</ul>
		</li>
		<?php endif ?>

		<!-- section sans sous menu -->

		<li  class='active has-sub'><a href="<?= ROOT_PATH. '/public/gazette/gazette.php'?>" >Les gazettes</a>

				<ul>
					<li><a href="<?= ROOT_PATH. '/public/gazette/opp-exploit.php'?>">Ajout opportunités</a></li>
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
						<?php
						$a_rev="<li><a href='".ROOT_PATH."/public/doc/exploit_rev.php'>Exploit reversements</a></li>";
						if($d_rev)
						{
							echo $a_rev;
						}
						?>
						<li><a href="<?= ROOT_PATH. '/public/doc/histo_rev.php'?>">Reversements</a></li>

					</ul>
				</li>
				<li><a href="<?= ROOT_PATH. '/public/doc/doris.php'?>">Doris</a></li>
				<li><a href="<?= ROOT_PATH. '/public/doc/extralec.php'?>">Application Extralec</a></li>
				<li><a href="<?= ROOT_PATH. '/public/salon/presentation-salon-2020.php'?>">Convention 2020</a></li>
				<?php

				$btdoc="<li><a href='".ROOT_PATH."/public/doc/upload-main.php'>Ajouter des documents</a></li>";

				$btdoc.="<li><a href='".ROOT_PATH. "/public/doc/flash-add.php'>Ajouter une info flash</a></li>";
				if($d_comm)
				{
					echo $btdoc;
				}

				?>


			</ul>
		</li>
		<?php if ($d_btlec): ?>
			<li  class='active has-sub'><a href="#" >Magasins</a>
				<ul>
					<li><a href="<?= ROOT_PATH?>/public/basemag/base-mag.php">Base magasins</a></li>
					<li><a href="<?= ROOT_PATH?>/public/basemag/fiche-mag.php"><span>Fiches magasins</span></a></li>


				</ul>
			</li>

		<?php endif ?>


		<?php
			//ajout menu exploitation salon
		$exploitHead="<li class='active has-sub'><a href='".ROOT_PATH. "/public/exploit/connexion.php' ><span>Exploit</span></a>";
		$exploitStat="<ul><li><a href='".ROOT_PATH."/public/salon/stats-salon-2020.php'><span>Stats Salon 2020</span></a></li>";
		$exploitStat.="<li><a href='".ROOT_PATH."/public/salon/exploit-2020.php'><span>Exploit salon 2020</span></a></li>";
		$exploitStat.="<li><a href='".ROOT_PATH."/public/salon/stats-salon-2019.php'><span>Stats Salon 2019</span></a></li>";
		$exploitStat.="<li><a href='".ROOT_PATH."/public/exploit/connexion.php'><span>Suivi magasins</span></a></li>";
		$exploitStat.="<li><a href='".ROOT_PATH."/public/exploit/ld-exploit.php'><span>Listes de diffu BTLec</span></a></li>";

		$exploitMore="<li><a href='".ROOT_PATH."/public/doc/flash-validation.php'><span>Suivi des infos flash</span></a></li>";
		$exploitMore.="<li><a href='".ROOT_PATH."/public/exploit/upload-adh.php'><span>Upload documents Adhérents</span></a></li>";
		$exploitEnd='</ul>';
		$exploitEnd.="</li>";

		$lcomUserNav="<li class='active has-sub'><a href='#' title='espace LCommerce - documents' ><span>LCommerce</span></a>";
		$lcomUserNav.="<ul><li><a href='".ROOT_PATH."/public/lcom/doc-lcom.php'><span>Documents</span></a></li>";
		$lcomAdminNav="<li><a href='".ROOT_PATH."/public/lcom/upload-lcom.php'><span>Ajout de documents</span></a></li>";
		$lcomAdminNav.="<li><a href='".ROOT_PATH."/public/lcom/move-lcom.php'><span>Gérer les documents</span></a></li></ul></li>";

		if($d_exploit)
		{
			echo $exploitHead;

			echo $exploitStat;
			echo $exploitMore;
			echo $exploitEnd;
		}
		elseif($d_exploitStat){
			echo $exploitHead;

			echo $exploitStat;
			echo $exploitEnd;
		}
		elseif($d_lcomUser && !$d_lcomAdmin)
		{
			echo $lcomUserNav .'</ul></li>';
		}
		elseif ($d_lcomAdmin)
		{
			echo $lcomUserNav;
			echo $lcomAdminNav;
		}

			//menu conseil
		if($d_conseil)
		{
			$conseilNav="<li class='has-sub'><a href='http://172.30.92.53/".$version."conseil/home.php' class='tooltipped' data-position='bottom' data-tooltip='Réservé adhérents / conseil'><span>adhérents & pres</span></a>";
			$conseilNav.="<ul><li><a href='http://172.30.92.53/".$version."conseil/home.php' class='tooltipped' data-position='bottom' data-tooltip='Conseil'><span>Conseil</span></a></li>";
			$conseilNav.="<li><a href='".ROOT_PATH."/public/exploit/doc-adh.php' class='tooltipped' data-position='bottom' data-tooltip='documents réservés adhérents'><span>Documents</span></a></li>";
			$conseilNav.="<li><a href='".ROOT_PATH."/public/pres/home-pres.php' ><span>Présentations</span></a></li></ul>";
			$conseilNav.='</li>';

			echo $conseilNav;
		}

		if($d_mission)
		{
			$missionNav="<li class='has-sub'><a href='http://172.30.92.53/".$version."cm/cm/index.php' ><span>CHARGES DE MISSION</span></a>";
			$missionNav.="<ul><li><a href='http://172.30.92.53/".$version."cm/cm/index.php' ><span>Portail CM</span></a></li>";
			$missionNav.="<li><a href='http://172.30.92.53/".$version."btlecest/public/cm/cm-news.php' ><span>Fil d'actu</span></a></li>";
			$missionNav.='</ul>';
			$missionNav.='</li>';
			echo $missionNav;
		}


		?>
		<li><a href="http://172.30.92.53/<?=$version?>sav/scapsav/home.php" class="tooltipped" data-position="bottom" data-tooltip="site du portail SAV">Portail SAV</a></li>
		<?php if ($d_restraintAdmin): ?>

			<li  class='active has-sub red-nav'><a href="#" >Evolutions</a>
				<ul>
					<li><a href="<?= ROOT_PATH?>/public/evo/dde-evo.php" class="red-nav">Demande d'évo</a></li>
					<li><a href="<?= ROOT_PATH?>/public/evo/dashboard-evo.php" class="red-nav">Supervision</a></li>
					<li><a href="<?= ROOT_PATH?>/public/evo/vosdemandes-evo.php" class="red-nav">Vos demandes</a></li>


				</ul>
			</li>
		<?php endif ?>

		<?php if ($d_mag): ?>
			<li><a href="<?= ROOT_PATH ?>/public/user/profil.php" class="tooltipped" data-position="bottom" data-tooltip="Votre compte"><span>Votre magasin<i class="fa fa-user pl-3"></i></span></a></li>
		<?php endif ?>


		<li><a href="<?= ROOT_PATH ?>/public/logoff.php" class="tooltipped" data-position="bottom" data-tooltip="se déconnecter"><span><i class="fa fa-power-off"></i></span></a></li>
	</ul>
</div>

