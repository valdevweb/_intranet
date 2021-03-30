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
require '../../Class/GesapDao.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');


$gesapDao=new GesapDao($pdoDAchat);

$listGesap=$gesapDao->getListGesap();
$listFiles=$gesapDao->getListFiles();


if(isset($_POST['add'])){
	if(empty($_POST['op']) || empty($_POST['salon']) || empty($_POST['cata']) || empty($_POST['code_op']) || empty($_POST['date_remonte'])){
		$errors[]="Vous devez renseigner les champs nom de l'opération, catalogue, salon, code opération et date limite de remontée";
	}
	if(isset($_FILES['file_ga']['tmp_name']) && !empty($_FILES['file_ga']['tmp_name']) && empty($_POST['ga_name'])){
		$errors[]="Merci de saisir le numéro du guide d'achat";
	}

	if(empty($errors)){
		if(isset($_FILES['file_ga']['tmp_name']) && !empty($_FILES['file_ga']['tmp_name'])){
			$orginalFilename=$_FILES['file_ga']['name'];
			$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

			// $filenameNoExt = basename($orginalFilename, '.'.$ext);
			$gaFilename = 'guide_achat_' . time() . '.' . $ext;
			$uploaded=move_uploaded_file($_FILES['file_ga']['tmp_name'],DIR_UPLOAD.'gesap\\'.$gaFilename );
			if($uploaded==false){
				$errors[]="Nous avons rencontré un problème avec votre fichier, impossible de l'uploader vers le serveur";
			}
		}
		if(isset($_FILES['file_other']['tmp_name'][0]) && !empty($_FILES['file_other']['tmp_name'][0])){

			for ($i=0; $i <count($_FILES['file_other']['tmp_name']) ; $i++) {
				$orginalFilename=$_FILES['file_other']['name'][$i];
				$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

				$filenameNoExt = basename($orginalFilename, '.'.$ext);
				$filename = str_replace(' ', '_', $filenameNoExt) . '_' . time() . '.' . $ext;

				$uploaded=move_uploaded_file($_FILES['file_other']['tmp_name'][$i],DIR_UPLOAD.'gesap\\'.$filename );
				if($uploaded==false){
					$errors[]="Nous avons rencontré avec votre fichier, impossible de l'uploader vers le serveur";
				}else{
					$otherFilename[]=$filename;
				}
			}
		}
	}

	if(empty($errors)){
		if(isset($gaFilename)){
			$idGesap=$gesapDao->insertGesapWithGa($gaFilename);
		}else{
			$idGesap=$gesapDao->insertGesapWithoutGa();
		}
		if(isset($otherFilename) && !empty($otherFilename)){
			for ($i=0; $i < count($otherFilename); $i++) {

				$gesapDao->insertFileWithOrdre($idGesap, $otherFilename[$i], $_POST['filename'][$i], $_POST['ordre'][$i]);
			}

		}
	}
	if(empty($errors)){
		$successQ='?success';
		unset($_POST);
		header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	}

}


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Gestion des infos GESAP</h1>
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

	<div class="row" id="un">
		<div class="col">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-edit pr-3"></i>Ajout de GESAP</h5>
		</div>
	</div>
	<div class="row pb-5">
		<div class="col">
			<?php include 'gesap-gestion/01-form-add.php' ?>
		</div>
	</div>
	<div class="bg-separation"></div>
	<div class="row" id="deux">
		<div class="col">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-book-reader pr-3"></i>Gesap à venir</h5>
		</div>
	</div>
	<div class="row pb-5">
		<div class="col">
			<?php if (!empty($listGesap)): ?>
				<?php include 'gesap-gestion/02-table-gesap.php' ?>

			<?php endif ?>
		</div>
	</div>
	<div class="bg-separation"></div>

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

		$('input[name="file_ga"]').change(function(){
			var fileList='';
			var warning  ="";
			var fileSize=$(this).get(0).files[0].size;
			var fileName=$(this).get(0).files[0].name;
			var extension=fileName.replace(/^.*\./, '');

			fileList += fileName + warning+'<br>';
			if(fileSize <= 52428800 ){
				$("#file-ga-msg").text("");
				$("#file-ga-msg").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(fileSize)+"</div>");
				$('button[type="submit"]').removeAttr('disabled','disabled');
			}

			if(fileSize > 52428800){
				$("#file-ga-msg").text("");
				$('button[type="submit"]').attr('disabled','disabled');
				$("#file-ga-msg").append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(fileSize)+".<br>La taille est limitée à 50Mo, votre ODR ne pourra pas être enregistrée</div>");
			}

			titre='<p><span class="text-main-blue font-weight-bold">Fichier sélectionné: <br></span>'
			end='</p>';
			all=titre+fileList+end;
			$('#filename-ga').empty();
			$('#filename-ga').append(all);
			fileList="";
		});
		$('input[name="file_other[]"]').change(function(){

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
				$("#file-other-msg").text("");
				$("#file-other-msg").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(totalSize)+"</div>");
				$('button[type="submit"]').removeAttr('disabled','disabled');

			}
			if(interdit==true){
				$("#file-other-msg").text("");
				$('button[type="submit"]').attr('disabled','disabled');
				$("#file-other-msg").append("<div class='text-danger'>Vous avez sélectionnés des types de fichiers interdits (<i class='fas fa-times px-1 text-danger'></i>), merci de modifier votre sélection</div>");
			}
			if(totalSize > 52428800 && interdit==false){
				$("#file-other-msg").text("");
				$('button[type="submit"]').attr('disabled','disabled');
				$("#file-other-msg").append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(totalSize)+".<br>La taille est limitée à 50Mo, votre ODR ne pourra pas être enregistrée</div>");

			}

			titre='<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span>'
			end='</p>';
			all=titre+fileList+end;
			$('#filename-other').empty();

			$('#filename-other').append(all);
			fileList="";
		});
	});


</script>
<?php
require '../view/_footer-bt.php';
?>