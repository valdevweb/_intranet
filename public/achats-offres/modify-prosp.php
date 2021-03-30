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

if(isset($_GET['id'])){
	$prosp=$prospDao->getProspectusById($_GET['id']);
	$listFiles=$prospDao->getOneProspectusFiles($_GET['id']);
	$listLinks=$prospDao->getOneProspectusLinks($_GET['id']);
}else{
	echo "pas de prospectus sélectionné";
	exit();
}

$listIdProsp="";

if(isset($_POST['modify_prosp'])){
	require 'modify-prosp/01-modify-prospectus.php';

}


if(isset($_POST['modif_link'])){
	foreach ($_POST['linkname'] as $idLink => $value) {
		$offreDao->updateLinks($idLink,$_POST['linkname'][$idLink]);
	}
	$successQ='?id='.$_GET['id'].'#modif-link';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
}

if(isset($_POST['modif_file'])){
	foreach ($_POST['filename'] as $idFile => $value) {
		$offreDao->updateFiles($idFile,$_POST['filename'][$idFile],$_POST['ordre'][$idFile] );
	}
	$successQ='?id='.$_GET['id'].'#modif-file';
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);
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
	<div class="row  pt-5 pb-2">
		<div class="col">
			<h1 class="text-main-blue  ">Gestion des offres - tickets et BRII</h1>

		</div>
		<div class="col-auto">
			<a href="offre-gestion.php" class="btn btn-primary">Retour</a>
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
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-edit pr-3 text-orange"></i>Modification du prospectus <?= ($prosp['prospectus'])??""?></h5>
		</div>
	</div>
	<?php include 'modify-prosp/10-modify-prospectus.php'; ?>

	<div class="bg-separation"></div>
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
		var url = window.location + '';
		var splited=url.split("#");
		if(splited[1]==undefined){
			var line='';
		}
		else if(splited.length==2){
			var line=splited[1];
			line=line.replace("offre-", "");
			$("tr#offre-"+line).addClass("anim");
		}


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
			$('#file-name-mod').empty();

			$('#file-name-mod').append(all);
			fileList="";
		});


		$('input[name="file_other[]"]').change(function(){
			var totalSize=0;
			var fileName='';
			var fileList='';
			var nbFiles = $(this).get(0).files.length;
			var formGroup="<div class='form-group'>";
			var endDiv="</div>";
			var titre="<div class='text-main-blue heavy'>Nommer  ";
			var titreOrdre="<div class='text-main-blue heavy'>Ordre d'affichage:</div>";


			for (var i = 0; i < nbFiles; ++i) {
				var fileSize=$(this).get(0).files[i].size;
				fileName=$(this).get(0).files[i].name;
				totalSize = totalSize+fileSize;
				var extension=fileName.replace(/^.*\./, '');

				fileList += fileName +'<br>';
				var input="<input type='text' class='form-control form-primary'  name='filename[" +i +"]'>";
				var label="<div class='text-main-blue'>Ordre :</div>";
				var ordre=i+1;
				var inputOrdre="<input type='text' class='form-control form-primary'  name='ordre[" +i +"]' value='"+ordre+"'>";

				$("#zone-noms").append(titre+fileName+formGroup+input+endDiv);
				$("#zone-ordre").append(label+formGroup +inputOrdre+endDiv);
			}

			if(totalSize <= 52428800){
				$("#file-other-msg").text("");
				$("#file-other-msg").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(totalSize)+"</div>");
				$('button[type="submit"]').removeAttr('disabled','disabled');

			}

			if(totalSize > 52428800){
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