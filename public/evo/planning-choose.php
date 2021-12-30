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
require "../../Class/evo/EvoDao.php";

// require_once '../../vendor/autoload.php';

function getIsoWeeksInYear($year) {
	$date = new DateTime;
	$date->setISODate($year, 53);
	return ($date->format("W") === "53" ? 53 : 52);
}
$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoEvo=$db->getPdo('evo');




$evoDao=new EvoDao($pdoEvo);

$listResp=$evoDao->getListResp();




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container-fluid  bg-white">
	<div class="row py-3">
		<div class="col">
			<h1 class="text-main-blue text-center">Planning </h1>
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
		<div class="col-lg-1"></div>

		<?php foreach ($listResp as $key => $resp): ?>
			<div class="col">
				<a href="planning-evo.php?id=<?=$resp['idwebuser']?>">Planning <?=$resp['resp']?></a>

			</div>
		<?php endforeach ?>
		<div class="col-lg-1"></div>

	</div>

</div>

<?php
require '../view/_footer-bt.php';
?>