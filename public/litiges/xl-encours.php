<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

require_once '../../vendor/autoload.php';
require('../../Class/LitigeDao.php');
require('../../Class/LitigeHelpers.php');
require('../../Class/MagHelpers.php');

//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------
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

function arrayToString($ar, $index){
	if (isset($ar[$index])) {
		return $ar[$index];
	}
	return "";
}


$dateStart=(new DateTime('first day of january this year'))->format('Y-m-d H:i:s');
$dateEnd=date('Y-m-d H:i:s');

$litigeQuery="SELECT *, dossiers.id as id_main, magasin.mag.id as btlec
FROM dossiers
LEFT JOIN details ON dossiers.id=details.id_dossier
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
WHERE ";
$litigeParam="date_crea BETWEEN '$dateStart' AND '$dateEnd' ";

$litigeMod="ORDER BY dossiers.dossier DESC";







if(isset($paramList)){
	$paramList=array_filter($paramList);
	$joinParam=function($value){
		if(!empty($value)){
			return '('.$value.')';
		}
	};

	$litigeParam=join(' AND ',array_map($joinParam,$paramList));
}

$litigeDao=new LitigeDao($pdoLitige);
$listLitige=makeQuery($pdoLitige, $litigeQuery, $litigeParam, $litigeMod);

$arReclam=LitigeHelpers::listReclamationIncludingMasked($pdoLitige);
$arTypo=LitigeHelpers::listTypoAll($pdoLitige);
$arAffrete=LitigeHelpers::listAffreteAll($pdoLitige);
$arAnalyse=LitigeHelpers::listAnalyseAll($pdoLitige);
$arConclusion=LitigeHelpers::listConclusionAll($pdoLitige);
$arEquipe=LitigeHelpers::listEquipeAll($pdoLitige);
$arEtat=LitigeHelpers::listEtatAll($pdoLitige);
$arImputation=LitigeHelpers::listImputationAll($pdoLitige);
$arTransit=LitigeHelpers::listTransitAll($pdoLitige);
$arTransport=LitigeHelpers::listTransporteurAll($pdoLitige);
$arCentrale=MagHelpers::getListCentrale($pdoMag);



function nullToZero($value){
	if($value==null){
		$value=0;
	}
	return $value;
}



//------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$listDossierInvPalette=[];


include 'xl-inc.php';



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
	<h1 class="text-main-blue py-5 ">Main title</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>


	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>