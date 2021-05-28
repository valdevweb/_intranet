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

$catBt=$gazetteDao->getCatByMain(1);
$catGalec=$gazetteDao->getCatByMain(2);
$mainCat=[1 =>"btlec", 2 =>"galec"];

$listCat=$gazetteDao->getCat();
$listGazette=$gazetteDao->getGazetteThisWeek();
$gazetteDate="";
$dayOne=(new DateTime())->modify('- 60 days');
$dayOneStr=$dayOne->format('Y-m-d');

if(!empty($listGazette)){
	$listFiles=[];
	$listLinks=[];
	$listFiles=$gazetteDao->getFilesEncours();
	$listLinks=$gazetteDao->getLinkEncours();
}

if(isset($_POST['search'])){
	require 'search-form/01-submit-post.php';
}



//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row py-3">
		<div class="col">
			<h1 class="text-main-blue">Les infos gazette</h1>
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
		<div class="col">
			<h4 class="text-main-blue text-center pb-3">Les gazettes de la semaine</h4>
			<?php if (!empty($listGazette)): ?>
				<?php include 'gazette/10-list-gazette.php' ?>
				<?php else: ?>
					<div class="alert alert-primary">Il n'y a pas de gazette à afficher pour la semaine en cours</div>
				<?php endif ?>
			</div>
		</div>
		<div class="bg-separation"></div>
		<div class="row">
			<div class="col">
				<h4 class="text-main-blue text-center py-3">Rechercher une ancienne gazette</h4>
			</div>
		</div>
		<div class="row pb-5">
			<div class="col">
				<?php include 'search-form/10-search-form.php' ?>
			</div>
		</div>
		<?php if (isset($results)): ?>
			<div class="row">
				<div class="col">
					<?php include 'search-form/11-results.php' ?>

				</div>
			</div>


		<?php endif ?>
	</div>

	<script type="text/javascript">

		$(document).ready(function() {
			$('#main_cat').on('change', function() {

				var main_cat=$('#main_cat').val();
				$.ajax({
					type:'POST',
					url:'ajax-g-cat.php',
					data:{main_cat:main_cat},
					success: function(html){
						$("#cat").empty();
						$("#cat").append(html);
					}
				});
			});

			$('div.more-search').hide();
			$('div.more').hide();
			$('.show-link').on("click", function(){
				var id= $(this).data("gazette-id");
				if($('div[data-content-id="'+id+'"]').is(":visible")){
					$('div[data-content-id="'+id+'"]').hide();

				}else{
					$('div[data-content-id="'+id+'"]').show();
				}
			});
			$('.show-link-search').on("click", function(){
				var id= $(this).data("gazette-search-id");
				if($('div[data-content-search-id="'+id+'"]').is(":visible")){
					$('div[data-content-search-id="'+id+'"]').hide();

				}else{
					$('div[data-content-search-id="'+id+'"]').show();
				}
			});
		});

	</script>
	<?php
	require '../view/_footer-bt.php';
	?>