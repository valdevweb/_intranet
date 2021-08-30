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
require '../../Class/Evo/ModuleDao.php';
require '../../Class/evo/EvoHelpers.php';

// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoEvo=$db->getPdo('evo');


$moduleDao=new ModuleDao($pdoEvo);



if(empty($_GET['id'])){
	echo "une erreur c'est produite, le module n'a pas été trouvé";
	exit;
}

$arrResp=EvoHelpers::arrayAppliRespName($pdoEvo);

$module=$moduleDao->getModule($_GET['id']);
// $listDocModule=$moduleDao->getDocByModule();




//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div class="container">
	<div class="row pt-5 pb-3">
		<div class="col">
			<h1 class="text-main-blue">Module : <?=$module['module']?></h1>
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
		<div class="col bg-grey border border-dark p-3 rounded">
			<div class="row">
				<div class="col">
					<?=$module['plateforme']?><i class="fas fa-angle-double-right px-3"></i><?=$module['appli']?>
				</div>
				<div class="col"><i class="fas fa-user pr-3"></i>Développeur : <?=$arrResp[$module['id_resp']]?></div>
			</div>
			<div class="row">
				<div class="col">
					Description :<br>
					<?=$module['descr']?>
				</div>
			</div>
		</div>
	</div>


</div>

<?php
require '../view/_footer-bt.php';
?>