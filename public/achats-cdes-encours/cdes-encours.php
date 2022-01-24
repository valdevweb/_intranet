<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}

$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


require '../../Class/Db.php';
require '../../Class/achats/CdesDao.php';
require '../../Class/achats/CdesAchatDao.php';
require '../../Class/achats/CdesCmtDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/FormHelpers.php';
require '../../Class/UserDao.php';

require_once '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoFou=$db->getPdo('fournisseurs');
$pdoDAchat=$db->getPdo('doc_achats');

$cdesDao=new CdesDao($pdoQlik);
$userDao= new UserDao($pdoUser);
$cdesAchatDao=new CdesAchatDao($pdoDAchat);
$cdesCmtDao=new  CdesCmtDao($pdoDAchat);

$userGts=$userDao->getUserGts($_SESSION['id_web_user']);
if(isset($_SESSION['temp'])){
	unset($_SESSION['temp']);
}
if(isset($_POST['reset'])){

	$_SESSION['encours_filter']=[];
	unset($_SESSION['encours_filter']);
	header("Location: ".$_SERVER['PHP_SELF'],true,303);
}
if (isset($_POST['filter'])) {
	if(!empty($_POST['date_start']) && empty($_POST['date_end'])){
		$errors[]="Merci de sélectionner une fin de période";
	}
	if(empty($_POST['date_start']) && !empty($_POST['date_end'])){
		$errors[]="Merci de sélectionner un début de période";
	}
	if((!empty($_POST['date_start']) || !empty($_POST['date_end'])) && (!isset($_POST['date_type']) && empty($_POST['date_type']))){
		$errors[]="Merci de sélectionner le type de date pour la période sélectionnée";
	}
	if(!empty($_POST['search_strg']) && empty($_POST['type_of_strg'])){
		$errors[]="Merci de préciser sur quel champ vous souhaitez chercher";
	}

	if(empty($errors)){
		$_SESSION['encours_filter']=$_POST;
	}
	// if(!empty($_POST['gt'][0])){
	// 	$_SESSION['encours_filter']['gt']=$_POST['gt'];

	// }
}elseif(!empty($userGts)){
	if(!isset($_SESSION['encours_filter'])){

		$_SESSION['encours_filter']=[];
		$_SESSION['encours_filter']['gt']=[];
		$_SESSION['encours_filter']['gt']=$userGts;
	}


}

if(isset($_SESSION['encours_filter'])){
	$param="";
	$paramList=[];
	if(isset($_SESSION['encours_filter']['gt']) && !empty($_SESSION['encours_filter']['gt'])){
		$paramGt=join(' OR ',array_map(
			function($value){return "gt=".$value;},
			$_SESSION['encours_filter']['gt']));
		$paramList[]='('.$paramGt.')';

	}
	if(!empty($_SESSION['encours_filter']['fou'])){
		$paramFou='fournisseur="'.$_SESSION['encours_filter']['fou'].'"';
		$paramList[]=$paramFou;
	}



	if(!empty($_SESSION['encours_filter']['marque'])){
		$paramMarque='marque="'.$_SESSION['encours_filter']['marque'].'"';
		$paramList[]=$paramMarque;

	}
	if(isset($_SESSION['encours_filter']['op']) && !empty($_SESSION['encours_filter']['op'])){
		$paramOp=join(' OR ',array_map(
			function($value){return "libelle_op='".$value."'";},
			$_SESSION['encours_filter']['op']));
		$paramList[]=$paramOp;

	}
	if(isset($_SESSION['encours_filter']['other_num_cde']) && !empty($_SESSION['encours_filter']['other_num_cde'])){
		if(isset($_SESSION['encours_filter']['num_cde'])){
			unset($_SESSION['encours_filter']['num_cde']);
			$_SESSION['encours_filter']['num_cde']=$_SESSION['encours_filter']['other_num_cde'];
			unset($_SESSION['encours_filter']['other_num_cde']);
		}

	}
	if(isset($_SESSION['encours_filter']['num_cde']) && !empty($_SESSION['encours_filter']['num_cde'])){
		$paramNumCde=join(' OR ',array_map(
			function($value){return "id_cde=".$value;},
			$_SESSION['encours_filter']['num_cde']));
		$paramList[]=$paramNumCde;

	}
	if(isset($_SESSION['encours_filter']['dossier'])){
		if($_SESSION['encours_filter']['dossier']==1000){
			$paramDossier= 'dossier=1000';
			$paramList[]=$paramDossier;

		}
		if($_SESSION['encours_filter']['dossier']==1){
			$paramDossier= 'dossier!=1000';
			$paramList[]=$paramDossier;
		}

	}
	if(!empty($_SESSION['encours_filter']['date_start']) && !empty($_SESSION['encours_filter']['date_end']) && isset($_SESSION['encours_filter']['date_type']) && !empty($_SESSION['encours_filter']['date_type'])){
		if($_SESSION['encours_filter']['date_type']=='date_op'){
			$paramDate="date_start ";
		}
		if($_SESSION['encours_filter']['date_type']=='date_cde'){
			$paramDate="date_cde ";

		}
		if($_SESSION['encours_filter']['date_type']=='date_liv'){
			$paramDate="date_liv ";

		}
		$paramDate.= 'BETWEEN "'. $_SESSION['encours_filter']['date_start']. '" AND "'. $_SESSION['encours_filter']['date_end'].'"';
		$paramList[]=$paramDate;
	}

	if(!empty($_SESSION['encours_filter']['search_strg'])){

		$paramSearch=$_SESSION['encours_filter']['type_of_strg']. " LIKE '%".$_SESSION['encours_filter']['search_strg']. "%'";
		$paramList[]=$paramSearch;

	}

	$param='AND ' .join(' AND ',$paramList);

	$listCdes=$cdesDao->getCdes($param);
	if(isset($_SESSION['encours_filter']['fou'])){
		if(isset($paramGt)){
			$paramGt= ' AND (' . $paramGt .')';
			$listCdesByFou=$cdesDao->getCdesByFou($_SESSION['encours_filter']['fou'], $paramGt);
		}

	}
// echo $_SESSION['encours_filter']['fou'];
	$nbArt=count($listCdes);
	$listInfos=$cdesAchatDao->getInfos($param);

}else{
	$listCdes=$cdesDao->getCdes();
	$nbArt=count($listCdes);
	$listInfos=$cdesAchatDao->getInfos();
}


