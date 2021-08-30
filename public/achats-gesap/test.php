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
	// echo "<pre>";
	// print_r($_POST);
	// echo '</pre>';
	// exit;

	// if(empty($_POST['op']) || empty($_POST['salon']) || empty($_POST['cata']) || empty($_POST['code_op']) || empty($_POST['date_remonte'])){
	// 	$errors[]="Vous devez renseigner les champs nom de l'opération, catalogue, salon, code opération et date limite de remontée";
	// }
	// if(isset($_POST['file_ga']) && empty($_POST['ga_name'])){
	// 	$errors[]="Merci de saisir le numéro du guide d'achat";
	// }
	// if (!empty($_POST['ga_name']) && !isset($_POST['file_ga'])) {
	// 	$errors[]="Merci de joindre le guide d'achat";
	// }

	// if(empty($errors)){


	// 	if (!empty($_POST['ga_name']) && isset($_POST['file_ga'])) {
	// 		$idGesap=$gesapDao->insertGesapWithGa($_POST['file_ga'][1]);

	// 	}else{
	// 		$idGesap=$gesapDao->insertGesapWithoutGa();

	// 	}
	// 	if(isset($_POST['file_otherfile'])){
	// 		echo "<pre>";
	// 		print_r($_POST);
	// 		echo '</pre>';


	// 		for ($i=1; $i <=count($_POST['file_otherfile']) ; $i++) {
	// 			echo $_POST['file_otherfile'][$i];
	// 			echo "<br>";

	// 			$gesapDao->insertFileWithOrdre($idGesap, $_POST['file_otherfile'][$i], $_POST['readable_otherfile'][$i], $_POST['ordre_otherfile'][$i]);
	// 		}


	// 	}

	// }

	// if(empty($errors)){
	// 	$successQ='?success';
	// 	unset($_POST);
	// 	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
	// }

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
			<form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post" enctype="multipart/form-data">

				<div class="row">
					<div class="col">
						Autres fichiers joints
					</div>
				</div>
				<div class="row ml-1">
					<div class="col">
						<div class="row">
							<div class="col" id="test">
								<input type="file" name="file[]" class="dragndropfile " multiple>
								<div class="upload-area uploadfile row">
									<p>Glisser/déposer les fichiers<br/>ou<br/>cliquez ici pour les sélectionner</p>
								</div>
								<div class="filename"></div>
								<div class="readablename"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-3">
					<div class="col text-right">
						<button class="btn btn-primary" name="add">Ajouter</button>
					</div>
				</div>
			</form>
		</div>
	</div>


</div>
<script src="../js/dragndrop.js"></script>


<script type="text/javascript">
	$.getJSON("http://cpvapi.com/pens/pens/popular", function(resp) {
		console.log(resp);
	});
	$(document).ready(function(){

		$("html").on("dragover", function(e) {
			e.preventDefault();
			e.stopPropagation();
		});
		$("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

		var idName="#test";
		var  readable=false;
		var order=true;

		$(idName +' .upload-area').on('dragenter', function (e) {
			e.stopPropagation();
			e.preventDefault();
			$(idName + " .upload-area p").text("Déposez");
		});

		$(idName +' .upload-area').on('dragover', function (e) {
			e.stopPropagation();
			e.preventDefault();
			$(idName +" .upload-area p").text("Déposez");
		});

		$(idName +' .upload-area').on('drop', function (e) {
			e.stopPropagation();
			e.preventDefault();

			$(idName +" .upload-area p").text("Téléchargement..");
			var files = e.originalEvent.dataTransfer.files;
			var fd = new FormData();
			for (var i = 0; i < files.length; i++) {
				fd.append('file[]', files[i]);
				console.log( files[i])
			}
			uploadMultiple(fd, 'drag-and-drop-multi.php', idName, readable, order );

		});

		$(idName +" .uploadfile").click(function(){
			$(idName +" .dragndropfile").click();
		});

		$(idName +" .dragndropfile").change(function(){
			var fd = new FormData();
			// var files = $(idName +' .dragndropfile')[0].files[0];
			var nbFiles=($(idName +' .dragndropfile')[0].files).length;
			for (var i = 0; i < nbFiles; i++) {
			var file = $(idName +' .dragndropfile')[0].files[i];

			console.log($(idName +' .dragndropfile')[0].files[i])
			fd.append('file',file);
				fd.append('file[]', file);

		}
			uploadMultiple(fd, 'drag-and-drop-multi.php', idName,  readable, order);
		});





	});


</script>
<?php
require '../view/_footer-bt.php';
?>