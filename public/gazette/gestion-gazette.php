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
require '../../Class/GazetteDao.php';

// require_once '../../vendor/autoload.php';

$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');


$gazetteDao=new GazetteDao($pdoDAchat);

$catBt=$gazetteDao->getCatByMain(1);
$catGalec=$gazetteDao->getCatByMain(2);
$mainCat=[1 =>"btlec", 2 =>"galec"];

$listCat=$gazetteDao->getCat();
$listGazette=$gazetteDao->getGazetteEnCours();
if(!empty($listGazette)){
	$listFiles=[];
	$listLinks=[];
	$listFiles=$gazetteDao->getFilesEncours();
	$listLinks=$gazetteDao->getLinkEncours();
}
if(isset($_POST['add-gazette'])){
	require 'gestion-gazette/01-add-gazette.php';
}

if(isset($_POST['search'])){
	if(!empty($_POST['strg'])){
		$searchResults=$gazetteDao->getGazetteString($_POST['strg']);
		$searchResultsLink=$gazetteDao->getLinkString($_POST['strg']);
		$searchResultsFiles=$gazetteDao->getFilesString($_POST['strg']);
	}
	if(!empty($_POST['date_start'])){
		if(!empty($_POST['date_end'])){
			$dateEnd=$_POST['date_end'];
		}else{
			$dateEnd=date('Y-m-d');
		}
		$searchResults=$gazetteDao->getGazettePeriode($_POST['date_start'], $dateEnd);
		$searchResultsLink=$gazetteDao->getLinkPeriode($_POST['date_start'], $dateEnd);
		$searchResultsFiles=$gazetteDao->getFilesPeriode($_POST['date_start'], $dateEnd);
	}
}

if(isset($_POST['add_cat'])){
	if(empty($_POST['main_cat'])){
		$errors[]="Veuillez sélectionner à quelle catégorie vous souhaitez ajouter un type d'information";
	}
	if(empty($errors)){
		$do=$gazetteDao->addCat($_POST['main_cat'], trim($_POST['cat']));
		$successQ='#quatre';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}
}

