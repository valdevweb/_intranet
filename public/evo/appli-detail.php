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
require '../../Class/evo/AppliDao.php';
require '../../Class/evo/ModuleDao.php';
require '../../Class/evo/ChgLogDao.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoEvo=$db->getPdo('evo');


$appliDao=new AppliDao($pdoEvo);
$moduleDao=new ModuleDao($pdoEvo);
$chgDao=new ChgLogDao($pdoEvo);


if(!isset($_GET['id'])){
	echo "une erreur de navigation s'est produite";
	exit;

}

$thisAppli=$appliDao->getAppli($_GET['id']);
$docAppli=$appliDao->getDocAppli($_GET['id']);
$listModules=$moduleDao->getListModule($_GET['id']);


if(isset($_POST['submit_changelog'])){

	if(empty($_POST['changelog']) || empty($_POST['date_chglog'])){
		$errors[]="MErci de saisir le texte du change log et la date";
	}

	if(empty($errors)){


		$idChg=$chgDao->insertChgLog($_GET['id'],null, null);
		if(isset($_FILES['files']['tmp_name'][0]) &&  !empty($_FILES['files']['tmp_name'][0])){

			for ($i=0; $i <count($_FILES['files']['tmp_name']) ; $i++) {
				$orginalFilename=$_FILES['files']['name'][$i];
				$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);
				$filenameNoExt = basename($orginalFilename, '.'.$ext);
				$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;
				$uploaded=move_uploaded_file($_FILES['files']['tmp_name'][$i],DIR_UPLOAD.'evo-doc\\'.$filename );
				if($uploaded==false){
					$errors[]="Nous avons rencontré avec votre fichier, votre demande n'a pas pu être enregistrée 2";
				}else{
					$listFilename[]=$filename;
				}
			}
		}
		// if(!empty($listFilename)){
		// 	for ($i=0; $i < count($listFilename); $i++) {
		// 		$oppDao->addAddonsFile($idOpp,$listFilename[$i], $_POST['intitule'][$i]);
		// 	}
		// }

	}





}


if(empty($thisAppli)){
	echo "l'application recherchée n'existe pas";
	exit;
}


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container pb-5">
	<div class="row pt-5 pb-3">
		<div class="col">
			<h1 class="text-main-blue">Application : <?=$thisAppli['appli']?></h1>
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

	<div class="row shadow p-3">
		<div class="col-auto text-orange ">
			Plateforme : <br>
			URL : <br>
			Chemin :<br>
		</div>
		<div class="col">
			<?=$thisAppli['plateforme']?><br>
			<?=$thisAppli['url']?><br>
			<?=$thisAppli['path']?><br>
		</div>
		<div class="col-auto text-orange">
			Responsable :
		</div>
		<div class="col-auto">
			<?=$thisAppli['resp']?>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<div class="hr-orange-small"></div>

		</div>
	</div>
	<div class="row mt-5">
		<div class="col">
			<h5 class="text-orange">Documents</h5>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php if (!empty($docAppli)): ?>
				<div class="cols-four">
					<?php foreach ($docAppli as $key => $doc): ?>
						<i class="far fa-file-alt pr-3"></i><a href="<?=URL_UPLOAD.'/evo-doc'.$doc['doc_link']?>" target="_blank" class="grey-link"><?=$doc['doc_name']?></a>
					<?php endforeach ?>
				</div>
			<?php else: ?>
				<div class="alert alert-primary">Aucun document à afficher pour cette application</div>
			<?php endif ?>
		</div>
	</div>
	<div class="row">
		<div class="col text-center">
			<button class="btn btn-primary">Ajouter un document</button>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<h5 class="text-orange">ChangeLog</h5>
		</div>
	</div>
	<div class="row">
		<div class="col text-center">
			<button class="btn btn-primary" id="show-changelog">Ajouter un changelog</button>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="hidden" id="form-changelog">

				<div class="row">
					<div class="col">
						<h6>Ajout d'un changelog</h6>
					</div>
				</div>
				<div class="row">
					<div class="col shadow p-3">
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
							<div class="row">
								<div class="col-auto">
									<div class="form-group">
										<label for="date_chglog">Date du change-log:</label>
										<input type="date" class="form-control" name="date_chglog" id="date_chglog"value="<?=date('Y-m-d')?>">
									</div>
								</div>

								<div class="col-3">
									<div class="form-group">
										<label for="version">Version / Référence :</label>
										<input type="text" class="form-control" name="version" id="version">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="form-group">
										<label for="chglog">Change-log :</label>
										<textarea class="form-control" name="changelog" id="changelog" row="3"></textarea>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col">
									<div class="row">
										<div class="col mb-5 text-main-blue text-center sub-title font-weight-bold ">
											Fichiers  :
										</div>
									</div>

									<div class="row">
										<div class="col">
											<div class="font-italic"><i class="fas fa-lightbulb pr-2"></i>Pour sélectionner plusieurs fichiers, maintenez la touche <strong>ctrl</strong> appuyée lors de la sélection</div>
										</div>
									</div>
									<div class="row ">
										<div class="col-8">
											<div class="row bg-blue-input rounded mx-1 pt-2">
												<div class="col" id="filenames">
													<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span></p>
												</div>
											</div>
											<div class="row">
												<div class="col" id="file-size"></div>
											</div>
										</div>
										<div class="col-4 pt-2">
											<div class="form-group">
												<label class="btn btn-upload-primary btn-file text-center">
													<input type="file" name="files[]" class='form-control-file' multiple>
													Sélectionner
												</label>
											</div>
										</div>
									</div>
									<div class="row mt-3">
										<div class="col-4" id="zone-noms"></div>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col text-right">
									<button class="btn btn-primary" name="submit_changelog">Ajouter</button>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<h5 class="text-orange">Modules de l'application</h5>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?php if (!empty($listModules)): ?>
				<div class="cols-four">
					<?php foreach ($listModules as $key => $module): ?>
						<a href="module-detail.php?id=<?=$module['id']?>" class="grey-link">- <?=$module['module']?></a><br>
					<?php endforeach ?>
				</div>
			<?php else: ?>
				<div class="alert alert-primary">Aucun module à afficher pour cette application</div>
			<?php endif ?>
		</div>
	</div>
	<div class="row">
		<div class="col text-center">
			<button class="btn btn-primary">Ajouter un module</button>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<h5 class="text-orange">Evos</h5>
		</div>
	</div>
	<div class="row">
		<div class="col text-center">
			<button class="btn btn-primary">Voir les évo</button>
		</div>
	</div>

