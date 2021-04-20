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
require '../../Class/FouDao.php';
// require_once '../../vendor/autoload.php';


$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoQlik=$db->getPdo('qlik');
$pdoFou=$db->getPdo('fournisseurs');
$pdoDAchat=$db->getPdo('doc_achats');

$cdesDao=new CdesDao($pdoQlik);
$cdesAchatDao=new CdesAchatDao($pdoDAchat);
$cdesRelancesDao=new CdesRelancesDao($pdoDAchat);

$userDao= new UserDao($pdoUser);
$fouDao=new FouDao($pdoFou);




$param=null;

if (isset($_GET)) {
	$param='AND (';
	$param.=join(' OR ', array_map(function($value){ return 'gt='.$value;},	$_GET));
	$param.=' )';

}else{
	echo "Merci de sélectionner un GT pour faire les relances";
	exit();
}


// echo "<pre>";
// print_r($_SESSION);
// echo '</pre>';
	echo "<pre>";
		print_r($_POST);
		echo '</pre>';


if(isset($_POST['launch_relance_one_auto'])){


	$dOne=((new DateTime())->modify('+49 day'));
	$dateStart=clone $dOne;
	$dateEnd=clone $dOne;
	$dateStart=$dateStart->modify('monday this week');
	$dateEnd=$dateEnd->modify('sunday this week');


	$relancesAuto=$cdesDao->getCdesOpRelancesGroupByCnuf($dateStart, $dateEnd, $param);
	$relancesPrevi=$cdesAchatDao->getInfosOpRelancesWithDatePrevi($dateStart, $dateEnd, $param);
}elseif(isset($_POST['launch_relance_two_auto'])){


	$dTwo=((new DateTime())->modify('+35 day'));
	$dateStart=clone $dTwo;
	$dateEnd=clone $dTwo;
	$dateStart=$dateStart->modify('monday this week');
	$dateEnd=$dateEnd->modify('sunday this week');

	$relancesAuto=$cdesDao->getCdesOpRelancesGroupByCnuf($dateStart, $dateEnd, $param);
	$relancesPrevi=$cdesAchatDao->getInfosOpRelancesWithDatePrevi($dateStart, $dateEnd, $param);
}elseif(isset($_POST['launch_relance_perm_auto'])){



	$relancesAuto=$cdesDao->getCdesPermRelancesGroupByCnuf($param);
	$relancesPrevi=$cdesAchatDao->getInfosOpPermWithDatePrevi($param);

}





foreach ($relancesAuto as $keyCnuf => $value) {
	$listContact=$fouDao->getFouContact($keyCnuf);

	$idR=$cdesRelancesDao->insertRelance($keyCnuf, null);

	if(!empty($listContact)){
		foreach($listContact as $m =>$contact){
			$cdesRelancesDao->insertEmail($idR, $contact['email'], $contact['id']);
		}
	}else{

	}

	foreach ($relancesAuto[$keyCnuf] as $key => $detail) {

		if (!isset($relancesPrevi[$detail['id_detail']])) {
			$cdesRelancesDao->insertRelanceDetail($idR, $detail['id_detail'],  $detail['qte_cde']);
		}

		// echo $idR;
	}


}

if (isset($_POST['launch_relance_one_auto']) || isset($_POST['launch_relance_two_auto'])) {
	header('Location:relances-synthese.php?op');
}elseif(isset($_POST['launch_relance_perm_auto'])){
	header('Location:relances-synthese.php?perm');
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