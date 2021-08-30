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
require '../../Class/CataDao.php';
require '../../Class/InfoLivDao.php';
require '../../Class/achats/ArticleAchatsDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/DateHelpers.php';
require '../../Class/FormHelpers.php';


// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoDAchat=$db->getPdo('doc_achats');
$pdoFou=$db->getPdo('fournisseurs');


$inInfoLiv=1;

$cataDao= new CataDao($pdoQlik);
$infoLivDao=new infoLivDao($pdoDAchat);
$articleDao=new ArticleAchatsDao($pdoDAchat);


// pour le bouton slect
$listOpAVenir=$infoLivDao->getOpAVenir();
$opToDisplay=$listOpAVenir;

$listGt=FournisseursHelpers::getGts($pdoFou, "libelle","id");
$gt="";


include 'suivi-livraison-commun/01-filter-op.php';

if (isset($_POST['search_by_week'])) {
	$listArticle=$cataDao->getArticleByCodeOp($_POST['op']);
	$listInfoLivraison=$infoLivDao->getInfoLivByOpKeyArticle($_POST['op']);
}

if (isset($_POST['search_by_cata'])) {

	$listArticle=$cataDao->getArticleByCodeOp(strtoupper($_POST['code_op']));
	$listInfoLivraison=$infoLivDao->getInfoLivByOpKeyArticle($_POST['code_op']);
}
if(isset($_GET['code_op'])){
	$listArticle=$cataDao->getArticleByCodeOp(strtoupper($_GET['code_op']));
	$listInfoLivraison=$infoLivDao->getInfoLivByOpKeyArticle($_GET['code_op']);
}




