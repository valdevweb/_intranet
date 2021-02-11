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

function getFileNews($pdoBt,$onoff){
	$req=$pdoBt->prepare("SELECT html_file, id FROM occ_news WHERE onoff= :onoff");
	$req->execute([
		':onoff'	=>$onoff
	]);
	$data=$req->fetchAll(PDO::FETCH_ASSOC);

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
$target_dir = "D:\\www\\_intranet\\upload\\flash\\";
$pjDir=UPLOAD_DIR.'\\flash\\';
$listNews=getFileNews($pdoBt,1);







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
		<h1>Les news Leclerc occasion</h1>
	</div>
	</div>

	<div class="row pt-5">
		<div class="col">

			<?php if (!empty($listNews)): ?>
				<?php foreach ($listNews as $key => $news): ?>
					<div class="row pb-5">
						<div class="col onenews">
							<div class="row">
								<div class="col">
									<?php
									$theNews=$target_dir.$news['html_file'].'.html';

									 include  $theNews;
									 ?>

								</div>
							</div>
							<?php $listPj=getPj($pdoBt,$news['id']);?>
							<?php if (!empty($listPj)): ?>
								<?php foreach ($listPj as $key => $pj): ?>
									<div class="row">
										<div class="col">
											<a href="<?=$pjDir.$pj['pj']?>" class="pr-3" target="_blank"><?= $pj['pj']?></a>
										</div>
									</div>
								<?php endforeach ?>

								<?php endif ?></div>
							</div>
							<div class="bg-separation"></div>
						<?php endforeach ?>

					<?php endif ?>

				</div>
			</div>
			<!-- ./container -->
		</div>

		<?php
		require '../view/_footer-bt.php';
		?>