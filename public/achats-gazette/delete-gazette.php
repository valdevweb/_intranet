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
require '../../Class/GazetteDao.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoDAchat=$db->getPdo('doc_achats');


$gazetteDao=new GazetteDao($pdoDAchat);

if (!isset($_GET['id']) || empty($_GET['id'])) {
	echo "pas de gazette sélectionnée, impossible d'afficher la page. <a href='gestion-gazette.php'>Retour</a>";
	exit();
}

if(isset($_GET['link'])){
	$gazetteDao->deleteLink($_GET['id']);
	$successQ='?id='.$_GET['id_gazette'].'#linkformtitle';
	header("Location: modif-gazette.php".$successQ,true,303);
}

if(isset($_GET['file'])){
	$gazetteDao->deleteFile($_GET['id']);
	$successQ='?id='.$_GET['id_gazette'].'#fileformtitle';
	header("Location: modif-gazette.php".$successQ,true,303);
}

//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Main title</h1>

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