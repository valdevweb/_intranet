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
require('../../Class/OccInfoDao.php');
require('../../Class/UserHelpers.php');


//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);


 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------


$target_dir= DIR_UPLOAD. 'flash\\';

$errors=[];
$success=[];
$infoDao=new OccInfoDao($pdoOcc);
$topublish=$infoDao->getUnpublished();
$activeNews=$infoDao->getActiveNews();



$filelist=$infoDao->getUnpublishedFiles();
$topublishFiles=[];
	// echo "<pre>";
	// print_r($filelist);
	// echo '</pre>';

if(!empty($filelist)){
	foreach ($filelist as $key => $file) {
		$topublishFiles[$file['id_occ_news']]['pj'][]=$file['pj'];
		$topublishFiles[$file['id_occ_news']]['id_file'][]=$file['id_file'];
	}
}



if(isset($_GET['modif'])){
	$modif=$infoDao->getHtmlNews($_GET['modif']);
	$modifHtml=file_get_contents($targetDir.$modif['html_file'].".html");
	$filename=$modif['html_file'];
}


if(isset($_GET['del'])){
	$done=$infoDao->delHtmlNews($_GET['del']);
	header("Location: occ-editinfo.php#topublish",true,303);
}

if(isset($_GET['delfile'])){
	$done=$infoDao->delFile($_GET['delfile']);
	if($done==1){
		header("Location: occ-editinfo.php#topublish",true,303);
	}
}

