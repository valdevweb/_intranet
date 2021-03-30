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
require '../../Class/FormHelpers.php';

// require_once '../../vendor/autoload.php';

$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');

if (!isset($_GET['id']) || empty($_GET['id'])) {
	echo "pas de sélection, impossible d'afficher la page. <a href='gestion-gazette.php'>Retour</a>";
	exit();
}

$gazetteDao=new GazetteDao($pdoDAchat);

$catBt=$gazetteDao->getCatByMain(1);
$catGalec=$gazetteDao->getCatByMain(2);
$mainCat=[1 =>"btlec", 2 =>"galec"];


$thisGazette=$gazetteDao->getGazette($_GET['id']);
$listCatByMain=$gazetteDao->getCatByMain($thisGazette['main_cat']);

$listFiles=$gazetteDao->getFiles($_GET['id']);
$listLinks=$gazetteDao->getLinks($_GET['id']);
$links="";



if(isset($_POST['modif-gazette'])){
	require 'modif-gazette/01-modif-gazette.php';
}

if(isset($_POST['modif_link'])){
	foreach ($_POST['linkname'] as $idLink => $value) {
		$gazetteDao->updateLinks($idLink,$_POST['linkname'][$idLink]);
	}
	$successQ='?id='.$_GET['id'].'&success=link#linkformtitle';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if(isset($_POST['modif_file'])){
	foreach ($_POST['filename'] as $idFile => $value) {
		$gazetteDao->updateFiles($idFile,$_POST['filename'][$idFile],$_POST['ordre'][$idFile] );
	}
	$successQ='?id='.$_GET['id'].'&success=file#fileformtitle';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if(isset($_GET['success'])){
	$arrSuccess=[
		'mod'=>'Gazette modifiée avec succès',
		'link'=>'Lien mis à jour',
		'file'=>'Fichier mis à jour',
	];
	$success[]=$arrSuccess[$_GET['success']];
}
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row">
		<div class="col-auto">
			<h1 class="text-main-blue py-5 ">Modification d'information gazette</h1>

		</div>
		<div class="col text-right pt-5">
			<a href="gestion-gazette.php" class="btn btn-primary">Retour</a>
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
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-pencil-alt pr-3"></i>Gazette</h5>
		</div>
	</div>

	<div class="row pb-5">
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-lg-3">
						<div class="form-group">
							<label for="main_cat">Catégorie :</label>
							<select class="form-control form-primary" name="main_cat" id="main_cat" required>
								<option value="">Sélectionner</option>
								<option value="1" <?=FormHelpers::restoreSelected(1,$thisGazette['main_cat']) ?>>BTLEC</option>
								<option value="2" <?=FormHelpers::restoreSelected(2,$thisGazette['main_cat']) ?>>GALEC</option>
							</select>
						</div>

					</div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="cat">Type d'information :</label>
							<select class="form-control form-primary" name="cat" id="cat" required>
								<option value="">Sélectionner</option>
								<?php
								?>
								<?php foreach ($listCatByMain as $keyCat => $cat): ?>
									<option value="<?=$keyCat?>" <?=FormHelpers::restoreSelected($keyCat,$thisGazette['cat']) ?>><?=$listCatByMain[$keyCat]?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<div class="col-lg-3"></div>
					<div class="col-lg-3">
						<div class="form-group">
							<label for="date_start">Date de parution</label>
							<input type="date" class="form-control form-primary" name="date_start" id="date_start" value="<?=($thisGazette['date_start'])??date('Y-m-d')?>" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="titre">Titre :</label>
							<input type="text" class="form-control form-primary" name="titre" id="titre" value="<?=($thisGazette['titre'])??""?>" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="description">Description* :</label>
							<textarea class="form-control form-primary" name="description" id="description" row="3"><?=($thisGazette['description'])??""?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col">
					</div>
				</div>
				<div class="row">
					<div class="col">
						<div class="form-group">
							<label for="link">Liens* :</label>
							<div class="font-italic"><i class="fas fa-lightbulb pr-2"></i>Pour ajouter plusieurs liens, veuillez les séparer par une virgule et un espace</div>
							<?php if (isset($listLinks) && !empty($listLinks)): ?>

							<?php
							$links=implode(', ',array_map(function($value){ return $value['link'];}, $listLinks));
							?>
						<?php endif ?>

						<input type="text" class="form-control form-primary" name="link" id="link" value="<?=($links)??""?>" >
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="font-italic"><i class="fas fa-lightbulb pr-2"></i>Pour sélectionner plusieurs fichiers, maintenez la touche <strong>ctrl</strong> appuyée lors de la sélection</div>
				</div>
			</div>
			<div class="row">
				<div class="col-8">
					<div class="row bg-blue-input rounded mx-1 pt-2">
						<div class="col" id="gazette-filenames">
							<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span></p>
						</div>
					</div>
					<div class="row">
						<div class="col" id="file-msg-gazette"></div>
					</div>
				</div>
				<div class="col-4 pt-2">
					<div class="form-group">
						<label class="btn btn-upload-primary btn-file text-center">
							<input type="file" name="gazette_files[]" class='form-control-file' multiple>
							Sélectionner
						</label>
					</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-4" id="zone-noms"></div>
				<div class="col-3" id="zone-ordre"></div>
				<div class="col"></div>
			</div>

			<div class="row">
				<div class="col text-right">
					<button class="btn btn-primary" name="modif-gazette" type="submit">Modifier</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="row">
	<div class="col" id="linkformtitle">
		<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-pencil-alt pr-3"></i>Nommer / supprimer les liens </h5>
	</div>
</div>
<div class="row">
	<div class="col">
		<?php if (!empty($listLinks)): ?>
			<form method="post" class="form-inline" id="linkform" action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>">

				<table class="table w-auto table-sm">
					<thead class="thead-light">
						<tr>
							<th class="px-5 text-center">Liens</th>
							<th class="px-5 text-center">Nom</th>
							<th class="px-5 text-center">Supprimer</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($listLinks as $key => $link): ?>

							<tr>
								<td><a href="<?=$link['link']?>"><?=$link['link']?></a></td>
								<td>
									<div class="form-group">
										<input type="text" class="form-control wider" name="linkname[<?=$link['id']?>]" value="<?=(!empty($link['linkname'])) ? $link['linkname']:''?>">
									</div>
								</td>
								<td>
									<a href="delete-gazette.php?link=<?=$link['id'].'&id_gazette='.$_GET['id']?>" class="btn btn-orange" onclick="return confirm('Etes vous sûr de vouloir supprimer ce lien ?')">Supprimer</a>

								</td>

							</tr>
						<?php endforeach ?>
						<tr>
							<td colspan="2"></td>
							<td class="text-right">
								<button class="btn btn-primary" name="modif_link"><i class="fas fa-save pr-3"></i>Enregistrer</button>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			<?php else: ?>
				<div class="alert alert-primary">Pas de lien à afficher pour cette gazette</div>

			<?php endif ?>
		</div>
	</div>
	<div class="row">
		<div class="col" id="fileformtitle">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-pencil-alt pr-3"></i>Nommer / supprimer les fichiers </h5>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?php if (!empty($listFiles)): ?>
				<form method="post" class="form-inline" id="fileform" action="<?= htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']?>">

					<table class="table w-auto table-sm">
						<thead class="thead-light">
							<tr>
								<th class="px-5 text-center">Fichiers</th>
								<th class="px-5 text-center">Nom</th>
								<TH>Ordre</TH>
								<th class="px-5 text-center">Supprimer</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($listFiles as $key => $file): ?>

								<tr>
									<td><a href="<?=$file['file']?>" target="_blank"><?=$file['file']?></a></td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control wider" name="filename[<?=$file['id']?>]" value="<?=(!empty($file['filename'])) ? $file['filename']:''?>">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control" name="ordre[<?=$file['id']?>]" value="<?=(!empty($file['ordre'])) ? $file['ordre']:''?>">
										</div>
									</td>
									<td>
										<a href="delete-gazette.php?file=<?=$file['id'].'&id_gazette='.$_GET['id']?>" class="btn btn-orange" onclick="return confirm('Etes vous sûr de vouloir supprimer ce fichier ?')">Supprimer</a>

									</td>

								</tr>
							<?php endforeach ?>
							<tr>
								<td colspan="2"></td>
								<td class="text-right">
									<button class="btn btn-primary" name="modif_file"><i class="fas fa-save pr-3"></i>Enregistrer</button>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
				<?php else: ?>
					<div class="alert alert-primary">Pas de fichier joint à afficher pour cette gazette</div>

				<?php endif ?>
			</div>
		</div>
		<div class="row">
			<div class="col text-right py-5">
				<a href="gestion-gazette.php" class="btn btn-primary">Retour</a>
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
				var formGroup="<div class='form-group'>";
				var endDiv="</div>";
				var titre="<div class='text-main-blue heavy'>Nommer  ";
				var titreOrdre="<div class='text-main-blue heavy'>Ordre d'affichage:</div>";
				$("#zone-noms").empty();
				$("#zone-ordre").empty();
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
					var input="<input type='text' class='form-control form-primary'  name='filename[" +i +"]'>";
					var label="<div class='text-main-blue'>Ordre :</div>";
					var ordre=i+1;
					var inputOrdre="<input type='text' class='form-control form-primary'  name='ordre[" +i +"]' value='"+ordre+"'>";

					$("#zone-noms").append(titre+fileName+formGroup+input+endDiv);
					$("#zone-ordre").append(label+formGroup +inputOrdre+endDiv);
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