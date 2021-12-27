<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			INCLUDES
//------------------------------------------------------
require '../../Class/Db.php';


require('../../Class/Table.php');
require('../../Class/BaDao.php');
require('../../Class/casse/PalettesDao.php');
require('../../Class/casse/ExpDao.php');
require('../../Class/casse/CasseHelpers.php');
require('../../Class/casse/TrtDao.php');
require('../../Class/casse/CasseDao.php');

require('../../Class/MagHelpers.php');
require('../../Class/CrudDao.php');


require('casse-getters.fn.php');

$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoCasse=$db->getPdo('casse');
$pdoMag=$db->getPdo('magasin');

$baDao=new BaDao($pdoQlik);
$paletteDao=new PalettesDao($pdoCasse);
$expDao=new ExpDao($pdoCasse);
$trtDao=new TrtDao($pdoCasse);
$casseDao=new CasseDao($pdoCasse);

$casseCrud=new CrudDao($pdoCasse);

$listAffectation=CasseHelpers::getAffectation($pdoCasse);

$arStatutPalette=CasseHelpers::getStatutsPalette($pdoCasse);
$listStatutPalette=CasseHelpers::getListStatutPalette($pdoCasse);
$listStatutPaletteIco=CasseHelpers::getListStatutPaletteIco($pdoCasse);


$listTrtMag=CasseHelpers::getTraitementsByType($pdoCasse, "mag"); //1
$listTrtSav=CasseHelpers::getTraitementsByType($pdoCasse, "sav");//3
$listTrtOcc=CasseHelpers::getTraitementsByType($pdoCasse, "occasion"); //2
$listAffectation=CasseHelpers::getAffectation($pdoCasse);
$listAffectationIco=CasseHelpers::getAffectationIco($pdoCasse);

$listTrtUrl=CasseHelpers::getTraitementsUrl($pdoCasse);
$trtHisto=$trtDao->getTrtHistoByExp();

$errors=[];
$success=[];
$today=date('Y-m-d');
$start=date('Y-m-d',strtotime("2019-01-01"));
$yesterday=date('Y-m-d',strtotime("-1 days"));
$nbPalette=0;
$expeds=$expDao->getExpDetails();



$paletteEnStock=$paletteDao->getStockPalette();


if(!empty($paletteEnStock)){
	$nbPalette=count($paletteEnStock);
}



$sumTot=0;
$arMagSum=[];

$arrParam=[];


if(isset($_POST['clear_form'])){
	unset($_POST);
	unset($_SESSION['casse_filter']);
}

if(isset($_GET['field_1'])){
	$_SESSION['casse_filter']['field_1']=$_GET['field_1'];
}
if(isset($_GET['field_2'])){
	$_SESSION['casse_filter']['field_2']=$_GET['field_2'];
}


if(isset($_POST['search'])){
	$params= $_POST['field'] ." = '".$_POST['search_string']."'";
	$_SESSION['casse_filter']['search_field']=$_POST['field'];
	$_SESSION['casse_filter']['search_value']=$_POST['search_string'];
}


if(!isset($_SESSION['casse_filter'])){
	// par défaut, on affiche les palettes non cloturées
	$params="palettes.statut !=2";

}else{
	if (isset($_SESSION['casse_filter']['search_field'])) {
		if($_SESSION['casse_filter']['search_field']=="ean"){
			$arrParam[]= " ean='".$_SESSION['casse_filter']['search_value']."'";
		}elseif($_SESSION['casse_filter']['search_field']=="palette"){
			$arrParam[]= " palette LIKE '%".$_SESSION['casse_filter']['search_value']."%'";
		}elseif($_SESSION['casse_filter']['search_field']=="id_casse"){
			$arrParam[]= " casses.id=".$_SESSION['casse_filter']['search_value'];
		}elseif($_SESSION['casse_filter']['search_field']=="btlec"){
			$arrParam[]= " exps.btlec=".$_SESSION['casse_filter']['search_value'];
		}
	}
	if(isset($_SESSION['casse_filter']['field_1'])) {
		$arrParam[]=" statut=".$_SESSION['casse_filter']['field_1'];
	}
	if(isset($_SESSION['casse_filter']['field_2'])) {
		$arrParam[]=" palettes.id_affectation=".$_SESSION['casse_filter']['field_2'];
	}
	if(!empty($arrParam)){
		$params=join(' and ', array_map(function($value){return $value;},$arrParam));
	}

}

$palettesToDisplay=$paletteDao->getPaletteByFilter($params);


if(isset($palettesToDisplay)){
	$nbpalette=count($palettesToDisplay);

	foreach ($palettesToDisplay as $key => $value) {
		$sumTot+=$value['valopalette'];
		if(isset($arMagSum[$value['galec']])){
			$arMagSum[$value['galec']]+=$value['valopalette'];
		}else{
			$arMagSum[$value['galec']]=$value['valopalette'];
		}
	}
	$nbMagCol=ceil(count($arMagSum)/2);
	$lig=1;
}



