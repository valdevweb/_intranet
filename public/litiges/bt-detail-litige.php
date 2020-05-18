<?php

 // require('../../config/pdo_connect.php');
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

unset($_SESSION['goto']);


require 'echanges.fn.php';
require('../../Class/UserHelpers.php');

//------------------------------------------------------
//			INFOS
//------------------------------------------------------
// 0=pas ajoutée, 1 ajoutée et correcte, 2 ajoutée mais incorrecte


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

// info produit
function getLitige($pdoLitige)
{
	$req=$pdoLitige->prepare("
		SELECT
		dossiers.id as id_main,	dossiers.dossier,dossiers.date_crea,DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea,dossiers.user_crea,dossiers.galec,dossiers.etat_dossier,vingtquatre, dossiers.id_web_user, inversion,inv_article,inv_fournisseur,inv_tarif,inv_descr,nom, valo, flag_valo, id_reclamation,inv_palette,inv_qte,id_robbery, commission, box_tete, box_art,
		details.id as id_detail,details.ean,details.id_dossier,	details.palette,details.facture,details.article,details.tarif,details.qte_cde, details.qte_litige,details.valo_line,details.dossier_gessica,details.descr,details.fournisseur,details.pj,DATE_FORMAT(details.date_facture, '%d-%m-%Y') as datefacture, details.serials,
		reclamation.reclamation,
		btlec.sca3.mag, btlec.sca3.centrale, btlec.sca3.btlec,
		etat.etat
		FROM dossiers
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN reclamation ON details.id_reclamation = reclamation.id
		LEFT JOIN btlec.sca3 ON dossiers.galec=btlec.sca3.galec
		LEFT JOIN etat ON etat_dossier=etat.id
		WHERE dossiers.id= :id ORDER BY date_crea");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
	// return $req->errorInfo();
}

$fLitige=getLitige($pdoLitige);


function getFirstDial($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM `dial` WHERE id_dossier=:id AND mag=3");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}


function getInfos($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT transporteur.transporteur, affrete.affrete, transit.transit, CONCAT(prepa.nom,' ', prepa.prenom) as fullprepa, CONCAT(ctrl.nom,' ',ctrl.prenom) as fullctrl,CONCAT(chg.nom,' ',chg.prenom) as fullchg, mt_transp, mt_assur, mt_fourn, mt_mag, fac_mag, DATE_FORMAT(date_prepa,'%d-%m-%Y') as dateprepa, ctrl_ok FROM dossiers
		LEFT JOIN transporteur ON id_transp=transporteur.id
		LEFT JOIN affrete ON id_affrete=affrete.id
		LEFT JOIN transit ON id_transit=transit.id
		LEFT JOIN equipe as prepa ON id_prepa=prepa.id
		LEFT JOIN equipe as ctrl ON id_ctrl=ctrl.id
		LEFT JOIN equipe as chg ON id_chg=chg.id
		LEFT JOIN equipe as ctrl_stock ON id_ctrl_stock=ctrl_stock.id
		WHERE  dossiers.id= :id ");

	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}
$infos=getInfos($pdoLitige);


function getAnalyse($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT gt, imputation, typo, etat, analyse, conclusion FROM dossiers
		LEFT JOIN gt ON id_gt=gt.id
		LEFT JOIN imputation ON id_imputation=imputation.id
		LEFT JOIN typo ON id_typo=typo.id
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN analyse ON id_analyse=analyse.id
		LEFT JOIN conclusion ON id_conclusion=conclusion.id
		WHERE dossiers.id= :id");
	$req->execute(array(
		':id'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);

}
$analyse=getAnalyse($pdoLitige);

function getAction($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT libelle, id_web_user, DATE_FORMAT(date_action, '%d-%m-%Y')as dateFr,  pj, sav, achats FROM action  WHERE action.id_dossier= :id ORDER BY date_action");
	$req->execute(array(
		':id'		=>$_GET['id']

	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
$actionList=getAction($pdoLitige);



$coutTotal=$infos['mt_transp']+$infos['mt_assur']+$infos['mt_fourn']+$infos['mt_mag'];
if($infos['ctrl_ok']==0)
{
	$ctrl="non contrôlé";
}
elseif($infos['ctrl_ok']==1){
	$ctrl="fait";

}
elseif($infos['ctrl_ok']==2){
	$ctrl="demandé";
}

if($coutTotal!=0){
	$coutTotal=number_format((float)$coutTotal,2,'.','');
}



function updateValo($pdoLitige, $valo,$flag)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET valo= :valo, flag_valo= :flag_valo WHERE id= :id");
	$req->execute(array(
		':id'		=>$_GET['id'],
		':valo'		=>$valo,
		':flag_valo'	=>$flag
	));
	return $req->rowCount();
}
$articleAZero='';




function getFinance($pdoQlik, $btlec, $year)
{
	$req=$pdoQlik->prepare("SELECT CA_Annuel FROM statsventesadh WHERE CodeBtlec= :btlec AND AnneeCA= :year");
	$req->execute(array(
		':btlec' =>$btlec,
		':year'	=>$year
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getSumDeclare($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(valo) as sumValo FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}

function getMtMag($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(mt_mag) as sumMtMag FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getCoutTotalYear($pdoLitige,$galec,$year){
	$req=$pdoLitige->prepare("SELECT sum(mt_mag) as mtMag, sum(mt_assur) as mtassur, sum(mt_transp) as mttransp, sum(mt_fourn) as mtfourn FROM dossiers WHERE galec=:galec AND DATE_FORMAT(date_crea, '%Y')=:year");
	$req->execute([
		':galec'		=>$galec,
		':year'			=>$year
	]);
	return $req->fetch(PDO::FETCH_ASSOC);

}



$yearN=date('Y');
$yearNUn= date("Y",strtotime("-1 year"));
$yearNDeux= date("Y",strtotime("-2 year"));

$financeN=getFinance($pdoQlik,$fLitige[0]['btlec'],$yearN);
$financeNUn=getFinance($pdoQlik,$fLitige[0]['btlec'],$yearNUn);
$financeNDeux=getFinance($pdoQlik,$fLitige[0]['btlec'],$yearNDeux);
$reclameN=getSumDeclare($pdoLitige,$fLitige[0]['galec'],$yearN);
$reclameNUn=getSumDeclare($pdoLitige,$fLitige[0]['galec'],$yearNUn);
$reclameNDeux=getSumDeclare($pdoLitige,$fLitige[0]['galec'],$yearNDeux);

$rembourseN=getMtMag($pdoLitige,$fLitige[0]['galec'],$yearN);
$rembourseNUn=getMtMag($pdoLitige,$fLitige[0]['galec'],$yearNUn);
$rembourseNDeux=getMtMag($pdoLitige,$fLitige[0]['galec'],$yearNDeux);

$coutN=getCoutTotalYear($pdoLitige,$fLitige[0]['galec'],$yearN);
$coutN=$coutN['mtMag']+$coutN['mtfourn']+$coutN['mttransp']+$coutN['mtassur'];
$coutNUn=getCoutTotalYear($pdoLitige,$fLitige[0]['galec'],$yearNUn);
$coutNUn=$coutNUn['mtMag']+$coutNUn['mtfourn']+$coutNUn['mttransp']+$coutNUn['mtassur'];

$coutNDeux=getCoutTotalYear($pdoLitige,$fLitige[0]['galec'],$yearNDeux);

$coutNDeux=$coutNDeux['mtMag']+$coutNDeux['mtfourn']+$coutNDeux['mttransp']+$coutNDeux['mtassur'];






if($fLitige[0]['flag_valo']==2)
{
	$valoMag='impossible de calculer la valorisation';
	$articleAZero='<i class="fas fa-info-circle text-main-blue pr-3"></i>Un des articles n\'a pas de tarif, veuillez cliquer sur le code article pour effectuer une recherche dans la base';

}


$firstDial=getFirstDial($pdoLitige);
function getInvPaletteDetail($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT * FROM palette_inv WHERE id_dossier = :id_dossier");
	$req->execute(array(
		':id_dossier'	=>$_GET['id']
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function sommeInvPalette($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT SUM(tarif) as valoInv, palette FROM palette_inv WHERE id_dossier = :id_dossier");
	$req->execute(array(
		':id_dossier'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function sommePaletteCde($pdoLitige)
{
	$req=$pdoLitige->prepare("SELECT SUM(tarif) as valoCde, palette,pj FROM details WHERE id_dossier = :id_dossier");
	$req->execute(array(
		':id_dossier'	=>$_GET['id']
	));
	return $req->fetch(PDO::FETCH_ASSOC);
}

function searchPalette($pdoQlik,$palette)
{
	$req=$pdoQlik->prepare("SELECT * FROM statsventeslitiges WHERE palette LIKE :palette");
	$req->execute(array(
		':palette'	=>'%'.$palette.'%'
	));
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
// maj si recherche palette
function addPaletteInv($pdoLitige,$palette,$facture,$date_facture,$article,$ean,$dossier_gessica,$descr,$qte_cde,$tarif,$fournisseur, $cnuf)
{
	$req=$pdoLitige->prepare("INSERT INTO palette_inv (id_dossier, palette, facture, date_facture, article, ean, dossier_gessica, descr, qte_cde, tarif, fournisseur, cnuf, found)
		VALUES (:id_dossier, :palette, :facture, :date_facture, :article, :ean, :dossier_gessica, :descr, :qte_cde, :tarif, :fournisseur, :cnuf, :found)");
	$req->execute(array(
		':id_dossier'		=>$_GET['id'],
		':palette'			=>$palette,
		':facture'			=>$facture,
		':date_facture'	=>$date_facture,
		':article'			=>$article,
		':ean'				=>$ean,
		':dossier_gessica'	=>$dossier_gessica,
		':descr'			=>$descr,
		':qte_cde'			=>$qte_cde,
		':tarif'			=>$tarif,
		':fournisseur'		=>$fournisseur,
		':cnuf'			=>$cnuf,
		':found'			=>1,

	));
	return $req->rowCount();
}


function updateCommission($pdoLitige,$etat)
{
	$req=$pdoLitige->prepare("UPDATE dossiers SET commission = :commission, date_commission= :date_commission WHERE id= :id");
	$req->execute([
		':commission'	=>$etat,
		':date_commission'	=>date('Y-m-d H:i:s'),
		':id'		=>$_GET['id']

	]);
	return $req->rowCount($pdoLitige);
}

function addAction($pdoLitige, $idContrainte){
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier, libelle, id_contrainte, id_web_user, date_action) VALUES (:id_dossier, :libelle, :id_contrainte, :id_web_user, :date_action)");
	$req->execute([
		':id_dossier'		=>$_GET['id'],
		':libelle'			=>$_POST['cmt'],
		':id_contrainte'	=>$idContrainte,
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_action'		=>date('Y-m-d H:i:s'),
	]);
	return $req->rowCount();
}






// $name=getMagName($pdoUser, $dial['id_web_user']);
$infoMag=getMagName($pdoUser, $fLitige[0]['id_web_user']);

if(isset($_POST['validate']))
{
	if($_SESSION['id_web_user'] !=959 && $_SESSION['id_web_user'] !=981)
	{
		header('Location:bt-detail-litige.php?notallowed&id='.$_GET['id']);

	}
	elseif(!empty($_POST['cmt']))
	{

		$action=addAction($pdoLitige, 3);
		if($action==1){
			$result=updateCommission($pdoLitige,1);
		}
		else{
			$errors[]="impossible d'ajouter le commentaire";
		}
		if($result==1)
		{
			header('Location:bt-detail-litige.php?id='.$_GET['id']);

		}
		else{
			$errors[]="impossible de mettre le statut à jour";
		}
	}
	else{
		$errors[]="Veuillez saisir un commentaire";
	}
}
if(isset($_GET['notallowed'])){
	$errors[]="Vous n'êtes pas autorisé à modifier le statut 'validé en commission'";
}

if(isset($_POST['annuler']))
{
	header('Location:bt-detail-litige.php?id='.$_POST['iddossier']);

}
// calcul valo totale uniquement si inversion de palette et palette reçue non toruvéé au moment de la déclaration
function getSumLitige($pdoLitige){
	$req=$pdoLitige->prepare("SELECT sum(valo_line) as sumValo, dossiers.valo, id_reclamation FROM details LEFT JOIN dossiers ON details.id_dossier= dossiers.id WHERE details.id_dossier= :id");
	$req->execute([
		':id'		=>$_GET['id']
	]

);
	return $req->fetch(PDO::FETCH_ASSOC);
}


function getSumPaletteRecu($pdoLitige){
	$req=$pdoLitige->prepare("SELECT sum(tarif) as sumValo FROM palette_inv  WHERE palette_inv.id_dossier= :id");
	$req->execute([
		':id'		=>$_GET['id']
	]

);
	return $req->fetch(PDO::FETCH_ASSOC);
}

function updateValoDossier($pdoLitige,$sumValo){
	$req=$pdoLitige->prepare("UPDATE dossiers SET valo= :valo WHERE id= :id");
	$req->execute([
		':valo'			=>$sumValo,
		':id'			=>$_GET['id']
	]);
	return $req->rowCount();
}



function getPagination($pdoLitige){
	$req=$pdoLitige->query("SELECT id FROM dossiers ORDER BY dossier ASC");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_COLUMN);
}
function addSerials($pdoLitige,$idDetail,$values){
	$req=$pdoLitige->prepare("UPDATE details SET serials=:serials WHERE id=:id");
	$req->execute([
		':id'		=>$idDetail,
		':serials' => stripslashes($values)
	]);
	return $req->rowCount();
}


if(isset($_POST['submit-serials'])){

	$idDetail="";
	foreach ($_POST as $key => $value) {
		if(strpos($key,"iddetail")!==false){
			echo "true";
			$idDetail=explode("-",$key)[1];
			echo $idDetail;
			$added=addSerials($pdoLitige, $idDetail, $_POST[$key]);
			if($added>=1){
				$successStr='success=sn';
				unset($_POST);
				header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&".$successStr,true,303);
			}
		}
	}

}


if(isset($_GET['successpal']))
{
	$success[]='la palette a  été trouvée et la base de donnée mise à jour';
}
if(isset($_GET['success'])){
	$arrSuccess=[
		'sn'		=>"Les numéros de séries ont bien été enregistrés"
	];
	$success[]=$arrSuccess[$_GET['success']];

}

$pagination=getPagination($pdoLitige);
$page=array_search($_GET['id'], $pagination);
$last=$pagination[count($pagination)-1];

if($_GET['id']!=$last){
	$next=$pagination[$page+1];
}
else{
	$next=$last;
}

if($_GET['id']!=1)
{
	$prev=$pagination[$page-1];
}
else{
	$prev=0;
}

	// echo "<pre>";
	// print_r($fLitige);
	// echo '</pre>';
// $reclameN=getSumDeclare($pdoBt,$listLitige[0]['galec'],$yearN);



//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<div class="row pb-3">
		<div class="col-7">
			<?php
			echo '<table class="table text-right table-bordered ">';
			echo '<tr class="bg-blue">';
			echo '<td></td>';
			echo '<td>'.$yearN.'</td>';
			echo '<td>'.$yearNUn .'</td>';
			echo '<td>'.$yearNDeux .'</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td class="text-main-blue heavy"> Chiffres d\'affaire :</td>';
			echo '<td>'.number_format((float)$financeN['CA_Annuel'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$financeNUn['CA_Annuel'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$financeNDeux['CA_Annuel'],2,'.',' ').'&euro;</td>';
			echo '</tr>';



			echo '<tr>';
			echo '<td class="text-main-blue heavy">Réclamé :</td>';
			echo '<td>'.number_format((float)$reclameN['sumValo'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$reclameNUn['sumValo'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$reclameNDeux['sumValo'],2,'.',' ').'&euro;</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td class="text-main-blue heavy">Remboursé :</td>';
			echo '<td>'.number_format((float)$rembourseN['sumMtMag'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$rembourseNUn['sumMtMag'],2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$rembourseNDeux['sumMtMag'],2,'.',' ').'&euro;</td>';
			echo '</tr>';

			echo '<td class="text-main-blue heavy"> Coût BTlec</td>';
			echo '<td>'.number_format((float)$coutN,2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$coutNUn,2,'.',' ').'&euro;</td>';
			echo '<td>'.number_format((float)$coutNDeux,2,'.',' ').'&euro;</td>';
			echo '</tr>';
			echo '</table>';
			?>
		</div>
		<div class="col">
			<p class="text-right pt-3">
				<?php if ($prev!=0): ?>
					<a href="bt-detail-litige.php?id=<?=$prev?>" class="grey-link"><i class="fas fa-angle-left pr-2 pt-2"></i>Litige précédent</a>
				<?php endif ?>
				<?php if ($next!=$last): ?>
					<a href="bt-detail-litige.php?id=<?=$next?>" class="grey-link"><i class="fas fa-angle-right pl-5 pr-2 pt-1"></i>Litige suivant</a></p>

				<?php endif ?>
			</div>
			<div class="col-auto  pt-3">
				<p class="text-right"><a href="bt-litige-encours.php" class="btn btn-primary">Retour</a></p>
			</div>
		</div>


		<div class="row  pb-3 align-items-center">

			<div class="col">
				<h1 class="text-main-blue ">
					Dossier N° <?= $fLitige[0]['dossier']?>
				</h1>
			</div>

			<div class="col">
				<p class="text-right text-main-blue bigger my-auto">
					déclaration du <?=$fLitige[0]['datecrea'] ?>
				</p>
			</div>
			<div class="col-auto">
				<?php
				if($fLitige[0]['vingtquatre']==1)
				{
					$vingtquatre='<img src="../img/litiges/2448_40.png">';

				}
				else
				{
					$vingtquatre="";
				}
				echo $vingtquatre;
				?>
			</div>
		</div>

		<div class="row no-gutters">
			<div class="col">
				<?php
				include('../view/_errors.php');
				?>
			</div>
			<div class="col-lg-1 col-xxl-2"></div>
		</div>
		<!-- info mag -->
		<div class="row mb-3">
			<div class="col-lg-2"></div>
			<div class="col">
				<div class="row bg-alert-primary border light-shadow no-gutters">
					<div class="col-auto my-auto">
						<div class="align-middle"><img src="../img/litiges/mag-sm.jpg"></div>
					</div>
					<div class="col pl-5">
						<div class="row">
							<div class="col">
								<h4 class="khand pt-2"><a href="stat-litige-mag.php?galec=<?=$fLitige[0]['galec']?>"><?= $fLitige[0]['mag'] .' - '.$fLitige[0]['btlec'].' ('.$fLitige[0]['galec'].')' ?></a></h4>
							</div>
							<div class="col">
								<h4 class="khand pt-2 text-right pr-3"><?=$fLitige[0]['centrale']?></h4>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<span class="heavy">Interlocuteur : </span><?= $fLitige[0]['nom'] ?>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<span class="heavy">Commentaire : </span><?= $firstDial['msg'] ?>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="col-lg-2"></div>
		</div>

		<div class="bg-separation"></div>

		<div class="row mt-3">
			<div class="col">
				<div class="row">
					<div class="col">
						<h5 class="khand text-main-blue pb-3">Intervenir sur le dossier :</h5>
					</div>
				</div>
				<div class="row">
					<!-- <div class="col"></div> -->
					<div class="col-auto">
						<p class="text-right"><a href="bt-analyse.php?id=<?=$_GET['id']?>" class="btn btn-primary"><i class="fas fa-chart-area pr-3"></i>Analyser litige</a></p>
					</div>

					<div class="col-auto">
						<p class="text-right"><a href="bt-action-add.php?id=<?=$_GET['id']?>" class="btn btn-red"><i class="fas fa-plus-square pr-3"></i>Ajouter une action</a></p>
					</div>
					<div class="col-auto">
						<p class="text-right"><a href="bt-contact.php?id=<?=$_GET['id']?>" class="btn btn-kaki"><i class="fas fa-comment pr-3"></i>Contacter le magasin</a></p>
					</div>
					<div class="col-auto">
						<p class="text-right"><a href="bt-info-litige.php?id=<?=$_GET['id']?>" class="btn btn-yellow"><i class="fas fa-highlighter pr-3"></i>Ajouter des informations</a></p>
					</div>
					<div class="col-auto">
						<p class="text-right"><a href="bt-generate-fiche.php?id=<?=$_GET['id']?>" class="btn btn-black" target="_blank"><i class="fas fa-print pr-3"></i>Imprimer</a></p>
					</div>

					<!-- <div class="col"></div> -->
				</div>
			</div>

		</div>
		<div class="bg-separation"></div>
		<!-- infos produit -->
		<?php

	// affiche soit le tableau de detail des produits soit le tableau d'inversion de palette
		if($fLitige[0]['id_reclamation']==7)
		{
	// traitement pour affichage détail palette
			$detailInv=false;
			$detailCde=false;
			$majrecherchepalette='';
			$pj='';
			$invPal = sommeInvPalette($pdoLitige);
			$cdePal=sommePaletteCde($pdoLitige);
			if(isset($_GET['inv']))
			{
				$invPalette=getInvPaletteDetail($pdoLitige);
				$detailInv=true;
			}
			if(isset($_GET['cde']))
			{
		//on réutilise fLitige
				$detailCde=true;
			}
		// tableau palette + pj
			if($cdePal['pj']!='')
			{
				$pj=createFileLink($cdePal['pj']);

			}

		// si la palette n'a pas été trouvée au moment de la déclaration, l'utilisateur voit un btn rechercher apparaitre , l'adresse du bouton contient le paramètre search
			if(isset($_GET['search']))
			{
				$newFoundPalette=searchPalette($pdoQlik, $fLitige[0]['inv_palette']);
				if(empty($newFoundPalette))
				{
					$majrecherchepalette='<div class="alert alert-danger">la palette n\'a pas été trouvée</div>';
				}
				else
				{
					foreach ($newFoundPalette as $pal)
					{
						$paletteFound=addPaletteInv($pdoLitige,$pal['palette'],$pal['facture'], $pal['date_mvt'],$pal['article'],$pal['gencod'],$pal['dossier'],$pal['libelle'],$pal['qte'],$pal['tarif'],$pal['fournisseur'],$pal['cnuf']);
						if($paletteFound!=1)
						{
							$errors[]="Problème d'enregistrement lors de l'ajout de la palette reçue";
						}
						else
						{
						// il faut recalculer la valo totale
							$sumLitige=getSumLitige($pdoLitige);
							$sumRecu=getSumPaletteRecu($pdoLitige);
							$sumCde=$sumLitige['sumValo'];
							$sumRecu=$sumRecu['sumValo'];
							$sumValo=$sumCde -$sumRecu;
							$update=updateValoDossier($pdoLitige,$sumValo);
							$majrecherchepalette='<div class="alert alert-success">la palette a été trouvée et la base de donnée mise à jour. Cliquez <a href="?id='.$_GET['id'].'">ici pour rafraichir la page</a></div>';
						}
					}

				}
			}
			include('dt-invpalette.php');

		}

		else
		{
			include('dt-prod.php');
		}

		?>


		<div class="bg-separation"></div>

		<!-- analyse service, imputation, typo, etat, analyse, conclusion -->
		<div class="row mt-3">
			<div class="col">
				<h5 class="khand text-main-blue pb-3">Analyse :</h5>
			</div>
		</div>
		<div class="row mb-3">
			<div class="col">
				<!-- start table -->
				<table class="table light-shadow">
					<thead class="thead-dark">
						<tr>
							<th>Nature</th>
							<th>Imputation</th>
							<th>Typologie</th>
							<th>Etat</th>
							<th>Analyse</th>
							<th>Réponse</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?=$analyse['gt']?></td>
							<td><?=$analyse['imputation']?></td>
							<td><?=$analyse['typo']?></td>
							<td><?=$analyse['etat']?></td>
							<td><?=$analyse['analyse']?></td>
							<td><?=$analyse['conclusion']?></td>
						</tr>
					</tbody>
				</table>
				<!-- ./table -->
			</div>
		</div>

		<div class="bg-separation"></div>

		<!-- infos -->
		<div class="row mt-3">
			<div class="col">
				<h5 class="khand text-main-blue pb-3">Informations :</h5>

			</div>
		</div>


		<div class="row">
			<div class="col bg-alert mr-3 border-kaki">
				<div class="row">
					<div class="col text-center"><img src="../img/litiges/ico-entrepot.png"></div>

				</div>
				<div class="row">
					<div class="col-5 text-kaki">Préparateur :</div>
					<div class="col "><?=$infos['fullprepa']?></div>
				</div>
				<div class="row">
					<div class="col-5 text-kaki">Date prépa :</div>
					<div class="col "><?=$infos['dateprepa']?></div>
				</div>
				<div class="row">
					<div class="col-5 text-kaki">Contrôleur :</div>
					<div class="col "><?=$infos['fullctrl']?></div>
				</div>
				<div class="row">
					<div class="col-5 text-kaki">Chargeur :</div>
					<div class="col "><?=$infos['fullchg']?></div>
				</div>
				<div class="row">
					<div class="col-5 text-kaki">Contrôle stock : </div>
					<div class="col "><?=$ctrl?></div>
				</div>
			</div>
			<div class="col bg-alert mr-3  border-yellow">
				<div class="row">
					<div class="col text-center"><img src="../img/litiges/ico-transp.png"></div>
				</div>
				<div class="row">
					<div class="col text-yellow">Transporteur :</div>
					<div class="col"><?=$infos['transporteur']?></div>
				</div>
				<div class="row">
					<div class="col text-yellow">Affreteur :</div>
					<div class="col"><?=$infos['affrete']?></div>
				</div>
				<div class="row">
					<div class="col text-yellow">Transité par :</div>
					<div class="col"><?=$infos['transit']?></div>
				</div>
			</div>
			<div class="col bg-alert border-reddish">
				<div class="row">
					<div class="col text-center"><img src="../img/litiges/ico-fact.png"></div>

				</div>
				<div class="row">
					<div class="col-8 text-red">Réglement transporteur :</div>
					<div class="col text-right"><?=number_format((float)$infos['mt_transp'],2,'.','')?>&euro;</div>
				</div>
				<div class="row">
					<div class="col-8 text-red">Réglement assurance :</div>
					<div class="col text-right"><?= number_format((float)$infos['mt_assur'],2,'.','')?>&euro;</div>
				</div>
				<div class="row">
					<div class="col-8 text-red">Réglement fournisseur :</div>
					<div class="col text-right"><?= number_format((float)$infos['mt_fourn'],2,'.','')?>&euro;</div>
				</div>
				<div class="row">
					<div class="col-8 text-red">Avoir magasin :</div>
					<div class="col text-right"><?= number_format((float)$infos['mt_mag'],2,'.','')?>&euro;</div>
				</div>
				<div class="row">
					<div class="col-8 text-red">Coût du litige :</div>
					<div class="col text-right"><?= number_format((float)$coutTotal,2,'.','') ?>&euro;</div>
				</div>

			</div>

		</div>
		<div class="bg-separation"></div>

		<div class="row mt-3">
			<div class="col">
				<h5 class="khand text-main-blue pb-3">Actions :</h5>
			</div>
		</div>
		<?php if ($fLitige[0]['commission']==0): ?>
			<div class="row">
				<div class="col stamps pb-3">
					Cliquez sur <a href="#hidden"  class="stamps" ><i class="fas fa-user-check stamp pending"></i></a> si le dossier a été statué en commission
				</div>
			</div>


			<div class="row mb-3" id="hidden">
				<div class="col">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?> " method="post">
						<div class="form-group">
							<label class="text-main-blue">Commentaire :</label>
							<textarea class="form-control" name="cmt" rows="3" id="cmtarea"></textarea>
						</div>
						<div class="form-group">
							<input type="hidden" class="form-control" name="iddossier" id="hiddeninput" value="<?=$_GET['id']?>">
						</div>
						<button class="btn btn-black" name="validate">Valider</button>
						<button class="btn btn-red" id="annuler">Annuler</button>

					</form>
				</div>
			</div>
		<?php endif ?>


		<div class="row">
			<div class="col">
				<table class="table light-shadow">
					<thead class="thead-dark">
						<tr>
							<th>date</th>
							<th>Par</th>
							<th>Action</th>
							<th>PJ</th>
						</tr>
					</thead>
					<tbody>
						<?php


						if(isset($actionList) && count($actionList)>0)
						{
							foreach ($actionList as $action)
							{
								if($action['pj']!='')
								{
									$pj=createFileLink($action['pj']);
								}
								else
								{
									$pj='';
								}
								echo '<tr>';
								echo'<td>'.$action['dateFr'].'</td>';
								echo'<td>'. UserHelpers::getFullname($pdoUser, $action['id_web_user']) .'</td>';

								echo'<td>'.$action['libelle'].'</td>';
								echo'<td>'.$pj.'</td>';
								echo '</tr>';
							}

						}
						else
						{
							echo '<tr><td colspan="3">Aucune Action</td></tr>';
						}

						?>

					</tbody>
				</table>
			</div>

		</div>
		<div class="bg-separation"></div>

		<div class="row mt-3">
			<div class="col">
				<h5 class="khand text-main-blue pb-3">Echange avec le magasin</h5>

			</div>
		</div>

		<div class="row">
			<div class="col">

				<?php
				if(isset($dials) && count($dials)>0)
				{
					include('echanges.php');

				}
				?>


			</div>
		</div>

		<?php




		?>



		<!--  -->
		<!-- MODAL SN -->
		<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">

					<div class="modal-body">
						<h5 class="text-center text-violet">Numéros de séries :</h5>
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post">
							<div class="form-group">
								<textarea class="form-control" name=""></textarea>
							</div>

							<div class="text-right">
								<button class="btn btn-primary" name="submit-serials">Enregistrer</button>

							</div>



						</form>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-violet" data-dismiss="modal">Fermer</button>
					</div>
				</div>
			</div>
		</div>

		<!--  -->








	</div>



	<script type="text/javascript">

		$(document).ready(function(){
			$('#largeModal').on('show.bs.modal', function (e) {
				var rowid = $(e.relatedTarget).data('id');
				console.log(rowid);
				$('textarea').attr('name', "iddetail-"+rowid);
				if(rowid){
					$.ajax({
						type:'POST',
						url:'bt-detail-serial.php',
						data:'idprod='+rowid,
						success:function(html){
							$('textarea').val(html);
							console.log(html);
						}
					});
				}



			});
		//
		var url = window.location + '';
		var splited=url.split("?id=");
		if(splited[1]==undefined){
			var line='';
		}
		else{
			var line=splited[1];
		}

		$('.stamps').on('click',function(){
			console.log(line);
			$('#hiddeninput').val(line);
			$('#hidden').css("display","block");
			// $('#modal1').removeAttr('aria-hidden');
				// $('#modal1').attr('aria-modal', true);
				$('#cmtarea').focus();
			// $("tr#"+line).addClass("anim");
		});
		$('#annuler').on('click', function(e){
			e.preventDefault();
			$('#hidden').css("display","none");


		});



	});



</script>




<?php

require '../view/_footer-bt.php';

?>