if(isset($_GET['success'])){
	$arrSuccess=[
		'add'=>'Information gazette ajoutée avec succès',
	];
	$success[]=$arrSuccess[$_GET['success']];
}


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<h1 class="text-main-blue pt-5">Saisie et administration des gazettes</h1>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row" id="un">
		<div class="col">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-newspaper pr-3"></i>Gazettes de la semaine</h5>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<?php if (!empty($listGazette)): ?>
				<?php include 'gestion-gazette/10-table-list-gazette.php' ?>
				<?php else: ?>
					<div class="alert alert-primary">Aucune gazette à afficher pour la semaine en cours</div>
				<?php endif ?>
			</div>
		</div>
		<div class="bg-separation"></div>
		<div class="row" id="deux">
			<div class="col">
				<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-search pr-3"></i>Rechercher une gazette</h5>
			</div>
		</div>
		<?php include('gestion-gazette/12-form-search.php') ?>
		<?php if (isset($searchResults)): ?>
			<?php include('gestion-gazette/13-table-result.php') ?>
		<?php endif ?>
		<div class="bg-separation"></div>
		<div class="row" id="trois">
			<div class="col">
				<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-pencil-alt pr-3"></i>Ajout de gazette</h5>
			</div>
		</div>
		<?php include 'gestion-gazette/11-form-add-gazette.php' ?>
		<div class="bg-separation"></div>

		<div class="row" id="quatre">
			<div class="col">
				<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-plus-circle pr-3"></i>Ajout de type d'information</h5>
			</div>
		</div>
		<div class="row">
			<div class="col-auto">
				<table class="table table-sm w-auto">
					<thead class="thead-light">
						<tr>
							<th>Catégorie</th>
							<th>Type d'info</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($catBt)): ?>
							<?php foreach ($catBt as $keyBt => $value): ?>
								<tr>
									<td>BTLec</td>
									<td><?=$catBt[$keyBt]?></td>
								</tr>
							<?php endforeach ?>
						<?php endif ?>
						<?php if (!empty($catGalec)): ?>
							<?php foreach ($catGalec as $keyGalec => $value): ?>
								<tr>
									<td>Galec</td>
									<td><?=$catGalec[$keyGalec]?></td>
								</tr>
							<?php endforeach ?>
						<?php endif ?>
					</tbody>
				</table>
			</div>
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="main_cat">Catégorie :</label>
								<select class="form-control form-primary" name="main_cat" id="main_cat" required>
									<option value="">Sélectionner</option>
									<option value="1">BTLEC</option>
									<option value="2">GALEC</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="cat">Type d'information : </label>
								<input type="text" class="form-control  form-primary" name="cat" id="cat">
							</div>
						</div>
					</div>
					<div class="row pb-5">
						<div class="col text-right">
							<button class="btn btn-primary" name="add_cat">Ajouter</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div id="floating-nav">
			<h6 class="text-main-blue text-center">Aller à</h6>
			<div class="pb-2"><i class="fas fa-newspaper fa-sm circle-icon-blue mr-3"></i><a href="#un">Gazettes de la semaine</a></div>
			<div class="pb-2"><i class="fas fa-search fa-sm circle-icon-blue mr-3 p-euro"></i><a href="#deux">Recherche de gazette</a></div>
			<div class="pb-2"><i class="fas fa-pencil-alt fa-sm circle-icon-orange mr-3"></i><a href="#trois">Ajout de gazette</a></div>
			<div class="pb-2"><i class="fas fa-plus fa-sm circle-icon-orange mr-3 p-add"></i><a href="#quatre">Ajout de type d'information</a></div>
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

		$(document).ready(function() {
			$('#main_cat').on('change', function() {

				var main_cat=$('#main_cat').val();
				$.ajax({
					type:'POST',
					url:'ajax-g-cat.php',
					data:{main_cat:main_cat},
					success: function(html){
						$("#cat").empty();
						$("#cat").append(html);
					}
				});
			});

			$('input[name="gazette_files[]"]').change(function(){
				var totalSize=0;
				var fileName='';
				var fileList='';
				var nbFiles = $(this).get(0).files.length;
				var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'xls', 'xlsx'];
				var warning  ="";
				var interdit=false;

				for (var i = 0; i < nbFiles; ++i) {
					var fileSize=$(this).get(0).files[i].size;
					fileName=$(this).get(0).files[i].name;
					totalSize = totalSize+fileSize;
					var extension=fileName.replace(/^.*\./, '');
					if ($.inArray(extension, fileExtension)==-1) {
						warning="<i class='fas fa-times px-3 text-danger'></i>";
						interdit=true;
					}else{
						warning="<i class='fas fa-check px-3 text-success'></i>";

					}
					fileList += fileName + warning+'<br>';
				}

				if(totalSize <= 52428800 && interdit==false){
					$("#file-msg-gazette").text("");
					$("#file-msg-gazette").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(totalSize)+"</div>");
					$('button[type="submit"]').removeAttr('disabled','disabled');

				}
				if(interdit==true){
					$("#file-msg-gazette").text("");
					$('button[type="submit"]').attr('disabled','disabled');
					$("#file-msg-gazette").append("<div class='text-danger'>Vous avez sélectionnés des types de fichiers interdits (<i class='fas fa-times px-1 text-danger'></i>), merci de modifier votre sélection</div>");
				}
				if(totalSize > 52428800 && interdit==false){
					$("#file-msg-gazette").text("");
					$('button[type="submit"]').attr('disabled','disabled');
					$("#file-msg").append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(totalSize)+".<br>La taille est limitée à 50Mo, votre ODR ne pourra pas être enregistrée</div>");

				}

				titre='<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span>'
				end='</p>';
				all=titre+fileList+end;
				$('#gazette-filenames').empty();

				$('#gazette-filenames').append(all);
				fileList="";
			});

		});

	</script>
	<?php
	require '../view/_footer-bt.php';
	?>