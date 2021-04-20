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
require '../../Class/OdrDao.php';
require '../../Class/FormHelpers.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');



$odrDao=new OdrDao($pdoDAchat);

$listOdr=$odrDao->getOdrEncours();
$listEan=$odrDao->getOdrEanEncours();
$listFiles=$odrDao->getOdrFilesEncours();


$thisOdr="";

if(isset($_POST['add_odr'])){
	require 'odr-gestion/01-odr-add.php';
}
if(isset($_GET['odr-modif'])){
	$oneOdr=$odrDao->getOdrById($_GET['odr-modif']);
	$oneOdrEan=$odrDao->getOdrEan($_GET['odr-modif']);
	$oneOdrFiles=$odrDao->getOdrFiles($_GET['odr-modif']);
}




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row">
		<div class="col">
			<h1 class="text-main-blue pt-5 ">Gestion des ODR</h1>
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
			<h5 class="text-main-blue border-bottom pb-3 my-3" id="un"><i class="fas fa-table pr-3"></i>ODR en cours</h5>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<?php if (!empty($listOdr)): ?>

				<?php include('odr-gestion/10-odr-encours.php'); ?>
				<?php else: ?>
					<div class="alert alert-danger">Aucune ODR à</div>
				<?php endif ?>

			</div>
		</div>

		<div class="bg-separation"></div>
		<div class="row">
			<div class="col">
				<h5 class="text-main-blue border-bottom pb-3 mt-3 mb-5" id="deux"><i class="fas fa-pencil-alt pr-3"></i>Saisie d'ODR</h5>

			</div>
		</div>
		<div class="row pb-5">
			<div class="col">
				<?php include 'odr-gestion/11-form-add-odr.php' ?>
			</div>
		</div>
		<div id="floating-nav">
			<h6 class="text-main-blue text-center">Aller à</h6>
			<div class="pb-2"><i class="fas fa-table fa-sm circle-icon-blue mr-3"></i><a href="#un">ODR en cours</a></div>
			<div class="pb-2"><i class="fas fa-pencil-alt fa-sm circle-icon-orange mr-3"></i><a href="#deux">Saisie ODR</a></div>
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

			$('input[name="ean_file"]').change(function(){
				var fileList='';
				var warning  ="";
				var fileSize=$(this).get(0).files[0].size;
				var fileName=$(this).get(0).files[0].name;
				var extension=fileName.replace(/^.*\./, '');

				fileList += fileName + warning+'<br>';
				if(fileSize <= 52428800 ){
					$("#file-msg").text("");
					$("#file-msg").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(fileSize)+"</div>");
					$('button[type="submit"]').removeAttr('disabled','disabled');
				}

				if(fileSize > 52428800){
					$("#file-msg").text("");
					$('button[type="submit"]').attr('disabled','disabled');
					$("#file-msg").append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(fileSize)+".<br>La taille est limitée à 50Mo, votre ODR ne pourra pas être enregistrée</div>");
				}

				titre='<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span>'
				end='</p>';
				all=titre+fileList+end;
				$('#filenames').empty();
				$('#filenames').append(all);
				fileList="";
			});
			$('input[name="odr_files[]"]').change(function(){
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
					$("#file-msg-odr").text("");
					$("#file-msg-odr").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(totalSize)+"</div>");
					$('button[type="submit"]').removeAttr('disabled','disabled');

				}
				if(interdit==true){
					$("#file-msg-odr").text("");
					$('button[type="submit"]').attr('disabled','disabled');
					$("#file-msg-odr").append("<div class='text-danger'>Vous avez sélectionnés des types de fichiers interdits (<i class='fas fa-times px-1 text-danger'></i>), merci de modifier votre sélection</div>");
				}
				if(totalSize > 52428800 && interdit==false){
					$("#file-msg-odr").text("");
					$('button[type="submit"]').attr('disabled','disabled');
					$("#file-msg").append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(totalSize)+".<br>La taille est limitée à 50Mo, votre ODR ne pourra pas être enregistrée</div>");

				}

				titre='<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span>'
				end='</p>';
				all=titre+fileList+end;
				$('#filenames-odr').empty();

				$('#filenames-odr').append(all);
				fileList="";
			});



		});
	</script>
	<?php
	require '../view/_footer-bt.php';
	?>