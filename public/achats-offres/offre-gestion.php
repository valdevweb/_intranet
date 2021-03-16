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
require '../../Class/ProspectusDao.php';
require '../../Class/OffreDao.php';
require '../../Class/FormHelpers.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');


$prospDao=new ProspectusDao($pdoDAchat);
$offreDao=new OffreDao($pdoDAchat);

$listProsp=$prospDao->getComingProspectus((new DateTime())->format('Y-m-d'));
$listOffre=$offreDao->getOffreEncours();
if(isset($_POST['add_prosp'])){
	require 'offre-gestion/01-add-prospectus.php';
}


if(isset($_POST['modify_prosp'])){
	require 'offre-gestion/02-modify-prospectus.php';

}
if(isset($_POST['add_offre'])){
	require 'offre-gestion/03-add-offre.php';
}
if(isset($_POST['update_offre'])){
	require 'offre-gestion/04-update-offre.php';
}
if(isset($_GET['prosp-id-mod'])){
	$prospMod=$prospDao->getProspectusById($_GET['prosp-id-mod']);
}
if(isset($_GET['prosp-id-add'])){
	$prospMod=$prospDao->getProspectusById($_GET['prosp-id-add']);
}

if(isset($_GET['offre-modif'])){
	$offreMod=$offreDao->getOffre($_GET['offre-modif']);
}
if(isset($_GET['success'])){
	$arrSuccess=[
		'prosp-add'=>'Prospectus ajouté avec succès',
		'prosp-mod'=>'Prospectus mis à jour',
		'add-offre'=>'Offre ajoutée',
		'update-offre'=>'Offre modifiée',
	];
	$success[]=$arrSuccess[$_GET['success']];
}

