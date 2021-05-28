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
require '../../Class/cm/RapportDao.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoCm=$db->getPdo('cm');

$rapportDao=new RapportDao($pdoCm);


$listRapport=$rapportDao->magRapportSent($_SESSION['id_galec']);






//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Liste des comptes rendu de votre chargé de mission</h1>
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


<?php if (!empty($listRapport)): ?>
	<ul>
	<?php foreach ($listRapport as $key => $rapport): ?>
		<li><a href="rapport-consult.php?id=<?=$rapport['id_rdv']?>">Visite du <?=date('d-m-Y', strtotime($rapport['date_start']))?></a></li>
	<?php endforeach ?>
	</ul>
	<?php else: ?>
		<div class="alert alert-primary">Aucun rapport n'a encore été saisi</div>


<?php endif ?>

			</div>
		</div>



</div>

<?php
require '../view/_footer-bt.php';
?>