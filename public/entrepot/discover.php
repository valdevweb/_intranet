<?php
require('../../config/autoload.php');
if(!isset($_SESSION['id'])){
	header('Location:'. ROOT_PATH.'/index.php');
	exit();
}
require '../../config/db-connect.php';

//----------------------------------------------------------------
//			css dynamique
//----------------------------------------------------------------
$page=(basename(__FILE__));
$page=explode(".php",$page);
$page=$page[0];
$cssFile=ROOT_PATH ."/public/css/".$page.".css";
// <------------------------------------
// STATS - add rec
//--------------------------------------
require "../../functions/stats.fn.php";
$descr="page visite entrepot";
$page=basename(__file__);
$action="consultation";
addRecord($pdoStat,$page,$action, $descr);
//----------------------------------------------------
// REQUIRED FUNCTIONS
//----------------------------------------------------
//require '../../functions/form.fn.php';
//----------------------------------------------------
// DATA LOGIC
//----------------------------------------------------
//----------------------------------------------------
// VIEW - HEADER
//----------------------------------------------------
include('../view/_head-bt.php');
include('../view/_navbar.php');

//----------------------------------------------------
// VIEW - CONTENT
//----------------------------------------------------
//require 'contact.ct.php';

?>
<div class="container">


	<h1 class="text-main-blue">L'entrepôt</h1>
	 <div class="row ">
	 	<div class="col">
			<h4 class="text-main-blue">Visite virtuelle de l'entrepôt</h4>
	 	</div>

	</div>
	 <div class="row pb-5">
	 	<div class="col text-center">
	 		<video width="340" height="200" controls poster="">
	 			<source src="../video/entrepot02.mp4" type="video/mp4">
					<source src="" type="video/ogg">
			</video>
			<p>quai de chargements</p>
	 	</div>
	 	<div class="col text-center">
	 		<video width="340" height="200" controls poster="">
	 			<source src="../video/entrepot03.mp4" type="video/mp4">
	 			<source src="" type="video/ogg">
	 		</video>
			<p>zone internet</p>

	 	</div>
	 	<div class="col text-center">
	 		<video width="340" height="200" controls poster="">
	 			<source src="../video/entrepot04.mp4" type="video/mp4">
	 			<source src="" type="video/ogg">
	 		</video>
			<p>"filmeuse"</p>
	 	</div>
	</div>

</div>

<?php
//----------------------------------------------------
// VIEW - FOOTER
//----------------------------------------------------
require '../view/_footer-bt.php';

?>