//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<h1 class="text-main-blue py-5 ">Gestion des offres - tickets et BRII</h1>
	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row py-3">
		<div class="col">
			<h5 class="text-main-blue" id="list-prosp">Listes des prospectus</h5>
		</div>
	</div>
	<?php if (!empty($listProsp)): ?>
		<?php include 'offre-gestion/13-list-prosp.php'; ?>
		<?php else: ?>
			<div class="row">
				<div class="col">
					<div class="alert alert-primary">Aucun prospectus à afficher</div>
				</div>
			</div>
		<?php endif ?>

		<?php if (isset($_GET['prosp-id-mod'])): ?>
			<?php include 'offre-gestion/11-modify-prospectus.php'; ?>
		<?php endif ?>
		<div class="bg-separation"></div>

		<div class="row py-3" id="list-offre">
			<div class="col">
				<h5 class="text-main-blue">Offres en cours</h5>
			</div>
		</div>
		<?php if (!empty($listOffre)): ?>
			<?php include 'offre-gestion/14-list-offre.php' ?>
			<?php else: ?>
				<div class="row">
					<div class="col">
						<div class="alert alert-primary">Aucune offre à afficher</div>
					</div>
				</div>
			<?php endif ?>
			<?php if (isset($_GET['offre-modif'])): ?>
				<?php include 'offre-gestion/15-modify-offre.php' ?>
			<?php endif ?>


			<div class="row my-3" id="add-offre-title">
				<div class="col">
					<h5 class="text-main-blue">Ajouter des offres à un prospectus</h5>
				</div>
			</div>
			<?php include 'offre-gestion/12-add-offres.php'; ?>
			<div class="bg-separation"></div>
			<div class="bg-separation"></div>
			<div class="row my-3" id="add-prosp-title">
				<div class="col">
					<h5 class="text-main-blue">Créer un prospectus</h5>
				</div>
			</div>
			<?php include 'offre-gestion/10-add-prospectus.php' ?>

			<div class="bg-separation"></div>
			<!-- Start of floating navigation -->


			<div id="floating-nav">
				<h6 class="text-main-blue text-center">Aller à</h6>
				<div class="pb-2"><i class="fas fa-newspaper fa-sm circle-icon-blue mr-3"></i><a href="#list-prosp">Listes des prospectus</a></div>
				<div class="pb-2"><i class="fas fa-euro-sign fa-sm circle-icon-blue mr-3 p-euro"></i><a href="#list-offre">Listes des offres en cours ou à venir</a></div>
				<div class="pb-2"><i class="fas fa-newspaper fa-sm circle-icon-orange mr-3"></i><a href="#add-prosp-title">Créer un prospectus</a></div>
				<div class="pb-2"><i class="fas fa-plus fa-sm circle-icon-orange mr-3 p-add"></i><a href="#add-offre-title">Ajouter une offre</a></div>
			</div>



		</div>
  <script src="../js/excel-filter.js"></script>

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
				$('#offre-table').excelTableFilter();

				$("#circle_trigger").click(function() {
					$("#floating_nav_choices").fadeIn(600);
					$("#floating_nav_choices").toggleClass("visible");
					if ($("#floating_nav_choices").hasClass("visible")) {
						$("#circle_trigger").css({ transform: "rotate(45deg)" });
					} else {
						$("#circle_trigger").css({ transform: "rotate(0deg)" });
						$("#floating_nav_choices").fadeOut(300);
					}
				});
				$('input[name="fic"]').change(function(){
					var fileList='';
					var fileExtension = ['xml'];
					var warning  ="";
					var interdit=false;
					var fileSize=$(this).get(0).files[0].size;
					var fileName=$(this).get(0).files[0].name;
					var extension=fileName.replace(/^.*\./, '');
					if ($.inArray(extension, fileExtension)==-1) {
						warning="<i class='fas fa-times px-3 text-danger'></i>";
						interdit=true;
					}else{
						warning="<i class='fas fa-check px-3 text-success'></i>";

					}
					fileList += fileName + warning+'<br>';


					if(fileSize <= 52428800 && interdit==false){
						$("#file-msg").text("");
						$("#file-msg").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(fileSize)+"</div>");
						$('button[type="submit"]').removeAttr('disabled','disabled');

					}
					if(interdit==true){
						$("#file-msg").text("");
						$('button[type="submit"]').attr('disabled','disabled');
						$("#file-msg").append("<div class='text-danger'>Vous devez télécharger un fichier xml(<i class='fas fa-times px-1 text-danger'></i>)</div>");
					}
					if(fileSize > 52428800 && interdit==false){
						$("#file-msg").text("");
						$('button[type="submit"]').attr('disabled','disabled');
						$("#file-msg").append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(fileSize)+".<br>La taille est limitée à 50Mo, votre message ne pourra pas être envoyé</div>");

					}

					titre='<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span>'
					end='</p>';
					all=titre+fileList+end;
					$('#filenames').empty();

					$('#filenames').append(all);
					fileList="";
				});

				$('input[name="fic-mod"]').change(function(){
					var fileList='';
					var fileExtension = ['xml'];
					var warning  ="";
					var interdit=false;
					var fileSize=$(this).get(0).files[0].size;
					var fileName=$(this).get(0).files[0].name;
					var extension=fileName.replace(/^.*\./, '');
					if ($.inArray(extension, fileExtension)==-1) {
						warning="<i class='fas fa-times px-3 text-danger'></i>";
						interdit=true;
					}else{
						warning="<i class='fas fa-check px-3 text-success'></i>";

					}
					fileList += fileName + warning+'<br>';


					if(fileSize <= 52428800 && interdit==false){
						$("#file-msg-mod").text("");
						$("#file-msg-mod").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(fileSize)+"</div>");
						$('button[type="submit"]').removeAttr('disabled','disabled');

					}
					if(interdit==true){
						$("#file-msg-mod").text("");
						$('button[type="submit"]').attr('disabled','disabled');
						$("#file-msg-mod").append("<div class='text-danger'>Vous devez télécharger un fichier xml(<i class='fas fa-times px-1 text-danger'></i>)</div>");
					}
					if(fileSize > 52428800 && interdit==false){
						$("#file-msg-mod").text("");
						$('button[type="submit"]').attr('disabled','disabled');
						$("#file-msg-mod").append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(fileSize)+".<br>La taille est limitée à 50Mo, votre message ne pourra pas être envoyé</div>");

					}

					titre='<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span>'
					end='</p>';
					all=titre+fileList+end;
					$('#filenames-mod').empty();

					$('#filenames-mod').append(all);
					fileList="";
				});


			});
		</script>


		<?php
		require '../view/_footer-bt.php';
		?>