<?php
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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
require_once '../../Class/MagDbHelper.php';
require_once '../../Class/Mag.php';
require_once '../../Class/UserHelpers.php';
require_once '../../Class/Helpers.php';



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
addRecord($pdoStat,basename(__file__),'consultation', "base magasin", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

$magDbHelper=new MagDbHelper($pdoMag);
$listCentrale=$magDbHelper->getDistinctCentrale();
$listType=$magDbHelper->getListType();
$listCm=UserHelpers::getUserByService($pdoUser,17);
$listTypePair=$magDbHelper->getListTypePair();
$listCodeAcdlec=$magDbHelper->getListCodeAcdlec();








function checkChecked($value,$field){
	if(isset($_SESSION['mag_filters']) && isset($_SESSION['mag_filters'][$field])){
		if(in_array($value,$_SESSION['mag_filters'][$field])){
			return "checked";
		}
	}

	return "";
}


function arrayCentrale($listCentrale){
	foreach ($listCentrale as $key => $value) {
		$centrale[$value['id_centrale']]=$value['centrale_name'];
	}
	$centrale[0]="";
	return $centrale;
}

$centraleName=arrayCentrale($listCentrale);


//------------------------------------------------------
//			EXPLOIT
//------------------------------------------------------


//------------------------------------------------------
//			gestion de l'affichage par défaut
//------------------------------------------------------



if(isset($_POST['filter'])){

	if(isset($_POST['centraleSelected'])){
		$_SESSION['mag_filters']['centraleSelected']=$_POST['centraleSelected'];
		if(in_array(1,$_POST['centraleSelected'])){
			$paramCentrale='';
		}else{
			$paramCentrale=join(' OR ', array_map(function($value){return 'centrale='.$value;},$_POST['centraleSelected']));

		}
	}else{
		$_SESSION['mag_filters']['centraleSelected']=[];
		$paramCentrale='';
	}
	$paramList[]=$paramCentrale;

	if(isset($_POST['typeSelected'])){
		$_SESSION['mag_filters']['typeSelected']=$_POST['typeSelected'];
		$paramType=join(' OR ', array_map(function($value){return 'id_type='.$value;},$_POST['typeSelected']));

	}else{
		$_SESSION['mag_filters']['typeSelected']=[];
		$paramType='';
	}
	$paramList[]=$paramType;

	if(isset($_POST['acdlecSelected'])){
		$_SESSION['mag_filters']['acdlecSelected']=$_POST['acdlecSelected'];
		$paramAcdlec=join(' OR ', array_map(function($value){return 'acdlec_code='.$value;},$_POST['acdlecSelected']));

	}else{
		$_SESSION['mag_filters']['acdlecSelected']=[];
		$paramAcdlec='';
	}
	$paramList[]=$paramAcdlec;


	if(isset($_POST['sorti'])){
		$_SESSION['mag_filters']['sorti']=$_POST['sorti'];
		$paramClosed=join(' OR ', array_map(function($value){return 'sorti='.$value;},$_POST['sorti']));

	}else{
		$_SESSION['mag_filters']['sorti']=[];
		$paramClosed='';
	}
	$paramList[]=$paramClosed;

	if(isset($_POST['cmSelected'])){
		$_SESSION['mag_filters']['cmSelected']=$_POST['cmSelected'];
		$paramCm=join(' OR ', array_map(function($value){
			if($value=='NULL'){
				return 'id_cm_web_user IS '.$value;
			}
			return 'id_cm_web_user='.$value;
		},$_POST['cmSelected']));

	}else{
		$_SESSION['mag_filters']['cmSelected']=[];
		$paramCm='';

	}
	$paramList[]=$paramCm;
}

if(isset($_POST['clear_form'])){
	$_POST=[];
	header("Location: ".$_SERVER['PHP_SELF']);

}



if(isset($_POST['clear_filter'])){
	unset($_SESSION['mag_filters']);
	header('Location:'.$_SERVER['PHP_SELF']);
}


$joinParam=function($value){
	if(!empty($value)){
		return '('.$value.')';
	}
};
if(isset($paramList)){
	$paramList=array_filter($paramList);
	$params=join(' AND ',array_map($joinParam,$paramList));
	$params= "WHERE " .$params;
	$query="SELECT * FROM mag LEFT JOIN sca3 ON mag.id=sca3.btlec_sca $params";

	$req=$pdoMag->query($query);
	$magList=$req->fetchAll(PDO::FETCH_ASSOC);
}

// echo "<pre>";
// print_r($magList);
// echo '</pre>';

if(!isset($_POST['filter'])){
	if(isset($_SESSION['mag_filters'])){
		unset($_SESSION['mag_filters']);

	}

	// sans filtre centrale
	$_SESSION['mag_filters']['centraleSelected'][]=1;
		// uniquement les établissements de type magasin
	$_SESSION['mag_filters']['typeSelected'][]=1;
	$_SESSION['mag_filters']['typeSelected'][]=3;
	$_SESSION['mag_filters']['acdlecSelected']=["010","029","070","078","101","102","111","114","116","118","119"];
	$sessionAcdlec=join(' OR ', array_map(function($value){return 'acdlec_code='.$value;},$_SESSION['mag_filters']['acdlecSelected']));
	// echo $sessionAcdlec;


		// uniquement les magasins  ouverts
	$_SESSION['mag_filters']['sorti'][]=0;
	$query="SELECT * FROM mag LEFT JOIN sca3 ON mag.id=sca3.btlec_sca WHERE (id_type=1 OR id_type=3) AND (sorti=0) AND ({$sessionAcdlec})";
	// echo $query;
	// $req=$pdoMag->query("SELECT * FROM mag ");
	$req=$pdoMag->query($query);
	$magList=$req->fetchAll(PDO::FETCH_ASSOC);

}

$iCentrale=0;
$newRowCentrale=3;

$nbResult=count($magList);
$countItem=0;

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

	<div class="row">
		<div class="col">
			<h1 class="text-main-blue py-3 ">Base magasins</h1>
		</div>
		<?php include('search-form.php') ?>
	</div>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php include('../view/_errors.php'); ?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<?php include 'base-mag-filtre.php' ?>
	<?php include 'base-mag-tableau.php' ?>
	<!-- ./container -->
</div>

<script src="../js/autocomplete-searchmag.js"></script>
<script type="text/javascript">
<!-- check uncheck all code acdlec -->
	$(document).ready(function(){
		$("#check-all-code").click(function () {
			$('.acdlec').prop('checked', this.checked);
		});
		$("#uncheck-code").click(function () {
			$('.acdlec').removeAttr('checked');
		});
	});


</script>
<?php require '../view/_footer-bt.php'; ?>