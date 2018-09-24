<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------------------
require_once '../../functions/form.fn.php';
require "../../functions/stats.fn.php";
$descr="historique côté mag";
$page=basename(__file__);
$action="tableau général";
addRecord($pdoStat,$page,$action, $descr);

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

//header et nav bar
include ('../view/_head-mig.php');
include ('../view/_navbar.php');
// echo "session " . $_SESSION['id'];
//contenu
include('histo-mag.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer.php');