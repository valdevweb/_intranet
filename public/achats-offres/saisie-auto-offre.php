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
require '../../Class/CataDao.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');
$pdoQlik=$db->getPdo('qlik');


$prospDao=new ProspectusDao($pdoDAchat);
$offreDao=new OffreDao($pdoDAchat);
$cataDao=new CataDao($pdoQlik);

$listProsp=$prospDao->getComingProspectus((new DateTime())->format('Y-m-d'));
$listOffre=$offreDao->getOffreEncours();
$inProspectus=1;

if (isset($_POST['search_by_week'])) {
	$listArticle=$cataDao->getArticleByCodeOp($_POST['op']);
}

if (isset($_POST['search_by_cata'])) {
	$listArticle=$cataDao->getArticleByCodeOp(strtoupper($_POST['code_op']));
}



if(isset($_POST['add_offre_gessica'])){
	require 'saisie-auto-offre/01-post-add-offre.php';
}

if(isset($_GET['success'])){
	$arrSuccess=[
		'offres-add'=>'Offres ajoutées avec succès. Pour voir les offres, <a href="offre-gestion.php">cliquez ici</a>',

	];
	$success[]=$arrSuccess[$_GET['success']];
}

//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<h1 class="text-main-blue py-5 ">Saisie automatique des offres</h1>
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
		<div class="col pb-3">
			<h6 class="text-main-blue"><span class="step">1</span>Sélection du prospectus</h6>
		</div>
	</div>
	<?php include '../achats-commun/10-form-search-cata.php' ?>
	<div class="row">
		<div class="col">
			<h6 class="text-main-blue">
				<span class="step">2</span>Saisie des offres sur le prospectus
				<?=($_POST['op'])??""?>
				<?=($_POST['code_op'])??""?>
			</h6>
		</div>
	</div>
	<?php if (!isset($listArticle)): ?>

		<div class="row pb-5">
			<div class="col">
				Pas de prospectus sélectionné. Veuillez utiliser le formulaire de recherche pour afficher les articles et saisir les offres
			</div>
		</div>
		<?php else: ?>
			<?php if (!empty($listArticle)): ?>
				<?php include 'saisie-auto-offre/12-form-add-offres.php' ?>
				<?php else: ?>
					<div class="alert alert-danger">Aucun article à afficher pour ce prospectus</div>
				<?php endif ?>
			<?php endif ?>
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

				$('#week').on('change', function() {
					var week=$('#week').val();
					$.ajax({
						type:'POST',
						url:'../achats-commun/ajax-get-cata-week.php',
						data:{week:week},
						success: function(html){
							$("#op").empty();
							$("#op").append(html);
						}
					});
				});

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