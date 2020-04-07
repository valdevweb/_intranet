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
$listCentrale=$magDbHelper-> getDistinctCentraleDoris();
$listType=$magDbHelper->getListType();
$listCm=UserHelpers::getUserByService($pdoUser,17);
$listTypePair=$magDbHelper->getListTypePair();
$listCodeAcdlec=$magDbHelper->getListCodeAcdlecUtilise();
$centraleName=Helpers::arrayFlatten($listCentrale,"centrale_doris","centrale");
$ets=Helpers::arrayFlatten($listCodeAcdlec,"acdlec_code","nom_ets");

$mainCentraleIds=[20,10,30,50,90,100,110,120,160,170,250,210];

function checkChecked($value,$field){
	if(isset($_SESSION['mag_filters']) && isset($_SESSION['mag_filters'][$field])){
		if(in_array($value,$_SESSION['mag_filters'][$field])){
			return "checked";
		}
	}

	return "";
}




//------------------------------------------------------
//			EXPLOIT
//------------------------------------------------------


//------------------------------------------------------
//			gestion de l'affichage par défaut
//------------------------------------------------------



if(isset($_POST['filter'])){

	if(isset($_POST['centraleSelected'])){
		$_SESSION['mag_filters']['centraleSelected']=$_POST['centraleSelected'];

			//si on a coché la case sans filtre centrale, on ne met pas de paramètre centrale
		if(in_array(1,$_POST['centraleSelected'])){
			$paramCentrale='';
		}elseif(in_array(0,$_POST['centraleSelected'])){
			$paramCentrale="(centrale_doris IS NULL OR centrale_doris='') ";
		}
		else{
			$paramCentrale=join(' OR ', array_map(function($value){return 'centrale_doris='.$value;},$_POST['centraleSelected']));
		}
	}else{
		$_SESSION['mag_filters']['centraleSelected']=[];
		$paramCentrale='';
	}
	$paramList[]=$paramCentrale;


	if(isset($_POST['acdlecSelected'])){
		$_SESSION['mag_filters']['acdlecSelected']=$_POST['acdlecSelected'];
		$paramAcdlec=join(' OR ', array_map(function($value){return 'acdlec_code='.$value;},$_POST['acdlecSelected']));

	}else{
		$_SESSION['mag_filters']['acdlecSelected']=[];
		$paramAcdlec='';
	}
	$paramList[]=$paramAcdlec;


	if(isset($_POST['sorti']) && $_POST['sorti'][0]==9){
		$_SESSION['mag_filters']['sorti']=$_POST['sorti'];
		if(empty($_POST['date_fermeture'][0])){
			$_SESSION['mag_filters']['date_fermeture_deb']="2000-12-31";
			$_SESSION['mag_filters']['date_fermeture_fin']=date('Y-m-d');
			$paramClosed=join(' OR ', array_map(function($value){return 'sorti='.$value;},$_POST['sorti']));

		}else{
			$_SESSION['mag_filters']['date_fermeture_deb']=$_POST['date_fermeture'][0];
			$_SESSION['mag_filters']['date_fermeture_fin']=$_POST['date_fermeture'][1];
			$paramClosed=join(' OR ', array_map(function($value){return 'sorti='.$value;},$_POST['sorti']));
			$paramClosed.=" AND ( date_fermeture BETWEEN '" .$_SESSION['mag_filters']['date_fermeture_deb'] ."' AND '".$_SESSION['mag_filters']['date_fermeture_fin'] ."')";
		}


	}elseif(isset($_POST['sorti']) && $_POST['sorti'][0]==0){

		$_SESSION['mag_filters']['sorti']=[];
		unset($_SESSION['mag_filters']['date_fermeture_deb']);
		unset($_SESSION['mag_filters']['date_fermeture_fin']);
		$paramClosed='';
	}else{
		$_SESSION['mag_filters']['sorti']=[];
		unset($_SESSION['mag_filters']['date_fermeture_deb']);
		unset($_SESSION['mag_filters']['date_fermeture_fin']);
		$paramClosed='';

	}
	$paramList[]=$paramClosed;

	if(isset($_POST['no-docubase'])){
		$_SESSION['mag_filters']['no-docubase']=$_POST['no-docubase'];
		$paramDocubase=" docubase_login IS NULL OR docubase_login='' ";
	}else{
		$_SESSION['mag_filters']['no-docubase']=[];
		$paramDocubase="";

	}
	$paramList[]=$paramDocubase;

	if(isset($_POST['no-portail'])){
		$_SESSION['mag_filters']['no-portail']=$_POST['no-portail'];
		$paramPortail=" login IS NULL OR login='' ";
	}else{
		$_SESSION['mag_filters']['no-portail']=[];
		$paramPortail="";

	}
	$paramList[]=$paramPortail;


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
	$query="SELECT mag.*,sca3.*,web_users.users.login, web_users.users.nohash_pwd  FROM mag LEFT JOIN sca3 ON mag.id=sca3.btlec_sca LEFT JOIN web_users.users ON mag.galec=web_users.users.galec $params GROUP BY mag.id";
	$_SESSION['mag_filters']['query']=$query;


	$req=$pdoMag->query($query);
	$magList=$req->fetchAll(PDO::FETCH_ASSOC);
}



