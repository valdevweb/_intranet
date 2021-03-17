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
require '../../Class/ArticleAchatsDao.php';
require '../../Class/FournisseursHelpers.php';
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
$opToDisplay=$infoLivDao->getOpAVenir();

$listGt=FournisseursHelpers::getGts($pdoFou, "libelle","id");
$gt="";


if (isset($_POST['filter_op'])) {
	$param=join(' OR ',array_map(
    function($value){return "code_op='".$value."'";},
    $_POST['select_op']));
	// $param= "code_op='".$_POST['select_op']."'";
	$opToDisplay=$infoLivDao->getOpByCode($param);
}

if (isset($_POST['search_by_week'])) {
	// récup article de la base doc_achats => si rien c'est qu'on a pas encore saisi d'info sur ce cata donc on cherche dans qlik
	$listArticle=$infoLivDao->getInfoLivByOp($_POST['op']);
	if(empty($listArticle)){
		$listArticle=$cataDao->getArticleByCodeOp($_POST['op']);
		$inInfoLiv=0;
	}
}

if (isset($_POST['search_by_cata'])) {
	$listArticle=$infoLivDao->getInfoLivByOp(strtoupper($_POST['code_op']));
	if(empty($listArticle)){
		$listArticle=$cataDao->getArticleByCodeOp(strtoupper($_POST['code_op']));
		$inInfoLiv=0;
	}
}
if(isset($_POST['save'])){
	if($_POST['update']==1){
		foreach ($_POST['article'] as $idArticle => $value) {
			$articleR=null;

			$recu=null;
			$recuDeux=null;

			if (!empty($_POST['article_remplace'][$idArticle])) {
				$articleR=$_POST['article_remplace'][$idArticle];
			}

			if (!empty($_POST['recu'][$idArticle])) {
				$recu=$_POST['recu'][$idArticle];
			}
			if (!empty($_POST['recu_deux'][$idArticle])) {
				$recuDeux=$_POST['recu_deux'][$idArticle];
			}
			$infoLivDao->updateInfoLiv($idArticle, $recu, $_POST['info_livraison'][$idArticle], $articleR,$_POST['ean_remplace'][$idArticle],$recuDeux, $_POST['info_livraison_deux'][$idArticle]);
		}
	}else{
		$infoOp=$cataDao->getOpByCode($_POST['code_op']);
		$idOp=$infoLivDao->insertOp($infoOp['code_op'], $infoOp['libelle'], $infoOp['cata'], $infoOp['origine'], $infoOp['date_start'], $infoOp['date_end']);
		foreach ($_POST['article'] as $idArticle => $value) {

			$idNewArticle=$articleDao->insertArticle($idOp, $_POST['article'][$idArticle], $_POST['dossier'][$idArticle], $_POST['libelle'][$idArticle], $_POST['ean'][$idArticle], $_POST['gt'][$idArticle], $_POST['marque'][$idArticle], $_POST['fournisseur'][$idArticle], $_POST['cnuf'][$idArticle], $_POST['deee'][$idArticle], $_POST['ppi'][$idArticle]);
			$articleR=null;
			$recu=null;
			$recuDeux=null;

			if (!empty($_POST['article_remplace'][$idArticle])) {
				$articleR=$_POST['article_remplace'][$idArticle];
			}

			if (!empty($_POST['recu'][$idArticle])) {
				$recu=$_POST['recu'][$idArticle];
			}
			if (!empty($_POST['recu_deux'][$idArticle])) {
				$recuDeux=$_POST['recu_deux'][$idArticle];
			}
			$infoLivDao->insertInfoLiv($idNewArticle, $recu, $_POST['info_livraison'][$idArticle], $articleR,$_POST['ean_remplace'][$idArticle],  $recu, $_POST['info_livraison_deux'][$idArticle], );


		}
	}
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
			<?php include 'suivi-liv-gestion/form-info-liv.php' ?>
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
					<?php include 'suivi-liv-gestion/select-info-liv.php' ?>
					<?php include 'suivi-liv-gestion/table-info-liv.php' ?>
					<?php else: ?>
						<div class="alert alert-primary">Aucune information livraison n'a été saisie pour les opérations à venir</div>
					<?php endif ?>
				</div>
			</div>
		</div>
		<script type="text/javascript">
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
			});
		</script>


		<?php
		require '../view/_footer-bt.php';
		?>