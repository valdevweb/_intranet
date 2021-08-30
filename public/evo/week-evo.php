



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
require '../../Class/evo/PlanningDao.php';
require '../../Class/evo/EvoDao.php';
require '../../Class/DateHelpers.php';
require_once '../../vendor/autoload.php';

function getIsoWeeksInYear($year) {
	$date = new DateTime;
	$date->setISODate($year, 53);
	return ($date->format("W") === "53" ? 53 : 52);
}
$errors=[];
$success=[];
$db=new Db();
$pdoUser=$db->getPdo('web_users');
$pdoEvo=$db->getPdo('evo');


$planningDao=new PlanningDao($pdoEvo);
$evoDao=new EvoDao($pdoEvo);

if(!isset($_GET)){
	echo "une erreur s'est produite"	;

	exit();
}

$week=$_GET['week'];
unset($_GET['week']);
$param=join(' OR ', array_map(function($value){ return 'evos.id='.$value;},	$_GET));

$evos=$evoDao->getEvoParam($param);
if (!empty($evos)) {
	$mpdf = new \Mpdf\Mpdf();

	ob_start();
	include('pdf-week.php');
	$html=ob_get_contents();
	ob_end_clean();

	$mpdf->WriteHTML($html);
	$mpdf->Output();
}


