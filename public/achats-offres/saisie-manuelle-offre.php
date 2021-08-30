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

$listProsp=$prospDao->getComingProspectus();
if (isset($_POST['search_by_week'])) {
	$listArticle=$cataDao->getArticleByCodeOp($_POST['op']);
}

if (isset($_POST['search_by_cata'])) {
	$listArticle=$cataDao->getArticleByCodeOp(strtoupper($_POST['code_op']));
}


if(isset($_POST['add_prosp'])){
	require 'saisie-manuelle-offre/01-add-prospectus.php';
}

if(isset($_POST['add_offre'])){
	require 'saisie-manuelle-offre/03-add-offre.php';
}

if(isset($_GET['success'])){
	$arrSuccess=[
		'prosp-add'=>'Prospectus ajouté avec succès',
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
	<div class="row  py-5">
		<div class="col">
			<h1 class="text-main-blue  ">Saisie manuelle d'offre</h1>
		</div>
		<div class="col-auto">
			<a href="offre-gestion.php" class="btn btn-primary">Gestion des offres</a>
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
	<div class="row my-3">
		<div class="col">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-edit pr-3 text-orange"></i>Création du prospectus</h5>
		</div>
	</div>
	<?php include 'saisie-manuelle-offre/10-add-prospectus.php' ?>

	<div class="bg-separation"></div>
	<div class="row my-3">
		<div class="col">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-edit pr-3 text-orange"></i>saisie des offres</h5>
		</div>
	</div>
	<?php include 'saisie-manuelle-offre/12-add-offres.php'; ?>
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
				$("#fic-msg").text("");
				$("#fic-msg").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(fileSize)+"</div>");
				$('button[type="submit"]').removeAttr('disabled','disabled');

			}
			if(interdit==true){
				$("#fic-msg").text("");
				$('button[type="submit"]').attr('disabled','disabled');
				$("#fic-msg").append("<div class='text-danger'>Vous devez télécharger un fichier xml(<i class='fas fa-times px-1 text-danger'></i>)</div>");
			}
			if(fileSize > 52428800 && interdit==false){
				$("#fic-msg").text("");
				$('button[type="submit"]').attr('disabled','disabled');
				$("#fic-msg").append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(fileSize)+".<br>La taille est limitée à 50Mo, votre message ne pourra pas être envoyé</div>");

			}

			titre='<p><span class="text-main-blue font-weight-bold">Fichier(s) sélectionnés: <br></span>'
			end='</p>';
			all=titre+fileList+end;
			$('#fic-name').empty();

			$('#fic-name').append(all);
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

			$("#zone-noms").empty();
			$("#zone-ordre").empty();
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

		var x_timer=0.5;
		$('#search').keyup(function(){
            // clearTimeout(x_timer);
            var search = $(this).val();
            $.ajax({
            	url:"saisie-manuelle-offre/ajax-get-article.php",
            	method:"POST",
            	data:{search:search},
            	success:function(data)
            	{
            		$('#result').fadeIn();
            		$('#result').html(data);
            	}
            });

        });
		$(document).on('click','.selected-art',function(){
			var id= $(this).attr("data-id-ba");
			console.log("id "+id);
			$.ajax({
				url:"saisie-manuelle-offre/ajax-get-selected-article.php",
				method:"POST",
				data:{id:id},
				success:function(data){
					// $('#result').fadeIn();
					// $('#result').html(data);
					console.log(data);
					//
					json = $.parseJSON(data);
					console.log(json);

					console.log(json.marque);

					$('#marque').val(json.marque);
					$('#ean').val(json.ean);
					$('#produit').val(json.libelle);
					$('#reference').val(json.libelle);
					$('#gt').val(json.gt);
					$('#pvc').val(json.ppi);
					$('#result').empty();

				}
			});
		});






		$(document).on('click', 'li', function(){
			$('#search_term').val($(this).text());
			$('#magList').fadeOut();
		});

		$(document).on('keypress', '#search_term', function(e){
			if(e.which == 13){
				e.preventDefault();
				var url=$('.result-item').first().attr('href');
				var goto="./"+url;
				$(location).attr('href',goto);
			}

		});


// 4712900497045
});
</script>



<?php
require '../view/_footer-bt.php';
?>