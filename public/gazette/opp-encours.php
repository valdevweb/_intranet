<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//			css spécifique
//----------------------------------------------------------------
$pageCss='opp-display-inc';
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

require_once '../../Class/OpportuniteDAO.php';


$errors=[];
$success=[];

define("DIR_UPLOAD_OPP",DIR_UPLOAD."opportunites\\");
define("URL_UPLOAD_OPP",URL_UPLOAD."opportunites/");
$oppDao=new OpportuniteDAO($pdoBt);


$listOpp=$oppDao->getActiveOpp();




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
	<h1 class="text-main-blue pt-5">Offres spéciales en cours</h1>

	<div class="row">
		<div class="col-lg-1"></div>
		<div class="col">
			<?php
			include('../view/_errors.php');
			?>
		</div>
		<div class="col-lg-1"></div>
	</div>
	<div class="row mb-3">
		<div class="col-lg-2 col-xl-3"></div>
		<div class="col border rounded">
			<div class="text-center">Accès rapide : </div>
			<ul class="leaders">
				<?php foreach ($listOpp as $key => $opp): ?>
					<li><span>
						<a href="#<?=$opp['id']?>" class="mini-nav"><?=$opp['title']?></a>
						<?=($opp['date_start']==date('Y-m-d') ||  $opp['date_start']==(new DateTime('yesterday'))->format('Y-m-d'))?
						"<span class='badge badge-warning ml-3'>Nouveau</span>" :""
						?>
					</span>
					<span>jusqu'au <?=date('d/m/Y', strtotime($opp['date_end']))?></span>
				</li>
				<?php $oppIds[]=$opp['id'];?>
			<?php endforeach ?>
		</ul>
	</div>
	<div class="col-lg-2 col-xl-3"></div>
</div>

<?php if(!empty($listOpp)):?>
	<?php include 'opp-display-inc.php';?>
	<?php else: ?>

		<div class="row mb-5">
			<div class="col">
				<div class="alert alert-primary">
					Aucune offre spéciale à afficher
				</div>
			</div>
		</div>
	<?php endif ?>

	<!-- ./container -->
</div>

<?php
require '../view/_footer-bt.php';
?>