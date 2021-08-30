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
require '../../Class/FournisseursHelpers.php';
require '../../Class/FormHelpers.php';
require '../../Class/UserDao.php';

// require_once '../../vendor/autoload.php';


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


$userGts=$userDao->getUserGts($_SESSION['id_web_user']);
if(isset($_SESSION['temp'])){
	unset($_SESSION['temp']);
}
if(isset($_POST['reset'])){
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

	if(empty($errors)){
		$_SESSION['encours_filter']=$_POST;
	}
	// if(!empty($_POST['gt'][0])){
	// 	$_SESSION['encours_filter']['gt']=$_POST['gt'];

	// }
}elseif(!empty($userGts)){
	$_SESSION['encours_filter']=[];
	$_SESSION['encours_filter']['gt']=[];
	$_SESSION['encours_filter']['gt']=$userGts;
	// $defaultParam='AND (gt='.join(' OR gt=',$userGts). ')';
	// $listCdes=$cdesDao->getCdes($defaultParam);
	// $nbArt=count($listCdes);
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

	$param='AND ' .join(' AND ',$paramList);
	// echo $param;
	$listCdes=$cdesDao->getCdes($param);
	$nbArt=count($listCdes);
	$listInfos=$cdesAchatDao->getInfos($param);

}else{
	$listCdes=$cdesDao->getCdes();
	$nbArt=count($listCdes);
	$listInfos=$cdesAchatDao->getInfos();

}


// if(isset($_POST['save'])){
// 	$idDetail=key($_POST['save']);
// 	$update=$cdesAchatDao->insertInfos($idDetail,$_POST['date_previ'][$idDetail], $_POST['qte_previ'][$idDetail], $_POST['cmt'][$idDetail]);
// 	if($update==1){
// 		$successQ='#'.$idDetail;
// 		unset($_POST);
// 		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
// 	}
// }
if(isset($_POST['save_all'])){
	$_SESSION['temp']=$_POST['id_encours'];
	header("Location: edit-encours.php");
}

$listGt=FournisseursHelpers::getGts($pdoFou, "GT","id");

$tableCol=["GT", "Date cde", "Fournisseur", "Marque", "Article", "Dossier", "Ref", "Désignation", "Cde", "Qte init colis", "Qte colis", "Qte UV", "PCB", "Date réception", "Date début op", "Op", "Semaine prévi", "Date prévi rdv", "Qte prévi", "Commentaire"];

$listOp=array_unique(array_column($listCdes, 'libelle_op'));
$listNumCde=array_unique(array_column($listCdes, 'id_cde'));
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






