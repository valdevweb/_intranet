<?php
// ---------------------------------------------------
// SESSION & AUTOLOAD
//----------------------------------------------------
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
header('Location:'. ROOT_PATH.'/index.php');
}
//----------------------------------------------------
// REQUIRED FUNCTIONS
//----------------------------------------------------
//require '../../functions/form.fn.php';
// require 'export.php';

//----------------------------------------------------
// DATA LOGIC
//----------------------------------------------------


//-----------------------------------------------------
//	css dynamique
//-----------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";

// <---------------------------------------------------
// STATS - add rec
//-----------------------------------------------------
// require "../../functions/stats.fn.php";
// $descr="demande mag au service ".$gt ;
// $page=basename(__file__);
// $action="envoi d'une demande";
// addRecord($pdoStat,$page,$action, $descr);
//------------------------------------->
//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------
require '../view/_head.php';
require '../view/_navbar.php';
//----------------------------------------------------
// VIEW - CONTENT
//----------------------------------------------------
 require inscription.ct.php;




//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer.php';

?>