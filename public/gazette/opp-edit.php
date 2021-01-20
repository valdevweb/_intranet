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
	if(isset($_FILES['opp_files']['tmp_name'][0]) && !empty($_FILES['opp_files']['tmp_name'][0])){

		for ($i=0; $i <count($_FILES['opp_files']['tmp_name']) ; $i++) {
			$orginalFilename=$_FILES['opp_files']['name'][$i];
			$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

			$filenameNoExt = basename($orginalFilename, '.'.$ext);
			$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;
			// echo $filename;
			// echo "<br>";
			$uploaded=move_uploaded_file($_FILES['opp_files']['tmp_name'][$i],DIR_UPLOAD_OPP.$filename );
			if($uploaded==false){
				$errors[]="Nous avons rencontré avec votre fichier, votre demande n'a pas pu être enregistrée";
			}else{
				$listFilenameOpp[]=$filename;
			}
		}
	}

	if(isset($_FILES['addons_files']['tmp_name'][0]) &&  !empty($_FILES['addons_files']['tmp_name'][0])){

		for ($i=0; $i <count($_FILES['addons_files']['tmp_name']) ; $i++) {
			$orginalFilename=$_FILES['addons_files']['name'][$i];
			$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);
			$filenameNoExt = basename($orginalFilename, '.'.$ext);
			$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;
			// echo $filename;
			// echo "<br>";
			$uploaded=move_uploaded_file($_FILES['addons_files']['tmp_name'][$i],DIR_UPLOAD_OPP.$filename );
			if($uploaded==false){
				$errors[]="Nous avons rencontré avec votre fichier, votre demande n'a pas pu être enregistrée 2";
			}else{
				$listFilenameAddons[]=$filename;
			}
		}
	}
	if(empty($errors)){
		$idOpp=$_GET['id'];
		if(isset($idOpp)&& ($idOpp>0)){
			for ($i=0; $i < count($listFilenameOpp); $i++) {
				$ext = pathinfo($listFilenameOpp[$i], PATHINFO_EXTENSION);
				$image=(in_array(strtolower($ext),$imgExt))? 1:0;
				$oppDao->addMainFile($idOpp,$listFilenameOpp[$i],$image,$i);
			}
			if(!empty($listFilenameAddons)){
				for ($i=0; $i < count($listFilenameAddons); $i++) {
					$oppDao->addAddonsFile($idOpp,$listFilenameAddons[$i], $_POST['intitule'][$i]);
				}
			}

			header("Location: opp-edit.php?id=".$idOpp,true,303);

		}else{
			$errors[]="Une erreur est survenue, impossible d'enregistrer l'opportunité";
		}
	}
}