//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container-fluid bg-white">
	<div class="row pt-5">
		<div class="col">
			<h1 class="text-main-blue">Commandes en cours</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row mb-5">
		<div class="col-lg-2"></div>
		<div class="col border rounded p-3">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
				<div class="row">
					<div class="col text-center">
						<h5 class="text-main-blue">Filtrer le tableau commandes en cours :</h5>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="fou">Fournisseur :</label>
							<select class="form-control form-primary" name="fou" id="fou">
								<option value="">Sélectionner</option>
								<?php foreach ($listFou as $keyFou => $fou): ?>
									<option value="<?=$listFou[$keyFou]?>" <?=isset($_SESSION['encours_filter']['fou'])?FormHelpers::restoreSelected($_SESSION['encours_filter']['fou'], $listFou[$keyFou]):""?>><?=$listFou[$keyFou]?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="marque">Marque :</label>
							<select class="form-control form-primary" name="marque" id="marque">
								<option value="">Sélectionner</option>
								<?php foreach ($listMarque as $keyMarque => $fou): ?>
									<option value="<?=$listMarque[$keyMarque]?>" <?=isset($_SESSION['encours_filter']['marque'])?FormHelpers::restoreSelected($_SESSION['encours_filter']['marque'], $listMarque[$keyMarque]):""?>><?=$listMarque[$keyMarque]?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="op">Opérations :</label>
							<select class="form-control form-primary" name="op[]" id="op" multiple>
								<option value="">Sélectionner</option>
								<?php foreach ($listOp as $keyOp => $fou): ?>
									<option value="<?=$listOp[$keyOp]?>" <?=isset($_SESSION['encours_filter']['op'])?FormHelpers::restoreSelectedArray($listOp[$keyOp],$_SESSION['encours_filter']['op']):""?>><?=$listOp[$keyOp]?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="num_cde">Commandes :</label>
							<select class="form-control form-primary" name="num_cde[]" id="num_cde" multiple>
								<option value="">Sélectionner</option>
								<?php foreach ($listNumCde as $keyCde => $fou): ?>
									<option value="<?=$listNumCde[$keyCde]?>" <?=isset($_SESSION['encours_filter']['num_cde'])?FormHelpers::restoreSelectedArray($listNumCde[$keyCde],$_SESSION['encours_filter']['num_cde']):""?>><?=$listNumCde[$keyCde]?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-auto">
						<div class="form-check">
							<input class="form-check-input" type="radio" value="1000" id="permanent" name="dossier"
							<?=(isset($_SESSION['encours_filter']['dossier']) && $_SESSION['encours_filter']['dossier']==1000)?"checked":""?>>
							<label class="form-check-label" for="permanent">Permanent</label>
						</div>
					</div>
					<div class="col-auto">
						<div class="form-check">
							<input class="form-check-input" type="radio" value="1" id="op" name="dossier"
							<?=(isset($_SESSION['encours_filter']['dossier']) && $_SESSION['encours_filter']['dossier']==1)?"checked":""?>
							>
							<label class="form-check-label" for="permanent">Opérations</label>
						</div>
					</div>
					<div class="col-auto">
						<div class="form-check">
							<input class="form-check-input" type="radio" value="9" id="permanent" name="dossier"
							<?=(isset($_SESSION['encours_filter']['dossier']) && $_SESSION['encours_filter']['dossier']==9)?"checked":""?>
							>
							<label class="form-check-label" for="permanent">Permanent et opération</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-3">
						<div class="form-group">
							<label for="date_start">Du :</label>
							<input type="date" class="form-control form-primary" name="date_start" id="date_start" value="<?=isset($_SESSION['encours_filter']['date_start'])?$_SESSION['encours_filter']['date_start']:""?>">
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="date_end">au :</label>
							<input type="date" class="form-control form-primary" name="date_end" id="date_end"  value="<?=isset($_SESSION['encours_filter']['date_end'])?$_SESSION['encours_filter']['date_end']:""?>">
						</div>
					</div>
					<div class="col-auto mt-4 pt-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" value="date_op" id="date_op" name="date_type"  <?=(isset($_SESSION['encours_filter']['date_type']) && $_SESSION['encours_filter']['date_type']=="date_op")?"checked":""?>>
							<label class="form-check-label" for="date_op">dates d'opération</label>
						</div>
					</div>
					<div class="col-auto mt-4 pt-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" value="date_cde" id="date_cde" name="date_type" <?=(isset($_SESSION['encours_filter']['date_type']) && $_SESSION['encours_filter']['date_type']=="date_cde")?"checked":""?>>
							<label class="form-check-label" for="date_cde">dates de commandes</label>
						</div>
					</div>
					<div class="col-auto mt-4 pt-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" value="date_liv" id="date_liv" name="date_type" <?=(isset($_SESSION['encours_filter']['date_type']) && $_SESSION['encours_filter']['date_type']=="date_liv")?"checked":""?>>
							<label class="form-check-label" for="date_liv">dates de réception</label>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-auto">GTs :</div>
					<div class="col cols-four">
						<?php foreach ($listGt as $keyGt => $value): ?>
							<div class="form-check">
								<input class="form-check-input form-primary" type="checkbox" value="<?=$keyGt?>" id="<?=$listGt[$keyGt]?>" name="gt[]"  <?=isset($_SESSION['encours_filter']['gt'])?FormHelpers::restoreCheckedArray($keyGt,$_SESSION['encours_filter']['gt']):""?>>
								<label class="form-check-label gt-<?=$keyGt?>" for="<?=$listGt[$keyGt]?>"><?=ucfirst(strtolower($listGt[$keyGt]))?></label>
							</div>
						<?php endforeach ?>
					</div>
					<div class="col-lg-2"></div>

				</div>
				<div class="row mb-3">
					<div class="col"></div>
					<div class="col-auto text-right">
						<button class="btn btn-secondary" name="reset">Effacer les filtres</button>

					</div>
					<div class="col-auto text-right">
						<button class="btn btn-primary" name="filter">Filtrer</button>
					</div>
				</div>

			</form>
		</div>
		<div class="col-lg-2"></div>

	</div>
	<div class="row">
		<div class="col text-center">
			<h5 class="text-main-blue">Nombre de ligne de commandes : <?=$nbArt??""?></h5>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php if (!empty($listCdes)): ?>
				<?php include 'cdes-encours/10-table-encours.php' ?>
				<?php else: ?>
					<div class="alert alert-info">Veuillez sélectionner le(s)  GT(s) pour lesquels vous souhaitez afficher les commandes en cours</div>

				<?php endif ?>
			</div>
		</div>


	</div>
	<!-- <script src="../js/excel-filter.js"></script> -->

	<script type="text/javascript">
		$(document).ready(function(){
			// $('#table-cde-encours').excelTableFilter({ columnSelector: '.apply-filter',});
			$('.show-col').hide();

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

			// var tableCol=[]
			// $( "table > thead > tr > th" ).each(function(){
			// 	tableCol.push($(this).text());
			// });


			// console.log(tableCol);
			$('table#table-cde-encours input.switch-input').on("click", function(){
				nthChild=$(this).data("col") +1 ;
				arrayCol=$(this).data("col");

				$('table#table-cde-encours td:nth-child('+nthChild+'),table#table-cde-encours th:nth-child('+nthChild+')').hide();
				listCol=$('div#masked-col').text();
				$('.show-col[data-col-show="'+arrayCol+'"]').show();

			});


			$('a.show-col').on("click", function(){
				console.log("clic");
				nthChild=$(this).data("col-show") +1 ;
				arrayCol=$(this).data("col-show");

				// alert("clic" +nthChild);

				$('table#table-cde-encours td:nth-child('+nthChild+'),table#table-cde-encours th:nth-child('+nthChild+')').show();
				$('.switch-input[data-col="'+arrayCol+'"]').removeAttr('checked');
				$(this).hide();
			});



		});

	</script>

	<?php
	require '../view/_footer-bt.php';
	?>