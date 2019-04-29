<?php
// fonction pour vérifier les droits utilisateur
function isUserAllowed($pdoUser, $params)
{
	$session=$_SESSION['id'];
	$placeholders=implode(',', array_fill(0, count($params), '?'));
	$req=$pdoUser->prepare("SELECT login FROM attributions WHERE id_droit IN($placeholders) AND id_user=$session" );
	$req->execute($params);
	return $req->fetchAll(PDO::FETCH_ASSOC);

}



//determine si un user (id de la table web user appartient à un grou^pe
function isUserInGroup($pdoBt,$idWebuser,$groupName)
{
	$req=$pdoBt->prepare("SELECT * FROM groups WHERE id_webuser= :idWebuser AND group_name= :groupName");
	$req->execute(array(
		":idWebuser" =>$idWebuser,
		":groupName" =>$groupName
	));
	return $req->rowCount();
}
// accès reversement : admin, compta, rev
$revIds=array(5,7,8);
$d_rev=isUserAllowed($pdoUser,$revIds);
// accès comm : admin, comm
$comIds=array(5,6);
$d_comm=isUserAllowed($pdoUser,$comIds);
// accès exploit : admin
$exploitIds=array(5);
$d_exploit=isUserAllowed($pdoUser,$exploitIds);
// accès conseil : admin, consultation conseil,exploit conseil, inscription
$conseilIds=array(5,9,10,27);
$d_conseil=isUserAllowed($pdoUser,$conseilIds);
// accès contactez nos services : compte mag
$magIds=array(2);
$d_mag=isUserAllowed($pdoUser,$magIds);
//accès lcom user (lecture uniquement)
$lcomUserIds=array(43,44);
$d_lcomUser=isUserAllowed($pdoUser,$lcomUserIds);
//accès lcom admin : luc et amenet
$lcomAdminIds=array(44);
$d_lcomAdmin=isUserAllowed($pdoUser,$lcomAdminIds);

$tempSavIds=array(62);
$d_tempSav=isUserAllowed($pdoUser,$tempSavIds);

$magSocamilIds=array(66,5);
$d_Socamil=isUserAllowed($pdoUser,$magSocamilIds);

$litigeBtIds=array(69);
$d_litigeBt=isUserAllowed($pdoUser,$litigeBtIds);