if(isset($_POST['save_all'])){
	$_SESSION['temp']=$_POST['id_encours'];
	header("Location: edit-encours.php");
}

$listGt=FournisseursHelpers::getGts($pdoFou, "GT","id");

$tableCol=["", "GT", "Date cde", "Fournisseur", "Marque", "Article", "Dossier", "Date début op", "Op", "Ref", "EAN", "Désignation", "Cde", "Qte init colis", "Colis à recevoir", "UV à recevoir", "PCB", "% reçu", "Restant prévi", "date livraison initiale","Date livraison"];

$tableColTh=["GT", "Date cde", "Fournisseur", "Marque", "Article", "Dossier", "Début op", "Op", "Ref", "EAN", "Désignation", "Cde", "Qte init colis", "Colis à<br> recevoir", "UV<br>à recevoir", "PCB", "% reçu", "Restant prévi", "date livraison initiale","Date livraison"];


$listOp=array_unique(array_column($listCdes, 'libelle_op'));
$listNumCde=array_unique(array_column($listCdes, 'id_cde'));
// 634703


$listMarque=array_unique(array_column($listCdes, 'marque'));
$listFou=array_unique(array_column($listCdes, 'fournisseur'));
// fou manque cnuf
// op manque code op
//
$listOp=array_filter($listOp);


sort($listOp);
$listNumCde=array_filter($listNumCde);
sort($listNumCde);
$listMarque=array_filter($listMarque);
sort($listMarque);
$listFou=array_filter($listFou);
sort($listFou);






if(isset($_POST['kill_session'])){
	if(isset($_SESSION['encours_col'])){
		unset($_SESSION['encours_col']);
		header("Location: cdes-encours.php");

	}
}
if(isset($_GET['export-xls'])){
	include 'xl-cols/xl-cols.php';
	include 'cdes-encours/01-export-xls.php';
}



//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container-fluid bg-white">
	<div class="row pt-3">
		<div class="col text-center">
			<h1 class="text-main-blue">Commandes en cours</h1>
		</div>
	</div>


	<!-- filtres -->
	<div class="row mb-5">
		<div class="col-lg-2"></div>
		<div class="col border rounded p-3">
			<?php include 'cdes-encours/10-filtre.php' ?>
		</div>
		<div class="col-lg-2"></div>
	</div>
	<!-- tableau commandes en cours -->
	<div class="row">
		<div class="col text-center">
			<h5 class="text-main-blue">Nombre de ligne de commandes : <?=$nbArt??""?></h5>
		</div>
	</div>
	<div class="row">
		<div class="col-auto font-italic">
			<a href="?export-xls" class="btn btn-success" id="export-xls">Export Excel</a>
		</div>

		<div class="col font-italic">
			<a href="import-cdesinfos.php" class="btn btn-success">Import Excel</a>
		</div>
		<div class="col-auto font-weight-boldless text-main-blue">
			Rechercher sur la page :
		</div>
		<div class="col-lg-3">

			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" id="search_form">
				<div class="form-group text-center">
					<input type="text" class="form-control " name="str" id="str" style="font-family:'Font Awesome 5 Free',sans-serif !important; font-weight: 900 !important;" type="text" placeholder="&#xf002">
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-auto" id="wait">

		</div>
		<div class="col"></div>
	</div>
	<div class="row">
		<div class="col">
			Cochez les colonnes que vous souhaitez masquer
		</div>
	</div>
	<div class="row">
		<div class="col" id="col-selector">
			<?php for ($j=1; $j <count($tableCol) ; $j++) : ?>
				<div class="form-check form-check-inline">
					<input class="form-check-input col-box" type="checkbox" value="<?=$j?>"  data-box-id="<?=$j?>" name="cols[]" >
					<label class="form-check-label" for="model"><?=$tableCol[$j]?></label>
				</div>
			<?php endfor ?>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php if (!empty($listCdes)): ?>
				<?php include 'cdes-encours/11-table-encours.php' ?>
			<?php else: ?>
				<div class="alert alert-info">Aucune commande à afficher</div>
			<?php endif ?>
		</div>
	</div>