if(isset($_POST['submit'])){
	foreach ($_POST['date_start'] as $idNews => $value) {
		if(!empty($_POST['date_start'][$idNews]) && empty($_POST['date_end'][$idNews])){
			$errors[]="Merci de sélectionner une date de fin de mise en ligne";
		}
		if(!empty($_POST['date_end'][$idNews]) && empty($_POST['date_start'][$idNews])){
			$errors[]="Merci de sélectionner une date de début de mise en ligne";
		}
	}
	if(empty($errors)){
		foreach ($_POST['date_start'] as $idNews => $value) {
			if(!empty($_POST['date_start'][$idNews]) && !empty($_POST['date_end'][$idNews])){
				$done=$infoDao->updateDateNews($idNews, $_POST['date_start'][$idNews], $_POST['date_end'][$idNews]);
				if($done!=1){
					$errors[]="L'information ".$idNews." n'a pas pu être mise en ligne";
				}
			}

		}
	}
	if(empty($errors)){
		unset($_POST);
		header("Location: occ-editinfo.php#topublish",true,303);

	}

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
<div class="container-fluid bg-light">
	<h1 class="text-main-blue pt-5 pb-3 text-center">Leclerc Occasion</h1>


	<div class="row">
		<div class="col"></div>
		<div class="col-auto">
			<nav class="cl-effect-13">
				<a href="#edit">&#8801 Saisie info &#8801</a>
				<a href="#topublish">&#8801 A publier &#8801</a>
				<a href="#active_news">&#8801 En ligne &#8801</a>
			</nav>
		</div>
		<div class="col"></div>
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
	<div class="row pb-5 pt-4 bg-light-grey ">
		<div class="col">
			<h5 class="text-main-blue text-center" id="edit">Saisie d'info pour les magasins</h5>
		</div>
	</div>

	<div class="row justify-content-center bg-light-grey pb-5 ">
		<div class="col-auto border bg-white rounded-lg px-5 py-5">
			<section >
				<div class="row">
					<div class="col-auto pr-2">
						<button class="btn btn-light" onClick="execCmd('bold')"><i class="fas fa-bold"></i></button>
						<button class="btn btn-light" onClick="execCmd('italic')"><i class="fas fa-italic"></i></button>
						<button class="btn btn-light" onClick="execCmd('underline')"><i class="fas fa-underline"></i></button>
					</div>
					<div class="col-auto ">
						<select  class="form-control" onchange="execCommandWithArg('fontSize',this.value);">
							<option value="3">Taille par défaut</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
						</select>
					</div>
					<div class="col-auto pr-2">

						<select class="form-control" onchange="execCommandWithArg('formatBlock',this.value);">
							<option value="">Titres</option>

							<option value="H1">Titre 1</option>
							<option value="H2">Titre 2</option>
							<option value="H3">Titre 3</option>
							<option value="H4">Titre 4</option>
							<option value="H5">Titre 5</option>
							<option value="H6">Titre 6</option>
						</select>
					</div>
					<div class="col-auto pr-2">
						<button class="btn btn-light" onClick="execCmd('justifyLeft')"><i class="fas fa-align-left"></i></button>
						<button class="btn btn-light" onClick="execCmd('justifyCenter')"><i class="fas fa-align-center"></i></button>
						<button class="btn btn-light" onClick="execCmd('justifyRight')"><i class="fas fa-align-right"></i></button>
						<button class="btn btn-light" onClick="execCmd('justifyFull')"><i class="fas fa-align-justify"></i></button>

					</div>

					<div class="col-auto pr-2">
						<button class="btn btn-light" onClick="execCmd('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
						<button class="btn btn-light" onClick="execCmd('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
					</div>


					<div class="col-auto pr-2">
						<button class="btn btn-light" onClick="execCmd('indent')"><i class="fas fa-indent"></i></button>
						<button class="btn btn-light" onClick="execCmd('oudent')"><i class="fas fa-outdent"></i></button>
					</div>

					<div class="col-auto pr-2">
						<label class="btn btn-light">
							<i class="fas fa-file-image fa-lg"></i><input type="file" name="image" id="img" hidden>
						</label>
					</div>

					<div class="col-auto">

						<button class="btn btn-light" onClick="execCommandWithArg('createLink',prompt('Copiez le lien','lien' ))"><i class="fas fa-link"></i></button>
						<button class="btn btn-light" onClick="execCmd('unlink')"><i class="fas fa-unlink"></i></button>
					</div>
				</div>

				<div class="row mt-3 mb-5">
					<div class="col">
						<div class="color-picker-text color-block">
							<i class="fas fa-font pl-1 pr-3"></i>
							<div class="circle" style="background : #f8f9fa; border: 2px solid #0d47a1" onclick="execCommandWithArg('foreColor','rgba(13,71,161,1)')"></div>
							<div class="circle" style="background : #f8f9fa; border: 2px solid #f18f0b" onclick="execCommandWithArg('foreColor','rgba(241,143,11,1)')"></div>
							<div class="circle" style="background : #f8f9fa; border: 2px solid black" onclick="execCommandWithArg('foreColor','rgba(0,0,0,1)')"></div>
							<div class="circle" style="background : #fff; border: 2px solid #fff;" onclick="execCommandWithArg('foreColor','rgba(255,255,255,1)')"></div>
							<div class="circle" style="background : #f8f9fa; border: 2px solid red" onclick="execCommandWithArg('foreColor','rgba(255,0,0,1)')"></div>
							<div class="circle" style="background : #f8f9fa; border: 2px solid green" onclick="execCommandWithArg('foreColor','rgba(0,128,0,1)')"></div>
							<div class="circle" style="background : #f8f9fa; border: 2px solid grey"  onclick="execCommandWithArg('foreColor','rgba(128,128,128,1)')"></div>
						</div>

						<div class="color-block color-picker-bg">
							<i class="fas fa-fill pl-1 pr-3"></i>
							<div class="circle" style="background: #0d47a1" onclick="execCommandWithArg('hiliteColor','rgba(13,71,161,1)')"></div>
							<div class="circle" style="background: #f18f0b" onclick="execCommandWithArg('hiliteColor','rgba(241,143,11,1)')"></div>
							<div class="circle" style="background: black" onclick="execCommandWithArg('hiliteColor','rgba(0,0,0,1)')"></div>
							<div class="circle" style="background: white; border:1px solid #999" onclick="execCommandWithArg('hiliteColor','rgba(255,255,255,1)')"></div>
							<div class="circle" style="background: red" onclick="execCommandWithArg('hiliteColor','rgba(255,0,0,1)')"></div>
							<div class="circle" style="background: green" onclick="execCommandWithArg('hiliteColor','rgba(0,128,0,1)')"></div>
							<div class="circle" style="background: grey"  onclick="execCommandWithArg('hiliteColor','rgba(128,128,128,1)')"></div>
						</div>
					</div>
					<div class="col">
						<label class="btn btn-upload btn-file text-center">
							<input name="upload" id="file" type="file" multiple="" class="form-control-file">
							<i class="fas fa-file-image pr-3"></i>Ajouter des fichiers
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col">
						<iframe name="richText" style="width: 1200px; height:300px; font-family:Arial;" id="richText"></iframe>
					</div>
				</div>
				<div class="row justify-content-end">
					<div class="col" id="listfiles">

					</div>
					<div class="col-auto">
						<div id="preview"></div>
					</div>
					<div class="col-auto">
						<button class="btn btn-primary" name="save" id="save">Enregistrer</button>
					</div>
					<div class="col-auto">
						<button class="btn btn-secondary" name="end" id="end">Terminer</button>
					</div>
				</div>

			</div>
		</section>
	</div>

	<div class="hidden" id="modif"><?=isset($modifHtml)?$modifHtml:""?></div>
	<div class="hidden" id="phpdate"><?=date('YmdHis')?></div>

	<div class="hidden" id="filename"><?=isset($filename)?$filename:""?></div>

	<div class="row my-5">
		<div class="col-xl-1"></div>
		<div class="col">
			<h5 class="text-main-blue text-center" id="topublish">Infos saisies à publier</h5>
		</div>
		<div class="col-xl-1"></div>
	</div>
	<?php if (!empty($topublish)): ?>
		<div class="row">
			<div class="col-xl-1"></div>
			<div class="col">
				<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

					<table class="table">
						<thead class="thead-dark">
							<tr>
								<th>#</th>
								<th>Date de saisie</th>
								<th>Prévisualisation</th>
								<th>Date de début</th>
								<th>Date de fin</th>
								<th>Fichiers joints</th>

								<th>Modifier</th>
								<th>Supprimer</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($topublish as $key => $new): ?>

								<tr>
									<td><?=$new['id']?></td>
									<td><?=date('d-m-Y', strtotime($new['date_insert']))?></td>
									<td><a href="preview.php?file=<?=$new['html_file']?>" target="_blank">voir</a></td>
									<td>
										<div class="form-group">
											<input type="date" class="form-control" name="date_start[<?=$new['id']?>]" value="<?=isset($new['date_start'])? $new['date_start']: ''?>">
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type="date" class="form-control" name="date_end[<?=$new['id']?>]" value="<?=isset($new['date_end'])? $new['date_end']: ''?>">
										</div>
									</td>
									<td>
										<?php if (isset($topublishFiles[$new['id']])): ?>
											<?php for ($i=0; $i<count($topublishFiles[$new['id']]['pj']);$i++): ?>
												<?=$topublishFiles[$new['id']]['pj'][$i]?><a href="?delfile=<?=$topublishFiles[$new['id']]['id_file'][$i]?>"><i class="fas fa-trash-alt pl-3"></i></a><br>
											<?php endfor ?>

										<?php endif ?>
									</td>
									<td><a href="<?=$_SERVER['PHP_SELF']?>?modif=<?=$new['html_file']?>"><i class="fas fa-edit"></i></a></td>
									<td><a href="<?=$_SERVER['PHP_SELF']?>?del=<?=$new['html_file']?>"><i class="fas fa-trash-alt"></i></a></td>

								</tr>
							<?php endforeach ?>

						</tbody>
					</table>
					<div class="text-right pb-5">
						<button class="btn btn-primary" name="submit">Valider</button>
					</div>
				</form>
			</div>
			<div class="col-xl-1"></div>

		</div>
	<?php endif ?>

</div>


<div class="row my-5">
	<div class="col-xl-1"></div>
	<div class="col">
		<h5 class="text-main-blue text-center" id="active_news">Infos en ligne</h5>
	</div>
	<div class="col-xl-1"></div>
</div>
<?php if (!empty($activeNews)): ?>
	<div class="row">
		<div class="col-xl-1"></div>
		<div class="col">
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">

				<table class="table bg-white">
					<thead class="thead-dark">
						<tr>
							<th>#</th>
							<th>Date de saisie</th>
							<th>Prévisualisation</th>
							<th>Date de début</th>
							<th>Date de fin</th>
							<th>Fichiers joints</th>
							<th>Modifier</th>
							<th>Supprimer</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($activeNews as $key => $new): ?>

							<tr>
								<td><?=$new['id']?></td>
								<td><?=date('d-m-Y', strtotime($new['date_insert']))?></td>
								<td><a href="preview.php?file=<?=$new['html_file']?>" target="_blank">voir</a></td>
								<td>
									<div class="form-group">
										<input type="date" class="form-control" name="date_start[<?=$new['id']?>]" value="<?=isset($new['date_start'])? $new['date_start']: ''?>">
									</div>
								</td>
								<td>
									<div class="form-group">
										<input type="date" class="form-control" name="date_end[<?=$new['id']?>]" value="<?=isset($new['date_end'])? $new['date_end']: ''?>">
									</div>
								</td>
								<td>
									<?php if (isset($topublishFiles[$new['id']])): ?>
										<?php for ($i=0; $i<count($topublishFiles[$new['id']]['pj']);$i++): ?>
											<?=$topublishFiles[$new['id']]['pj'][$i]?><a href="?delfile=<?=$topublishFiles[$new['id']]['id_file'][$i]?>"><i class="fas fa-trash-alt pl-3"></i></a><br>
										<?php endfor ?>

									<?php endif ?>
								</td>
								<td><a href="<?=$_SERVER['PHP_SELF']?>?modif=<?=$new['html_file']?>"><i class="fas fa-edit"></i></a></td>
								<td><a href="<?=$_SERVER['PHP_SELF']?>?del=<?=$new['html_file']?>"><i class="fas fa-trash-alt"></i></a></td>

							</tr>
						<?php endforeach ?>

					</tbody>
				</table>
				<div class="text-right pb-5">
					<button class="btn btn-primary" name="submit">Valider</button>
				</div>
			</form>
		</div>
		<div class="col-xl-1"></div>

	</div>
<?php endif ?>

</div>


<script type="text/javascript">

	var modif=$('#modif').html();
	if(modif!=""){
		var myFrame = $("#richText").contents().find('body');
		myFrame.html(modif);
	}
	$('#end').on('click',function(){
		window.location.replace(location.pathname);
		richText.document.getElementsByTagName('body')[0].innerHTML="";
		$('#filename').empty();

	});


	var showingSourceCode=false;
	var isInEditMode=true;
	var iframeCopy=document.getElementById('iframe-copy');
	window.onload = function() {
		richText.document.designMode='on';
		document.richText.document.body.style.fontFamily = "Arial";

	};
	function execCmd(command){
		richText.document.execCommand(command, false, null);
	}
	function execCommandWithArg(command, arg){
		richText.document.execCommand(command, false, arg);
	}
	function toggleSource(){
		if(showingSourceCode){
			richText.document.getElementsByTagName('body')[0].innerHTML=richText.document.getElementsByTagName('body')[0].textContent;
			showingSourceCode=false;
		}else{
			richText.document.getElementsByTagName('body')[0].textContent=richText.document.getElementsByTagName('body')[0].innerHTML;

			showingSourceCode=true;
		}
	}
	function toggleEdit(){
		if(isInEditMode){
			richText.document.designMode='off';
			isInEditMode=false;
		}else{
			richText.document.designMode='on';

			isInEditMode=true;

		}
	}


	$(document).ready(function(){

		$(document).on("change", "#img", function(){
			var filename = document.getElementById("img").files[0].name;
			var file=document.getElementById("img").files[0];
			var fd = new FormData();
			var ext = filename.split('.').pop().toLowerCase();

			var oFReader = new FileReader();
			oFReader.readAsDataURL(file);
			if(jQuery.inArray(ext, ['gif','png','jpg','jpeg']) == -1){
				alert("Type de fichier non supporté");
			}
			var fsize = file.size||file.fileSize;
			if(fsize > 5000000){
				alert("Fichier trop lourd");
			}else{

				fd.append("file", file);
				fd.append("submit", true);

				$.ajax({
					url:"ajax-upload-img.php",
					type:"POST",
					data: fd,
					contentType: false,
					processData: false,
					dataType:"JSON",
					success:function(data){
						console.log(data);
						if(data.success){
							$('#richText').contents().find('body').append($("<img/>").attr("src", data.path).attr("title", "sometitle"));
						}
					}
				});
			}
		});
		$(document).on("change", "#file", function(){
			var savedFilename=$('#filename').text();
			if(savedFilename==""){
				alert("merci d'enregistrer votre saisie avant de joindre des documents")
			}else{
				var filename = document.getElementById("file").files[0].name;
				var file=document.getElementById("file").files[0];
				var fd = new FormData();
				var listfiles=$('#listfiles');
				var prevlistfiles=listfiles.text();

				var oFReader = new FileReader();
				oFReader.readAsDataURL(file);

				var fsize = file.size||file.fileSize;
				if(fsize > 5000000){
					alert("Fichier trop lourd");
				}else{

					fd.append("file", file);
					fd.append("submit", true);
					fd.append("htmlfile", savedFilename);

					$.ajax({
						url:"ajax-upload-file.php",
						type:"POST",
						data: fd,
						contentType: false,
						processData: false,
						dataType:"JSON",
						success:function(data){

							if(data.success){
								$('#listfiles').append(data.path);
								$('#listfiles').append("<br>");

							}
						},
						error: function(data){

							$('#listfiles').append("<div class='alert alert-danger'>Une erreur est survenue :  "+data.message+"</div>");

						}
					});
				}
			}

		});



		$('#save').on('click',function(){

			var iframeContent = richText.document.getElementsByTagName('body')[0].innerHTML;
			iframeContent=iframeContent.replace(/&nbsp;/gi,'');

			var filename="";
			var savedFilename=$('#filename').text();
			if(savedFilename==""){
				console.log("vide");
				filename=$('#phpdate').text();
				$('#filename').text(filename);
			}else{
				filename=savedFilename;
			}
			console.log(filename);
			if(iframeContent){
				$.ajax({
					type:'POST',
					dataType : 'html',
					url:'ajax-saveas-html.php',
					data:'iframe='+iframeContent+'&filename='+filename,
					success:function(html){
						$('#preview').empty();
						$('#preview').append('<a class="btn btn-orange" href="preview.php?file='+html+'" target="_blank" id="previewlink">previsualiser</a>');
					}
				});
			}

		});

		// on fait disparaitre le btn preview dès que l'utilisateur retourne dans le iframe pour le forcer à enregistrer avant la previsualisation
		var frameBody = $("#richText").contents().find("body");
		frameBody.focus(function(e){
			$('#preview').empty();
		});

	});


</script>

<?php
require '../view/_footer-bt.php';
?>