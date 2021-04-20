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
require '../../Class/CdesDao.php';
require '../../Class/CdesAchatDao.php';
require '../../Class/CdesRelancesDao.php';
require '../../Class/FournisseursHelpers.php';
require '../../Class/UserDao.php';
require '../../Class/UserHelpers.php';
require '../../Class/FouDao.php';
require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoFou=$db->getPdo('fournisseurs');
$pdoDAchat=$db->getPdo('doc_achats');

$cdesDao=new CdesDao($pdoQlik);
$cdesRelancesDao=new CdesRelancesDao($pdoDAchat);
$cdesAchatDao=new CdesAchatDao($pdoDAchat);
$userDao= new UserDao($pdoUser);
$fouDao=new FouDao($pdoFou);

$listRelances=$cdesRelancesDao->getRelancesToSend();
$ligne="";

if(isset($_SESSION['temp_relance'])){
	unset($_SESSION['temp_relance']);
}
if(isset($_SESSION['temp_relance_perm'])){
	unset($_SESSION['temp_relance_perm']);
}

if(empty($listRelances)){
	echo "aucune relance à envoyer";
	exit();
}
foreach ($listRelances as $key => $relance) {
	$ligne="";

	$emails=$cdesRelancesDao->getRMailToSend($relance['id']);
	$detailsRelance=$cdesRelancesDao->getRDetailToSend($relance['id']);
	foreach ($detailsRelance as $key => $encours) {
		$prod=$cdesDao->getEncours($encours['id_encours']);
		$infoEncours=$cdesAchatDao->getInfoIdEncours($encours['id_encours']);
		$cmts=implode("<br>", array_column($infoEncours,'cmt'));

		$qteUv=$prod['qte_init']*$prod['cond_carton'];
		if (isset($_GET['op'])) {

			$ligne.="<tr><td>".$prod['id_cde']."</td><td>" .$prod['libelle_op']."</td><td>" . $prod['date_start'] ."</td><td>" . $prod['ref'] ."</td><td>" . $prod['ean'] ."</td><td>" . $prod['libelle_art'] ."</td><td>" . $prod['cond_carton'] ."</td><td>"  . $qteUv ."</td><td>" . $prod['qte_init']  ."</td><td>" . $encours['qte_restante'] ."</td><td>" . $cmts. "</td></tr>";
		}elseif(isset($_GET['perm'])){
			$ligne.="<tr><td>".$prod['id_cde']."</td><td>" . $prod['ref'] ."</td><td>" . $prod['ean'] ."</td><td>" . $prod['libelle_art'] ."</td><td>" . $prod['cond_carton'] ."</td><td>"  . $qteUv ."</td><td>" . $prod['qte_init']  ."</td><td>" . $encours['qte_restante'] ."</td><td>" . $cmts. "</td></tr>";
		}

	}
	if (isset($_GET['op'])) {
		include "relances-synthese/send-mail-op.php";

	}elseif(isset($_GET['perm'])){
		include "relances-synthese/send-mail-perm.php";

	}
}




if(empty($errors)){
	$listRelances=$cdesRelancesDao->getRelancesToSend();
	if(empty($listRelances)){
		$successQ='?success=relance';
		unset($_POST);

		header("Location: encours-relances.php".$successQ,true,303);
	}else{
		foreach ($listRelances as $key => $relance) {
			$errors[]='La relance ' .$relance['id']. ' n\'a pas pu être envoyée';
		}
	}
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