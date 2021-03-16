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
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');







//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<h1 class="text-main-blue py-5 ">Achats</h1>
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
			La Gazette
		</div>
		<div class="col">
			Les Gesap
		</div>
		<div class="col">
			Les ODR
		</div>
	</div>
		<div class="row">
			<div class="col">
					Les offres produits (TEL/BRII)
			</div>
			<div class="col">Les offres spéciales</div>
			<div class="col">Le suivi des livraison</div>
		</div>


</div>

<?php
require '../view/_footer-bt.php';
?>