if(!isset($_POST['filter'])){
	if(isset($_SESSION['mag_filters'])){
		unset($_SESSION['mag_filters']);
	}

	// sans filtre centrale
	$_SESSION['mag_filters']['centraleSelected']=$mainCentraleIds;
	$sessionCentrale=join(' OR ', array_map(function($value){return 'centrale_doris='.$value;},$_SESSION['mag_filters']['centraleSelected']));

		// uniquement les établissements de type magasin

	$_SESSION['mag_filters']['acdlecSelected']=["010","029","070","078","101","102","111","114","116","118","119"];
	$sessionAcdlec=join(' OR ', array_map(function($value){return 'acdlec_code='.$value;},$_SESSION['mag_filters']['acdlecSelected']));
	// echo $sessionAcdlec;
	$_SESSION['mag_filters']['no-docubase'][]="";
	$_SESSION['mag_filters']['no-portail'][]="";

		// uniquement les magasins  ouverts
	$_SESSION['mag_filters']['sorti'][]=0;
	$query="SELECT mag.*,sca3.*,web_users.users.login, web_users.users.nohash_pwd FROM mag LEFT JOIN sca3 ON mag.id=sca3.btlec_sca LEFT JOIN web_users.users ON mag.galec=web_users.users.galec WHERE (sorti=0) AND ({$sessionAcdlec}) AND ({$sessionCentrale}) GROUP BY mag.id";
	$_SESSION['mag_filters']['query']=$query;

	// echo $query;
	// $req=$pdoMag->query("SELECT * FROM mag ");
	$req=$pdoMag->query($query);
	$magList=$req->fetchAll(PDO::FETCH_ASSOC);

}

$iCentrale=0;
$newRowCentrale=3;

$nbResult=count($magList);
$countItem=0;

// 1ere date de fermeture 2000-12-31
function compareMag($fieldGessica, $fieldSca, $key, $magList){
	$gessica = str_replace(' ', '', $magList[$key][$fieldGessica]);
	$sca = str_replace(' ', '', $magList[$key][$fieldSca]);
	if(strtoupper($gessica) !=strtoupper($sca)){
		$magList[$key]['diff']=1;
		// echo "DIFF " . $mag['deno'].$mag['deno_sca']."<br>";
		if(!isset($magList[$key]['diff-field'])){
			$magList[$key]['diff-field']=$fieldGessica.",";
		}else{
			$magList[$key]['diff-field'].=$fieldGessica.",";
		}
	}
	return $magList;

}

foreach ($magList as $key => $mag) {
	$magList=compareMag('deno', 'deno_sca', $key, $magList);
	$magList=compareMag('cp', 'cp_sca', $key, $magList);
	$magList=compareMag('galec', 'galec_sca', $key, $magList);
	$magList=compareMag('ville', 'ville_sca', $key, $magList);
	$magList=compareMag('gel', 'sorti', $key, $magList);
}
if(array_column($magList, 'diff')){
	$diff=array_count_values(array_column($magList, 'diff'))[1]; //nb de fois ou la clé diff apparaît égale à 1
}else{
	$diff=0;
}



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

	<?php include 'base-mag-export.php' ?>

	<?php include 'base-mag-tableau.php' ?>
	<!-- ./container -->
</div>

<script src="../js/autocomplete-searchmag.js"></script>
<script type="text/javascript">
	<!-- check uncheck all code acdlec -->
	$(document).ready(function(){
		// cocher tout decocher tout pour addlec
		$("#check-all-code").click(function () {
			$('.acdlec').prop('checked', this.checked);
		});
		$("#uncheck-code").click(function () {
			$('.acdlec').removeAttr('checked');
		});
		// décocher tout qd eno-filtre-centrale
		$('#no-filtre-centrale').click(function(){
			$('.centrale').removeAttr('checked');
			$('#no-centrale').removeAttr('checked');

		});
		// décocher tout qd pas de centrale
		$('#no-centrale').click(function(){
			$('.centrale').removeAttr('checked');
			$('#no-filtre-centrale').removeAttr('checked');

		});
		// décocher no-filtre-centrale et no-centrale qd class centrale slectionnnée
		$('.centrale').click(function(){
			$('#no-filtre-centrale').removeAttr('checked');
			$('#no-centrale').removeAttr('checked');

		});
		$('.hide-btn').on("click",function(){
			$( "tr.ok" ).hide();
		});
		$('.show-btn').on("click",function(){
			$( "tr.ok" ).show();
		});
		$("#radio-fermeture").change(function(){
			if($(this).prop("checked")) {
				$('#fermeture-option').attr('class','show');
				$('.acdlec').prop('checked', this.checked);
				$('#no-filtre-centrale').prop('checked', this.checked);
				$('.centrale').removeAttr('checked');


			}
		});

		if($("#radio-fermeture").prop("checked")) {
			$('#fermeture-option').attr('class','show');
		}

		// if($("#radio-fermeture").click(function ()) {
		// 	$('.centrale').removeAttr('checked');
		// 	$('.acdlec').prop('checked', this.checked);
		// });


		$("#radio-ouverture").change(function(){
			if($(this).prop("checked")){
				$('#fermeture-option').attr('class','hide');

			}
		});


	});


</script>
<?php require '../view/_footer-bt.php'; ?>