<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	echo "pas de variable session";
	header('Location:'. ROOT_PATH.'/index.php');
}
//			css dynamique
//----------------------------------------------------------------
$pageCss=explode(".php",basename(__file__));
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";


//------------------------------------------------------
//			REQUIRES
//------------------------------------------------------
// require_once '../../vendor/autoload.php';



//---------------------------------------
//	ajout enreg dans stat
//---------------------------------------
require "../../functions/stats.fn.php";
addRecord($pdoStat,basename(__file__),'consultation', "fil actu chargé de mission", 101);

$req=$pdoBt->query("SELECT *, DATE_FORMAT(date_upload, '%d-%m-%Y') as dateupload FROM doc_cm ORDER BY id DESC LIMIT 5");
$news=$req->fetchAll(PDO::FETCH_ASSOC);




 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];




//------------------------------------------------------
//			FONCTION
//------------------------------------------------------


//------------------------------------------------------
//			VIEW
//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>
<!--********************************
DEBUT CONTENU CONTAINER
*********************************-->
<div class="container">
	<div class="row">
		<div class="col">
			<img src="../img/icons/newspaper2.png" class="rounded float-left pr-3">

			<h1 class="text-main-blue py-5 ">Fil d'actu</h1>
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
	<div class="row my-5">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php if (!empty($news)): ?>

				<?php foreach ($news as $n): ?>
					<div class="row">
						<div class="col">
							<i class="fas fa-newspaper pr-3"></i><?=$n['dateupload']?> :	<a href="<?=UPLOAD_DIR.'/documents/'.$n['file']?>">télécharger</a>
						</div>
					</div>
					<div class="row">
						<div class="col pl-5">
							<?php if (!empty($n['info'])): ?>
								<?=$n['info']?>

							<?php endif ?>
						</div>
					</div>
				<?php endforeach ?>
			<?php endif ?>

		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row my-5 py-5">
		<div class="col"></div>
	</div>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>