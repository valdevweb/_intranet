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
// require "../../functions/stats.fn.php";
// addRecord($pdoStat,basename(__file__),'consultation', "fichiers d'info du service achats", 101);

//------------------------------------------------------
//			FONCTION
//------------------------------------------------------

function getFileNews($pdoBt){
	$req=$pdoBt->prepare("SELECT html_file, id FROM occ_news WHERE html_file LIKE :html_file");
	$req->execute([
		':html_file'	=>$_GET['file']
	]);
	$data=$req->fetch(PDO::FETCH_ASSOC);

	if(!empty($data)){
		return $data;
	}
	return false;
}

function getPj($pdoBt,$idNews){
	$req=$pdoBt->prepare("SELECT * FROM occ_news_file WHERE id_occ_news= :id_occ_news");
	$req->execute([
		':id_occ_news'	=>$idNews
	]);
	$data=$req->fetchAll(PDO::FETCH_ASSOC);
	if(!empty($data)){
		return $data;
	}
	return '';
}

 //------------------------------------------------------
//			DECLARATIONS
//------------------------------------------------------
$errors=[];
$success=[];

if(VERSION=="_"){
	$target_dir = "D:\\www\\_intranet\\upload\\flash\\";
}else{
	$target_dir = "D:\\www\\intranet\\upload\\flash\\";
}

$pjDir=UPLOAD_DIR.'\\flash\\';
$data=getFileNews($pdoBt);


if($data){
	$htmlfile=$target_dir.$data['html_file'].'.html';
	$listPj=getPj($pdoBt,$data['id']);
}







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
		<div class="col-lg-1"></div>
		<div class="col">
			<?php include $htmlfile ?>
		</div>
		<div class="col-lg-1"></div>
	</div>

	<div class="row">
		<div class="col">
			<?php if (!empty($listPj)): ?>
				<?php foreach ($listPj as $key => $pj): ?>
					<a href="<?=$pjDir.$pj['pj']?>" class="pr-3" target="_blank"><?= $pj['pj']?></a>
				<?php endforeach ?>

			<?php endif ?>
		</div>
	</div>
	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>