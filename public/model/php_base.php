<?php
//----------------------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){

	header('Location:'. ROOT_PATH.'/index.php');
}

//----------------------------------------------------------------
require "../../functions/stats.fn.php";
$descr="page pour réouvrir une demande";
$page=basename(__file__);
$action="consultation";
$code=101;
// addRecord($pdoStat,$page,$action, $descr,$code);

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
// $page=basename(__file__);
$pageCss=explode(".php",$page);
$pageCss=$pageCss[0];
$cssFile=ROOT_PATH ."/public/css/".$pageCss.".css";

//header et nav bar
include ('../view/_head-mig-bis.php');
include ('../view/_navbar.php');

//----------------------------------------------------------------
//			functions
//----------------------------------------------------------------

?>
<?php

//contenu
// include('news-alert.ct.php');


// footer avec les scripts et fin de html
include('../view/_footer-mig-bis.php');
 ?>