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
require '../../Class/OffreDao.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');

$offreDao=new OffreDao($pdoDAchat);


if(isset($_GET['id'])){
	$offreDao->deleteOffre($_GET['id']);
	header("Location: offre-gestion.php#list-offre",true,303);

}

if(isset($_GET['file'])){
	$offreDao->deleteFile($_GET['file']);
	header("Location: modify-prosp.php?id=".$_GET['id-prosp']."#modif-file",true,303);
}
if(isset($_GET['link'])){
	$offreDao->deleteLink($_GET['link']);
	header("Location: modify-prosp.php?id=".$_GET['id-prosp']."#modif-link",true,303);
}
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<h1 class="text-main-blue py-5 ">Main title</h1>
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