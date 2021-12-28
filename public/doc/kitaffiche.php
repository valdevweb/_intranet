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
$pdoBt=$db->getPdo('btlec');




$req=$pdoBt->query("SELECT * FROM documents WHERE id_doc_type=9");
$kitData=$req->fetch(PDO::FETCH_ASSOC);




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div id="container" class="container">
	<div class="row py-5">
		<div class="col">
			<h1 class="text-main-blue">Kits affiches</h1>
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
	<div class="row pb-5">
		<div class="col">
			<h4 class="blue-text text-darken-4" ><i class="fa fa-hand-o-right" aria-hidden="true"></i><?= $kitData['name'] ?></h4>
			<a  class= "blue-link" href="<?=URL_UPLOAD."documents/".$kitData['file']?>">télécharger le kit affiches</a>
		</div>
	</div>

	<!-- contenu -->
</div>

<?php
require '../view/_footer-bt.php';
?>