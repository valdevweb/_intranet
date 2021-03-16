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

require '../../Class/GesapDao.php';

$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');


$gesapDao=new GesapDao($pdoDAchat);

if (!isset($_GET['id']) || empty($_GET['id'])) {
	echo "pas de gesap sélectionné, impossible d'afficher la page. <a href='gestion-gesap.php'>Retour</a>";
	exit();
}

if(isset($_GET['id'])){
	$gesapDao->deleteGesap($_GET['id']);
		$successQ='#deux';
	header("Location: gestion-gesap.php".$successQ,true,303);
}




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">SUpprimer</h1>
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
</div>

<?php
require '../view/_footer-bt.php';
?>