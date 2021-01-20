<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

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



if(isset($_SESSION['form-data'])){
	$paramList=[];
	// unset($_SESSION['form-data']);
	unset($_SESSION['form-data-deux']);
	if(isset($_SESSION['form-data']['date_start']) && !empty($_SESSION['form-data']['date_start'])){
		$dateStart=$_SESSION['form-data']['date_start'];
	}else{
		$dateStart="2019-01-01 00:00:00";
	}
	if(isset($_SESSION['form-data']['date_end']) && !empty($_SESSION['form-data']['date_end'])){
		$dateEnd=$_SESSION['form-data']['date_end'];
	}else{
		$dateEnd=date('Y-m-d H:i:s');
	}
	$paramDate="date_crea BETWEEN '".$dateStart ."'  AND '".$dateEnd."'";
	$paramList[]=$paramDate;


	if(isset($_SESSION['form-data']['etat'])){
		$paramEtat=join(' OR ', array_map(function($value){return 'id_etat='.$value;},$_SESSION['form-data']['etat']));
	}else{
		$paramEtat='';
	}
	$paramList[]=$paramEtat;

	if(isset($_SESSION['form-data']['centrale'])){
		$paramCentrale=join(' OR ', array_map(function($value){return 'centrale='.$value;},$_SESSION['form-data']['centrale']));
	}else{
		$paramCentrale='';
	}
	$paramList[]=$paramCentrale;
	// $listLitige=getListLitige($pdoLitige);
}
if (isset($_SESSION['form-data-deux'])) {
	if(isset($_SESSION['form-data-deux']['search_strg']) && !isset($_SESSION['form-data-deux']['article']) && !isset($_SESSION['form-data-deux']['btlec']) && !isset($_SESSION['form-data-deux']['galec'])){
		$paramStrg= "concat(dossiers.dossier,magasin.mag.deno) LIKE '%".$_SESSION['form-data-deux']['search_strg'] ."%'";
	}elseif(isset($_SESSION['form-data-deux']['search_strg']) && isset($_SESSION['form-data-deux']['article'])){
		$paramStrg= "details.article LIKE '%".$_SESSION['form-data-deux']['search_strg'] ."%'";
	}elseif(isset($_SESSION['form-data-deux']['search_strg']) && isset($_SESSION['form-data-deux']['btlec'])){
		$paramStrg= "magasin.mag.id =".$_SESSION['form-data-deux']['search_strg'];
	}elseif(isset($_SESSION['form-data-deux']['search_strg']) && isset($_SESSION['form-data-deux']['galec'])){
		$paramStrg= "magasin.mag.galec=".$_SESSION['form-data-deux']['search_strg'] ;
	}else{
		$paramStrg="";
	}
	$paramList[]=$paramStrg;
}
if(isset($_SESSION['filter-data'])){
	if(isset($_SESSION['filter-data']['vingtquatre']) && $_SESSION['filter-data']['vingtquatre']==1){
		$paramVingtQuatre= ' vingtquatre = 1 OR esp = 1';
	}elseif (isset($_SESSION['filter-data']['vingtquatre']) && $_SESSION['filter-data']['vingtquatre']==0){
		$paramVingtQuatre= " vingtquatre = 0 AND esp = 0";
	}else{
		$paramVingtQuatre= '';
	}
	$paramList[]=$paramVingtQuatre;

	if (isset($_SESSION['filter-data']['pending']) && $_SESSION['filter-data']['pending']==1) {
		$paramCommission= " commission=1 ";
	}elseif (isset($_SESSION['filter-data']['pending']) && $_SESSION['filter-data']['pending']==0){
		$paramCommission= " commission=0 ";
	}else{
		$paramCommission= "";
	}

	$paramList[]=$paramCommission;

	if (isset($_SESSION['filter-data']['occasion']) && $_SESSION['filter-data']['occasion']==1) {
		$paramOccasion= " occasion=1 ";
	}elseif (isset($_SESSION['filter-data']['occasion']) && $_SESSION['filter-data']['occasion']==0){
		$paramOccasion= " occasion=0 ";
	}else{
		$paramOccasion= "";
	}
	$paramList[]=$paramOccasion;

}



$litigeQuery="SELECT *, dossiers.id as id_main, magasin.mag.id as btlec
FROM dossiers
LEFT JOIN details ON dossiers.id=details.id_dossier
LEFT JOIN magasin.mag ON dossiers.galec=magasin.mag.galec
WHERE ";
$litigeParam="(id_etat != 1 AND id_etat != 20)|| commission != 1";
$litigeMod="ORDER BY dossiers.dossier DESC";


// requete par défaut
// $dateStart=(new DateTime('first day of january this year'))->format('Y-m-d H:i:s');
// $dateEnd=date('Y-m-d H:i:s');



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