?>
<div id='cssmenu'>
	<ul>
		<li><a class="less-padding"  href='<?= ROOT_PATH ?>/public/home.php' data-tooltip="Accueil"><span><i class="fa fa-home fa-2x" aria-hidden="true"></i></span></a></li>
		<!-- sous menu 1 -->
		<?php

				//-----------------------------------------------------------------------------------------------------------------
				//							menu pour les magasins
				//-----------------------------------------------------------------------------------------------------------------

		ob_start();
		?>
		<li><a href="<?= ROOT_PATH?>/public/mag/histo-mag.php"><span>Vos demandes</span></a></li>
		<li class='active has-sub'><a href='#'><span>Contacter nos services</span></a>
			<ul>
				<!-- sous menu niv 1 -->
				<li data-module="7"><a href="<?= $contact?>gt=brun ">achats brun</a></li>
				<li data-module="8"><a href="<?= $contact?>gt=gris ">achats gris</a></li>
				<li data-module="9"><a href="<?= $contact?>gt=pemgem ">achats PEM/GEM</a></li>
				<li data-module="16"><a href="<?= $contact?>gt=compta ">comptabilité</a></li>
				<li data-module="10"><a href="<?= $contact?>gt=comm ">communication</a></li>
				<li data-module="10"><a href="<?= $contact?>gt=cm ">contre-marque</a></li>
				<li data-module="10"><a href="<?= $contact?>gt=ctrl_gestion">contrôle de gestion</a></li>
				<li data-module="11"><a href="<?= $contact?>gt=dir ">direction</a></li>
				<li data-module="12"><a href="<?= $contact?>gt=dircom ">direction commerciale</a></li>
				<li data-module="13"><a href="<?= $contact?>gt=informatique ">exploitation informatique</a></li>
				<li data-module="14"><a href="<?= $contact?>gt=logistique ">logistique</a></li>
				<li data-module="15"><a href="<?= $contact?>gt=rh">social</a></li>
				<li data-module="17"><a href="<?= $contact?>gt=qual">qualité</a></li>
			</ul>
		</li>

		<li class='has-sub'><a href="#"><span>Litiges</span></a>
			<ul>
				<li><a href="<?= ROOT_PATH?>/public/litiges/declaration-basic.php">Déclaration de litige</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/mag-litige-listing.php">Mes litiges</a></li>
			</ul>
		</li>


		<?php
		$magNav=ob_get_contents();
		ob_end_clean();
				//-----------------------------------------------------------------------------------------------------------------
			 	//						fin menu pour les magasin
				//-----------------------------------------------------------------------------------------------------------------

			 	//-----------------------------------------------------------------------------------------------------------------
			 	//						menu pour le bt
				//-----------------------------------------------------------------------------------------------------------------

		ob_start();
		?>
		<li class='active has-sub'><a href="<?= ROOT_PATH?>/public/btlec/dashboard.php"><span>Demandes magasin</span></a>
			<ul>
				<li><a href="<?= ROOT_PATH?>/public/btlec/dashboard.php">En attente</a></li>
				<li> <a href="<?= ROOT_PATH?>/public/btlec/histo.php">Clôturées</a></li>
				<li> <a href="<?= ROOT_PATH?>/public/btlec/search.php">Histo par magasin</a></li>

			</ul>
		</li>

		<?php
		$bt=ob_get_contents();
		ob_end_clean();
				 //-----------------------------------------------------------------------------------------------------------------
			 	//						fin menu pour le bt
				//-----------------------------------------------------------------------------------------------------------------

		if($d_mag)
		{
			echo $magNav;

		}
		if ($_SESSION['type']=="btlec")
		{
			echo $bt;

		}
		else
		{

					//sinon rien !!!
		}
		ob_start();
		?>
		<li class='has-sub'><a href="#"><span>Litiges</span></a>
			<ul>
				<li><a href="<?= ROOT_PATH?>/public/litiges/declaration-bt-basic.php">Déclarer un litige pour un magasin</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/bt-litige-encours.php">Litiges en cours</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/bt-ouvertures.php">Demandes d'ouverture de dossier</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/stat-litige-mag.php">Réclamations par magasin</a></li>
				<li><a href="<?= ROOT_PATH?>/public/litiges/exploit-ltg.php">Exploitation</a></li>

			</ul>
		</li>
		<?php
		$litiges=ob_get_contents();
		ob_end_clean();
		if($d_litigeBt){
			echo $litiges;
		}

		?>

		<li><a href="<?= ROOT_PATH. '/public/salon/inscription-2019.php'?>"><span>Salon 2019</span></a></li>

		<!-- section sans sous menu -->
		<li><a href="<?= ROOT_PATH. '/public/entrepot/discover.php'?>"><span>Entrepôt</span></a></li>

		<li><a href="<?= ROOT_PATH. '/public/gazette/gazette.php'?>" >Les gazettes</a></li>
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
						<li><a href="<?= ROOT_PATH. '/public/doc/plancom2019.php'?>">Plan de Comm OP BTLec 2019</a></li>
						<li><a href="<?= ROOT_PATH. '/public/doc/kitaffiche.php'?>">Kit affiches OP BTLec</a></li>
						<?php
						$exceptSocara="<li><a href='".ROOT_PATH ."/public/infos/twentyfour.php#plv'>PLV 48h</a></li>";
						if(!isset($_SESSION['centrale']))
						{
							echo $exceptSocara;
						}
						else
						{
							if($_SESSION['centrale'] !="SOCARA")
							{
								echo $exceptSocara;
							}
						}
						?>
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
				<li><a href="<?= ROOT_PATH. '/public/doc/convention.php'?>">Convention 2018</a></li>
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
		<?php
			//ajout menu exploitation salon
		$exploitNav="<li class='active has-sub'><a href='".ROOT_PATH. "/public/exploit/connexion.php' ><span>Exploit</span></a>";
		$exploitNav.="<ul><li><a href='".ROOT_PATH."/public/salon/stats-salon-2019.php'><span>Stats Salon 2019</span></a></li>";


		$exploitNav.="<li><a href='".ROOT_PATH."/public/doc/flash-validation.php'><span>Suivi des infos flash</span></a></li>";
		$exploitNav.="<li><a href='".ROOT_PATH."/public/exploit/upload-adh.php'><span>Upload documents Adhérents</span></a></li>";
		$exploitNav.="<li><a href='".ROOT_PATH."/public/exploit/connexion.php'><span>Suivi magasins</span></a></li></ul></li>";
		$lcomUserNav="<li class='active has-sub'><a href='#' title='espace LCommerce - documents' ><span>LCommerce</span></a>";
		$lcomUserNav.="<ul><li><a href='".ROOT_PATH."/public/lcom/doc-lcom.php'><span>Documents</span></a></li>";
		$lcomAdminNav="<li><a href='".ROOT_PATH."/public/lcom/upload-lcom.php'><span>Ajout de documents</span></a></li>";
		$lcomAdminNav.="<li><a href='".ROOT_PATH."/public/lcom/move-lcom.php'><span>Gérer les documents</span></a></li></ul></li>";

		if($d_exploit)
		{
			echo $exploitNav;
		}

		if($d_lcomUser && !$d_lcomAdmin)
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
			$conseilNav="<li class='has-sub'><a href='http://172.30.92.53/".$version."conseil/home.php' class='tooltipped' data-position='bottom' data-tooltip='Réservé adhérents / conseil'><span>adhérents</span></a>";
			$conseilNav.="<ul><li><a href='http://172.30.92.53/".$version."conseil/home.php' class='tooltipped' data-position='bottom' data-tooltip='Conseil'><span>Conseil</span></a></li>";
			$conseilNav.="<li><a href='".ROOT_PATH."/public/exploit/doc-adh.php' class='tooltipped' data-position='bottom' data-tooltip='documents réservés adhérents'><span>Documents</span></a></li></ul>";
			$conseilNav.='</li>';

			echo $conseilNav;
		}
		?>
		<li><a href="http://172.30.92.53/<?=$version?>sav/scapsav/home.php" class="tooltipped" data-position="bottom" data-tooltip="site du portail SAV">Portail SAV</a></li>
		<li><a href="<?= ROOT_PATH ?>/public/user/profil.php" class="tooltipped" data-position="bottom" data-tooltip="Votre compte"><span><i class="fa fa-user"></i></span></a></li>
		<li><a href="<?= ROOT_PATH ?>/public/logoff.php" class="tooltipped" data-position="bottom" data-tooltip="se déconnecter"><span><i class="fa fa-power-off"></i></span></a></li>
	</ul>
</div>

