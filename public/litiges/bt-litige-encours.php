<?php
// session_cache_limiter('private_no_expire');
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



require('../../Class/FormHelpers.php');
require('../../Class/MagHelpers.php');
require('../../Class/LitigeDao.php');
require('../../Class/LitigeHelpers.php');


// unset($_SESSION['form-data-deux']);
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getEtat($pdoLitige){
	$req=$pdoLitige->prepare("SELECT etat,id FROM etat WHERE mask=0 ORDER BY etat");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}

function makeQuery($pdoLitige, $query, $param, $mod=null){
	if(!isset($mod)){
		$mod="";
	}
	$fullQuery=$query. ' ' .$param. ' '.$mod;
	echo $fullQuery;
	echo "<br>";
	$req=$pdoLitige->query($fullQuery);
	// return $req->errorInfo();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}



function getListVideo($pdoLitige,$idContrainte){
	$req=$pdoLitige->prepare("SELECT id_dossier, id_contrainte FROM action WHERE id_contrainte= :id_contrainte");
	$req->execute([
		':id_contrainte' =>$idContrainte
	]);
	return $req->fetchAll(PDO::FETCH_KEY_PAIR);
}




function getSumValo($pdoLitige, $listLitige){
	$valoTotal=0;
	foreach ($listLitige as $key => $litige) {
		$valoTotal+=$litige['valo'];
	}
	return $valoTotal;
}


function updateCommission($pdoLitige,$iddossier, $etat){
	$req=$pdoLitige->prepare("UPDATE dossiers SET commission = :commission, date_commission= :date_commission WHERE id= :id");
	$req->execute([
		':commission'	=>$etat,
		':date_commission'	=>date('Y-m-d H:i:s'),
		':id'		=>$iddossier

	]);
	return $req->rowCount($pdoLitige);
}



function addAction($pdoLitige, $idContrainte){
	$req=$pdoLitige->prepare("INSERT INTO action (id_dossier, libelle, id_contrainte, id_web_user, date_action) VALUES (:id_dossier, :libelle, :id_contrainte, :id_web_user, :date_action)");
	$req->execute([
		':id_dossier'		=>$_POST['iddossier'],
		':libelle'			=>$_POST['cmt'],
		':id_contrainte'	=>$idContrainte,
		':id_web_user'		=>$_SESSION['id_web_user'],
		':date_action'		=>date('Y-m-d H:i:s'),
	]);

	return $req->rowCount();
}

if(isset($_GET['notallowed'])){
	$errors[]="Vous n'êtes pas autorisé à modifier le statut 'validé en commission'";
}


include 'bt-litge-encours-formsearch-ex.php';
include 'bt-litige-encours-filtres-ex.php';
include 'bt-litige-encours-sessions-ex.php';



$litigeQuery="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp, dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission, dossiers.id_etat, dossiers.occasion, dossiers.id_robbery, dossiers.id_typo, magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat	FROM dossiers
LEFT JOIN etat ON id_etat=etat.id
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec WHERE";
$litigeParam="(id_etat != 1 AND id_etat != 20)|| commission != 1";
$litigeMod="ORDER BY dossiers.dossier DESC";



$statutQuery="SELECT  sum(valo) as valo, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat, etat.occ_etat FROM dossiers
LEFT JOIN etat ON id_etat=etat.id
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
WHERE";
$typoQuery="SELECT sum(valo) as valo, dossiers.id_typo, typo.typo, count(dossiers.id) as nbTypo FROM dossiers
LEFT JOIN typo ON id_typo=typo.id
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
WHERE";
// requete par défaut
$dateStart=(new DateTime('first day of january this year'))->format('Y-m-d H:i:s');
$dateEnd=date('Y-m-d H:i:s');

$statutParam="date_crea BETWEEN '$dateStart' AND '$dateEnd' GROUP BY etat ORDER BY occ_etat, etat.etat";
$typoParam="date_crea BETWEEN '$dateStart' AND '$dateEnd' GROUP BY id_typo ORDER BY typo";



if(isset($paramList)){
	$paramList=array_filter($paramList);
	$joinParam=function($value){
		if(!empty($value)){
			return '('.$value.')';
		}
	};

	$litigeParam=join(' AND ',array_map($joinParam,$paramList));

	$statutParam=$litigeParam." GROUP BY etat ORDER BY occ_etat, etat.etat";
	$typoParam=$litigeParam." GROUP BY id_typo ORDER BY typo";

		// 2 requetes types : une sur la table dossier "seule", une sur la table dossier jointe à la table article
	if(isset($_SESSION['form-data-deux']['article'])){
		$litigeQuery="SELECT dossiers.id as id_main, dossiers.dossier, DATE_FORMAT(date_crea, '%d-%m-%Y') as datecrea, dossiers.galec, dossiers.etat_dossier, dossiers.esp, dossiers.vingtquatre, dossiers.valo, dossiers.ctrl_ok, dossiers.commission, dossiers.id_etat, dossiers.occasion, dossiers.id_robbery, dossiers.id_typo, magasin.mag.deno, magasin.mag.centrale,  magasin.mag.id as btlec, etat.etat FROM dossiers
		LEFT JOIN details ON dossiers.id=details.id_dossier
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec WHERE";
		$litigeMod="GROUP BY dossiers.id ORDER BY dossiers.dossier DESC ";
		$statutQuery="SELECT  sum(valo) as valo, dossiers.id_etat, etat.etat, count(dossiers.id) as nbEtat, etat.occ_etat FROM dossiers
		LEFT JOIN etat ON id_etat=etat.id
		LEFT JOIN details ON dossiers.id=details.id_dossier
		WHERE";
		$typoQuery="SELECT sum(valo) as valo, dossiers.id_typo, typo.typo, count(dossiers.id) as nbTypo FROM dossiers LEFT JOIN typo ON id_typo=typo.id LEFT JOIN details ON dossiers.id=details.id_dossier WHERE";


	}
}

$litigeDao=new LitigeDao($pdoLitige);
$listLitige=makeQuery($pdoLitige, $litigeQuery, $litigeParam, $litigeMod);
$valoEtat=makeQuery($pdoLitige, $statutQuery, $statutParam);
$valoTypo=makeQuery($pdoLitige, $typoQuery, $typoParam);


$errors=[];
$success=[];

$arCentrale=MagHelpers::getListCentrale($pdoMag);

$nbLitiges=count($listLitige);
// foreach moins long
$valoTotalDefault=getSumValo($pdoLitige, $listLitige);
// $valoTotalEtat=getSumValo($pdoLitige, $valoEtat);

$listReclamations=$litigeDao->getReclamation();
$listVideoOk=getListVideo($pdoLitige, 7);
$listVideoko=getListVideo($pdoLitige, 6);
$arTypo=LitigeHelpers::listTypo($pdoLitige);
$arMagOcc=MagHelpers::getListMagOcc($pdoMag);
$sumValoMain=0;
$sumValoOcc=0;
$sumValoTypo=0;
$nbTotalDossierTypo=0;
$nbTotalDossierStatut=0;


include 'bt-litige-encours-statut-ex.php';



$listEtat=getEtat($pdoLitige);
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


	<?php include ('bt-litige-encours-header.php') ?>
	<?php include ('bt-litige-encours-formsearch.php') ?>

	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>

		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>


	<?php include ('bt-litige-encours-stats.php') ?>
	<?php include ('bt-litige-encours-filtres.php') ?>
	<?php include ('bt-litige-encours-table.php') ?>
	<?php include ('bt-litige-encours-statut-modal.php') ?>




	<!-- ./row -->
	<!-- ./row -->



</div>


<script type="text/javascript">

	$(document).ready(function(){
		var url = window.location + '';
		var splited=url.split("#");
		if(splited[1]==undefined)
		{
			var line='';
		}
		else if(splited.length==2)
		{
			var line=splited[1];
			$("tr#"+line).addClass("anim");
		}

		$("#main-check").click(function () {
			$('.cb-commission').prop('checked', this.checked);
		});
        // lorsque l'on décoche, on fait l'inverse
        $("#uncheck-code").click(function () {
        	$('.acdlec').removeAttr('checked');
        });




        $('.stamps').on('click',function(){
        	var line=$(this).attr("data")

        	console.log(line);
        	$('#hiddeninput').val(line);
        	$('#modal1').css("display","null");
        	$('#modal1').removeAttr('aria-hidden');
				// $('#modal1').attr('aria-modal', true);
				$('#cmtarea').focus();
			// $("tr#"+line).addClass("anim");
		});
        $('#annuler').on('click', function(){

        	$('#modal1').css("display","hidden");

        });

        $('.unvalidate').on('click', function(){
        	return confirm('Etes vous sûrs de vouloir passer le statut du dossier en non statué ?')
        });

    });



</script>

<?php

require '../view/_footer-bt.php';

?>