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
require '../../Class/InfoLivDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/DateHelpers.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');
$pdoFou=$db->getPdo('fournisseurs');

$infoLivDao=new infoLivDao($pdoDAchat);

$listOpAVenir=$infoLivDao->getOpAVenir();

$opToDisplay=$infoLivDao->getOpAVenir();


$listGt=FournisseursHelpers::getGts($pdoFou, "libelle","id");
$gt="";

include 'suivi-livraison-commun/01-filter-op.php';



//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Le suivi livraison</h1>
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
		<?php if (!empty($listOpAVenir)): ?>
			<?php include 'suivi-livraison-commun/11-select-info-liv.php' ?>
			<?php include 'suivi-livraison-commun/12-table-info-liv.php' ?>
			<?php else: ?>
				<div class="alert alert-primary">Aucune information livraison n'a été saisie pour les opérations à venir</div>
			<?php endif ?>
		</div>
	</div>
</div>

	<?php
	require '../view/_footer-bt.php';
	?>