if(isset($_POST['save_contremarque'])){
	$updateTrt=true;

	foreach ($_POST['contremarque'] as $id => $value) {
		if (empty($_POST['contremarque'][$id])) {
			$updateTrt=false;
		}
		$casseCrud->update("palettes", "id=".$id, ['contremarque'=>$_POST['contremarque'][$id]]);
	}

	if($updateTrt){
		$trtDao->insertTrtHisto($_POST['id_exp'], 1);
	}

	$successQ='#exp-'.$_POST['id_exp'];
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

}


if(isset($_POST['update_palette'])){
	$casseCrud->updateOneField("palettes", "palette", $_POST['palette_modal'], $_POST['id_palette']);
	$successQ='#palette-'.$_POST['id_palette'];
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if(isset($_GET['del-palette'])){
	$casses=$casseDao->getCasseByPalette($_GET['del-palette']);
	if(empty($casses)){
		$paletteDao->copyPaletteToDeleted( $_GET['del-palette']);
		$casseCrud->update("palettes_deleted", "id=".$_GET['del-palette'], ['deleted_on'=>date('Y-m-d H:i:s'), 'deleted_by'=>$_SESSION['id_web_user']]);
		$casseCrud->deleteOne("palettes", $_GET['del-palette']);
		$successQ='?success=del-palette';
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}else{
		$errors[]="avant de supprimer la palette, vous devez supprimer ou réaffecter les casses sur une autre palette";
	}


}


if(isset($_GET['id_trt'])){
	$url=($listTrtUrl[$_GET['id_trt']])??"";
	if(empty($url)){
		$errors[]="Le traitement n'a pas été reconnu";
	}
	if(!file_exists($url)){
		$errors[]="La page de traitement demandée, n'existe pas";

	}
	if(empty($errors)){
		header("Location: ".$url."?id=".$_GET['id_exp']."&id_trt=".$_GET['id_trt']);
	}

}


// cas ou va sur detail-palette sans id en paramètre => redirige ici
if(isset($_GET['error'])){
	if($_GET['error']==1){
		$errors[]="Vous avez été redirigé, cette page n'est pas accessible";
	}
	elseif($_GET['error']==2){
		$errors[]="Une erreur est survenue";
	}
}

if(isset($_GET['success'])){
	$arrSuccess=[
		'del-palette'=>'Palette supprimée',
	];
	$success[]=$arrSuccess[$_GET['success']];
}



if(isset($_GET['mailPilote'])){
	$success[]="Le mail a bien été envoyé aux pilotes";
}

if(isset($_GET['majExp'])){
	$success[]="La date d'expédition a bien été enregistrée";
}
if(isset($_GET['mailMag'])){
	$success[]="Le mail a bien été envoyé magasin";
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

	<div class="container no-padding">
		<div class="row no-gutters" id="mini-menu">
			<div class="col">
				<img src="../img/litiges/brokenphone2.png" class="img-fluid">
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-1"></div>
			<div class="col">
				<?php
				include('../view/_errors.php');
				?>
			</div>
			<div class="col-lg-1"></div>
		</div>
		<div class="row" >
			<div class="col"></div>
			<div class="col-auto">
				<ul class="css-not-selector-shortcut sibling-fade">
					<li><a href="declare-casse.php" class="link-main-blue">Déclarer</a></li>
					<li><a href="#searching" class="link-main-blue">Rechercher</a></li>
					<li><a href="#stock" class="link-main-blue">En stock</a></li>
					<li><a href="#traitement" class="link-main-blue">Traitement</a></li>
				</ul>
			</div>
			<div class="col"></div>
		</div>

		<?php if(isset($palettesToDisplay)): ?>
			<div class="result-zone px-5 pb-2 pt-2 mb-2">

				<?php include ('casse-dashboard/12-search-stat.php');	?>
				<?php include ('casse-dashboard/11-form-search.php');	?>
			</div>
			<?php include ('casse-dashboard/13-search-table-result.php')?>
		<?php endif ?>
		<div class="row">
			<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
		</div>
		<div class="bg-separation"></div>
		<div class="row py-3">
			<div class="col">
				<h5 class="text-main-blue" id="stock">Palettes en stock :</h5>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<p>
					<?php foreach ($arStatutPalette as $key => $statut): ?>
						<?php if ($statut['id']!=0): ?>
							<?=$statut['ico']." : ".$statut['statut'].$statut['id']?>
						<?php endif ?>
					<?php endforeach ?>
				</p>
			</div>
		</div>
		<div class="row pb-5">
			<div class="col">
				<?php if (!empty($paletteEnStock)): ?>
					<ul id="list-palette">
						<?php foreach ($paletteEnStock as $palette): ?>
							<?php
							if($palette['id_affectation']==3){
								$classAffectation="text-success";
							}elseif($palette['id_affectation']==2){
								$classAffectation="text-orange";
							}elseif($palette['id_affectation']==1){
								$classAffectation="text-primary";
							}
							?>

							<li>
								<a href="detail-palette.php?id=<?=$palette['paletteid']?>" class="<?=$classAffectation?>"><?=$palette['palette']?></a> :
								<?php if (isset($listStatutPalette[$palette['statut']])): ?>
									<?=$listStatutPaletteIco[$palette['statut']]. '<span class="pl-3 '.$classAffectation.'">'.$listStatutPalette[$palette['statut']]?></span>
								<?php else: ?>
									<i class="fas fa-hourglass-start text-danger"></i><span class="pl-3 <?=$classAffectation?>">en stock</span>

								<?php endif ?>

							</li>
						<?php endforeach ?>
					</ul>
				<?php else: ?>
					<p>aucune palette de casse en stock</p>
				<?php endif ?>
				<p class="alert alert-primary">Cliquez sur une palette pour en afficher le contenu et la positionner sur une livraison</p>
			</div>
		</div>
		<div class="row">
			<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
		</div>
		<div class="bg-separation"></div>
		<div class="row py-3">
			<div class="col">
				<h5 class="text-main-blue" id="traitement">Palettes à livrer :</h5>
			</div>
		</div>


		<?php if (!empty($expeds)): ?>
			<?php include "casse-dashboard/14-table-en-stock.php" ?>
		<?php else: ?>
			aucune palette n'a été sélectionnée pour une livraison magasin
		<?php endif ?>

		<div class="row">
			<div class="col text-right"><a href="#mini-menu" class="uplink">retour</a></div>
		</div>
	</div>


	<div class="modal fade" id="edit-palette" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title" id="modal-label">Modifier la palette</h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="palette-modal">Numéro de palette :</label>
									<input type="text" class="form-control" name="palette_modal" id="palette-modal">
									<input type="hidden" class="form-control" name="id_palette" id="id-palette-modal">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col text-right">
								<button class="btn btn-primary" name="update_palette"><i class="fas fa-save pr-3"></i>Enregistrer</button>
							</div>
						</div>
					</form>

				</div>

			</div>
		</div>
	</div>





	<script src="../js/sortmultitable.js"></script>

	<script type="text/javascript">
		function sortTable(n) {
			sort_table(document.getElementById("palettes"), n);
		}
		url=window.location.href;

		var url = window.location.href;
		var splited=url.split("#");
		console.log(url);
		if(splited[1]==undefined){
			var line='';
		}
		else if(splited.length==2){
			var line=splited[1];
			console.log(line);
			// line=line.replace("palette-", "");
			$('#'+line).addClass("anim");
		}



		// // url.searchParams.get("field_1");
		// const urlSearchParams = new URLSearchParams(window.location.search);
		// const params = Object.fromEntries(urlSearchParams.entries());
		// console.log(params);
		$(document).ready(function(){
			$('#edit-palette').on('show.bs.modal', function (e) {
				var idPalette=$(e.relatedTarget).data('id-palette');
				var palette=$(e.relatedTarget).data('palette');
				$('#palette-modal').val(palette);
				$('#id-palette-modal').val(idPalette);

			});


			$('button#submit_search[type="submit"]').attr('disabled','disabled');
			$("input[name='field']").on("click", function(){
				$('button#submit_search[type="submit"]').removeAttr('disabled');
			})
			// boite de dialogue confirmation clic sur lien
			$('.red-link').on('click', function(e){
				var webid='<?php echo $_SESSION['id_web_user'];?>';
				console.log(webid);
				if(webid!=981 && webid!=959 && webid !=1043 && webid != 1279){
					alert("vous n'avez pas les droits pour supprimer une casse. Merci de faire votre demande à Christelle Trousset et/ou Nathalie Pazik ");
					e.preventDefault();
				}else{
					return confirm('Etes vous sûrs de vouloir supprimer cette casse ?');
				}
			});
			$('#mailpilote').on('click', function(){
				return confirm('Envoyer le mail de demande de contrôle des palettes aux pilotes ?');
			});
			$('#mailMag').on('click', function(){
				return confirm('Envoyer le mail d\'information au magasin ?');
			});

			// field_1=pending
			$('.legend').on('click', function(){
				var param = $(this).attr('data-statut');;
				const params = new URLSearchParams(location.search);
				params.set('field_1', param);
				window.history.replaceState({}, '', `${location.pathname}?${params}`);
				location.reload();

			});
			$('#affectation').on('change', function(){
				var param = $("option:selected", this).val();
				const params = new URLSearchParams(location.search);
				params.set('field_2', param);
				window.history.replaceState({}, '', `${location.pathname}?${params}`);
				location.reload();
			});
		});
	</script>



	<?php
	require '../view/_footer-bt.php';
?>