</div>

<script src="../js/search-in-window.js"></script>
<script src="../js/upload-helpers.js"></script>
<script type="text/javascript">
	document.getElementById('search_form').onsubmit = function() {
		findString(this.str.value);
		return false;
	};



	$(document).ready(function(){

		$('#export-xls').click(function(e){
			if($( $("#reply").val()!="")){
				$('#wait').append("<div class='alert alert-success'>Merci de patienter pendant la génération du fichier</div>");

				setInterval(function(){
					$('#wait').empty();
				}, 10000);

			}
		});

		$('#checkall').change(function(){
			if($(this).prop("checked")){
				$('.select-checkbox').prop('checked',true);
			}else{
				$('.select-checkbox').prop('checked',false);

			}
		});
		console.log(sessionStorage.getItem('hidden_cols'));
		hiddenCols = sessionStorage.getItem('hidden_cols');
		if (hiddenCols != null && hiddenCols.length!=0) {

			arrHiddenCols=JSON.parse(hiddenCols);
			for (var i = 0; i < arrHiddenCols.length; i++) {
				var id =arrHiddenCols[i];

				var box = $(".col-box").find(`[data-box-id='${id}']`);

				$('input[type="checkbox"][data-box-id="'+id+'"]').prop('checked', true);
				col="col-"+id;
				$('.'+col).hide();

			}

		}
		// sessionStorage.removeItem('hidden_cols');
		$('.col-box').click(function(){
			var arrHiddenCols=[];
			hiddenCols = sessionStorage.getItem('hidden_cols');
			if($(this).prop("checked")){
				id=$(this).attr("data-box-id");
				col="col-"+id;
				$('.'+col).hide();
				if (hiddenCols != null && hiddenCols.length!=0) {
					console.log(sessionStorage);
					arrHiddenCols=JSON.parse(hiddenCols);
					var nextElem=arrHiddenCols.length;
					arrHiddenCols[nextElem]=id;
					sessionStorage.setItem("hidden_cols", JSON.stringify(arrHiddenCols));
					console.log(sessionStorage);

				}else{
					arrHiddenCols[0]=id;
					sessionStorage.setItem("hidden_cols", JSON.stringify(arrHiddenCols));

				}

				// $('.service').prop('checked',true);
			}else{
				console.log($(this).attr("data-box-id"));
				id=$(this).attr("data-box-id");
				col="col-"+id;
				$('.'+col).show();
				if (hiddenCols != null && hiddenCols.length!=0) {
					// console.log(sessionStorage);

					arrHiddenCols=JSON.parse(hiddenCols);
					console.log(arrHiddenCols)
					var indexToRemove = arrHiddenCols.indexOf(id);
					arrHiddenCols.splice(indexToRemove, 1)
					sessionStorage.setItem("hidden_cols", JSON.stringify(arrHiddenCols));
					console.log(sessionStorage);

				}
				// $('.service').prop('checked',false);

			}
		})

	});


</script>

<?php
require '../view/_footer-bt.php';
?>

<!--
	$('.show-detail').on("click", function(){
		var id= $(this).data("camion-id");
		console.log("click");
		if($('div[data-detail-id="'+id+'"]').is(":visible")){
			$('div[data-detail-id="'+id+'"]').hide();
			inLSCamion = sessionStorage.getItem('unhidden_camion');
			if (inLSCamion != null && inLSCamion.length!=0) {
				unhiddenCamion=JSON.parse(inLSCamion);
				var indexToRemove = unhiddenCamion.indexOf(id);
				unhiddenCamion.splice(indexToRemove, 1);
				sessionStorage.setItem("unhidden_camion", JSON.stringify(unhiddenCamion));
			}

		}else{
			$('div[data-detail-id="'+id+'"]').show();
			inLSCamion = sessionStorage.getItem('unhidden_camion');
			if (inLSCamion != null && inLSCamion.length!=0) {
				unhiddenCamion=JSON.parse(inLSCamion);
				var nextElem=unhiddenCamion.length;
				unhiddenCamion[nextElem]=id;
				sessionStorage.setItem("unhidden_camion", JSON.stringify(unhiddenCamion));
			}else{
				unhiddenCamion[0]=id;
				sessionStorage.setItem("unhidden_camion", JSON.stringify(unhiddenCamion));
			}
		}
	}); -->