</div>
<script type="text/javascript">
	function getReadableFileSizeString(fileSizeInBytes) {
		var i = -1;
		var byteUnits = [' ko', ' Mo', ' Go'];
		do {
			fileSizeInBytes = fileSizeInBytes / 1024;
			i++;
		} while (fileSizeInBytes > 1024);

		return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
	};

	$(document).ready(function(){

		$("#show-changelog").on("click", function() {
			$("#form-changelog").toggleClass("hidden shown");
		});
		$('input[name="files[]"]').change(function(){
			var totalSize=0;
			var fileName='';
			var fileList='';
			var nbFiles = $(this).get(0).files.length;
			var warning  ="";
			var zoneTaille=$("#file-size");
			var zoneAvertissement=$("#file-msg");
			var zoneFilename=$('#filenames');
			var formGroup="<div class='form-group'>";
			var endDiv="</div>";
			var titre="<div class='text-main-blue heavy'>Nommer  ";
			var titreOrdre="<div class='text-main-blue heavy'>Ordre d'affichage:</div>";

			$("#zone-noms").empty();
			for (var i = 0; i < nbFiles; ++i) {
				var fileSize=$(this).get(0).files[i].size;
				fileName=$(this).get(0).files[i].name;
				totalSize = totalSize+fileSize;
				var extension=fileName.replace(/^.*\./, '');
				fileList += fileName + warning+'<br>';
				var input="<input type='text' class='form-control form-primary'  name='filename[" +i +"]'>";
				$("#zone-noms").append(titre+fileName+formGroup+input+endDiv);
			}
			console.log(totalSize);
			if(totalSize <= 52428800){
				zoneTaille.text("");
				zoneTaille.append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(totalSize)+"</div>");
				$('button[type="submit"]').removeAttr('disabled','disabled');

			}

			if(totalSize > 52428800){
				zoneTaille.text("");
				$('button[type="submit"]').attr('disabled','disabled');
				zoneAvertissement.append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(totalSize)+".<br>La taille est limitée à 50Mo, votre ODR ne pourra pas être enregistrée</div>");

			}

			titre='<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span>'
			end='</p>';
			all=titre+fileList+end;
			zoneFilename.empty();

			zoneFilename.append(all);
			fileList="";
		});




	});
</script>



<?php
require '../view/_footer-bt.php';
?>