if(isset($_POST['new-name'])){
	echo "<pre>";
	print_r($_POST);
	echo '</pre>';
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
							<input type="text" class="form-control" name="salon" id="salon"
							value="<?=FormHelpers::restoreValue($oneOpp['salon'])?>"
							>
						</div>
					</div>
					<div class="col">
						<div class="form-group">
							<label for="cata">Catalogue :</label>
							<input type="text" class="form-control" name="cata" id="cata"
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
										<a href="<?=URL_UPLOAD_OPP.$addonsFile['filename']?>"><?= Helpers::removeLastStringElt($addonsFile['filename'],"_"," ")?></a><br>

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

				<div class="row rounded bg-blue-input mx-1 pt-2">
					<div class="col">
						<div id="filelistopp">
							<span class="text-main-blue heavy">Fichier(s) sélectionnés : <br></span>
						</div>
					</div>
					<div class="col text-right align-self-end">
						<div class="form-group">
							<label class="btn btn-upload btn-file text-center">
								<input type="file" name="opp_files[]" class='form-control-file' multiple="">
								Sélectionner
							</label>
						</div>
						<div class="form-group">
							<input type="hidden" class="form-control" name="hidden_opp_size" id="hidden_opp_size">
						</div>
					</div>
				</div>
			</div>

			<div class="col">

				<div class="row rounded bg-blue-input mx-1  pt-2">
					<div class="col">
						<div id="filelistaddon">
							<span class="text-main-blue heavy">Fichier(s) sélectionnés : <br></span>
						</div>
					</div>
					<div class="col text-right align-self-end">
						<div class="form-group">
							<label class="btn btn-upload btn-file text-center">
								<input type="file" name="addons_files[]" class='form-control-file' multiple="">
								Sélectionner
							</label>
						</div>
						<div class="form-group">
							<input type="hidden" class="form-control" name="hidden_addons_size" id="hidden_addons_size">
						</div>
					</div>
				</div>
				<div class="row mt-3">

					<div class="col" id="zone-intitule">
					</div>
				</div>
			</div>
		</div>


		<div class="row mt-5">
			<div class="col">
				<div class="file-error"></div>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div class="alert alert-warning">
					<i class="fas fa-lightbulb pr-3"></i>Pour sélectionner plusieurs fichiers, maintenez la touche CTRL appuyée
				</div>
			</div>
		</div>
		<div class="row">
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




	<!-- 	</div>
		<div class="col-md-1 col-lg-2"></div>
	</div>
-->



<!-- ./container -->
</div>
<script type="text/javascript">

	$(document).ready(function(){

		function getReadableFileSizeString(fileSizeInBytes) {
			var i = -1;
			var byteUnits = [' ko', ' Mo', ' Go'];
			do {
				fileSizeInBytes = fileSizeInBytes / 1024;
				i++;
			} while (fileSizeInBytes > 1024);

			return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
		};



		$('input[name="opp_files[]"]').change(function(){
			var totalSize=0;
			var fileName='';
			var fileList='';
			var nbFiles = $(this).get(0).files.length;


			for (var i = 0; i < nbFiles; ++i) {
				var fileSize=$(this).get(0).files[i].size;
				fileName=$(this).get(0).files[i].name;
				totalSize = totalSize+fileSize;
				fileList += fileName + '<br>';
			}


			$('#hidden_opp_size').val(totalSize);
			hiddenAddonSize=$('#hidden_addons_size').val();

			if(typeof(hiddenAddonSize)!="undefined" && hiddenAddonSize!=null && hiddenAddonSize!=0){
				totalSize=parseInt(hiddenAddonSize)+totalSize;
			}


			if(totalSize <= 52428800){
				$(".file-error").text("");
				$('input[type="submit"]').removeAttr('disabled','disabled');
				console.log("taille totale ok " + getReadableFileSizeString(totalSize));

			}else{
				$('input[type="submit"]').attr('disabled','disabled');
				$(".file-error").append("<div class='alert alert-danger'>Impossible de télécharger les fichiers vers le serveur, la taille totale des fichiers est de "+getReadableFileSizeString(totalSize)+". Vous ne pouvez pas dépasser 50Mo</div>");

			}

			titre='<p><span class="text-main-blue heavy">Fichier(s) sélectionnés: <br></span>'
			end='</p>';
			all=titre+fileList+end;
			$('#filelistopp').empty();

			$('#filelistopp').append(all);
			fileList="";
		});




		$('input[name="addons_files[]"]').change(function(){

			var totalSize=0;
			var fileName='';
			var fileList='';
			var nbFiles = $(this).get(0).files.length;
			var formGroup="<div class='form-group'>";
			var endDiv="</div>";
			var titre="<div class='text-main-blue heavy'>Donner un intitulé au fichier :</div>";

			for (var i = 0; i < nbFiles; ++i) {
				var fileSize=$(this).get(0).files[i].size;
				fileName=$(this).get(0).files[i].name;
				totalSize = totalSize+fileSize;
				fileList += fileName + '<br>';

				var label="<label>"+fileName+"</label>";
				var input="<input type='text' class='form-control'  name='intitule[" +i +"]'>";

				$("#zone-intitule").append(titre+formGroup+label+input+endDiv);
			}

			$('#hidden_addons_size').val(totalSize);
			hiddenOppSize=$('#hidden_opp_size').val();

			if(typeof(hiddenOppSize)!="undefined" && hiddenOppSize!=null && hiddenOppSize!=0){
				totalSize=parseInt(hiddenOppSize)+totalSize;
			}


			if(totalSize <= 52428800){
				$(".file-error").text("");
				$('input[type="submit"]').removeAttr('disabled','disabled');
				console.log("taille totale ok "+ getReadableFileSizeString(totalSize));

			}else{
				$('input[type="submit"]').attr('disabled','disabled');
				$(".file-error").append("<div class='alert alert-danger'>Impossible de télécharger les fichiers vers le serveur, la taille totale des fichiers est de "+getReadableFileSizeString(totalSize)+". Vous ne pouvez pas dépasser 50Mo</div>");


				console.log("nb de fichier et taille totale NON ok. taille" +getReadableFileSizeString(totalSize));

			}
			titre='<p><span class="text-main-blue heavy">Fichier(s) sélectionnés : <br></span>'
			end='</p>';
			all=titre+fileList+end;
			$('#filelistaddon').empty();
			$('#filelistaddon').append(all);
			fileList="";


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