if(isset($_POST['save'])){


	$idArticle= key($_POST['save']);
	$erratumFilename="";
	if(isset($_FILES['file_erratum']['tmp_name'][$idArticle]) && !empty($_FILES['file_erratum']['tmp_name'][$idArticle])){

		$orginalFilename=$_FILES['file_erratum']['name'][$idArticle];
		$ext = pathinfo($orginalFilename, PATHINFO_EXTENSION);

		$filenameNoExt = basename($orginalFilename, '.'.$ext);
		$erratumFilename = $filenameNoExt . time() . '.' . $ext;
		$uploaded=move_uploaded_file($_FILES['file_erratum']['tmp_name'][$idArticle],DIR_UPLOAD.'erratum\\'.$erratumFilename );
		if($uploaded==false){
			$errors[]="Nous avons rencontré un problème avec votre fichier, impossible de l'uploader vers le serveur";
		}
	}
	$articleR=null;
	$recu=null;
	$recuDeux=null;
	$recuRemplace=null;
	$recuRemplaceDeux=null;
	if (!empty($_POST['article_remplace'][$idArticle])) {
		$articleR=$_POST['article_remplace'][$idArticle];
	}

	if (!empty($_POST['recu'][$idArticle]) || ($_POST['recu'][$idArticle])==0) {
		$recu=$_POST['recu'][$idArticle];

	}

	if (!empty($_POST['recu_deux'][$idArticle]) || ($_POST['recu_deux'][$idArticle])==0) {
		$recuDeux=$_POST['recu_deux'][$idArticle];

	}
	if (!empty($_POST['recu_remplace'][$idArticle]) || ($_POST['recu_remplace'][$idArticle])==0) {
		$recuRemplace=$_POST['recu_remplace'][$idArticle];
	}
	if(!empty($_POST['recu_deux_remplace'][$idArticle]) || ($_POST['recu_deux_remplace'][$idArticle])==0){
		$recuRemplaceDeux=$_POST['recu_deux_remplace'][$idArticle];
	}



	if ($_POST['exist'][$idArticle]=="false") {
		// pas encore d'info article donc on ajoute l'article et on ajoute l'info livraison
		// on verifie si on a déja l'op dans la base doc_achats, table operation
		$opExist=$infoLivDao->getOpByOp($_POST['code_op']);

		if(empty($opExist)){
			$infoOp=$cataDao->getOpByCode($_POST['code_op']);
			$idOp=$infoLivDao->insertOp($infoOp['code_op'], $infoOp['libelle'], $infoOp['cata'], $infoOp['origine'], $infoOp['date_start'], $infoOp['date_end']);
		}else{
			$idOp=$opExist['id'];
		}
		$idNewArticle=$articleDao->insertArticle($idOp, $_POST['article'][$idArticle], $_POST['dossier'][$idArticle], $_POST['libelle'][$idArticle], $_POST['ean'][$idArticle], $_POST['gt'][$idArticle], $_POST['marque'][$idArticle], $_POST['fournisseur'][$idArticle], $_POST['cnuf'][$idArticle], $_POST['deee'][$idArticle], $_POST['ppi'][$idArticle]);

		$infoLivDao->insertInfoLiv($idNewArticle, $recu, $_POST['info_livraison'][$idArticle], $articleR,$_POST['ean_remplace'][$idArticle],  $recuDeux, $_POST['info_livraison_deux'][$idArticle], $recuRemplace,$_POST['info_livraison_remplace'][$idArticle],$recuRemplaceDeux,$_POST['info_livraison_deux_remplace'][$idArticle],$erratumFilename);



	}elseif($_POST['exist'][$idArticle]=="true"){
		// déjà une info article donc on n'ajoute pas l'article et on met à jour l'info livraison
		if(empty($erratumFilename)){
			$infoLivDao->updateInfoLiv($_POST['id_article_table_article'][$idArticle], $recu, $_POST['info_livraison'][$idArticle], $articleR,$_POST['ean_remplace'][$idArticle],$recuDeux, $_POST['info_livraison_deux'][$idArticle], $recuRemplace,$_POST['info_livraison_remplace'][$idArticle],$recuRemplaceDeux,$_POST['info_livraison_deux_remplace'][$idArticle]);
		}else{
			$infoLivDao->updateInfoLivErratum($_POST['id_article_table_article'][$idArticle], $recu, $_POST['info_livraison'][$idArticle], $articleR,$_POST['ean_remplace'][$idArticle],$recuDeux, $_POST['info_livraison_deux'][$idArticle],  $recuRemplace,$_POST['info_livraison_remplace'][$idArticle],$recuRemplaceDeux,$_POST['info_livraison_deux_remplace'][$idArticle],$erratumFilename);
		}

		$idOp=$_POST['id_op'];
	}


	if(isset($erratumFilename)){
		if(isset($idNewArticle)){
			$infoLivDao->updateErratum($idNewArticle, $erratumFilename);
		}else{
			$infoLivDao->updateErratum($idArticle, $erratumFilename);

		}
	}

	$successQ='?code_op='.$_POST['code_op']."#".$idArticle;
	unset($_POST);
	header("Location: ".$_SERVER['PHP_SELF'].$successQ,true,303);

}




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Suivi  livraison - gestion </h1>
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
	<div class="row pb-3">
		<div class="col">
			<h5 class="text-main-blue border-bottom pb-3 my-3"><i class="fas fa-edit pr-3 text-orange"></i>Saisie d'information livraison</h5>
		</div>
	</div>

	<div class="row">
		<div class="col pb-3">
			<h6 class="text-main-blue"><span class="step">1</span>Sélection du catalogue</h6>
		</div>
	</div>
	<?php include '../achats-commun/10-form-search-cata.php' ?>
	<?php if (isset($listArticle)): ?>
		<?php if (!empty($listArticle)): ?>
			<?php include 'suivi-liv-gestion/10-form-info-liv.php' ?>
			<?php else: ?>
				<div class="alert alert-danger">Aucun article trouvé pour votre sélection</div>
			<?php endif ?>
		<?php endif ?>
		<div class="bg-separation"></div>
		<div class="row py-3">
			<div class="col">
				<h5 class="text-main-blue  border-bottom pb-3 my-3"><i class="fas fa-box-open text-orange pr-3"></i>Informations livraison opérations à venir</h5>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<?php if (!empty($listOpAVenir)): ?>
					<?php include 'suivi-livraison-commun/11-select-info-liv.php' ?>
					<?php include 'suivi-livraison-commun/12-table-info-liv.php' ?>
					<?php else: ?>
						<div class="alert alert-primary">Aucune information livraison n'a été saisie pour les opérations à venir</div>
					<?php endif ?>
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

				$('.hidden-form').hide();
				$('.hidden-form-erratum').hide();
				$('.show-form').on("click", function(){
					var id= $(this).data("btn-id");
					if($('div[data-form-id="'+id+'"]').is(":visible")){
						$('div[data-form-id="'+id+'"]').hide();

					}else{
						$('div[data-form-id="'+id+'"]').show();
					}
				});
				$('.show-form-erratum').on("click", function(){
					var id= $(this).data("btn-erratum-id");
					if($('div[data-form-erratum-id="'+id+'"]').is(":visible")){
						$('div[data-form-erratum-id="'+id+'"]').hide();

					}else{
						$('div[data-form-erratum-id="'+id+'"]').show();
					}
				});
				var url = window.location + '';
				var splited=url.split("#");
				if(splited[1]==undefined){
					var line='';
				}
				else if(splited.length==2){
					var line=splited[1];
					console.log(line);
					$("div#"+line).addClass("anim");
				}

				function offsetAnchor() {
					if (window.location.hash.length !== 0) {
						window.scrollTo(window.scrollX, window.scrollY - 500 );
					}
				}

				window.setTimeout(offsetAnchor, 0);




				$('input[type="file"]').change(function(){
					console.log("djfhsf");
					console.log($(this).get(0).files[0]);
					var fileList='';
					var warning  ="";
					var fileSize=$(this).get(0).files[0].size;
					var fileName=$(this).get(0).files[0].name;
					var articleId=$(this).attr("data-id-input");

					var extension=fileName.replace(/^.*\./, '');

					fileList += fileName + warning+'<br>';
					if(fileSize <= 52428800 ){

						// $(".file-erratum-msg").find(`[data-id-msg='${articleId}']`).append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(fileSize)+"</div>");
						$(".file-erratum-msg").text("");
						$(".file-erratum-msg").append("<div class='text-success'>Taille totale : "+ getReadableFileSizeString(fileSize)+"</div>");
						// $('button[type="submit"]').removeAttr('disabled','disabled');
					}

					if(fileSize > 52428800){
						$(".file-erratum-msg").text("");
						$('button[type="submit"]').attr('disabled','disabled');
						$(".file-erratum-msg").append("<div class='text-danger'>Taille totale : "+getReadableFileSizeString(fileSize)+".<br>La taille est limitée à 50Mo, votre ODR ne pourra pas être enregistrée</div>");
					}

					titre='<p><span class="text-main-blue font-weight-bold">Fichier sélectionné: <br></span>'
					end='</p>';
					all=titre+fileList+end;
					$('.filename-erratum').empty();
					$('.filename-erratum').append(all);
					fileList="";
				});

			});

			var emplacement = null;

			function findString(str) {
				if (parseInt(navigator.appVersion) < 4) return;
				var strFound;
				if (window.find) {
					strFound = self.find(str);
					if (strFound && self.getSelection && !self.getSelection().anchorNode) {
						strFound = self.find(str)
					}
					if (!strFound) {
						strFound = self.find(str, 0, 1)
						while (self.find(str, 0, 1)) continue
					}
			}else if(navigator.appName.indexOf("Microsoft") != -1) {
				if (emplacement != null) {
					emplacement.collapse(false)
					strFound = emplacement.findText(str)
					if (strFound) emplacement.select()
				}
			if (emplacement == null || strFound == 0) {
				emplacement = self.document.body.createTextRange()
				strFound = emplacement.findText(str)
				if (strFound) emplacement.select()
			}
	}
	if (!strFound) alert("String '" + str + "' non trouvé!")
		return;
};

document.getElementById('search_form').onsubmit = function() {
	findString(this.str.value);
	return false;
};

</script>

<?php
require '../view/_footer-bt.php';
?>