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


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';
require_once '../../Class/FormHelpers.php';
require_once '../../Class/OpportuniteDAO.php';
require_once '../../Class/Helpers.php';

require_once '../../functions/accessCheck.fn.php';


$accessDenied=localAccessDenied($pdoUser,array(89));
if($accessDenied){
	header('Location:'. ROOT_PATH.'/public/home/home.php?access-denied');
}


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];






define("DIR_UPLOAD_OPP",DIR_UPLOAD."opportunites\\");
define("URL_UPLOAD_OPP",URL_UPLOAD."opportunites/");
$notMandatoryFields=[
	0 =>[
		'field' =>'salon',
		'warning'=>'Le champ salon doit être rempli'
	],
	1 =>[
		'field' =>'cata',
		'warning'=>'Le champ catalogue doit être rempli'
	],
	2 =>[
		'field' =>'title',
		'warning'=>'Le champ titre doit être rempli'
	],
	3 =>[
		'field' =>'date_start',
		'warning'=>'Vous devez saisir une date de mise en ligne'
	],

	4 =>[
		'field' =>'date_end',
		'warning'=>'Vous devez saisir une date de mise à dispo'
	]
];
$ico=["Nouveauté", "TEL", "BRII", "ODR"];
$imgExt=['jpg', 'jpeg', 'png','gif'];


$oppDao=new OpportuniteDAO($pdoBt);

$listOpp=$oppDao->getOpp($_GET['id']);
$oneOpp=$listOpp[0];
$oppIds=[$_GET['id']];
$listMainFiles=$oppDao->getListMainFiles($oppIds);
$listAddonsFiles=$oppDao->getListAddonsFiles($oppIds);
$listIcons=$oppDao->getListIcons($oppIds);
$oneOppIcons=[];
if(isset($listIcons[$_GET['id']])){
	$oneOppIcons=$listIcons[$_GET['id']];
}


