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
require '../../Class/CrudDao.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoCasse=$db->getPdo('casse');



function updateExpsSav($pdoCasse){
	$req=$pdoCasse->query("UPDATE exps SET id_affectation= 3 WHERE exp=1 AND mt_fac is null");
}

function updateExpsMag($pdoCasse){
	$req=$pdoCasse->query("UPDATE exps SET id_affectation= 1 WHERE exp=1 AND mt_fac is not null");
}

function updateAffectationPalette($pdoCasse){
	$req=$pdoCasse->prepare("UPDATE palettes LEFT JOIN exps ON palettes.id_exp= exps.id SET palettes.id_affectation=exps.id_affectation");
	$req->execute([

	]);
	return $req->rowCount();
}


$casseCrud=new CrudDao($pdoCasse);
$qlikCrud=new CrudDao($pdoQlik);

$articles=$casseCrud->getMany("casses", "" );
	echo "<pre>";
	print_r($articles);
	echo '</pre>';


// foreach ($articles as $key => $art) {

// 	$ppi=$qlikCrud->getOneWhere("ba", " WHERE id=".$art['article'].$art['dossier']);
// 	echo "ean" .$ppi['ean'];
// 	echo "<br>";

// 	if(!empty($ppi)){
// 		$casseCrud->update("casses","id=".$art['id'], ['ppi'=>$ppi['ppi'], 'ean'	=>$ppi['ean']]);
// 	}

// }









// update des id_affectation sur les exps
// on met à jour les expé traitées , les autres il faudra faire à la main

// updateExpsMag($pdoCasse);
// updateExpsSav($pdoCasse);

// updateAffectationPalette($pdoCasse);

// UPDATE `palettes` SET `id_affectation`=1

// UPDATE `palettes` SET `id_affectation`=3 WHERE `palette` LIKE '%hs%'


//------------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');
?>

<div id="container" class="container">
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