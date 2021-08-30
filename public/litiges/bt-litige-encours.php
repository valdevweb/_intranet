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



require('../../Class/FormHelpers.php');
require('../../Class/MagHelpers.php');
require('../../Class/LitigeDao.php');
require('../../Class/LitigeHelpers.php');
require('../../Class/LitigeDialDao.php');


// unset($_SESSION['form-data-deux']);
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getEtat($pdoLitige){
	$req=$pdoLitige->prepare("SELECT etat,id FROM etat WHERE mask=0 ORDER BY etat");
	$req->execute();
	return $req->fetchAll(PDO::FETCH_ASSOC);
}
function arrayToString($ar, $index){
	if (isset($ar[$index])) {
		return $ar[$index];
	}
	return "";
}

function nullToZero($value){
	if($value==null){
		$value=0;
	}
	return $value;
}

function makeQuery($pdoLitige, $query, $param, $mod=null){
	if(!isset($mod)){
		$mod="";
	}
	$fullQuery=$query. ' ' .$param. ' '.$mod;
	// echo $fullQuery;
	// echo "<br>";
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
	return $req->rowCount();
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


include 'bt-litige-encours\01-data-formsearch.php';
include 'bt-litige-encours\02-data-filtres.php';
include 'bt-litige-encours\03-data-sessions.php';
include 'bt-litige-encours\03b-data-build-query.php';




$litigeDao=new LitigeDao($pdoLitige);
$listLitige=makeQuery($pdoLitige, $litigeQuery, $litigeParam, $litigeMod);
$valoEtat=makeQuery($pdoLitige, $statutQuery, $statutParam);
$valoTypo=makeQuery($pdoLitige, $typoQuery, $typoParam);
$dialDao=new LitigeDialDao($pdoLitige);


$errors=[];
$success=[];

$arCentrale=MagHelpers::getListCentrale($pdoMag);

$nbLitiges=count($listLitige);
// foreach moins long
$valoTotalDefault=getSumValo($pdoLitige, $listLitige);
// $valoTotalEtat=getSumValo($pdoLitige, $valoEtat);

$listReclamations=$litigeDao->getReclamation();
$listVideoOk=getListVideo($pdoLitige, 7);
$listVideoKo=getListVideo($pdoLitige, 6);


$arTypo=LitigeHelpers::listTypo($pdoLitige);
$arMagOcc=MagHelpers::getListMagOcc($pdoMag);
$unread=$dialDao->getUnreadDossierColumn();
$unreadActionSav=$dialDao->getUnreadActionSavColumn();


$sumValoMain=0;
$sumValoOcc=0;
$sumValoTypo=0;
$nbTotalDossierTypo=0;
$nbTotalDossierStatut=0;
$nbTotalDossierOcc=0;
$nbTotalDossierMain=0;
include 'bt-litige-encours\04-data-statut.php';



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


	<?php include ('bt-litige-encours\05-view-header.php') ?>
	<?php include ('bt-litige-encours\06-view-formsearch.php') ?>

	<div class="row">
		<div class="col-lg-1 col-xxl-2"></div>

		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1 col-xxl-2"></div>
	</div>


	<?php include ('bt-litige-encours\07-view-stats.php') ?>
	<?php include ('bt-litige-encours\08-view-filtres.php') ?>
	<?php include ('bt-litige-encours\09-view-table.php') ?>
	<?php include ('bt-litige-encours\10-view-statut-modal.php') ?>




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





        $('.stamps-filter').on('click',function(){
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