if(isset($_POST['update'])){
	foreach ($notMandatoryFields as $key => $field) {
		if(empty($_POST[$field['field']])){
			$errors[]=$field['warning'];
		}
	}

	if(empty($errors)){
		$oppDao->updateOpportunite($_GET['id']);
		$oppDao->deleteOppIcons($_GET['id']);
		if(isset($_POST['icons'])){
			$oppDao->addIcons($_GET['id'],$_POST['icons']);
		}
		$successQ='?id='.$_GET['id'].'&success=maj#main-mod';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}
if(isset($_POST['new-order'])){
	for ($i=0; $i < count($_POST['ordre']) ; $i++) {
		$req=$pdoBt->prepare("UPDATE opp_files_main SET ordre = :ordre WHERE id = :id");
		$req->execute([
			':ordre'		=>$_POST['ordre'][$i],
			':id'		=>$_POST['main_file_id'][$i],
		]);
	}
	$successQ='?id='.$_GET['id'].'&success=ordre';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if(isset($_POST['add_new_files'])){

	$idOpp=$_GET['id'];
	if(isset($idOpp)&& ($idOpp>0)){

		for ($i=0; $i <count($_POST['file_opp_files']) ; $i++) {
			$ext = pathinfo($_POST['file_opp_files'][$i], PATHINFO_EXTENSION);
			$image=(in_array(strtolower($ext),$imgExt))? 1:0;
			$oppDao->addMainFile($idOpp,$_POST['file_opp_files'][$i],$_POST['readable_opp_files'][$i], $image,$_POST['ordre_opp_files'][$i]);
		}

		if(isset($_POST['icons'])){
			$oppDao->addIcons($idOpp,$_POST['icons']);
		}
		if(!empty($_POST['file_addons_files'])){
			for ($i=0; $i < count($_POST['file_addons_files']); $i++) {
				$oppDao->addAddonsFile($idOpp,$_POST['file_addons_files'][$i], $_POST['readable_addons_files'][$i]);
			}
		}
		header("Location: opp-edit.php?id=".$idOpp,true,303);


	}else{
		$errors[]="Une erreur est survenue, impossible d'enregistrer l'opportunité";
	}
}

if(isset($_POST['new-name'])){

	for($i=0; $i<count($_POST['id_addon']); $i++){
		$oppDao->updateAddonName($_POST['id_addon'][$i],$_POST['name'][$i]);
	}
	$idOpp=$_GET['id'].'#file-addon-form';

	unset($_POST);
	header("Location: opp-edit.php?id=".$idOpp,true,303);

}

if(isset($_GET['success'])){
	$arrSuccess=[
		'ordre'=>'Ordre des images mis à jour',
		'maj'	=>'Informations mises à jour'
	];
	$success[]=$arrSuccess[$_GET['success']];
}
//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container ">
	<div class="row pt-3">
		<div class="col text-right">
			<a href="opp-exploit.php" class="btn btn-primary">Retour</a>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue" id="main-mod">Modifier l'opportunité</h1>
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

	<div class="row">
		<div class="col">
			<h5 class="border-bottom no-top-padding"><i class="fas fa-pencil-alt pr-3"></i>Modifier les infos générales</h5>
		</div>
	</div>

	<div class="row">
		<div class="col  p-3 text-main-blue">
			<form method="post" enctype="multipart/form-data" name="new_opp" id="new_opp">
				<div class="row">
					<div class="col-xl-6">
						<div class="form-group">
							<label for="title">Titre de l'opportunité :</label>
							<input type="text" class="form-control" name="title" id="title" value="<?=FormHelpers::restoreValue($oneOpp['title'])?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="date_start">Date de début (mise en ligne)</label>
							<input type="date" class="form-control date-width" name="date_start" id="date_start"
							value="<?=FormHelpers::restoreValue($oneOpp['date_start'])?>">
						</div>
					</div>

					<div class="col">
						<div class="form-group">
							<label for="date_end">Date de fin (fin de remontée)</label>
							<input type="date" class="form-control date-width" name="date_end" id="date_end"
							value="<?=FormHelpers::restoreValue($oneOpp['date_end'])?>"
							>
						</div>
					</div>
				</div>
				<div class="row">

					<div class="col">
						<div class="form-group">
							<label for="salon">Numéro de salon :</label>
							<input type="text" class="form-control" name="salon" id="salon" required
							value="<?=FormHelpers::restoreValue($oneOpp['salon'])?>"
							>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="cata">Catalogue :</label>
							<input type="text" class="form-control" name="cata" id="cata" required
							value="<?=FormHelpers::restoreValue($oneOpp['cata'])?>"
							>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="dispo">Dispo entrepôt :</label>
							<input type="text" class="form-control" name="dispo" id="dispo"
							value="<?=FormHelpers::restoreValue($oneOpp['dispo'])?>"
							>
						</div>
					</div>
					<div class="col">
						<label>GT :</label>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="0" id="gt-blanc" name="gt" <?=FormHelpers::restoreChecked(0,$oneOpp['gt'])?>>
							<label class="form-check-label" for="gt-blanc">Blanc</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="1" id="gt-blanc" name="gt" <?=FormHelpers::restoreChecked(1,$oneOpp['gt'])?>>
							<label class="form-check-label" for="gt-blanc">Multimédia</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="descr">Description :</label>
							<textarea class="form-control" name="descr" id="descr" row="3"><?=FormHelpers::restoreValue($oneOpp['descr'])?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<p class="heavy pt-2">Ajouter des icônes :</p>
						<?php for($i=0; $i< count($ico); $i++):?>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="<?=$i?>" id="icon1" name="icons[]"
								<?= (in_array($i,$oneOppIcons)) ? "checked" :""?>>
								<label class="form-check-label" for="icon1"><?= $ico[$i]?></label>
							</div>
						<?php endfor ?>

					</div>

				</div>
				<div class="row">
					<div class="col text-right">
						<button class="btn btn-primary" name="update">Modifier</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row mb-3" id="delete-main">
		<div class="col">
			<h5 class="border-bottom"><i class="fas fa-pencil-alt pr-3"></i>Supprimer / changer l'ordre des fichiers opportunité</h5>

		</div>
	</div>
	<?php if (isset($listMainFiles[$oneOpp['id']])): ?>
		<div class="row">
			<div class="col">
				<form method="post" name="file-main-form" id="file-main-form">

					<table class="table w-auto table-sm table-bordered">
						<thead class="bg-grey-table">
							<tr>
								<th class="px-5 text-center">Nom du fichier</th>
								<th class="px-5 text-center">Image</th>
								<th class="px-5 text-center ">Suppression</th>

								<th class="px-5 text-center">Ordre</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($listMainFiles[$oneOpp['id']] as $key => $mainFile): ?>
								<tr>
									<td class="px-5 text-center"><a href="<?=URL_UPLOAD_OPP.$mainFile['filename']?>"><?=$mainFile['filename']?></a></td>
									<td class="px-5 text-center"><?=($mainFile['image']==1)? "<img class='vignette' src='".URL_UPLOAD_OPP.$mainFile['filename']."'> " :""?></td>
									<td class="px-5 text-center">
										<a href="opp-delete-file.php?idmain=<?=$mainFile['id'].'&id='.$_GET['id']?>" class="btn btn-orange" name="delete">Supprimer</a>
									</td>
									<td class="px-5 text-center">
										<input class="mini-input" type="text" name="ordre[]" value="<?=$mainFile['ordre']?>">
										<input type="hidden" name="main_file_id[]" value=<?=$mainFile['id']?>>
									</td>

								</tr>

							<?php endforeach ?>
							<tr class="bg-grey-table">
								<td class="text-center text-main-blue font-italic" colspan="3"><i class="fas fa-lightbulb pr-3"></i>Si vous avez modifié l'ordre d'apparition des images, cliquez sur le bouton enregistrer</td>
								<td  class="text-center" >
									<button class="btn btn-primary" name="new-order">Enregistrer</button>

								</td>
							</tr>
						</tbody>
					</table>

				</form>
			</div>
		</div>
	<?php endif ?>


	<?php if (isset($listAddonsFiles[$oneOpp['id']])): ?>
		<div class="bg-separation"></div>
		<div class="row mb-3" id="delete-addons">
			<div class="col">
				<h5 class="border-bottom"><i class="fas fa-pencil-alt pr-3"></i>Supprimer des fichiers joints</h5>

			</div>
		</div>
		<div class="row">
			<div class="col">
				<form method="post" class="form-inline" name="file-addon-form" id="file-addon-form">

					<table class="table w-auto table-sm table-bordered-special">
						<thead class="bg-grey-table">
							<tr>
								<th class="px-5 text-center">Nom du fichier</th>
								<th class="px-5 text-center">Intitulé du lien</th>
								<th class="px-5 text-center ">Suppression</th>

							</tr>
						</thead>
						<tbody>
							<?php foreach ($listAddonsFiles[$oneOpp['id']] as $key => $addonsFile): ?>
								<tr>
									<td class="px-5 text-center">
										<a href="<?=URL_UPLOAD_OPP.$addonsFile['filename']?>"><?= $addonsFile['filename']?></a><br>

									</td>
									<td>

										<div class="form-group">
											<label for="name"></label>
											<input type="text" class="form-control wider" name="name[]" id="name" value="<?=(!empty($addonsFile['name'])) ? $addonsFile['name']:''?>">
											<input type="hidden" name="id_addon[]" value="<?=$addonsFile['id']?>">
										</div>
									</td>

									<td class="px-5 text-center">
										<a href="opp-delete-file.php?idaddons=<?=$addonsFile['id'].'&id='.$_GET['id']?>" class="btn btn-orange" name="delete">Supprimer</a>
									</td>

								</tr>

							<?php endforeach ?>
							<tr>
								<td></td>
								<td class="text-right">
									<button class="btn btn-primary" name="new-name"><i class="fas fa-save pr-3"></i>Enregistrer</button>
								</td>
								<td></td>
							</tr>

						</tbody>
					</table>

				</form>
			</div>
		</div>
	<?php endif ?>



	<div class="bg-separation"></div>
	<div class="row">
		<div class="col">
			<h5 class="border-bottom"><i class="fas fa-pencil-alt pr-3"></i>Ajouter un ou des fichiers opportunité :</h5>

		</div>
		<div class="col">
			<h5 class="border-bottom" ><i class="fas fa-pencil-alt pr-3"></i>Ajouter une ou des pièces jointes :</h5>

		</div>
	</div>
	<form method="post" enctype="multipart/form-data" name="file-form" id="file-form">
		<div class="row">
			<div class="col">

				<div class="row">
					<div class="col" id="opp_files">
						<input type="file" name="file[]" class="dragndropfile" multiple="multiple">
						<div class="upload-area uploadfile">
							<p>Glisser/déposer les fichiers<br/>ou<br/>cliquez ici pour les sélectionner</p>
						</div>
						<div class="filename"></div>
						<div class="readablename"></div>
					</div>
				</div>
			</div>

			<div class="col">

				<div class="row">
					<div class="col" id="addons_files">
						<input type="file" name="file[]" class="dragndropfile" multiple="multiple">
						<div class="upload-area uploadfile">
							<p>Glisser/déposer les fichiers<br/>ou<br/>cliquez ici pour les sélectionner</p>
						</div>
						<div class="filename"></div>
						<div class="readablename"></div>
					</div>
				</div>
			</div>
		</div>


		<div class="row mt-3">
			<div class="col"></div>
			<div class="col text-center">
				<div class="form-group">
					<input type="submit" class=" btn btn-primary" name="add_new_files" id="add_new" value="Enregistrer">
				</div>
			</div>
			<div class="col pt-2">
				<div id="wait"></div>
			</div>
		</div>
	</form>
	<!-- ./container -->
</div>
<script src="../js/dragndrop.js"></script>

<script type="text/javascript">

	$(document).ready(function(){
		$("html").on("dragover", function(e) {
			e.preventDefault();
			e.stopPropagation();
		});
		$("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

		var idOpp="#opp_files";
		var readable=true;
		var order=true;

		$(idOpp +' .upload-area').on('dragenter', function (e) {
			e.stopPropagation();
			e.preventDefault();
			$(idOpp + " .upload-area p").text("Déposez");
		});

		$(idOpp +' .upload-area').on('dragover', function (e) {
			e.stopPropagation();
			e.preventDefault();
			$(idOpp +" .upload-area p").text("Déposez");
		});

		$(idOpp +' .upload-area').on('drop', function (e) {
			e.stopPropagation();
			e.preventDefault();

			var files = e.originalEvent.dataTransfer.files;
			console.log(" drope" +files);

			var fd = new FormData();
			for (var i = 0; i < files.length; i++) {
				fd.append('file[]', files[i]);
			}
			uploadData(fd, 'drag-and-drop.php', idOpp, readable, order );
		});

		$(idOpp +" .uploadfile").click(function(){
			$(idOpp +" .dragndropfile").click();
		});

		$(idOpp +" .dragndropfile").change(function(){
			var fd = new FormData();
			var nbFiles=($(idOpp +' .dragndropfile')[0].files).length;
			console.log(" change" +nbFiles);

			for (var i = 0; i < nbFiles; i++) {
				var file = $(idOpp +' .dragndropfile')[0].files[i];
				fd.append('file[]', file);
			}
			console.log(fd);
			uploadData(fd, 'drag-and-drop.php', idOpp,  readable, order);
		});
		var idAddOns="#addons_files";
		var readableAddOns=true;
		var orderAddOns=false;

		$(idAddOns +' .upload-area').on('dragenter', function (e) {
			e.stopPropagation();
			e.preventDefault();
			$(idAddOns + " .upload-area p").text("Déposez");
		});

		$(idAddOns +' .upload-area').on('dragover', function (e) {
			e.stopPropagation();
			e.preventDefault();
			$(idAddOns +" .upload-area p").text("Déposez");
		});

		$(idAddOns +' .upload-area').on('drop', function (e) {
			e.stopPropagation();
			e.preventDefault();
			var files = e.originalEvent.dataTransfer.files;
			var fd = new FormData();
			for (var i = 0; i < files.length; i++) {
				fd.append('file[]', files[i]);
			}
			uploadData(fd, 'drag-and-drop.php', idAddOns, readableAddOns, orderAddOns );
		});

		$(idAddOns +" .uploadfile").click(function(){
			$(idAddOns +" .dragndropfile").click();
		});

		$(idAddOns +" .dragndropfile").change(function(){
			var fd = new FormData();
			var nbFiles=($(idAddOns +' .dragndropfile')[0].files).length;

			for (var i = 0; i < nbFiles; i++) {
				var file = $(idAddOns +' .dragndropfile')[0].files[i];
				fd.append('file[]', file);

			}
			uploadData(fd, 'drag-and-drop.php', idAddOns,  readable, order);
		});


		$("#new_opp").submit(function(){
			console.log("ddd");
			$("#wait" ).append('<i class="fas fa-spinner"></i>&nbsp;&nbsp;<span class="pl-3">Merci de patienter</span>')
		});



	});

</script>
<?php
require '../view/_footer-bt.php';
?>