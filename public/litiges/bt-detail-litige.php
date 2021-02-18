<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

unset($_SESSION['goto']);


require 'echanges.fn.php';
require('../../Class/UserHelpers.php');
require('../../Class/MagHelpers.php');
require('../../Class/LitigeDao.php');
require('../../Class/LitigeDialDao.php');
require('../../Class/OccHelpers.php');

//------------------------------------------------------
//			INFOS
//------------------------------------------------------
// 0=pas ajoutée, 1 ajoutée et correcte, 2 ajoutée mais incorrecte


//------------------------------------------------------
//			FONCTION
//------------------------------------------------------



function getFinance($pdoQlik, $btlec, $year){
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


function updateValo($pdoLitige, $valo,$flag){
	$req=$pdoLitige->prepare("UPDATE dossiers SET valo= :valo, flag_valo= :flag_valo WHERE id= :id");
	$req->execute(array(
		':id'		=>$_GET['id'],
		':valo'		=>$valo,
		':flag_valo'	=>$flag
	));
	return $req->rowCount();
}
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

$litigeDao=new LitigeDao($pdoLitige);
$dialDao=new LitigeDialDao($pdoLitige);
$infoLitige=$litigeDao->getLitigeDossierDetailReclamMagEtatById($_GET['id']);

$firstDial=$litigeDao->getFirstDial($_GET['id']);
$infos=$litigeDao->getInfos($_GET['id']);
$analyse=$litigeDao->getAnalyse($_GET['id']);
$actionList=$litigeDao->getAction($_GET['id']);

$coutTotal=$infos['mt_transp']+$infos['mt_assur']+$infos['mt_fourn']+$infos['mt_mag'];
$arMagOcc=MagHelpers::getListMagOcc($pdoMag);


if($infos['ctrl_ok']==0){
	$ctrl="non contrôlé";
}
elseif($infos['ctrl_ok']==1){
	$ctrl="fait";

}elseif($infos['ctrl_ok']==2){
	$ctrl="demandé";
}

if($coutTotal!=0){
	$coutTotal=number_format((float)$coutTotal,2,'.','');
}

$articleAZero='';

$yearN=date('Y');
$yearNUn= date("Y",strtotime("-1 year"));
$yearNDeux= date("Y",strtotime("-2 year"));

$financeN=getFinance($pdoQlik,$infoLitige[0]['btlec'],$yearN);
$financeNUn=getFinance($pdoQlik,$infoLitige[0]['btlec'],$yearNUn);
$financeNDeux=getFinance($pdoQlik,$infoLitige[0]['btlec'],$yearNDeux);
$reclameN=getSumDeclare($pdoLitige,$infoLitige[0]['galec'],$yearN);
$reclameNUn=getSumDeclare($pdoLitige,$infoLitige[0]['galec'],$yearNUn);
$reclameNDeux=getSumDeclare($pdoLitige,$infoLitige[0]['galec'],$yearNDeux);

$rembourseN=getMtMag($pdoLitige,$infoLitige[0]['galec'],$yearN);
$rembourseNUn=getMtMag($pdoLitige,$infoLitige[0]['galec'],$yearNUn);
$rembourseNDeux=getMtMag($pdoLitige,$infoLitige[0]['galec'],$yearNDeux);

$coutN=getCoutTotalYear($pdoLitige,$infoLitige[0]['galec'],$yearN);
$coutN=$coutN['mtMag']+$coutN['mtfourn']+$coutN['mttransp']+$coutN['mtassur'];
$coutNUn=getCoutTotalYear($pdoLitige,$infoLitige[0]['galec'],$yearNUn);
$coutNUn=$coutNUn['mtMag']+$coutNUn['mtfourn']+$coutNUn['mttransp']+$coutNUn['mtassur'];

$coutNDeux=getCoutTotalYear($pdoLitige,$infoLitige[0]['galec'],$yearNDeux);

$coutNDeux=$coutNDeux['mtMag']+$coutNDeux['mtfourn']+$coutNDeux['mttransp']+$coutNDeux['mtassur'];


if($infoLitige[0]['flag_valo']==2){
	$valoMag='impossible de calculer la valorisation';
	$articleAZero='<i class="fas fa-info-circle text-main-blue pr-3"></i>Un des articles n\'a pas de tarif, veuillez cliquer sur le code article pour effectuer une recherche dans la base';

}



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



if(isset($_POST['submit-serials'])){
	$idDetail="";
	foreach ($_POST as $key => $value) {
		if(strpos($key,"iddetail")!==false){
			$idDetail=explode("-",$key)[1];
			$added=addSerials($pdoLitige, $idDetail, $_POST[$key]);
			if($added>=1){
				$successStr='success=sn';
				unset($_POST);
				header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&".$successStr,true,303);
			}
		}
	}

}

if(isset($_POST['not_read'])){
	if (UserHelpers::isUserAllowed($pdoUser,['94'])){
		$dialDao->updateRead($_POST['id_dial'],0);
		header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."#".$_POST['id_dial']);
	}else{
		$errors[]="vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
	}
}
if(isset($_POST['read'])){
	if (UserHelpers::isUserAllowed($pdoUser,['94'])){
		$dialDao->updateRead($_POST['id_dial'],1);
		header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."#".$_POST['id_dial']);
	}else{
		$errors[]="vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
	}
}



if(isset($_POST['not_read_action'])){

	if (UserHelpers::isUserAllowed($pdoUser,['94']) || $_SESSION['id_web_user']==1402){
		$dialDao->updateReadAction($_POST['id_action'],0);
		header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."#".$_POST['id_action']);
	}else{
		$errors[]="vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
	}
}
if(isset($_POST['read_action'])){


	if (UserHelpers::isUserAllowed($pdoUser,['94'])  || $_SESSION['id_web_user']==1402){
		$dialDao->updateReadAction($_POST['id_action'],1);
		header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."#".$_POST['id_action']);
	}else{
		$errors[]="vos droits ne vous permettent pas d'utiliser cette fonctionnalité";
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

if($_GET['id']!=1){
	$prev=$pagination[$page-1];
}
else{
	$prev=0;
}

	// echo "<pre>";
	// print_r($infoLitige);
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

	<?php
	include('bt-detail-litige\01-view-ca-pagenav.php');
	include('bt-detail-litige\02-view-head-dossier.php');
	?>






	<div class="bg-separation"></div>
	<div class="row no-gutters">
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>

	<?php
	include('bt-detail-litige\03-view-btn.php');
	?>


	<div class="bg-separation"></div>
	<!-- infos produit -->
	<?php

	// affiche soit le tableau de detail des produits soit le tableau d'inversion de palette
	if($infoLitige[0]['id_reclamation']==7){
		include('bt-detail-litige\04-view-invpalette.php');
	}else{
		include('bt-detail-litige\04-view-prods.php');
	}

	?>


	<div class="bg-separation"></div>
	<?php
	include('bt-detail-litige\05-view-analyse.php');
	include('bt-detail-litige\06-view-info.php');
	include('bt-detail-litige\07-view-action.php');
	include('bt-detail-litige\08-view-echanges.php');


	?>

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